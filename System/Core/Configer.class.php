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
use System\Util\SEK;

defined('BASE_PATH') or die('No Permission!');
/**
 * Class Configer 配置加载帮助类
 * @package System\Core
 */
class Configer{

    /**
     * 配置文件类型
     * 同时保存了配置文件后缀信息
     */
    const CONFIGTYPE_PHP = '.config.php';
    const CONFIGTYPE_INI = '.ini';
    //.....
    /**
     * 配置缓存信息
     * @var array
     */
    private static $_configures = array();

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
            $hasfile = Storage::has($file);
            if(false === ($hasfile and
                    (Storage::info($file,Storage::FILEINFO_LAST_MODIFIED_TIME) >
                        Storage::info($dir,Storage::FILEINFO_LAST_MODIFIED_TIME))))
            {
                foreach(Storage::readFolder($dir) as $filename => $filepath){
                    //读取所有的配置文件
                    self::$_configures[substr($filename,0,strpos($filename,'.'))] = self::read($filepath);
                }
                Storage::write($file,'<?php return '.var_export(self::$_configures,true).'; '); //闭包函数无法写入
                Mist::status('config_init_and_writetemp_done');
                return;
            }
        }
        self::$_configures = self::read($file);
        Mist::status('config_init_done');
    }

    /**
     * 加载配置文件
     * @param string $confnm 配置项名称,默认是有用户自定义的名称
     * @return array
     * @throws ConfigLoadFailedException
     */
    public static function load($confnm='custom'){
        $confnm = strtolower($confnm);
        if(!isset(self::$_configures[$confnm])){//不存在该配置
            throw new ConfigLoadFailedException($confnm);
        }
        return self::$_configures[$confnm];
    }
    /**
     * 获取配置信息
     * 示例：
     *  database.DB_CONNECT.0.type
     * 除了第一段外要注意大小写
     * @param string $confnm
     * @return mixed
     * @throws ConfigLoadFailedException
     */
    public static function get($confnm = null){
        $configes = null;//配置分段，如果未分段则保持null的值
        $value = null;//最终将被返回的值
        if(null === $confnm){//默认参数时返回全部
            return self::$_configures;
        }
        if(false !== strpos($confnm,'.')){
            $configes = explode(',',$confnm);
            $confnm = array_shift($configes);
        }
        $confnm = strtolower($confnm);
        if(!isset(self::$_configures[$confnm])){//不存在该配置
            throw new ConfigLoadFailedException($confnm);
        }
        $value = self::$_configures[$confnm];
        if($configes){
            foreach($configes as $val){
                if(isset($value[$val])){
                    $value = $value[$val];
                }else{
                    return null;
                }
            }
        }
        return $value;
    }

    /**
     * @param $confnm
     * @param $value
     * @return bool
     * @throws ConfigLoadFailedException
     */
    public static function set($confnm,$value){
        $configes = null;//配置分段，如果未分段则保持null的值
        $var = null;
        if(false !== strpos($confnm,'.')){
            $configes = explode(',',$confnm);
            $confnm = array_shift($configes);
        }
        $confnm = strtolower($confnm);
        if(!isset(self::$_configures[$confnm])){//不存在该配置
            throw new ConfigLoadFailedException($confnm);
        }
        $var = self::$_configures[$confnm];
        foreach($configes as $val){
            if(isset($value[$val])){
                $var = $var[$val];
            }else{
                return false;
            }
        }
        return true;
    }
    /**
     * 读取配置文件内容
     * @param string $path 配置文件的完整路径
     * @param string $type 配置文件类型，默认使用PHP形式的配置类型
     * @return array
     * @throws FileNotFoundException
     */
    public static function read($path,$type=self::CONFIGTYPE_PHP){
        static $_conf = array();
        if(!isset($_conf[$path])){//部署运行阶段配置文件不会发生变化
            if(Storage::has($path)){
                switch($type){
                    //other config type ...
                    case self::CONFIGTYPE_PHP:
                    default:
                        $_conf[$path] = include $path;
                }
            }else{
                throw new FileNotFoundException($path);
            }
        }
        return $_conf[$path];
    }

    /**
     * 将配置写入到配置文件中文件中
     * @param string $path 配置文件的完整路径
     * @param array $config 配置数组
     * @param string $type 配置文件类型
     * @return bool
     * @throws FileNotFoundException
     */
    public static function write($path,array $config,$type=self::CONFIGTYPE_PHP){
        switch($type){
            //...other config type ...
            case self::CONFIGTYPE_PHP:
            default:
                $filename = pathinfo($path,PATHINFO_FILENAME);
                self::$_configures[substr($filename,0,strpos($filename,'.'))] = $config;
                return Storage::write($path,'<?php return '.var_export($config,true).'; ?>'); //闭包函数无法写入
        }
    }

    /**
     * 将配置写入数组
     * 自动配置信息一般随模块的安装而生成的，不能随意修改
     * @param string$confnm
     * @param array $config
     * @param null $path
     * @return bool
     * @throws FileNotFoundException
     */
    public static function writeConfig($confnm,array $config,$path){
        //配置文件路径，不同的配置文件类型拥有不同的后缀
        $path = "{$path}/{$confnm}.config.php";
        if(Storage::has($path)){
            //文件存在，读取并合并配置
            $origin_config = self::read($path);
            SEK::merge($origin_config,$config);//后者覆盖前者
            $config = $origin_config;
        }
        return Storage::write($path,'<?php return '.var_export($config,true).'; ?>'); //闭包函数无法写入
    }

    /**
     * 读取配置
     * @param string $confnm 配置名称
     * @param string $path 配置存放的路径
     * @return array
     * @throws FileNotFoundException
     */
    public static function readAuto($confnm,$path=null){
        isset($path) or $path = BASE_PATH.'Configure/Auto/';
        $path = "{$path}auto_{$confnm}.config.php";
        if(Storage::has($path)){
            return self::read($path);
        }else{
            throw new FileNotFoundException($path);
        }
    }

}