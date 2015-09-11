<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 19:12
 */
namespace Application\Koe\Controller;
use Utils\Koe\FileCache;
use Utils\Koe\KoeTool;

class GroupController extends KoeController{
    private $sql;
    function __construct()    {
        parent::__construct();
        $this->sql=new FileCache(USER_SYSTEM.'group.php');
    }

    public function get() {
        KoeTool::show_json($this->sql->get());
    }
    /**
     * �û����
     */
    public function add(){
        $group = $this->_init_data();
        if($this->sql->add($this->in['role'],$group)){
            KoeTool::show_json($this->L['success']);
        }
        KoeTool::show_json($this->L['error_repeat'],false);
    }

    /**
     * �༭
     */
    public function edit(){
        $group = $this->_init_data();
        $role_old = $this->in['role_old'];
        if (!$role_old) KoeTool::show_json($this->L["groupname_can_not_null"],false);
        if ($role_old == 'root') KoeTool::show_json($this->L['default_group_can_not_do'],false);

        if ($this->sql->replace_update($role_old,$this->in['role'],$group)){
            $member = new fileCache(USER_SYSTEM.'member.php');
            if ($member -> update('role',$this->in['role'],$role_old)) {
                KoeTool::show_json($this->L['success']);
            }
            KoeTool::show_json($this->L['group_move_user_error'],false);
        }
        KoeTool::show_json($this->L['error_repeat'],false);
    }

    /**
     * ɾ��
     */
    public function del() {
        $role = $this->in['role'];
        if (!$role) KoeTool::show_json($this->L["groupname_can_not_null"],false);
        if ($role == 'root') KoeTool::show_json($this->L['default_group_can_not_do'],false);
        if($this->sql->delete($role)){
            $member = new fileCache(USER_SYSTEM.'member.php');
            $member -> update('role','',$role);//�����û�����Ϊ��
            KoeTool::show_json($this->L['success']);
        }
        KoeTool::show_json($this->L['error'],false);
    }


    //===========�ڲ�����============
    /**
     * ��ʼ������ get
     * ֻ��������  &ext_not_allow=''&explorer-mkfile&explorer-pathRname
     */
    private function _init_data(){
        if (strlen($this->in['role'])<1) KoeTool::show_json($this->L["groupname_can_not_null"],false);
        if (strlen($this->in['name'])<1) KoeTool::show_json($this->L["groupdesc_can_not_null"],false);

        $role_arr = array('role'=>$this->in['role'],'name'=>$this->in['name']);
        $role_arr['ext_not_allow'] = $this->in['ext_not_allow'];
        foreach ($this->config['role_setting'] as $key => $actions) {
            foreach ($actions as $action) {
                $k = $key.':'.$action;
                if (isset($this->in[$k])){
                    $role_arr[$k] = 1;
                }else{
                    $role_arr[$k] = 0;
                }
            }
        }
        return $role_arr;
    }
}