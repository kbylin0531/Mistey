<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace System\Extension;
/**
 * Base64 ����ʵ����
 */
class ThinkBase64 {
    private static $key = 'linzhv@qq.com';
    /**
     * ���ü��ܵ�Կ��
     * @param $key
     * @return void
     */
    public static function setKey($key){
        self::$key = $key;
    }
    /**
     * �����ַ���
     * @param string $data �ַ���
     * @param string $key ����key
     * @param integer $expire ��Ч�ڣ��룩
     * @return string
     */
    public static function encrypt($data,$key=null,$expire=0) {
        $expire = sprintf('%010d', $expire ? time()+$expire:0);
        $key  = isset($key)?md5($key):md5(self::$key);
        $data = base64_encode($expire.$data);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        $char = $str    =   '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
        }
        return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
    }
    /**
     * �����ַ���
     * @param string $data �ַ���
     * @param string $key ����key
     * @return string
     */
    public static function decrypt($data,$key=null) {
        $key  = isset($key)?md5($key):md5(self::$key);
        $data   = str_replace(array('-','_'),array('+','/'),$data);
        $mod4   = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        $data   = base64_decode($data);
        $x      = 0;
        $len    = strlen($data);
        $l      = strlen($key);
        $char   = $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }else{
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        $data   = base64_decode($str);
        $expire = substr($data,0,10);
        if($expire > 0 && $expire < time()) {
            return '';
        }
        $data   = substr($data,10);
        return $data;
    }
}