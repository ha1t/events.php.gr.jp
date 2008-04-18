<?php
/**
 *  Event/Page.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Page.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Event_Page�ե�����μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventPage extends Haste_ActionForm
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
            'name' => 'EventId',
            'required' => true,
            'form_type' => FORM_TYPE_HIDDEN,
            'type' => VAR_TYPE_INT,
        ),

        'content' => array(
            'name' => 'Content',
            'required' => false,
            'form_type' => FORM_TYPE_TEXTAREA,
            'type' => VAR_TYPE_STRING,
        ),

        'preview' => array(
            'name' => 'Preview',
            'required' => false,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),

        'submit' => array(
            'name' => 'Submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),
    );
}

/**
 *  Event_Page���������μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventPage extends Ethna_AuthActionClass
{
    /**
     *  Event_Page����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();

        if ($this->af->get('submit') && $this->af->validate() == 0) {

            return null;

        } else if ($this->af->get('preview')) {

            $this->af->setAppNE('content', $this->af->get('content'));
            return 'event_page';

        } else {

            if (!$this->af->get('event_id')) {

                $event_id = Event_Util::getPathinfoArg();
                $event = $this->db->getEventFromId($event_id);
                $record = $this->db->getEventPageFromEventId($event_id);

                if (count($record) != 0) {
                    $this->af->set('event_id', $record['event_id']);
                    $this->af->set('content', $record['content']);
                    $this->af->setAppNE('content', $record['content']);
                } else {
                    $this->af->set('event_id', $event_id);
                }
            }

            return 'event_page';

        }
    }

    /**
     *  Event_Page���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        $param = array(
            'event_id' => $this->af->get('event_id'),
            'author' => $_SESSION['name'],
            'content' => $this->af->get('content'),
            'timestamp' => time(),
        );

        $this->db->postEventPage($param);

        $url = $this->config->get('base_url') . "/event_show/{$this->af->get('event_id')}";
        Event_Util::redirect($url, 1, "now loading...");
    }
}
?>
