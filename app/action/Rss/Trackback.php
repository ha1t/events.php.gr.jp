<?php
/**
 *	Rss/Trackback.php
 *
 *	@author		{$author}
 *	@package	Event
 *	@version	$Id: skel.action.php 2 2006-04-29 15:04:12Z halt $
 */

/**
 *	rss_trackback�ե�����μ���
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Event
 */
class Event_Form_RssTrackback extends Ethna_ActionForm
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
 *	rss_trackback���������μ���
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Event
 */
class Event_Action_RssTrackback extends Ethna_ActionClass
{
	/**
	 *	rss_trackback����������������
	 *
	 *	@access	public
	 *	@return	string		����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
	 */
	function prepare()
	{
		return null;
	}

	/**
	 *	rss_trackback���������μ���
	 *
	 *	@access	public
	 *	@return	string	����̾
	 */
	function perform()
	{
        $this->db = $this->backend->getDB();
        $recent = $this->db->getTrackbackList(20);

        foreach ($recent as $key => $value) {
            $recent[$key]['pubDate'] = date('r', strtotime($value['receive_time']));
            $recent[$key]['title'] = $value['title'] . ' - ' . $value['blog_name'];
            $recent[$key]['receive_time'] = date('[Y-m-d]', strtotime($value['receive_time']));
            $recent[$key]['url'] = $value['url'];
        }

        $this->af->setApp('recent', $recent);
        $this->af->setApp('title', $this->config->get('site_name'));

        header("Content-type: text/xml;charset=UTF-8");
        header( "Last-Modified: " . gmdate( "D, d M Y H:i:s", strtotime($recent[0]['publish_date']) ) . " GMT" );

        return 'rss_trackback';
	}
}
?>
