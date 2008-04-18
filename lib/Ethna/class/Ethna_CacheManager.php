<?php
// vim: foldmethod=marker tabstop=4 shiftwidth=4 autoindent
/**
 *  Ethna_CacheManager.php
 *
 *  @author     Masaki Fujimoto <fujimoto@php.net>
 *  @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 *  @package    Ethna
 *  @version    $Id: Ethna_CacheManager.php 464 2007-07-04 13:29:34Z ichii386 $
 */

/**
 *  ����å���ޥ͡����㥯�饹
 *
 *  @author     Masaki Fujimoto <fujimoto@php.net>
 *  @access     public
 *  @package    Ethna
 */
class Ethna_CacheManager
{
    /**
     *  Cachemaanger �ץ饰����Υ��󥹥��󥹤��������
     *
     *  @param  string  $type   ����å��奿����('localfile', 'memcache'...)
     *  @return object  Ethna_Plugin_CacheMaanger   Cachemanager �ץ饰����Υ��󥹥���
     *  @access public
     */
    function &getInstance($type)
    {
        $controller =& Ethna_Controller::getInstance();
        $plugin =& $controller->getPlugin();

        $cache_manager =& $plugin->getPlugin('Cachemanager', ucfirst($type));

        return $cache_manager;
    }
}
?>
