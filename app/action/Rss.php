<?php
/**
 *  Rss.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Rss.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Rss�ե�����μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_Rss extends Haste_ActionForm
{
    /**
     *  @access private
     *  @var    array   �ե����������
     */
    var $form = array(
        /*
        'sample' => array(
            'name'          => '����ץ�',      // ɽ��̾
            'required'      => true,            // ɬ�ܥ��ץ����(true/false)
            'min'           => null,            // �Ǿ���
            'max'           => null,            // ������
            'regexp'        => null,            // ʸ�������(����ɽ��)
            'custom'        => null,            // �᥽�åɤˤ������å�
            'filter'        => null,            // �������Ѵ��ե��륿���ץ����
            'form_type'     => FORM_TYPE_TEXT,  // �ե����෿
            'type'          => VAR_TYPE_INT,    // �����ͷ�
        ),
        */
    );
}

/**
 *  Rss���������μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_Rss extends Ethna_ActionClass
{
    /**
     *  Rss����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        $id = Event_Util::getPathInfoArg();
        $this->db = $this->backend->getDB();

        if (is_numeric($id)) {
            return $this->getEvent($id);
        } elseif ($id == 'trackback') {
            return $this->getTrackback();
        }

        return null;
    }

    /**
     *  Rss���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        $recent = $this->db->getRecentEvent();

        foreach ($recent as $key => $value) {
            $recent[$key]['pubDate'] = date('r', strtotime($value['publish_date']));
            $recent[$key]['name'] = date('[Y-m-d]', strtotime($value['start_date'])) . $value['name'];
            $recent[$key]['startdate'] = date('c', strtotime($value['start_date']));
            $recent[$key]['enddate'] = date('c', strtotime($value['end_date']));
        }

        $this->af->setApp('recent', $recent);
        $this->af->setApp('title', $this->config->get('site_name'));
        
        header("Content-type: text/xml;charset=UTF-8");
        header( "Last-Modified: " . gmdate( "D, d M Y H:i:s", strtotime($recent[0]['publish_date']) ) . " GMT" );
        return 'rss';
    }

    /**
     * getEvent
     *
     * @todo FIXME
     * @param int $event_id
     */
    function getEvent($event_id)
    {
        $event = $this->db->getEventFromId($event_id);
        $attendee = $this->db->getEventAttendeeFromId($event_id);
        $comment = $this->db->getEventComments($event_id);

        //var_dump($event, $attendee, $comment);
        //var_dump($event);

        $mix = array();

        while ($row = array_pop($attendee)) {
            $key = strtotime($row['register_at']);

            $data = array();
            $data['id'] = $row['id'];
            $data['author'] = $row['account_nick'];
            $data['category'] = 'attendee';
            $data['pubDate'] = date('r', strtotime($row['register_at']));
            $data['description'] = $row['comment'];
            $data['name'] = "Joined " . $row['account_nick'] . " at " . $row['register_at'];

            $mix[$key] = $data;
        }

        while ($row = array_pop($comment)) {
            $key = strtotime($row['timestamp']);

            $data = array();
            $data['id'] = $row['id'];
            $data['author'] = $row['nick'];
            $data['category'] = 'comment';
            $data['pubDate'] = date('r', strtotime($row['timestamp']));
            $data['description'] = $row['comment'];
            $data['name'] = "Commented " . $row['nick'] . " at " . $row['timestamp'];

            $mix[$key] = $data;
        }

        krsort($mix);
        
        $this->af->setApp('event', $event);
        $this->af->setApp('detail', $mix);

        $latest_time = current($mix);
        $latest_time = $latest_time['pubDate'];

        header("Content-type: text/xml;charset=UTF-8");
        header( "Last-Modified: " . gmdate( "D, d M Y H:i:s", strtotime($latest_time) ) . " GMT" );
        return 'rss-event';
    }

    /**
     *
     */
    function getTrackback()
    {
        $this->db = $this->backend->getDB();
        $recent = $this->db->getTrackbackList(20);

        foreach ($recent as $key => $value) {
            $recent[$key]['pubDate'] = date('r', strtotime($value['receive_time']));
            $recent[$key]['title'] = $value['title'] . ' - ' . $value['blog_name'];
            $recent[$key]['pubDate'] = date('r', strtotime($value['receive_time']));
            $recent[$key]['receive_time'] = date('Y-m-d H:i:s', strtotime($value['receive_time']));
            $recent[$key]['url'] = $value['url'];
        }

        $this->af->setApp('recent', $recent);
        $this->af->setApp('title', $this->config->get('site_name'));

        header("Content-type: text/xml;charset=UTF-8");
        header( "Last-Modified: " . gmdate( "D, d M Y H:i:s", strtotime($recent[0]['receive_time']) ) . " GMT" );

        return 'rss-trackback';
    }
}
?>
