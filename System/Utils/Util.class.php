<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/15
 * Time: 16:06
 */
namespace System\Utils;
use System\Core\Configer;
use System\Core\Router;
use System\Exception\Template\XMLReadFailedException;
use System\Extension\PasswordHash;

defined('BASE_PATH') or die('No Permission!');

/**
 * Class Util 通用工具类
 * @package System\Utils
 */
class Util{

    /**
     * 转换地址成网络地址
     * @param $path
     * @return mixed
     */
    public static function path($path){
        return str_replace('\\','/',$path);
    }

    /**
     * <b>暂时有异常，不建议使用</b>
     * PasswordHash加密解密类
     * @param string $password
     * @param string $compare
     * @return bool|string
     */
    public static function pwd($password,$compare=null){
        static $hasher = null;
        isset($hasher) or $hasher = new PasswordHash(8, true);
        if(null === $compare){
            //返回加密结果
            return $hasher->HashPassword($password);
        }else{
            //返回比较结果
            return $hasher->CheckPassword($password,$compare);
        }
    }


    /**
     * 字符串命名风格转换
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
            return TRUE;
        }elseif($_SERVER['HTTPS'] === 'on'){ //IIS
            return TRUE;
        }elseif($_SERVER['SERVER_PORT'] == 443){ //其他
            return TRUE;
        }
        return FALSE;
    }
    /**
     * 生成系统AUTH_KEY
     * @return string
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function buildAuthKey(){
        $chars  = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ`~!@#$%^&*()_+-=[]{};:"|,.<>/?';
        $chars  = str_shuffle($chars);
        return substr($chars, 0, 40);
    }

    /**
     * 判断是否有不合法的参数存在，不合法的参数参照参数一（使用严格的比较-判断类型）
     * 第一个参数将会被认为是不合法的值，参数一可以是单个字符串或者数组
     * 第二个参数开始是要比较的参数列表，如果任何一个参数"匹配"了参数一，将返回true表示存在不合法的参数
     * @return bool
     */
    public static function checkInvalidExistInStrict(){
        $params = func_get_args();
        return self::checkInvalidExist($params,true);
    }
    /**
     * 判断是否有不合法的参数存在，不合法的参数参照参数一（使用宽松的比较-不判断类型）
     * 第一个参数将会被认为是不合法的值，参数一可以是单个字符串或者数组
     * 第二个参数开始是要比较的参数列表，如果任何一个参数"匹配"了参数一，将返回true表示存在不合法的参数
     * @return bool
     */
    public static function checkInvalidExistInEase(){
        $params = func_get_args();
        return self::checkInvalidExist($params);
    }

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
     * @param array $params 参数
     * @param bool|false $district 比较时是否判断其类型，默认是
     * @return bool
     */
    public static function checkInvalidExist($params,$district=false){
        $invalidVal = array_shift($params);
        foreach ($params as $key=>&$val){
            if(is_array($invalidVal)){
                //参数三决定是否使用严格的方式
                return in_array(trim($val),$invalidVal,$district);
            }else{
                return $district? ($invalidVal === $val) : ($invalidVal == $val);
            }
        }
        return false;
    }

    /**
     * 打印变量的详细信息
     * @param $key
     * @param $val
     * @param $level
     */
    private static function printVarOfType(&$key,&$val,&$level){
        $type = gettype($val);
        switch($type){
            case 'boolean':
                echo str_repeat('    ',$level)."<b>['{$key}']</b> : {$type}(".intval($val).")<br />";
                break;
            case 'integer':
            case 'double':
            case 'NULL':
                echo str_repeat('    ',$level)."<b>['{$key}']</b> : {$type}($val)<br />";
                break;

            case 'string':
                echo str_repeat('    ',$level)."<b>['{$key}']</b> : String('$val')<br />";
                break;

            case 'array':
                $key  = is_numeric($key)?'':"<b>['{$key}']</b> : ";
                echo str_repeat('    ',$level++).$key.'Array(<br />';
                foreach($val as $k=>$v){
                    self::printVarOfType($k,$v,$level);
                }
                --$level;
                echo str_repeat('    ',$level).');<br />';
                break;

            case 'object'://打印对象（待完善）
                echo str_repeat('    ',$level)."<b>['{$key}']</b> : Object<br />";
                break;
            case 'resource':
                echo str_repeat('    ',$level)."<b>['{$key}']</b> : Resource<br />";
                break;
            case 'unknown type':
            default:
                echo str_repeat('    ',$level)."<b>['{$key}']</b> : unknown type<br />";
        }
    }


    /**
     * 合并配置项的方法
     * @param array $dest 合并的目标
     * @param array $sourse 并入的配置数组
     * @param boolean $cover 配置项是数组时是否直接覆盖，默认为false
     * @return array 返回合并后的配置
     */
    public static function mergeConf(&$dest,$sourse,$cover=false){
        if(NULL !== $sourse and is_array($sourse)){
            if($cover){
                $dest = array_merge($dest,$sourse);
            }else{
                foreach($sourse as $key=>&$val){
                    if(isset($dest[$key]) and is_array($val)){
                        $dest[$key] = array_merge($dest[$key],$val);
                    }else{
                        $dest[$key] = $val;
                    }
                }
            }
        }
        return $dest;
    }

    /**
     * 发送浏览器无缓存指令
     * @return bool
     */
    public static function sendBrowerCacheOption(){
        $obcache = null;
        if(ob_get_level()){
            $obcache = ob_get_clean();
        }
        header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
        header( 'Cache-Control: no-store, no-cache, must-revalidate' );
        header( 'Cache-Control: post-check=0, pre-check=0', false );
        header( 'Pragma: no-cache' );
        if(null !== $obcache){
            echo $obcache;
        }
    }

    /**
     * $url规则如：
     *  .../Ma/Mb/Cc/Ad
     * 依次从后往前解析出操作，控制器，模块(如果存在模块将被认定为完整的模块路径)
     * TODO:如果未设置控制器或者模块将默认使用当前访问的模块或者控制器(在URLHelper::create方法已有这个设定)
     * @param string $url 快速创建的URL字符串
     * @param array $params GET参数数组
     * @return string
     */
    public static function url($url=null,array $params=array()){
        //解析参数中的$url
        if(!$url){
            return Router::create(null,null,null,$params);
        }
        $parts = @explode('/',$url);
        //调用URLHelper创建URL
        $action  = array_pop($parts);
        $ctler   = $action?array_pop($parts):null;
        $modules = $ctler?$parts:null;
        return Router::create($modules,$ctler,$action,$params);
    }
    /**
     * $url规则如：
     *  .../Ma/Mb/Cc/Ad
     * 依次从后往前解析出操作，控制器，模块(如果存在模块将被认定为完整的模块路径)
     * TODO:如果未设置控制器或者模块将默认使用当前访问的模块或者控制器(在URLHelper::create方法已有这个设定)
     * @param string $url 快速创建的URL字符串
     * @param array $params GET参数数组
     * @return string
     */
    public static function jump($url,array $params=array()){
        $url = self::url($url,$params);
        ob_get_level() > 0 and ob_end_clean();
        self::redirect($url);
    }

    /**
     * 重定向
     * @param $url
     * @param int $time
     * @param string $message
     * @return void
     */
    public static function redirect($url, $time=0,$message=''){
        if(headers_sent()){//检查头部是否已经发送
            exit("<meta http-equiv='Refresh' content='{$time};URL={$url}'>$message");
        }else{
            if(0 === $time){
                header('Location: ' . $url);
            }else{
                header("refresh:{$time};url={$url}");
            }
            exit($message);
        }
    }
    /**
     * Cookie 设置、获取、删除
     * @param string $name cookie名称
     * @param mixed $value cookie值
     * @param mixed $option cookie参数
     * @return mixed
     */
    function cookie($name='', $value='', $option=null) {
        // 默认设置
        $config = array(
            'prefix'    =>  APP_NAME, // cookie 名称前缀
            'expire'    =>  3600, // cookie 保存时间
            'path'      =>  '/', // cookie 保存路径
            'domain'    =>  '', // cookie 有效域名
            'secure'    =>  false, //  cookie 启用安全传输
            'httponly'  =>  '', // httponly设置
        );
        // 参数设置(会覆盖黙认设置)
        if (isset($option)) {
            if (is_numeric($option)){
                $option = array('expire' => $option);
            }elseif(is_string($option)){
                parse_str($option, $option);//解析查询字符串
            }
            $config     = array_merge($config, array_change_key_case($option));
        }
        if($config['httponly'])  ini_set('session.cookie_httponly', 1);
        // 清除指定前缀的所有cookie
        if (null === $name) {
            if (empty($_COOKIE)) return null;
            // 要删除的cookie前缀，不指定则删除config设置的指定前缀
            $prefix = empty($value) ? $config['prefix'] : $value;
            if (!empty($prefix)) {// 如果前缀为空字符串将不作处理直接返回
                foreach ($_COOKIE as $key => $val) {
                    if (0 === stripos($key, $prefix)) {
                        setcookie($key, '', time() - 3600, $config['path'], $config['domain'],$config['secure'],$config['httponly']);
                        unset($_COOKIE[$key]);
                    }
                }
            }
            return null;
        }elseif('' === $name){
            // 获取全部的cookie
            return $_COOKIE;
        }
        $name = $config['prefix'] . str_replace('.', '_', $name);
        if ('' === $value) {
            if(isset($_COOKIE[$name])){
                $value =    $_COOKIE[$name];
                if(0===strpos($value,'think:')){
                    $value  =   substr($value,6);
                    return array_map('urldecode',json_decode($value,true));//stripslashes
                }else{
                    return $value;
                }
            }else{
                return null;
            }
        } else {
            if (is_null($value)) {
                setcookie($name, '', time() - 3600, $config['path'], $config['domain'],$config['secure'],$config['httponly']);
                unset($_COOKIE[$name]); // 删除指定cookie
            } else {
                // 设置cookie
                if(is_array($value)){
                    $value  = 'think:'.json_encode(array_map('urlencode',$value));
                }
                $expire = !empty($config['expire']) ? time() + intval($config['expire']) : 0;
                setcookie($name, $value, $expire, $config['path'], $config['domain'],$config['secure'],$config['httponly']);
                $_COOKIE[$name] = $value;
            }
        }
        return null;
    }


    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id   数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    public static function encodeHtml($data, $root='think', $item='item', $attr='', $id='id', $encoding='utf-8') {
        if(is_array($attr)){
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr   = trim($attr);
        $attr   = empty($attr) ? '' : " {$attr}";
        $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml   .= "<{$root}{$attr}>";
        $xml   .= self::traslateData2Html($data, $item, $id);
        $xml   .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed  $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id   数字索引key转换为的属性名
     * @return string
     */
    private static function traslateData2Html($data, $item='item', $id='id') {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if(is_numeric($key)){
                $id && $attr = " {$id}=\"{$key}\"";
                $key  = $item;
            }
            $xml    .=  "<{$key}{$attr}>";
            $xml    .=  (is_array($val) || is_object($val)) ? self::traslateData2Html($val, $item, $id) : $val;
            $xml    .=  "</{$key}>";
        }
        return $xml;
    }

    /**
     * 检查或获取开启的PHP扩展
     * @param null|string $extname 扩展名称
     * @return array|bool
     */
    public static function phpExtend($extname=NULL){
        if(isset($extname)){
            //dl($extname) 运行时开启
            return extension_loaded($extname);
        }
        return get_loaded_extensions();
    }

    /**
     * 加载配置文件
     * @param $confName
     * @param string $modlist 如果为空字符串或者无参数二，则都读取标准配置目录下的配置文件
     * @return array
     */
    public static function loadConf($confName,$modlist=''){
        Configer::load($confName,$modlist);
    }


    /**
     * 获取日期
     * $start = microtime(true);
     * for($i=0;$i<10000;++$i){
     *      Util::getFormatDate();
     * }
     * $end = microtime(true);
     * for($i=0;$i<10000;++$i){
     *      date('Y-m-d H:i:s');
     * }
     * $reend = microtime(true);
     * Util::dump(
     *      floatval($end - $start), //float(0.0039999485015869)
     *      floatval($reend - $end) //float(0.02000093460083)
     * );
     * @param string $format
     * @param null $timestap
     * @return bool|string
     */
    public static function getFormatDate($format = 'Y-m-d H:i:s',$timestap=null){
        static $date = array();
        $param = array($format);
        if(isset($timestap)) $param[] = $timestap;
        return isset($date[$format])?$date[$format]:($date[$format] = call_user_func_array('date',$param));
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
     * 只替换一次目标中的指定字符串
     * @param $needle
     * @param $replace
     * @param $haystack
     * @return string
     */
    public static function strReplaceJustOnce($needle, $replace, $haystack) {
        $pos = strpos($haystack, $needle);
        return false === $pos?$haystack:substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    /**
     * 打印参数的详细信息
     * @return void
     */
    public static function dump(){
        $params = func_get_args();
        //随机浅色背景
        $str='9ABCDEF';
        $color='#';
        $len=strlen($str);
        for($i=0;$i<6;$i++) {
            $color=$color.$str[rand(0,$len-1)];
        }
        //传入空的字符串或者==false的值时 打印文件
        $traces = debug_backtrace();
        $title = "<b>File:</b>{$traces[0]['file']} << <b>Line:</b>{$traces[0]['line']} >> ";
        echo "<pre style='background: {$color};width: 100%;'><h3 style='color: midnightblue'>{$title}</h3>";
        foreach ($params as $key=>$val){
            echo '<b>Param '.$key.' is:</b><br />';
//            var_dump($val);
//            if (!extension_loaded('xdebug')) {
//                $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', var_export($val,true));
//            }else{
//                if (ini_get('html_errors')) {
//                    $output = print_r($val, true);
//                } else {
//                    $output = print_r($val, true);
//                }
//            }
            echo print_r($val, true).'<br />';
        }
        echo '</pre>';
    }

    /**
     * 解析XML属性内容成键值对数组
     * 如
     * <include  file="Yeah/look2,Yeah/look3" dir="/usr" />
     * 中类似<< file="Yeah/look2,Yeah/look3" dir="/usr" >>的内容
     * @param string $tagcontent 元素属性内容
     * @return array
     * @throws XMLReadFailedException
     */
    public static function readXmlAttrs($tagcontent){
        $xml = simplexml_load_string("<tpl><tag {$tagcontent} /></tpl>");//返回SimpleXMLElement，失败时返回false
        if(false === $xml or !($xml instanceof \SimpleXMLElement)){
            throw new XMLReadFailedException($tagcontent);
        }
        $xml = (array)($xml->tag->attributes());//改自$xml->tag->attributes()
        return array_change_key_case($xml['@attributes']);
    }




}