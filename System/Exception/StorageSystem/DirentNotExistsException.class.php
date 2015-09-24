<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/31
 * Time: 18:55
 */
namespace System\Exception\StorageSystem;

class DirentNotExistsException extends \Exception{

    public function __construct($dirpath){
        $this->message = "Dirent '{$dirpath}' do not exists!";
    }

}