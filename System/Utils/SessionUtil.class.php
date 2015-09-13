<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/12
 * Time: 17:51
 */
namespace System\Utils;

use System\Exception\ParameterInvalidException;

/**
 * Class SessionUtil �Ự������
 * @package System\Utils
 *
 * ����ʹ�õĺ����б�
 * ��session_is_registered
 * ��session_register
 * ��session_unregister
 */
class SessionUtil{
    /**
     * �ͻ��˻�����Ʋ���
     * �ͻ��˻��ߴ��������ͨ����������Ӧͷ��Ϣ�� ȷ������ҳ�����ݵĻ������
     * nocache ���ֹ�ͻ��˻��ߴ����������������
     * public ��ʾ����ͻ��˻�����������������
     * private ��ʾ����ͻ��˻��棬 ���ǲ���������������������
     * private ģʽ�£� ���� Mozilla ���ڵ�һЩ����������޷���ȷ���� Expire ��Ӧͷ�� ͨ��ʹ�� private_no_expire ģʽ���Խ��������⣺������ģʽ�£� ������ͻ��˷��� Expire ��Ӧͷ
     */

    /**
     * Expires: Thu, 19 Nov 1981 08:52:00 GMT
     * Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0
     * Pragma: no-cache
     */
    const LIMITER_NOCACHE = 'nocache';
    /**
     * Expires�������� session.cache_expire ���趨����ó���
     * Cache-Control�� public, max-age=������ session.cache_expire ���趨����ó���
     * Last-Modified�����Ự��󱣴�ʱ�䣩
     */
    const LIMITER_PUBLIC  = 'public';
    /**
     * Expires: Thu, 19 Nov 1981 08:52:00 GMT
     * Cache-Control: private, max-age=������ session.cache_expire ���趨����ó���,
     *      pre-check=������ session.cache_expire ���趨����ó���
     * Last-Modified: ���Ự��󱣴�ʱ�䣩
     */
    const LIMITER_PEIVATE = 'private';
    /**
     * Cache-Control: private, max-age=������ session.cache_expire ���趨����ó���,
     *      pre-check=������ session.cache_expire ���趨����ó���
     * Last-Modified: ���Ự��󱣴�ʱ�䣩
     */
    const LIMITER_PEIVATE_WITHOUT_EXPIRE = 'private_no_expire';


    private static $_config = array(
        'cache_expire'  => 180,//Ĭ��session����ʱ��
        'name'          => APP_NAME,//�Ự����

    );

    /**
     * ��session��������г�ʼ������
     * @param array $config
     * @return bool
     */
    public static function init(array $config){
        if(is_array($config)){
            self::$_config = array_merge(self::$_config,$config);
            return true;
        }
        return false;
    }

    /**
     * ��ȡ ���� ���õ�ǰ����ĵ���ʱ��
     * ע����Ҫ��session_start֮ǰ���ò���Ч
     * @param string|null $new_cache_expire �µĵ���ʱ�䣬��λΪ���ӣ����Ϊnull��ʾ��ȡ
     * @return bool|int falseʱ��ʾ����ʧ��
     */
    public static function cacheExpire($new_cache_expire =null){
        if(isset($new_cache_expire)){
            if('nocache' === ini_get('session.cache_limiter')){
                return false;
            }
            return session_cache_expire('session.cache_expire');
        }
        return session_cache_expire();
    }

    /**
     * ��ȡ�����õ�ǰ����������������
     * ע����Ҫ��session_start֮ǰ���ò���Ч
     * @param null|string $cache_limiter Ϊnullʱ��ȡ��ǰ��������������
     * @return string
     */
    public static function cacheLimiter($cache_limiter = null){
        if(null === $cache_limiter){
            return session_cache_limiter();
        }
        return session_cache_limiter($cache_limiter);
    }

    /**
     * ��ȡ/���ûỰ����
     * ���� cookie ���� URL �еĻỰ���ƣ� ���磺PHPSESSID��
     * ֻ��ʹ����ĸ��������Ϊ�Ự���ƣ����龡���ܵĶ�һЩ��
     * ����������֪������֣����������� cookie ������û���˵���������ж��Ƿ�Ҫ����� cookie����
     * ���ָ���� name ������ ��ô��ǰ�ỰҲ��ʹ��ָ��ֵ��Ϊ����
     * @param string $newname nullʱ���ص�ǰ��session���ƣ��������ò�����֮ǰ������
     * @return string
     */
    public static function name($newname=null){
        return session_name($newname);
    }
    /**
     * Session data is usually stored after your script terminated without the need to call session_write_close()
     * session����ͨ���ڽű�ִ�н�����洢��������Ҫ���ú���session_write_close
     * but as session data is locked to prevent concurrent writes only one script may operate on a session at any time
     * ��������Ϊ��ֹ���е�д��session���ݻᱻ�������������κ�ʱ��ֻ��һ���ű����ܲ���һ��session
     * When using framesets together with sessions you will experience the frames loading one by one due to this locking
     * �������ʹ��frameset��session��ʱ����ᾭ����frame����һ����frame��������ڴ�
     * You can reduce the time needed to load all the frames by ending the session as soon as all changes to session variables are done.
     * ����Լ������е�frame�ļ���ʱ�䣬ͨ����session���ݲ�����ɺ󾡿����session�ķ�ʽ
     * @return void
     */
    public static function commit(){
        session_write_close();
    }

    /**
     * ���ص�ǰ�Ự���������ݣ���$_SESSION
     * @return string
     */
    public static function encode(){
        return session_encode();
    }

    /**
     * �Բ�������session���룬����䵽$_SESSION������
     * @param string $code_data �����������
     * @return bool
     */
    public static function decode($code_data){
        return session_decode($code_data);
    }

    /**
     * Re-initialize session array with original values
     * ����session�ĸĶ����ָ��������״̬
     * @return void
     */
    public static function reset(){
        session_reset();
    }

    /**
     * ��ȡ������session�ı���·��
     * @param string|null $path ����Ϊnullʱ��ȡ����·��
     * @return string
     */
    public static function savePath($path=null){
        return session_save_path($path);
    }

    /**
     * ��ȡsession��״̬(5.4)
     * PHP_SESSION_DISABLED if sessions are disabled.
     * PHP_SESSION_NONE if sessions are enabled, but none exists.
     * PHP_SESSION_ACTIVE if sessions are enabled, and one or more exists.
     * @return int ״̬����
     */
    public static function getStatus(){
        return session_status();
    }
    /**
     * ���session
     * @param string|array $name
     * @return void
     * @throws ParameterInvalidException
     */
    public static function clear($name=null){
        if(null === $name){
            $_SESSION = array();
        }elseif(is_string($name)){
            if(strpos($name,'.')){
                list($name1,$name2) =   explode('.',$name);
                unset($_SESSION[$name1][$name2]);
            }else{
                unset($_SESSION[$name]);
            }
        }elseif(is_array($name)){
            foreach($name as $val){
                self::clear($val);
            }
        }else{
            throw new ParameterInvalidException($name);
        }
    }

    /**
     * ����Ƿ�����
     * @param string $name
     * @return bool
     */
    public static function has($name){
        if(strpos($name,'.')){ // ֧������
            list($name1,$name2) =   explode('.',$name);
            return isset($_SESSION[$name1][$name2]);
        }else{
            return isset($_SESSION[$name]);
        }
    }

    /**
     * @param null|string $name Ϊnullʱ��ȡȫ��session
     * @return null
     */
    public static function get($name=null){
        if(!isset($name)){//��ȡȫ��
            return $_SESSION;
        }else{
            if(strpos($name,'.')){
                list($name1,$name2) =   explode('.',$name);
                return $_SESSION[$name1][$name2];
            }else{
                return $_SESSION[$name];
            }
        }
    }

    public static function sessionAutoStart(){

    }

    /**
     * �����Ự
     * �����ڽű����֮ǰ����
     * @return bool
     */
    public static function start(){
        return session_start();
    }

    /**
     * @return void
     */
    public static function pause(){
        session_write_close();
    }

    /**
     * ���ٻỰ��ȫ������
     * Ҫ������ʹ��session����Ҫ���µ���session_start����
     * @return bool
     */
    public static function destroy(){
//        $_SESSION =  array();
        //unset($_SESSION)�ᵼ��$_SESSION���鳹�׵ز���ʹ��
        //����session_unset�����ͷ����е�ע���session����
        session_unset();
        return session_destroy();
    }

    /**
     * sessionID����
     * @param string|null  $id ���õ�sessionID
     * @param bool|false $regenerate �Ƿ���������sessionID
     * @return string
     */
    public static function id($id=null,$regenerate=false){
        $regenerate and session_regenerate_id();
        return session_id($id);
    }

    /**
     * Ҫ��PHP�汾��5.4֮�����ʹ��
     *  �����û��Զ���Ự�洢�����ࣨ�汾5.4�Ժ�ʹ�ã�
     * @param \SessionHandlerInterface $session_handler ʵ���� SessionHandlerInterface �ӿڵĶ���,���� SessionHandler
     * @param bool|true $register_shutdown ������ session_write_close() ע��Ϊ register_shutdown_function() ����
     *                                     Ĭ��Ϊtrue��ʾsession�Զ��ڽű�ִ�н�����ʱ�����
     * @return bool
     */
    public static function setSaveHandler(\SessionHandlerInterface $session_handler, $register_shutdown= true){
        return session_set_save_handler($session_handler, $register_shutdown);
    }

    /**
     * ��ȡ/���ûỰ cookie ����
     * �������� array(
     *      "lifetime",// - cookie ���������ڣ�����Ϊ��λ��
     *      "path",// - cookie �ķ���·����
     *      "domain",// - cookie ����
     *      "secure",// - ����ʹ�ð�ȫ����ʱ���� cookie��
     *      "httponly",// - ֻ��ͨ�� http Э����� cookie
     * )
     * ���·�����Ч
     * ini_get('session.cookie_lifetime'),
     * ini_get('session.cookie_path'),
     * ini_get('session.cookie_domain'),
     * ini_get('session.cookie_secure'),
     * ini_get('session.cookie_httponly'),
     *      <==>
     * session_get_cookie_params()
     * @param array $params cookie��������
     * @return mixed
     */
    public static function cookieParams($params=null){
        if(isset($params)){
            session_set_cookie_params(
                $params[0],
                isset($params[1])?$params[1]:null,
                isset($params[2])?$params[2]:null,
                isset($params[3])?$params[3]:false,
                isset($params[4])?$params[4]:false
            );
        }
        return session_get_cookie_params();
    }

    /**
     * ����session
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public static function set($name,$value){
        if(strpos($name,'.')){
            list($name1,$name2) =   explode('.',$name);
            $_SESSION[$name1][$name2] = $value;
        }else{
            $_SESSION[$name] = $value;
        }
    }

}