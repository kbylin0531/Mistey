<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/17
 * Time: 13:20
 */
namespace Application\Cms\Controller;
use System\Extension\Verify;
use Utils\Cms\CmsUtil;

/**
 * Class MemberController 后台用户中心及用户登录登出控制器
 * @package Application\Cms\Controller
 */
class MemberController extends AdminController{
    /**
     * 验证码类
     * @var Verify
     */
    private $verify = null;

    public function __construct(){
        defined('GO_MEMBER') or define('GO_MEMBER',true);
        parent::__construct();
    }

    /**
     * 用户登录界面和操作
     * @param string $username
     * @param string $password
     * @param string $verify
     * @return void
     */
    public function login($username=null,$password=null,$verify=null){
        if(IS_POST){
            isset($this->verify) or $this->verify = new Verify();
            if($this->checkVerify($verify)){
                $this->error('验证码错误!');
            }

            //TODO:登陆成功后设置UID常量
        }else{
            if(CmsUtil::getUid()){//已经登陆了
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