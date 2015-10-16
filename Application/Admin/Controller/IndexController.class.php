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
    }

    /**
     * 后台首页界面
     */
    public function index(){
        $this->display();
    }

    public function top(){
        //TODO:数据库获取用户允许访问的模块 分配到前端
        $modules = array(
            array(
                'href'      => '#',
                'imgsrc'    => URL_CMS_ADMIN_IMG_PATH.'/icon01.png',
                'title'     => '内容管理',
            ),
            array(
                'href'      => '#',
                'imgsrc'    => URL_CMS_ADMIN_IMG_PATH.'/icon02.png',
                'title'     => '系统设置',
            ),
            array(
                'href'      => '/Mist/admin/index/left.html',
                'imgsrc'    => URL_CMS_ADMIN_IMG_PATH.'/icon03.png',
                'title'     => '用户管理',
            ),
            array(
                'href'      => '',
                'imgsrc'    => URL_CMS_ADMIN_IMG_PATH.'/icon04.png',
                'title'     => '文件中心',
            ),
        );
        $this->assign('modules',$modules);
        $this->display();
    }
    public function left($mid=0){
        //TODO:根据不同的mid获取不同的子模块选项
        $submodules = array(
            array(
                'src'   => URL_CMS_ADMIN_IMG_PATH.'/leftico01.png',
                'name'  => '管理信息',
                'items' => array(
                    '用户列表'  => '/Mist/member/member/showMemberList.html',
                    'sina'  => '/Mist/member/public/login.html',
                ),
            ),
            array(
                'src'   => URL_CMS_ADMIN_IMG_PATH.'/leftico02.png',
                'name'  => '其他设置',
                'items' => array(
                    'ifeng'  => 'http://www.ifeng.com',
                ),
            ),
        );
        $this->assign('title','用户管理');
        $this->assign('submodules',$submodules);
        $this->display();
    }

    /**
     * 显示系统信息和运行状态
     */
    public function main(){
        $this->display();
    }

    public function listMessage($username){
        //TODO:检查session与cookie
        //TODO:数据库查询离线消息
        //TODO:返回数据
    }


}