<?php
/**
 *	Event_Filter_ExecutionTime.php
 *
 *	@author		{$author}
 *	@package	Event
 *	@version	$Id: Event_Filter_ExecutionTime.php 2 2006-04-29 15:04:12Z halt $
 */

/**
 *	�¹Ի��ַ�¬�ե��륿�μ���
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Event
 */
class Event_Filter_ExecutionTime extends Ethna_Filter
{
	/**#@+
	 *	@access	private
	 */

	/**
	 *	@var	int		���ϻ���
	 */
	var	$stime;

	/**#@-*/


	/**
	 *	�¹����ե��륿
	 *
	 *	@access	public
	 */
	function preFilter()
	{
		$stime = microtime(true);
		$this->stime = $stime;
	}

	/**
	 *	�¹Ը�ե��륿
	 *
	 *	@access	public
	 */
	function postFilter()
	{
		$etime = microtime(true);
		$time   = round(($etime - $this->stime), 4);

		print "\n<!-- page was processed in $time seconds -->\n";
	}
}
?>
