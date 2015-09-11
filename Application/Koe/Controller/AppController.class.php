<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 18:33
 */
namespace Application\Koe\Controller;
use Utils\Koe\FileCache;
use Utils\Koe\FileTool;
use Utils\Koe\KoeTool;
use Utils\Koe\WebTool;

class AppController extends KoeController{
    function __construct()    {
        parent::__construct();
        $this->sql=new FileCache(USER_SYSTEM.'apps.php');
    }

    /**
     * �û���ҳչʾ
     */
    public function index() {
        $this->display(TEMPLATE.'app/index.php');
    }

    public function init_app($user_info){
        $list = $this->sql->get();
        $new_user_app = $this->config['setting_system']['new_user_app'];
        $default = explode(',',$new_user_app);
        $info = array();
        foreach ($default as $key) {
            $info[$key] = $list[$key];
        }
        $desktop = USER_PATH.$user_info['name'].'/home/desktop/';
        FileTool::mk_dir($desktop);
        foreach ($info as $key => $data) {
            if (!is_array($data)) {
                continue;
            }
            $path = FileTool::iconv_system($desktop.$key.'.oexe');
            unset($data['name']);
            unset($data['desc']);
            unset($data['group']);
            file_put_contents($path, json_encode($data));
        }
        $user_info['status'] = 1;
        $member = new fileCache(USER_SYSTEM.'member.php');
        $member->update($user_info['name'],$user_info);
    }

    /**
     * �û�app ��ӡ��༭
     */
    public function user_app() {
        $path = KoeTool::_DIR($this->in['path']);
        if (isset($this->in['action']) && $this->in['action'] == 'add'){
            $path .= '.oexe';
        }

        if (!KoeTool::checkExt($path)) {
            KoeTool::show_json($this->L['error']);exit;
        }

        $data = json_decode(rawurldecode($this->in['data']),true);
        unset($data['name']);unset($data['desc']);unset($data['group']);
        $res  = file_put_contents($path, json_encode($data));
        KoeTool::show_json($this->L['success']);
        return $res;
    }

    /**
     * ��ȡ�б�
     */
    public function get() {
        $list = (!isset($this->in['group']) || $this->in['group']=='all')?$this->sql->get():
            $this->sql->get('group','',$this->in['group']);
        $list = array_reverse($list);
        KoeTool::show_json($list);
    }

    /**
     * ���
     */
    public function add() {
        $res=$this->sql->add(rawurldecode($this->in['name']),$this->_init());
        if($res) KoeTool::show_json($this->L['success']);
        KoeTool::show_json($this->L['error_repeat'],false);
    }

    /**
     * �༭
     */
    public function edit() {
        //���ҵ�һ����¼���޸�Ϊ������
        if($this->sql->replace_update(
            rawurldecode($this->in['old_name']),
            rawurldecode($this->in['name']),$this->_init())){
            KoeTool::show_json($this->L['success']);
        }
        KoeTool::show_json($this->L['error_repeat'],false);
    }
    /**
     * ɾ��
     */
    public function del() {
        if($this->sql->delete(rawurldecode($this->in['name']))){
            KoeTool::show_json($this->L['success']);
        }
        KoeTool::show_json($this->L['error'],false);
    }

    public function get_url_title(){
        $html = WebTool::curl_get_contents($this->in['url']);
        $result = KoeTool::match($html,"<title>(.*)<\/title>");
        if (strlen($result)>50) {
            $result = mb_substr($result,0,50,'utf-8');
        }
        if (!$result || strlen($result) == 0) {
            $result = $this->in['url'];
            $result = str_replace(array('http://','&','/'),array('','@','-'), $result);
        }
        KoeTool::show_json($result);
    }

    private function _init(){
        return  json_decode(rawurldecode($this->in['data']));
    }
}