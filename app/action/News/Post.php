<?php
/**
 *  News/Post.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Post.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  News_Post�ե�����μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_NewsPost extends Haste_ActionForm
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
        'id' => array(
            'name' => 'name',
            'required' => false,
            'form_type' => FORM_TYPE_HIDDEN,
            'type' => VAR_TYPE_STRING,
        ),
        'title' => array(
            'name' => 'Title',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'date' => array(
            'name' => 'Date',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'duedate' => array(
            'name' => 'Due Date',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),
        'description' => array(
            'name' => 'Description',
            'required' => true,
            'form_type' => FORM_TYPE_TEXTAREA,
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
 *  News_Post���������μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_NewsPost extends Ethna_AuthActionClass
{
    /**
     *  News_Post����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();
        $id = Event_Util::getPathinfoArg();

        if (is_numeric($id) && !$this->af->get('submit')) {

            $entry = $this->db->getNewsFromId($id);
            foreach ($entry as $key => $item) {
                $this->af->set($key, $item);
            }

        } else if (!$this->af->get('submit')) {
            $this->af->set('date', date('Y-m-d H:i:s'));
            $this->af->set('duedate', date('Y-m-d H:i:s'));
        }

        if ($this->af->get('submit') && ($this->af->validate() == 0)) {
           $this->db->postNews($this->af->getArray()); 
           Event_Util::redirect($this->config->get('base_url') . "/news_admin", 1, "now loading...");
        }
        return null;
    }

    /**
     *  News_Post���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        return 'news/post';
    }
}
?>
