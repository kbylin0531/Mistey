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

        //����Ҫ����action
        $arr_not_check = array('common_js');
        defined('ACT') or die('ACT not defined!');
        if (!in_array(ACT,$arr_not_check)){
            $this->_check_share();
            $this->_init_info();
            $this->assign('can_download',$this->share_info['not_download']=='1'?0:1);
        }
        //��Ҫ�������Ȩ�޵�Action
        $arr_check_download = array('fileDownload','zipDownload','fileProxy','fileGet');//'fileProxy','fileGet'
        if (in_array(ACT,$arr_check_download)){
            if ($this->share_info['not_download']=='1') {
                KoeTool::show_json($this->L['share_not_download_tips'],false);
            }
        }
        //��ֹ���غ�Ҳ���޷�Ԥ�� 'fileProxy','fileGet'
        if (ACT == 'file' && $this->share_info['not_download']=='1') {
            $this->error($this->L['share_not_download_tips']);
        }
    }
    //======//
    private function _check_share(){
        if (!isset($this->in['user']) || !isset($this->in['sid'])) {
            $this->error($this->L['share_error_param']);
        }
        //����ù��������Ϣ
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
        //����Ƿ����
        if (count($share_info['time'])) {
            $date = date_create_from_format('y/m/d',$share_info['time_to']);
            if (time() > $date) {
                $this->error($this->L['share_error_time']);
            }
        }

        //������
        if ($share_info['share_password']=='') return;
        //if ($_SESSION['kod_user']['name']==$this->in['user']) return;

        //�ύ����
        if (!isset($this->in['password'])){
            //��������
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
        //��ȡ�û��飬�����Ƿ�Ϊroot ����ǰ׺
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
            define('HOME',$share_path);//dir_outʱ����ǰ׺�޳�;ϵͳ
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
     * �ļ����
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
     * �ļ������
     */
    public function folder() {
        $this->share_view_add();
        if(isset($this->in['path']) && $this->in['path'] !=''){
            $dir = '/'.KoeTool::_DIR_CLEAR($this->in['path']);
        }else{
            $dir = '/';//�״ν���ϵͳ,��������
        }
        $dir = '/'.trim($dir,'/').'/';
        $this->_assign_info();
        $this->assign('dir',$dir);
        $this->display('explorer.php');
    }
    /*
     * �����Ķ�
     */
    public function code_read() {
        $this->share_view_add();
        $this->_assign_info();
        $this->display('editor.php');
    }

    //==========================
    //ҳ��ͳһע�����
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
    //���ش���ͳ��
    private function share_download_add(){
        $num = abs(intval($this->share_info['num_download'])) +1;
        $this->share_info['num_download'] = $num;
        $this->sql->update($this->in['sid'],$this->share_info);
    }
    //�������ͳ��
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

            'theme'         => $config['theme'],           //�б��������յ��ֶ�
            'list_type'     => $config['list_type'],       //�б��������յ��ֶ�
            'sort_field'    => $config['list_sort_field'], //�б��������յ��ֶ�
            'sort_order'    => $config['list_sort_order'], //�б���������or����
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

    // ���ļ��༭
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
        $this->assign('editor_config',$default);//��ȡ�༭��������Ϣ
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
        $list_file = ($this->in['app'] == 'editor'?true:false);//�༭�����г��ļ�
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


    //�������
    public function fileProxy(){
        FileTool::file_put_out($this->path);
    }
    public function fileDownload(){
        $this->share_download_add();
        FileTool::file_put_out($this->path,true);
    }
    //�ļ����غ�ɾ��,�����ļ�������
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
        }else{//���δɾ������ʱ�ļ���һ��ǰ
            $list = FileTool::path_list(USER_TEMP,true,false);
            $max_time = 3600*24;
            if ($list['filelist']>=1) {
                for ($i=0; $i < count($list['filelist']); $i++) {
                    $create_time = $list['filelist'][$i]['mtime'];//����޸�ʱ��
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

        //ָ��Ŀ¼
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

    // ��ȡ�ļ�����
    public function fileGet(){
        $name = $this->_clear($this->in['filename']);
        $filename= $this->share_path.$name;
        if (filesize($filename) >= 1024*1024*20) KoeTool::show_json($this->L['edit_too_big'],false);

        $filecontents=file_get_contents($filename);//�ļ�����
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
        //ǰ����ɹ����Զ����Ժ���
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
        if (filesize($this->path) <= 1024*10) {//С��10k ������������ͼ
            FileTool::file_put_out($this->path);
        }
        KoeTool::load_class('imageThumb');
        $image= $this->path;
        $image_thum = DATA_THUMB.md5($image).'.png';
        if (!is_dir(DATA_THUMB)){
            mkdir(DATA_THUMB,"0777");
        }
        if (!file_exists($image_thum)){//���ƴװ�ɵ�url��������û�����ɹ�
            if ($_SESSION['this_path']==DATA_THUMB){//��ǰĿ¼����������ͼ
                $image_thum=$this->path;
            }else {
                $cm=new CreatMiniature();
                $cm->SetVar($image,'file');
                //$cm->Prorate($image_thum,72,64);//���ɵȱ�������ͼ
                $cm->BackFill($image_thum,72,64,true);//�ȱ�������ͼ���հ״������͸��ɫ
            }
        }
        if (!file_exists($image_thum) || filesize($image_thum)<100){//����ͼ����ʧ������Ĭ��ͼ��
            $image_thum=STATIC_PATH.'images/image.png';
        }
        FileTool::file_put_out($image_thum);
    }

    //��ȡ�ļ��б�&Ŷexe�ļ�json����
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