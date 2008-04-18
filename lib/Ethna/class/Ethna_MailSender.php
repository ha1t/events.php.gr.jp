<?php
// vim: foldmethod=marker
/**
 *  Ethna_MailSender.php
 *
 *  @author     Masaki Fujimoto <fujimoto@php.net>
 *  @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 *  @package    Ethna
 *  @version    $Id: Ethna_MailSender.php 462 2007-04-19 14:59:38Z ichii386 $
 */

/** �᡼��ƥ�ץ졼�ȥ�����: ľ������ */
define('MAILSENDER_TYPE_DIRECT', 0);


// {{{ Ethna_MailSender
/**
 *  �᡼���������饹
 *
 *  @author     Masaki Fujimoto <fujimoto@php.net>
 *  @access     public
 *  @package    Ethna
 */
class Ethna_MailSender
{
    /**#@+
     *  @access private
     */

    /** @var    array   �᡼��ƥ�ץ졼����� */
    var $def = array(
    );

    /** @var    string  �᡼��ƥ�ץ졼�ȥǥ��쥯�ȥ� */
    var $mail_dir = 'mail';

    /** @var    int     �����᡼�륿���� */
    var $type;

    /** @var    string  �������ץ���� */
    var $option = '';

    /** @var    object  Ethna_Backend   backend���֥������� */
    var $backend;

    /** @var    object  Ethna_Config    ���ꥪ�֥������� */
    var $config;

    /**#@-*/

    /**
     *  Ethna_MailSender���饹�Υ��󥹥ȥ饯��
     *
     *  @access public
     *  @param  object  Ethna_Backend   &$backend       backend���֥�������
     */
    function Ethna_MailSender(&$backend)
    {
        $this->backend =& $backend;
        $this->config =& $this->backend->getConfig();
    }

    /**
     *  �᡼�륪�ץ��������ꤹ��
     *
     *  @access public
     *  @param  string  $option �᡼���������ץ����
     */
    function setOption($option)
    {
        $this->option = $option;
    }

    /**
     *  �᡼�����������
     *
     *  $attach �λ�����ˡ:
     *  - ��¸�Υե������ź�դ���Ȥ�
     *  <code>
     *  array('filename' => '/tmp/hoge.xls', 'content-type' => 'application/vnd.ms-excel')
     *  </code>
     *  - ʸ�����̾�����դ���ź�դ���Ȥ�
     *  <code>
     *  array('name' => 'foo.txt', 'content' => 'this is foo.')
     *  </code>
     *  'content-type' ��ά���� 'application/octet-stream' �Ȥʤ롣
     *  ʣ��ź�դ���Ȥ��Ͼ�������ź��0����Ϥޤ�դĤ������������롣
     *
     *  @access public
     *  @param  string  $to         �᡼�������襢�ɥ쥹 (null�ΤȤ����������������Ƥ� return ����)
     *  @param  string  $template   �᡼��ƥ�ץ졼��̾ or ������
     *  @param  array   $macro      �ƥ�ץ졼�ȥޥ��� or $template��MAILSENDER_TYPE_DIRECT�ΤȤ��ϥ᡼����������)
     *  @param  array   $attach     ź�եե�����
     *  @return bool|string  mail() �ؿ�������� or �᡼������
     */
    function send($to, $template, $macro, $attach = null)
    {
        // �᡼�����Ƥ����
        if ($template === MAILSENDER_TYPE_DIRECT) {
            $mail = $macro;
        } else {
            $renderer =& $this->getTemplateEngine();

            // ���ܾ�������
            $renderer->setProp("env_datetime", strftime('%Yǯ%m��%d�� %H��%Mʬ%S��'));
            $renderer->setProp("env_useragent", $_SERVER["HTTP_USER_AGENT"]);
            $renderer->setProp("env_remoteaddr", $_SERVER["REMOTE_ADDR"]);

            // �ǥե���ȥޥ�������
            $macro = $this->_setDefaultMacro($macro);

            // �桼�������������
            if (is_array($macro)) {
                foreach ($macro as $key => $value) {
                    $renderer->setProp($key, $value);
                }
            }
            if (isset($this->def[$template])) {
                $template = $this->def[$template];
            }
            $mail = $renderer->perform(sprintf('%s/%s', $this->mail_dir, $template), true);
        }
        if ($to === null) {
            return $mail;
        }

        // �᡼�����Ƥ�إå�����ʸ��ʬΥ
        $mail = str_replace("\r\n", "\n", $mail);
        list($header, $body) = $this->_parse($mail);

        // ź�եե����� (multipart)
        if ($attach !== null) {
            $attach = isset($attach[0]) ? $attach : array($attach);
            $boundary = Ethna_Util::getRandom(); 
            $body = "This is a multi-part message in MIME format.\n\n" .
                "--$boundary\n" .
                "Content-Type: text/plain; charset=iso-2022-jp\n" .
                "Content-Transfer-Encoding: 7bit\n\n" .
                "$body\n";
            foreach ($attach as $part) {
                if (isset($part['content']) === false
                    && isset($part['filename']) && is_readable($part['filename'])) {
                    $part['content'] = file_get_contents($part['filename']);
                    $part['filename'] = basename($part['filename']);
                }
                if (isset($part['content']) === false) {
                    continue;
                }
                if (isset($part['content-type']) === false) {
                    $part['content-type'] = 'application/octet-stream';
                }
                if (isset($part['name']) === false) {
                    $part['name'] = $part['filename'];
                }
                if (isset($part['filename']) === false) {
                    $part['filename'] = $part['name'];
                }
                $part['name'] = preg_replace('/([^\x00-\x7f]+)/e',
                    "Ethna_Util::encode_MIME('$1')", $part['name']); // XXX: rfc2231
                $part['filename'] = preg_replace('/([^\x00-\x7f]+)/e',
                    "Ethna_Util::encode_MIME('$1')", $part['filename']);

                $body .=
                    "--$boundary\n" .
                    "Content-Type: " . $part['content-type'] . ";\n" .
                        "\tname=\"" . $part['name'] . "\"\n" .
                    "Content-Transfer-Encoding: base64\n" . 
                    "Content-Disposition: attachment;\n" .
                        "\tfilename=\"" . $part['filename'] . "\"\n\n";
                $body .= chunk_split(base64_encode($part['content']));
            }
            $body .= "--$boundary--";
        }

        // �إå�
        if (isset($header['mime-version']) === false) {
            $header['mime-version'] = array('Mime-Version', '1.0');
        }
        if (isset($header['subject']) === false) {
            $header['subject'] = array('Subject', 'no subject in original');
        }
        if (isset($header['content-type']) === false) {
            $header['content-type'] = array(
                'Content-Type',
                $attach === null ? 'text/plain; charset=iso-2022-jp'
                                 : "multipart/mixed; \n\tboundary=\"$boundary\"",
            );
        }

        $header_line = "";
        foreach ($header as $key => $value) {
            if ($key == 'subject') {
                // should be added by mail()
                continue;
            }
            if ($header_line != "") {
                $header_line .= "\n";
            }
            $header_line .= $value[0] . ": " . $value[1];
        }

        // ���ԥ����ɤ� CRLF ��
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $body = str_replace("\n", "\r\n", $body);
        }
        $header_line = str_replace("\n", "\r\n", $header_line);

        // ����
        foreach (to_array($to) as $rcpt) {
            if (is_string($this->option)) {
                mail($rcpt, $header['subject'][1], $body, $header_line, $this->option);
            } else {
                mail($rcpt, $header['subject'][1], $body, $header_line);
            }
        }
    }

    /**
     *  ���ץꥱ��������ͭ�Υޥ�������ꤹ��
     *
     *  @access protected
     *  @param  array   $macro  �桼������ޥ���
     *  @return array   ���ץꥱ��������ͭ�����Ѥߥޥ���
     */
    function _setDefaultMacro($macro)
    {
        return $macro;
    }

    /**
     *  �ƥ�ץ졼�ȥ᡼��Υإå�������������
     *
     *  @access private
     *  @param  string  $mail   �᡼��ƥ�ץ졼��
     *  @return array   �إå�, ��ʸ
     */
    function _parse($mail)
    {
        list($header_line, $body) = preg_split('/\r?\n\r?\n/', $mail, 2);
        $header_line .= "\n";

        $header_lines = explode("\n", $header_line);
        $header = array();
        foreach ($header_lines as $h) {
            if (strstr($h, ':') == false) {
                continue;
            }
            list($key, $value) = preg_split('/\s*:\s*/', $h, 2);
            $i = strtolower($key);
            $header[$i] = array();
            $header[$i][] = $key;
            $header[$i][] = preg_replace('/([^\x00-\x7f]+)/e', "Ethna_Util::encode_MIME('$1')", $value);
        }

        $body = mb_convert_encoding($body, "ISO-2022-JP");

        return array($header, $body);
    }

    /**
     *  �᡼��ե����ޥå��ѥ����饪�֥������ȼ�������
     *
     *  @access public
     *  @return object  Ethna_Renderer  �����饪�֥�������
     */
    function &getRenderer()
    {
        $_ret_object =& $this->getTemplateEngine();
        return $_ret_object;
    }

    /**
     *  �᡼��ե����ޥå��ѥ����饪�֥������ȼ�������
     *
     *  @access public
     *  @return object  Ethna_Renderer  �����饪�֥�������
     */
    function &getTemplateEngine()
    {
        $c =& $this->backend->getController();
        $renderer =& $c->getRenderer();
        return $renderer;
    }
}
// }}}
?>
