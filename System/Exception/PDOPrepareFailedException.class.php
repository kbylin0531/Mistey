<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/18
 * Time: 16:49
 */
namespace System\Exception;

class PDOPrepareFailedException extends \Exception{
    /**
     * @param string $sql
     * @param array $option
     */
    public function __construct($sql,$option){
        $option = var_export($option);
        $this->message = "
<pre>
    Prepare a SQL Statement Failed.
    SQL:
        <b>$sql</b>
    Option:
        $option
</pre>";
    }

}