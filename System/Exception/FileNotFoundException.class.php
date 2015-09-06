<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/8
 * Time: 17:19
 */
namespace System\Exception;

/**
 * 文件未找导致的异常
 * Class FileNotFoundException
 * @package System\Core\Exception
 */
class FileNotFoundException extends \Exception{

    /**
     * 构造函数需要传入文件路径
     * @param string $fileName 错误的文件路径
     */
    public function __construct($fileName){
//        var_dump($fileName,is_file($fileName));
        $this->message = "File '{$fileName}' do not exist!";
    }

}