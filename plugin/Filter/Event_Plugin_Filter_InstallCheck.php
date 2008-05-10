<?php
/**
 *  Event_Plugin_Filter_InstallCheck.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Ethna
 *  @version    $Id: app.plugin.filter.default.php,v 1.2 2006/11/06 14:31:24 cocoitiban Exp $
 */

/**
 *  �¹Ի��ַ�¬�ե��륿�ץ饰����μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Ethna
 */
class Event_Plugin_Filter_InstallCheck extends Ethna_Plugin_Filter
{

    /**
     *  �¹����ե��륿
     *
     *  @access public
     */
    function preFilter()
    {
        $tmp = $this->ctl->getDirectory('tmp');

        //tmp�ǥ��쥯�ȥ�˽񤭹��߸��¤����뤫�����å�
        if (!is_writable($tmp)) {
            header('Content-type: text/html; charset=EUC-JP');
            $html = <<<EOD
<html>
  <head>
    <title>Ethna Install Error</title>
  </head>
  <body>
    <h1>Ethna Install Error</h1>
    <p><strong>����å���ǥ��쥯�ȥ�˽񤭹��߸��¤�����ޤ���({$tmp})�˽񤭹��߸��¤��ղä��Ƥ�������</strong></p>
  </body>
</html>
EOD;
            echo $html;
            exit();
        }
    }

    /**
     *  ���������¹����ե��륿
     *
     *  @access public
     *  @param  string  $action_name    �¹Ԥ���륢�������̾
     *  @return string  null:���ｪλ (string):�¹Ԥ��륢�������̾���ѹ�
     */
    function preActionFilter($action_name)
    {
        return null;
    }

    /**
     *  ���������¹Ը�ե��륿
     *
     *  @access public
     *  @param  string  $action_name    �¹Ԥ��줿���������̾
     *  @param  string  $forward_name   �¹Ԥ��줿��������󤫤�������
     *  @return string  null:���ｪλ (string):����̾���ѹ�
     */
    function postActionFilter($action_name, $forward_name)
    {
        return null;
    }

    /**
     *  �¹Ը�ե��륿
     *
     *  @access public
     */
    function postFilter()
    {
        return null;
    }
}
?>
