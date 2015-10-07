<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/10/7 0007
 * Time: 19:09
 */
namespace System\Extension\Think;
use System\Core\Model;
use System\Exception\ParameterInvalidException;
use System\Util\SEK;

/**
 * Class ThinkModel ThinkPHP框架下的Model扩展
 * @package System\Extension
 */
class ThinkModel extends Model{


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
     * JOIN类型
     */
    const JOINTYPE_INNER = ' INNER ';//等同于 JOIN（默认的JOIN类型）,如果表中有至少一个匹配，则返回行
    const JOINTYPE_LEFT   = ' LEFT ';// 即使右表中没有匹配，也从左表返回所有的行
    const JOINTYPE_RIGHT  = ' RIGHT ';// 即使左表中没有匹配，也从右表返回所有的行
    const JOINTYPE_FULL   = ' FULL ';//只要其中一个表中存在匹配，就返回行
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
     * 自动验证配置
     * @var array
     */
    protected $_validate = array();
    /**
     * 获取主键名称
     * @access public
     * @return string
     */
    public function getPrimarKey() {
        return $this->attributes['primar_key'];
    }

    /**
     * 设置数据表主键
     * @param string $key 数据表主键
     * @return void
     */
    public function setprimarKey($key){
        $this->attributes['primar_key'] = $key;
    }
    /**
     * 指定查询条件 支持安全过滤
     * ①可以使用字符串直接查询
     * ②可以使用数组方式进行查询
     * ③不同于ThinkPHP，预处理机制只能针对参数二（在in/not_in的情况下参数可能是数组，故同意参数二二数组来避免歧义）
     * ③不同于ThinkPHP，where方法不可以多次调用，后面的调用会把结果覆盖到前面的结果上
     * @param mixed $where 条件表达式，可以是数组、字符串、对象，最终都是转化为字符串的形式
     * @param mixed $inputs 输入参数
     * @return $this
     */
    public function where($where,$inputs=null){
        if(is_string($where)){//字符串方式
            if(is_array($inputs)){
                //如果是数字键，不会覆盖之前的
                $this->bind = array_merge($this->bind,$inputs);
            }
            $this->options['where'] = $where;
        }else{//数组方式，使用标准字段数组
            $segments = $this->dao->makeSegments($where);
            $this->options['where'] = $segments[0];
            $this->bind = empty($this->bind)?$segments[1]:array_merge($this->bind,$segments[1]);
        }
        return $this;
    }
    /**
     * ①设置要操作的数据表的名称，不建议多表
     * ②参数为空时获取要操作的数据表
     * @param string|null $tablename 数据表名称
     * @param bool $check_prefix
     * @return $this
     */
    public function table($tablename=null,$check_prefix=true){
        if(isset($tablename)){
            if($check_prefix and 0 !== stripos(trim($tablename),$this->attributes['prefix'])){
                //判断参数中的表名称是否以前缀开头，不是则自动添加
                $tablename = $this->attributes['prefix'].$tablename;
            }
            $this->options['table'] = $tablename;
            return $this;
        }else{
            return isset($this->options['table'])?
                $this->options['table']:$this->attributes['real_tablename'];
        }
    }

    /**
     * 设置当前数据表的别名，便于使用其他的连贯操作例如join方法等(摘自ThinkPHP)
     * @param string $alianm 别名
     * @return $this
     */
    public function alias($alianm){
        $tablenm = $this->table();
        $this->options['table'] = " {$tablenm} {$alianm} ";
        return $this;
    }

    /**
     * 设置当前要操作的数据对象的值
     * @param $data
     * @return $this
     */
    public function data($data){
        $this->options['data'] = $data;
    }
    /**
     * 添加一个数据
     * @param array|null $data
     * @return int|string
     */
    public function add($data=null){
        if(!isset($data)){
            $data = &$this->options['data'];
        }
        return $this->create($data);
    }
    /**
     * 设置要查询的字段
     * @param string|array $fields 字段集合
     * @param boolean $except 是否排除参数一的字段
     * @return $this
     * @throws ParameterInvalidException
     */
    public function fields($fields=null,$except=false){
        if(is_string($fields)){//字符串则分解成数组
            $fields = explode(',',$fields);
        }elseif(true === $fields or null === $fields){//true或者null则获取全部
            if(empty($this->attributes['fields'])){
                $table = null;
                if(isset($this->options['table'])){
                    $table = $this->options['table'];
                }else{
                    $table = $this->table();
                }
                $this->attributes['fields'] = $this->dao->getFields($table);
            }
            $fields = $this->attributes['fields'];
        }

        if(is_array($fields)){
            $temp = '';
            foreach($fields as $value){
                $temp .= $this->dao->driver->escapeField($value).',';
            }
            $fields = rtrim($temp,',');
        }else{
            throw new ParameterInvalidException($fields);
        }
        if($except){//设置为排除
            $allfields = $this->getDbFields();
            $fields = array_diff($allfields,$fields);
        }
        $this->options['fields'] = $fields;
        return $this;
    }

    /**
     * 设置排序字段
     * @param string|array $order 排序字段
     * @return $this
     */
    public function order($order){
        if(is_string($order)){
            $this->options['order'] = $order;
        }else{
            $this->options['order'] = implode(',',$order);
        }
        return $this;
    }

    /**
     * 指定查询数量
     * @access public
     * @param mixed $offset 起始位置
     * @param mixed $length 查询数量
     * @return Model
     */
    public function limit($offset,$length=null){
        if(false !== strpos($offset,',') and null === $length){//参数一为'20,10'类似的情况
//            list($offset,$length) = explode(',',$offset);
            $this->options['limit'] = $offset;
        }else{
            if($length){
                $this->options['limit'] = " $offset , $length ";
            }else{
                $this->options['limit'] = " $offset ";
            }
        }
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
        if(is_null($listRows) && strpos($page,',')){
            list($page,$listRows)   =   explode(',',$page);
        }
        $this->options['page']      =   array(intval($page),intval($listRows));
        return $this;
    }

    /**
     * @param string|array $group
     * @return $this
     */
    public function group($group){
        if(is_string($group)){
            $this->options['group'] = $group;
        }else{
            $this->options['group'] = implode(',',$group);
        }
        return $this;
    }

    /**
     * 用于配合group方法完成从分组的结果中筛选（通常是聚合条件）数据
     * @param string $having
     * @return $this
     * @throws ParameterInvalidException
     */
    public function having($having){
        if(!is_string($having)) throw new ParameterInvalidException($having);
        $this->options['having'] = $having;
        return $this;
    }

    /**
     * 加入SQL中join的部分
     * @param array|string $join join参数
     * @param string $type JOIN的类型
     * @return $this
     */
    public function join($join,$type=ThinkModel::JOINTYPE_INNER){
        if(is_array($join)) {//如果是数组
            foreach ($join as &$_join){
                if(is_array($_join)){
                    $this->options['join'][] = " {$_join[1]} JOIN {$_join[0]} ";
                }else{//string
                    $this->options['join'][] = " {$type} JOIN {$join} ";
                }
            }
        }elseif(!empty($join)) {
            $this->options['join'][] = " {$type} JOIN {$join} ";
        }
        return $this;
    }
    public function union(){}

    /**
     * @param bool|true $distinct
     * @return $this
     */
    public function distinct($distinct=true){
        $this->options['join'] = $distinct;
        return $this;
    }
    /**
     * 查询缓存
     * @access public
     * @param mixed $key
     * @param integer $expire
     * @param string $type
     * @return Model
     */
    public function cache($key=true,$expire=null,$type=''){}//如果有cache，直接返回，否则查询并缓存
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
        }elseif($this->attributes['fields']) {
            $fields     =  $this->attributes['fields'];
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
            $fields             =   $this->attributes['fields'];
        }else{
            // 指定数据表 则重新获取字段列表 但不支持类型检测
            $fields             =   $this->getDbFields();
        }

        // 数据表别名
        if(!empty($options['alias'])) {
            $options['table']  .=   ' '.$options['alias'];
        }
        // 记录操作的模型名称
        $options['model']       =   $this->attributes['model_name'];

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
        if(!isset($this->options['bind'][':'.$key]) && isset($this->attributes['fields']['_type'][$key])){
            $fieldType = strtolower($this->attributes['fields']['_type'][$key]);
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
                return in_array(SEK::getClientIP(),explode(',',$rule));
            case 'ip_deny': // IP 操作禁止验证
                return !in_array(SEK::getClientIP(),explode(',',$rule));
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

    /**
     * < 暂时不提供对外使用 >
     * ThinkPHP下调用save方法更新数据的时候会自动判断当前的数据对象里面是否有主键值存在，
     * 如果有的话会自动作为更新条件
     * Mist下仅仅是update的一个重写而已
     * @param null $data
     * @param null $where
     * @return int|string
     */
    public function save($data=null,$where=null){
        if(!isset($data)){
            $data = &$this->options['data'];
        }
        return $this->update($where,$data);
    }

}