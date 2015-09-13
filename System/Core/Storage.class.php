<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/25
 * Time: 9:08
 */
namespace System\Core;
use System\Exception\FileWriteFailedException;
use System\Mist;
use System\Utils\Util;

defined('BASE_PATH') or die('No Permission!');
/**
 * Class Storage 持久化存储类
 * 实际文件可能写在伺服器的文件中，也可能存放到数据库文件中，或者远程文件服务器中
 * @package System\Core
 */
class Storage {

    const FILEINFO_LAST_ACCESS_TIME = 'fileatime';
    const FILEINFO_LAST_MODIFIED_TIME = 'filemtime';
    const FILEINFO_PERMISSION = 'fileperms';
    const FILEINFO_SIZE = 'filesize';//文件大小
    const FILEINFO_TYPE = 'filetype';//可能的值有 fifo，char，dir，block，link，file 和 unknown。

    /**
     * @var StorageDriver\CommonDriver
     */
    private static $driver = null;

    public static function init($mode = null){
        Mist::status('storage_init_begin');
        //获取运行环境
        null === $mode and $mode = RUNTIME_ENVIRONMENT;
        //实例化驱动类
        $driverName = "System\\Core\\StorageDriver\\{$mode}Driver";
        self::$driver = new $driverName();
        Mist::status('storage_init_done');
    }

    /**
     * 文件内容读取
     * @access public
     * @param string $filename  文件名
     * @return string
     */
    public static function readFile($filename){
        null === self::$driver and self::init();
        return self::$driver->read($filename);
    }

    /**
     * 文件写入
     * @param string $filename  文件名
     * @param string $content  文件内容
     * @return bool
     * @throws FileWriteFailedException
     */
    public static function writeFile($filename,$content){
        null === self::$driver and self::init();
        return self::$driver->write($filename,$content);
    }

    /**
     * 文件追加写入
     * @access public
     * @param string $filename  文件名
     * @param string $content  追加的文件内容
     * @return string 返回写入内容
     */
    public static function appendFile($filename,$content){
        null === self::$driver and self::init();
        return self::$driver->append($filename,$content);
    }


    /**
     * 文件是否存在
     * @access public
     * @param string $filename  文件名
     * @return boolean
     */
    public static function hasFile($filename){
        null === self::$driver and self::init();
        return self::$driver->has($filename);
    }

    /**
     * 文件删除
     * @access public
     * @param string $filename  文件名
     * @return boolean
     */
    public static function unlinkFile($filename){
        null === self::$driver and self::init();
        return self::$driver->unlink($filename);
    }

    /**
     * 读取文件信息
     * 可以使用stat获取信息
     * @access public
     * @param string $filename  文件名
     * @param null $type
     * @return array|mixed
     */
    public static function getFileInfo($filename,$type=null){
        null === self::$driver and self::init();
        return self::$driver->info($filename,$type);
    }

    /**
     * 删除文件夹
     * @param string $dir 文件夹目录
     * @param bool $recursion 是否递归删除
     * @return bool true成功删除，false删除失败
     */
    public static function removeFolder($dir,$recursion=false) {
        null === self::$driver and self::init();
        return self::$driver->removeFolder($dir,$recursion);
    }

    /**
     * 读取文件夹内容，并返回一个数组(不包含'.'和'..')
     * array(
     *      //文件内容  => 文件内容
     *      'filename' => 'file full path',
     * );
     * @param string $dir 文件夹路径
     * @return array
     */
    public static function readFolder($dir){
        null === self::$driver and self::init();
        return self::$driver->readFolder($dir);
    }

    /**
     * 创建文件夹
     * 如果文件夹已经存在，则修改权限
     * @param string $fullpath 文件夹路径
     * @param int $auth 文件权限，八进制表示
     * @return bool
     */
    public static function makeFolder($fullpath,$auth = 0755){
        null === self::$driver and self::init();
        return self::$driver->makeFolder($fullpath,$auth);
    }

}