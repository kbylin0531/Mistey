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
 * Class Route 路由解析类
 * @package System\Core
 */
class Router{

    protected static $convention = array(
        //直接路由发生在URL解析之前，直接路由如果匹配了URL字符串，则直接链接到指定的模块，否则将进行URL解析和间接路由
        'DIRECT_ROUTE_RULES'    => array(

        ),
        //间接路由在URL解析之后
        'INDIRECT_ROUTE_RULES'   => array(

        ),
    );

    protected static $inited = false;

    public static function init(){
        //获取静态方法调用的类名称使用get_called_class,对象用get_class
//        $clsnm = strtolower(strstr(get_called_class(),'Helper',true));//配置文件名称
        Util::mergeConf(self::$convention,Configer::load('route'),true);
        static::$inited = true;
    }

    public static function check(){

    }

    public static function parseIndirectRouteRule(){
        $conf = Configer::load('');
        self::$convention = array_merge(self::$convention,$conf);
    }

    public static function parseDirectRouteRule($url){

    }


}