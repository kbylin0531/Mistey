<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/6
 * Time: 9:13
 */
use System\Mist;
/**
 * 基础目录定义
 */
define('BASE_PATH',str_replace('\\','/',dirname(__DIR__)).'/');

/**
 * URL模式
 */
const URLMODE_COMMON = 1;//最快速的URL访问速度
const URLMODE_PATHINFO = 2;
const URLMODE_COMPATIBLE = 3;//兼容模式
/**
 * 存储系统模式
 */
const STORAGEMODE_COMMON = 'Common';
const STORAGEMODE_SAE = 'Sae';
/**
 * 日志频率
 * LOGRATE_DAY  每天一个文件的日志频率
 * LOGRATE_HOUR 每小时一个文件的日志频率，适用于较频繁的访问
 */
const LOGRATE_HOUR = 0;
const LOGRATE_DAY = 1;


include_once BASE_PATH.'System/Mist.class.php';
Mist::init(array(
    'URL_MODE'          => URLMODE_PATHINFO,
    'TIME_ZONE'         => 'Asia/Shanghai',
    'APP_NAME'          => 'WebManagement',
    'LOG_RATE'          => LOGRATE_DAY,

    'DEBUG_MODE_ON'         => true,
    'URLMODE_TOPSPEED_ON'   => false,
    'REWRITE_ENGINE_ON'     => false,
    'PAGE_TRACE_ON'         => true,
    'URL_ROUTE_ON'          => true,
    'AUTO_CHECK_CONFIG_ON'  => true,

    'TEMPLATE_ENGINE'       => 'Shuttle',
));

Mist::start();


