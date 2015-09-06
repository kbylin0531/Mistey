<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/30
 * Time: 13:38
 */
namespace System\Core;
use System\Exception\FileNotFoundException;
use System\Utils\Util;

class View{

    const TPL_ENGINE_SMARTY = 'Smarty';
    const TPL_ENGINE_SHUTTLER = 'Shuttle';

    /**
     * 模板文件存放目录
     * @var string
     */
    protected $_tpl_dir = null;
    /**
     * 编译缓存目录，包括编译后的模板和静态缓存
     * @var string
     */
    protected $_tpl_cache_dir = null;

    /**
     * 模板文件编译后的存放目录
     * @var string
     */
    protected $_tpl_compile_dir = null;

    /**
     * 静态缓存的输出文件存放目录
     * @var string
     */
    protected $_tpl_static_dir = null;
    /**
     * 一个控制器对应一个视图
     * @var array
     */
    protected static $_context = null;

    /**
     * 模板引擎驱动
     * @var TemplateDriver\TemplateDriver
     */
    public static $tpl_engine = null;

    protected $_tVars = array();

    /**
     * 构造函数
     * @param array $context 控制器闯入的伪上下文环境
     * @throws \Exception
     */
    public function __construct($context){
        self::$_context = $context;
        if(!isset(self::$tpl_engine)){
            $driverName = 'System\\Core\\TemplateDriver\\'.TEMPLATE_ENGINE.'Driver';
            if(class_exists($driverName)){
                self::$tpl_engine = new $driverName();
            }else{
                throw new \Exception('Unknown Template Driver "'.TEMPLATE_ENGINE.'"');
            }
//            Util::dump($driverName,TEMPLATE_ENGINE);exit;
        }
    }


    /**
     * 显示模板
     * @param string $template 全部模板引擎通用的
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     * @throws FileNotFoundException 模板文件找不到时抛出
     * @throws \Exception
     */
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null){
//        Util::dump($template,$this->_context,TEMPLATE_ENGINE);exit;
        $context = &self::$_context;
        if($template){
            $context = array_merge($context,self::parseTemplatePath($template));
        }
        //项目中省略模板文件后缀的情况
        $suffix = '.'.TEMPLATE_EXT;
        if(false === strpos($context['a'],$suffix)){
            $context['a'] .= $suffix;
        }
        //获取缓存目录
        $this->_tpl_cache_dir = RUNTIME_PATH."cache/{$context['m']}/{$context['c']}/";
        //确定是否存在该主题文件，不存在则使用默认的对应主题文件
        $this->_tpl_dir = APP_PATH."{$context['m']}/View/{$context['c']}/";
        if(isset($context['t']) and is_file($this->_tpl_dir."{$context['t']}/{$context['a']}")){
            $this->_tpl_dir .= "{$context['t']}/";
        }
        //判断模板文件是否存在（改为由模板引擎判断）
//        if(!is_file($this->_tpl_dir.$context['a'])){
//            throw new FileNotFoundException($this->_tpl_dir.$context['a']);
//        }

        //分配变量
        switch(TEMPLATE_ENGINE){
            case self::TPL_ENGINE_SMARTY:
                self::$tpl_engine->assign($this->_tVars);
                break;
            case self::TPL_ENGINE_SHUTTLER:
                //批量导入模板变量
                self::$tpl_engine->assign($this->_tVars);
                break;
            default:
                throw new \Exception('Unknown template engine "'.TEMPLATE_ENGINE.'"!');
        }
        //设置模板目录(基础)
//        Util::dump($template,$this->_context,TEMPLATE_ENGINE);
        self::$tpl_engine->setTemplateDir($this->_tpl_dir);
        self::$tpl_engine->setCompileDir($this->_tpl_cache_dir.'compile/');
        self::$tpl_engine->setCacheDir($this->_tpl_cache_dir.'static/');
//        Util::dump($this->_context,$template);exit;
        //显示模板文件
        self::$tpl_engine->display(self::$_context['a'],$cache_id,$compile_id,$parent);
    }

    /**
     * 保存控制器分配的变量
     * @param string $tpl_var
     * @param null $value
     * @param bool $nocache
     * @return $this
     */
    public function assign($tpl_var,$value=null,$nocache=false){
        if($nocache){
            //TODO:Smarty模板分配变量
        }else{
            if(is_array($tpl_var)){
                foreach($tpl_var as $_key => $_val){
                    $_key and $this->_tVars[$_key] = $_val;
                }
            }else{
                $tpl_var and $this->_tVars[$tpl_var] = $value;
            }
        }
        return $this;
    }

    /**
     * 解析资源文件地址
     * 地址解析格式：
     *  ModuleA/ModuleB@Controller/action:theme
     * 解析结果可以是资源地址，也可以是位置组成数组
     * @param string $templatepath
     * @param bool $get_url 返回的是位置字符串还是数组
     * @return array|string
     */
    public static function parseTemplatePath($templatepath,$get_url=false){
        $rst = array();
        if($templatepath){
            $tpos = strpos($templatepath,':');
            $pathlen = strlen($templatepath);
            //解析主题
            if(false !== $tpos){
                //存在主题
                $rst['t'] = substr($templatepath,$tpos+1,$pathlen-1);//末尾的pos需要-1-1
                $templatepath = substr($templatepath,0,$tpos);
            }
            $mcpos = strpos($templatepath,'@');
            if(false !== $mcpos){
                $rst['m'] = substr($templatepath,0,$mcpos);
                $templatepath = substr($templatepath,$mcpos+1);
            }
            $capos = strpos($templatepath,'/');
            if(false !== $capos){
                $rst['c'] = substr($templatepath,0,$capos);
                $rst['a'] = substr($templatepath,$capos+1);
            }else{
                $rst['a'] = $templatepath;
            }
        }
        if($get_url){
            $url = APP_PATH;
            $context = &self::$_context;
            $url .= isset($rst['m']) ? "{$rst['m']}/":"{$context['m']}/";
            $url .= isset($rst['c']) ? "View/{$rst['c']}/":"View/{$context['c']}/";
            if(isset($rst['t'])){
                $url .= "{$url}/{$rst['t']}/";
            }elseif(isset($context['t'])){
                $url .= "{$url}/{$context['t']}/";
            }
            $url .= (isset($rst['a']) ? $rst['a']:$context['a']).'.'.TEMPLATE_EXT;
            return $url;
        }
        return $rst;
    }




}