<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/18
 * Time: 9:26
 */
namespace Utils\Cms;
use System\Util\SEK;
use System\Util\SessionUtil;

class CmsUtil{

    /**
     * 检测用户是否登录
     * @return integer 0-未登录，大于0-当前登录用户ID
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function getUid(){
        $user = SessionUtil::get('user_auth');
        if (empty($user)) {
            return 0;
        } else {
            return SessionUtil::get('user_auth_sign') == SEK::dataAuthSign($user) ? $user['uid'] : 0;
        }
    }


}