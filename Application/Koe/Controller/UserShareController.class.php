<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 19:30
 */
namespace Application\Koe\Controller;
use Utils\Koe\FileCache;
use Utils\Koe\KoeTool;

class UserShareController extends KoeController{
    private $sql;
    function __construct(){
        parent::__construct();
        $this->sql=new FileCache($this->config['user_share_file']);
    }

    /**
     * ��ȡ
     */
    public function get() {
        return $this->sql->get();
    }
    public function checkByPath(){
        $share_list = $this->sql->get('path','',$this->in['path']);
        //show_json($this->sql->get(),true,$this->in['path']);

        if (count($share_list)==0) {
            KoeTool::show_json('',false);//û���ҵ�
        }else{
            $val = array_values($share_list);
            KoeTool::show_json($val[0],true);
        }
    }

    /**
     * �༭
     */
    public function set(){
        $share_info = $this->_getData();

        //����sid��Ϊ���£�����Ϊ����
        if (isset($this->in['sid']) && strlen($this->in['sid']) == 8) {
            $info_new = $this->sql->get($this->in['sid']);
            //ֻ����ָ��key
            foreach ($share_info as $key=>$val) {
                $info_new[$key] = $val;
            }
            if($this->sql->update($this->in['sid'],$info_new)){
                KoeTool::show_json($info_new,true);
            }
            KoeTool::show_json($this->L['error'],false);
        }else{//����
            $share_list = $this->sql->get();
            $new_id = KoeTool::rand_string(8);
            while (isset($share_list[$new_id])) {
                $new_id = KoeTool::rand_string(8);
            }
            $share_info['sid'] = $new_id;
            if($this->sql->add($new_id,$share_info)){
                KoeTool::show_json($share_info,true);
            }
            KoeTool::show_json($this->L['error'],false);
        }
        KoeTool::show_json($this->L['error'],false);
    }

    /**
     * ɾ��
     */
    public function del() {
        $list = json_decode($this->in['list'],true);
        foreach ($list as $val) {
            $this->sql->delete($val['path']);
        }
        KoeTool::show_json($this->L['success'],true);
    }

    public function _getData(){
        if (!$this->in['name'] || !$this->in['path'] || !$this->in['type']){
            KoeTool::show_json($this->L["data_not_full"],false);
        }
        $in = array(
            'mtime'=>time(),//�������¼���ʱ��
            'sid'=>$this->in['sid'],
            'type'=>$this->in['type'],
            'path'=>$this->in['path'],
            'name'=>$this->in['name'],
            'time_to'=>$this->in['time_to']?$this->in['time_to']:'',
            'share_password'=>$this->in['share_password']?$this->in['share_password']:'',
            'code_read'=>$this->in['code_read']?$this->in['code_read']:'',
            'not_download'=>$this->in['not_download']?$this->in['not_download']:''
        );
        return $in;
    }
}