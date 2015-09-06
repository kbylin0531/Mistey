<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/26
 * Time: 14:55
 */
namespace System\Exception;

/**
 * Class ConfigLoadFailedException 配置加载失败异常类
 * @package System\Exception
 */
class ConfigLoadFailedException extends \Exception{

    public function __construct($confnm){
        $this->message = "Load Config file {$confnm}.config.php failed!";
    }

}