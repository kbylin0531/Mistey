<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/27
 * Time: 14:04
 */
namespace System\Core;
use System\Utils\Util;
defined('BASE_PATH') or die('No Permission!');

/**
 * Class Route 路由定义类
 * @package System\Core
 */
class Router{

    protected static $convention = array();

    protected static $inited = false;

    public static function init(){
        //获取静态方法调用的类名称使用get_called_class,对象用get_class
//        $clsnm = strtolower(strstr(get_called_class(),'Helper',true));//配置文件名称
        Util::mergeConf(self::$convention,Configer::load('route'),true);
        static::$inited = true;
    }

    public static function check(){

    }

    public static function parseRouteRules(){
        $conf = Configer::load('');
        self::$convention = array_merge(self::$convention,$conf);
    }

    public static function parseDirectRouteRule($url){

    }

    public static function parseIndirectRouteRule($module,$controller,$action,$param=null){

    }

}