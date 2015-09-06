<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/16
 * Time: 0:34
 */
namespace System\Exception;

class MethodNotFoundException extends \Exception{

    public function __construct($className,$methodName){
        $this->message = "Exception:Class [$className] lack of public method [$methodName]!";
    }

}