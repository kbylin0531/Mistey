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
     * 数据库操作入口
     * @var Dao
     */
    protected $dao = null;

    /**
     * 实际的对应的数据表的名称
     * @var string
     */
    protected $_real_tablename = null;

    /**
     * @param string $tablename 对应的表明
     * @param int $identifier
     */
    public function __construct($tablename=null,$identifier=0){
        if(!isset($this->dao)){
            $this->dao = Dao::getInstance($identifier);
        }
        $this->_real_tablename = $tablename;
    }


}