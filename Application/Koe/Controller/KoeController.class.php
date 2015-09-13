<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 21:20
 */
namespace Application\Koe\Controller;
use System\Core\Controller;
use System\Utils\Util;
use Utils\Koe\KoeTool;
use Utils\Koe\WebTool;

abstract class KoeController extends Controller{

    public $in;
    public $db;
    /**
     * ģ��Ŀ¼
     * @var string
     */
    public $tpl = '';

    /**
     * ģ�����
     * @var array
     */
    public $values = array();

    public $L;

    /**
     * ȫ������
     * @var array
     */
    protected $config = array();

    /**
     * ���캯��
     */
    public function __construct(){
        parent::__construct();
        //��ʼ��
        $this->koeInit();
        global $in,$config,$db,$L;
        $this -> db  = $db;
        $this -> L 	 = $L;
        $this -> values['in'] = $this -> in = &$in;
        $this -> values['config'] = $this -> config = &$config;
        $this->tpl = BASE_PATH.'Application/'.$this->context['m'].'/View/'.$this->context['c'].'/';//����������
//        Util::dump($this->tpl);exit;
    }


    /**
     * ����ģ��
     * @param string $class
     */
    public function loadModel($class){
        $args = func_get_args();
        $this -> $class = call_user_func_array('init_model', $args);
        return $this -> $class;
    }

    /**
     * ��������ļ�
     * @param string $class
     */
    public function loadClass($class){
        if (1 === func_num_args()) {
            $this -> $class = new $class;
        } else {
            $reflectionObj = new \ReflectionClass($class);
            $args = func_get_args();
            array_shift($args);
            $this -> $class = $reflectionObj -> newInstanceArgs($args);
        }
        return $this -> $class;
    }

    /**
     * @param array|string $tpl_var
     * @param null $value
     * @param bool|false $nocache
     * @return null
     */
    public function assign($tpl_var, $value = NULL, $nocache = false){
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $_key => $_val) {
                if ($_key != '') {
                    $this->values[$_key] = $_val;
                }
            }
        } else {
            if ($tpl_var != '') {
                $this->values[$tpl_var] = $value;
            }
        }
        return $this;
    }

    /**
     * ��ʾģ��
     * @param string $template
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     */
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null){
//        global $L,$LNG;
        extract($this->values);
        require($this->tpl.$template);
    }

    public function koeInit(){
        //10min pathInfoMuti,search,upload,download...
        @set_time_limit(600);
        @ini_set('session.cache_expire',600);

        //��ȡ��Ŀ¼
        $web_root = str_replace(Util::path($_SERVER['SCRIPT_NAME']),'',KoeTool::P(dirname(dirname(__FILE__))).'/index.php').'/';
        if (substr($web_root,-10) == 'index.php/') {//���������������������
            $web_root = KoeTool::P($_SERVER['DOCUMENT_ROOT']).'/';
        }

        define('WEB_ROOT',$web_root);
        define('HOST', (KoeTool::isHTTPS() ? 'https://' :'http://').$_SERVER['HTTP_HOST'].'/');

        define('BASIC_PATH',    SYSTEM_PATH.'Projects/KodExplorer/');
        define('APPHOST',       HOST.str_replace(WEB_ROOT,'',BASIC_PATH));//�����Ŀ¼
        define('TEMPLATE',		BASIC_PATH .'template/');	//ģ���ļ�·��
        define('CONTROLLER_DIR',BASIC_PATH .'controller/'); //������Ŀ¼
        define('MODEL_DIR',		BASIC_PATH .'model/');		//ģ��Ŀ¼
        define('LIB_DIR',		BASIC_PATH .'lib/');		//��Ŀ¼
        define('FUNCTION_DIR',	LIB_DIR .'function/');		//������Ŀ¼
        define('CLASS_DIR',		LIB_DIR .'class/');			//��Ŀ¼
        define('CORER_DIR',		LIB_DIR .'core/');			//����Ŀ¼
        define('DATA_PATH',     BASIC_PATH .'data/');       //�û�����Ŀ¼
        define('LOG_PATH',      DATA_PATH .'log/');         //��־Ŀ¼
        define('USER_SYSTEM',   DATA_PATH .'system/');      //�û����ݴ洢Ŀ¼
        define('DATA_THUMB',    DATA_PATH .'thumb/');       //����ͼ���ɴ��
        define('LANGUAGE_PATH', DATA_PATH .'i18n/');        //������Ŀ¼
        define('STATIC_PATH',   BASIC_PATH.'static/');//��̬�ļ�Ŀ¼

        define('STATIC_JS','_dev');  //_dev(����״̬)||app(���ѹ��)
        define('STATIC_LESS','less');//less(����״̬)||css(���ѹ��)

//define('STATIC_PATH','http://static.kalcaddle.com/static/');//��̬�ļ�ͳ����,�ɵ�����static����CDN

        /*
         �����Զ��塾�û�Ŀ¼���͡�����Ŀ¼��;�Ƶ�webĿ¼֮�⣬
         ����ʹ�������ȫ, �Ͳ��������û�����չ��Ȩ����;
         */
        define('USER_PATH',     DATA_PATH .'User/');        //�û�Ŀ¼
//�Զ����û�Ŀ¼����Ҫ�Ƚ�data/User�Ƶ���ĵط� ���޸����ã����磺
//define('USER_PATH',   DATA_PATH .'/Library/WebServer/Documents/User');
        define('PUBLIC_PATH',   DATA_PATH .'public/');     //����Ŀ¼
//��������Ŀ¼,��дȨ�޸����û�Ŀ¼�Ķ�дȨ�� ���޸����ã����磺
//define('PUBLIC_PATH','/Library/WebServer/Documents/Public/');
        /*
         * office���������ã�Ĭ�ϵ��õ�΢��Ľӿڣ�������Ҫ����������
         * ���ز���weboffice ��������дoffice������������ַ ����:  http://---/view.aspx?src=
         */
        define('OFFICE_SERVER','');


        include(FUNCTION_DIR.'web.function.php');
        include(FUNCTION_DIR.'file.function.php');
        include(CLASS_DIR.'fileCache.class.php');
        include(CONTROLLER_DIR.'util.php');
        include(CORER_DIR.'Application.class.php');
        include(CORER_DIR.'Controller.class.php');
        include(CORER_DIR.'Model.class.php');
        include(FUNCTION_DIR.'common.function.php');
//        include(BASIC_PATH.'config/setting.php');
//        include(BASIC_PATH.'config/version.php');

        //���ݵ�ַ���塣
        $GLOBALS['config']['pic_thumb']	= BASIC_PATH.'data/thumb/';		// ����ͼ���ɴ�ŵ�ַ
        $GLOBALS['config']['cache_dir']	= BASIC_PATH.'data/cache/';		// �����ļ���ַ
        $GLOBALS['config']['app_startTime'] = KoeTool::mtime();         			//��ʼʱ��

        //ϵͳ��������
        $GLOBALS['config']['app_charset']	 ='utf-8';			//�ó�������ͳһ����
        $GLOBALS['config']['check_charset'] = 'ASCII,UTF-8,GBK';//�ļ����Զ�������
        //when edit a file ;check charset and auto converto utf-8;
        if (strtoupper(substr(PHP_OS, 0,3)) === 'WIN') {
            $GLOBALS['config']['system_os']='windows';
            $GLOBALS['config']['system_charset']='gbk';//user set your server system charset
        } else {
            $GLOBALS['config']['system_os']='linux';
            $GLOBALS['config']['system_charset']='utf-8';
        }

        $GLOBALS['in'] = WebTool::parseIncoming();
        @session_start();
        session_write_close();//����session��������;֮��Ҫ�޸�$_SESSION ��Ҫ�ȵ���session_start()
//        $GLOBALS['config']['autorun'] = array(
//            array('controller'=>'User','function'=>'loginCheck'),
//            array('controller'=>'user','function'=>'authCheck')
//        );

        $GLOBALS['config']['setting_all'] = array(
            'language' 		=> 'en:English,zh_CN:��������,zh_TW:���w����',
            'themeall'		=> 'default/:<b>areo blue</b>:default,simple/:<b>simple</b>:simple,metro/:<b>metro</b>:metro,metro/blue_:metro-blue:color,metro/leaf_:metro-green:color,metro/green_:metro-green+:color,metro/grey_:metro-grey:color,metro/purple_:metro-purple:color,metro/pink_:metro-pink:color,metro/orange_:metro-orange:color',
            'codethemeall'	=> 'chrome,clouds,crimson_editor,eclipse,github,solarized_light,tomorrow,xcode,ambiance,idle_fingers,monokai,pastel_on_dark,solarized_dark,tomorrow_night_blue,tomorrow_night_eighties',
            'wallall'		=> '1,2,3,4,5,6,7,8,9,10,11,12,13',
            'musicthemeall'	=> 'ting,beveled,kuwo,manila,mp3player,qqmusic,somusic,xdj',
            'moviethemeall'	=> 'webplayer,qqplayer,vplayer,tvlive,youtube'
        );

        //���û���ʼ������
        $GLOBALS['config']['setting_default'] = array(
            'list_type'			=> 'icon',		// list||icon
            'list_sort_field'	=> 'name',		// name||size||ext||mtime
            'list_sort_order'	=> 'up',		// asc||desc
            'theme'				=> 'simple/',	// app theme [default,simple,metro/,metro/black....]
            'codetheme'			=> 'clouds',	// code editor theme
            'wall'				=> '7',			// wall picture
            'musictheme'		=> 'mp3player',	// music player theme
            'movietheme'		=> 'webplayer'	// movie player theme
        );

        //��ʼ��ϵͳ����
        $GLOBALS['config']['setting_system_default'] = array(
            'system_password'	=> KoeTool::randString(10),
            'system_name'		=> 'KodExplorer',
            'system_desc'		=> '����â����.��Դ������',
            'path_hidden'		=> '.htaccess,.git,.DS_Store,.gitignore',//Ŀ¼�б����ص���
            'auto_login'		=> '1',			// �Ƿ��Զ���¼����¼�û�Ϊguest

            'first_in'			=> 'explorer',	// ��¼��Ĭ�Ͻ���[explorer desktop,editor]
            'new_user_app'		=> '365����,pptvֱ��,ps,qq����,�Ѻ�Ӱ��,ʱ��,����,ˮ������,������,�����̨,����̨,icloud',
            'new_user_folder'	=> 'download,music,image,desktop'
        );

        //��ʼ��Ĭ�ϲ˵�����
        $GLOBALS['config']['setting_menu_default'] = array(
            array('name'=>'desktop','type'=>'system','url'=>'index.php?desktop','target'=>'_self','use'=>'1'),
            array('name'=>'explorer','type'=>'system','url'=>'index.php?explorer','target'=>'_self','use'=>'1'),
            array('name'=>'editor','type'=>'system','url'=>'index.php?editor','target'=>'_self','use'=>'1'),
            array('name'=>'adminer','type'=>'','url'=>'./lib/plugins/adminer/','target'=>'_blank','use'=>'1')
        );

        //Ȩ�����ã���ȷ����Ҫ��Ȩ�޿��ƵĿ������ͷ���
        //��ҪȨ����֤��Action;root������Ȩ��
        $GLOBALS['config']['role_setting'] = array(
            'explorer'	=> array(
                'mkdir','mkfile','pathRname','pathDelete','zip','unzip','pathCopy','pathChmod',
                'pathCute','pathCuteDrag','pathCopyDrag','clipboard','pathPast','pathInfo',
                'serverDownload','fileUpload','search','pathDeleteRecycle',
                'fileDownload','zipDownload','fileDownloadRemove','fileProxy','makeFileProxy'),
            'app'		=> array('user_app','init_app','add','edit','del'),//
            'user'		=> array('changePassword'),//�������������˻�
            'editor'	=> array('fileGet','fileSave'),
            'userShare' => array('set','del'),
            'setting'	=> array('set','system_setting','php_info'),
            'fav'		=> array('add','del','edit'),
            'member'	=> array('get','add','del','edit'),
            'group'		=> array('get','add','del','edit'),
        );
        define('KOD_VERSION','3.12');
    }
}