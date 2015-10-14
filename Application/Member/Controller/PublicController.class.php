<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/10/11 0011
 * Time: 19:45
 */
namespace Application\Member\Controller;
use Application\Member\Util\MemberKits;
use System\Core\Controller;
use System\Extension\Verify;

/**
 * Class PublicController 用户登录登出控制器
 * @package Application\Member\Controller
 */
class PublicController extends Controller {
    /**
     * 验证码类
     * @var Verify
     */
    private $verify = null;


    public function __construct(){
        parent::__construct();
        defined('URL_ASSERTS_SAMPLE_PATH') or define('URL_ASSERTS_SAMPLE_PATH',URL_PUBLIC_PATH.'sample');
    }

    /**
     * 用户登录界面和操作
     * @param string $username
     * @param string $password
     * @param string $verify
     * @return void
     */
    public function login($username=null,$password=null,$verify=null){
        $error = 0;
        if(IS_POST){
            if(empty($verify)){
                $error = 3;
            }else if(!$this->checkVerify($verify)){
                $error = 2;
            }else{
                if($this->check($username,$password)){
                    $this->redirect('admin/index/index');
                }
                //密码错误
                $error = 1;
            }
        }
        if(MemberKits::getUserId()){//已经登陆了
            $this->redirect('admin/index/index');
        }
        $this->assign('error',$error);
        $this->display('login');
    }
    /**
     * 用户登出界面和操作
     * @return void
     */
    public function logout(){

    }


    /**
     * 检查用户和密码是否允许登录
     * 此外还可以加上用户登录权限检查(是否被强制禁止登陆等)
     * @param $username
     * @param $password
     * @return bool
     */
    public function check($username,$password){
        return true;
    }
    /**
     * 生成验证码
     * @param string $id
     * @return void
     */
    public function createVerify($id = APP_NAME){
        isset($this->verify) or $this->verify = new Verify();
        $this->verify->entry($id);
    }

    /**
     * 检测验证码
     * @param string $code 验证码的值
     * @param  string $id 验证码ID
     * @return boolean     检测结果
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    private function checkVerify($code, $id = APP_NAME){
        isset($this->verify) or $this->verify = new Verify();
        return $this->verify->check($code, $id);
    }
}