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
     * 模型所属模块名称
     * @var string
     */
    protected $module_name = null;
    /**
     * 模型名称，不包含命名空间部分和Model后缀
     * @var string
     */
    protected $model_name = null;

    /**
     * @param string $tablename 对应的数据表的名称
     * @throws \Exception
     */
    public function __construct($tablename=null){
        if(isset($tablename)){
            $this->setTableName($tablename);
        }else{
            if(preg_match('/^Application\\\(.*)\\\Model\\\(.*)Model$/',get_called_class(),$matches)){
                $this->module_name = str_replace('\\','/',$matches[1]);
                $this->model_name = $matches[2];
            }else{
                throw new \Exception('Class "'.get_called_class().'" fetch falied!');
            }
        }
    }

    /**
     * 设置表的名称
     * @param string $tablename
     * @return void
     */
    protected function setTableName($tablename){
        $this->_real_tablename = $tablename;
    }
    protected function getTableName(){
        return $this->_real_tablename;
    }

    /**
     * 初始化连接配置
     * @param string|array $config 连接配置标识符 或者 配置数组
     * @return void
     */
    public function init($config='0'){
        if(is_array($config)){
            $this->dao = new Dao($config);
        }else{
            $this->dao = Dao::getInstance($config);
        }
    }

    /**
     * 获取查询出错信息
     * @return string
     */
    public function getErrorInfo(){
        return isset($this->dao)? $this->dao->getErrorInfo() : '';
    }

    /**
     * 插入数据库记录
     * @param array $fields
     * @param string $tablename
     * @return int|string
     * @throws \Exception
     */
    public function create(array $fields,$tablename=null){
        isset($tablename) or $tablename = $this->getTableName();
        if(!$this->dao) throw new \Exception('Dao is empty!');
        return $this->dao->create($tablename,$fields);
    }


}