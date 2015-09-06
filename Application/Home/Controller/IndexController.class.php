<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/15
 * Time: 18:44
 */
namespace Application\Home\Controller;
use System\Core\Controller;
use System\Utils\Util;

class IndexController extends Controller{

    private $model = null;

    public function index(){

//        Util::status('database_begin');
//        $this->model = new TestDaoModel();
//        $rst = $this->model->test();
//        Util::status('database_end');

//        $rst = Log::write('hello log');
//        $rst = Log::read('2015-08-25','Debug');
//        $rst = Log::getLogs();
//        Util::dump($rst);
//        Util::status('calculate_+_begin');
//        $str = '';
//        for($i = 0 ;$i<1000000; $i++){
//            $str .= 'a';
//        };
//        Util::status('calculate_+_end');
//        $count = 1;
//        Util::dump(str_replace('ab','XX','jab_ab',$count));
        echo __METHOD__.'<br />';

//        isset($_SERVER['PATH_INFO']) and Util::dump($_SERVER['PATH_INFO']);
//        $url = URLHelper::create(
//            array('Home','User'),
//            'Index',
//            'index',
//            array('a'=>1,'b'=>2)
//        );
//        $url = URLHelper::create(
//            null,null,'create',array()
//        );
//        $url = URLHelper::create();
        $url = Util::url('Admin/User/Name/look',array('asd'=>123));
        echo "<a href='{$url}'>Go to Admin/User/Name/look</a>";
echo <<<endline
<div id="myTime"> <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="200" height="80" id="honehoneclock" align="middle"> <param name="allowScriptAccess" value="always"> <param name="movie" value="http://chabudai.sakura.ne.jp/blogparts/honehoneclock/honehone_clock_wh.swf"> <param name="quality" value="high"> <param name="bgcolor" value="#ffffff"> <param name="wmode" value="transparent"> <embed wmode="transparent" src="http://chabudai.sakura.ne.jp/blogparts/honehoneclock/honehone_clock_wh.swf" quality="high" bgcolor="#ffffff" width="200" height="80" name="honehoneclock" align="middle" allowscriptaccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"> </object> </div>
endline;


        $this->display('index');
//        trigger_error('发生了错误,- -#，这个是错误信息！ ',E_USER_ERROR);

    }




}