<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/10/11 0011
 * Time: 19:45
 */
namespace Application\Member\Controller;
use Application\Common\Controller\AdminController;
use Application\Member\Util\MemberKits;
use System\Core\Storage;
use System\Extension\Crypt3Des;
use System\Extension\Verify;
use System\Util\RSA;

/**
 * Class PublicController 用户登录登出控制器
 * @package Application\Member\Controller
 */
class PublicController extends AdminController {
    /**
     * 验证码类
     * @var Verify
     */
    private $verify = null;

    private $rsa = null;

    public function __construct(){
        parent::__construct();
        defined('URL_ASSERTS_SAMPLE_PATH') or define('URL_ASSERTS_SAMPLE_PATH',URL_PUBLIC_PATH.'sample');
    }

    /**
     * 用户登录界面和操作
     * @param string $username
     * @param string $password
     * @return void
     */
    public function login($username=null,$password=null){
        if(IS_POST){
//            isset($this->verify) or $this->verify = new Verify();
//            if($this->checkVerify($verify)){
//                $this->error('验证码错误!');
//            }
            $this->check($username,$password);

//            $this->redirect('cms/Index/index');
            //TODO:登陆成功后设置UID常量
        }else{
            if(MemberKits::getUserId()){//已经登陆了
                $this->redirect('cms/Index/index');
            }

            //TODO:读取配置 并 缓存
            $this->display();
        }
    }
    /**
     * 用户登出界面和操作
     * @return void
     */
    public function logout(){



    }

    private function createRsaKeys(){

    }



    /**
     * 检查用户和密码是否允许登录
     * 此外还可以加上用户登录权限检查(是否被强制禁止登陆等)
     * @param $username
     * @param $password
     */
    public function check($username,$password){
        //密文传输
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
    function checkVerify($code, $id = APP_NAME){
        isset($this->verify) or $this->verify = new Verify();
        return $this->verify->check($code, $id);
    }
}