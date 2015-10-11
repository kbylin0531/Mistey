<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 18:37
 */
namespace Application\Koe\Controller;

class DesktopController extends KoeController{
    function __construct() {
        parent::__construct();
        $this->tpl = TEMPLATE.'desktop/';
    }
    public function index() {
        $wall = $this->config['user']['wall'];
        if(strlen($wall)>3){
            $this->assign('wall',$wall);
        }else{
            $this->assign('wall',STATIC_PATH.'images/wall_page/'.$wall.'.jpg');
        }
        defined('MYHOME') or die('MYHOME not defined!');
        if (!is_dir(MYHOME.'desktop/') && is_writable(MYHOME)) {
            mkdir(MYHOME.'desktop/');
        }
        $this->display('index.php');
    }
}