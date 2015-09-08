<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace System\Core\TemplateDriver\ShuttleTagLib;
use System\Core\TemplateDriver\ThinkDriver;
use System\Exception\ParameterInvalidException;
use System\Exception\Template\XMLReadFailedException;
use System\Utils\Util;

/**
 * ThinkPHP标签库TagLib解析基类
 */
class TagLib {

    /**
     * 标签库定义XML文件
     * @var string
     * @access protected
     */
    protected $xml      = '';

    /**
     * 标签定义
     * @var array
     */
    protected $tags     = array();
    /**
     * 标签库名称
     * @var string
     * @access protected
     */
    protected $tagLib   ='';

    /**
     * 标签库标签列表
     * @var string
     * @access protected
     */
    protected $tagList  = array();

    /**
     * 标签库分析数组
     * @var string
     * @access protected
     */
    protected $parse    = array();

    /**
     * 标签库是否有效
     * @var string
     * @access protected
     */
    protected $valid    = false;

    /**
     * 当前模板对象
     * @var ThinkDriver
     */
    protected $_context;

    protected $comparison = array(' nheq '=>' !== ',' heq '=>' === ',' neq '=>' != ',
        ' eq '=>' == ',' egt '=>' >= ',' gt '=>' > ',' elt '=>' <= ',' lt '=>' < ');

    /**
     * @param ThinkDriver $context 驱动类的上下文
     * @throws \System\Exception\ClassNotFoundException
     */
    public function __construct($context){
        $this->tagLib  = strtolower(substr(get_class($this),6));
        $this->_context     = $context;
    }


    // 获取标签定义
    public function getTags(){
        return $this->tags;
    }

    /**
     * TagLib标签属性分析 返回标签属性数组
     * @param string $attr 标签内容
     * @param $tag
     * @return array
     * @throws ParameterInvalidException
     * @throws XMLReadFailedException
     */
    public function parseXmlAttr($attr,$tag){
        //XML解析安全过滤
        $attr = str_replace('&','___', $attr);
        $attributes = Util::readXmlAttrs($attr);
        if($attributes) {
            $tag = strtolower($tag);
            $item = isset($this->tags[$tag])?$this->tags[$tag]:null;
            $attrs = explode(',',$item['attr']);
            if(isset($item['must'])){
                $must = explode(',',$item['must']);
            }else{
                $must = array();
            }
            foreach($attrs as $name){
                if(isset($attributes[$name])) {
                    $attributes[$name] = str_replace('___','&',$attributes[$name]);
                }elseif(false !== array_search($name,$must)){
                    throw new ParameterInvalidException($name);
                }
            }
            return $attributes;
        }
        return $attributes;
    }

    /**
     * 解析条件表达式
     * @access public
     * @param string $condition 表达式标签内容
     * @return array
     */
    public function parseCondition($condition) {
        $condition = str_ireplace(array_keys($this->comparison),array_values($this->comparison),$condition);
        $condition = preg_replace('/\$(\w+):(\w+)\s/is','$\\1->\\2 ',$condition);
        switch(strtolower($this->_context->config('TEMPLATE_VAR_IDENTIFY'))) {
            case 'array': // 识别为数组
                $condition  =   preg_replace('/\$(\w+)\.(\w+)\s/is','$\\1["\\2"] ',$condition);
                break;
            case 'obj':  // 识别为对象
                $condition  =   preg_replace('/\$(\w+)\.(\w+)\s/is','$\\1->\\2 ',$condition);
                break;
            default:  // 自动判断数组或对象 只支持二维
                $condition  =   preg_replace('/\$(\w+)\.(\w+)\s/is','(is_array($\\1)?$\\1["\\2"]:$\\1->\\2) ',$condition);
        }
        if(false !== strpos($condition, '$Think'))
            $condition      =   preg_replace_callback('/(\$Think.*?)\s/is', array($this, 'parseThinkVar'), $condition);
        return $condition;
    }

    /**
     * 自动识别构建变量
     * @access public
     * @param string $name 变量描述
     * @return string
     */
    public function autoBuildVar($name) {
        if('Think.' == substr($name,0,6)){
            // 特殊变量
            return $this->parseThinkVar($name);
        }elseif(strpos($name,'.')) {
            $vars = explode('.',$name);
            $var  =  array_shift($vars);
            switch(strtolower($this->_context->config('TEMPLATE_VAR_IDENTIFY'))) {
                case 'array': // 识别为数组
                    $name = '$'.$var;
                    foreach ($vars as $key=>$val){
                        if(0===strpos($val,'$')) {
                            $name .= '["{'.$val.'}"]';
                        }else{
                            $name .= '["'.$val.'"]';
                        }
                    }
                    break;
                case 'obj':  // 识别为对象
                    $name = '$'.$var;
                    foreach ($vars as $key=>$val)
                        $name .= '->'.$val;
                    break;
                default:  // 自动判断数组或对象 只支持二维
                    $name = 'is_array($'.$var.')?$'.$var.'["'.$vars[0].'"]:$'.$var.'->'.$vars[0];
            }
        }elseif(strpos($name,':')){
            // 额外的对象方式支持
            $name   =   '$'.str_replace(':','->',$name);
        }elseif(!defined($name)) {
            $name = '$'.$name;
        }
        return $name;
    }

    /**
     * 用于标签属性里面的特殊模板变量解析
     * 格式 以 Think. 打头的变量属于特殊模板变量
     * @access public
     * @param string $varStr  变量字符串
     * @return string
     */
    public function parseThinkVar($varStr){
        if(is_array($varStr)){//用于正则替换回调函数
            $varStr = $varStr[1];
        }
        $vars       = explode('.',$varStr);
        $vars[1]    = strtoupper(trim($vars[1]));
        $parseStr   = '';
        if(count($vars)>=3){
            $vars[2] = trim($vars[2]);
            switch($vars[1]){
                case 'SERVER':    $parseStr = '$_SERVER[\''.$vars[2].'\']';break;
                case 'GET':         $parseStr = '$_GET[\''.$vars[2].'\']';break;
                case 'POST':       $parseStr = '$_POST[\''.$vars[2].'\']';break;
                case 'COOKIE':
                    if(isset($vars[3])) {
                        $parseStr = '$_COOKIE[\''.$vars[2].'\'][\''.$vars[3].'\']';
                    }elseif(APP_NAME){
                        $parseStr = '$_COOKIE[\''.APP_NAME.$vars[2].'\']';
                    }else{
                        $parseStr = '$_COOKIE[\''.$vars[2].'\']';
                    }
                    break;
                case 'SESSION':
                    if(isset($vars[3])) {
                        $parseStr = '$_SESSION[\''.$vars[2].'\'][\''.$vars[3].'\']';
                    }elseif(APP_NAME){
                        $parseStr = '$_SESSION[\''.APP_NAME.'\'][\''.$vars[2].'\']';
                    }else{
                        $parseStr = '$_SESSION[\''.$vars[2].'\']';
                    }
                    break;
                case 'ENV':         $parseStr = '$_ENV[\''.$vars[2].'\']';break;
                case 'REQUEST':  $parseStr = '$_REQUEST[\''.$vars[2].'\']';break;
                case 'CONST':     $parseStr = strtoupper($vars[2]);break;
                //不兼容这两种变量
//                case 'LANG':       $parseStr = 'L("'.$vars[2].'")';break;
//                case 'CONFIG':    $parseStr = 'C("'.$vars[2].'")';break;
            }
        }else if(count($vars)==2){
            switch($vars[1]){
                case 'NOW':       $parseStr = "date('Y-m-d g:i a',time())";break;
//                case 'VERSION':  $parseStr = 'THINK_VERSION';break;//不兼容
//                case 'TEMPLATE': $parseStr = $this->_context->_cur_tpl_file;break;//不兼容
                case 'LDELIM':    $parseStr = $this->_context->config('TEMPLATE_VAR_L_DEPR');break;
                case 'RDELIM':    $parseStr = $this->_context->config('TEMPLATE_VAR_R_DEPR');break;
                default:  if(defined($vars[1])) $parseStr = $vars[1];
            }
        }
        return $parseStr;
    }

}