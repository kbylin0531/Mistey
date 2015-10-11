<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 21:23
 */
namespace Application\Koe\Model;

use System\Core\Model;

class KoeModel extends Model{
    protected $db = null;
    protected $in;
    protected $config;

    /**
     * 构造函数
     * @param $config
     */
    public function __construct($config = null){
        global  $in;
        $this -> in = $in;
        null !== $config and $this -> config = $config;
        parent::__construct();
    }

    /**
     * 获取数据库
     * @return null
     */
    function db(){
        if ($this ->db != NULL) {
            return $this ->db;
        }else{
            return null;
        }
    }
}