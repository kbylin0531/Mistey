<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/9
 * Time: 16:02
 */
namespace Application\Cms\Util;
use System\Util\SEK;
use System\Util\SessionUtil;

class CmsKits {

    /**
     * 及时显示提示信息
     * @param string $msg 提示信息
     * @param string $class 提示信息类型
     */
    public static function flushMessageToClient($msg, $class = ''){
        echo "<script type=\"text/javascript\">showmsg(\"{$msg}\", \"{$class}\")</script>";
        flush();
        ob_flush();
    }
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