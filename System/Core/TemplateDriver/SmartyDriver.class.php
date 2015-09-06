<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/29
 * Time: 22:32
 */
namespace System\Core\TemplateDriver;
use System\Utils\Util;

class SmartyDriver extends TemplateDriver{

    /**
     * @var \Smarty
     */
    protected static $instance = null;

    public function __construct(){
        defined('SMARTY_DIR') or define('SMARTY_DIR',BASE_PATH.'System/Projects/TemplateEngine/Smarty/libs/');
        require_once SMARTY_DIR.'Smarty.class.php';
        static::$instance = new \Smarty();
    }

    public function setTemplateDir($path){
//        Util::dump($path);
        return static::$instance->setTemplateDir($path);
    }

    public function setCompileDir($path){
        return static::$instance->setCompileDir($path);
    }
    public function setCacheDir($path){
        return static::$instance->setCacheDir($path);
    }

    public function assign($tpl_var, $value = null, $nocache = false){
//        Util::dump(static::$instance->tpl_vars);
        return static::$instance->assign($tpl_var,$value,$nocache);
    }

    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null){
        static::$instance->display($template,$cache_id,$compile_id,$parent);
    }

}