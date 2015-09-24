<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/27
 * Time: 21:44
 */
namespace System\Exception;

class URLRewriteFailedException extends \Exception{

    public function __construct($url,$rewrite_hidden){
        $this->message = "URL('{$url}') apply rewrite('{$rewrite_hidden}') failed!";
    }

}