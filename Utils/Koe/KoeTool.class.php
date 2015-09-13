<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 20:16
 */
namespace Utils\Koe;

class KoeTool{

    /**
     * ����ͬ���path
     * @param $path
     * @return mixed
     */
    public static function P($path){
        return str_replace('\\','/',$path);
    }

    /**
     * �ж��Ƿ���https����
     * @return bool
     */
    public static function isHttps(){
        if(!isset($_SERVER['HTTPS']))  return FALSE;
        if($_SERVER['HTTPS'] === 1){  //Apache
            return TRUE;
        }elseif($_SERVER['HTTPS'] === 'on'){ //IIS
            return TRUE;
        }elseif($_SERVER['SERVER_PORT'] == 443){ //����
            return TRUE;
        }
        return FALSE;
    }


    /**
     * �����࣬��classĿ¼��controller��modelĿ¼��Ѱ��class
     * @param $className
     */
    public static function _autoload($className){
        if (file_exists(CLASS_DIR . strtolower($className) . '.class.php')) {
            require_once(CLASS_DIR . strtolower($className) . '.class.php');
        } else if (file_exists(CONTROLLER_DIR . strtolower($className) . '.class.php')) {
            require_once(CONTROLLER_DIR . strtolower($className) . '.class.php');
        } else if (defined('MODEl_DIR') and file_exists(MODEl_DIR . strtolower($className) . '.class.php')) {
            require_once(MODEl_DIR . strtolower($className) . '.class.php');
        } else {
            // error code;
        }
    }


    /**
     * ����model����
     * @param $model_name
     * @return bool
     */
    public static function init_model($model_name){
        if (!class_exists($model_name.'Model')) {
            $model_file = MODEL_DIR.$model_name.'Model.class.php';
            require_once ($model_file);

            if(!is_file($model_file)){
                return false;
            }
        }
        $reflectionObj = new \ReflectionClass($model_name.'Model');
        $args = func_get_args();
        array_shift($args);
        return $reflectionObj -> newInstanceArgs($args);
    }
    /**
     * ����controller����
     * @param $controller_name
     * @return bool
     */
    public static function init_controller($controller_name){
        if (!class_exists($controller_name)) {
            $model_file = CONTROLLER_DIR.$controller_name.'.class.php';
            if(!is_file($model_file)){
                return false;
            }
            require_once ($model_file);
        }
        $reflectionObj = new \ReflectionClass($controller_name);
        $args = func_get_args();
        array_shift($args);
        return $reflectionObj -> newInstanceArgs($args);
    }

    /**
     * ������
     * @param $class
     */
    public static function load_class($class){
        $filename = CLASS_DIR.$class.'.class.php';
        if (file_exists($filename)) {
            require($filename);
        }else{
            self::pr($filename.' is not exist',1);
        }
    }
    /**
     * ���غ�����
     * @param $function
     */
    public static function load_function($function){
        $filename = FUNCTION_DIR.$function.'.public static function.php';
        if (file_exists($filename)) {
            require($filename);
        }else{
            self::pr($filename.' is not exist',1);
        }
    }
    /**
     * �ı��ַ���ת��
     * @param $str
     * @return mixed
     */
    public static function mystr($str){
        $from = array("\r\n", " ");
        $to = array("<br/>", "&nbsp");
        return str_replace($from, $to, $str);
    }

    /**
     * �������ո�ͻس��ַ�
     * @param $str
     * @return mixed
     */
    public static function strip($str){
        return preg_replace('!\s+!', '', $str);
    }

    /**
     * ��ȡ��ȷʱ��
     */
    public static function mtime(){
        $t= explode(' ',microtime());
        $time = $t[0]+$t[1];
        return $time;
    }
    /**
     * ����HTML
     * @param $HTML
     * @param bool|true $br
     * @return mixed|string
     */
    public static function clear_html($HTML, $br = true){
        $HTML = htmlspecialchars(trim($HTML));
        $HTML = str_replace("\t", ' ', $HTML);
        if ($br) {
            return nl2br($HTML);
        } else {
            return str_replace("\n", '', $HTML);
        }
    }

    /**
     * ��obj���ת����array
     * @param  mixed $obj Ҫת�������� ���������� Ҳ�����Ǹ����� ��������һ����������
     * @return array || һ����������
     */
    public static function obj2array($obj){
        if (is_array($obj)) {
            foreach($obj as &$value) {
                $value = self::obj2array($value);
            }
            return $obj;
        } elseif (is_object($obj)) {
            $obj = get_object_vars($obj);
            return self::obj2array($obj);
        } else {
            return $obj;
        }
    }

    /**
     * ����ʱ���
     * @param float $pretime
     * @return float
     */
    public static function spend_time(&$pretime){
        $now = microtime(true);
        $spend = round($now - $pretime, 5);
        $pretime = $now;
        return $spend;
    }

    /**
     * @param $code
     */
    public static function check_code($code){
        header("Content-type: image/png");
        $fontsize = 18;$len = strlen($code);
        $width = 70;$height=27;
        $im = @imagecreatetruecolor($width, $height) or die("create image error!");
        $background_color = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $background_color);
        for ($i = 0; $i < 2000; $i++) {//��ȡ�����ɫ
            $line_color = imagecolorallocate($im, mt_rand(180,255),mt_rand(160, 255),mt_rand(100, 255));
            imageline($im,mt_rand(0,$width),mt_rand(0,$height), //��ֱ��
                mt_rand(0,$width), mt_rand(0,$height),$line_color);
            imagearc($im,mt_rand(0,$width),mt_rand(0,$height), //������
                mt_rand(0,$width), mt_rand(0,$height), $height, $width,$line_color);
        }
        $border_color = imagecolorallocate($im, 160, 160, 160);
        imagerectangle($im, 0, 0, $width-1, $height-1, $border_color);//�����Σ��߿���ɫ200,200,200

        for ($i = 0; $i < $len; $i++) {//д������ִ�
//            $current = $str[mt_rand(0, strlen($str)-1)];
            $text_color = imagecolorallocate($im,mt_rand(30, 140),mt_rand(30,140),mt_rand(30,140));
            imagechar($im,10,$i*$fontsize+6,rand(1,$height/3),$code[$i],$text_color);
        }
        imagejpeg($im);//��ʾͼ
        imagedestroy($im);//����ͼƬ
    }

    /**
     * ���ص�ǰ����ʽ��ʱ��,��λ��;��Ҫ���ڵ��Գ������ʱ��ʱ��
     * @return float
     */
    public static function microtime_float(){
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }
    /**
     * ����N�η���
     * @param $num
     * @param int $root
     * @return float
     */
    public static function croot($num, $root = 3){
        $root = intval($root);
        if (!$root) {
            return $num;
        }
        //exp:���� e  �� arg �η�ֵ��
        return exp(log($num) / $root);
    }

    /**
     * @param $array
     * @return mixed
     */
    public static function add_magic_quotes($array){
        foreach ((array) $array as $k => $v) {
            if (is_array($v)) {
                $array[$k] = self::add_magic_quotes($v);
            } else {
                $array[$k] = addslashes($v);
            }
        }
        return $array;
    }

    /**
     * �ַ�����ת��
     * @param $string
     * @return array|string
     */
    public static function add_slashes($string){
        if (!$GLOBALS['magic_quotes_gpc']) {
            if (is_array($string)) {
                foreach($string as $key => $val) {
                    $string[$key] = self::add_slashes($val);
                }
            } else {
                $string = addslashes($string);
            }
        }
        return $string;
    }

    /**
     * hex to binary
     * @param $hexdata
     * @return string
     */
    public static function hex2bin($hexdata)	{
        return pack('H*', $hexdata);
    }

    /**
     * ��ά���鰴��ָ���ļ�ֵ��������
     * @param array $arr
     * @param string $keys ���ݼ�ֵ
     * @param string $type ������
     * @return array  array(
     * array('name'=>'�ֻ�','brand'=>'ŵ����','price'=>1050),
     * array('name'=>'�ֱ�','brand'=>'����ŷ','price'=>960)
     * );$out = array_sort($array,'price');
     */
    public static function array_sort($arr, $keys, $type = 'asc'){
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }
    /**
     * �������飬��ÿ��Ԫ�ص��� $callback�����緵��ֵ��Ϊ��ֵ����ֱ�ӷ��ظ÷���ֵ��
     * ����ÿ�� $callback �����ؼ�ֵ�����շ��� false
     * @param  $array
     * @param  $callback
     * @return mixed
     */
    public static function array_try($array, $callback){
        if (!$array || !$callback) {
            return false;
        }
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        if (!$args) {
            $args = array();
        }
        foreach($array as $v) {
            $params = $args;
            array_unshift($params, $v);
            $x = call_user_func_array($callback, $params);
            if ($x) {
                return $x;
            }
        }
        return false;
    }
    // ��������Ĳ���
    public static function array_union(){
        $argsCount = func_num_args();
        if ($argsCount < 2) {
            return false;
        } else if (2 === $argsCount) {
            list($arr1, $arr2) = func_get_args();

            while ((list($k, $v) = each($arr2))) {
                if (!in_array($v, $arr1)) $arr1[] = $v;
            }
            return $arr1;
        } else { // �������ϵ�����ϲ�
            $arg_list = func_get_args();
            $all = call_user_func_array('array_union', $arg_list);
            return self::array_union($arg_list[0], $all);
        }
    }

    /**
     * ȡ�������е�n��
     * @param array $arr
     * @param int $index
     * @return array
     */
    public static function array_get($arr,$index){
        foreach($arr as $k=>$v){
            $index--;
            if($index<0) return array($k,$v);
        }
    }

    /**
     * �������AJAX���������
     * @param $data
     * @param bool|true $code ����״̬�룬 ͨ��0��ʾ����
     * @param string $info ���ص����ݼ���
     */
    public static function show_json($data,$code = true,$info=''){
        $use_time = self::mtime() - $GLOBALS['config']['app_startTime'];
        $result = array('code' => $code,'use_time'=>$use_time,'data' => $data);
        if ($info != '') {
            $result['info'] = $info;
        }
        header("X-Powered-By: kodExplorer.");
        header('Content-Type: application/json; charset=utf-8');
        exit(json_encode($result));
    }

    /**
     * ��ģ��ת�������ڸ������û�ȡ�б�
     * ������cute1:��һ���и���ַ�����cute2�ڶ����и���ַ���,
     * arraylistΪ��������ַ�����$this Ϊ��ǵ�ǰ�$this_strΪ�����ǵ��滻��
     * $tplΪ�������䵽��̬ģ��({0}�����и����ֵ,{1}�����и����ֵ,{this}����ǰ�����ֵ)
     * ���ӣ�
     * $arr="default=����(Ĭ��)=5|mac=mac����=6|mac1=mac1����=7";
     * $tpl="<li class='list {this}' theme='{0}'>{1}_{2}</li>\n";
     * echo getTplList('|','=',$arr,$tpl,'mac'),'<br/>';
     * @param $cute1
     * @param $cute2
     * @param $arraylist
     * @param $tpl
     * @param $this
     * @param string $this_str
     * @return string
     */
    public static function getTplList($cute1, $cute2, $arraylist, $tpl,$this,$this_str=''){
        $list = explode($cute1, $arraylist);
        if ($this_str == '') $this_str ="this";
        $html = '';
        foreach ($list as $value) {
            $info = explode($cute2, $value);
            $arr_replace = array();
            foreach ($info as $key => $v) {
                $arr_replace[$key]='{'.$key .'}';
            }
            if ($info[0] instanceof KoeTool) {// == $this
                $temp = str_replace($arr_replace, $info, $tpl);
                $temp = str_replace('{this}', $this_str, $temp);
            } else {
                $temp = str_replace($arr_replace, $info, $tpl);
                $temp = str_replace('{this}', '', $temp);
            }
            $html .= $temp;
        }
        return $html;
    }

    /**
     * ��ȡ��ǰurl��ַ
     * @return string
     */
    public static function get_url() {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT']
        == '443' ? 'https://' : 'http://';
        $php_self   = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info  = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] :
            $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }

    /**
     * ȥ��HTML�����е�HTML��ǩ�����ش��ı�
     * @param string $document ��������ַ���
     * @return string
     */
    public static function html2txt($document){
        $search = array ("'<script[^>]*?>.*?</script>'si", // ȥ�� javascript
            "'<[\\/\\!]*?[^<>]*?>'si", // ȥ�� HTML ���
            "'([\r\n])[\\s]+'", // ȥ���հ��ַ�
            "'&(quot|#34);'i", // �滻 HTML ʵ��
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
            "'&#(\\d+);'e"); // ��Ϊ PHP ��������
        $replace = array ("",
            "",
            "",
            "\"",
            "&",
            "<",
            ">",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            "chr(\\1)");
        $text = preg_replace ($search, $replace, $document);
        return $text;
    }

    /**
     * ��ȡ���ݵ�һ��
     * @param $content
     * @param $preg
     * @return mixed
     */
    public static function match($content, $preg){
        $preg = "/" . $preg . "/isU";
        preg_match($preg, $content, $result);
        return $result[1];
    }

    /**
     * ��ȡ����,��ȡһ��ҳ��������Ϣ.����� 1,2,3������
     * @param $content
     * @param $preg
     * @return mixed
     */
    public static function match_all($content, $preg){
        $preg = "/" . $preg . "/isU";
        preg_match_all($preg, $content, $result);
        return $result;
    }

    /**
     * ��ȡָ�����ȵ� utf8 �ַ���
     * @param string $string
     * @param int $length
     * @param string $dot
     * @return string
     */
    public static function get_utf8_str($string, $length, $dot = '...'){
        if (strlen($string) <= $length) return $string;

//        $strcut = '';
        $n = $tn = $noc = 0;

        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
                $noc++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n++;
            }
            if ($noc >= $length) break;
        }
        if ($noc > $length) {
            $n -= $tn;
        }
        if ($n < strlen($string)) {
            $strcut = substr($string, 0, $n);
            return $strcut . $dot;
        } else {
            return $string ;
        }
    }

    /**
     * �ַ�����ȡ��֧�����ĺ���������
     *
     * @param string $str ��Ҫת�����ַ���
     * @param int $start ��ʼλ��
     * @param string $length ��ȡ����
     * @param string $charset �����ʽ
     * @param bool|true $suffix �ض���ʾ�ַ�
     * @return string
     */
    public static function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true){
        if (function_exists("mb_substr")) {
            $i_str_len = mb_strlen($str);
            $s_sub_str = mb_substr($str, $start, $length, $charset);
            if ($length >= $i_str_len) {
                return $s_sub_str;
            }
            return $s_sub_str . '...';
        } elseif (function_exists('iconv_substr')) {
            return iconv_substr($str, $start, $length, $charset);
        }
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
        if ($suffix) return $slice . "��";
        return $slice;
    }

    public static function web2wap(&$content){
        $search = array ("/<img[^>]+src=\"([^\">]+)\"[^>]+>/siU",
            "/<a[^>]+href=\"([^\">]+)\"[^>]*>(.*)<\/a>/siU",
            "'<br[^>]*>'si",
            "'<p>'si",
            "'</p>'si",
            "'<script[^>]*?>.*?</script>'si", // ȥ�� javascript
            "'<[\\/\\!]*?[^<>]*?>'si", // ȥ�� HTML ���
            "'([\r\n])[\\s]+'", // ȥ���հ��ַ�
        ); // ��Ϊ PHP ��������
        $replace = array ("#img#\\1#/img#",
            "#link#\\1#\\2#/link#",
            "[br]",
            "",
            "[br]",
            "",
            "",
            "",
        );
        $publish_url = '';
        $text = preg_replace ($search, $replace, $content);
        $text = str_replace("[br]", "<br/>", $text);
        $img_start = "<img src=\"" . $publish_url . "automini.php?src=";
        $img_end = "&amp;pixel=100*80&amp;cache=1&amp;cacheTime=1000&amp;miniType=png\" />";
        $text = preg_replace ("/#img#(.*)#\\/img#/isUe", "'$img_start'.urlencode('\\1').'$img_end'", $text);
        $text = preg_replace ("/#link#(.*)#(.*)#\\/link#/isU", "<a href=\"\\1\">\\2</a>", $text);
        while (preg_match("/<br\\/><br\\/>/siU", $text)) {
            $text = str_replace('<br/><br/>', '<br/>', $text);
        }
        return $text;
    }

    /**
     * ��ȡ����������
     * eg hello="123" ��ȡss�ַ���
     * @param $aVar
     * @return int|string
     */
    public static function get_var_name(&$aVar){
        foreach($GLOBALS as $key => $var) {
            if ($aVar == $GLOBALS[$key] && $key != "argc") {
                return $key;
            }
        }
        return null;
    }
    // -----------------��������-------------------
    /**
     * ��ʽ��������������߶���
     *
     * @param mixed $var
     * @param boolean $exit
     */
    public static function pr($var, $exit = false){
        ob_start();
        $style = '<style>
        pre#debug{margin:10px;font-size:14px;color:#222;line-height:1.2em;background:#f6f6f6;border-left:5px solid #444;padding:5px;width:95%;word-break:break-all;}
        pre#debug b{font-weight:400;}
        #debug #debug_str{color:#E75B22;}
        #debug #debug_keywords{font-weight:800;color:#00f;}
        #debug #debug_tag1{color:#22f;}
        #debug #debug_tag2{color:#f33;font-weight:800;}
        #debug #debug_var{color:#33f;}
        #debug #debug_var_str{color:#f00;}
        #debug #debug_set{color:#0C9CAE;}</style>';
        if (is_array($var)) {
            print_r($var);
        } else if (is_object($var)) {
            echo get_class($var) . " Object";
        } else if (is_resource($var)) {
            echo (string)$var;
        } else {
            var_dump($var);
        }
        $out = ob_get_clean(); //���������$out ����
        $out = preg_replace('/"(.*)"/', '<b id="debug_var_str">"' . '\\1' . '"</b>', $out); //�����ַ�������
        $out = preg_replace('/=\>(.*)/', '=>' . '<b id="debug_str">' . '\\1' . '</b>', $out); //����=>�����ֵ
        $out = preg_replace('/\[(.*)\]/', '<b id="debug_tag1">[</b><b id="debug_var">' . '\\1' . '</b><b id="debug_tag1">]</b>', $out); //��������
        $from = array('    ', '(', ')', '=>');
        $to = array('  ', '<b id="debug_tag2">(</i>', '<b id="debug_tag2">)</b>', '<b id="debug_set">=></b>');
        $out = str_replace($from, $to, $out);

        $keywords = array('Array', 'int', 'string', 'class', 'object', 'null'); //�ؼ��ָ���
        $keywords_to = $keywords;
        foreach($keywords as $key => $val) {
            $keywords_to[$key] = '<b id="debug_keywords">' . $val . '</b>';
        }
        $out = str_replace($keywords, $keywords_to, $out);
        $out = str_replace("\n\n", "\n", $out);
        echo $style . '<pre id="debug"><b id="debug_keywords">' . self::get_var_name($var) . '</b> = ' . $out . '</pre>';
        if ($exit) exit; //Ϊ�����˳�
    }

    /**
     * ������������������ֵ��
     * ���������(�������͵ı���)
     */
    public static function debug_out(){
        $avg_num = func_num_args();
        $avg_list = func_get_args();
        ob_start();
        for($i = 0; $i < $avg_num; $i++) {
            self::pr($avg_list[$i]);
        }
        $out = ob_get_clean();
        echo $out;
        exit;
    }

    /**
     * ȡ$from~$to��Χ�ڵ������
     * @param mixed $from  ����
     * @param mixed $to ����
     * @return mixed
     */
    public static function rand_from_to($from, $to){
        $size = $from - $to; //��ֵ����
        $max = 30000; //���
        if ($size < $max) {
            return $from + mt_rand(0, $size);
        } else {
            if ($size % $max) {
                return $from + self::rand_from_to(0, $size / $max) * $max + mt_rand(0, $size % $max);
            } else {
                return $from + self::rand_from_to(0, $size / $max) * $max + mt_rand(0, $max);
            }
        }
    }

    /**
     * ��������ִ����������Զ��������� Ĭ�ϳ���6λ ��ĸ�����ֻ��
     *
     * @param int $len ����
     * @param string $type �ִ����ͣ�0 ��ĸ 1 ���� 2 ��д��ĸ 3 Сд��ĸ  4 ����
     * ����Ϊ������ĸ���(ȥ���� ���׻������ַ�oOLl������01��)
     * @return string
     */
    public static function randString($len = 4, $type='check_code'){
        $str = '';
        switch ($type) {
            case 0://��Сд��Ӣ��
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 1://����
                $chars = str_repeat('0123456789', 3);
                break;
            case 2://��д��ĸ
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 3://Сд��ĸ
                $chars = 'abcdefghijklmnopqrstuvwxyz';
                break;
            default:
                // Ĭ��ȥ�������׻������ַ�oOLl������01��Ҫ�����ʹ��addChars����
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
                break;
        }
        if ($len > 10) { // λ�������ظ��ַ���һ������
            $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $len);
        } else {
            // ���������
            for($i = 0; $i < $len; $i ++) {
                $str .= self::msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
            }
        }
        return $str;
    }

    /**
     * �����Զ�����
     */
    public static function make_password(){
        $temp = '0123456789abcdefghijklmnopqrstuvwxyz'.
            'ABCDEFGHIJKMNPQRSTUVWXYZ~!@#$^*)_+}{}[]|":;,.'.time();
        for($i=0;$i<10;$i++){
            $temp = str_shuffle($temp.substr($temp,-5));
        }
        return md5($temp);
    }


    /**
     * php DES���ܺ���
     *
     * @param string $key ��Կ
     * @param string $encrypted �����ַ���
     * @return string
     */
    public static function des_decode($key, $encrypted){
        $encrypted = base64_decode($encrypted);
        $td = mcrypt_module_open(MCRYPT_DES, '', MCRYPT_MODE_CBC, ''); //ʹ��MCRYPT_DES�㷨,cbcģʽ
//        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
//        $ks = mcrypt_enc_get_key_size($td);

        mcrypt_generic_init($td, $key, $key); //��ʼ����
        $decrypted = mdecrypt_generic($td, $encrypted); //����

        mcrypt_generic_deinit($td); //����
        mcrypt_module_close($td);
        return self::pkcs5_unpad($decrypted);
    }
    /**
     * php DES���ܺ���
     *
     * @param string $key ��Կ
     * @param string $text �ַ���
     * @return string
     */
    public static function des_encode($key, $text){
        $y = self::pkcs5_pad($text);
        $td = mcrypt_module_open(MCRYPT_DES, '', MCRYPT_MODE_CBC, ''); //ʹ��MCRYPT_DES�㷨,cbcģʽ
//        $ks = mcrypt_enc_get_key_size($td);
        mcrypt_generic_init($td, $key, $key); //��ʼ����
        $encrypted = mcrypt_generic($td, $y); //����
        mcrypt_generic_deinit($td); //����
        mcrypt_module_close($td);
        return base64_encode($encrypted);
    }
    public static function pkcs5_unpad($text){
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) return $text;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return $text;
        return substr($text, 0, -1 * $pad);
    }
    public static function pkcs5_pad($text, $block = 8){
        $pad = $block - (strlen($text) % $block);
        return $text . str_repeat(chr($pad), $pad);
    }








    //---------------- ԭ Controller/util.php -----------------------//

    //����ɱ�׼Ŀ¼
    public static function _DIR_CLEAR($path){
        $path = self::htmlspecial_decode($path);
        $path = str_replace('\\','/',trim($path));
        if (strstr($path,'../')) {//preg������
            $path = preg_replace('/\.+\/+/', '/', $path);
        }
        $path = preg_replace('/\/+/', '/', $path);
        return $path;
    }

//������û�Ŀ¼�����Ҳ��������Ŀ¼���������
    public static function _DIR($path){
        $path = self::_DIR_CLEAR(rawurldecode($path));
        $path = FileTool::iconv_system($path);
        if (substr($path,0,strlen('*recycle*/')) == '*recycle*/') {
            return USER_RECYCLE.str_replace('*recycle*/','',$path);
        }
        if (substr($path,0,strlen('*public*/')) == '*public*/') {
            return PUBLIC_PATH.str_replace('*public*/','',$path);
        }
        if (substr($path,0,strlen('*share*/')) == '*share*/') {
            return "*share*/";
        }
        defined('HOME') or define('HOME','');//*******
        $path = HOME.$path;
        if (is_dir($path)) $path = rtrim($path,'/').'/';
        return $path;
    }

//������û�Ŀ¼���
    public static function _DIR_OUT(&$arr){
        self::xxsClear($arr);
        if ($GLOBALS['is_root']) return;
        if (is_array($arr)) {
            foreach ($arr['filelist'] as $key => $value) {
                $arr['filelist'][$key]['path'] = self::pre_clear($value['path']);
            }
            foreach ($arr['folderlist'] as $key => $value) {
                $arr['folderlist'][$key]['path'] = self::pre_clear($value['path']);
            }
        }else{
            $arr = self::pre_clear($arr);
        }
    }
//ǰ׺���� ��root�û�Ŀ¼/��HOME��ʼ
    public static function pre_clear($path){
        if (ST=='share') {
            return str_replace(HOME,'',$path);
        }
        if (substr($path,0,strlen(PUBLIC_PATH)) == PUBLIC_PATH) {
            return '*public*/'.str_replace(PUBLIC_PATH,'',$path);
        }
        return str_replace(HOME,'',$path);
    }
    public static function xxsClear(&$list){
        if (is_array($list)) {
            foreach ($list['filelist'] as $key => $value) {
                $list['filelist'][$key]['ext'] = self::htmlspecial($value['ext']);
                $list['filelist'][$key]['path'] = self::htmlspecial($value['path']);
                $list['filelist'][$key]['name'] = self::htmlspecial($value['name']);
            }
            foreach ($list['folderlist'] as $key => $value) {
                $list['folderlist'][$key]['path'] = self::htmlspecial($value['path']);
                $list['folderlist'][$key]['name'] = self::htmlspecial($value['name']);
            }
        }else{
            $list = self::htmlspecial($list);
        }
    }
    public static function htmlspecial($str){
        return str_replace(
            array('<','>','"',"'"),
            array('&lt;','&gt;','&quot;','&#039;','&amp;'),
            $str
        );
    }
    public static function htmlspecial_decode($str){
        return str_replace(
            array('&lt;','&gt;','&quot;','&#039;'),
            array('<','>','"',"'"),
            $str
        );
    }

    /**
     * ��չ��Ȩ���ж�
     * @param $s
     * @param $info
     * @return mixed
     */
    public static function checkExtUnzip($s,$info){
        return self::checkExt($info['stored_filename']);
    }

    /**
     * ��չ��Ȩ���ж� ��Ȩ���򷵻�1 ����true
     * @param $file
     * @param bool|false $changExt
     * @return int
     */
    public static function checkExt($file,$changExt=false){
        if (strstr($file,'<') || strstr($file,'>') || $file=='') {
            return 0;
        }
        if ($GLOBALS['is_root'] == 1) return 1;
        $not_allow = $GLOBALS['auth']['ext_not_allow'];
        $ext_arr = explode('|',$not_allow);
        foreach ($ext_arr as $current) {
            $current = trim($current);
            if ($current !== '' && stristr($file,'.'.$current)){//������չ��
                return 0;
            }
        }
        return 1;
    }

    public static function php_env_check(){
        $L = $GLOBALS['L'];
        $error = '';
        $base_path = FileTool::get_path_this(BASIC_PATH).'/';
        if(!function_exists('iconv')) $error.= '<li>'.$L['php_env_error_iconv'].'</li>';
        if(!function_exists('mb_convert_encoding')) $error.= '<li>'.$L['php_env_error_mb_string'].'</li>';
        if(!version_compare(PHP_VERSION,'5.0','>=')) $error.= '<li>'.$L['php_env_error_version'].'</li>';
        if(!function_exists('file_get_contents')) $error.='<li>'.$L['php_env_error_file'].'</li>';
        if(!FileTool::path_writable(BASIC_PATH)) $error.= '<li>'.$base_path.'	'.$L['php_env_error_path'].'</li>';
        if(!FileTool::path_writable(BASIC_PATH.'data')) $error.= '<li>'.$base_path.'data	'.$L['php_env_error_path'].'</li>';
        if(!FileTool::path_writable(BASIC_PATH.'data/system')) $error.= '<li>'.$base_path.'data/system	'.$L['php_env_error_path'].'</li>';
        if(!FileTool::path_writable(BASIC_PATH.'data/User')) $error.= '<li>'.$base_path.'data/User	'.$L['php_env_error_path'].'</li>';
        if(!FileTool::path_writable(BASIC_PATH.'data/thumb')) $error.= '<li>'.$base_path.'data/thumb	'.$L['php_env_error_path'].'</li>';
        if( !function_exists('imagecreatefromjpeg')||
            !function_exists('imagecreatefromgif')||
            !function_exists('imagecreatefrompng')||
            !function_exists('imagecolorallocate')){
            $error.= '<li>'.$L['php_env_error_gd'].'</li>';
        }
        return $error;
    }

    /**
     * ���԰����أ����ȼ���cookie��ȡ>�Զ�ʶ��
     * �״�û��cookie���Զ�ʶ�𡪡�����cookie,����ʱ������
     */
    public static function init_lang(){
        if (isset($_COOKIE['kod_user_language'])) {
            $lang = $_COOKIE['kod_user_language'];
        }else{//û��cookie
            preg_match('/^([a-z\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
            $lang = $matches[1];
            switch (substr($lang,0,2)) {
                case 'zh':
                    if ($lang != 'zn-TW'){
                        $lang = 'zh-CN';
                    }
                    break;
                case 'en':$lang = 'en';break;
                default:$lang = 'en';break;
            }
            $lang = str_replace('-', '_',$lang);
            setcookie('kod_user_language',$lang, time()+3600*24*365);
        }
        if ($lang == '') $lang = 'en';

        $lang = str_replace(array('/','\\','..','.'),'',$lang);
        define('LANGUAGE_TYPE', $lang);
        include(LANGUAGE_PATH.$lang.'/main.php');
        $GLOBALS['L'] = $L;
    }

    public static function init_setting(){
        $setting_file = USER_SYSTEM.'system_setting.php';
        if (!file_exists($setting_file)){//����������
            $setting = $GLOBALS['config']['setting_system_default'];
            $setting['menu'] = $GLOBALS['config']['setting_menu_default'];
            fileCache::save($setting_file,$setting);
        }else{
            $setting = fileCache::load($setting_file);
        }
        if (!is_array($setting)) {
            $setting = $GLOBALS['config']['setting_system_default'];
        }
        if (!is_array($setting['menu'])) {
            $setting['menu'] = $GLOBALS['config']['setting_menu_default'];
        }

        $GLOBALS['app']->setDefaultController($setting['first_in']);//����Ĭ�Ͽ�����
        $GLOBALS['app']->setDefaultAction('index');    //����Ĭ�Ͽ���������

        $GLOBALS['config']['setting_system'] = $setting;//ȫ��
        $GLOBALS['L']['kod_name'] = $setting['system_name'];
        $GLOBALS['L']['kod_name_desc'] = $setting['system_desc'];
        if (isset($setting['powerby'])) {
            $GLOBALS['L']['kod_power_by'] = $setting['powerby'];
        }
    }

}