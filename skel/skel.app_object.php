<?php
/**
 *	{$app_path}
 *
 *	@author		{$author}
 *	@package	Event
 *	@version	$Id: skel.app_object.php 2 2006-04-29 15:04:12Z halt $
 */

/**
 *	{$app_object}Manager
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Event
 */
class {$app_object}Manager extends Ethna_AppManager
{
}

/**
 *	{$app_object}
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Event
 */
class {$app_object} extends Ethna_AppObject
{
    /**
     *  �ץ�ѥƥ���ɽ��̾���������
     *
     *  @access public
     */
    function getName($key)
    {
        return $this->get($key);
    }
}
?>
