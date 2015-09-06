<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/29
 * Time: 22:29
 */
namespace System\Core\TemplateDriver;


abstract class TemplateDriver {
    protected static $instance = null;

    abstract public function assign($tpl_var, $value = null, $nocache = false);

    abstract public function display($template = null, $cache_id = null, $compile_id = null, $parent = null);

    abstract public function setTemplateDir($path);

    abstract public function setCompileDir($path);

    abstract public function setCacheDir($path);

    public function __call($name,$params){
        return call_user_func_array(array(static::$instance,$name),$params);
    }

}