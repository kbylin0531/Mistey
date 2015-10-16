<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/17
 * Time: 13:20
 */
namespace Application\Member\Controller;
use Application\Common\Controller\AdminController;

/**
 * Class MemberController 后台用户中心
 * @package Application\Cms\Controller
 */
class MemberController extends AdminController{

    public function __construct(){
        parent::__construct();
    }


    public function showList(){

        $this->display();
    }

    public function pageAddMember(){

        $this->display();
    }


    public function listMembers(){

    }

    public function create(){

    }
    public function remove(){}

    public function update(){}



}