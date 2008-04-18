<?php
/**
 *  Event/Admin.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Admin.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Event_Admin�ե�����μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventAdmin extends Haste_ActionForm
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
 *  Event_Admin���������μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventAdmin extends Ethna_AuthAdminActionClass
{
    /**
     *  Event_Admin����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();
        return null;
    }

    /**
     *  Event_Admin���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        $recent_event = $this->db->getRecentEvent(50, true);
        $this->af->setApp('recent_event', $recent_event);
        return 'event/admin';
    }
}
?>
