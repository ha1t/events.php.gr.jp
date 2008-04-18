<?php
/**
 *  Event/CommentDelete.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: CommentDelete.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  Event_CommentDelete�ե�����μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_EventCommentDelete extends Haste_ActionForm
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
        'submit' => array(
            'name' => 'Submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),

    );
}

/**
 *  Event_CommentDelete���������μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_EventCommentDelete extends Ethna_AuthAdminActionClass
{
    /**
     *  Event_CommentDelete����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();

        $id = intval(Event_Util::getPathinfoArg());

        if ($this->af->get('submit') && $this->af->validate() == 0) {
            return null;
        } else {
            $this->af->setApp('comment', $this->db->getEventComment($id));
            return 'event-comment-delete';
        }
    }

    /**
     *  Event_CommentDelete���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        $id = intval(Event_Util::getPathinfoArg());
        $this->db->deleteCommentFromEvent($id);

        $url = $this->config->get('base_url');
        Event_Util::redirect($url , 2, 'Comment Deleted');
        
    }
}
?>
