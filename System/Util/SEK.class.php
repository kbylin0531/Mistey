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
     * 打印参数
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
    /**
     * 获取日期时间
     * @param string $format
     * @param int $timestap
     * @return bool|string false时可能的原因是日期时间格式错误
     */
    public static function date($format = 'Y-m-d H:i:s',$timestap=null){
        if(empty($format))$format = 'Y-m-d H:i:s';
        $date = date($format,$timestap);
        return $date;
    }
    /**
     * Formats a numbers as bytes, based on size, and adds the appropriate suffix
     * 摘录自：CI_SAE\system\helpers\number_helper.php
     * @access	public
     * @param int $num 待格式化的数据
     * @param int $precision 精度
     * @return string
     */
    function byteFormat($num, $precision = 1){
        $unit = 'Bytes';//合适的单位
        if ($num >= 1000000000000){//0.9XX +++
            $num = round($num / 1099511627776, $precision);
            $unit = 'TB';
        }elseif ($num >= 1000000000){
            $num = round($num / 1073741824, $precision);
            $unit = 'GB';
        }elseif ($num >= 1000000){
            $num = round($num / 1048576, $precision);
            $unit = 'MB';
        }elseif ($num >= 1000){
            $num = round($num / 1024, $precision);
            $unit = 'KB';
        }else{
            return number_format($num).' '.$unit;
        }
        return number_format($num, $precision).' '.$unit;
    }
}