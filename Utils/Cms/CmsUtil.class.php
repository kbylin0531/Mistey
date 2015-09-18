<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/18
 * Time: 9:26
 */
namespace Utils\Cms;

use System\Utils\SessionUtil;

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
            return SessionUtil::get('user_auth_sign') == self::dataAuthSign($user) ? $user['uid'] : 0;
        }
    }

    /**
     * 数据签名认证
     * @param  array  $data 被认证的数据
     * @return string 签名
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function dataAuthSign($data) {
        //统一转换为数组类型
        if(!is_array($data)){
            $data = (array)$data;
        }
        //统一排序
        ksort($data);
        $data = http_build_query($data); //url编码并生成query字符串
        $sign = sha1($data); //生成签名
        return $sign;
    }

}