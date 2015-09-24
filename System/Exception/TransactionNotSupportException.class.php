<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/17
 * Time: 14:26
 */
namespace System\Exception;

class TransactionNotSupportException extends \Exception{

    public function __construct($driverName){
        $this->message = "PDO[$driverName] do not support transaction!";
    }

}