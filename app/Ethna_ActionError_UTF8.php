<?php
require_once 'Ethna/class/Ethna_ActionError.php';
class Ethna_ActionError_UTF8 extends Ethna_ActionError
{
    /**
     *  ���ץꥱ������󥨥顼��å��������������
     *
     *  @access private
     *  @param  array   ���顼����ȥ�
     *  @return string  ���顼��å�����
     */
    function _getMessage(&$error)
    {
        $af =& $this->_getActionForm();
        $form_name = $af->getName($error['name']);
        $form_name = mb_convert_encoding($form_name, 'EUC-JP', 'UTF-8');
        $result = str_replace("{form}", $form_name, $error['object']->getMessage());
        return mb_convert_encoding($result, 'UTF-8', 'EUC-JP');
    }
}
?>
