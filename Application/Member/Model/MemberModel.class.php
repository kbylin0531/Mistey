<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/17
 * Time: 13:21
 */
namespace Application\Member\Model;
use System\Core\Dao;
use System\Core\Model;
use System\Extension\ThinkBase64;
use System\Util\SessionUtil;
use System\Utils\Util;

class MemberModel extends Model{

    protected $prefix = 'ot_';

    /**
     * 注册用户
     * @param array $member 用户信息
     * @param Dao $dao 数据库访问对象
     * @return int|string 返回错误信息 或者 成功插入的记录数目
     * @throws \Exception
     */
    public function registerMember($member,$dao =null){
        isset($dao) and $this->dao = $dao;
        $rst = $this->create(array(
            '1',
            $member['username'],
            ThinkBase64::encrypt($member['password']),
            $member['email'], '',
            $_SERVER['REQUEST_TIME'],
            Util::getClientIP(1),
            0,
            0,
            $_SERVER['REQUEST_TIME'],
            '1'
        ),$this->prefix.'ucenter_member');
        if(is_string($rst) or !$rst){
            SessionUtil::set('error',true);
            return $rst;
        }

        $rst = $this->create(array(
            '1', $member['username'], '0', '0000-00-00', '', '0', '1', '0', $_SERVER['REQUEST_TIME'], '0', $_SERVER['REQUEST_TIME'], '1'
        ),$this->prefix.'member');
        if(is_string($rst) or !$rst){
            SessionUtil::set('error',true);
            return $rst;
        }
        SessionUtil::set('error',false);
        return 1;
    }

}