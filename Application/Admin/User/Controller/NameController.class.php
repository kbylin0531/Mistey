<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/15
 * Time: 18:47
 */
namespace Application\Admin\User\Controller;
use System\Core\Controller;
use System\Utils\Util;

class NameController extends Controller{

    public function look(){
        echo __METHOD__.'<br />';
        $url = Util::url('hey',array('k'=>'Thanks!'));
        echo "<a href='{$url}'>Go to Self Controller method [hey]</a>";

//        Util::status('smarty_begin');
//
//        $this->assign('lin','zhonghuang');
//        $this->assign(array(
//            'zhao'  => 'youtian',
//            'tang'  => 'yiguang',
//        ));
//        Util::status('smarty_midway');
//        $this->display('look.html');
//        Util::status('smarty_end');
//        $this->error('yeah!',3);
    }

    public function hey(){
        echo __METHOD__.'<br />';
        $url = Util::url('Home/Index/index',array('k'=>'Thanks!'));
        echo "<a href='{$url}'>Go to Home/Index/index</a>";
    }
}