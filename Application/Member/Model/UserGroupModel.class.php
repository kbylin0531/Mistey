<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/12
 * Time: 14:42
 */
namespace Application\Member\Model;
use Application\Installer\Util\InstallKits;
use System\Core\Model;
use System\Util\SessionUtil;

/**
 * Class UserGroupModel 用户组模型
 * @package Application\Member\Model
 */
class UserGroupModel extends Model{

    /**
     * 涉及的关系表
     * @var array
     */
    protected $_tables = array(
        'member'        => null,
        'ucenter_member'    => null,
        'auth_group_access' => null,// 关系表表名
        'auth_extend'   => null,// 动态权限扩展信息表
        'auth_group'    => null,// 用户组表名
    );


    public function __construct($config=null){
        parent::__construct($config);
        //初始化
        $prefix = InstallKits::getDatabaseConfig(DB_PREFIX);
        foreach($this->_tables as $key=>&$value){
            $value = $prefix.$key;
        }
    }

    /**
     * 返回用户拥有管理权限的扩展数据id列表
     * @param int     $uid  用户id
     * @param int     $type 扩展数据标识
     * @param int     $session  结果缓存标识
     * @return array 结果如array(2,4,8,13)
     *
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    public function getAuthExtend($uid,$type,$session){
        if (!$type) {
            return false;
        }
        if ($session) {
            $result = SessionUtil::get($session);
        }
        if ( $uid == UID && !empty($result) ) {
            return $result;
        }
        $sql = '
SELECT DISTINCT extend_id
FROM ot_auth_group_access g
INNER JOIN ot_auth_extend c ON g.group_id=c.group_id
WHERE  g.uid=:uid and c.type=:type
AND extend_id IS NOT NULL
AND extend_id > 0';
        $inputs = array(
            ':uid'  => $uid,
            ':type' => $type,
        );
        $result = $this->query($sql,$inputs);
        if(false === $result){
            return false;
        }
        if ( $uid == UID && $session ) {
            SessionUtil::set($session,$result);
        }
        return $result;
    }

}