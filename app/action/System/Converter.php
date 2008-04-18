<?php
/**
 *  System/Converter.php
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: /project/event/trunk/skel/skel.action.php 10 2006-04-29T15:04:12.368054Z halt  $
 */

/**
 *  System_Converter�ե�����μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Form_SystemConverter extends Haste_ActionForm
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
 *  System_Converter���������μ���
 *
 *  @author     halt <halt.feits@gmail.com>
 *  @access     public
 *  @package    Event
 */
class Event_Action_SystemConverter extends Ethna_ActionClass
{
    /**
     *  System_Converter����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        $ctl = $this->backend->getController();
        $plugin = $ctl->getPlugin();
        $this->system =  $plugin->getPlugin('System', 'Converter');

        $version = $this->system->getDBVersion();
        $result = $this->system->updateDB($version);

        $latest_version = $this->system->getLatestDBVersion();

        $this->af->setApp('latest_version', $latest_version);
        $this->af->setApp('version', $version);
        
        if ($result !== true) {
            var_dump($result);
        } else {
            print("upgraded from {$version}");
        }

        return null;
    }

    /**
     *  System_Converter���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        return null;
    }
}
?>
