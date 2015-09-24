<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/30
 * Time: 17:41
 */
namespace System\Exception\Template;

class XMLReadFailedException extends \Exception{

    public function __construct($attrs){
        $this->message = $attrs;
    }

}