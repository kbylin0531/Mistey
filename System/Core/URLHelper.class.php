<?php
/**
 * @author Lin
 * 2015年8月15日 上午11:04:13
 */
namespace System\Core;
use System\Exception\URLRewriteFailedException;
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
     * 管理配置
     * @var array
     */
    protected static $_convention = array(
        //普通模式获取变量
        'URL_MODULE_VARIABLE'   => 'm',
        'URL_CONTROLLER_VARIABLE'   => 'c',
        'URL_ACTION_VARIABLE'   => 'a',
        //兼容模式获取变量
        'URL_COMPATIBLE_VARIABLE' => 'pathinfo',

        //兼容模式和PATH_INFO模式下的解析配置，也是URL生成配置
        'MM_BRIDGE'     => '+',//模块与模块之间的连接桥
        'MC_BRIDGE'     => '/',
        'CA_BRIDGE'     => '/',
        'AP_BRIDGE'     => '_',//*** 必须保证操作与控制器之间的符号将是$_SERVER['PATH_INFO']字符串中第一个出现的
        'PP_BRIDGE'     => '/',//参数与参数之间的连接桥
        'PKV_BRIDGE'    => '/',//参数的键值对之前的连接桥

        //伪装的后缀，不包括'.'号
        'MASQUERADE_TAIL'   => 'html',
        'REWRITE_HIDDEN'      => '/index.php',

        //参数缺失时
        'DEFAULT_MODULE'      => 'Home',
        'DEFAULT_CONTROLLER'  => 'Index',
        'DEFAULT_ACTION'      => 'index',


        'DOMAIN_DEPLOY_ON'    => false,
        //完整域名
        'FUL_DOMAIN'=>'',
        //子域名部署规则
        'SUB_DOMAIN_DEPLOY_RULES' => array(
            //正式的URL规则是从前往后一次是  [Modulelist,Controller,Action,Query]，如果某一段不想设置却需要设置之后的部分，就需要将不想设置的地方设置为NULL
        ),
    );

    /**
     * 存放原始$_SERVER数组
     * URL助手类原则上不改变$_SERVER超全局变量
     * @var array
     */
    private static $_server = null;

    /**
     * 解析结果集
     * @var array
     */
    private static $_parseSet = array();

    private static $_inited = false;

    /**
     * 初始化
     * @return void
     * @throws \System\Exception\ConfigLoadFailedException
     */
    public static function init(){
        Util::status('urlhelper_init_begin');
        if(self::$_inited) return;//只能初始化一次
        self::$_server = $_SERVER;
        //获取静态方法调用的类名称使用get_called_class,对象用get_class
        Util::mergeConf(self::$_convention,ConfigHelper::loadConfig('url'),true);
        //参数确实时返回默认配置
        self::$_parseSet['m'] = self::$_convention['DEFAULT_MODULE'];
        self::$_parseSet['c'] = self::$_convention['DEFAULT_CONTROLLER'];
        self::$_parseSet['a'] = self::$_convention['DEFAULT_ACTION'];
        self::$_parseSet['p'] = array();

        Util::status('urlhelper_init_done');
        //表示已经初始化过了
        self::$_inited = true;
    }

    /**
     * 获取解析结果集
     * @param string $key
     * @return array
     */
    public static function getParsedResult($key = null){
        return isset($key)?self::$_parseSet[$key]:self::$_parseSet;
    }

    /**
     * 解析URL中的参数信息
     * 兼容四种模式下的url
     * @return array 解析结果
     * @throws \Exception
     */
	public static function parse(){
        //访问如http://localhost/MinShuttler/Public时
        //REQUEST_URI : /MinShuttler/Public/
        //SCRIPT_NAME : /MinShuttler/Public/index.php

//        Util::dump($_SERVER['REQUEST_URI'],$_SERVER['SCRIPT_NAME']);exit;

        self::$_inited or self::init();
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
//        Util::dump($_GET,self::$_parseSet['p']);
        $_GET = array_merge($_GET,self::$_parseSet['p']);
        return self::$_parseSet;
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
                self::$_parseSet['m'] = array_map(function ($val) {
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
                $query and self::$_convention['p'] = array_merge(self::$_parseSet['p'],$rule[3]);
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
        if(null === $mode){//设置了普通模式变量将被认为是普通模式(必须三个全部被设置)
            if(isset($_GET[self::$_convention['URL_MODULE_VARIABLE']]) and
                isset($_GET[self::$_convention['URL_CONTROLLER_VARIABLE']]) and
                isset($_GET[self::$_convention['URL_ACTION_VARIABLE']])){
                //普通模式下不能省略三个url变量才能被认定为common模式
                $mode = URLMODE_COMMON;
            }elseif(isset($_GET[self::$_convention['URL_COMPATIBLE_VARIABLE']]) and count($_GET) === 1){
                //未设置普通模式下的变量 且 设置了pathinfo变量(唯一)时将被认定为compatible模式
                $mode = URLMODE_COMPATIBLE;
            }else{
                //URL中没有这两个以上的这些变量 可能的情况
                if(isset($_SERVER['PATH_INFO'])){//省略了
                    $mode = URLMODE_PATHINFO;
                }else{
                    $mode = URLMODE_COMMON;
                }
            }
        }
        return $mode;
    }

    /**
     * 解析普通模式下URL信息 或者 极速模式下的URL解析
     * 注：使用了ucwords，对模块和控制器而言可以不考虑大小写的问题
     * @return void
     */
    public static function parseByCommon(){
        $mName  = &self::$_convention['URL_MODULE_VARIABLE'];
        $cName  = &self::$_convention['URL_CONTROLLER_VARIABLE'];
        $aName  = &self::$_convention['URL_ACTION_VARIABLE'];

        //获取模块名称
        isset($_GET[$mName]) and
            self::$_parseSet['m'] =array_map(function($val){
                    return ucwords($val);
                },explode('/',$_GET[$mName]));

        //获取控制器名称
        isset($_GET[$cName]) and
            self::$_parseSet['c'] = ucwords($_GET[$cName]);

        //获取操作名称
        isset($_GET[$aName]) and
            self::$_parseSet['a'] = $_GET[$aName];
        
        unset($_GET[$mName],$_GET[$cName],$_GET[$aName]);
        //获取参数
        self::$_parseSet['p'] = $_GET;
    }

    /**
     * 解析compatible模式下的URL信息
     * 实现上仅仅是获取pathinfo变量（设置$_SERVER['PATH_INFO']）再调用self::parseByPathinfo()设置结果集
     * @return void
     */
    public static function parseByCompatible(){
        self::$_server['PATH_INFO'] = $_GET[self::$_convention['URL_COMPATIBLE_VARIABLE']];
        unset($_GET[self::$_convention['URL_COMPATIBLE_VARIABLE']]);
        self::parseByPathinfo();
    }

    /**
     * 解析pathinfo模式或者rewrite模式下的URL信息
     * 考虑多级模块的情况:
     *      ①获取操作及之前的部分 和 参数部分
     *      ②从后往前依次获取操作、控制器和模块列表
     *      ③参数从前往后解析
     * @param string $pathinfo 优先解析的PATH_INFO信息
     * @return void
     */
    public static function parseByPathinfo($pathinfo=null){
        //-- 检查PATH_INFO设置 --//
        Util::status('check_pathinfo_begin');
        if(isset($pathinfo)){
            self::$_server['PATH_INFO'] = $pathinfo;
        }elseif(!isset(self::$_server['PATH_INFO'])) {
            //在不支持PATH_INFO或者PATH_INFO不存在的情况下(URL省略)
            //REQUEST_URI获取原生的URL地址进行解析(返回脚本名称后面的部分),此外特殊情况下可以使用ORIG_PATH_INFO
            $pos = stripos(self::$_server['REQUEST_URI'],self::$_server['SCRIPT_NAME']);
            if(0 === $pos){//PATHINFO模式
                self::$_server['PATH_INFO'] = substr(self::$_server['REQUEST_URI'], strlen(self::$_server['SCRIPT_NAME']));
            }else{
                //重写模式
                self::$_server['PATH_INFO'] = self::$_server['REQUEST_URI'];
            }
        }
        $position = stripos(self::$_server['PATH_INFO'],'.'.self::$_convention['MASQUERADE_TAIL']);
        if(strlen(self::$_server['PATH_INFO']) ===
                ($position + strlen(self::$_convention['MASQUERADE_TAIL']) +1)){
            self::$_server['PATH_INFO'] = substr(self::$_server['PATH_INFO'],0,$position);
        }
        Util::status('check_pathinfo_done');
//        Log::write('PATHINFO:'.self::$_server['PATH_INFO'],Log::LOG_LEVEL_TRACE);//记录访问的PATHINFO信息
        //-- 解析PATHINFO信息 --//
        Util::status('parse_pathinfo_mac_begin');
        //截取参数段param与定位段local
        $plpos          = strpos(self::$_server['PATH_INFO'],self::$_convention['AP_BRIDGE']);
        $mcapart = '';
        $pparts = '';
        if(false === $plpos){
            $mcapart  = self::$_server['PATH_INFO'];//不存在参数则认定PATH_INFO全部是MCA的部分，否则得到结果substr(self::$_server['PATH_INFO'],0,0)即空字符串
        }else{
            $mcapart  = ltrim(substr(self::$_server['PATH_INFO'],0,$plpos),'/');
            $pparts   = substr(self::$_server['PATH_INFO'],$plpos+strlen(self::$_convention['AP_BRIDGE']));
        }

        //-- 解析MCA --//
        $capos = strrpos($mcapart,self::$_convention['CA_BRIDGE']);
        if(false === $capos){
            //找不到控制器与操作之间分隔符（一定不存在控制器）
            //先判断位置部分是否为空字符串来决定是否有操作名称
            if(strlen($mcapart)){
                //位置字段全部是字符串的部分
                self::$_parseSet['a'] = $mcapart;
            }else{
                //没有操作部分，则使用默认的
            }
        }else{
            self::$_parseSet['a'] = substr($mcapart,$capos+strlen(self::$_convention['CA_BRIDGE']));//apos后面的部分全部算作action
            $mcalen = strlen($mcapart);
            //一定存在控制器
            $mcpart = substr($mcapart,0,$capos-$mcalen);//去除了action的部分
//            Log::trace($mcpart,$capos-$posSegmentLength);//right
            $mcpos = strrpos($mcpart,self::$_convention['MC_BRIDGE'],$capos-$mcalen);
            if(false === $mcpos){
                //不存在模块 之后 获取控制器部分
                if(strlen($mcpart)){
                    //位置字段全部是字符串的部分
                    self::$_parseSet['c'] = ucwords($mcpart);
                }else{
                    //没有控制器部分，则使用默认的
                }
            }else{
//                Log::trace('-----',$mcpart,$mcpos,substr($mcpart,$mcpos+1));
                $mclen = strlen($mcpart);
                self::$_parseSet['c']   = ucwords(substr($mcpart,$mcpos+strlen(self::$_convention['MC_BRIDGE'])));
                //存在模块
                $mpart = substr($mcpart,0,$mcpos-$mclen);//以下的全是模块部分的字符串
//                Log::trace($mpart,$mcpos-$mcalen);
                if(strlen($mpart)){
                    if(false === stripos($mpart,self::$_convention['MM_BRIDGE'])){
                        self::$_parseSet['m'] = ucwords($mpart);
                    }else{
                        self::$_parseSet['m'] =
                            array_map(function($val){
                                return ucwords($val);
                            },explode(self::$_convention['MM_BRIDGE'],$mpart));
                    }
                }else{
                    //模块为空
                }
            }
        }
        Util::status('parse_pathinfo_mac_done');
        //测试strrposde得到结论:从前往后是从0开始的，从后往前是-1开始的
//        Util::dump(strrpos('bsabab','ab'));//4
//        Util::dump(strrpos('bsabab','ab',-1));//4
//        Util::dump(strrpos('bsabab','ab',-2));//4
//        Util::dump(strrpos('bsabab','ab',-3));//2
//        Util::dump(strrpos('bsabab','ab',-4));//2
        //-- 解析PARAM --//
        self::$_parseSet['p'] = self::translateParameters($pparts,false);
        Util::status('parse_pathinfo_params_done');
//        Log::trace(self::$_parseSet,self::$_convention['AP_BRIDGE'],$mcapart,$pparts);
    }

    /**
     * 加入匿名URL参数
     * @param $value
     * @param array $pc
     * @return void
     */
    private static function pushAnonymousParam($value,&$pc=null){
        null === $pc and  $pc = &self::$_parseSet['p'];
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
        isset($modulelist) or $modulelist = self::$_parseSet['m'];
        isset($controller) or $controller = self::$_parseSet['c'];
        isset($action)     or $action     = self::$_parseSet['a'];
//        Util::dump('URLHelper::create',$modulelist,$controller,$action,$params);
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
        Util::dump($modules,$controller,$action,$params);
        return self::$_server['SCRIPT_NAME'].'?'.http_build_query(array_merge(array(
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
     * @return string
     */
    public static function createInPathinfo($modules,$controller,$action,$params){
        if(is_array($modules)) self::translateModules($modules);
        $conf = &self::$_convention;
        $tail = isset($conf['MASQUERADE_TAIL'])?".{$conf['MASQUERADE_TAIL']}":'';
        $params = self::translateParameters($params);
        empty($params) or $params = "{$conf['AP_BRIDGE']}{$params}";
        $url = self::$_server['SCRIPT_NAME']."/{$modules}{$conf['MC_BRIDGE']}{$controller}{$conf['CA_BRIDGE']}{$action}{$params}{$tail}";
        REWRITE_ENGINE_ON and self::applyRewriteHidden($url);
        return $url;
    }

    /**
     * 创建compatible模式下的URL
     * @param $modules
     * @param $controller
     * @param $action
     * @param $params
     * @return string
     */
    public static function createInCompatible($modules,$controller,$action,$params){        if(is_array($modules)) self::translateModules($modules);
        $conf = &self::$_convention;
        $tail = isset($conf['MASQUERADE_TAIL'])?".{$conf['MASQUERADE_TAIL']}":'';
        $params = self::translateParameters($params);
        empty($params) or $params = "{$conf['AP_BRIDGE']}{$params}";
        $url = self::$_server['SCRIPT_NAME']."?{$conf['URL_COMPATIBLE_VARIABLE']}{$modules}{$conf['MC_BRIDGE']}{$controller}{$conf['CA_BRIDGE']}{$action}{$params}{$tail}";
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
//            Util::dump($modules);
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
    private static function translateParameters($params,$toString=true){
        $conf = &self::$_convention;
        if($toString){
            //希望返回的是字符串是，返回值是void，直接修改自$params
            $temp = '';
            if($params){
                foreach($params as $key => $val){
                    $temp .= "{$key}{$conf['PKV_BRIDGE']}{$val}{$conf['PP_BRIDGE']}";
                }
                return substr($temp,0,strlen($temp) - strlen($conf['PP_BRIDGE']));
            }else{
                return '';
            }
        }else{
            //解析字符串成数组
            $ppb    = &self::$_convention['PP_BRIDGE'];
            $pkvb   = &self::$_convention['PKV_BRIDGE'];
            $pc = array();
            if($ppb !== $pkvb){//使用不同的分割符
                $parampairs = explode($ppb,$params);
                foreach($parampairs as $val){
                    $pos = stripos($val,$pkvb);
                    if(false === $pos){
                        //非键值对，赋值数字键
                        self::pushAnonymousParam($val,$pc);
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
                        self::pushAnonymousParam($elements[$i],$pc);//单个将被投入匿名参数
                    }
                }
            }
            return $pc;
        }
    }

    public static function createTemplateConstant($modulelist=null,$controller=null,$action=null,array $params=array()){
        $url = null;
        if(URLMODE_TOPSPEED_ON){
            if(is_array($modulelist)) self::translateModules($modulelist);
//            Util::dump($modulelist,$controller,$action,$params);
            $query = http_build_query(array_merge(array(
                self::$_convention['URL_MODULE_VARIABLE'] => $modulelist,
                self::$_convention['URL_CONTROLLER_VARIABLE'] => $controller,
                self::$_convention['URL_ACTION_VARIABLE'] => $action,
            ),$params));
            $url = self::$_server['SCRIPT_NAME'].(empty($query)?'':"?{$query}");
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
     * 对url进行url重写处理，需要借助以.htaccess或者虚拟主机配置才能正常解析URL
     * @param $url
     * @throws URLRewriteFailedException
     */
    private static function applyRewriteHidden(&$url){
        $pos = stripos($url,self::$_convention['REWRITE_HIDDEN']);//获取第一个位置
//        Util::dump($url,self::$_convention['REWRITE_HIDDEN']);exit;
        if(false !== $pos){
            $url = Util::strReplaceJustOnce(self::$_convention['REWRITE_HIDDEN'],'',$url);
        }
    }

	
}