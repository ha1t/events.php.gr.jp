<?php
/**
 *  /Logout.php
 *
 *  @author     your name
 *  @package    Event
 *  @version    $Id: Logout.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  blog_logout�ե�����μ���
 *
 *  @author     your name
 *  @access     public
 *  @package    Event
 */
class Event_Form_Logout extends Haste_ActionForm
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
            'form_type'     => FORM_TYPE_TEXT   // �ե����෿
            'type'          => VAR_TYPE_INT,    // �����ͷ�
        ),
        */
    );
}

/**
 *  blog_logout���������μ���
 *
 *  @author     your name
 *  @access     public
 *  @package    Event
 */
class Event_Action_Logout extends Ethna_ActionClass
{
    /**
     *  blog_logout����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        return null;
    }

    /**
     *  blog_logout���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        $typekey_url = $this->config->get('typekey_url');
        $typekey_token = $this->config->get('typekey_token');
    
        $this->session->destroy();

        $tk = new Auth_TypeKey();
        $tk->site_token($typekey_token);
        
        $signin_url = $tk->urlSignIn($typekey_url);
        $signout_url = $tk->urlSignOut($this->config->get('base_url'));
        
        //$this->af->setApp('signout_url', $signout_url);
        header('Location: ' . $signout_url);
        exit();
    
    }
}
?>
