<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/17
 * Time: 10:59
 */
namespace System\Core;
use System\Core\DaoDriver\DaoDriver;
use System\Exception\ParameterInvalidException;
use System\Mist;
use System\Utils\Util;
defined('BASE_PATH') or die('No Permission!');
/**
 * Class Dao 数据入口对象(DataAccessObject)
 * 具体方法的实现以来于各个驱动
 * @package System\Core
 */
class Dao{

    /**
     * SQL查询记录
     * @var array
     */
    private static $_query = array();

    /**
     * 数据库类型
     * 具体驱动的名称要根据驱动类型的名称而定
     */
    const DB_TYPE_MYSQL = 'mysql';
    const DB_TYPE_SQLSRV = 'sqlsrv';
    const DB_TYPE_ORACLE = 'oci';//Oracle Call Interface

    /**
     * 保存数据库的驱动，在类构造的过程中初始化
     * @var DaoDriver
     */
    protected $driver;

    /**
     * PDOStatement池
     * @var array
     */
    protected $statementPool = array();
    /**
     * 指向当前的PDOStatement对象
     * @var \PDOStatement
     */
    protected $curStatement = null;

    /**
     * 实例共用的配置信息
     * @var array
     */
    protected static $_conf = array(
        'MASTER_NO'    => 0,
        'DB_CONNECT'   =>   array(
            0   =>  array(
                'type'   =>  Dao::DB_TYPE_MYSQL,//数据库类型
                'dbname'   => 'ot-1.1',//选择的数据库
                'username'   =>  'root',
                'password'   => '123456',
                'host' => 'localhost',
                'port' => '3306',
                'charset'   => 'UTF8',
                'dsn'       => null,//默认先检查差DSN是否正确
                'options'    => array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,//默认异常模式
                ),
            )
        ),
    );
    /**
     * 数据库连接池
     * 刀池
     * @var array
     */
    private static $daoPool = array();

    /**
     * 记录类是否已经初始化过
     * @var bool
     */
    private static $_hasInited = false;
    /**
     * 选择数据库连接
     * @param int|string $identifier
     */
    public static function getInstance($identifier=0){
        self::$_hasInited or self::init();
        if(!isset(self::$daoPool[$identifier])){//创建连接
            self::$daoPool[$identifier] = new Dao(self::$_conf['DB_CONNECT'][$identifier]);
        }
        return self::$daoPool[$identifier];
    }

    /**
     * 获取应用实例
     * @return object
     */
    public static function init(){
        $config = ConfigHelper::loadConfig('database');
        if(isset($config)){
            Util::mergeConf(static::$_conf,$config,false);
        }
        self::$_hasInited = true;
    }


    /**
     * Dao构造函数
     * @param null|array $config 数据库连接参数
     * @param bool $cover
     */
    public function __construct($config=null,$cover=false){
        $classname = 'System\\Core\\DaoDriver\\'.ucwords($config['type']).'Driver';
        try{
            $this->driver = new $classname(self::buildDSN($config),$config['username'],$config['password'],$config['options']);
        }catch (\PDOException $e){//连接失败总会抛出异常
            //收集和处理异常信息
            Mist::handleException($e);
        }
    }


    /**
     * 更具不同的数据库类型获取不同的DSN
     * @param array $config 数据库连接配置
     * @return string|null
     */
    public static function buildDSN($config){
        $dsn = null;
        switch($config['type']){
            case self::DB_TYPE_MYSQL://MySQL
                if(isset($config['dsn'])){
                    $dsn = $config['dsn'];
                }else{
                    $dsn  =   'mysql:dbname='.$config['dbname'].';host='.$config['host'];
                    if(!empty($config['port'])) {
                        $dsn .= ';port=' . $config['port'];
                    }
                    if(!empty($config['socket'])){
                        $dsn  .= ';unix_socket='.$config['socket'];
                    }
                    if(!empty($config['charset'])){
                        $dsn  .= ';charset='.$config['charset'];
                    }
                }
                break;
            case self::DB_TYPE_SQLSRV:
                break;
            case self::DB_TYPE_ORACLE:
                break;
            default:;
        }
        return $dsn;
    }

    /**
     * 获取所有可用的数据库PDO驱动
     * @return array
     */
    public static function getAvailableDrivers(){
        return \PDO::getAvailableDrivers();
    }

/*********************** 基本功能（CURD）:简单地执行SQL语句，对外不涉及PDOStatement结果集等特性 *****************************/
    /**
     * 为指定的数据表插入一条数据
     * <code>
     *      $fldsMap ==> array(
     *          //-- 第一种情况，不会进行转义 --//
     *          'fieldName' => 'fieldValue',
     *          //-- 第二种情况,[值，是否转义] --//
     *          'fieldName' => array('fieldValue',boolean),
     *      );
     *
     *     #牛人想到的一个办法(MySQL)
     *     $data = ['a'=>'foo','b'=>'bar'];
     *     $keys = array_keys($data);
     *     $fields = '`'.implode('`, `',$keys).'`';
     *     #here is my way
     *     $placeholder = substr(str_repeat('?,',count($keys),0,-1));
     *     $pdo->prepare("INSERT INTO `baz`($fields) VALUES($placeholder)")->execute(array_values($data));
     * </code>
     * @param string $tablename
     * @param array $fieldsMap
     * @return string|int
     */
    public function create($tablename,$fieldsMap){
        $fields    = '';
        $placeholder     = '';
        $bind  = array();
        foreach($fieldsMap as $fieldName=>$fieldValue){
            $fieldName = trim($fieldName,' :');
            $colnm = $fieldName;
            if(is_array($fieldValue)){
                $colnm = $fieldValue[1]?$this->driver->escapeField($fieldName):$fieldName;
                $fieldValue = $fieldValue[0];
            }
            //拼接插入SQL字符串
            $fields .= " {$colnm} ,";
            $placeholder  .= " :{$fieldName} ,";

            $bind[":{$fieldName}"] = $fieldValue;
        }
        $fields = rtrim($fields,',');
        $placeholder  = rtrim($placeholder,',');
        return $this->prepare("insert into {$tablename} ( {$fields} ) VALUES ( {$placeholder} );")
                    ->execute($bind)->doneExecute();
    }

    /**
     * 为指定的数据表更新记录
     * @param $tablename
     * @param $flds
     * @param $whr
     * @return int|string 返回受影响的行数
     */
    public function update($tablename,$flds,$whr){
        $fields = $this->makeSegments($flds);
        $where  = $this->makeSegments($whr);
        return $this->prepare("update {$tablename} set {$fields[0]} where {$where[0]};")
                    ->execute(array_merge($fields[1],$where[1]))
                    ->doneExecute();
    }

    /**
     * 删除数据
     * @param $tablename
     * @param $whr
     * @return int|string
     */
    public function delete($tablename,$whr){
        $where  = $this->makeSegments($whr);
        return $this->prepare("delete from {$tablename} where {$where[0]};")
            ->execute($where[1])
            ->doneExecute();
    }

    /**
     * 查询一段SQL，并且将解析出所有的结果集合
     * @param string $sql
     * @return array|mixed
     */
    public function query($sql){
        self::$_query[] = $sql;
        return $this->driver->query($sql)->fetchAll();
    }

    /**
     * 简单地执行Insert、Delete、Update操作
     * @param string $sql
     * @return int|void
     */
    public function exec($sql){
        self::$_query[] = $sql;
        return $this->driver->exec($sql);
    }

    /**
     * 获取执行过的SQL
     * @param bool $get_last
     * @return mixed|string
     */
    public static function getSql($get_last=true){
        return $get_last?end(self::$_query):self::$_query;
    }

    /**
     * 返回PDO驱动或者上一个PDO语句对象上发生的错误的信息（具体驱动的错误号和错误信息）
     * @return string 返回错误信息字符串，没有错误发生时返回空字符串
     */
    public function getErrorInfo(){
        $pdoError = $this->driver->errorInfo();
        $stmtError = $this->curStatement->errorInfo();
        $message = '';
        isset($pdoError[1]) and $message .= "[{$pdoError[1]}]:[{$pdoError[2]}]";
        isset($stmtError[1]) and $message .= "[{$stmtError[1]}]:[{$stmtError[2]}]";
        return $message;
    }

    /**
     * 综合字段绑定的方法
     * <code>
     *      $operator = '='
     *          $fieldName = :$fieldName
     *          :$fieldName => trim($fieldValue)
     *
     *      $operator = 'like'
     *          $fieldName = :$fieldName
     *          :$fieldName => dowithbinstr($fieldValue)
     *
     *      $operator = 'in|not_in'
     *          $fieldName in|not_in array(...explode(...,$fieldValue)...)
     * </code>
     * @param string $fieldName 字段名称
     * @param string|array $fieldValue 字段值
     * @param string $operator 操作符
     * @param bool $translate 是否对字段名称进行转义,MSSQL中使用[]
     * @return array
     * @throws ParameterInvalidException
     */
    protected function makeFieldBind($fieldName,$fieldValue,$operator='=',$translate=false){
        static $suffix = 1;//使用场景是update时既有set绑定又有where绑定时，区分前后二者，将suffix设置可以防止前后冲突
        //绑定设置
        $fieldName = trim($fieldName, ' :');
        $fieldBindName = null;
        if (false !== strpos($fieldName, '.')) {//存在则选择最后一节 ot_students.id  ==> id
            $arr = explode('.', $fieldName);
            $fieldBindName = ':' . array_pop($arr);
        } elseif (mb_strlen($fieldName, 'utf-8') < strlen($fieldName)) {//字段名称为其他的编码
            $fieldBindName = ':' . md5($fieldName);
        } else {
            $fieldBindName = ":{$fieldName}";
        }
        $fieldBindName .= $suffix;//后缀衔接
        //操作符设置
        $operator = strtolower(trim($operator));
        $sql = $translate ? $this->driver->escapeField($fieldName) : $fieldName ;
        $bind = array();

        switch ($operator) {
            case '=':
                $sql .= " = {$fieldBindName} ";
                $bind[$fieldBindName] = $fieldValue;
                break;
            case 'like':
                $sql .= " like {$fieldBindName} ";
                $bind[$fieldBindName] = $fieldValue;
                break;
            case 'in':
            case 'not in':
                if (is_string($fieldValue)) {
                    $sql .= " {$operator} ({$fieldValue}) ";
                } elseif (is_array($fieldValue)) {
                    $sql .= " {$operator} ('" . implode("','", $fieldValue) . "')";
                } else {
                    throw new ParameterInvalidException($fieldName);
                }
                break;
            default:
                throw new ParameterInvalidException($fieldValue);
        }
        ++$suffix;
        return array(
            $sql,
            $bind,
        );
    }

    /**
     * <note>
     *      片段准则
     *      $map == array(
     *          //-- 第一种情况,连接符号一定是'='，并且字段名称不是保留字 --//
     *          'key' => $val,
     *          //-- 第二种情况，[绑定值，是否转义，操作符] --//
     *          'key' => array($val,true,$operator),//布尔值情况如下,遗留问题，参数二和三应该倒置
     *          //-- 第三种情况，[完整的SQL片段，绑定名称，绑定值] --//
     *          array('assignSql',':bindSQLSegment',value),//参数4的值为true时表示对key进行[]转义
     *      );
     * </note>
     * @param array $segments 字段绑定片段
     * @param bool $is_and 表示是否使用and作为连接符，false时为,
     * @return array
     */
    protected function makeSegments($segments,$is_and=true){
        //初始值与参数检测
        $bind = array();
        $sql = '';
        if(empty($segments)){
            return array($sql,$bind);
        }
        //片段之间的连接
        $bridge = $is_and?'and':',';

        //元素连接
        foreach($segments as $key=>$val){
            if(is_numeric($key)){//第三种情况
                $sql .= " {$val[0]} $bridge";
                $bind[$val[1]] = $val[2];
            }else{
                $rst = null;
                if(is_array($val)){//第二种情况
                    $rst = $this->makeFieldBind(
                        $val[0],
                        $val[1],
                        empty($val[2])?' = ':$val[2],
                        $val[3]
                    );
                }else{//第一种情况
                    $rst = $this->makeFieldBind($key,$val);
                }
                //合并绑定参数
                if(is_array($rst)){
                    $sql .= " {$rst[0]} $bridge";
                    $bind = array_merge($bind, $rst[1]);
                }
            }
        }
        return array(
            substr($sql,0,strlen($sql)-strlen($bridge)),//去除最后一个and
            $bind,
        );
    }

    /**
     * 执行结果信息返回
     * @return int|string 返回受影响行数，发生错误时返回错误信息
     */
    public function doneExecute(){
        $errorInfo = $this->getErrorInfo();
        return empty($errorInfo)?$this->rowCount():$errorInfo;
    }

    /**
     * 查询结果集返回
     * @return string|Dao 返回查询结果集，发生错误时返回错误信息
     */
    public function donwQuery(){
        $errorInfo = $this->getErrorInfo();
        return empty($errorInfo)?$this:$errorInfo;
    }

/*********************** 高级功能（事务，PDOStatement等） ***************************************************************/
    /**
     * @return bool
     */
    public function beginTransaction(){
        return $this->driver->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit(){
        return $this->driver->commit();
    }

    /**
     * @return bool
     */
    public function rollBack(){
        return $this->driver->rollBack();
    }

    /**
     * @return bool
     */
    public function inTransaction(){
        return $this->driver->inTransaction();
    }



    /**
     * 准备一段SQL
     *  <note>
     *      prepare('insert *****',$id='helo');  准备一段SQL并命名ID为helo
     *      prepare( null|false|''|0 ,$id='helo');  切换到该ID下，并将PDOStatement返回
     *      prepare('insert *****');  将SQL语句设置ID为0并默认指向0
     *  </note>
     * @param string $sql 查询的SQL，当参数二指定的ID存在，只有在参数一布尔值不为false时，会进行真正地prepare
     * @param null|string $id   不设置ID或者ID的值为布尔类型的false时，将把ID设置为0，并默认指针指向0
     *                          设置了ID时将先检查对应的ID是否存在对应的PDOStatement，如果不存在则将当前查询的语句绑定到该ID下
     * @param array $option prepare方法参数二
     * @return Dao
     */
    public function prepare($sql,$id=NULL,$option=array()){
        !$id and $id = 0;
        if(!isset($this->statementPool[$id])){
            //采取默认的方式准备一个查询语句 或者 重新设置一个查询语句
            try{
                $this->statementPool[$id] = $this->driver->prepare($sql,$option);
            }catch (\PDOException $e){
                Mist::handleException($e);
            }
        }
        $this->curStatement = $this->statementPool[$id];
        return $this;
    }


    /**
     * 绑定一个参数到指定的变量名
     * 绑定一个PHP变量到用作预处理的SQL语句中的对应命名占位符或问号占位符。
     *      不同于 PDOStatement::bindValue() ，此变量作为引用被绑定，
     *      并只在 PDOStatement::execute() 被调用的时候才取其值
     * <note>
     *      ①如果要使用like查询，%的位置应该在变量处而非SQL语句中
     *      ②foreach ($params as $key => &$val) { $sth->bindParam($key, $val); }时正确的
     *        foreach ($params as $key => $val) { $sth->bindParam($key, $val); }会失败，因为bingParam参数二明确要求是引用变量
     *      ③在MySQL中经过绑定参数，值得类型会发生改变
     *          $active = 1;
     *          $active === 1; //is true
     *          $ps->bindParam(":active", $active, PDO::PARAM_INT);
     *          $ps->execute();
     *          $active === 1;//  will be false
     *      ④一个值对应多个位置在PHP5.2.0及之前的版本中会导致错误，在5.2.1版本之后貌似能正常工作
     *          $sql = "SELECT * FROM u WHERE a = :myValue AND d = :myValue ";
     *          $params = array("myValue" => "0");
     *
     * </note>
     * @param int|string $parameter 参数标识符。
     *                          对于使用命名占位符的预处理语句，应是类似 :name 形式的参数名。
     *                          对于使用问号占位符的预处理语句，应是以1开始索引的参数位置。
     * @param mixed $variable 绑定到 SQL 语句参数的 PHP 变量名
     * @param int $data_type 使用 PDO::PARAM_* 常量明确地指定参数的类型。
     *                       要从一个存储过程中返回一个 INOUT 参数，需要为 data_type 参数使用按位或操作符去设置 PDO::PARAM_INPUT_OUTPUT 位。
     * @param int $length 数据类型的长度。为表明参数是一个存储过程的 OUT 参数，必须明确地设置此长度
     * @param mixed $driver_options 驱动的可选参数
     * @return bool 成功时返回 TRUE， 或者在失败时返回 FALSE。
     */
    public function bindParam($parameter, &$variable, $data_type = \PDO::PARAM_STR, $length = null, $driver_options = null){
        return $this->curStatement->bindParam($parameter,$variable,$data_type,$length,$driver_options);
    }

    /**
     * 绑定一个值到用作预处理的 SQL 语句中的对应命名占位符或问号占位符
     *  参数一和三的意义同bindParam，参数二的意义类似，只是bindValue传递的是值，而非引用
     * <note>
     *      ①由于参数二传递的是值，所以类似一下的调用可以通过，而相同的参数bindParam方法是不通过的
     *          $stmt->bindValue(":something", "bind this");
     * </note>
     * @param mixed $parameter 参数标识符。对于使用命名占位符的预处理语句，应是类似 :name 形式的参数名。对于使用问号占位符的预处理语句，应是以1开始索引的参数位置。
     * @param mixed $value
     * @param int $data_type
     * @return bool 成功时返回 TRUE， 或者在失败时返回 FALSE
     */
    public function bindValue($parameter, $value, $data_type = \PDO::PARAM_STR){
        return $this->curStatement->bindValue($parameter, $value, $data_type);
    }

    /**
     * 安排一个特定的变量绑定到一个查询结果集中给定的列。每次调用 PDOStatement::fetch()
     *  或 PDOStatement::fetchAll() 都将更新所有绑定到列的变量
     * <note>
     *      ①在语句执行前 PDO 有关列的信息并非总是可用，可移植的应用应在 PDOStatement::execute() 之后 调用此函数（方法）。
     *      ②但是，当使用 PgSQL 驱动 时，要想能绑定一个 LOB 列作为流，应用程序必须在调用 PDOStatement::execute() 之前调用此方法，
     *        否则大对象 OID 作为一个整数返回
     *      ③用法实例：
     *          $stmt = $dbh->prepare('SELECT name, colour, calories FROM fruit');
     *          $stmt->execute();//在execute之后、fetch之前调用
     *          $stmt->bindColumn(1, $name);
     *          $stmt->bindColumn(2, $colour);
     *          $stmt->bindColumn('calories', $cals);//通过名称绑定
     *          while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {//参数传入PDO::FETCH_BOUND
     *              echo $name . "\t" . $colour . "\t" . $cals . "\n";
     *          }
     * </note>
     * @param int|string $column 结果集中的列号（从1开始索引）或列名。如果使用列名，注意名称应该与由驱动返回的列名大小写保持一致。
     * @param mixed $param 将绑定到列的 PHP 变量的引用
     * @param int  $type 通过 PDO::PARAM_* 常量指定的参数的数据类型
     * @param int  $maxlen 预分配提示
     * @param mixed $driverdata 驱动的可选参数
     * @return bool 成功时返回 TRUE， 或者在失败时返回 FALSE
     */
    public function bindColumn($column, &$param, $type = null, $maxlen = null, $driverdata = null){
        return $this->curStatement->bindColumn($column,$param,$type,$maxlen,$driverdata);
    }

    /**
     * 释放到数据库服务的连接，以便发出其他 SQL 语句，但使语句处于一个可以被再次执行的状态
     * 当上一个执行的 PDOStatement 对象仍有未取行时，此方法对那些不支持再执行一个 PDOStatement 对象的数据库驱动非常有用。
     * 如果数据库驱动受此限制，则可能出现失序错误的问题
     * PDOStatement::Cursor() 要么是一个可选驱动的特有方法（效率最高）来实现，要么是在没有驱动特定的功能时作为一般的PDO 备用来实现
     * <note>
     *      ① 语意上相当于下面的语句的执行结果
     *          do {
     *              while ($stmt->fetch());
     *              if (!$stmt->nextRowset()) break;
     *          } while (true);
     * </note>
     * @param \PDOStatement|null $statement
     * @return bool 成功时返回 TRUE， 或者在失败时返回 FALSE
     */
    public function closeCursor($statement=null){
        isset($statement) and $this->curStatement = $statement;
        return $this->curStatement->closeCursor();
    }

    /**
     * 返回由 PDOStatement 对象代表的结果集中的列数
     * <note>
     *      ①只有在执行PDOStatement::execute()之后才能准确地获取列数，空的结果集的列数位0
     * </note>
     * @return int
     */
    public function columnCount(){
        return $this->curStatement->columnCount();
    }

    /**
     * 获取预处理语句包含的信息
     * <note>
     *      ①实际不能获取参数的值，不像文档中写的那样
     *      ②无论是否发生了错误，信息都会存在
     * </note>
     * @return string
     */
    public function getDebugDumpParams(){
        ob_start();//开始本层次的ob缓冲区
        $this->curStatement->debugDumpParams();
        return ob_get_clean();// 相当于ob_get_contents() 和 ob_end_clean()
    }

    /**
     * @param array $input_parameters
     *                  一个元素个数和将被执行的 SQL 语句中绑定的参数一样多的数组。
     *                  所有的值作为 PDO::PARAM_STR 对待。
     *                  不能绑定多个值到一个单独的参数,如果在 input_parameters 中
     *                  存在比 PDO::prepare() 预处理的SQL 指定的多的键名，
     *                  则此语句将会失败并发出一个错误。
     *                  (这个错误在PHP 5.2.0版本之前是默认忽略的，个人认为忽略是不负责任的行为，即使错误就不该被执行)
     * @param \PDOStatement $statement
     * @return Dao|string
     */
    public function execute(array $input_parameters = null, \PDOStatement $statement=null){
        isset($statement) and $this->curStatement = $statement;
        self::$_query[] = $this->curStatement->queryString;
        return $this->curStatement->execute($input_parameters)?$this:$this->getErrorInfo();
    }

    /**
     * @param int $fetch_style
     *              \PDO::FETCH_ASSOC 关联数组
     *              \PDO::FETCH_BOUND 使用PDOStatement::bindColumn()方法时绑定变量
     *              \PDO::FETCH_CLASS 放回该类的新实例，映射结果集中的列名到类中对应的属性名
     *              \PDO::FETCH_OBJ   返回一个属性名对应结果集列名的匿名对象
     * @param int $cursor_orientation 默认使用\PDO::FETCH_ORI_NEXT，还可以是PDO::CURSOR_SCROLL，PDO::FETCH_ORI_ABS，PDO::FETCH_ORI_REL
     * @param int $cursor_offset
     *              参数二设置为PDO::FETCH_ORI_ABS(absolute)时，此值指定结果集中想要获取行的绝对行号
     *              参数二设置为PDO::FETCH_ORI_REL(relative) 时 此值指定想要获取行相对于调用 PDOStatement::fetch() 前游标的位置
     * @return mixed 此函数（方法）成功时返回的值依赖于提取类型。在所有情况下，失败都返回 FALSE
     */
    public function fetch($fetch_style = null, $cursor_orientation = \PDO::FETCH_ORI_NEXT, $cursor_offset = 0){
        //设置fetch_style，未设置时使用默认
        isset($fetchStyle) or $fetch_style = $this->driver->getAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE);
        return $this->curStatement->fetch($fetch_style,$cursor_orientation,$cursor_offset);
    }

    /**
     * 返回一个包含结果集中所有剩余行的数组
     * 此数组的每一行要么是一个列值的数组，要么是属性对应每个列名的一个对象
     * @param int|null $fetch_style
     *          想要返回一个包含结果集中单独一列所有值的数组，需要指定 PDO::FETCH_COLUMN ，
     *          通过指定 column-index 参数获取想要的列。
     *          想要获取结果集中单独一列的唯一值，需要将 PDO::FETCH_COLUMN 和 PDO::FETCH_UNIQUE 按位或。
     *          想要返回一个根据指定列把值分组后的关联数组，需要将 PDO::FETCH_COLUMN 和 PDO::FETCH_GROUP 按位或
     * @param int $fetch_argument
     *                  参数一为PDO::FETCH_COLUMN时，返回指定以0开始索引的列（组合形式如上）
     *                  参数一为PDO::FETCH_CLASS时，返回指定类的实例，映射每行的列到类中对应的属性名
     *                  参数一为PDO::FETCH_FUNC时，将每行的列作为参数传递给指定的函数，并返回调用函数后的结果
     * @param array $constructor_args 参数二为PDO::FETCH_CLASS时，类的构造参数
     * @return array
     */
    public function fetchAll($fetch_style = null, $fetch_argument = null, $constructor_args = null){
//        isset($fetchStyle) or $fetch_style = $this->driver->getAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE);
        $param = array();
        if(isset($fetch_style)){
            $param[] = $fetch_style;
            if(isset($fetch_argument)){
                $param[] = $fetch_argument;
                if(isset($constructor_args)){
                    $param[] = $constructor_args;
                }
            }
        }
        return call_user_func_array(array($this->curStatement,'fetchAll'),$param);
    }

    /**
     * 从结果集中的下一行返回单独的一列。
     * （这样的一列返回后，结果集中的指针将往后移动）
     * <note>
     *      ①这个方法很有用处的是：
     *          $db = new PDO('mysql:host=localhost;dbname=pictures','user','password');
     *          $pics = $db->query('SELECT COUNT(id) FROM pics');
     *          $this->totalpics = $pics->fetchColumn();
     *          $db = null;
     * </note>
     * @param int $column_number 列的索引，默认是第一列
     * @return string 从结果集中的下一行返回单独的一列，如果没有了，则返回 FALSE
     */
    public function fetchColumn($column_number = 0){
        return $this->curStatement->fetchColumn($column_number);
    }

    /**
     * 获取下一行并作为一个对象返回
     * 适合做框架中的Model类
     * 说明：获取下一行并作为一个对象返回。此函数（方法）是使用 PDO::FETCH_CLASS 或 PDO::FETCH_OBJ 风格的 PDOStatement::fetch() 的一种替代
     * @param string $class_name 类的名称,默认是stdClass类
     * @param array $constructor_args 构造函数参数
     * @return bool|Object 返回一个属性名对应于列名的所要求类的实例， 或者在失败时返回 FALSE
     */
    public function fetchObject($class_name = 'stdClass', array $constructor_args = array()){
        return $this->curStatement->fetchObject($class_name,$constructor_args);
    }

    /**
     * 返回上一个由对应的 PDOStatement 对象执行DELETE、 INSERT、或 UPDATE 语句受影响的行数
     * 如果上一条由相关 PDOStatement 执行的 SQL 语句是一条 SELECT 语句，有些数据可能返回由此语句返回的行数
     * 但这种方式不能保证对所有数据有效，且对于可移植的应用不应依赖于此方式
     * @return int
     */
    public function rowCount(){
        return $this->curStatement->rowCount();
    }


}