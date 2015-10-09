<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/16
 * Time: 10:17
 */
namespace System\Core;
use System\Util\SEK;

defined('BASE_PATH') or die('No Permission!');
/**
 * Class Model 模型类
 * 一个模型对应一个数据表
 * 完成基本的CURD操作
 * @package System\Core
 */
class Model{

    /**
     * 数据库操作入口
     * @var Dao
     */
    protected $dao = null;

    /**
     * 字段映射
     * 将表单提交的字段映射为数据库对应的字段名称
     * 例如：
     *  array(
     *      'key' => 'value'，//把表单中'key'映射到数据表的'value'字段
     *  );
     * @var array
     */
    protected $mapping = array();

    /**
     * 模型属性
     * @var array
     */
    protected $attributes = array(
        /**
         * 字段信息
         * @var array
         */
        'fields'    => array(),
        /**
         * 默认的表前缀
         * @var string
         */
        'prefix'    => '',
        /**
         * 主键，可以是复合主键(数组)
         * @var string|array
         */
        'primar_key'    => 'id',
        /**
         * 实际的对应的数据表的名称
         * @var string
         */
        'real_tablename'   => null,
        /**
         * 模型名称，不包含命名空间部分和Model后缀
         * @var string
         */
        'model_name'    => null,
        /**
         * 模型所属模块名称
         * @var string
         */
        'modules_name'   => null,
    );

    /**
     * 构造函数
     * @param string|array $config 对应的数据表的名称(string)或者模型配置选项(array)
     * @throws \Exception
     */
    public function __construct($config=null){
        $matches = null;
        if(preg_match('/^Application\\\(.*)\\\Model\\\(.*)Model$/',get_called_class(),$matches)){
            $this->attributes['modules_name'] = str_replace('\\','/',$matches[1]);
            $this->attributes['model_name'] = $matches[2];
        }else{
            throw new \Exception('Class "'.get_called_class().'" auto fetch falied!');
        }
        if(isset($config)){
            if(is_string($config)){
                $this->setTableName($config);
            }else{
                SEK::merge($this->attributes,$config);
            }
        }else{
            $modelname = SEK::translateStringStyle($matches[2],false);
            $this->setTableName($modelname);
        }
    }

    /**
     * 设置表的名称
     * @param string $tablename 数据表名称，不带前缀
     * @param string $prefix 数据表前缀
     * @return void
     */
    protected function setTableName($tablename,$prefix=null){
        if(isset($prefix)){
            $prefix = $this->attributes['prefix'];
        }
        $this->attributes['real_tablename'] = $prefix.$tablename;
    }
    protected function getTableName(){
        return $this->attributes['real_tablename'];
    }

    /**
     * 初始化连接配置
     * @param string|array $config 连接配置标识符 或者 配置数组
     * @return void
     */
    public function init($config='0'){
        $this->dao = Dao::getInstance($config);
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
     * @return int|string
     * @throws \Exception
     */
    public function create(array $fields){
        $tablename = $this->getTableName();
        return $this->dao->create($tablename,$fields);
    }

    /**
     * 更新记录
     * @param mixed $where
     * @param mixed $fields
     * @return int|string 受影响行数或者错误信息
     * @throws \Exception
     */
    public function update($where=null,$fields=null){
        $tablename = $this->getTableName();
        return $this->dao->update($tablename,$fields,$where);
    }

    /**
     * 获取记录
     * @param mixed $where
     * @param mixed $fields
     * @return array
     */
    public function select($where=null,$fields=null){
        $tablename = $this->getTableName();
        return $this->dao->select($tablename,$fields,$where);
    }

    /**
     * 获取一条记录
     * 如果记录有多条，需要使用select进行获取，否则获取结果数目不等于1时会返回string类型错误信息
     * @param mixed $where
     * @param mixed $fields
     * @return string
     */
    public function find($where=null,$fields=null){
        $tablename = $this->getTableName();
        $rst = $this->dao->select($tablename,$fields,$where);
        return count($rst) !== 1?$this->getErrorInfo():$rst[0];
    }

    /**
     * 删除记录
     * @param mixed $where
     * @return int|string
     */
    public function delete($where=null){
        $tablename = $this->getTableName();
        return $this->dao->delete($tablename,$where);
    }
}