<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 19:16
 */
namespace Application\Koe\Controller;

use Utils\Koe\FileCache;
use Utils\Koe\KoeTool;

class SettingController extends KoeController{

//    private $sql = null;

    function __construct(){
        parent::__construct();
    }

    /**
     * �û���ҳչʾ
     */
    public function index() {
        $this->tpl = TEMPLATE.'setting/';
        $this->display('index.php');
    }

    /**
     * �û���ҳչʾ
     */
    public function slider() {
        $this->tpl = TEMPLATE . 'setting/slider/';
        $this->display($this->in['slider'].'.php');
    }

    public function php_info(){
        phpinfo();
    }
    public function get_setting(){
        $setting = $GLOBALS['config']['setting_system']['menu'];
        if (!$setting) {
            $setting = $this->config['setting_menu_default'];
        }
        KoeTool::show_json($setting);
    }


    //����Ա  ϵͳ����ȫ������
    public function system_setting(){
        $setting_file = USER_SYSTEM.'system_setting.php';
        $data = json_decode($this->in['data'],true);
        if (!$data) {
            KoeTool::show_json($this->L['error'],false);
        }
        $setting = $GLOBALS['config']['setting_system'];
        foreach ($data as $key => $value){
            if ($key=='menu') {
                $setting[$key] = $value;
            }else{
                $setting[$key] = rawurldecode($value);
            }
        }
        //$setting['menu'] = $GLOBALS['config']['setting_menu_default'];
        //Ϊ�˱����������ݣ���ֱ�Ӹ����ļ� $data->setting_file;
        FileCache::save($setting_file,$setting);
        KoeTool::show_json($this->L['success']);
        //KoeTool::show_json($setting);
    }

    /**
     * ��������
     * ����ͬʱ�޸Ķ����key=a,b,c&value=1,2,3
     */
    public function set(){
        $file = $this->config['user_seting_file'];
        if (!is_writeable($file)) {//���ò���д
            KoeTool::show_json($this->L['no_permission_write_file'],false);
        }
        $key   = $this->in['k'];
        $value = $this->in['v'];
        if ($key !='' && $value != '') {
            $conf = $this->config['user'];
            $arr_k = explode(',', $key);
            $arr_v = explode(',',$value);
            $num = count($arr_k);

            for ($i=0; $i < $num; $i++) {
                $conf[$arr_k[$i]] = $arr_v[$i];
            }
            fileCache::save($file,$conf);
            KoeTool::show_json($this->L["setting_success"]);
        }else{
            KoeTool::show_json($this->L['error'],false);
        }
    }
}
