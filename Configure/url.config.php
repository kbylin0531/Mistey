<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/26
 * Time: 14:49
 */
return array(
    //普通模式 与 兼容模式 获取$_GET变量名称
    'URL_MODULE_VARIABLE'   => 'm',
    'URL_CONTROLLER_VARIABLE'   => 'c',
    'URL_ACTION_VARIABLE'   => 'a',
    'URL_COMPATIBLE_VARIABLE' => 'pathinfo',

    //兼容模式和PATH_INFO模式下的解析配置，也是URL生成配置
    'MM_BRIDGE'     => '+',//模块与模块之间的连接桥
    'MC_BRIDGE'     => '/',
    'CA_BRIDGE'     => '/',
    'AP_BRIDGE'     => '-',//*** 必须保证操作与控制器之间的符号将是$_SERVER['PATH_INFO']字符串中第一个出现的
    'PP_BRIDGE'     => '-',//参数与参数之间的连接桥
    'PKV_BRIDGE'    => '-',//参数的键值对之前的连接桥

    //伪装的后缀，不包括'.'号
    'MASQUERADE_TAIL'   => '.html',
    //重写模式下 消除的部分，对应.htaccess文件下
    'REWRITE_HIDDEN'      => '/index.php',

    //默认的模块，控制器和操作
    'DEFAULT_MODULE'      => 'Home',
    'DEFAULT_CONTROLLER'  => 'Index',
    'DEFAULT_ACTION'      => 'index',

    //是否开启子域名部署
    'DOMAIN_DEPLOY_ON'    => false,
    //子域名部署模式下 的 完整域名
    'FUL_DOMAIN'=>'',
    //子域名部署规则
    'SUB_DOMAIN_DEPLOY_RULES' => array(
        /**
         * 分别对应子域名模式下 的默认访问模块、控制器、操作和参数
         * 设置为null是表示不做设置，将使用默认的通用配置
         */
    ),

);