<?php
/**
 * @author Lin
 * 2015年8月15日 上午11:04:13
 */
namespace System\Core;
use System\Exception\URLRewriteFailedException;
use System\Mist;
use System\Utils\Util;
defined('BASE_PATH') or die('No Permission!');
/**
 * Class URLHelper URL解析和构建助手
 * @package System\Core
 * 相对于ThinkPHP的改变：
 * 解析：
 *      ①common模式
 *      ②pathinfo模式
 *      ③compatible模式：获取pathinfo变量后依据pathinfo模式解析
 *      ④（已删除，改为rewrite引擎开关）rewrite模式：语句pathinfo的配置解析，对于解析无影响
 * 创建：
 *      ①common模式
 *      ②pathinfo模式：创建url带上index.php
 *      ③compatible模式：创建url时带上入口文件和pathinfo变量，并语句pathinfo创建规则创建URL
 *      ④（已删除，改为rewrite引擎开关）rewrite模式：根据url模式省略对应的pathinfo变量或者入口文件
 */
final class URLHelper{

    /**
     * 惯例配置配置
     * @var array
     */
    protected static $_convention = array(
        //普通模式 与 兼容模式 获取$_GET变量名称
        'URL_MODULE_VARIABLE'   => 'm',
        'URL_CONTROLLER_VARIABLE'   => 'c',
        'URL_ACTION_VARIABLE'   => 'a',
        'URL_COMPATIBLE_VARIABLE' => 'pathinfo',

        //兼容模式和PATH_INFO模式下的解析配置，也是URL生成配置
        'MM_BRIDGE'     => '+',//模块与模块之间的连接桥
        'MC_BRIDGE'     => '/',
        'CA_BRIDGE'     => '/',
        'AP_BRIDGE'     => '-',//*** 必须保证操作与控制器之间的符号将是$_SERVER['PATH_INFO']字符串中第一个出现的
        'PP_BRIDGE'     => '-',//参数与参数之间的连接桥
        'PKV_BRIDGE'    => '-',//参数的键值对之前的连接桥

        //伪装的后缀，不包括'.'号
        'MASQUERADE_TAIL'   => '.html',
        //重写模式下 消除的部分，对应.htaccess文件下
        'REWRITE_HIDDEN'      => '/index.php',

        //默认的模块，控制器和操作
        'DEFAULT_MODULE'      => 'Home',
        'DEFAULT_CONTROLLER'  => 'Index',
        'DEFAULT_ACTION'      => 'index',

        //是否开启子域名部署
        'DOMAIN_DEPLOY_ON'    => false,
        //子域名部署模式下 的 完整域名
        'FUL_DOMAIN'=>'',
        //子域名部署规则
        'SUB_DOMAIN_DEPLOY_RULES' => array(
            /**
             * 分别对应子域名模式下 的默认访问模块、控制器、操作和参数
             * 设置为null是表示不做设置，将使用默认的通用配置
             */
        ),
    );

    /**
     * 解析结果 组成部件
     * @var array
     */
    private static $_components = array();

    /**
     * 初始化
     * @param array $config 配置
     * @return void
     * @throws \System\Exception\ConfigLoadFailedException
     */
    public static function init($config=null){
        Mist::status('urlhelper_init_begin');
        //初始化类
        Util::mergeConf(self::$_convention,
            isset($config) ? $config : Configer::load('url'),
            true
        );
        //参数确实时返回默认配置
        self::$_components['m'] = self::$_convention['DEFAULT_MODULE'];
        self::$_components['c'] = self::$_convention['DEFAULT_CONTROLLER'];
        self::$_components['a'] = self::$_convention['DEFAULT_ACTION'];
        self::$_components['p'] = array();

        Mist::status('urlhelper_init_done');
    }


    /**
     * 解析URL中的参数信息
     * 兼容四种模式下的url
     *
     * 访问如http://localhost/MinShuttler/Public时
     *  REQUEST_URI : /MinShuttler/Public/
     *  SCRIPT_NAME : /MinShuttler/Public/index.php
     * @return array 解析结果
     * @throws \Exception
     */
	public static function parse(){
        if(URLMODE_TOPSPEED_ON){//极速模式下只使用于common模式
            self::parseByCommon();
        }else{
            //非极速模式下可自由使用
            switch(self::checkUrlMode()){
                case URLMODE_COMMON:
                    self::parseByCommon();
                    break;
                case URLMODE_COMPATIBLE:
                    self::parseByCompatible();
                    break;
                case URLMODE_PATHINFO:
                    self::parseByPathinfo();
                    break;
                default:
                    throw new \Exception('Unknown url mode:'.self::checkUrlMode());
            }
        }
        //将参数释放到$_GET数组中
        $_GET = array_merge($_GET,self::$_components['p']);
        return self::$_components;
	}

    public static function parseDomian(){
        $conf = &self::$_convention;
        //获取子域名的部分，并且小写
        $name = strtolower(trim(strstr($_SERVER['HTTP_HOST'], $conf['FUL_DOMAIN'], true), '.'));
        if (isset($conf['SUB_DOMAIN_DEPLOY_RULES'][$name])) {
            //获取对应的规则并解析之
            $rule = self::$_convention['SUB_DOMAIN_DEPLOY_RULES'][$name];
            if (is_string($rule) && 0 === strpos($rule, 'http')) {
                Util::redirect($rule);
            }
            if (isset($rule[0])) {
                self::$_components['m'] = array_map(function ($val) {
                    return ucwords($val);
                }, explode('/', $rule[0]));
            }
            self::$_convention['c'] = isset($rule[1]) ? $rule[1] : NULL;
            self::$_convention['a'] = isset($rule[2]) ? $rule[2] : NULL;
            if (isset($rule[3])) {
                $query = null;
                if(is_array($rule[3])){
                    $query = &$rule[3];
                }elseif(is_string($rule[3])){
                    parse_str($rule[3], $query);
                }
                //query 不为null或者空数组时合并
                $query and self::$_convention['p'] = array_merge(self::$_components['p'],$rule[3]);
            }
        }
    }

    /**
     * <关键>
     * 确定当前访问的URL的URL模式
     * @return int
     */
    public static function checkUrlMode(){
        static $mode = null;
        Mist::status('parseurl_checkmode_begin');
        if(null === $mode){
            if(isset($_GET[self::$_convention['URL_MODULE_VARIABLE']]) and
                isset($_GET[self::$_convention['URL_CONTROLLER_VARIABLE']]) and
                isset($_GET[self::$_convention['URL_ACTION_VARIABLE']])){
                //设置了普通模式变量将被认为是普通模式(必须三个全部被设置)
                //普通模式下不在乎URL有多么不友好，所以参数必须写全
                $mode = URLMODE_COMMON;
            }elseif(isset($_GET[self::$_convention['URL_COMPATIBLE_VARIABLE']]) and count($_GET) === 1){
                //未设置普通模式下的变量 且 设置了pathinfo变量(唯一)时将被认定为compatible模式
                $mode = URLMODE_COMPATIBLE;
            }else{
                if(isset($_SERVER['PATH_INFO'])){
                    $mode = URLMODE_PATHINFO;
                }else{
                    //类似访问了 www.a.com/index.php 时将被认为是普通模式
                    $mode = URLMODE_COMMON;
                }
            }
        }
        Mist::status('parseurl_checkmode_end');
        return $mode;
    }

    /**
     * 解析普通模式下URL信息 或者 极速模式下的URL解析
     * 注：使用了ucwords，对模块和控制器而言可以不考虑大小写的问题
     * @return void
     */
    public static function parseByCommon(){
        Mist::status('parseurl_in_common_begin');
        $mName  = &self::$_convention['URL_MODULE_VARIABLE'];
        $cName  = &self::$_convention['URL_CONTROLLER_VARIABLE'];
        $aName  = &self::$_convention['URL_ACTION_VARIABLE'];
        //获取模块名称
//        Util::dump(strpos($_GET[$mName],self::$_convention['MM_BRIDGE']),self::$_convention['MM_BRIDGE'],$_GET[$mName]);exit;
        if(isset($_GET[$mName])){
            if(false === strpos($_GET[$mName],self::$_convention['MM_BRIDGE'])){
                //不存在多个模块
                self::$_components['m'] = ucwords($_GET[$mName]);
            }else{
                self::$_components['m'] =array_map(function($val){
                    return ucwords($val);
                },explode(self::$_convention['MM_BRIDGE'],$_GET[$mName]));
            }
        }

        //获取控制器名称
        isset($_GET[$cName]) and
            self::$_components['c'] = ucwords($_GET[$cName]);

        //获取操作名称
        isset($_GET[$aName]) and
            self::$_components['a'] = $_GET[$aName];
        
        unset($_GET[$mName],$_GET[$cName],$_GET[$aName]);
        //参数为剩余的变量
        self::$_components['p'] = $_GET;
        Mist::status('parseurl_in_common_end');
    }

    /**
     * 解析compatible模式下的URL信息
     * 实现上仅仅是获取pathinfo变量（设置$_SERVER['PATH_INFO']）再调用self::parseByPathinfo()设置结果集
     * @return void
     */
    public static function parseByCompatible(){
        $_SERVER['PATH_INFO'] = $_GET[self::$_convention['URL_COMPATIBLE_VARIABLE']];
        unset($_GET[self::$_convention['URL_COMPATIBLE_VARIABLE']]);
        self::parseByPathinfo();
    }

    /**
     * 解析pathinfo模式或者rewrite模式下的URL信息
     * 考虑多级模块的情况:
     *      ①获取操作及之前的部分 和 参数部分
     *      ②从后往前依次获取操作、控制器和模块列表
     *      ③参数从前往后解析
     * @return void
     */
    public static function parseByPathinfo(){
        //-- 检查PATH_INFO设置 --//
        Mist::status('parseurl_in_pathinfo_begin');
        if(!isset($_SERVER['PATH_INFO'])) {
            //在不支持PATH_INFO...或者PATH_INFO不存在的情况下(URL省略将被认定为普通模式)
            //REQUEST_URI获取原生的URL地址进行解析(返回脚本名称后面的部分)
            $pos = stripos($_SERVER['REQUEST_URI'],$_SERVER['SCRIPT_NAME']);
            if(0 === $pos){//PATHINFO模式
                $_SERVER['PATH_INFO'] = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));
            }else{
                //重写模式
                $_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'];
            }
        }
        //检查伪装的后缀
        $position = stripos($_SERVER['PATH_INFO'],self::$_convention['MASQUERADE_TAIL']);
        //$position === false 表示 不存在伪装的后缀或者相关带嫌疑的url部分
        if(false !== $position and
                strlen($_SERVER['PATH_INFO']) === ($position + strlen(self::$_convention['MASQUERADE_TAIL']))){////伪装的后缀出现在最后的位置时
            $_SERVER['PATH_INFO'] = substr($_SERVER['PATH_INFO'],0,$position);

        }
        Mist::status('parseurl_in_pathinfo_getpathinfo_done');

        //-- 解析PATHINFO --//
        //截取参数段param与定位段local
        $papos          = strpos($_SERVER['PATH_INFO'],self::$_convention['AP_BRIDGE']);
        $mcapart = $pparts = '';
        if(false === $papos){
            $mcapart  = trim($_SERVER['PATH_INFO'],'/');//不存在参数则认定PATH_INFO全部是MCA的部分，否则得到结果substr($_SERVER['PATH_INFO'],0,0)即空字符串
        }else{
            $mcapart  = trim(substr($_SERVER['PATH_INFO'],0,$papos),'/');
            $pparts   = substr($_SERVER['PATH_INFO'],$papos + strlen(self::$_convention['AP_BRIDGE']));
        }

        //-- 解析MCA部分 --//
        //逆向检查CA是否存在衔接
        $capos = strrpos($mcapart,self::$_convention['CA_BRIDGE']);
        if(false === $capos){
            //找不到控制器与操作之间分隔符（一定不存在控制器）
            //先判断位置部分是否为空字符串来决定是否有操作名称
            if(strlen($mcapart)){
                //位置字段全部是字符串的部分
                self::$_components['a'] = $mcapart;
            }else{
                //没有操作部分，MCA全部使用默认的
            }
        }else{
            //apos+CA_BRIDGE 后面的部分全部算作action
            self::$_components['a'] = substr($mcapart,$capos+strlen(self::$_convention['CA_BRIDGE']));

            //CA存在衔接符 则说明一定存在控制器
            $mcalen = strlen($mcapart);
            $mcpart = substr($mcapart,0,$capos-$mcalen);//去除了action的部分
//Util::dump($mcpart,self::$_convention['MC_BRIDGE'],strpos($mcpart,self::$_convention['MC_BRIDGE']),$capos,$mcalen);exit;
            if(strlen($mcapart)){
                $mcpos = strpos($mcpart,self::$_convention['MC_BRIDGE']);
                if(false === $mcpos){
                    //不存在模块
                    if(strlen($mcpart)){
                        //全部是控制器的部分
                        self::$_components['c'] = ucwords($mcpart);
                    }else{
                        //没有控制器部分，则使用默认的
                    }
                }else{
                    //截取控制器的部分
                    self::$_components['c']   = ucwords(substr($mcpart,$mcpos+strlen(self::$_convention['MC_BRIDGE'])));

                    //既然存在MC衔接符 说明一定存在模块
                    $mpart = substr($mcpart,0,$mcpos-strlen($mcpart));//以下的全是模块部分的字符串
                    if(strlen($mpart)){
                        if(false === strpos($mpart,self::$_convention['MM_BRIDGE'])){
                            self::$_components['m'] = ucwords($mpart);
                        }else{
                            self::$_components['m'] =
                                array_map(function($val){
                                    return ucwords($val);
                                },explode(self::$_convention['MM_BRIDGE'],$mpart));
                        }
                    }else{
                        //一般存在衔接符的情况下不为空,但也考虑下特殊情况
                    }
                }
            }else{
                //一般存在衔接符的情况下不为空,但也考虑下特殊情况
            }
        }
        Mist::status('parseurl_in_pathinfo_getmac_done');

        //-- 解析参数部分 --//
        self::$_components['p'] = self::switchTranslateParameters($pparts,false);
        Mist::status('parseurl_in_pathinfo_end');
    }

    /**
     * 加入匿名URL参数
     * @param $value
     * @param array $pc
     * @return void
     */
    private static function pushAnonParam($value,&$pc=null){
        if(null === $pc){
            $pc = self::$_components['p'];
        }
        !isset($pc['anonymous']) and $pc['anonymous'] = array();
        $pc['anonymous'][] = $value;
    }

    /**
     * 创建当前模式下的URL
     * @param null|string $modulelist 模块列表
     * @param null|string $controller 控制器名称
     * @param null|string $action 操作名称
     * @param array $params 参数列表
     * @return string URL字符串
     * @throws \Exception
     */
    public static function create($modulelist=null,$controller=null,$action=null,$params=array()){
        isset($modulelist) or $modulelist = self::$_components['m'];
        isset($controller) or $controller = self::$_components['c'];
        isset($action)     or $action     = self::$_components['a'];
        $url = null;
        if(URLMODE_TOPSPEED_ON){
            $url = self::createInCommon($modulelist,$controller,$action,$params);
        }else{
            switch(URL_MODE){
                case URLMODE_COMMON:
                    $url = self::createInCommon($modulelist,$controller,$action,$params);
                    break;
                case URLMODE_PATHINFO:
                    $url = self::createInPathinfo($modulelist,$controller,$action,$params);
                    break;
                case URLMODE_COMPATIBLE:
                    $url = self::createInCompatible($modulelist,$controller,$action,$params);
                    break;
                default:
                    throw new \Exception('Unknown url mode:'.URL_MODE);
            }
        }
        return $url;
    }


    /**
     * 创建普通模式下的URL
     * @param string|array $modules
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return string
     */
    public static function createInCommon($modules,$controller,$action,array $params){
        if(is_array($modules)) self::translateModules($modules);
        return $_SERVER['SCRIPT_NAME'].'?'.http_build_query(array_merge(array(
            self::$_convention['URL_MODULE_VARIABLE'] => $modules,
            self::$_convention['URL_CONTROLLER_VARIABLE'] => $controller,
            self::$_convention['URL_ACTION_VARIABLE'] => $action,
        ),$params));
    }
    /**
     * 创建PATHINFO模式的URL
     * 更具是否开启rewrite功能来决定是否省略入口文件
     * @param $modules
     * @param $controller
     * @param $action
     * @param $params
     * @param bool $withtail
     * @return string
     */
    public static function createInPathinfo($modules,$controller,$action,$params,$withtail=true){
        if(is_array($modules)) self::translateModules($modules);
        $conf = &self::$_convention;
        $params = self::switchTranslateParameters($params);
        empty($params) or $params = "{$conf['AP_BRIDGE']}{$params}";
        $url = $_SERVER['SCRIPT_NAME']."/{$modules}{$conf['MC_BRIDGE']}{$controller}{$conf['CA_BRIDGE']}{$action}{$params}";
        if(isset(self::$_convention['MASQUERADE_TAIL']) and $withtail){
            $url .= self::$_convention['MASQUERADE_TAIL'];
        }
        REWRITE_ENGINE_ON and self::applyRewriteHidden($url);
        return $url;
    }

    /**
     * 创建compatible模式下的URL
     * @param $modules
     * @param $controller
     * @param $action
     * @param $params
     * @param bool $withtail
     * @return string
     */
    public static function createInCompatible($modules,$controller,$action,$params,$withtail=true){
        if(is_array($modules)) self::translateModules($modules);
        $conf = &self::$_convention;
        $params = self::switchTranslateParameters($params);
        empty($params) or $params = "{$conf['AP_BRIDGE']}{$params}";
        $url = $_SERVER['SCRIPT_NAME']."?{$conf['URL_COMPATIBLE_VARIABLE']}{$modules}{$conf['MC_BRIDGE']}{$controller}{$conf['CA_BRIDGE']}{$action}{$params}";
        if(isset(self::$_convention['MASQUERADE_TAIL']) and $withtail){
            $url .= self::$_convention['MASQUERADE_TAIL'];
        }
        REWRITE_ENGINE_ON and self::applyRewriteHidden($url);
        return $url;
    }

    /**
     * 实现模块序列的字符串和数组之间的转化
     * @param string|array $modules 模块序列
     * @param bool $toString
     */
    public static function translateModules(&$modules,$toString=true){
        if($toString){//数组转字符串
            if(is_array($modules)){
                $modules = implode(self::$_convention['MM_BRIDGE'],$modules);
            }
            $modules = trim($modules,'/');
        }else{//字符串转数组
            $modules = array_map(function($val){
                return ucwords($val);
            },explode(self::$_convention['MM_BRIDGE'],$modules));
        }
    }

    /**
     * Pathinfo及相关模式下pathinfo参数的创建和解析
     * @param array|string $params 需要解析的参数
     * @param bool $toString 是否将参数解析成字符串，默认是
     * @return array|string
     */
    private static function switchTranslateParameters($params,$toString=true){
        $ppb    = &self::$_convention['PP_BRIDGE']; //参数与参数之间的衔接符
        $pkvb   = &self::$_convention['PKV_BRIDGE'];//参数键值对之间的衔接符

        if($toString){
            //希望返回的是字符串是，返回值是void，直接修改自$params
            $temp = '';
            if($params){
                foreach($params as $key => $val){
                    $temp .= "{$key}{$pkvb}{$val}{$ppb}";
                }
                return substr($temp,0,strlen($temp) - strlen($ppb));
            }else{
                return '';
            }
        }else{
            //解析字符串成数组
            $pc = array();
            if($ppb !== $pkvb){//使用不同的分割符
                $parampairs = explode($ppb,$params);
                foreach($parampairs as $val){
                    $pos = strpos($val,$pkvb);
                    if(false === $pos){
                        //非键值对，赋值数字键
                        self::pushAnonParam($val,$pc);
                    }else{
                        $key = substr($val,0,$pos);
                        $val = substr($val,$pos+strlen($pkvb));
                        $pc[$key] = $val;
                    }
                }
            }else{//使用相同的分隔符
                $elements = explode($ppb,$params);
                $count = count($elements);
                for($i=0; $i<$count; $i += 2){
                    if(isset($elements[$i+1])){
                        $pc[$elements[$i]] = $elements[$i+1];
                    }else{
                        self::pushAnonParam($elements[$i],$pc);//单个将被投入匿名参数
                    }
                }
            }
            return $pc;
        }
    }

    /**
     * 创建网站地址
     */
    public static function createRootURL(){
        static $_root = null;
        isset($_root) or $_root = str_replace(Util::path($_SERVER['SCRIPT_NAME']),'',Util::path(dirname(dirname(__FILE__))).'/index.php').'/';
    }

    public static function createTemplateConstant($modulelist=null,$controller=null,$action=null,array $params=array()){
        $url = null;
        if(URLMODE_TOPSPEED_ON){
            if(is_array($modulelist)) self::translateModules($modulelist);
            $query = http_build_query(array_merge(array(
                self::$_convention['URL_MODULE_VARIABLE'] => $modulelist,
                self::$_convention['URL_CONTROLLER_VARIABLE'] => $controller,
                self::$_convention['URL_ACTION_VARIABLE'] => $action,
            ),$params));
            $url = $_SERVER['SCRIPT_NAME'].(empty($query)?'':"?{$query}");
        }else{
            switch(URL_MODE){
                case URLMODE_COMMON:
                    $url = self::createInCommon($modulelist,$controller,$action,$params);
                    break;
                case URLMODE_PATHINFO:
                    $url = self::createInPathinfo($modulelist,$controller,$action,$params,false);
                    break;
                case URLMODE_COMPATIBLE:
                    $url = self::createInCompatible($modulelist,$controller,$action,$params,false);
                    break;
                default:
                    throw new \Exception('Unknown url mode:'.URL_MODE);
            }
        }
        return rtrim($url,'/');
    }

    /**
     * 对url进行url重写处理，需要借助以.htaccess或者虚拟主机配置才能正常解析URL
     * @param $url
     * @throws URLRewriteFailedException
     */
    private static function applyRewriteHidden(&$url){
        $pos = stripos($url,self::$_convention['REWRITE_HIDDEN']);//获取第一个位置
        if(false !== $pos){
            $url = Util::strReplaceJustOnce(self::$_convention['REWRITE_HIDDEN'],'',$url);
        }
    }



	
}