<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/15
 * Time: 18:44
 */
namespace Application\Home\Controller;
use Application\Home\Model\IndexModel;
use System\Core\Controller;
use System\Core\Storage;
use System\Util\SEK;
use System\Utils\Util;

class IndexController extends Controller{

    private $model = null;

    public function index(){
        //测试读取文件夹


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
//        echo __METHOD__.'<br />';

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
//        $url = Util::url('Admin/User/Name/look',array('asd'=>123));
//        echo "<a href='{$url}'>Go to Admin/User/Name/look</a>";
//echo <<<endline
//<div id="myTime"> <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="200" height="80" id="honehoneclock" align="middle"> <param name="allowScriptAccess" value="always"> <param name="movie" value="http://chabudai.sakura.ne.jp/blogparts/honehoneclock/honehone_clock_wh.swf"> <param name="quality" value="high"> <param name="bgcolor" value="#ffffff"> <param name="wmode" value="transparent"> <embed wmode="transparent" src="http://chabudai.sakura.ne.jp/blogparts/honehoneclock/honehone_clock_wh.swf" quality="high" bgcolor="#ffffff" width="200" height="80" name="honehoneclock" align="middle" allowscriptaccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"> </object> </div>
//endline;


        $this->display('index');
//        trigger_error('发生了错误,- -#，这个是错误信息！ ',E_USER_ERROR);

    }

    public function testStorageFile(){
        $files = Storage::readFolder('D:\旧的文件\My Documents');
        SEK::dump(count($files),$files);
//        测试读取文件
//        $content = Storage::read('D:/旧的文件/驱动程序/Realtek_LAN_V5792_V6250_V752_XPVistaWin7/Realtek/XP/SChinese.ini','UCS-2','UTF-8');
//        SEK::dump($content);
//        测试写入
//        $filename = 'D:/旧的文件/A测试.php';
//      $rst = Storage::write($filename,'hello 世界2！');
//        SEK::dump($rst,Storage::read($filename));
//
//        测试追加写入
//        $filename = 'D:/旧的文件/A测试.php';
//        $rst = Storage::append($filename,'AAA这是一段中文!','GBK');
//        $content = Storage::read($filename,'UTF-8');//'GBK' 和 'UTF-8'读取到不一样的显示
//        SEK::dump($rst,$content);
//
//        $rst = Storage::removeFolder('D:\旧的文件\驱动程序');//无法删除
//        SEK::dump($rst);
//        $rst = Storage::removeFolder('D:\旧的文件\驱动程序',true);
//        SEK::dump($rst);

        $rst = Storage::makeFolder('D:\旧的文件\驱动程序',0755);
        SEK::dump($rst);
    }


    public function testModel(){
        $model = new IndexModel();
        $model->index();
    }


}