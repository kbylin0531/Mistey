<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/24
 * Time: 10:24
 */
namespace System\Exception;
use System\Utils\Util;

/**
 * Class PDOExecuteException
 * PDOStatement::execute()执行失败时抛出的异常
 * @package System\Exception
 */
class PDOExecuteException extends \Exception{

    /**
     * @param \PDOStatement $stmt
     * @param array $input_parameters
     */
    public function __construct(&$stmt,$input_parameters){
        ob_start();
        echo '<pre>';
        $stmt->debugDumpParams();
        echo '<pre>';
        Util::dump($input_parameters);
        $this->message = ob_get_clean();
    }

}