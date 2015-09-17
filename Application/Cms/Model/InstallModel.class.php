<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/16
 * Time: 17:46
 */
namespace Application\Cms\Model;
use System\Core\Model;
use System\Utils\Util;

class InstallModel extends Model{

    /**
     * 构造
     * @param string|array $config 数据库连接标识符 或者 连接信息数组
     */
    public function __construct($config='0'){
        isset($config) and $this->init($config);
        parent::__construct();
    }

    /**
     * 创建数据库
     * @param array $dbname 数据库名称
     * @return bool
     */
    public function createDatabase($dbname){
        $rst = $this->dao->createDatabase(htmlspecialchars($dbname));
        return $rst?true:false;
    }

    /**
     * 执行创建数据库和插入记录的操作
     * @param string $sql 执行的SQL语句
     * @return int|string
     */
    public function execSql($sql){
        if(strtoupper(substr($sql, 0, 12)) == 'CREATE TABLE') {
            $name = preg_replace('/^CREATE TABLE `(\w+)` .*/s', '\1', $sql);
            $msg  = "正在创建数据表'{$name}'";
            $this->dao->exec($sql);

            if($this->dao->getTables($name)){
                return array(true,$msg.'...成功！');
            }else{
                return array(false,$msg.'...失败！');
            }
        }
        return $this->dao->exec($sql);
    }



}