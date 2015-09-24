<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/21
 * Time: 10:42
 */
namespace System\Exception\Storage;

class PermissionException extends \Exception{

    public function __construct($file,$write=true){
        $readable = is_readable($file)?'true':'false';
        $writable = is_writable($file)?'true':'false';
        $write = $write?'write':'read';
        $this->message = "File '{$file}'[Writable:{$writable};Readable:{$readable}] --> {$write} ";
    }

}