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

class FileDriver{

    protected $_cache = array();

    protected $_time = null;
    /**
     * 写入日志信息
     * 如果日志文件已经存在，则追加到文件末尾
     * @param string|array $content 日志内容
     * @param string $level 日志级别
     * @return string 写入内容返回
     */
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
        return Storage::append($path,"{$sdate[2]}\n+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n{$message}\n=====================================================================================\n\n\n\n\n\n");
    }

    /**
     * 读取日志文件内容
     * 如果设置了参数二，则参数一将被认定为文件名
     * @param string $path 日志文件路径或日志文件名（日志文件生成日期,格式如YYYY-mm-dd）
     * @param null|string $level 日志级别
     * @return string
     */
    public function read($path,$level=Log::LOG_LEVEL_DEBUG){
        isset($path) and $path = BASE_PATH."Runtime/Log/{$level}/{$path}.log";
        return Storage::read($path);
    }

    /**
     * 获取日期
     * 短日期格式如："1992-05-31"
     * 长日期格式如："2038-01-19 11:14:07"(date('Y-m-d H:i:s',PHP_INT_MAX))
     * @return string 日期字符串
     */
    protected function getTime(){
        if(!isset($this->_time)){
            $date = Util::getFormatDate();
            $this->_time[0] = LOG_RATE?'':substr($date,0,10);//年月日 文件夹名称,''表示创建文件夹
            $this->_time[1] = LOG_RATE?substr($date,0,10):substr($date,11,2);//时 文件名称,按日频度计算则显示年月入，否则显示小时
            $this->_time[2] = substr($date,11);//时分秒 具体时间
        }
        return $this->_time;
    }
    /**
     * 返回本次脚本执行的日志缓存数组
     * @return array
     */
    public function getLogCache(){
        return $this->_cache;
    }
}