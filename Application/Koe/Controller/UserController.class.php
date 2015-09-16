<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 19:25
 */
namespace Application\Koe\Controller;
use System\Core\Storage;
use System\Utils\Util;
use Utils\Koe\FileCache;
use Utils\Koe\FileTool;
use Utils\Koe\KoeTool;
use Utils\Koe\Mcrypt;
use Utils\Koe\WebTool;

class UserController extends KoeController{

    /**
     * �û������Ϣ
     * @var array
     */
    private $user;
    /**
     * �û�������Ȩ��
     * @var array
     */
//    private $auth;
    /**
     * ����Ҫ�жϵ�action
     * @var array
     */
    private $notCheck = array(
        'loginFirst',
        'login',
        'logout',
        'loginSubmit',
        'checkCode',
        'public_link'
    );

    function __construct(){
        parent::__construct();
        if(!isset($_SESSION)){
            $this->login("session write error!");
        }else{
            $this->user = &$_SESSION['kod_user'];
        }
    }

    /**
     * ��ʾ��װ����
     */
    public function install(){
        $wall_page_url = URL_KOE_IMG_PATH."wall_page/".array_rand(array(4,5,7,7,7,10,11,12)).".jpg";// $arr[mt_rand(0,count($arr)-1)]
        $errors = $this->checkEnvironment();

        $this->assign('loginFirstUrl',Util::url('Koe/User/loginFirst'));
        $this->assign('wall_page_url',$wall_page_url);
        $this->assign('errors',$errors);
        $this->display('install.html');
    }
    /**
     * �״ε�½
     */
    public function loginFirst(){
        Storage::touch(USER_SYSTEM.'install.lock');
        Util::redirect(Util::url('Koe/User/login'));
    }
    /**
     * ��½view
     * @param string $msg
     */
    public function login($msg = ''){
        if (!Storage::hasFile(USER_SYSTEM.'install.lock')) {
            $this->install();
        }
        //���������������
        if(isset($_SESSION['code_error_time']) && intval($_SESSION['code_error_time']) >=3){
            //��֤�����ɵ�ַ
            $codeurl = Util::url('Koe/User/checkCode');
            $this->assign('codeurl',$codeurl);
        }else{
            $this->assign('codeurl','');
        }

        $wall_page_url = URL_KOE_IMG_PATH."wall_page/".array_rand(array(4,5,7,7,7,10,11,12)).".jpg";// $arr[mt_rand(0,count($arr)-1)]
        $this->assign('wall_page_url',$wall_page_url);
        $this->assign('msg',$msg);
        $this->display('login.php');
    }



    /**
     * ��ȡ���л�����������
     * @return array
     */
    private function checkEnvironment(){
        $error = array();
        if(!function_exists('iconv')) $error[] = 'Can not use iconv';
        if(!function_exists('mb_convert_encoding')) $error[] = 'Can not use mb_string';
        if(!version_compare(PHP_VERSION,'5.4','>=')) $error[] = 'PHP version must more than 5.4';
        if(!function_exists('file_get_contents')) $error.= 'Can not use file_get_contents';
        if(!path_writable(BASIC_PATH)) $error[] = 'base path is not writable';
        if(!path_writable(BASIC_PATH.'data')) $error[] = 'data can not write';
        if(!path_writable(BASIC_PATH.'data/system')) $error[] = 'data/system can not write';
        if(!path_writable(BASIC_PATH.'data/User')) $error[] = 'data/User can not write';
        if(!path_writable(BASIC_PATH.'data/thumb')) $error[] = 'data/thumb can not write';
        if( !function_exists('imagecreatefromjpeg')||
            !function_exists('imagecreatefromgif')||
            !function_exists('imagecreatefrompng')||
            !function_exists('imagecolorallocate')){
            $error[] = 'Can not use php GD';
        }
        return $error;
    }
    /**
     * ��½״����;����ʼ������״�W
     * @return void
     */
    public function loginCheck(){
        defined('ST') or die('ST not defined!');
        defined('ACT') or die('ACT not defined!');
        if (ST == 'share') return ;//����ҳ��
        if(in_array(ACT,$this->notCheck)){
            //����Ҫ�жϵ�action
        }else if($_SESSION['kod_login']===true && $_SESSION['kod_user']['name']!=''){
            define('USER',USER_PATH.$this->user['name'].'/');
            define('USER_TEMP',USER.'data/temp/');
            define('USER_RECYCLE',USER.'recycle/');
            if (!file_exists(USER)) {
                $this->logout();
            }
            if ($this->user['role'] == 'root') {
                define('MYHOME',USER.'home/');
                define('HOME','');
                $GLOBALS['web_root'] = WEB_ROOT;//������Ŀ��
                $GLOBALS['is_root'] = 1;
            }else{
                define('MYHOME','/');
                define('HOME',USER.'home/');
                $GLOBALS['web_root'] = str_replace(WEB_ROOT,'',HOME);//�ӷ�������ʼ���û�Ŀ¼
                $GLOBALS['is_root'] = 0;
            }
            $this->config['user_share_file']   = USER.'data/share.php';    // �ղؼ��ļ���ŵ؈}.
            $this->config['user_fav_file']     = USER.'data/fav.php';    // �ղؼ��ļ���ŵ؈}.
            $this->config['user_seting_file']  = USER.'data/config.php'; //�û������ļ�
            $this->config['user']  = FileCache::load($this->config['user_seting_file']);
            if($this->config['user']['theme']==''){
                $this->config['user'] = $this->config['setting_default'];
            }
        }else if($_COOKIE['kod_name']!='' && $_COOKIE['kod_token']!=''){
            $member = new fileCache(USER_SYSTEM.'member.php');
            $user = $member->get($_COOKIE['kod_name']);
            if (!is_array($user) || !isset($user['password'])) {
                $this->logout();
            }
            if(md5($user['password'].WebTool::get_client_ip()) == $_COOKIE['kod_token']){
                session_start();//re start
                $_SESSION['kod_login'] = true;
                $_SESSION['kod_user']= $user;
                setcookie('kod_name', $_COOKIE['kod_name'], time()+3600*24*365);
                setcookie('kod_token',$_COOKIE['kod_token'],time()+3600*24*365); //�����MD5ֵ�ٴ�md5
                header('location:'.KoeTool::get_url());
                exit;
            }
            $this->logout();//session user���ݲ���]
        }else{
            if ($this->config['setting_system']['auto_login'] != '1') {
                $this->logout();//���Զ��Ǐ�
            }else{
                if (!file_exists(USER_SYSTEM.'install.lock')) {
                    $this->display('install.html');exit;
                }
                header('location:./index.php?user/loginSubmit&name=guest&password=guest');
                exit;
            }
        }
    }
    /**
     * Ȩ����֤��ͳد��ڗ��j
     */
    public function authCheck(){
        defined('ST') or die('ST not defined!');
        defined('ACT') or die('ACT not defined!');
        if (isset($GLOBALS['is_root']) && $GLOBALS['is_root'] == 1) return;
        if (in_array(ACT,$this->notCheck)) return;
        if (!array_key_exists(ST,$this->config['role_setting']) ) return;
        if (!in_array(ACT,$this->config['role_setting'][ST]) &&
            ST.':'.ACT != 'user:common_js') return;//����������Ȩ��

        //��Ȩ�����Ƶĺ���
        $key = ST.':'.ACT;
        $group  = new fileCache(USER_SYSTEM.'group.php');
        $auth= $group->get($this->user['role']);


        //���°汾���ݴ���
        //δ���壻�°汾�״�ʹ��Ĭ�Ͽ��ŵĹ���
        if(!isset($auth['userShare:set'])){
            $auth['userShare:set'] = 1;
        }
        if(!isset($auth['explorer:fileDownload'])){
            $auth['explorer:fileDownload'] = 1;
        }
        //Ĭ����չ���� �ȼ�Ȩ��
        $auth['user:common_js'] = 1;//Ȩ���������ú������ǰ��
        $auth['explorer:pathChmod']         = $auth['explorer:pathRname'];
        $auth['explorer:pathDeleteRecycle'] = $auth['explorer:pathDelete'];
        $auth['explorer:pathCopyDrag']      = $auth['explorer:pathCuteDrag'];

        $auth['explorer:fileDownloadRemove']= $auth['explorer:fileDownload'];
        $auth['explorer:zipDownload']       = $auth['explorer:fileDownload'];
        $auth['explorer:fileProxy']         = $auth['explorer:fileDownload'];
        $auth['editor:fileGet']             = $auth['explorer:fileDownload'];
        $auth['explorer:makeFileProxy']     = $auth['explorer:fileDownload'];
        $auth['userShare:del']              = $auth['userShare:set'];
        if ($auth[$key] != 1) KoeTool::show_json($this->L['no_permission'],false);

        $GLOBALS['auth'] = $auth;//ȫ��
        //��չ�����ƣ��½��ļ�&�ϴ��ļ�&�������ķ�&�����ļ�&zip��ѹ�ļ�
        $check_arr = array(
            'mkfile'    =>  $this->check_key('path'),
            'pathRname' =>  $this->check_key('rname_to'),
            'fileUpload'=>  isset($_FILES['file']['name'])?$_FILES['file']['name']:'',
            'fileSave'  =>  $this->check_key('path')
        );
        if (array_key_exists(ACT,$check_arr) && !KoeTool::checkExt($check_arr[ACT])){
            KoeTool::show_json($this->L['no_permission_ext'],false);
        }
    }
    //��ʱ�ļ�����
    public function public_link(){
        KoeTool::load_class('mcrypt');
        $pass = $this->config['setting_system']['system_password'];
        $path = Mcrypt::decode($this->in['fid'],$pass);
        if (strlen($path) == 0) {
            KoeTool::show_json($this->L['error'],false);
        }
        FileTool::file_put_out($path);
    }
    public function common_js(){
        $basic_path = BASIC_PATH;
        if (!$GLOBALS['is_root']) {
            $basic_path = '/';//�Է�root�û����س��е؈}
        }
        $the_config = array(
            'lang'          => LANGUAGE_TYPE,
            'is_root'       => $GLOBALS['is_root'],
            'user_name'     => $this->user['name'],
            'web_root'      => $GLOBALS['web_root'],
            'web_host'      => HOST,
            'static_path'   => STATIC_PATH,
            'basic_path'    => $basic_path,
            'version'       => KOD_VERSION,
            'app_host'      => APPHOST,
            'office_server' => OFFICE_SERVER,
            'myhome'        => MYHOME,
            'upload_max'    => FileTool::get_post_max(),
            'json_data'     => "",

            'theme'         => $this->config['user']['theme'], //�б��������յ��ֵ�
            'list_type'     => $this->config['user']['list_type'], //�б��������յ��ֵ�
            'sort_field'    => $this->config['user']['list_sort_field'], //�б��������յ��ֵ�
            'sort_order'    => $this->config['user']['list_sort_order'], //�б���������or����
            'musictheme'    => $this->config['user']['musictheme'],
            'movietheme'    => $this->config['user']['movietheme']
        );

        $js  = 'LNG='.json_encode($GLOBALS['L']).';';
        $js .= 'AUTH='.json_encode($GLOBALS['auth']).';';
        $js .= 'G='.json_encode($the_config).';';
        header("Content-Type:application/javascript");
        echo $js;
    }



    /**
     * �T�����q
     */
    public function logout(){
        session_start();
        setcookie('kod_name', '', time()-3600);
        setcookie('kod_token', '', time()-3600);
        setcookie('kod_user_language', '', time()-3600);
        session_destroy();
        header('location:./index.php?user/login');
        exit;
    }

    /**
     * ��½�����ύ����
     */
    public function loginSubmit(){
        if(!isset($this->in['name']) || !isset($this->in['password'])) {
            $msg = $this->L['login_not_null'];
        }else{
            //��������������֤�m
            $name = rawurldecode($this->in['name']);
            $password = rawurldecode($this->in['password']);

            session_start();//re start ���µ��޸ĺ����
            if(isset($_SESSION['code_error_time'])  &&
                intval($_SESSION['code_error_time']) >=3 &&
                $_SESSION['check_code'] !== strtolower($this->in['check_code'])){
                // pr($_SESSION['check_code'].'--'.strtolower($this->in['check_code']));exit;
                $this->login($this->L['code_error']);
            }
            $member = new fileCache(USER_SYSTEM.'member.php');
            $user = $member->get($name);
            if ($user ===false){
                $msg = $this->L['user_not_exists'];
            }else if(md5($password)==$user['password']){
                if($user['status'] == 0){//��ʼ��app
                    $app = new AppController();
                    $app->init_app($user);
                }
                $_SESSION['kod_login'] = true;
                $_SESSION['kod_user']= $user;
                setcookie('kod_name', $user['name'], time()+3600*24*365);
                if ($this->in['rember_password'] == '1') {
                    setcookie('kod_token',md5($user['password'].WebTool::get_client_ip()),time()+3600*24*365);
                }
                header('location:./index.php');
                return;
            }else{
                $msg = $this->L['password_error'];
            }
            $_SESSION['code_error_time'] = intval($_SESSION['code_error_time']) + 1;
        }
        $this->login($msg);
    }

    /**
     * �޸�����
     */
    public function changePassword(){
        $password_now=$this->in['password_now'];
        $password_new=$this->in['password_new'];
        if (!$password_now && !$password_new) KoeTool::show_json($this->L['password_not_null'],false);
        if ($this->user['password']==md5($password_now)){
            $sql=new fileCache(USER_SYSTEM.'member.php');
            $this->user['password'] = md5($password_new);
            $sql->update($this->user['name'],$this->user);
            setcookie('kod_token',md5(md5($password_new)),time()+3600*24*365);
            KoeTool::show_json('success');
        }else {
            KoeTool::show_json($this->L['old_password_error'],false);
        }
    }


    private function check_key($key){
        return isset($this->in[$key])? rawurldecode($this->in[$key]):'';
    }

    public function checkCode() {
        session_start();//re start
        $code = KoeTool::randString(4);
        $_SESSION['check_code'] = strtolower($code);
        KoeTool::check_code($code);
    }
}