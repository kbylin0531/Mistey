<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/21
 * Time: 10:31
 */
namespace System\Exception\Storage;

/**
 * Class IOException 存储类输入输出错误
 * @package System\Exception\Storage
 */
class IOException extends \Exception{

    public function __construct($path,$content=null){
        if(null === $content){//读取
            $this->message = "File '{$path}' read failed!";
        }else{
            if(strlen($content) >10){
                $content = substr($content,0,10).'...';
            }
            $this->message = "File  '{$path}' write content of '{$content}' failed!";
        }
    }

}