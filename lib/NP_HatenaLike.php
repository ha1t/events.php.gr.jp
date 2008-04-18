<?php
/**
 * Nucleus Hatena Like Plugin
 *
 * @author TAKAMI Chie
 * @author halt <halt.hde@gmail.com>
 */
if (!class_exists('NucleusPlugin')) {
    class NucleusPlugin {}
}

if (!function_exists('removeBreaks')){
  function removeBreaks($var) {     return preg_replace("/<br \/>([\r\n])/","$1",$var); }
}

/**
 * NP_HatenaLike
 *
 * @author TAKAMI Chie
 * @author halt <halt.hde@gmail.com>
 *
 */
class NP_HatenaLike extends NucleusPlugin {

  function getName() { return 'HatenaLike';}

  function getAuthor()  { return '�����ߤ���'; }

  function getURL()   { return 'http://Onpu.jpn.ch/'; }

  function getVersion() { return '0.1'; }

  function getDescription() { return '�ϤƤʤäݤ����Ҥ�ͭ���ˤ��ޤ����ƹԤ�p�ǰϤ���ꡢ' .
    '���Ԥ��̣����ζ��ڤ�Ȥ���div�Ƕ��ڤä��ꡢ���ϰϤˤ�����ʤ��Ȥ��äƤ���ޤ���'; }

  // �褯ʬ����ʤ��ΤǤ��Τޤ�
  function supportsFeature($what) {
    switch($what){
      case 'SqlTablePrefix':
        return 1;
      default:
        return 0;
    }
  }

  function getEventList() { return array('PreItem'); }

  function event_PreItem(&$data) {
    $this->currentItem = &$data["item"];
    $this->convert_hltags($this->currentItem->body);
    if($this->currentItem->more)
      $this->convert_hltags($this->currentItem->more);
  }

    /**
     * convert_hltags
     *
     * @todo fix pre
     */
    function convert_hltags(&$text) {
        $text = removeBreaks($text);
        
        //�ϥ��ѡ��֥�å���
        // >> xxx << �ǰ���
        $text = preg_replace("/(?:[\r\n]+)?>>[\r\n]+(.*?)[\r\n]+<<(?:[\r\n]+)?/s",
            "<blockquote>\n$1\n</blockquote>\n", $text);
        $text = preg_replace("/(?:[\r\n]+)?><!--[\r\n]+(.*?)[\r\n]+--><(?:[\r\n]+)?/s",
            "\n", $text); # ����
            /*
        $text = preg_replace("/(?:[\r\n]+)?>>|![\r\n]+(.*?)[\r\n]+|<<(?:[\r\n]+)?/s",
            "<pre>\n$1\n</pre>\n", $text); # pre
*/
    //$text = preg_replace("/^(?!-|\+|\*|\.|:|<>|��|\s)(.+?)$/m", "<p>$1</p>", $text);
        //����ǰϤ�(���饹����)
        $text = preg_replace("/^\.(\w+)\s?(.+)$/m", "<p class=\"$1\">$2</p>", $text);

        //���Ѱ���
        $text = preg_replace("/^>(\w+)\s?(.+)$/m", "<p class=\"quote\">$2</p>", $text);
        
        $text = preg_replace("/^----$/m", "<hr />", $text);                              # hr
        $text = preg_replace("/^-(.+)$/m", "<li class=\"ul\">$1</li>", $text);           # ul�Υꥹ��
        $text = preg_replace("/^\+(.+)$/m", "<li class=\"ol\">$1</li>", $text);          # li�Υꥹ��
        $text = preg_replace("/^\+(.+)$/m", "<li class=\"ol\">$1</li>", $text);          # li�Υꥹ��
    $text = preg_replace("/^:(.+?):(.+?)$/m", "<dt>$1</dt><dd>$2</dd>", $text);         # dt, dd�Υꥹ��
    $text = preg_replace_callback("/^(\*{1,3})(.+)$/m",
      create_function('$matches', '$hc = strlen($matches[1]) + 4 - 1; ' .
      'return "<h$hc>$matches[2]</h$hc>";'), $text);
    $text = preg_replace("/((?:<li\sclass=\"ul\">.+<\/li>\n?)+)/", "<ul>\n$1</ul>\n", $text); # ul�ǰϤ�
    $text = preg_replace("/((?:<li\sclass=\"ol\">.+<\/li>\n?)+)/", "<ol>\n$1</ol>\n", $text); # ol�ǰϤ�
    $text = preg_replace("/((?:<dt>.+<\/dt><dd>.+<\/dd>\n?)+)/", "<dl>\n$1</dl>\n", $text);   # dl�ǰϤ�

    //������ղä���
    $nest     = 0;
    $lines    = explode("\n", $text);
    $TAGS     = "(h[1-6]|p|ul|ol|dl|blockquote|address|pre|table|div)";
    $res      = "";
    $divs     = FALSE;
    for($i = 0; $i < count($lines); $i++)
    {
      if(!preg_match("/^\s*$/", $lines[$i])) {
        
        //�֥�å����� ����
        if(preg_match("/<$TAGS.*?>/", $lines[$i])) {
            $nest++;
        }    
        
        //div�򳫻Ϥ��롩 div�γ��Ǥ��ꡢh\d�ԡ�hr�ʳ��ʤ鳫��
        if(!$divs && !preg_match("/<h[r1-6]>/", $lines[$i]))
        {
          $res .= "\n<div class=\"subsection\">\n";
          $divs = TRUE;
        }
        
        //������ �֥�å��Υͥ����桦hr�ʤ餽�Τޤޡ�����ʳ��ʤ�����ˤ���
        if($nest || preg_match("/<hr.*?>/", $lines[$i])) {
          $res .= "$lines[$i]\n";
        } else {
          $res .= "<p>$lines[$i]</p>\n";
        }
        
        //div��λ���롩 div����Ǥ��ꡢ���ιԤ����ԡ�h\d�ԡ�hr�Τ����줫�ʤ齪λ
        if($divs && preg_match("/(?:<h[r1-6]>|^\s*$)/", $lines[$i + 1])) {
          $res .= "</div>\n";
          $divs = FALSE;
        }
        
        //�֥�å����� ��λ
        if(preg_match("/<\/$TAGS.*?>/", $lines[$i])) {
            $nest--;
        }    
      }
    }
    $text = $res;

    //����饤�����1
    # ��Ĵ��
    $text = preg_replace("/\[!!\s*(.+?)\s*!!\]/", "<strong>$1</strong>", $text);          # [!! !!]->strong
    $text = preg_replace("/\[!_\s*(.+?)\s*_!\]/", "<em class=\"warn\">$1</em>", $text);   # [!_ _!]->em.warn
    $text = preg_replace("/\[!\s*(.+?)\s*!\]/", "<em>$1</em>", $text);                    # [! !]->em
    # ����¾�ΰ�̣����
    $text = preg_replace("/\[<\s*(.+?)\s*>\]/", "<dfn>$1</dfn>", $text);                  # [< >]->dfn
    $text = preg_replace("/\[\*\s*(.+?)\s*\*\]/", "<kbd>$1</kbd>", $text);                # [* *]->kbd
    $text = preg_replace("/\[\"\s*(.+?)\s*\"\]/", "<q>$1</q>", $text);                    # [" "]->q
    $text = preg_replace("/\[&\s*(.+?)\s*&\]/", "<code>$1</code>", $text);                # [& &]->code
    $text = preg_replace("/\[x\s*(.+?)\s*x\]/", "<samp>$1</samp>", $text);                # [x x]->samp
    $text = preg_replace("/\[\$\s*(.+?)\s*\$\]/", "<var>$1</var>", $text);                # [$ $]->var
    # ��������Ӻ��
    $text = preg_replace("/\[__\s*(.+?)\s*__\]/", "<ins>$1</ins>", $text);
    $text = preg_replace("/\[--\s*(.+?)\s*--\]/", "<del>$1</del>", $text);
    ## �ʲ�����ä��ü�ʥ���
    $text = preg_replace("/\['(.+?)::(.+?)\]/", "<abbr title=\"$2\">$1</abbr>", $text);  # abbr
    $text = preg_replace("/\[::(.+?)::(.+?)?\]/", "<a href=\"$1\">$2</a>", $text);       # ���
    $text = preg_replace("/\[r:(.+?)::(.+?)\]/", "<ruby><rb>$1</rb><rp>(</rp><rt>$2</rt><rp>)</rp></ruby>", $text);
    $text = preg_replace("/([^=^\"]|^)((?:ht|f)tp:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#]+)/", "$1<a href=\"$2\">$2</a>", $text);
    $text = preg_replace("/([\w\.\-]+)\@([\w\.\-]+)/", "<a href=\"mailto\:$1\@$2\">$1\@$2</a>", $text);
}

}
?>
