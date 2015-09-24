<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/16
 * Time: 9:28
 */
namespace System\Exception;

class ClassNotFoundException extends \Exception{

    public function __construct($clsnm){
        $this->message = "Exception:Class [$clsnm] not found !";
    }

}