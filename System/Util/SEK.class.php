<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/20
 * Time: 11:15
 */
namespace System\Util;

use System\Exception\ParameterInvalidException;

/**
 * Class SEK 系统执行工具(System Execute Kits)
 * 不直接面向用户
 * @package System\Utils
 */
final class SEK {

    /**
     * 合并数组配置(参数二合并到参数一上)
     * @param array $dest
     * @param array $sourse
     * @param bool|false $cover
     * @return void
     * @throws ParameterInvalidException
     */
    public static function merge(array &$dest,array $sourse,$cover=false){
        if(is_array($dest) and is_array($sourse)){
            if($cover){
                $dest = array_merge($dest,$sourse);
            }else{
                foreach($sourse as $key=>&$val){
                    if(isset($dest[$key]) and is_array($val)){
                        self::merge($dest[$key],$val);
                    }else{
                        $dest[$key] = $val;
                    }
                }
            }
        }else{
            throw new ParameterInvalidException($sourse);
        }
    }

    /**
     * debug参数
     * @return void
     */
    public static function dump(){
        $params = func_get_args();
        //随机浅色背景
        $str='9ABCDEF';
        $color='#';
        for($i=0;$i<6;$i++) {
            $color=$color.$str[rand(0,strlen($str)-1)];
        }
        //传入空的字符串或者==false的值时 打印文件
        $traces = debug_backtrace();
        $title = "<b>File:</b>{$traces[0]['file']} << <b>Line:</b>{$traces[0]['line']} >> ";
        echo "<pre style='background: {$color};width: 100%;'><h3 style='color: midnightblue'>{$title}</h3>";
        foreach ($params as $key=>$val){
            echo '<b>Param '.$key.':</b><br />'.var_export($val, true).'<br />';
        }
        echo '</pre>';
    }
}