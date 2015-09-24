<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/25
 * Time: 9:24
 */
namespace System\Exception;

class FileWriteFailedException extends \Exception{

    /**
     * @param string $path 文件完整路径
     */
    public function __construct($path){
        $this->message = "File write failed,path({$path})";
    }

}