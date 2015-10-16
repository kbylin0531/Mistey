<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/16
 * Time: 16:19
 */
namespace Application\Member\Model;
use Application\Common\Model\AdminModel;

class MemberModel extends AdminModel {

    /**
     * 字段配置
     * @var array
     */
    protected $fields = array(
        'uid'           => array(),
        'nickname'      => array(),
        'sex'           => array(),
        'birthday'      => array(),
        'username'      => array(),
        'password'      => array(),
        'email'         => array(),
        'mobile'        => array(),
        'qq'            => array(),
        'score'         => array(),
        'login'         => array(),
        'reg_time'      => array(),
        'reg_ip'        => array(),
        'last_login_time'  => array(),
        'last_login_ip' => array(),
        'update_time'   => array(),
        'status'        => array(),
    );


    public function __construct(){
        parent::__construct();

        //表的实际名称 前缀自动添加
        $this->setTableName('entity_user');
    }

    /**
     * 注册会员
     */
    public function createMember(){

    }
    /**
     * 注销会员
     */
    public function removeMember(){

    }

    /**
     * 删除状态为0的会员，已经被注销了
     */
    public function cleanMember(){

    }



    public function listMemberList($fields){

    }


}