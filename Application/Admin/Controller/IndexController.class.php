<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/18
 * Time: 9:48
 */
namespace Application\Cms\Controller;

/**
 * Class IndexController 后台首页控制器
 * @package Application\Cms\Controller
 */
class IndexController extends AdminController{

    /**
     * 后台首页界面
     */
    public function index(){
        echo __METHOD__;
    }

}