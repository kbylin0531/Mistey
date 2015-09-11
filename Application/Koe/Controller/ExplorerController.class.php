<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 18:42
 */
namespace Application\Koe\Controller;

use Utils\Koe\CreatMiniature;
use Utils\Koe\FileCache;
use Utils\Koe\FileTool;
use Utils\Koe\History;
use Utils\Koe\KoeTool;
use Utils\Koe\Mcrypt;
use Utils\Koe\PclZip;
use Utils\Koe\WebTool;

class ExplorerController extends KoeController{
    public $path;
    public function __construct(){
        parent::__construct();
        $this->tpl = TEMPLATE.'explorer/';
        if (isset($this->in['path'])) {
            $this->path = KoeTool::_DIR($this->in['path']);
        }
    }
    public function index(){
        if(isset($this->in['path']) && $this->in['path'] !=''){
            $dir = KoeTool::_DIR_CLEAR($_GET['path']);
        }else if(isset($_SESSION['this_path'])){
            $dir = KoeTool::_DIR_CLEAR($_SESSION['this_path']);
        }else{
            $dir = '/';//首次进入系统,不带参数
            if ($GLOBALS['is_root']) $dir = WEB_ROOT;
        }
        $dir = rtrim($dir,'/').'/';
        $this->assign('dir',$dir);
        $this->display('index.php');
    }

    public function pathInfo(){
        $info_list = json_decode($this->in['list'],true);
        foreach ($info_list as &$val) {
            $val['path'] = KoeTool::_DIR($val['path']);
        }
        $data = FileTool::path_info_muti($info_list,$this->L['time_type_info']);
        KoeTool::_DIR_OUT($data['path']);
        KoeTool::show_json($data);
    }

    public function pathChmod(){
        $info_list = json_decode($this->in['list'],true);
        $mod = octdec('0'.$this->in['mod']);
        $success=0;$error=0;
        foreach ($info_list as $val) {
            $path = KoeTool::_DIR($val['path']);
            if(FileTool::chmod_path($path,$mod)){
                $success++;
            }else{
                $error++;
            }
        }
        $state = $error==0?true:false;
        $info = $success.' success,'.$error.' error';
        if (count($info_list) == 1 && $error==0) {
            $info = $this->L['success'];
        }
        KoeTool::show_json($info,$state);
    }

    private function _pathAllow($path){
        $name = FileTool::get_path_this($path);
        $path_not_allow  = array('*','?','"','<','>','|');
        foreach ($path_not_allow as $tip) {
            if (strstr($name,$tip)) {
                KoeTool::show_json($this->L['path_not_allow']."*,?,<,>,|",false);
            }
        }
    }
    public function pathRname(){
        if (!is_writable($this->path)) {
            KoeTool::show_json($this->L['no_permission_write_all'],false);
        }
        $rname_to=KoeTool::_DIR($this->in['rname_to']);
        $this->_pathAllow($rname_to);
        if (file_exists($rname_to)) {
            KoeTool::show_json($this->L['name_isexists'],false);
        }
        rename($this->path,$rname_to);
        KoeTool::show_json($this->L['rname_success']);
    }
    public function pathList(){
        KoeTool::load_class('history');
        session_start();//re start
        $session=isset($_SESSION['history'])?$_SESSION['history']:false;
        $user_path = $this->in['path'];
        if (is_array($session)){
            $hi=new History($session);
            if ($user_path==""){
                $user_path=$hi->getFirst();
            }else {
                $hi->add($user_path);
                $_SESSION['history']=$hi->getHistory();
            }
        }else {
            $hi=new history(array(),20);
            if ($user_path=="")  $user_path='/';
            $hi->add($user_path);
            $_SESSION['history']=$hi->getHistory();
        }

        //回收站不记录前进后退
        if (isset($this->in['type'])){
            if($this->in['path'] != '*recycle*/' && $this->in['type'] !=='desktop'){
                $_SESSION['this_path']=$user_path;
            }
        }

        $list=$this->path($this->path);
        $list['history_status']= array('back'=>$hi->isback(),'next'=>$hi->isnext());
        KoeTool::show_json($list);
    }
    public function search(){
        if (!isset($this->in['search'])) KoeTool::show_json($this->L['please_inpute_search_words'],false);
        $is_content = false;
        $is_case    = false;
        $ext        = '';
        if (isset($this->in['is_content'])) $is_content = true;
        if (isset($this->in['is_case'])) $is_case = true;
        if (isset($this->in['ext'])) $ext= str_replace(' ','',$this->in['ext']);
        $list = FileTool::path_search(
            $this->path,
            FileTool::iconv_system($this->in['search']),
            $is_content,$ext,$is_case);
        KoeTool::_DIR_OUT($list);
        KoeTool::show_json($list);
    }
    public function treeList(){//树结构
        $app = $this->in['app'];//是否获取文件 传folder|file
        if (isset($this->in['type']) && $this->in['type']=='init'){
            $this->_tree_init($app);
        }
        if (isset($this->in['this_path'])){
            $path=KoeTool::_DIR($this->in['this_path']);
        }else{
            $path=KoeTool::_DIR($this->in['path'].$this->in['name']);
        }
        if (!is_readable($path)) KoeTool::show_json($path,false);
        $list_file = ($app == 'editor'?true:false);//编辑器内列出文件
        $list=$this->path($path,$list_file,true);
        function sort_by_key($a, $b){
            if ($a['name'] == $b['name']) return 0;
            return ($a['name'] > $b['name']) ? 1 : -1;
        }
        usort($list['folderlist'], "sort_by_key");
        usort($list['filelist'], "sort_by_key");
        if ($app == 'editor') {
            $res = array_merge($list['folderlist'],$list['filelist']);
            KoeTool::show_json($res,true);
        }else{
            KoeTool::show_json($list['folderlist'],true);
        }
    }
    private function _tree_init($app){
        if ($app == 'editor' && isset($this->in['project'])) {
            $list_project = $this->path(KoeTool::_DIR($this->in['project']),true,true);
            $project = array_merge($list_project['folderlist'],$list_project['filelist']);
            $tree_data = array(
                array('name'=> FileTool::get_path_this($this->in['project']),
                    'children'=>$project,
                    'iconSkin'  => "my",
                    'menuType'  => "menuTreeRoot",
                    'open'      => true,
                    'this_path' => $this->in['project'],
                    'isParent'  => count($project)>0?true:false)
            );
            KoeTool::show_json($tree_data);
            return;
        }

        $check_file = ($app == 'editor'?true:false);
        $favData=new FileCache($this->config['user_fav_file']);
        $fav_list = $favData->get();
        $fav = array();
        foreach($fav_list as $key => $val){
            $fav[] = array(
                'name'      => $val['name'],
                'this_path' => $val['path'],
                'iconSkin'  => "fav",
                'menuType'  => "menuTreeFav",
                'type'      => 'folder',
                'isParent'  => FileTool::path_haschildren(KoeTool::_DIR($val['path']),$check_file)
            );
        }

        defined('MYHOME') or die('MYHOME not defined!');
        $list_root  = $this->path(KoeTool::_DIR(MYHOME),$check_file,true);
        $list_public = $this->path(PUBLIC_PATH,$check_file,true);
        if ($check_file) {//编辑器
            $root = array_merge($list_root['folderlist'],$list_root['filelist']);
            $public = array_merge($list_public['folderlist'],$list_public['filelist']);
        }else{//文件管理器
            $root  = $list_root['folderlist'];
            $public = $list_public['folderlist'];
        }

        $root_isparent = count($root)>0?true:false;
        $public_isparent = count($public)>0?true:false;
        $tree_data = array(
            array('name'=>$this->L['fav'],'iconSkin'=>"fav",
                'menuType'  => "menuTreeFavRoot",'open'=>true,'children'=>$fav),
            array('name'=>$this->L['root_path'],'children'=>$root,'menuType'=>"menuTreeRoot",
                'iconSkin'=>"my",'open'=>true,'this_path'=> MYHOME,'isParent'=>$root_isparent),
            array('name'=>$this->L['public_path'],'children'=>$public,'menuType'=>"menuTreeRoot",
                'iconSkin'=>"lib",'open'=>true,'this_path'=> '*public*','isParent'=>$public_isparent)
        );
        KoeTool::show_json($tree_data);
    }

    public function historyBack(){
        KoeTool::load_class('history');
        session_start();//re start
        $session=$_SESSION['history'];
        if (is_array($session)){
            $hi=new history($session);
            $path=$hi->goback();
            $_SESSION['history']=$hi->getHistory();
            $folderlist=$this->path(KoeTool::_DIR($path));
            $_SESSION['this_path']=$path;
            KoeTool::show_json(array(
                'history_status'=>array('back'=>$hi->isback(),'next'=>$hi->isnext()),
                'thispath'=>$path,
                'list'=>$folderlist
            ));
        }
    }
    public function historyNext(){
        KoeTool::load_class('history');
        session_start();//re start
        $session=$_SESSION['history'];
        if (is_array($session)){
            $hi=new history($session);
            $path=$hi->gonext();
            $_SESSION['history']=$hi->getHistory();
            $folderlist=$this->path(KoeTool::_DIR($path));
            $_SESSION['this_path']=$path;
            KoeTool::show_json(array(
                'history_status'=>array('back'=>$hi->isback(),'next'=>$hi->isnext()),
                'thispath'=>$path,
                'list'=>$folderlist
            ));
        }
    }
    public function pathDelete(){
        $list = json_decode($this->in['list'],true);
        defined('USER_RECYCLE') or die('USER_RECYCLE not defined!');
        if (!is_writable(USER_RECYCLE)) KoeTool::show_json($this->L['no_permission_write'],false);
        $success=0;$error=0;
        foreach ($list as $val) {
            $path_this = KoeTool::_DIR($val['path']);
            $filename  = FileTool::get_path_this($path_this);
            $filename = FileTool::get_filename_auto(USER_RECYCLE.$filename,date(' h.i.s'));//已存在处理 创建副本
            if (@rename($path_this,$filename)) {
                $success++;
            }else{
                $error++;
            }
        }
        $state = $error==0?true:false;
        $info = $success.' success,'.$error.' error';
        if ($error==0) {
            $info = $this->L['remove_success'];
        }
        KoeTool::show_json($info,$state);
    }
    public function pathDeleteRecycle(){
        defined('USER_RECYCLE') or die('USER_RECYCLE not defined!');
        if(!isset($this->in['list'])){
            if (!FileTool::del_dir(USER_RECYCLE)) {
                KoeTool::show_json($this->L['remove_fali'],false);
            }else{
                mkdir(USER_RECYCLE);
                KoeTool::show_json($this->L['recycle_clear_success'],true);
            }
            return;
        }
        $list = json_decode($this->in['list'],true);
        $success = 0;$error   = 0;
        foreach ($list as $val) {
            $path_full = KoeTool::_DIR($val['path']);
            if ($val['type'] == 'folder') {
                if(FileTool::del_dir($path_full)) $success ++;
                else $error++;
            }else{
                if(FileTool::del_file($path_full)) $success++;
                else $error++;
            }
        }
        if (count($list) == 1) {
            if ($success) KoeTool::show_json($this->L['remove_success']);
            else KoeTool::show_json($this->L['remove_fali'],false);
        }else{
            $code = $error==0?true:false;
            KoeTool::show_json($this->L['remove_success'].$success.'success,'.$error.'error',$code);
        }
    }

    public function mkfile(){
        $new= rtrim($this->path,'/');
        $this->_pathAllow($new);
        if(touch($new)){
            if (isset($this->in['content'])) {
                file_put_contents($new,$this->in['content']);
            }
            KoeTool::show_json($this->L['create_success'],true,FileTool::get_path_this($new));
        }else{
            KoeTool::show_json($this->L['create_error'],false);
        }
    }
    public function mkdir(){
        $new = rtrim($this->path,'/');
        $this->_pathAllow($new);
        if(mkdir($new,0777)){
            KoeTool::show_json($this->L['create_success']);
        }else{
            KoeTool::show_json($this->L['create_error'],false);
        }
    }
    public function pathCopy(){
        session_start();//re start
        $copy_list = json_decode($this->in['list'],true);
//        $list_num = count($copy_list);
//        for ($i=0; $i < $list_num; $i++) {
//            $copy_list[$i]['path'] =$copy_list[$i]['path'];
//        }
        $_SESSION['path_copy']= json_encode($copy_list);
        $_SESSION['path_copy_type']='copy';
        KoeTool::show_json($this->L['copy_success']);
    }
    public function pathCute(){
        session_start();//re start
        $cute_list = json_decode($this->in['list'],true);
//        $list_num = count($cute_list);
//        for ($i=0; $i < $list_num; $i++) {
//            $cute_list[$i]['path'] = $cute_list[$i]['path'];
//        }
        $_SESSION['path_copy']= json_encode($cute_list);
        $_SESSION['path_copy_type']='cute';
        KoeTool::show_json($this->L['cute_success']);
    }
    public function pathCuteDrag(){
        $clipboard = json_decode($this->in['list'],true);
        $path_past=$this->path;
        if (!is_writable($this->path)) KoeTool::show_json($this->L['no_permission_write'],false);
        $success=0;$error=0;
        foreach ($clipboard as $val) {
            $path_copy = KoeTool::_DIR($val['path']);
            $filename  = FileTool::get_path_this($path_copy);
            $filename = FileTool::get_filename_auto($path_past.$filename);//已存在处理 创建副本
            if (@rename($path_copy,$filename)) {
                $success++;
            }else{
                $error++;
            }
        }
        $state = $error==0?true:false;
        $info = $success.' success,'.$error.' error';
        if (count($clipboard) == 1 && $error==0) {
            $info = $this->L['success'];
        }
        KoeTool::show_json($info,$state);
    }

    public function pathCopyDrag(){
        $clipboard = json_decode($this->in['list'],true);
        $path_past=$this->path;
        $data = array();
        if (!is_writable($this->path)) KoeTool::show_json($this->L['no_permission_write'],false);
        foreach ($clipboard as $val) {
            $path_copy = KoeTool::_DIR($val['path']);
            $filename = FileTool::get_path_this($path_copy);
            $path = FileTool::get_filename_auto($path_past.$filename);
            FileTool::copy_dir($path_copy,$path);
            $data[] = FileTool::iconv_app(FileTool::get_path_this($path));
        }
        KoeTool::show_json($data,true);
    }

    public function clipboard(){
        $clipboard = json_decode($_SESSION['path_copy'],true);
        $msg = '';
        if (count($clipboard) == 0){
            $msg = '<div style="padding:20px;">null!</div>';
        }else{
            $msg='<div style="height:200px;overflow:auto;padding:10px;width:400px"><b>'.$this->L['clipboard_state']
                .($_SESSION['path_copy_type']=='cute'?$this->L['cute']:$this->L['copy']).'</b><br/>';
            $len = 40;
            foreach ($clipboard as $val) {
                $val['path'] = rawurldecode($val['path']);
                $path=(strlen($val['path'])<$len)?$val['path']:'...'.substr($val['path'],-$len);
                $msg.= '<br/>'.$val['type'].' :  '.$path;
            }
            $msg.="</div>";
        }
        KoeTool::show_json($msg);
    }
    public function pathPast(){
        if (!isset($_SESSION['path_copy'])){
            KoeTool::show_json($this->L['clipboard_null'],false,array());
        }

        session_start();//re start
        $error = '';$data = array();
        $clipboard = json_decode($_SESSION['path_copy'],true);
        $copy_type = $_SESSION['path_copy_type'];
        $path_past=$this->path;
        if (!is_writable($path_past)) KoeTool::show_json($this->L['no_permission_write'],false,$data);

        $list_num = count($clipboard);
        if ($list_num == 0) {
            KoeTool::show_json($this->L['clipboard_null'],false,$data);
        }
        for ($i=0; $i < $list_num; $i++) {
            $path_copy = KoeTool::_DIR($clipboard[$i]['path']);
            $filename  = FileTool::get_path_this($path_copy);
            $filename_out  = FileTool::iconv_app($filename);

            if (!file_exists($path_copy) && !is_dir($path_copy)){
                $error .=$path_copy."<li>{$filename_out}'.$this->L['copy_not_exists'].'</li>";
                continue;
            }
            if ($clipboard[$i]['type'] == 'folder'){
                if ($path_copy == substr($path_past,0,strlen($path_copy))){
                    $error .="<li style='color:#f33;'>{$filename_out}'.$this->L['current_has_parent'].'</li>";
                    continue;
                }
            }

            $auto_path = FileTool::get_filename_auto($path_past.$filename);
            $filename = FileTool::get_path_this($auto_path);
            if ($copy_type == 'copy') {
                if ($clipboard[$i]['type'] == 'folder') {
                    FileTool::copy_dir($path_copy,$auto_path);
                }else{
                    copy($path_copy,$auto_path);
                }
            }else{
                rename($path_copy,$auto_path);
            }
            $data[] = FileTool::iconv_app($filename);
        }
        if ($copy_type == 'copy') {
            $msg=$this->L['past_success'].$error;
        }else{
            $_SESSION['path_copy'] = json_encode(array());
            $_SESSION['path_copy_type'] = '';
            $msg=$this->L['cute_past_success'].$error;
        }
        $state = ($error ==''?true:false);
        KoeTool::show_json($msg,$state,$data);
    }
    public function fileDownload(){
        FileTool::file_put_out($this->path,true);
    }
    //文件下载后删除,用于文件夹下载
    public function fileDownloadRemove(){
        defined('USER_TEMP') or die('USER_TEMP not defined!');
        $path = rawurldecode(KoeTool::_DIR_CLEAR($this->in['path']));
        $path = USER_TEMP.FileTool::iconv_system($path);
        FileTool::file_put_out($path,true);
        FileTool::del_file($path);
    }
    public function zipDownload(){
        defined('USER_TEMP') or die('USER_TEMP not defined!');
        if(!file_exists(USER_TEMP)){
            mkdir(USER_TEMP);
        }else{//清除未删除的临时文件，一天前
            $list = FileTool::path_list(USER_TEMP,true,false);
            $max_time = 3600*24;
            if ($list['filelist']>=1) {
                for ($i=0; $i < count($list['filelist']); $i++) {
                    $create_time = $list['filelist'][$i]['mtime'];//最后修改时间
                    if(time() - $create_time >$max_time){
                        FileTool::del_file($list['filelist'][$i]['path'].$list['filelist'][$i]['name']);
                    }
                }
            }
        }
        $zip_file = $this->zip(USER_TEMP);
        KoeTool::show_json($this->L['zip_success'],true,FileTool::get_path_this($zip_file));
    }
    public function zip($zip_path){
        KoeTool::load_class('pclzip');
        ini_set('memory_limit', '2028M');//2G;
        $zip_list = json_decode($this->in['list'],true);
        $list_num = count($zip_list);
        for ($i=0; $i < $list_num; $i++) {
            $zip_list[$i]['path'] = rtrim(KoeTool::_DIR($zip_list[$i]['path']),'/');
        }
        //指定目录
        $basic_path = $zip_path;
        if (!isset($zip_path)){
            $basic_path = FileTool::get_path_father($zip_list[0]['path']);
        }
        if ($list_num == 1){
            $path_this_name= FileTool::get_path_this($zip_list[0]['path']);
        }else{
            $path_this_name= FileTool::get_path_this( FileTool::get_path_father($basic_path));
        }
        $zipname = $basic_path.$path_this_name.'.zip';
        $zipname = FileTool::get_filename_auto($zipname);
        if (!is_writeable($basic_path)) {
            KoeTool::show_json($this->L['no_permission_write'],false);
        }else{
            $files = array();
            for ($i=0; $i < $list_num; $i++) {
                $files[] = $zip_list[$i]['path'];
            }
            $remove_path_pre = KoeTool::_DIR_CLEAR(FileTool::get_path_father($zip_list[0]['path']));
            $archive = new PclZip($zipname);
            $v_list = $archive->create(implode(',',$files),PCLZIP_OPT_REMOVE_PATH,$remove_path_pre);
            if ($v_list == 0) {
                KoeTool::show_json("Error:".$archive->errorInfo(true),false);
            }
            $info = $this->L['zip_success'].$this->L['size'].":".FileTool::size_format(filesize($zipname));
            if (!isset($zip_path)) {
                KoeTool::show_json($info,true,FileTool::iconv_app(FileTool::get_path_this($zipname)));
            }else{
                return FileTool::iconv_app($zipname);
            }
        }
    }

    public function unzip(){
        KoeTool::load_class('PclZip');
        ini_set('memory_limit', '2028M');//2G;
        $path=$this->path;
        $name = FileTool::get_path_this($path);
        $name = substr($name,0,strrpos($name,'.'));
        $unzip_to=FileTool::get_path_father($path).$name;
        if (isset($this->in['path_to'])) {//解压到指定位置
            $unzip_to =KoeTool:: _DIR($this->in['path_to']);
        }
        //所在目录不可写
        if (!is_writeable(FileTool::get_path_father($path))){
            KoeTool::show_json($this->L['no_permission_write'],false);
        }
        $zip = new PclZip($path);//
        if ($GLOBALS['is_root'] == 1){
            $result = $zip->extract(PCLZIP_OPT_PATH,$unzip_to,
                PCLZIP_OPT_SET_CHMOD,0777,
                PCLZIP_OPT_REPLACE_NEWER);//解压到某个地方,覆盖方式
        }else{
            $result = $zip->extract(PCLZIP_OPT_PATH,$unzip_to,
                PCLZIP_OPT_SET_CHMOD,0777,
                PCLZIP_CB_PRE_EXTRACT,"checkExtUnzip",
                PCLZIP_OPT_REPLACE_NEWER);//解压到某个地方,覆盖方式
        }
        if ($result == 0) {
            KoeTool::show_json("Error : ".$zip->errorInfo(true));
        }else{
            KoeTool::show_json($this->L['unzip_success']);
        }
    }
    public function image(){
        if (filesize($this->path) <= 1024*10) {//小于10k 不再生成缩略图
            FileTool::file_put_out($this->path);
        }
        KoeTool::load_class('imageThumb');
        $image= $this->path;
        $image_md5  = md5_file($image);//文件md5
        if (strlen($image_md5)<5) {
            $image_md5 = md5($image);
        }

        $image_thum = DATA_THUMB.$image_md5.'.png';
        if (!is_dir(DATA_THUMB)){
            mkdir(DATA_THUMB,"0777");
        }
        if (!file_exists($image_thum)){//如果拼装成的url不存在则没有生成过
            if ($_SESSION['this_path']==DATA_THUMB){//当前目录则不生成缩略图
                $image_thum=$this->path;
            }else {
                $cm=new CreatMiniature();
                $cm->SetVar($image,'file');
                //$cm->Prorate($image_thum,72,64);//生成等比例缩略图
                $cm->BackFill($image_thum,72,64,true);//等比例缩略图，空白处填填充透明色
            }
        }
        if (!file_exists($image_thum) || filesize($image_thum)<100){//缩略图生成失败则用默认图标
            $image_thum=STATIC_PATH.'images/image.png';
        }
        //输出
        FileTool::file_put_out($image_thum);
    }

    // 远程下载
    public function serverDownload() {
        $uuid = 'download_'.$this->in['uuid'];
        if ($this->in['type'] == 'percent') {//获取下载进度
            //KoeTool::show_json($_SESSION[$uuid]);
            if (isset($_SESSION[$uuid])){
                $info = $_SESSION[$uuid];
                $result = array(
                    'uuid'      => $this->in['uuid'],
                    'length'    => (int)$info['length'],
                    'size'      => (int)filesize($info['path'].'.download'),
                    'time'      => KoeTool::mtime()
                );
                KoeTool::show_json($result);
            }else{
                KoeTool::show_json('',false);
            }
        }else if($this->in['type'] == 'remove'){//取消下载;文件被删掉则自动停止
            FileTool::del_file($_SESSION[$uuid]['path']);
            unset($_SESSION[$uuid]);
            KoeTool::show_json('',false);
        }
        //下载
        $save_path = KoeTool::_DIR($this->in['save_path']);
        if (!is_writeable($save_path)) KoeTool::show_json($this->L['no_permission_write'],false);

        $url = rawurldecode($this->in['url']);
        $header = WebTool::url_header($url);
        if (!$header) KoeTool::show_json($this->L['download_error_exists'],false);

        $save_path = $save_path.urldecode($header['name']);
        if (!KoeTool::checkExt($save_path)) $save_path = KoeTool::_DIR($this->in['save_path']).date('Y-m-d').'.temp';

        $save_path = FileTool::iconv_system($save_path);
        $save_path = FileTool::get_filename_auto($save_path);

        session_start();
        $_SESSION[$uuid] = array('length'=>$header['length'],'path'=>$save_path);
        session_write_close();

        if (FileTool::file_download_this($url,$save_path)){
            $name = FileTool::get_path_this(FileTool::iconv_app($save_path));
            KoeTool::show_json($this->L['download_success'],true,$name);
        }else{
            KoeTool::show_json($this->L['download_error_create'],false);
        }
    }

    //生成临时文件key
    public function makeFileProxy(){
        KoeTool::load_class('mcrypt');
        $pass = $this->config['setting_system']['system_password'];
        $fid = Mcrypt::encode($this->path,$pass,60*50*24);
        KoeTool::show_json($fid);
    }
    //代理输出
    public function fileProxy(){
        FileTool::file_put_out($this->path);
    }

    /**
     * 上传,html5拖拽  flash 多文件
     */
    public function fileUpload(){
        $save_path = $this->path;
        if (!is_writeable($save_path)) KoeTool::show_json($this->L['no_permission_write'],false);

        if ($save_path == '') KoeTool::show_json($this->L['upload_error_big'],false);
        if (strlen($this->in['fullPath']) > 1) {//folder drag upload
            $full_path = KoeTool::_DIR_CLEAR(rawurldecode($this->in['fullPath']));
            $full_path = FileTool::get_path_father($full_path);
            $full_path = FileTool::iconv_system($full_path);
            if (FileTool::mk_dir($save_path.$full_path)) {
                $save_path = $save_path.$full_path;
            }
        }
        //upload('file',$save_path);
        //分片上传
        defined('USER_TEMP') or die('USER_TEMP not defined!');
        $temp_dir = USER_TEMP;
        FileTool::mk_dir($temp_dir);
        if (!is_writeable($temp_dir)) KoeTool::show_json($this->L['no_permission_write'],false);
        FileTool::upload_chunk('file',$save_path,$temp_dir);
    }

    //share list
    private function path_share(){
        $path_hidden = $this->config['setting_system']['path_hidden'];
        $ex_name = explode(',',$path_hidden);

        $userShare = KoeTool::init_controller('userShare');
        $share_list = $userShare->get();
        $list = array(
            'folderlist'    => array(),
            'filelist'      => array(),
            'share_list'    => $share_list,
            'path_type'     => "writeable"
        );
        foreach ($share_list as $key => $value) {
            $value['path'] = $key;
            $value['atime']='';$value['ctime']='';
            $value['mode']='';$value['is_readable'] = 1;$value['is_writable'] = 1;
            $value['exists'] = intval(file_exists(KoeTool::_DIR($share_list[$key]['path'])));
            if ($value['type']=='file') {
                if(in_array($value['name'],$ex_name)) continue;
                $value['ext'] = FileTool::get_path_ext($share_list[$key]['path']);
                $list['filelist'][] = $value;
            }else{
                if(in_array($value['name'],$ex_name)) continue;
                $list['folderlist'][] = $value;
            }
        }
        return $list;
    }

    //获取文件列表&哦exe文件json解析
    private function path($dir,$list_file=true,$check_children=false){
        $path_hidden = $this->config['setting_system']['path_hidden'];
        $ex_name = explode(',',$path_hidden);
        if (strstr($dir,"*share*")) {
            return $this->path_share();
        }

        $list = FileTool::path_list($dir,$list_file,$check_children);
        $filelist_new = array();
        $folderlist_new = array();
        foreach ($list['filelist'] as $key => $val) {
            if (in_array($val['name'],$ex_name)) continue;
            if ($val['ext'] == 'oexe'){
                $path = FileTool::iconv_system($val['path']).'/'.FileTool::iconv_system($val['name']);
                $json = json_decode(file_get_contents($path),true);
                if(is_array($json)) $val = array_merge($val,$json);
            }
            $filelist_new[] = $val;
        }
        foreach ($list['folderlist'] as $key => $val) {
            if (in_array($val['name'],$ex_name)) continue;
            $folderlist_new[] = $val;
        }
        $list['filelist'] = $filelist_new;
        $list['folderlist'] = $folderlist_new;
        //读写权限判断
        $list['path_type'] = 'readable';
        if (is_writable($dir)) {
            $list['path_type'] = 'writeable';
        }else if (!is_readable($dir)) {
            $list['path_type'] = 'not_readable';
        }

        KoeTool::_DIR_OUT($list);
        return $list;
    }
}