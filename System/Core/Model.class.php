<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/16
 * Time: 10:17
 */
namespace System\Core;
defined('BASE_PATH') or die('No Permission!');
/**
 * Class Model 模型类
 * 一个模型对应一个数据表
 * @package System\Core
 */
class Model{
    /**
     * @var Dao
     */
    public $dao = null;

    public function __construct(){
        if(!isset($this->dao)){
            $this->dao = Dao::getInstance(0);
        }
    }


}