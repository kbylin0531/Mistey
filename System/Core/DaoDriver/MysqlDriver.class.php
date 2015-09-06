<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/17
 * Time: 9:45
 */
namespace System\Core\DaoDriver;
use System\Exception\PHPExtensionNotOpenException;
use System\Utils\Util;
defined('BASE_PATH') or die('No Permission!');

/**
 * Class MysqlDriver
 * @package System\Core\DaoDriver
 */
class MysqlDriver extends DaoDriver{

    protected $_l_quote = '`';
    protected $_r_quote = '`';


    public function __construct($dsn,$username,$password,$option=array()){
        //检查扩展是否开启
        if(!Util::phpExtend('pdo_mysql')){
//            dl('pdo_mysql');
            throw new PHPExtensionNotOpenException('pdo_mysql');
        }
        parent::__construct($dsn,$username,$password,$option);
    }

    public function getTables(){

    }










}