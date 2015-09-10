<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 20:14
 */
namespace Application\Controller;
use System\Core\Controller;
use Utils\Koe\KoeTool;
use Utils\Koe\WebTool;

class IndexController extends Controller{

    protected $_config = array();

    public function __contruct(){
        self::config();
    }

    public function config(){
        @date_default_timezone_set(@date_default_timezone_get());
        @set_time_limit(600);//10min pathInfoMuti,search,upload,download...
        @ini_set('session.cache_expire',600);
        @ini_set("display_errors","on");
        @error_reporting(E_ERROR|E_WARNING|E_PARSE);

        $web_root = str_replace(KoeTool::P($_SERVER['SCRIPT_NAME']),'',KoeTool::P(dirname(dirname(__FILE__))).'/index.php').'/';
        if (substr($web_root,-10) == 'index.php/') {//���������������������
            $web_root = KoeTool::P($_SERVER['DOCUMENT_ROOT']).'/';
        }
        define('WEB_ROOT',$web_root);
        define('HOST', (KoeTool::isHttps() ? 'https://' :'http://').$_SERVER['HTTP_HOST'].'/');
        define('BASIC_PATH',    KoeTool::P(dirname(dirname(__FILE__))).'/');
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

        define('STATIC_JS','app');  //_dev(����״̬)||app(���ѹ��)
        define('STATIC_LESS','css');//less(����״̬)||css(���ѹ��)
        define('STATIC_PATH',"./static/");//��̬�ļ�Ŀ¼
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
        define('OFFICE_SERVER',"");

        include(FUNCTION_DIR.'web.function.php');
        include(FUNCTION_DIR.'file.function.php');
        include(CLASS_DIR.'fileCache.class.php');
        include(CONTROLLER_DIR.'util.php');
        include(CORER_DIR.'Application.class.php');
        include(CORER_DIR.'Controller.class.php');
        include(CORER_DIR.'Model.class.php');
        include(FUNCTION_DIR.'common.function.php');
        include(BASIC_PATH.'config/setting.php');
        include(BASIC_PATH.'config/version.php');

        //���ݵ�ַ���塣
        $this->_config['pic_thumb']	= BASIC_PATH.'data/thumb/';		// ����ͼ���ɴ�ŵ�ַ
        $this->_config['cache_dir']	= BASIC_PATH.'data/cache/';		// �����ļ���ַ
        $this->_config['app_startTime'] = KoeTool::mtime();         			//��ʼʱ��

        //ϵͳ��������
        $this->_config['app_charset']	 ='utf-8';			//�ó�������ͳһ����
        $this->_config['check_charset'] = 'ASCII,UTF-8,GBK';//�ļ����Զ�������
        //when edit a file ;check charset and auto converto utf-8;
        if (strtoupper(substr(PHP_OS, 0,3)) === 'WIN') {
            $this->_config['system_os']='windows';
            $this->_config['system_charset']='gbk';//user set your server system charset
        } else {
            $this->_config['system_os']='linux';
            $this->_config['system_charset']='utf-8';
        }

        $in = WebTool::parse_incoming();
        @session_start();
        session_write_close();//����session��������;֮��Ҫ�޸�$_SESSION ��Ҫ�ȵ���session_start()
        $this->_config['autorun'] = array(
            array('controller'=>'user','function'=>'loginCheck'),
            array('controller'=>'user','function'=>'authCheck')
        );
        // �������ѡֵ
        $this->_config['setting_all'] = array(
            'language' 		=> "en:English,zh_CN:��������,zh_TW:���w����",
            'themeall'		=> "default/:<b>areo blue</b>:default,simple/:<b>simple</b>:simple,metro/:<b>metro</b>:metro,metro/blue_:metro-blue:color,metro/leaf_:metro-green:color,metro/green_:metro-green+:color,metro/grey_:metro-grey:color,metro/purple_:metro-purple:color,metro/pink_:metro-pink:color,metro/orange_:metro-orange:color",
            'codethemeall'	=> "chrome,clouds,crimson_editor,eclipse,github,solarized_light,tomorrow,xcode,ambiance,idle_fingers,monokai,pastel_on_dark,solarized_dark,tomorrow_night_blue,tomorrow_night_eighties",
            'wallall'		=> "1,2,3,4,5,6,7,8,9,10,11,12,13",
            'musicthemeall'	=> "ting,beveled,kuwo,manila,mp3player,qqmusic,somusic,xdj",
            'moviethemeall'	=> "webplayer,qqplayer,vplayer,tvlive,youtube"
        );

        //���û���ʼ������
        $this->_config['setting_default'] = array(
            'list_type'			=> "icon",		// list||icon
            'list_sort_field'	=> "name",		// name||size||ext||mtime
            'list_sort_order'	=> "up",		// asc||desc
            'theme'				=> "simple/",	// app theme [default,simple,metro/,metro/black....]
            'codetheme'			=> "clouds",	// code editor theme
            'wall'				=> "7",			// wall picture
            'musictheme'		=> "mp3player",	// music player theme
            'movietheme'		=> "webplayer"	// movie player theme
        );

        //��ʼ��ϵͳ����
        $this->_config['setting_system_default'] = array(
            'system_password'	=> KoeTool::rand_string(10),
            'system_name'		=> "KodExplorer",
            'system_desc'		=> "����â����.��Դ������",
            'path_hidden'		=> ".htaccess,.git,.DS_Store,.gitignore",//Ŀ¼�б����ص���
            'auto_login'		=> "1",			// �Ƿ��Զ���¼����¼�û�Ϊguest
            'first_in'			=> "explorer",	// ��¼��Ĭ�Ͻ���[explorer desktop,editor]
            'new_user_app'		=> "365����,pptvֱ��,ps,qq����,�Ѻ�Ӱ��,ʱ��,����,ˮ������,������,�����̨,����̨,icloud",
            'new_user_folder'	=> "download,music,image,desktop"
        );

        //��ʼ��Ĭ�ϲ˵�����
        $this->_config['setting_menu_default'] = array(
            array('name'=>'desktop','type'=>'system','url'=>'index.php?desktop','target'=>'_self','use'=>'1'),
            array('name'=>'explorer','type'=>'system','url'=>'index.php?explorer','target'=>'_self','use'=>'1'),
            array('name'=>'editor','type'=>'system','url'=>'index.php?editor','target'=>'_self','use'=>'1'),
            array('name'=>'adminer','type'=>'','url'=>'./lib/plugins/adminer/','target'=>'_blank','use'=>'1')
        );

        //Ȩ�����ã���ȷ����Ҫ��Ȩ�޿��ƵĿ������ͷ���
        //��ҪȨ����֤��Action;root������Ȩ��
        $this->_config['role_setting'] = array(
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