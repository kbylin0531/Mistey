<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/12
 * Time: 14:53
 */
namespace Application\Koe\Controller;
use System\Utils\SessionUtil;
use System\Utils\Util;

/**
 * ����ֱ��ʹ��
 * Class IndexController
 * @package Application\Koe\Controller
 */
class IndexController extends KoeController{

    /**
     * KOE��ڲ���
     */
    public function index(){
//        Util::dump($_SESSION);
        session_start();
//        Util::dump(
//            ini_get('session.cookie_lifetime'),
//        ini_get('session.cookie_path'),
//        ini_get('session.cookie_domain'),
//        ini_get('session.cookie_secure'),
//        ini_get('session.cookie_httponly'),
//        session_get_cookie_params()
//        );


        Util::dump(
            SessionUtil::savePath()
        );


    }
//    public function index(){
//        include (SYSTEM_PATH.'Projects/KodExplorer/config/config.php');
//        $app = new \Application();
//        init_lang();
//        init_setting();
//        $app->run();
//    }
}