<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/16
 * Time: 10:17
 */
namespace System\Core;
use System\Utils\Util;

defined('BASE_PATH') or die('No Permission!');
/**
 * Class Model 模型类
 * 一个模型对应一个数据表
 * @package System\Core
 */
class Model{



    const VALIDATE_CONDITION_MUST         =   1;      // 必须验证
    const VALIDATE_CONDITION_EXISTS       =   0;      // 表单存在字段则验证
    const VALIDATE_CONDITION_NOT_EMPTY        =   2;      // 表单值不为空则验证

    const VALIDATE_RULE_REGEX = 'regex';
    const VALIDATE_RULE_FUNCTION = 'function';
    const VALIDATE_RULE_CALLBACK = 'callback';
    const VALIDATE_RULE_CONFIRM = 'confirm';
    const VALIDATE_RULE_EQUAL = 'equal';
    const VALIDATE_RULE_IN = 'in';
    const VALIDATE_RULE_LENGTH = 'length';
    const VALIDATE_RULE_BETWEEN = 'between';
    const VALIDATE_RULE_EXPIRE = 'expire';
    const VALIDATE_RULE_IP_ALLOW = 'ip_allow';
    const VALIDATE_RULE_IP_DENY = 'ip_deny';
    const VALIDATE_RULE_UNIQUE = 'unique';


    /**
     * 数据库操作入口
     * @var Dao
     */
    protected $dao = null;
    /**
     * 用于连贯操作的组成项
     * @var array
     */
    protected $options = array();
    /**
     * CURD操作参数绑定
     * @var array
     */
    protected $bind = array();
    /**
     * 主键，可以是复合主键(数组)
     * @var string|array
     */
    protected $_primar_key = 'id';
    /**
     * 字段信息
     * @var array
     */
    protected $fields           =   array();
    /**
     * 默认的表前缀
     * @var string
     */
    protected $prefix = '';
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
     * 自动验证配置
     * @var array
     */
    protected $_validate        =   array();

    /**
     * @param string $tablename 对应的数据表的名称
     * @throws \Exception
     */
    public function __construct($tablename=null){
        //获取表前缀，比较高效的方式是手动添加表前缀
        if(!isset($config['prefix'])){
            $config = ConfigHelper::readAutoConfig('cms');
            $this->prefix = $config['prefix'];
        }
        if(isset($tablename)){
            $this->setTableName($tablename);
        }else{
            if(preg_match('/^Application\\\(.*)\\\Model\\\(.*)Model$/',get_called_class(),$matches)){
                $tablename = Util::translateStringStyle($matches[2],false);
                $this->setTableName($tablename);
                $this->module_name = str_replace('\\','/',$matches[1]);
                $this->model_name = $matches[2];
            }else{
                throw new \Exception('Class "'.get_called_class().'" fetch falied!');
            }
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
            $prefix = $this->prefix;
        }
        $this->_real_tablename = $prefix.$tablename;
    }
    protected function getTableName(){
        return $this->_real_tablename;
    }

    /**
     * 获取主键名称
     * @access public
     * @return string
     */
    public function getPrimarKey() {
        return $this->_primar_key;
    }

    /**
     * 设置数据表主键
     * @param string $key 数据表主键
     * @return void
     */
    public function setprimarKey($key){
        $this->_primar_key = $key;
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

    /**
     * @param mixed $where
     * @param mixed $fields
     * @param string $tablename
     */
    public function update($where=null,$fields=null,$tablename=null){

    }

    /**
     * @param mixed $where
     * @param mixed $fields
     * @param string $tablename
     */
    public function select($where=null,$fields=null,$tablename=null){

    }

    /**
     * @param mixed $where
     * @param mixed $fields
     * @param string $tablename
     */
    public function find($where=null,$fields=null,$tablename=null){

    }

    /**
     * @param mixed $where
     * @param mixed $fields
     * @param string $tablename
     */
    public function delete($where=null,$fields=null,$tablename=null){

    }

    /**
     * 验证因子定义格式:
     *      array(
     *          field, //验证字段
     *          rule, //验证规则
     *          message, //错误提示
     *          condition,//【验证条件】
     *          type,// 【附加规则】
     *          when, //【附加规则】
     *          params //函数或者回掉函数的参数
     *      );
     * @param $data
     * @return bool
     */
    protected function autoValidate($data){
        foreach($this->_validate as $key=>$val) {
            $val[4]  =  isset($val[4])?$val[4]:'regex';
            // 判断验证条件
            switch($val[3]) {
                // 必须验证 不管表单是否有设置该字段
                case self::VALIDATE_CONDITION_MUST:
                    if(false === $this->_checkValidate($data,$val))
                        return false;
                    break;

                // 值不为空的时候才验证
                case self::VALIDATE_CONDITION_NOT_EMPTY:
                    if('' != trim($data[$val[0]]))
                        if(false === $this->_checkValidate($data,$val))
                            return false;
                    break;

                // 默认表单存在该字段就验证(默认)
                case self::VALIDATE_CONDITION_EXISTS:
                default:
                    if(isset($data[$val[0]]))
                        if(false === $this->_checkValidate($data,$val))
                            return false;
            }
        }
        return true;
    }

    /**
     * @param $data
     * @param $value
     * @return bool
     */
    protected function _checkValidate($data,$value){
        switch(strtolower(trim($value[4]))) {
            case 'function':// 使用函数进行验证
            case 'callback':// 调用方法进行验证
                $args = isset($value[6]) ? (array)$value[6] : array();
                if (is_string($value[0]) && strpos($value[0], ','))
                    $value[0] = explode(',', $value[0]);
                if (is_array($value[0])) {
                    // 支持多个字段验证
                    foreach ($value[0] as $field)
                        $_data[$field] = $data[$field];
                    array_unshift($args, $_data);
                } else {
                    array_unshift($args, $data[$value[0]]);
                }
                if ('function' == $value[4]) {
                    return call_user_func_array($value[1], $args);
                } else {
                    return call_user_func_array(array(&$this, $value[1]), $args);
                }
            case 'confirm': // 验证两个字段是否相同
                return $data[$value[0]] == $data[$value[1]];
            default:  // 检查附加规则
                return $this->check($data[$value[0]],$value[1],$value[4]);
        }
    }
    /**
     * 验证数据 支持 in between equal length regex expire ip_allow ip_deny
     * @access public
     * @param string $value 验证数据
     * @param mixed $rule 验证表达式
     * @param string $type 验证方式 默认为正则验证
     * @return boolean
     */
    public function check($value,$rule,$type='regex'){
        $type   =   strtolower(trim($type));
        switch($type) {
            case 'in': // 验证是否在某个指定范围之内 逗号分隔字符串或者数组
            case 'notin':
                $range   = is_array($rule)? $rule : explode(',',$rule);
                return $type == 'in' ? in_array($value ,$range) : !in_array($value ,$range);
            case 'between': // 验证是否在某个范围
            case 'notbetween': // 验证是否不在某个范围
                if (is_array($rule)){
                    $min    =    $rule[0];
                    $max    =    $rule[1];
                }else{
                    list($min,$max)   =  explode(',',$rule);
                }
                return $type == 'between' ? $value>=$min && $value<=$max : $value<$min || $value>$max;
            case 'equal': // 验证是否等于某个值
            case 'notequal': // 验证是否等于某个值
                return $type == 'equal' ? $value == $rule : $value != $rule;
            case 'length': // 验证长度
                $length  =  mb_strlen($value,'utf-8'); // 当前数据长度
                if(strpos($rule,',')) { // 长度区间
                    list($min,$max)   =  explode(',',$rule);
                    return $length >= $min && $length <= $max;
                }else{// 指定长度
                    return $length == $rule;
                }
            case 'expire':
                list($start,$end)   =  explode(',',$rule);
                if(!is_numeric($start)) $start   =  strtotime($start);
                if(!is_numeric($end)) $end   =  strtotime($end);
                return $_SERVER['REQUEST_TIME'] >= $start && $_SERVER['REQUEST_TIME'] <= $end;
            case 'ip_allow': // IP 操作许可验证
                return in_array(Util::getClientIP(),explode(',',$rule));
            case 'ip_deny': // IP 操作禁止验证
                return !in_array(Util::getClientIP(),explode(',',$rule));
            case 'regex':
            default:    // 默认使用正则验证 可以使用验证类中定义的验证名称
                // 检查附加规则
                return $this->regex($value,$rule);
        }
    }
    /**
     * 使用正则验证数据
     * @access public
     * @param string $value  要验证的数据
     * @param string $rule 验证规则
     * @return bool
     */
    public function regex($value,$rule) {
        $validate = array(
            'require'   =>  '/\S+/',
            'email'     =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            'url'       =>  '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(:\d+)?(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
            'currency'  =>  '/^\d+(\.\d+)?$/',
            'number'    =>  '/^\d+$/',
            'zip'       =>  '/^\d{6}$/',
            'integer'   =>  '/^[-\+]?\d+$/',
            'double'    =>  '/^[-\+]?\d+(\.\d+)?$/',
            'english'   =>  '/^[A-Za-z]+$/',
        );
        // 检查是否有内置的正则表达式
        if(isset($validate[strtolower($rule)]))
            $rule       =   $validate[strtolower($rule)];
        return preg_match($rule,$value)===1;
    }





//******************************************  选择自ThinkPHP v3.2.3 ******************************************************************************//
    /**
     * 指定查询条件 支持安全过滤
     * @access public
     * @param mixed $where 条件表达式，可以是数组、字符串、对象，最终都是转化为字符串的形式
     * @param mixed $inputs 输入参数
     * @return $this
     */
    public function where($where,$inputs=null){
        if(is_string($where)){//①②
            if(isset($inputs)){
                if(is_array($inputs)){
                    //这里是数字键，不会覆盖之前的
                    $this->bind = array_merge($this->bind,$inputs);
                }else{
                    //非数组，顺序添加
                    $this->bind[] = $inputs;
                }
            }
            $this->options['where'] = $where;
        }else{// ③ array
            $segments = $this->dao->makeSegments($where);
            $this->options['where'] = $segments[0];
            $this->bind = $this->bind?array_merge($this->bind,$segments[1]):$segments[1];
        }
        return $this;
    }
    /**
     * 设置要操作的数据表的名称，不建议多表
     * @param string $tablename 数据表名称
     * @param bool $check_prefix
     * @return $this
     */
    public function table($tablename,$check_prefix=true){
        if($check_prefix){
            //判断参数一是否以前缀开头，不是则自动添加
            if(0 !== stripos(trim($tablename),$this->prefix)){
                $tablename = $this->prefix.$tablename;
            }
        }
        $this->options['table'] = $tablename;
        return $this;
    }

    public function fields($fields=null,$except=false){
        if(isset($fields)){
            if(is_string($fields)){
                $this->options['fields'] = $fields;
            }else{
                
            }
        }else{
            if(empty($this->fields)){
                $table = null;
                if(isset($this->options['table'])){
                    $table = $this->options['table'];
                }else{
                    $table = $this->getTableName();
                }
                $this->fields = $this->dao->getFields($table);
            }
            $this->options['fields'] = $this->fields;
        }
        return $this;
    }
    /**
     * 查询数据
     * @access public
     * @param string|array $options 查询条件参数，可以是string（主键）或者数组（复合查询条件，如果逐渐正好是数组时为复合主键）
     * @return mixed
     */
//    public function find($options=array()) {
//        $where = array();
//        $pk = $this->getPrimarKey();
//        if(is_numeric($options) or is_string($options)) {
//            $where[$pk]  =   $options;
//            $options                =   array();
//            $options['where']       =   $where;
//        }elseif(is_array($options) and (count($options) > 0) and is_array($pk)) {
//            // 根据复合主键查找记录
//            $count = 0;
//            //获取参数中元素的个数
//            foreach (array_keys($options) as $key) {
//                if (is_int($key)) $count++;
//            }
//            if ($count == count($pk)) {
//                $i = 0;
//                foreach ($pk as $field) {
//                    $where[$field] = $options[$i];
//                    unset($options[$i++]);
//                }
//                $options['where']  =  $where;
//            } else {
//                //不是复合主键主键，返回false
//                return false;
//            }
//        }
//        // 总是查找一条记录
//        $options['limit']   =   1;
//        // 分析表达式
//        $options            =   $this->parseOptions($options);
//        // 判断查询缓存
//        if(isset($options['cache'])){
//            $cache  =   $options['cache'];
//            $key    =   is_string($cache['key'])?$cache['key']:md5(serialize($options));
//        }
//        $resultSet          =   $this->db->select($options);
//        if(false === $resultSet) {
//            return false;
//        }
//        if(empty($resultSet)) {// 查询结果为空
//            return null;
//        }
//        if(is_string($resultSet)){
//            return $resultSet;
//        }
//
//        return $resultSet;
//    }
    /**
     * 获取数据表字段信息
     * @access public
     * @return array
     */
    public function getDbFields(){
        if(isset($this->options['table'])) {// 动态指定表名
            $table = is_array($this->options['table'])?
                key($this->options['table'])://当前单元的键名称
                $this->options['table'];
            $fields = $this->dao->getFields($table);
            return  $fields ? array_keys($fields) : false;
        }elseif($this->fields) {
            $fields     =  $this->fields;
            unset($fields['_type'],$fields['_pk']);
            return $fields;
        }
        return false;
    }

    /**
     * 分析表达式
     * @access protected
     * @param array $options 表达式参数
     * @return array
     * @throws \Exception
     */
    protected function parseOptions($options=array()) {
        if(is_array($options)){
            $options =  array_merge($this->options,$options);
        }

        // 自动获取表名
        if(!isset($options['table'])){
            $options['table']   =   $this->getTableName();
            $fields             =   $this->fields;
        }else{
            // 指定数据表 则重新获取字段列表 但不支持类型检测
            $fields             =   $this->getDbFields();
        }

        // 数据表别名
        if(!empty($options['alias'])) {
            $options['table']  .=   ' '.$options['alias'];
        }
        // 记录操作的模型名称
        $options['model']       =   $this->model_name;

        // 字段类型验证
        if(isset($options['where']) && is_array($options['where']) && !empty($fields) && !isset($options['join'])) {
            // 对数组查询条件进行字段类型检查
            foreach ($options['where'] as $key=>$val){
                $key            =   trim($key);
                if(in_array($key,$fields,true)){
                    if(is_scalar($val)) {
                        $this->parseType($options['where'],$key);
                    }
                }elseif(!is_numeric($key) && '_' != substr($key,0,1) && false === strpos($key,'.') && false === strpos($key,'(') && false === strpos($key,'|') && false === strpos($key,'&')){
                    if(!empty($this->options['strict'])){
                        throw new \Exception('_ERROR_QUERY_EXPRESS_');
                    }
                    unset($options['where'][$key]);
                }
            }
        }
        // 查询过后清空sql表达式组装 避免影响下次查询
        $this->options  =   array();
        // 表达式过滤
        return $options;
    }
    /**
     * 数据类型检测
     * @access protected
     * @param mixed $data 数据
     * @param string $key 字段名
     * @return void
     */
    protected function parseType(&$data,$key) {
        if(!isset($this->options['bind'][':'.$key]) && isset($this->fields['_type'][$key])){
            $fieldType = strtolower($this->fields['_type'][$key]);
            if(false !== strpos($fieldType,'enum')){
                // 支持ENUM类型优先检测
            }elseif(false === strpos($fieldType,'bigint') && false !== strpos($fieldType,'int')) {
                $data[$key]   =  intval($data[$key]);
            }elseif(false !== strpos($fieldType,'float') || false !== strpos($fieldType,'double')){
                $data[$key]   =  floatval($data[$key]);
            }elseif(false !== strpos($fieldType,'bool')){
                $data[$key]   =  (bool)$data[$key];
            }
        }
    }
    /**
     * 指定查询数量
     * Mysql数据库以外要谨慎使用
     * @access public
     * @param mixed $offset 起始位置
     * @param mixed $limit 查询数量
     * @return Model
     */
    public function limit($offset,$limit=null){
        if(!isset($limit) and strpos($offset,',')){
            list($offset,$limit)   =   explode(',',$offset);
        }
        $this->options['limit']     =   intval($offset).( $limit? ','.intval($limit) : '' );
        return $this;
    }
    /**
     * 指定分页
     * @access public
     * @param mixed $page 页数
     * @param mixed $listRows 每页数量
     * @return Model
     */
    public function page($page,$listRows=null){
        if(!isset($listRows) and strpos($page,',')){
            list($page,$listRows)   =   explode(',',$page);
        }
        $this->options['page']      =   array(intval($page),intval($listRows));
        return $this;
    }
    /**
     * 查询注释
     * @access public
     * @param string $comment 注释
     * @return Model
     */
    public function comment($comment){
        $this->options['comment'] =   $comment;
        return $this;
    }
    /**
     * 获取执行的SQL语句
     * @access public
     * @param boolean $fetch 是否返回sql
     * @return Model
     */
    public function fetchSql($fetch){
        $this->options['fetch_sql'] =   $fetch;
        return $this;
    }
    /**
     * 参数绑定
     * @access public
     * @param array|string $key  参数名
     * @param mixed $value  绑定的变量及绑定参数
     * @return Model
     */
    public function bind($key,$value=false) {
        if(is_array($key)){
            $this->options['bind'] =    $key;
        }else{
            $num =  func_num_args();
            if($num>2){//第一个参数之后全部包装成一个数组
                $params =   func_get_args();
                array_shift($params);//shift出来的是$key
                $this->options['bind'][$key] =  $params;
            }else{
                $this->options['bind'][$key] =  $value;
            }
        }
        return $this;
    }
    /**
     * 设置模型的属性值
     * @access public
     * @param string $name 属性名称
     * @param mixed $value 属性值
     * @return Model
     */
    public function setProperty($name,$value) {
        if(property_exists($this,$name))
            $this->$name = $value;
        return $this;
    }

}