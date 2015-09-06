<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/26
 * Time: 14:49
 */
return array(
    //普通模式获取变量
    'URL_MODULE_VARIABLE'   => 'm',
    'URL_CONTROLLER_VARIABLE'   => 'c',
    'URL_ACTION_VARIABLE'   => 'a',
    //兼容模式获取变量
    'URL_COMPATIBLE_VARIABLE' => 'pathinfo',

    //兼容模式和PATH_INFO模式下的解析配置，也是URL生成配置
    'MM_BRIDGE'     => '/',//模块与模块之间的连接桥
    'MC_BRIDGE'     => '/',
    'CA_BRIDGE'     => '/',
    'AP_BRIDGE'     => '_',//*** 操作与控制器之间的符号将是第一个出现的
    'PP_BRIDGE'     => '-',//参数与参数之间的连接桥
    'PKV_BRIDGE'    => '_',//参数的键值对之前的连接桥

    //伪装的后缀，不包括'.'号
    'MASQUERADE_TAIL'   => 'html',
    'REWRITE_HIDDEN'      => '/index.php',

    //参数缺失时
    'DEFAULT_MODULE'      => 'Home',
    'DEFAULT_CONTROLLER'  => 'Index',
    'DEFAULT_ACTION'      => 'index',

    'DOMAIN_DEPLOY_ON'    => true,
    //完整域名
    'FUL_DOMAIN'=>'minshuttler.com',
    //子域名部署规则
    'SUB_DOMAIN_DEPLOY_RULES' => array(
        //正式的URL规则是从前往后一次是  [Modulelist,Controller,Action,Query]，如果某一段不想设置却需要设置之后的部分，就需要将不想设置的地方设置为NULL
        'news'=>array('Admin/User','Name','look','a=1&b=2&c=3'),//测试子域名映射
        'sports'=>'http://www.qq.com',//可能会影响执行速度
    ),

);