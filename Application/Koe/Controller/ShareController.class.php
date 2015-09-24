<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 19:18
 */
namespace Application\Koe\Controller;
use Utils\Koe\CreatMiniature;
use Utils\Koe\FileCache;
use Utils\Koe\FileTool;
use Utils\Koe\KoeTool;
use Utils\Koe\PclZip;

class ShareController extends KoeController{
    /**
     * @var FileCache
     */
    private $sql;
    private $share_info;
    private $share_path;
    private $path;
    function __construct(){
        parent::__construct();
        $this->tpl = TEMPLATE.'share/';

        //不需要检查的action
        $arr_not_check = array('common_js');
        defined('ACT') or die('ACT not defined!');
        if (!in_array(ACT,$arr_not_check)){
            $this->_check_share();
            $this->_init_info();
            $this->assign('can_download',$this->share_info['not_download']=='1'?0:1);
        }
        //需要检查下载权限的Action
        $arr_check_download = array('fileDownload','zipDownload','fileProxy','fileGet');//'fileProxy','fileGet'
        if (in_array(ACT,$arr_check_download)){
            if ($this->share_info['not_download']=='1') {
                KoeTool::show_json($this->L['share_not_download_tips'],false);
            }
        }
        //禁止下载后；也会无法预览 'fileProxy','fileGet'
        if (ACT == 'file' && $this->share_info['not_download']=='1') {
            $this->error($this->L['share_not_download_tips']);
        }
    }
    //======//
    private function _check_share(){
        if (!isset($this->in['user']) || !isset($this->in['sid'])) {
            $this->error($this->L['share_error_param']);
        }
        //储存该共享基础信息
        $share_data = USER_PATH.$this->in['user'].'/data/share.php';
        if (!file_exists($share_data)) {
            $this->error($this->L['share_error_user']);
        }
        $this->sql=new FileCache($share_data);
        $list = $this->sql->get();
        if (!isset($list[$this->in['sid']])){
            $this->error($this->L['share_error_sid']);
        }

        $this->share_info = $list[$this->in['sid']];
        $share_info = $this->share_info;
        //检查是否过期
        if (count($share_info['time'])) {
            $date = date_create_from_format('y/m/d',$share_info['time_to']);
            if (time() > $date) {
                $this->error($this->L['share_error_time']);
            }
        }

        //密码检测
        if ($share_info['share_password']=='') return;
        //if ($_SESSION['kod_user']['name']==$this->in['user']) return;

        //提交密码
        if (!isset($this->in['password'])){
            //输入密码
            if ($_SESSION['password_'.$this->in['sid']]==$share_info['share_password']) return;
            $this->error('password');
        }else{
            if ($this->in['password'] == $share_info['share_password']) {
                session_start();
                $_SESSION['password_'.$this->in['sid']]=$share_info['share_password'];
                KoeTool::show_json('success');
            }else{
                KoeTool::show_json($this->L['share_error_password'],false);
            }
        }
    }
    private function _init_info(){
        //获取用户组，根据是否为root 定义前缀
        $member = new FileCache(USER_SYSTEM.'member.php');
        $user = $member->get($this->in['user']);
        if (!is_array($user) || !isset($user['password'])) {
            $this->error($this->L['share_error_user']);
        }
        define('USER',USER_PATH.$user['name'].'/');
        define('USER_TEMP',USER.'data/share_temp/');

        $share_path = KoeTool::_DIR_CLEAR($this->share_info['path']);
        if (substr($share_path,0,strlen('*public*/')) == '*public*/') {
            $share_path = PUBLIC_PATH.str_replace('*public*/','',$share_path);
        }else{
            if ($user['role'] != 'root') {
                $share_path = USER.'home/'.$share_path;
            }else{
                $share_path = KoeTool::_DIR_CLEAR($this->share_info['path']);
            }
        }

        if ($this->share_info['type'] != 'file'){
            $share_path=rtrim($share_path,'/').'/';
            define('HOME',$share_path);//dir_out时当作前缀剔除;系统
        }

        $share_path = FileTool::iconv_system($share_path);
        if (!file_exists($share_path)) {
            $this->error($this->L['share_error_path']);
        }
        $this->share_path = $share_path;
        $this->path = $share_path.$this->_clear($this->in['path']);
    }
    private function _clear($path){
        return  FileTool::iconv_system(KoeTool::_DIR_CLEAR(rawurldecode($path)));
    }
    public function error($msg){
        $this->assign('msg',$msg);
        $this->display('tips.php');
        exit;
    }

    //==========================
    /*
     * 文件浏览
     */
    public function file() {
        $this->share_view_add();
        if ($this->share_info['type']!='file') {
            $this->share_info['name'] = FileTool::get_path_this($this->path);
        }
        $size = filesize($this->path);
        $this->share_info['size'] = FileTool::size_format($size);
        $this->_assign_info();
        $this->display('file.php');
    }
    /*
     * 文件夹浏览
     */
    public function folder() {
        $this->share_view_add();
        if(isset($this->in['path']) && $this->in['path'] !=''){
            $dir = '/'.KoeTool::_DIR_CLEAR($this->in['path']);
        }else{
            $dir = '/';//首次进入系统,不带参数
        }
        $dir = '/'.trim($dir,'/').'/';
        $this->_assign_info();
        $this->assign('dir',$dir);
        $this->display('explorer.php');
    }
    /*
     * 代码阅读
     */
    public function code_read() {
        $this->share_view_add();
        $this->_assign_info();
        $this->display('editor.php');
    }

    //==========================
    //页面统一注入变量
    private function _assign_info(){
        $user_config = new FileCache(USER.'data/config.php');
        $config = $user_config->get();
        if (count($config)<1) {
            $config = $GLOBALS['config']['setting_default'];
        }
        $this->assign('config_theme',$config['theme']);
        $this->share_info['share_password'] = '';
        $this->share_info['num_view'] = intval($this->share_info['num_view']);
        $this->share_info['num_download'] = intval($this->share_info['num_download']);
        $this->share_info['path'] = FileTool::get_path_this(FileTool::iconv_app($this->path));
        $this->assign('share_info',$this->share_info);
    }
    //下载次数统计
    private function share_download_add(){
        $num = abs(intval($this->share_info['num_download'])) +1;
        $this->share_info['num_download'] = $num;
        $this->sql->update($this->in['sid'],$this->share_info);
    }
    //浏览次数统计
    private function share_view_add(){
        $num = abs(intval($this->share_info['num_view'])) +1;
        $this->share_info['num_view'] = $num;
        $this->sql->update($this->in['sid'],$this->share_info);
    }
    public function common_js(){
        $config = $GLOBALS['config']['setting_default'];
        $the_config = array(
            'lang'          => LANGUAGE_TYPE,
            'is_root'       => 0,
            'web_root'      => '/',
            'web_host'      => HOST,
            'static_path'   => STATIC_PATH,
            'basic_path'    => BASIC_PATH,
            'version'       => KOD_VERSION,
            'app_host'      => APPHOST,
            'office_server' => OFFICE_SERVER,
            'json_data'     => "",
            'share_page'    => 'share',

            'theme'         => $config['theme'],           //列表排序依照的字段
            'list_type'     => $config['list_type'],       //列表排序依照的字段
            'sort_field'    => $config['list_sort_field'], //列表排序依照的字段
            'sort_order'    => $config['list_sort_order'], //列表排序升序or降序
            'musictheme'    => $config['musictheme'],
            'movietheme'    => $config['movietheme']
        );

        //KoeTool::show_json($this->L);
        $js  = 'LNG='.json_encode($GLOBALS['L']).';';
        $js .= 'AUTH=[];';
        $js .= 'G='.json_encode($the_config).';';
        header("Content-Type:application/javascript");
        echo $js;
    }


    //========ajax function============
    public function pathInfo(){
        $info_list = json_decode($this->in['list'],true);
        foreach ($info_list as &$val) {
            $val['path'] = $this->share_path.$this->_clear($val['path']);
        }
        $data = FileTool::path_info_muti($info_list,$this->L['time_type_info']);
        KoeTool::_DIR_OUT($data['path']);
        KoeTool::show_json($data);
    }
    public function fileSave(){
        KoeTool::show_json($this->L['no_permission'],false);
    }

    // 单文件编辑
    public function edit(){
        $default = array(
            'font_size'     => '14px',
            'theme'         => 'clouds',
            'auto_wrap'     => 0,
            'display_char'  => 0,
            'auto_complete' => 1,
            'function_list' => 1
        );
        $this->_assign_info();
        $this->assign('editor_config',$default);//获取编辑器配置信息
        $this->display('edit.php');
    }
    public function pathList(){
        $list=$this->path($this->path);
        KoeTool::show_json($list);
    }
    public function treeList(){
        $path=$this->path;
        if (isset($this->in['project'])) {
            $path = $this->share_path.$this->_clear($this->in['project']);
        }
        if (isset($this->in['name'])){
            $path=$path.'/'.$this->_clear($this->in['name']);
        }
        $list_file = ($this->in['app'] == 'editor'?true:false);//编辑器内列出文件
        $list=$this->path($path,$list_file,true);
        function sort_by_key($a, $b){
            if ($a['name'] == $b['name']) return 0;
            return ($a['name'] > $b['name']) ? 1 : -1;
        }
        usort($list['folderlist'], "sort_by_key");
        usort($list['filelist'], "sort_by_key");

        $result = array_merge($list['folderlist'],$list['filelist']);
        if ($this->in['app'] != 'editor') {
            $result =$list['folderlist'];
        }
        if ($this->in['type']=='init') {
            $result = array(
                array(
                    'name'=>FileTool::iconv_app(FileTool::get_path_this($path)),
                    'children'=>$result,
                    'menuType'=>"menuTreeRoot",
                    'open'=>true,
                    'this_path'=> '/',
                    'isParent'=>count($result)>0?true:false
                )
            );
        }
        KoeTool::show_json($result);
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


    //代理输出
    public function fileProxy(){
        FileTool::file_put_out($this->path);
    }
    public function fileDownload(){
        $this->share_download_add();
        FileTool::file_put_out($this->path,true);
    }
    //文件下载后删除,用于文件夹下载
    public function fileDownloadRemove(){
        if ($this->share_info['not_download']=='1') {
            KoeTool::show_json($this->L['share_not_download_tips'],false);
        }
        $path = rawurldecode(KoeTool::_DIR_CLEAR($this->in['path']));
        $path = USER_TEMP.FileTool::iconv_system($path);
        FileTool::file_put_out($path,true);
        FileTool::del_file($path);
    }
    public function zipDownload(){
        $this->share_download_add();
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
    private function zip($zip_path){
        if (!isset($zip_path)) {
            KoeTool::show_json($this->L['share_not_download_tips'],false);
        }
        KoeTool::load_class('PclZip');
        ini_set('memory_limit', '2028M');//2G;
        $zip_list = json_decode($this->in['list'],true);
        $list_num = count($zip_list);
        for ($i=0; $i<$list_num; $i++) {
            $zip_list[$i]['path'] = KoeTool::_DIR_CLEAR($this->path.$this->_clear($zip_list[$i]['path']));
        }

        //指定目录
        if ($list_num == 1) {
            $path_this_name=FileTool::get_path_this($zip_list[0]['path']);
        }else{
            $path_this_name=FileTool::get_path_this(FileTool::get_path_father($zip_list[0]['path']));
        }
        $zipname = $zip_path.$path_this_name.'.zip';
        $zipname = FileTool::get_filename_auto($zipname,date(' h.i.s'));
        $files = array();
        for ($i=0; $i < $list_num; $i++) {
            $files[] = $zip_list[$i]['path'];
        }
        $remove_path_pre = FileTool::get_path_father($zip_list[0]['path']);
        $archive = new PclZip($zipname);
        $v_list = $archive->create(implode(',',$files),PCLZIP_OPT_REMOVE_PATH,$remove_path_pre);
        return FileTool::iconv_app($zipname);
    }

    // 获取文件数据
    public function fileGet(){
        $name = $this->_clear($this->in['filename']);
        $filename= $this->share_path.$name;
        if (filesize($filename) >= 1024*1024*20) KoeTool::show_json($this->L['edit_too_big'],false);

        $filecontents=file_get_contents($filename);//文件内容
        $charset=$this->_get_charset($filecontents);
        if ($charset!='' || $charset!='utf-8') {
            $filecontents=mb_convert_encoding($filecontents,'utf-8',$charset);
        }
        $data = array(
            'ext'       => FileTool::get_path_ext($name),
            'name'      => FileTool::iconv_app($name),
            'filename'  => $name,
            'charset'   => $charset,
            'content'   => $filecontents
        );
        KoeTool::show_json($data);
    }
    private function _get_charset(&$str) {
        if ($str == '') return 'utf-8';
        //前面检测成功则，自动忽略后面
        $charset=strtolower(mb_detect_encoding($str,$this->config['check_charset']));
        if (substr($str,0,3)==chr(0xEF).chr(0xBB).chr(0xBF)){
            $charset='utf-8';
        }else if($charset=='cp936'){
            $charset='gbk';
        }
        if ($charset == 'ascii') $charset = 'utf-8';
        return strtolower($charset);
    }


    public function image(){
        if (filesize($this->path) <= 1024*10) {//小于10k 不再生成缩略图
            FileTool::file_put_out($this->path);
        }
        KoeTool::load_class('imageThumb');
        $image= $this->path;
        $image_thum = DATA_THUMB.md5($image).'.png';
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
        FileTool::file_put_out($image_thum);
    }

    //获取文件列表&哦exe文件json解析
    private function path($dir,$list_file=true,$check_children=false){
        $list = FileTool::path_list($dir,$list_file,$check_children);

        $file_parem = array('filelist'=>array(),'folderlist'=>array());
        $path_hidden = $this->config['setting_system']['path_hidden'];
        $ex_name = explode(',',$path_hidden);
        foreach ($list['filelist'] as $key => $val) {
            if (in_array($val['name'],$ex_name)) continue;
            if ($val['ext'] == 'oexe'){
                $path = FileTool::iconv_system($val['path']).'/'.FileTool::iconv_system($val['name']);
                $json = json_decode(file_get_contents($path),true);
                if(is_array($json)) $val = array_merge($val,$json);
            }
            $file_parem['filelist'][] = $val;
        }
        foreach ($list['folderlist'] as $key => $val) {
            if (in_array($val['name'],$ex_name)) continue;
            $file_parem['folderlist'][] = $val;
        }
        KoeTool::_DIR_OUT($file_parem);
        return $file_parem;
    }
}