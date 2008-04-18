<?php
/**
 *  Event/Join.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Join.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Event_Join�ե�����μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventJoin extends Haste_ActionForm
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
        'event_id' => array(
            'name' => 'id',
            'required' => true,
            'type' => VAR_TYPE_INT,
        ),
        'join_comment' => array(
            'name' => 'Comment',
            'required' => false,
            'type' => VAR_TYPE_STRING,
        ),
        'join' => array(
            'name' => 'submit',
            'required' => true,
            'type' => VAR_TYPE_STRING,
        ),
    );
}

/**
 *  Event_Join���������μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventJoin extends Ethna_AuthActionClass
{
    /**
     *  Event_Join����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        if ($this->af->validate() > 0) {
            return 'error';
        }

        $this->db = $this->backend->getDB();

        return null;
    }

    /**
     *  Event_Join���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        $param = $this->af->getArray();
        $param['comment'] = $param['join_comment'];
        $param['account_name'] = $_SESSION['name'];
        $param['account_nick'] = $_SESSION['nick'];
        $param['register_at'] = date('Y-m-d H:i:s');

        $this->db->AutoExecute('event_attendee', $param, 'INSERT');

        Event_Util::redirect(
            $this->config->get('base_url') . "/event_show/{$param['event_id']}",
            2,
            'joined event'
        );
    }

}
?>
