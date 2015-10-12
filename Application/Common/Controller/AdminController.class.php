<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/10/11 0011
 * Time: 20:59
 */
namespace Application\Common\Controller;
use Application\Member\Controller\PublicController;
use Application\Member\Util\MemberKits;
use System\Core\Controller;

/**
 * Class AdminController CMS后台控制器基类
 * @package Application\Common\Controller
 */
class AdminController extends Controller {

    /**
     * 构造函数
     * 检查后台操作是否在登陆的情况下，否则将前往登录页面
     * @throws \Exception
     */
    public function __construct(){
        parent::__construct();
        if(false === MemberKits::checkLoginStatus()){
            if(!defined('GO_MEMBER_LOGIN')){//防止陷入死循环
                define('GO_MEMBER_LOGIN',true);
                $memberController = new PublicController();
                $memberController->login();
            }
        }
    }


}