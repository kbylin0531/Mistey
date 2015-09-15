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
     * 用户相关信息
     * @var array
     */
    private $user;
    /**
     * 用户承属组权限
     * @var array
     */
//    private $auth;
    /**
     * 不需要判断的action
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
     * 显示安装界面
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
     * 首次登陆
     */
    public function loginFirst(){
        Storage::touch(USER_SYSTEM.'install.lock');
        Util::redirect(Util::url('Koe/User/login'));
    }
    /**
     * 登陆view
     * @param string $msg
     */
    public function login($msg = ''){
        if (!Storage::hasFile(USER_SYSTEM.'install.lock')) {
            $this->install();
        }
        //连续三次输入错误
        if(isset($_SESSION['code_error_time']) && intval($_SESSION['code_error_time']) >=3){
            //验证码生成地址
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
     * 获取运行环境棿测数捿
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
     * 登陆状濁检浿;并初始化数据状濿
     * @return void
     */
    public function loginCheck(){
        defined('ST') or die('ST not defined!');
        defined('ACT') or die('ACT not defined!');
        if (ST == 'share') return ;//共享页面
        if(in_array(ACT,$this->notCheck)){
            //不需要判断的action
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
                $GLOBALS['web_root'] = WEB_ROOT;//服务器目彿
                $GLOBALS['is_root'] = 1;
            }else{
                define('MYHOME','/');
                define('HOME',USER.'home/');
                $GLOBALS['web_root'] = str_replace(WEB_ROOT,'',HOME);//从服务器弿始到用户目录
                $GLOBALS['is_root'] = 0;
            }
            $this->config['user_share_file']   = USER.'data/share.php';    // 收藏夹文件存放地坿.
            $this->config['user_fav_file']     = USER.'data/fav.php';    // 收藏夹文件存放地坿.
            $this->config['user_seting_file']  = USER.'data/config.php'; //用户配置文件
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
                setcookie('kod_token',$_COOKIE['kod_token'],time()+3600*24*365); //密码的MD5值再次md5
                header('location:'.KoeTool::get_url());
                exit;
            }
            $this->logout();//session user数据不存圿
        }else{
            if ($this->config['setting_system']['auto_login'] != '1') {
                $this->logout();//不自动登彿
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
     * 权限验证；统丿入口棿骿
     */
    public function authCheck(){
        defined('ST') or die('ST not defined!');
        defined('ACT') or die('ACT not defined!');
        if (isset($GLOBALS['is_root']) && $GLOBALS['is_root'] == 1) return;
        if (in_array(ACT,$this->notCheck)) return;
        if (!array_key_exists(ST,$this->config['role_setting']) ) return;
        if (!in_array(ACT,$this->config['role_setting'][ST]) &&
            ST.':'.ACT != 'user:common_js') return;//输出处理过的权限

        //有权限限制的函数
        $key = ST.':'.ACT;
        $group  = new fileCache(USER_SYSTEM.'group.php');
        $auth= $group->get($this->user['role']);


        //向下版本兼容处理
        //未定义；新版本首次使用默认开放的功能
        if(!isset($auth['userShare:set'])){
            $auth['userShare:set'] = 1;
        }
        if(!isset($auth['explorer:fileDownload'])){
            $auth['explorer:fileDownload'] = 1;
        }
        //默认扩展功能 等价权限
        $auth['user:common_js'] = 1;//权限数据配置后输出到前端
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

        $GLOBALS['auth'] = $auth;//全局
        //扩展名限制：新建文件&上传文件&重命名文仿&保存文件&zip解压文件
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
    //临时文件访问
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
            $basic_path = '/';//对非root用户隐藏承有地坿
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

            'theme'         => $this->config['user']['theme'], //列表排序依照的字殿
            'list_type'     => $this->config['user']['list_type'], //列表排序依照的字殿
            'sort_field'    => $this->config['user']['list_sort_field'], //列表排序依照的字殿
            'sort_order'    => $this->config['user']['list_sort_order'], //列表排序升序or降序
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
     * 逿出处琿
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
     * 登陆数据提交处理
     */
    public function loginSubmit(){
        if(!isset($this->in['name']) || !isset($this->in['password'])) {
            $msg = $this->L['login_not_null'];
        }else{
            //错误三次输入验证砿
            $name = rawurldecode($this->in['name']);
            $password = rawurldecode($this->in['password']);

            session_start();//re start 有新的修改后调用
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
                if($user['status'] == 0){//初始化app
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
     * 修改密码
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