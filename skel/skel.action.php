<?php
/**
 *	{$action_path}
 *
 *	@author		{$author}
 *	@package	Event
 *	@version	$Id: skel.action.php 2 2006-04-29 15:04:12Z halt $
 */

/**
 *	{$action_name}�ե�����μ���
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Event
 */
class {$action_form} extends Ethna_ActionForm
{
	/**
	 *	@access	private
	 *	@var	array	�ե����������
	 */
	var	$form = array(
		/*
		'sample' => array(
			'name'			=> '����ץ�',		// ɽ��̾
			'required'      => true,			// ɬ�ܥ��ץ����(true/false)
			'min'           => null,			// �Ǿ���
			'max'           => null,			// ������
			'regexp'        => null,			// ʸ�������(����ɽ��)
			'custom'        => null,			// �᥽�åɤˤ������å�
			'filter'        => null,			// �������Ѵ��ե��륿���ץ����
			'form_type'     => FORM_TYPE_TEXT,	// �ե����෿
			'type'          => VAR_TYPE_INT,	// �����ͷ�
		),
		*/
	);
}

/**
 *	{$action_name}���������μ���
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Event
 */
class {$action_class} extends Ethna_ActionClass
{
	/**
	 *	{$action_name}����������������
	 *
	 *	@access	public
	 *	@return	string		����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
	 */
	function prepare()
	{
		return null;
	}

	/**
	 *	{$action_name}���������μ���
	 *
	 *	@access	public
	 *	@return	string	����̾
	 */
	function perform()
	{
		return '{$action_name}';
	}
}
?>
