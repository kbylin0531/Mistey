<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/20
 * Time: 17:26
 */

use System\Mist;
/**
 * 基础目录定义
 */
define('BASE_PATH',str_replace('\\','/',__DIR__).'/');

Mist::init();

//管理员身份验证

//解析控制台命令