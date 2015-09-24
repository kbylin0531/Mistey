<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/22
 * Time: 11:10
 */
namespace System\Core;
use System\Util\SEK;

/**
 * Class Security
 * @package System\Core
 * @subpackage	Libraries
 * @category	Security
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/security.html
 */
class Security{
    /**
     * 惯例配置
     * @var array
     */
    private static $_convention = array(
    );


    private static $_has_inited = false;

    /**
     * 初始化安全类
     * @param array $config 配置数组
     * @throws \System\Exception\ConfigLoadFailedException
     * @throws \System\Exception\ParameterInvalidException
     */
    public static function init($config=null){
        if(!self::$_has_inited ){
            isset($config) or $config = Configer::load('security');
            SEK::merge(self::$_convention,$config);
            if (self::$_convention['cookie_prefix']){
                self::$_convention['csrf_cookie_name'] =
                    self::$_convention['cookie_prefix'].self::$_convention['csrf_cookie_name'];
            }
            self::$_has_inited = true;
        }
    }

    public static function genToken(){

    }



}