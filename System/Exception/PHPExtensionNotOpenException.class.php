<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/17
 * Time: 13:23
 */
namespace System\Exception;

class PHPExtensionNotOpenException extends \Exception{

    public function __construct($extname){
        $this->message = "PHP Extension '{$extname}' Not Opened!";
    }

}