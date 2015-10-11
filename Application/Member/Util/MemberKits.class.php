<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/10/11 0011
 * Time: 15:24
 */
namespace Application\Member\Util;
use System\Util\SEK;
use System\Util\SessionUtil;

/**
 * Class UserKits 用户工具集
 * @package Application\Cms\Util
 */
class MemberKits {
    /**
     * 获取用户ID
     * @return integer 等于0-未设置userid，需要重新登录，
     *                 大于0-当前登录用户ID
     * @return int
     */
    public static function getUserId(){
        $info = self::getUserInfo();
        if(isset($info)){
            return $info['userid'];
        }else{
            return 0;
        }
    }

    /**
     * 获取用户信息
     * @return null|array
     */
    public static function getUserInfo(){
        $info = SessionUtil::get('x_user_info');
        if(empty($info)){
            return null;
        }else{
            return SessionUtil::get('x_user_sign') === SEK::dataAuthSign($info)?$info : null;
        }
    }
}