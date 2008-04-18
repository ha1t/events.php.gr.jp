<?php
/**
 *  News/Show.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Show.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  News_Show�ե�����μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_NewsShow extends Haste_ActionForm
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
 *  News_Show���������μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_NewsShow extends Ethna_ActionClass
{
    /**
     *  News_Show����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        $this->db = $this->backend->getDB();

        $path_info = explode('/', $_SERVER['PATH_INFO']);

        if (isset($path_info[2])) {
            $id = $path_info[2];
            $this->af->setApp('news', $this->db->getNewsFromId($id));
        }

        return null;
    }

    /**
     *  News_Show���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        return 'news/show';
    }
}
?>
