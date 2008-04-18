<?php
/**
 * Parser
 *
 * @author halt <halt.hde@gmail.com>
 */

require_once 'NP_HatenaLike.php';

/**
 * Parser
 *
 * @author halt <halt.hde@gmail.com>
 *
 */
class Parser
{

    /**
     * Parser List
     * @var     array
     * @access  protected
     */
    var $parser = array(
        'anubis',
        'tdiary',
        'html',
        'kinowiki'
        );

    //{{{ getParserList
    /**
     * getParserList
     *
     * @return array
     */
    function getParserList()
    {
        return $this->parser;
    }
    //}}}
    
    //{{{ parseTDiary()
    /**
     * parseTDiary()
     *
     */
    function parseTDiary($str)
    {
        $output = "";
        $lines = explode("\n", $str);
        foreach ( $lines as $value) {
            $value   = trim($value);
            $buf = strip_tags($value);
            if (!empty($buf)) {
                $output .= "<p>{$value}</p>\n";
            } else {
                $output .= "{$value}\n";
            }
        }

        return $output;
    }
    //}}}

    //{{{ parseAnubis()
    /**
     * parseAnubis()
     *
     */
    function parseAnubis($str)
    {
        $nest     = 0;
        $lines    = explode("\n", $str);
        $TAGS     = "(h[1-6]|p|ul|ol|dl|blockquote|address|pre|table|div)";
        $res      = "";
        $divs     = FALSE;
        for($i = 0; $i < count($lines); $i++)
        {
            if(!preg_match("/^\s*$/", $lines[$i])) {
                
                //$B%V%m%C%/%?%0(B $B3+;O(B          
                if(preg_match("/<({$TAGS}).*?>/", $lines[$i], $result)) {
                    $nest++;          
                }

                //div$B$r3+;O$9$k!)(B div$B$N30$G$"$j!"(Bh\d$B9T!&(Bhr$B0J30$J$i3+;O(B       
                if(!$divs && !preg_match("/<h[r1-6]>/", $lines[$i]))         
                {        
                    $res .= "\n<div class=\"subsection\">\n";        
                    $divs = TRUE;        
                }        
                         
                //$B9TA^F~(B $B%V%m%C%/$N%M%9%HCf!&(Bhr$B$J$i$=$N$^$^!"$=$l0J30$J$iCJMn$K$7$F(B          
                if($nest || preg_match("/<hr.*?>/", $lines[$i])) {
                    $res .= "$lines[$i]\n";
                } else {
                    $res .= "<p>$lines[$i]</p>\n";
                }

                //div$B$r=*N;$9$k!)(B div$B$NCf$G$"$j!"<!$N9T$,6u9T!&(Bh\d$B9T!&(Bhr$B$N$$$:$l$+$J$i=*N;(B       
                if($divs && !isset($lines[$i + 1]) || preg_match("/(?:<h[r1-6]>|^\s*$)/", $lines[$i + 1]))         
                {
                    if ($nest == 0) {
                    $res .= "</div>\n";
                    $divs = FALSE;
                    }
                } 
                         
                //$B%V%m%C%/%?%0(B $B=*N;(B          
                if(preg_match("/<\/$TAGS.*?>/", $lines[$i])) {        
                    $nest--;         
                }            
            }        
        }        
             
        return $res;
    }
    //}}}
    
}
