<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/18
 * Time: 9:27
 */
namespace Application\Cms\Controller;
use System\Core\Controller;
use Utils\Cms\CmsUtil;

/**
 * Class AdminController CMS控制器基类 (除了install控制器之外)
 * @package Application\Cms\Controller
 */
class AdminController extends Controller{

    public function __construct(){
        defined('URL_CMS_ADMIN_PATH') or define('URL_CMS_ADMIN_PATH',URL_PUBLIC_PATH.'CMS/modules/admin/');
        defined('URL_CMS_ADMIN_CSS_PATH') or define('URL_CMS_ADMIN_CSS_PATH',URL_CMS_ADMIN_PATH.'css/');
        defined('URL_CMS_ADMIN_JS_PATH') or define('URL_CMS_ADMIN_JS_PATH',URL_CMS_ADMIN_PATH.'js/');
        defined('URL_CMS_ADMIN_IMG_PATH') or define('URL_CMS_ADMIN_IMG_PATH',URL_CMS_ADMIN_PATH.'images/');
        //User ID 未定义时
        $this->initialize();
        parent::__construct();
    }

    /**
     * 初始化控制器
     * @return void
     */
    protected function initialize(){
        if(defined('UID')) return;
        $uid = CmsUtil::getUid();
        if(0 === $uid){//用户未登陆
            if(defined('GO_MEMBER') and GO_MEMBER){
                //直接前往login操作
            }else{
                $this->redirect('Cms/member/login');
            }
        }else{
            define('UID',$uid);
        }
    }


}