<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/17
 * Time: 13:36
 */
namespace System\Exception;

class PDOCreateFailedException extends \Exception{

    public function __construct($driverName){
        $this->message = "PDO[$driverName] instantiated failed!";
    }

}