<?php
/**
 *  Event_Filter_ExecutionTime.php
 *
 *  @author     your name
 *  @package    Event
 *  @version    $Id: Event_Filter_Header.php 135 2006-08-17 05:13:49Z ha1t $
 */

/**
 *  �¹Ի��ַ�¬�ե��륿�μ���
 *
 *  @author     your name
 *  @access     public
 *  @package    Event
 */
class Event_Filter_Header extends Ethna_Filter
{
    /**#@+
     *  @access private
     */

    /**
     *  @var    int     ���ϻ���
     */
    var $stime;

    /**#@-*/


    /**
     *  �¹����ե��륿
     *
     *  @access public
     */
    function preFilter()
    {
        //mb_http_output("UTF-8");
        //ob_start('mb_output_handler');
        header("Content-type: text/html; charset=UTF-8");
    }

    /**
     *  �¹Ը�ե��륿
     *
     *  @access public
     */
    function postFilter()
    {
        //ob_end_flush();
    }
}
?>
