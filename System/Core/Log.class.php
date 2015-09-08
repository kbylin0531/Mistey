<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/24
 * Time: 16:51
 */
namespace System\Core;
use System\Exception\ClassNotFoundException;
use System\Utils\Util;
defined('BASE_PATH') or die('No Permission!');

class Log{

    /**
     * 日志驱动类型
     */
    const LOGTYPE_FILE = 'File';
    const LOGTYPE_DATABASE = 'Sae';
    /**
     * 日志级别为记录错误
     */
    const LOG_LEVEL_DEBUG = 'Debug';
    /**
     * 记录日常操作的数据信息，以便数据丢失后寻回
     */
    const LOG_LEVEL_TRACE = 'Trace';

    /**
     * 日志记录的时间
     * @var array
     */
    protected static $_time = null;

    /**
     * @var LogDriver\LogDriver;
     */
    private static $_driver = null;

    public static function init($type = self::LOGTYPE_FILE){
        if(null === self::$_driver){
            new LogDriver\FileDriver();
            $clsnm = 'System\\Core\\LogDriver\\'.$type.'Driver';
            if(!class_exists($clsnm)){
                throw new ClassNotFoundException($clsnm);
            }
            self::$_driver = new $clsnm();
        }
    }

    /**
     * 写入日志信息
     * 如果日志文件已经存在，则追加到文件末尾
     * @param string|array $content 日志内容
     * @param string $level 日志级别
     * @return string 写入内容返回
     * @Exception FileWriteFailedException
     */
    public static function write($content,$level=self::LOG_LEVEL_DEBUG){
        return self::$_driver->write($content.$level);
    }

    /**
     * 写入跟踪信息,信息参数可变长
     * @param ...
     * @return string
     */
    public static function trace(){
        $content = '';
        if(DEBUG_MODE_ON){
            $params = func_get_args();
            foreach($params as $val){
                $content .= var_export($val,true).' █ ';
            }
            self::$_driver->write($content,self::LOG_LEVEL_TRACE);
        }
        return $content;
    }

    /**
     * 返回此次运行保留的日志信息
     * @return array
     */
    public static function getLogCache(){
        return self::$_driver->getLogCache();
    }

    /**
     * 读取日志文件内容
     * 如果设置了参数二，则参数一将被认定为文件名
     * @param string $path 日志文件路径或日志文件名（日志文件生成日期,格式如YYYY-mm-dd）
     * @param null|string $level 日志级别
     * @return string
     */
    public static function read($path,$level=self::LOG_LEVEL_DEBUG){
        return self::$_driver->read($path,$level);
    }


}