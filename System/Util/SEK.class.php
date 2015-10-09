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
 * 为保证系统运行而设置的通用工具库，开发者也可以使用
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
    public static function dumpout(){
        ob_end_clean();
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
        exit('</pre>');
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
     * 字符串命名风格转换
     * C风格      如： sub_string
     * JAVA风格   如： subString
     * @param string $str 字符串
     * @param bool $type 转换类型 true表示将C风格转换为Java的风格 false将Java风格转换为C的风格
     * @return string
     */
    public static function translateStringStyle($str, $type=true) {
        if ($type) {
            return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function($match){return strtoupper($match[1]);}, $str));
        } else {
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $str), "_"));
        }
    }
    /**
     * 判断是否是https请求
     * @return bool
     */
    public static function isHttps(){
        if(!isset($_SERVER['HTTPS']))  return FALSE;
        if($_SERVER['HTTPS'] === 1){  //Apache
            return true;
        }elseif($_SERVER['HTTPS'] === 'on'){ //IIS
            return true;
        }elseif($_SERVER['SERVER_PORT'] == 443){ //其他
            return true;
        }
        return false;
    }
    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    public static function getClientIP($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//透过代理的正式IP
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {//客户端IP，如果是通过代理访问则返回代理IP
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
    /**
     * Formats a numbers as bytes, based on size, and adds the appropriate suffix
     * 摘录自：CI_SAE\system\helpers\number_helper.php
     * @access	public
     * @param int $num 待格式化的数据
     * @param int $precision 精度
     * @return string
     */
    public static function byteFormat($num, $precision = 1){
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
    /**
     * 数据签名认证
     * @param  array  $data 被认证的数据
     * @return string 签名
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function dataAuthSign($data) {
        //统一转换为数组类型
        if(!is_array($data)) $data = (array)$data;
        //统一排序
        ksort($data);
        $sign = sha1(serialize($data)); //序列化并生成生成签名
        return $sign;
    }

    /**
     * 判断是否有不合法的参数存在，不合法的参数参照参数一（使用严格的比较-判断类型）
     * 第一个参数将会被认为是不合法的值，参数一可以是单个字符串或者数组
     * 第二个参数开始是要比较的参数列表，如果任何一个参数"匹配"了参数一，将返回true表示存在不合法的参数
     * @return bool
     */
    public static function checkInvalidValueExistInStrict(){
        $params = func_get_args();
        return self::checkInvalidValueExist($params,true);
    }
    /**
     * 判断是否有不合法的参数存在，不合法的参数参照参数一（使用宽松的比较-不判断类型）
     * 第一个参数将会被认为是不合法的值，参数一可以是单个字符串或者数组
     * 第二个参数开始是要比较的参数列表，如果任何一个参数"匹配"了参数一，将返回true表示存在不合法的参数
     * @return bool
     */
    public static function checkInvalidValueExistInEase(){
        $params = func_get_args();
        return self::checkInvalidValueExist($params);
    }
    /**
     * 检测是否存在不合法的值
     * 参数一种第一个元素作为比较对象
     * 如果是数组，则数组中都是不合法的值，如果是单值，使用===进行比较
     * @param array $params 参与比较的值的有序集合
     * @param bool|false $district 比较时是否判断其类型，默认是
     * @return bool
     */
    public static function checkInvalidValueExist($params,$district=false){
        $invalidVal = array_shift($params);
        foreach ($params as $key=>&$val){
            if(is_array($invalidVal)){
                //参数三决定是否使用严格的方式
                if(in_array(trim($val),$invalidVal,$district)) return true;
            }else{
                if($district? ($invalidVal === $val) : ($invalidVal == $val)) return true;
            }
        }
        return false;
    }
}