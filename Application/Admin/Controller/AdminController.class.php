<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/18
 * Time: 9:27
 */
namespace Application\Cms\Controller;
use Application\Member\Controller\PublicController;
use Application\Member\Util\MemberKits;
use System\Core\Controller;

/**
 * Class AdminController CMS控制器基类 (除了install控制器之外)
 * @package Application\Cms\Controller
 */
class AdminController extends Controller{

    public function __construct(){
        parent::__construct();
        $this->initialize();
    }

    /**
     * 初始化控制器
     * @return void
     */
    protected function initialize(){
        defined('URL_CMS_ADMIN_PATH') or define('URL_CMS_ADMIN_PATH',URL_PUBLIC_PATH.'cms/modules/admin/');
        defined('URL_CMS_ADMIN_CSS_PATH') or define('URL_CMS_ADMIN_CSS_PATH',URL_CMS_ADMIN_PATH.'css/');
        defined('URL_CMS_ADMIN_JS_PATH') or define('URL_CMS_ADMIN_JS_PATH',URL_CMS_ADMIN_PATH.'js/');
        defined('URL_CMS_ADMIN_IMG_PATH') or define('URL_CMS_ADMIN_IMG_PATH',URL_CMS_ADMIN_PATH.'images/');
        if(false === $this->checkLoginStatus()){
            if(!defined('GO_MEMBER_LOGIN')){//防止陷入死循环
                define('GO_MEMBER_LOGIN',true);
                $memberController = new PublicController();
                $memberController->login();
            }
        }
    }

    /**
     * 检查登陆状态
     * @return bool
     */
    protected function checkLoginStatus(){
        //设置了UID，说明是其他地方new了一个控制器
        if(defined('UID')) return true;
        $uid = MemberKits::getUserId();
        if(0 === $uid){//用户需要重新登录
            return false;
        }else{
            define('UID',$uid);
            return true;
        }
    }


}