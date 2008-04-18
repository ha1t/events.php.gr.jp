<?php
/**
 *  Ethna_UnitTestManager.php
 *
 *  @author     Takuya Ookubo <sfio@sakura.ai.to>
 *  @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 *  @package    Ethna
 *  @version    $Id: Ethna_UnitTestManager.php 471 2007-07-12 11:27:25Z mumumu-org $
 */

require_once('simpletest/unit_tester.php');
require_once('Ethna_UnitTestCase.php');
require_once('Ethna_UnitTestReporter.php');

/**
 *  Ethna��˥åȥƥ��ȥޥ͡����㥯�饹
 *
 *  @author     Takuya Ookubo <sfio@sakura.ai.to>
 *  @access     public
 *  @package    Ethna
 */
class Ethna_UnitTestManager extends Ethna_AppManager
{
    /** @var    object  Ethna_Controller    ����ȥ��饪�֥������� */
    var $ctl;

    /** @var    array                       ���̥ƥ��ȥ�������� */
    var $testcase = array();

    /**
     *  Ethna_UnitTestManager�Υ��󥹥ȥ饯��
     *
     *  @access public
     *  @param  object  Ethna_Backend   &$backend   Ethna_Backend���֥�������
     */
    function Ethna_UnitTestManager(&$backend)
    {
        parent::Ethna_AppManager($backend);
        $this->ctl =& Ethna_Controller::getInstance();
        $this->class_factory =& $this->ctl->getClassFactory();
    }

    /**
     *  ����Ѥߥ��������������������
     *
     *  @access public
     *  @return array   ������������
     */
    function _getActionList()
    {
        $im =& new Ethna_InfoManager($this->backend);
        return $im->getActionList();
    }

    /**
     *  ���饹̾����ӥ塼̾���������
     *
     *  @access public
     *  @param  string  $class_name     �ӥ塼���饹̾
     *  @return string  ���������̾
     */
    function viewClassToName($class_name)
    {
        $prefix = sprintf("%s_View_", $this->ctl->getAppId());
        if (preg_match("/$prefix(.*)/", $class_name, $match) == 0) {
            // �����ʥ��饹̾
            return null;
        }
        $target = $match[1];

        $action_name = substr(preg_replace('/([A-Z])/e', "'_' . strtolower('\$1')", $target), 1);

        return $action_name;
    }

    /**
     *  ���ꤵ�줿���饹̾��Ѿ����Ƥ��뤫�ɤ������֤�
     *
     *  @access private
     *  @param  string  $class_name     �����å��оݤΥ��饹̾
     *  @param  string  $parent_name    �ƥ��饹̾
     *  @return bool    true:�Ѿ����Ƥ��� false:���ʤ�
     */
    function _isSubclassOf($class_name, $parent_name)
    {
        while ($tmp = get_parent_class($class_name)) {
            if (strcasecmp($tmp, $parent_name) == 0) {
                return true;
            }
            $class_name = $tmp;
        }
        return false;
    }

    /**
     *  �ӥ塼������ץȤ���Ϥ���
     *
     *  @access private
     *  @param  string  $script �ե�����̾
     *  @return array   �ӥ塼���饹�������
     */
    function __analyzeViewScript($script)
    {
        $class_list = array();

        $source = "";
        $fp = fopen($script, 'r');
        if ($fp == false) {
            return null;
        }
        while (feof($fp) == false) {
            $source .= fgets($fp, 8192);
        }
        fclose($fp);

        // �ȡ������ʬ�䤷�ƥ��饹�����������
        $token_list = token_get_all($source);
        for ($i = 0; $i < count($token_list); $i++) {
            $token = $token_list[$i];

            if ($token[0] == T_CLASS) {
                // ���饹�������
                $i += 2;
                $class_name = $token_list[$i][1];       // should be T_STRING
                if ($this->_isSubclassOf($class_name, 'Ethna_ViewClass')) {
                    $view_name = $this->viewClassToName($class_name);
                    $class_list[$view_name] = array(
                        'template_file' => $this->ctl->_getForwardPath($view_name),
                        'view_class' => $class_name,
                        'view_class_file' => $this->ctl->getDefaultViewPath($view_name),
                    );
                }
            }
        }

        if (count($class_list) == 0) {
            return null;
        }
        return $class_list;
    }

    /**
     *  �ǥ��쥯�ȥ�ʲ��Υӥ塼������ץȤ���Ϥ���
     *
     *  @access private
     *  @param  string  $action_dir     �����оݤΥǥ��쥯�ȥ�
     *  @return array   �ӥ塼���饹�������
     */
    function __analyzeViewList($view_dir = null)
    {
        $r = array();

        if (is_null($view_dir)) {
            $view_dir = $this->ctl->getViewdir();
        }
        $prefix_len = strlen($this->ctl->getViewdir());

        $ext = '.' . $this->ctl->getExt('php');
        $ext_len = strlen($ext);

        $dh = opendir($view_dir);
        if ($dh) {
            while (($file = readdir($dh)) !== false) {
                $path = "$view_dir/$file";
                if ($file != '.' && $file != '..' && is_dir($path)) {
                    $tmp = $this->__analyzeViewList($path);
                    $r = array_merge($r, $tmp);
                    continue;
                }
                if (substr($file, -$ext_len, $ext_len) != $ext) {
                    continue;
                }

                include_once($path);
                $class_list = $this->__analyzeViewScript($path);
                if (is_null($class_list) == false) {
                    $r = array_merge($r, $class_list);
                }
            }
        }
        closedir($dh);

        return $r;
    }

    /**
     *  ����Ѥߥӥ塼�������������
     *
     *  @access public
     *  @return array   �ӥ塼����
     */
    function _getViewList()
    {
        $im =& new Ethna_InfoManager($this->backend);
//        $view_class_list = array_keys($im->getForwardList());

        $r = array();

        // �ƥ�ץ졼��/�ӥ塼������ץȤ���Ϥ���
        $forward_list = $im->_analyzeForwardList();
        $view_list = $this->__analyzeViewList();

        // �ӥ塼�������ȥ����
        $manifest_forward_list = $im->_getForwardList_Manifest($forward_list);

        // �ӥ塼�����ά����ȥ����
        $implicit_forward_list = $im->_getForwardList_Implicit($forward_list, $manifest_forward_list);

        $r = array_merge($view_list, $manifest_forward_list, $implicit_forward_list);
        ksort($r);

        return $r;
    }

    /**
     *  ���������ƥ��ȥ��饹���������
     *
     *  @access private
     *  @return array
     */
    function _getTestAction()
    {
        $action_class_list = array_keys($this->_getActionList());

        // �ƥ��Ȥ�¸�ߤ��륢�������
        foreach ($action_class_list as $key => $action_name) {
            $action_class = $this->ctl->getDefaultActionClass($action_name, false).'_TestCase';
            if (!class_exists($action_class)) {
                unset($action_class_list[$key]);
            }
        }

        return $action_class_list;
    }

    /**
     *  �ӥ塼�ƥ��ȥ��饹���������
     *
     *  @access private
     *  @return array
     */
    function _getTestView()
    {
        $view_class_list = array_keys($this->_getViewList());

        // �ƥ��Ȥ�¸�ߤ���ӥ塼
        foreach ($view_class_list as $key => $view_name) {
            $view_class = $this->ctl->getDefaultViewClass($view_name, false).'_TestCase';
            if (!class_exists($view_class)) {
                unset($view_class_list[$key]);
            }
        }

        return $view_class_list;
    }

    /**
     *  ��˥åȥƥ��Ȥ�¹Ԥ���
     *
     *  @access private
     *  @return mixed   0:���ｪλ Ethna_Error:���顼
     */
    function run()
    {
        $action_class_list = $this->_getTestAction();
        $view_class_list = $this->_getTestView();

        $test =& new GroupTest("Ethna UnitTest");

        // ���������
        foreach ($action_class_list as $action_name) {
            $action_class = $this->ctl->getDefaultActionClass($action_name, false).'_TestCase';
            $action_form = $this->ctl->getDefaultFormClass($action_name, false).'_TestCase';

            $test->addTestCase(new $action_class($this->ctl));
            $test->addTestCase(new $action_form($this->ctl));
        }

        // �ӥ塼
        foreach ($view_class_list as $view_name) {
            $view_class = $this->ctl->getDefaultViewClass($view_name, false).'_TestCase';

            $test->addTestCase(new $view_class($this->ctl));
        }

        // ����
        foreach ($this->testcase as $class_name => $file_name) {
            $dir = $this->ctl->getBasedir().'/';
            include_once $dir . $file_name;
            $testcase_name = $class_name.'_TestCase';
            $test->addTestCase(new $testcase_name($this->ctl));
        }

        // ActionForm�ΥХå����å�
        $af =& $this->ctl->getActionForm();

        //���Ϥ����������ˤ��碌���ڤ��ؤ���
        $reporter = new Ethna_UnitTestReporter();
        $test->run($reporter);

        // ActionForm�Υꥹ�ȥ�
        $this->ctl->action_form =& $af;
        $this->backend->action_form =& $af;
        $this->backend->af =& $af;

        return array($reporter->report, $reporter->result);
    }
}
?>
