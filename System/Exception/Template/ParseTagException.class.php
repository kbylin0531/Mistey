<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/30
 * Time: 17:27
 */
namespace System\Exception\Template;

class ParseTagException extends \Exception{

    public function __construct($tag,$content){
        $this->message = array(
            'tag'       =>  $tag,
            'content'   => $content,
        );
    }

}