<?php
/**
 *  Index.php
 *
 *  @author     {$author}
 *  @package    Event
 *  @version    $Id: Index.php 199 2007-11-23 12:36:33Z halt $
 */

/**
 *  index�ե�����μ���
 *
 *  @author     {$author}
 *  @access     public
 *  @package    Event
 */
class Event_Form_Index extends Haste_ActionForm
{
    /**
     *  @access private
     *  @var    array   �ե����������
     */
    var $form = array(
        /*
         *  TODO: ���Υ�������󤬻��Ѥ���ե�����������򵭽Ҥ��Ƥ�������
         *
         *  ������(type��������Ƥ����ǤϾ�ά��ǽ)��
         *
         *  'sample' => array(
         *      'name'          => '����ץ�',      // ɽ��̾
         *      'required'      => true,            // ɬ�ܥ��ץ����(true/false)
         *      'min'           => null,            // �Ǿ���
         *      'max'           => null,            // ������
         *      'regexp'        => null,            // ʸ�������(����ɽ��)
         *      'custom'        => null,            // �᥽�åɤˤ������å�
         *      'filter'        => null,            // �������Ѵ��ե��륿���ץ����
         *      'form_type'     => FORM_TYPE_TEXT,  // �ե����෿
         *      'type'          => VAR_TYPE_INT,    // �����ͷ�
         *  ),
         */
        'start' => array(
            'name' => 'page',
            'required' => false,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_INT,
        ),
    );
}

/**
 *  index���������μ���
 *
 *  @author     {$author}
 *  @access     public
 *  @package    Event
 */
class Event_Action_Index extends Ethna_ActionClass
{
    /**
     *  index����������������
     *
     *  @access public
     *  @return string      Forward��(���ｪλ�ʤ�null)
     */
    function prepare()
    {
        return null;
    }

    /**
     *  index���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        $this->db = $this->backend->getDB();

        $this->getPager();
        
        $recent_event = $this->db->getRecentEvent(5, false, $this->offset);


        if (!$recent_event) {
            return 'index';
        }
        //strip html tag from description
        foreach ($recent_event as $key => $value) {
            $recent_event[$key]['description'] = strip_tags(preg_replace("/\(\(\(.+\)\)\)/s", "", $value['description']));
        }

        $this->af->setApp('recent_news', $this->db->getRecentNews());
        $this->af->setApp('recent_event', $recent_event);
        return 'index';
    }

    function getPager() {

        $date = date('Y-m-d H:i:s');
        $sql = "SELECT count(*) FROM event";
        $sql .= " WHERE private = 0 AND publish_date < '{$date}'";

        $this->total = $this->db->db->getOne($sql);
        $this->offset = $this->af->get('start') == null ? 0 : $this->af->get('start');
        $this->count = 5;

        $pager = Ethna_Util::getDirectLinkList($this->total, $this->offset, $this->count);
        $next = $this->offset + $this->count;
        if($next < $this->total){
            $last = ceil($this->total / $this->count);
            $this->af->setApp('hasnext', true);
            $this->af->setApp('next', $next);
            $this->af->setApp('last', ($last * $this->count) - $this->count);
        }
        $prev = $this->offset - $this->count;
        if($this->offset - $this->count >= 0){
            $this->af->setApp('hasprev', true);
            $this->af->setApp('prev', $prev);
        }
        $this->af->setApp('current', $this->offset);
        $this->af->setApp('link', '');
        $this->af->setApp('pager', $pager);
    }
}
?>
