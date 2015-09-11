<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 19:14
 */
namespace Application\Koe\Controller;
use Utils\Koe\FileCache;
use Utils\Koe\FileTool;
use Utils\Koe\KoeTool;

class MemberController extends KoeController{
    private $sql;
    function __construct()    {
        parent::__construct();
        $this->tpl = TEMPLATE.'member/';
        $this->sql=new FileCache(USER_SYSTEM.'member.php');
    }

    /**
     * ��ȡ�û��б�����
     */
    public function get() {
        KoeTool::show_json($this->sql->get());
    }
    /**
     * �û����
     */
    public function add(){
        if (!$this->in['name'] ||
            !$this->in['password'] ||
            !$this->in['role'] ) KoeTool::show_json($this->L["data_not_full"],false);

        $this->in['name'] = rawurldecode($this->in['name']);
        $this->in['password'] = rawurldecode($this->in['password']);
        $user = array(
            'name'      =>  rawurldecode($this->in['name']),
            'password'  =>  md5(rawurldecode($this->in['password'])),
            'role'      =>  $this->in['role'],
            'status'    =>  0,
        );
        if ($this->sql->add($this->in['name'],$user)) {
            $this->_initUser($this->in['name']);
            KoeTool::show_json($this->L['success']);
        }
        KoeTool::show_json($this->L['error_repeat'],false);
    }

    /**
     * �༭
     */
    public function edit() {
        if (!$this->in['name'] ||
            !$this->in['name_to'] ||
            !$this->in['role_to'] ) KoeTool::show_json($this->L["data_not_full"],false);

        $this->in['name'] = rawurldecode($this->in['name']);
        $this->in['name_to'] = rawurldecode($this->in['name_to']);
        $this->in['password_to'] = rawurldecode($this->in['password_to']);
        if ($this->in['name'] == 'admin') KoeTool::show_json($this->L['default_user_can_not_do'],false);

        //���ҵ�һ����¼���޸�Ϊ������
        $user = $this->sql->get($this->in['name']);
        $user['name'] = $this->in['name_to'];
        $user['role'] = $this->in['role_to'];

        if (strlen($this->in['password_to'])>=1) {
            $user['password'] = md5($this->in['password_to']);
        }
        if($this->sql->replace_update($this->in['name'],$user['name'],$user)){
            rename(USER_PATH.$this->in['name'],USER_PATH.$this->in['name_to']);
            KoeTool::show_json($this->L['success']);
        }
        KoeTool::show_json($this->L['error_repeat'],false);
    }

    /**
     * ɾ��
     */
    public function del() {
        $name = $this->in['name'];
        if (!$name) KoeTool::show_json($this->L["username_can_not_null"],false);
        if ($name == 'admin') KoeTool::show_json($this->L['default_user_can_not_do'],false);
        if($this->sql->delete($name)){
            FileTool::del_dir(USER_PATH.$name.'/');
            KoeTool::show_json($this->L['success']);
        }
        KoeTool::show_json($this->L['error'],false);
    }

    //============�ڲ�������=============
    /**
     * ��ʼ���û����ݺ����á�
     * @param $name
     */
    public function _initUser($name){
        $root = array('home','recycle','data');
        $new_user_folder = $this->config['setting_system']['new_user_folder'];
        $home = explode(',',$new_user_folder);

        $user_path = USER_PATH.$name.'/';
        FileTool::mk_dir($user_path);
        foreach ($root as $dir) {
            FileTool::mk_dir($user_path.$dir);
        }
        foreach ($home as $dir) {
            FileTool::mk_dir($user_path.'home/'.$dir);
        }
        fileCache::save($user_path.'data/config.php',$this->config['setting_default']);
    }
}