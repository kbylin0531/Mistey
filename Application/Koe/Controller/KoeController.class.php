<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 21:20
 */
namespace Application\Koe\Controller;

use System\Core\Controller;

class KoeController extends Controller{
    public $in;
    public $db;
    public $config;	// 全局配置
    public $tpl;	// 模板目录
    public $values;	// 模板变量
    public $L;

    /**
     * 构造函数
     */
    public function __construct(){
        global $in,$config,$db,$L;

        $this -> db  = $db;
        $this -> L 	 = $L;
        $this -> config = &$config;
        $this -> in = &$in;
        $this -> values['config'] = &$config;
        $this -> values['in'] = &$in;
        parent::__construct();
    }

    /**
     * 加载模型
     * @param string $class
     */
    public function loadModel($class){
        $args = func_get_args();
        $this -> $class = call_user_func_array('init_model', $args);
        return $this -> $class;
    }

    /**
     * 加载类库文件
     * @param string $class
     */
    public function loadClass($class){
        if (1 === func_num_args()) {
            $this -> $class = new $class;
        } else {
            $reflectionObj = new \ReflectionClass($class);
            $args = func_get_args();
            array_shift($args);
            $this -> $class = $reflectionObj -> newInstanceArgs($args);
        }
        return $this -> $class;
    }

    /**
     * 显示模板
     * @param array|string $key
     * @param mixed|null $value
     * @return mixed|null
     */
    public function assign($key,$value){
        return $this->values[$key] = $value;
    }
    /**
     * 显示模板
     * @param null|string $tpl_file
     */
    public function display($tpl_file){
        global $L,$LNG;
        extract($this->values);
        require($this->tpl.$tpl_file);
    }
}