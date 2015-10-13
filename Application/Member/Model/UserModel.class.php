<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/17
 * Time: 13:21
 */
namespace Application\Member\Model;
use Application\Installer\Util\InstallKits;
use System\Core\Dao;
use System\Core\Model;
use System\Exception\MistException;
use System\Extension\ThinkBase64;
use System\Util\SessionUtil;
use System\Utils\Util;

/**
 * Class UserModel 用户模型
 * @package Application\Member\Model
 */
class UserModel extends Model{
    /**
     * 登陆类型
     */
    const LOGINTYPE_USERNAME    = 'username';
    const LOGINTYPE_MOBILE      = 'username';
    const LOGINTYPE_EMAIL       = 'username';

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

    /**
     * 检查用户登录
     * @param string $username 用户名
     * @param string $password 密码，加密后的
     * @param string $logintype 登陆类型，对应用户表字段
     * @return bool 是否成功登陆
     * @throws MistException
     */
    public function checkUserPassword($username,$password,$logintype=self::LOGINTYPE_USERNAME){
        $this->init(InstallKits::getDatabaseConfig());
        $rst = $this->find(array(
            'username'  => $username,
        ),array($logintype));
        if(false === $rst){
            throw new MistException('查询失败！');
        }
        return $password === $rst['password'];
    }

}