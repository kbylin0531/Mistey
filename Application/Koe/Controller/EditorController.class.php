<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 18:39
 */
namespace Application\Koe\Controller;

use Utils\Koe\FileCache;
use Utils\Koe\FileTool;
use Utils\Koe\KoeTool;

class EditorController extends KoeController{
    function __construct()    {
        parent::__construct();
        $this->tpl = TEMPLATE . 'editor/';
    }

    // ���ļ��༭��
    public function index(){
        $this->display('editor.php');
    }
    // ���ļ��༭
    public function edit(){
        $this->assign('editor_config',$this->getConfig());//��ȡ�༭��������Ϣ
        $this->display('edit.php');
    }

    // ��ȡ�ļ�����
    public function fileGet(){
        $filename = KoeTool::_DIR($this->in['filename']);
        if (!is_readable($filename)) KoeTool::show_json($this->L['no_permission_read'],false);
        if (filesize($filename) >= 1024*1024*20) KoeTool::show_json($this->L['edit_too_big'],false);

        $filecontents=file_get_contents($filename);//�ļ�����
        $charset=$this->_get_charset($filecontents);
        if ($charset!='' || $charset!='utf-8') {
            $filecontents=mb_convert_encoding($filecontents,'utf-8',$charset);
        }
        $data = array(
            'ext'		=> FileTool::get_path_ext($filename),
            'name'      => FileTool::iconv_app(FileTool::get_path_this($filename)),
            'filename'	=> rawurldecode($this->in['filename']),
            'charset'	=> $charset,
            'content'	=> $filecontents
        );
        KoeTool::show_json($data);
    }

    /**
     *
     */
    public function fileSave(){
        $filestr = rawurldecode($this->in['filestr']);
        $charset = $this->in['charset'];
        $path =KoeTool::_DIR($this->in['path']);
        if (!is_writable($path)) KoeTool::show_json($this->L['no_permission_write_file'],false);

        if ($charset !='' || $charset != 'utf-8') {
            $filestr=mb_convert_encoding($filestr,$this->in['charset'],'utf-8');
        }
        $fp=fopen($path,'wb');
        fwrite($fp,$filestr);
        fclose($fp);
        KoeTool::show_json($this->L['save_success']);
    }

    /*
    * ��ȡ�༭��������Ϣ
    */
    public function getConfig(){
        $default = array(
            'font_size'		=> '15px',
            'theme'			=> 'clouds',
            'auto_wrap'		=> 0,
            'display_char'	=> 0,
            'auto_complete'	=> 1,
            'function_list' => 1
        );
        defined('USER') or die('USER not defined!');
        $config_file = USER.'data/editor_config.php';
        if (!file_exists($config_file)) {//�������򴴽�
            $sql=new FileCache($config_file);
            $sql->reset($default);
        }else{
            $sql=new fileCache($config_file);
            $default = $sql->get();
        }
        if (!isset($default['function_list'])) {
            $default['function_list'] = 1;
        }
        return json_encode($default);
    }
    /*
    * ��ȡ�༭��������Ϣ
    */
    public function setConfig(){
        defined('USER') or die('USER not defined!');
        $file = USER.'data/editor_config.php';
        if (!is_writeable($file)) {//���ò���д
            KoeTool::show_json($this->L['no_permission_write_file'],false);
        }
        $key= $this->in['k'];
        $value = $this->in['v'];
        if ($key !='' && $value != '') {
            $sql=new fileCache($file);
            if(!$sql->update($key,$value)){
                $sql->add($key,$value);//û�������һ��
            }
            KoeTool::show_json($this->L["setting_success"]);
        }else{
            KoeTool::show_json($this->L['error'],false);
        }
    }

    //-----------------------------------------------
    /*
    * ��ȡ�ַ�������
    * @param:$ext �����ַ���
    */
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
}