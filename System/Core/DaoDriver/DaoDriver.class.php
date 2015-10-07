<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/17
 * Time: 9:47
 */
namespace System\Core\DaoDriver;
use System\Exception\PDOCreateFailedException;
use System\Exception\PDOPrepareFailedException;
defined('BASE_PATH') or die('No Permission!');
/**
 * Class DaoDriver
 * @package namespace System\Core\DaoDriver;
 *
 * 数据库驱动的具体细节实现类
 * 公共的方法在该类中实现
 * 子类根据具体数据库的不同选择不同的实现的方法在本类中以抽象方法表示
 */
abstract class DaoDriver extends \PDO{

    /**
     * 保留字段转义字符
     * mysql中是 ``
     * sqlserver中是 []
     * oracle中是 ""
     * @var string
     */
    protected static $_l_quote = null;
    protected static $_r_quote = null;

    /**
     * PDO驱动器名称
     * @var string
     */
    protected $driverName = null;

    /**
     * 禁止访问的PDO函数的名称
     * @var array
     */
    protected $forbidMethods = array(
        'forbid','getColumnMeta'
    );

    /**
     * 预设的PDO属性
     * @var array
     */
    protected $pdoAttr = array(
        \PDO::ATTR_AUTOCOMMIT => true,//为false时，每次执行exec将不被提交
        \PDO::ATTR_EMULATE_PREPARES => false,//不适用模拟预处理
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,//结果集返回形式
    );


    /**
     * 当前查询的Statement，为 PDOStatement::execute()准备的
     * @var \PDOStatement
     */
    protected $curStatement = null;

    /**
     * 创建驱动类对象
     * 子类需要调用 parent::__construct($dsn,$username,$password,$option); 类继承父类的构造，否则会发生错误
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array $option PDO构造函数参数项
     * @throws PDOCreateFailedException
     */
    public function __construct($dsn,$username,$password,$option=array()){
        $this->driverName = get_class($this);
        foreach($option as $key=>$val){//数字键不会覆盖
            $this->pdoAttr[$key] = $val;
        }
        parent::__construct($dsn,$username,$password,$this->pdoAttr);
    }

    /**
     * 调用PDO底层的prepare，获取PDO对象
     * @param string $statement SQL语句
     * @param array $driver_options 默认的配置为array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY);  \PDO::CURSOR_SCROLL前后可以回滚的不常使用
     * @return \PDOStatement
     */
    public function prepare($statement, array $driver_options = array()){
        return $this->curStatement = parent::prepare($statement,$driver_options);
    }
    /**
     * PDO对象上的execute方法
     *  注意与PDO::exec()和PDOStatement::execute()相区别
     * 执行预处理语句
     * 与方法exec的却别是，后者直接执行返回受影响结果
     * @param array $input_parameters
     * @param null|\PDOStatement 执行SQL的PDO对象，如果缺少该参数则使用上次的prepare的参数
     * @return bool|null 成功或者失败，返回null表示执行出错
     */
    public function execute(array $input_parameters = null,$pdoStatement=null){
        $rst = null;
        if($pdoStatement instanceof \PDOStatement){
            $rst = $pdoStatement->execute($input_parameters);
        }elseif($this->curStatement instanceof \PDOStatement){
            $rst = $this->curStatement->execute($input_parameters);
        }
        return $rst;
    }

    /**
     * 获取数据表
     * @param string $namelike
     * @param string $dbname 数据库名称
     * @return array
     */
    abstract public function getTables($namelike = '%',$dbname=null);
    /**
     * 取得数据表的字段信息
     * @access public
     * @param $tableName
     * @return array
     */
    abstract public function getFields($tableName);
    /**
     * 转义保留字字段名称
     * @param string $fieldname 字段名称
     * @return string
     */
    abstract public function escapeField($fieldname);
    /**
     * 根据SQL的各个组成部分创建SQL查询语句
     * @param string $tablename 数据表的名称
     * @param array $components sql组成部分
     * @param int $offset
     * @param int $limit
     * @return string
     */
    abstract public function buildSql($tablename,array $components,$offset=NULL,$limit=NULL);

    /**
     * 调用不存在的方法时
     * @param string $name 方法名称
     * @param array $args 方法参数
     * @return mixed
     */
    public function __call($name,$args){
        if(in_array($name,$this->forbidMethods,true))  return false;
        return call_user_func_array(array($this,$name),$args);
    }



}