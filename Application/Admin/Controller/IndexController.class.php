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
        //问题：在缺少sample的情况下会调用框架的方法进行寻找
        defined('URL_CMS_ADMIN_CSS_PATH') or define('URL_CMS_ADMIN_CSS_PATH',URL_PUBLIC_PATH.'sample/css');
        defined('URL_CMS_ADMIN_JS_PATH') or define('URL_CMS_ADMIN_JS_PATH',URL_PUBLIC_PATH.'sample/js');
        defined('URL_CMS_ADMIN_IMG_PATH') or define('URL_CMS_ADMIN_IMG_PATH',URL_PUBLIC_PATH.'sample/images');
    }

    /**
     * 后台首页界面
     */
    public function index(){
        $this->display();
    }

    public function top(){
        $this->display();
    }
    public function left(){
        $this->display();
    }
    public function main(){
        $this->display();
    }

}