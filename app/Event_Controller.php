<?php
/**
 *  Event_Controller.php
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Event
 *  @version    $Id: Event_Controller.php 200 2007-11-23 12:36:34Z halt $
 */

/** ���ץꥱ�������١����ǥ��쥯�ȥ� */
define('BASE', dirname(dirname(__FILE__)));

define('EVENT_VERSION', '1.0.4');

// include_path������(���ץꥱ�������ǥ��쥯�ȥ���ɲ�)
$include_paths = array(
    //'system' => ini_get('include_path'), //lib��app�����ߤʤ�
    'app' => BASE . "/app",
    'lib' => BASE . "/lib",
);

ini_set('include_path', implode(PATH_SEPARATOR, $include_paths));

// �ǥե���Ȥ�ʸ�����󥳡��ǥ��󥰤λ���
mb_internal_encoding("UTF-8");

/** ���ץꥱ�������饤�֥��Υ��󥯥롼�� */
include_once('Ethna/Ethna.php');
include_once('Event_Error.php');

require_once 'Ethna_AuthActionClass.php';
require_once 'Ethna_AuthAdminActionClass.php';
require_once 'Ethna_ActionError_UTF8.php';

require_once 'Event_UserManager.php';
require_once 'Event_Util.php';
require_once 'Event_DB.php';

require_once 'Text/PukiWiki.php';

require_once 'Haste_ActionForm.php';
require_once 'Haste_ViewClass.php';

/**
 *  Event���ץꥱ�������Υ���ȥ������
 *
 *  @author     {$author}
 *  @access     public
 *  @package    Event
 */
class Event_Controller extends Ethna_Controller
{
    /**#@+
     *  @access private
     */

    /**
     *  @var    string  ���ץꥱ�������ID
     */
    var $appid = 'EVENT';

    /**
     *  @var    array   forward���
     */
    var $forward = array(
        /*
         *  TODO: ������forward��򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'index'         => array(
         *      'view_name' => 'Event_View_Index',
         *  ),
         */
    );

    /**
     *  @var    array   action���
     */
    var $action = array(
        /*
         *  TODO: ������action����򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'index'     => array(),
         */
    );

    /**
     *  @var    array   soap action���
     */
    var $soap_action = array(
        /*
         *  TODO: ������SOAP���ץꥱ��������Ѥ�action�����
         *  ���Ҥ��Ƥ�������
         *  �����㡧
         *
         *  'sample'            => array(),
         */
    );

    /**
     *  @var    array       ���ץꥱ�������ǥ��쥯�ȥ�
     */
    var $directory = array(
        'action'        => 'app/action',
        'action_xmlrpc' => 'app/action_xmlrpc',
        'app'           => 'app',
        'etc'           => 'etc',
        'filter'        => 'filter',
        'locale'        => 'locale',
        'log'           => 'log',
        'plugin'        => 'plugin',
        'plugins'       => array('app/plugin_smarty'),
        'template'      => 'template',
        'template_c'    => 'tmp',
        'tmp'           => 'tmp',
        'view'          => 'app/view',
    );

    /**
     *  @var    array       DB�����������
     */
    var $db = array(
        ''              => DB_TYPE_RW,
    );

    /**
     *  @var    array       ��ĥ������
     */
    var $ext = array(
        'php'           => 'php',
        'tpl'           => 'tpl',
    );

    /**
     *  @var    array   ���饹���
     */
    var $class = array(
        /*
         *  TODO: ���ꥯ�饹�������饹��SQL���饹�򥪡��С��饤��
         *  �������ϲ����Υ��饹̾��˺�줺���ѹ����Ƥ�������
         */
        'class'         => 'Ethna_ClassFactory',
        'backend'       => 'Ethna_Backend',
        'config'        => 'Ethna_Config',
        'db'            => 'Event_DB',
        'error'         => 'Ethna_ActionError_UTF8',
        'form'          => 'Haste_ActionForm',
        'i18n'          => 'Ethna_I18N',
        'logger'        => 'Ethna_Logger',
        'session'       => 'Ethna_Session',
        'sql'           => 'Ethna_AppSQL',
        'view'          => 'Haste_ViewClass',
    );

    /**
     *  @var    array       �ե��륿����
     */
    var $filter = array(
        /*
         *  TODO: �ե��륿�����Ѥ�����Ϥ����ˤ��Υ��饹̾��
         *  ���Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'Event_Filter_ExecutionTime',
         */
         'InstallCheck',
    );

    /**
     *  @var    array   �ޥ͡��������
     */
    var $manager = array(
        /*
         *  TODO: �����˥��ץꥱ�������Υޥ͡����㥪�֥������Ȱ�����
         *  ���Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'um'    => 'User',
         */
         'db' => 'DB',
         'user' => 'User',
    );

    /**
     *  @var    array   smarty modifier���
     */
    var $smarty_modifier_plugin = array(
        //app/plugin_smarty���ɲä��Ƥ�������
    );

    /**
     *  @var    array   smarty function���
     */
    var $smarty_function_plugin = array(
        //app/plugin_smarty���ɲä��Ƥ�������
    );

    /**
     *  @var    array   smarty prefilter���
     */
    var $smarty_prefilter_plugin = array(
        /*
         *  TODO: �����˥桼�������smarty prefilter�����򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'smarty_prefilter_foo_bar',
         */
    );

    /**
     *  @var    array   smarty postfilter���
     */
    var $smarty_postfilter_plugin = array(
        /*
         *  TODO: �����˥桼�������smarty postfilter�����򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'smarty_postfilter_foo_bar',
         */
    );

    /**
     *  @var    array   smarty outputfilter���
     */
    var $smarty_outputfilter_plugin = array(
        /*
         *  TODO: �����˥桼�������smarty outputfilter�����򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'smarty_outputfilter_foo_bar',
         */
    );

    /**#@-*/

    /**
     *  ���ܻ��Υǥե���ȥޥ�������ꤹ��
     *
     *  @access protected
     *  @param  object  Smarty  $smarty �ƥ�ץ졼�ȥ��󥸥󥪥֥�������
     */
    function _setDefaultTemplateEngine(&$smarty)
    {
        /*
         *  TODO: �����ǥƥ�ץ졼�ȥ��󥸥�ν�������
         *  ���ƤΥӥ塼�˶��̤ʥƥ�ץ졼���ѿ������ꤷ�ޤ�
         *
         *  �����㡧
         * $smarty->assign_by_ref('session_name', session_name());
         * $smarty->assign_by_ref('session_id', session_id());
         *
         * // ������ե饰(true/false)
         * $session =& $this->getClassFactory('session');
         * if ($session && $this->session->isStart()) {
         *  $smarty->assign_by_ref('login', $session->isStart());
         * }
         */
        $Config = $this->getConfig();
        $smarty->assign('site_name', $Config->get('site_name') );
        $smarty->assign('BASE_URL', $Config->get('base_url') );
    }
    
    //{{{ _getActionName_Form
    /**
     *  �ե�����ˤ���׵ᤵ�줿���������̾���֤�
     *
     *  @access protected
     *  @return string  �ե�����ˤ���׵ᤵ�줿���������̾
     */
    function _getActionName_Form()
    {
        isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO']) ?
            $arr = explode('/', $_SERVER['PATH_INFO']) :
            $arr = false;
        
        return $arr[1];
    }
    //}}}

    function getTemplateDir()
    {
        $template = $this->getDirectory('template');
        $config = $this->getConfig();

        if (is_dir($template . '/' . $config->get('theme'))) {
            return $template . '/' . $config->get('theme');
        } else {
            return $template . '/event';
        }
    }
}
?>
