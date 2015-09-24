<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/8
 * Time: 18:43
 */
namespace System\Exception;

/**
 * 参数异常类
 * Class ParameterInvalidException
 * @package System\Core\Exception
 */
class ParameterInvalidException extends \Exception{

    /**
     * 构造函数中参数 为判断为不合法的参数
     */
    public function __construct(){
        $arguments = func_get_args();
        $this->message = 'Parameters [';
        foreach($arguments as $val){
            $this->message .= gettype($val)."({$val}),";
        }
        $this->message = rtrim($this->message,',').'] invalid!';
    }

}