<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/9
 * Time: 16:02
 */
namespace Application\Installer\Util;
use System\Core\Configer;

/**
 * Class InstallKits 安装过程中使用的工具包
 * @package Application\Installer\Util
 */
class InstallKits {

    /**
     * 及时显示提示信息
     * @param string $msg 提示信息
     * @param string $class 提示信息类型
     */
    public static function flushMessageToClient($msg, $class = ''){
        echo "<script type=\"text/javascript\">showmsg(\"{$msg}\", \"{$class}\")</script>";
        flush();
        ob_flush();
    }

    /**
     * 配置
     * @return array
     * @throws \System\Exception\FileNotFoundException
     */
    public static function getDatabaseConfig(){
        $path = str_replace('\\','/',dirname(dirname(__FILE__)).'/Configure/database.config.php');
        return Configer::read($path);
    }

}