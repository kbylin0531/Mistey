<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/28
 * Time: 10:11
 */
namespace System\Core\StorageDriver;
use System\Core\Storage;
use System\Exception\FileNotFoundException;
use System\Exception\FileWriteFailedException;
use System\Utils\Util;

/**
 * Class StorageDriver 文件系统驱动类基类
 * @package System\Core\StorageDriver
 */
abstract class StorageDriver{

    /**
     * 获取文件内容
     * @param string $filepath 文件路径
     * @return string|null 文件不存在时返回null
     */
    public function read($filepath){
        $filepath = iconv('UTF-8','GBK',$filepath);
        return file_get_contents($filepath);
    }

    /**
     * 将指定内容写入到文件中
     * @param string $filepath 文件路径
     * @param string $content 要写入的文件内容
     * @return int 返回写入的字节数目
     * @throws FileWriteFailedException
     */
    public function write($filepath,$content){
        $filepath = iconv('UTF-8','GBK',$filepath);
        $dir      =  dirname($filepath);
        if(!is_dir($dir)) $this->makeFolder($dir);//文件不存在则创建
        return file_put_contents($filepath,$content);
    }

    /**
     * 将指定内容追加到文件中
     * @param string $filepath 文件路径
     * @param string $content 要写入的文件内容
     * @return int 返回写入的字节数目
     * @throws FileWriteFailedException
     */
    public function append($filepath,$content){
        $filepath = iconv('UTF-8','GBK',$filepath);
        if(!file_exists($filepath)){
            return $this->write($filepath,$content);
        }
        if(false === is_writable($filepath)){
            throw new FileWriteFailedException($filepath);
        }
        $handler = fopen($filepath,'a+');//如果文件不存在则无法创建
        $rst = fwrite($handler,$content);
        if(false === fclose($handler)) throw new FileWriteFailedException($filepath);
        return $rst;
    }
    /**
     * 确定文件或者目录是否存在
     * 相当于 is_file() or is_dir()
     * @param string $filepath 文件路径
     * @return bool
     */
    public function has($filepath){
        $filepath = iconv('UTF-8','GBK',$filepath);
        return file_exists($filepath);
    }

    /**
     * 删除文件
     * @param string $filepath
     * @return bool
     */
    public function unlink($filepath){
        return unlink(iconv('UTF-8','GBK',$filepath));
    }

    /**
     * 读取文件信息
     * 可以使用stat获取信息
     * @param string $filepath  文件路径
     * @param null $type
     * @return mixed
     * @throws FileNotFoundException
     */
    public function info($filepath,$type=null){
        if(self::has($filepath)){
            return isset($type)?call_user_func($type,$filepath):array(
                Storage::FILEINFO_LAST_ACCESS_TIME => call_user_func(Storage::FILEINFO_LAST_ACCESS_TIME,$filepath),
                Storage::FILEINFO_LAST_MODIFIED_TIME => call_user_func(Storage::FILEINFO_LAST_MODIFIED_TIME,$filepath),
                Storage::FILEINFO_PERMISSION => call_user_func(Storage::FILEINFO_PERMISSION,$filepath),
                Storage::FILEINFO_SIZE => call_user_func(Storage::FILEINFO_SIZE,$filepath),
                Storage::FILEINFO_TYPE => call_user_func(Storage::FILEINFO_TYPE,$filepath),
            );
        }else{
//            Util::dump($filepath,file_exists($filepath),self::has($filepath));exit;
//            exit;
            throw new FileNotFoundException($filepath);
        }

    }

    /**
     * 读取文件夹内容，并返回一个数组(不包含'.'和'..')
     * array(
     *      //文件内容  => 文件内容
     *      'filename' => 'file full path',
     * );
     * @param string $dirpath
     * @return array
     */
    public function readFolder($dirpath){
        $fileMap = array();
        if(!is_dir($dirpath)) {
            $this->makeFolder($dirpath);
        }else{
            $dh = opendir($dirpath);
            while($file = readdir($dh)){
                if($file === '.' || $file === '..') continue;
                $fileMap[$file] = "{$dirpath}/{$file}";
            }
            closedir($dh);
        }
        return $fileMap;
    }
    /**
     * 删除文件夹
     * @param string $dirpath 文件夹名路径
     * @param bool $recursion 是否递归删除
     * @return mixed
     */
    public function removeFolder($dirpath,$recursion=false){
        if(!is_dir($dirpath)) return false;
        //扫描目录
        $dh = opendir($dirpath);
        while ($file = readdir($dh)) {
            if($file !== '.' && $file !== '..') {
                if(!$recursion) return false;//存在其他文件或者目录,非true时循环删除
                $path = "{$dirpath}/{$file}";
                if(false === (is_dir($path)?$this->removeFolder($path):unlink($path))) return false;//全等运算符优先级高于三目
            }
        }
        closedir($dh);
        return rmdir($dirpath);
    }
    /**
     * 创建文件夹
     * 如果文件夹已经存在，则修改权限
     * @param string $dirpath 文件夹路径
     * @param int $auth 文件权限，八进制表示
     * @return mixed
     */
    public function makeFolder($dirpath,$auth = 0755){
        return is_dir($dirpath)?chmod($dirpath,$auth):mkdir($dirpath,$auth,true);
    }

}