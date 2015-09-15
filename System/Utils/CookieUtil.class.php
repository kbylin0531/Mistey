<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/12
 * Time: 21:53
 */
namespace System\Utils;

class CookieUtil {

    private static $_config = array(
        'prefix'    =>  '', // cookie ����ǰ׺
        'expire'    =>  0, // cookie ����ʱ��
        'path'      =>  '/', // cookie ����·��
        'domain'    =>  '', // cookie ��Ч����
        'secure'    =>  false, //  cookie ���ð�ȫ����
        'httponly'  =>  0, // httponly����
    );

    /**
     * ��session��������г�ʼ������
     * @param array $config
     * @return bool
     */
    public static function init(array $config){
        if(is_array($config)){
            self::$_config = array_merge(self::$_config,array_change_key_case($config));
            return true;
        }
        return false;
    }

    public static function clear($name=''){
        if('' === $name){
            //�������
            if($_COOKIE){
                $prefix = empty($value) ? self::$_config['prefix'] : $value;
                //���������ǰ׺����ɾ��ָ����ǰ׺��cookie
                if($prefix){
                    foreach ($_COOKIE as $key => $val) {
                        if (0 === stripos($key, $prefix)) {
                            setcookie($key, '', time() - 3600,//ʱ������Ϊ֮ǰ�Ϳ�����
                                self::$_config['path'],
                                self::$_config['domain'],
                                self::$_config['secure'],
                                self::$_config['httponly']
                            );
                            unset($_COOKIE[$key]);
                        }
                    }
                }
            }else{
                //Ϊ������
            }
        }else{

        }
    }

    /**
     * ��ͻ��˷���cookie
     * @param $name
     * @param $value
     */
    public static function setCookie($name,$value){
        $name = self::$_config['prefix'].$name;
        $value  = serialize($value);
        $expire = self::$_config['expire']? time() + intval(self::$_config['expire']) : 0;
        setcookie($name, $value, $expire,
            self::$_config['path'],
            self::$_config['domain'],
            self::$_config['secure'],
            self::$_config['httponly']
        );
        $_COOKIE[$name] = $value;
    }

    /**
     * ��ȡcookie����
     * @param $name
     * @param null $defult_value
     * @return null
     */
    public static function getCookie($name=null,$defult_value = null){
        if(null === $name) return $_COOKIE;
        $name = self::$_config['prefix'] . str_replace('.', '_', $name);
        $key = self::$_config['prefix'].$name;
        return isset($_COOKIE[$key])?$_COOKIE[$key]:$defult_value;
    }


}