<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/17
 * Time: 13:21
 */
namespace Application\Cms\Model;
use System\Core\Dao;
use System\Core\Model;
use System\Utils\Util;

class MemberModel extends Model{

    /**
     * 注册用户
     * @param array $member 用户信息
     * @param string $prefix 表前缀
     * @param Dao $dao 数据库访问对象
     * @return int|string 返回错误信息或者成功插入的记录数目
     * @throws \Exception
     */
    public function registerMember($member,$prefix='',$dao =null){
        isset($dao) and $this->dao = $dao;
        $rst = $this->create(array(
            '1', $member['username'], Util::pwd($member['password']), $member['email'], '', $_SERVER['REQUEST_TIME'],
            Util::getClientIP(1), 0, 0, $_SERVER['REQUEST_TIME'], '1'
        ),$prefix.'ucenter_member');
        if(is_string($rst) or !$rst){
            return $rst;
        }

        $rst = $this->create(array(
            '1', $member['username'], '0', '0000-00-00', '', '0', '1', '0', $_SERVER['REQUEST_TIME'], '0', $_SERVER['REQUEST_TIME'], '1'
        ),$prefix.'member');
        if(is_string($rst)){
            return $rst;
        }
        return $rst;
    }

}