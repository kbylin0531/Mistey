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
        if (substr($web_root,-10) == 'index.php/') {//解决部分主机不兼容问题
            $web_root = KoeTool::P($_SERVER['DOCUMENT_ROOT']).'/';
        }
        define('WEB_ROOT',$web_root);
        define('HOST', (KoeTool::isHttps() ? 'https://' :'http://').$_SERVER['HTTP_HOST'].'/');
        define('BASIC_PATH',    KoeTool::P(dirname(dirname(__FILE__))).'/');
        define('APPHOST',       HOST.str_replace(WEB_ROOT,'',BASIC_PATH));//程序根目录
        define('TEMPLATE',		BASIC_PATH .'template/');	//模版文件路径
        define('CONTROLLER_DIR',BASIC_PATH .'controller/'); //控制器目录
        define('MODEL_DIR',		BASIC_PATH .'model/');		//模型目录
        define('LIB_DIR',		BASIC_PATH .'lib/');		//库目录
        define('FUNCTION_DIR',	LIB_DIR .'function/');		//函数库目录
        define('CLASS_DIR',		LIB_DIR .'class/');			//内目录
        define('CORER_DIR',		LIB_DIR .'core/');			//核心目录
        define('DATA_PATH',     BASIC_PATH .'data/');       //用户数据目录
        define('LOG_PATH',      DATA_PATH .'log/');         //日志目录
        define('USER_SYSTEM',   DATA_PATH .'system/');      //用户数据存储目录
        define('DATA_THUMB',    DATA_PATH .'thumb/');       //缩略图生成存放
        define('LANGUAGE_PATH', DATA_PATH .'i18n/');        //多语言目录

        define('STATIC_JS','app');  //_dev(开发状态)||app(打包压缩)
        define('STATIC_LESS','css');//less(开发状态)||css(打包压缩)
        define('STATIC_PATH',"./static/");//静态文件目录
        /*
         可以自定义【用户目录】和【公共目录】;移到web目录之外，
         可以使程序更安全, 就不用限制用户的扩展名权限了;
         */
        define('USER_PATH',     DATA_PATH .'User/');        //用户目录
        //自定义用户目录；需要先将data/User移到别的地方 再修改配置，例如：
        //define('USER_PATH',   DATA_PATH .'/Library/WebServer/Documents/User');
        define('PUBLIC_PATH',   DATA_PATH .'public/');     //公共目录
        //公共共享目录,读写权限跟随用户目录的读写权限 再修改配置，例如：
        //define('PUBLIC_PATH','/Library/WebServer/Documents/Public/');
        /*
         * office服务器配置；默认调用的微软的接口，程序需要部署到外网。
         * 本地部署weboffice 引号内填写office解析服务器地址 形如:  http://---/view.aspx?src=
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

        //数据地址定义。
        $this->_config['pic_thumb']	= BASIC_PATH.'data/thumb/';		// 缩略图生成存放地址
        $this->_config['cache_dir']	= BASIC_PATH.'data/cache/';		// 缓存文件地址
        $this->_config['app_startTime'] = KoeTool::mtime();         			//起始时间

        //系统编码配置
        $this->_config['app_charset']	 ='utf-8';			//该程序整体统一编码
        $this->_config['check_charset'] = 'ASCII,UTF-8,GBK';//文件打开自动检测编码
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
        session_write_close();//避免session锁定问题;之后要修改$_SESSION 需要先调用session_start()
        $this->_config['autorun'] = array(
            array('controller'=>'user','function'=>'loginCheck'),
            array('controller'=>'user','function'=>'authCheck')
        );
        // 配置项可选值
        $this->_config['setting_all'] = array(
            'language' 		=> "en:English,zh_CN:简体中文,zh_TW:繁體中文",
            'themeall'		=> "default/:<b>areo blue</b>:default,simple/:<b>simple</b>:simple,metro/:<b>metro</b>:metro,metro/blue_:metro-blue:color,metro/leaf_:metro-green:color,metro/green_:metro-green+:color,metro/grey_:metro-grey:color,metro/purple_:metro-purple:color,metro/pink_:metro-pink:color,metro/orange_:metro-orange:color",
            'codethemeall'	=> "chrome,clouds,crimson_editor,eclipse,github,solarized_light,tomorrow,xcode,ambiance,idle_fingers,monokai,pastel_on_dark,solarized_dark,tomorrow_night_blue,tomorrow_night_eighties",
            'wallall'		=> "1,2,3,4,5,6,7,8,9,10,11,12,13",
            'musicthemeall'	=> "ting,beveled,kuwo,manila,mp3player,qqmusic,somusic,xdj",
            'moviethemeall'	=> "webplayer,qqplayer,vplayer,tvlive,youtube"
        );

        //新用户初始化配置
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

        //初始化系统配置
        $this->_config['setting_system_default'] = array(
            'system_password'	=> KoeTool::rand_string(10),
            'system_name'		=> "KodExplorer",
            'system_desc'		=> "——芒果云.资源管理器",
            'path_hidden'		=> ".htaccess,.git,.DS_Store,.gitignore",//目录列表隐藏的项
            'auto_login'		=> "1",			// 是否自动登录；登录用户为guest
            'first_in'			=> "explorer",	// 登录后默认进入[explorer desktop,editor]
            'new_user_app'		=> "365日历,pptv直播,ps,qq音乐,搜狐影视,时钟,天气,水果忍者,计算器,豆瓣电台,音悦台,icloud",
            'new_user_folder'	=> "download,music,image,desktop"
        );

        //初始化默认菜单配置
        $this->_config['setting_menu_default'] = array(
            array('name'=>'desktop','type'=>'system','url'=>'index.php?desktop','target'=>'_self','use'=>'1'),
            array('name'=>'explorer','type'=>'system','url'=>'index.php?explorer','target'=>'_self','use'=>'1'),
            array('name'=>'editor','type'=>'system','url'=>'index.php?editor','target'=>'_self','use'=>'1'),
            array('name'=>'adminer','type'=>'','url'=>'./lib/plugins/adminer/','target'=>'_blank','use'=>'1')
        );

        //权限配置；精确到需要做权限控制的控制器和方法
        //需要权限认证的Action;root组无视权限
        $this->_config['role_setting'] = array(
            'explorer'	=> array(
                'mkdir','mkfile','pathRname','pathDelete','zip','unzip','pathCopy','pathChmod',
                'pathCute','pathCuteDrag','pathCopyDrag','clipboard','pathPast','pathInfo',
                'serverDownload','fileUpload','search','pathDeleteRecycle',
                'fileDownload','zipDownload','fileDownloadRemove','fileProxy','makeFileProxy'),
            'app'		=> array('user_app','init_app','add','edit','del'),//
            'user'		=> array('changePassword'),//可以设立公用账户
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