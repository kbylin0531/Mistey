<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/6
 * Time: 9:59
 */
namespace System\Core\LogDriver;
use System\Core\Log;
use System\Core\Storage;
use System\Utils\Util;

class FileDriver extends LogDriver{


    public function write($content,$level=Log::LOG_LEVEL_DEBUG){
        $sdate = $this->getTime();
        $path = BASE_PATH."Runtime/Log/{$level}/";
        if($sdate[0]){
            $path .= "{$sdate[0]}/";
        }
        $path .= "{$sdate[1]}.log";
        //写入文件内容
        $message = '';
        if(is_array($content)){//数组写入
            foreach($content as $key=>$val){
                $message .= is_numeric($key)?"{$val}\n":"||--{$key}--||\n{$val}\n";
            }
        }else{
            $message = $content;
        }
        $this->_cache[] = $message;
        return Storage::appendFile($path,"{$sdate[2]}\n+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n{$message}\n=====================================================================================\n\n\n\n\n\n");
    }


    public function read($path,$level=Log::LOG_LEVEL_DEBUG){
        isset($path) and $path = BASE_PATH."Runtime/Log/{$level}/{$path}.log";
        return Storage::readFile($path);
    }

}