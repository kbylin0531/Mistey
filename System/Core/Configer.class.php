<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/16
 * Time: 13:15
 */
namespace System\Core;
use System\Exception\ConfigLoadFailedException;
use System\Exception\FileNotFoundException;
use System\Mist;
defined('BASE_PATH') or die('No Permission!');
/**
 * Class ConfigHelper 配置加载帮助类
 * @package System\Core
 */
class Configer{

    /**
     * 配置文件类型
     */
    const CONFIGTYPE_PHP = 0;
    const CONFIGTYPE_INI = 1;
    //.....

    private static $_configures = null;

    /**
     * @param bool $force_refresh 是否强制刷新配置，默认不刷新
     * @return void
     * @throws FileNotFoundException
     */
    public static function init($force_refresh=false){
        //Runtime目录下建立了配置集合，并且配置集合是最新的
        $dir = BASE_PATH.'Configure/';
        $file = BASE_PATH.'Runtime/configure.php';
        Mist::status('config_init_begin');
        if(AUTO_CHECK_CONFIG_ON or $force_refresh){
            //文件不存在 或者 目录时间更加新 的情况下读取配置并写入配置
            //可以设置AUTO_CHECK_CONFIG_ON = false来阻止稳定运行情况下的检查(消耗时间减少三分之二)
            $hasfile = Storage::hasFile($file);
            $filetime = Storage::getFileInfo($file,Storage::FILEINFO_LAST_MODIFIED_TIME);
            $dirtime  = Storage::getFileInfo($dir,Storage::FILEINFO_LAST_MODIFIED_TIME);
            if(false === ($hasfile and ($filetime > $dirtime)))
            {
                foreach(Storage::readFolder($dir) as $filename => $filepath){
                    //读取所有的配置文件
                    self::$_configures[substr($filename,0,strpos($filename,'.'))] = self::loadConfigFile($filepath);
                }
                Storage::writeFile($file,'<?php return '.var_export(self::$_configures,true).'; ?>'); //闭包函数无法写入
                Mist::status('config_init_and_writetemp_done');
                return;
            }
        }
        self::$_configures = self::loadConfigFile($file);
        Mist::status('config_init_done');
    }

    /**
     * 加载配置文件
     * @param string $confnm 配置项名称,默认是有用户自定义的名称
     * @return array
     * @throws ConfigLoadFailedException
     */
    public static function loadConfig($confnm='custom'){
        $confnm = strtolower($confnm);
        if(!isset(self::$_configures[$confnm])){//不存在该配置
            throw new ConfigLoadFailedException($confnm);
        }
        return self::$_configures[$confnm];
    }

    /**
     * 将配置写入数组
     * @param string$confnm
     * @param array $config
     * @param null $path
     * @return bool
     * @throws FileNotFoundException
     */
    public static function writeAutoConfig($confnm,array $config,$path = null){
        isset($path) or $path = BASE_PATH.'Configure/Auto/';
        $path = $path."{$confnm}.config.php";
        if(Storage::hasFile($path)){
            //文件存在，读取并合并配置
            $origin_config = self::loadConfigFile($path);
            $config = array_merge($origin_config,$config);//后者覆盖前者
        }
        return Storage::writeFile($path,'<?php return '.var_export($config,true).'; ?>'); //闭包函数无法写入
    }

    /**
     * 读取配置
     * @param string $confnm 配置名称
     * @param string $path 配置存放的路径
     * @return array
     * @throws FileNotFoundException
     */
    public static function readAutoConfig($confnm,$path=null){
        isset($path) or $path = BASE_PATH.'Configure/Auto/';
        $path = $path."{$confnm}.config.php";
        if(Storage::hasFile($path)){
            return self::loadConfigFile($path);
        }else{
            throw new FileNotFoundException($path);
        }
    }

    /**
     * 读取配置文件内容
     * @param string $path 配置文件的完整路径
     * @param int $type 配置文件类型，默认使用PHP形式的配置类型
     * @return array
     * @throws FileNotFoundException
     */
    public static function loadConfigFile($path,$type=self::CONFIGTYPE_PHP){
        static $_conf = array();
        if(isset($_conf[$path])){
            return $_conf[$path];
        }
        if(Storage::hasFile($path)){
            switch($type){
                //other config type ...
                case self::CONFIGTYPE_PHP:
                default:
                    $_conf[$path] = include $path;
            }
        }else{
            throw new FileNotFoundException($path);
        }
        return $_conf[$path];
    }


}
//别名
class_alias('Configer','ConfigHelper');