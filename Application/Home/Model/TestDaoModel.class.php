<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/16
 * Time: 10:17
 */
namespace Application\Home\Model;
use System\Core\Model;
use System\Utils\Util;


/**
 * Class IndexModel
 * @package Application\Home\Model
 */
class TestDaoModel extends Model{

    private $ssql = "SELECT * from ot_action;";
    private $isql = "INSERT INTO `ot-1.1`.`ot_action_log`
    ( `action_id`, `user_id`, `action_ip`, `model`, `record_id`, `remark`, `status`, `create_time`)
    VALUES ( '1', '1', '0', :module, '1', :name, '1', :time);";

    private $sssql = 'SELECT * from ot_action;';


    /**
     * 测试入口
     * @return mixed
     */
    public function test(){
        return $this->testBasic01();
    }

    /**
     * 测试方法列表如下：
     * prepare
     * execute
     * fetchAll fetch
     * bindParam bindValue
     * @return mixed
     */
    public function testBasic01(){
        $rst = null;
        //测试返回全部结果
//        $rst = $this->dao->prepare($this->ssql)->execute()->fetchAll();

        //测试打印全部结果
        $this->dao->prepare($this->ssql)->execute();
        while($rst = $this->dao->fetch()){
            Util::dump($rst);
        }

        //测试查询
//        $this->dao->prepare($this->sssql)->execute();
//        $rst = $this->dao->fetchAll(\PDO::FETCH_COLUMN|\PDO::FETCH_GROUP,6);
//        $rst = $this->dao->fetchAll(\PDO::FETCH_COLUMN|\PDO::FETCH_GROUP,6);//测试值为键，id为值
//        $rst = $this->dao->fetchAll();//ASSOC
//        $rst = $this->dao->fetchAll(\PDO::FETCH_CLASS);//CLASS
//        while($rst = $this->dao->fetchColumn(2)){//$rst 的代码块范围是函数
//            Util::dump($rst);//最后一个值为false
//        }
//        while($rst = $this->dao->fetchObject()){//$rst 的代码块范围是函数
//            Util::dump($rst);//最后一个值为false
//        }


        //测试插入结果1
//        $this->dao->prepare($this->isql,'isql');
//        $time = time();
//        $module = 'M'.$time;
//        $name   = 'N'.$time;
//        $this->dao->bindParam(':module',$module);
//        $this->dao->bindParam(':name',$name);
//        $this->dao->bindParam(':time',$time);
//        $rst = $this->dao->execute()->rowCount();

        //测试插入结果2
//        $this->dao->prepare(null,'isql');
//        echo $this->dao->bindValue(':module','A'.time());
//        echo $this->dao->bindValue(':name','A'.time());
//        echo $this->dao->bindValue(':time',time());
//        $rst = $this->dao->execute()->rowCount();
        //测试插入结果3
//        $this->dao->prepare(null,'isql');
//        $rst = $this->dao->execute(array(
//            ':module'=> 'A'.time(),
//            ':name'=> 'A'.time(),
//            ':time'=> time()
//        ))->rowCount();



        return $rst;
    }



}