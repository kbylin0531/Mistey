<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/18
 * Time: 9:48
 */
namespace Application\Admin\Controller;
use Application\Common\Controller\AdminController;

/**
 * Class IndexController 后台控制器
 * @package Application\Cms\Controller
 */
class IndexController extends AdminController{

    public function __construct(){
        parent::__construct();
        defined('URL_CMS_ADMIN_PATH') or define('URL_CMS_ADMIN_PATH',URL_PUBLIC_PATH.'cms/modules/admin/');
        defined('URL_CMS_ADMIN_CSS_PATH') or define('URL_CMS_ADMIN_CSS_PATH',URL_CMS_ADMIN_PATH.'css/');
        defined('URL_CMS_ADMIN_JS_PATH') or define('URL_CMS_ADMIN_JS_PATH',URL_CMS_ADMIN_PATH.'js/');
        defined('URL_CMS_ADMIN_IMG_PATH') or define('URL_CMS_ADMIN_IMG_PATH',URL_CMS_ADMIN_PATH.'images/');
    }

    /**
     * 后台首页界面
     */
    public function index(){
        echo __METHOD__;
    }

}