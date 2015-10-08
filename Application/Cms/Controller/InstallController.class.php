<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/16
 * Time: 12:09
 */
namespace Application\Cms\Controller;
use Application\Cms\Model\InstallModel;
use Application\Cms\Model\MemberModel;
use System\Core\Configer;
use System\Core\Controller;
use System\Core\Storage;
use System\Util\SEK;
use System\Util\SessionUtil;
use System\Utils\Util;

/**
 * Class InstallController CMS安装控制器
 * @package Application\Cms\Controller
 */
class InstallController extends Controller{
    /**
     * 安装锁的路径
     * 锁文件存在的情况下无法进行安装
     * @var string
     */
    private static $lock_path = null;
    /**
     * 安装步骤
     * @var array
     */
    private static $steps = array(
        0   => 'Cms/install/index',
        1   => 'Cms/install/first',
        2   => 'Cms/install/second',
        3   => 'Cms/install/third',
        4   => 'Cms/install/complete'
    );

    public function __construct(){
        parent::__construct();
        self::$lock_path = BASE_PATH.'Data/CMS/install.lock';
        if(Storage::has(self::$lock_path)){
            $this->error('CMS已经安装完毕!');
        }
        //静态可以直接访问的文件目录
        defined('URL_CMS_STATIC_PATH') or define('URL_CMS_STATIC_PATH',URL_PUBLIC_PATH.'CMS/');

    }

    /**
     * 协议页面
     */
    public function index(){
        $this->display();
    }

    /**
     * 第一步操作页面
     */
    public function first(){
        $env = $this->checkEnv();
        $funcs = $this->checkFunc();
        $dirfile = $this->checkDirfile();

        SessionUtil::set('step', 1);

        $this->assign('env',$env);
        $this->assign('dirfile', $dirfile);
        $this->assign('funcs',$funcs);
        $this->display();
    }

    /**
     * 数据库配置
     * @param array $db 数据库连接配置
     * @param array $admin 数据库管理员配置
     * @return void
     * @throws \Exception
     */
    public function second($db = null, $admin = null){
        if(IS_POST){
            //检测管理员配置
            if(SEK::checkInvalidValueExistInStrict(true,
                !is_array($admin) ,
                empty($admin[0]) , //名称
                empty($admin[1]),//密码 2-密码确认
                empty($admin[3])//邮箱
            )){
                //任意一项为空
                $this->error('请填写完整管理员信息');
            }else{
                //检测密码
                if($admin[1] !== $admin[2]) $this->error('确认密码和密码不一致');
                //保存信息
                $info = array();
                list($info['username'], $info['password'], $info['repassword'], $info['email']) = $admin;
                //缓存管理员信息
                SessionUtil::set('admin_info', $info);
            }

            //检测数据库配置
            if(SEK::checkInvalidValueExistInStrict(true,
                !is_array($db), empty($db[0]),empty($db[1]) , empty($db[2]) , empty($db[3])) ){
                //任意一项为空
                $this->error('请填写完整的数据库配置');
            }else{
                $config = array();
                list($config['type'], $config['host'], $config['dbname'], $config['username'],
                    $config['password'],$config['port'],$config['prefix']) = $db;
                $config['prefix'] = 'ot_';
                //缓存数据库配置
                SessionUtil::set('database_info', $config);
                //创建数据库
                $installModel = new InstallModel($config,false);
                if(!$installModel->createDatabase($config['dbname'])){
                    $this->error($installModel->getErrorInfo());
                }else{
                    //成功创建数据库，写入配置信息
                    if(!Configer::writeAuto('cms',$config)){
                        throw new \Exception('Store Configure into file failed!');
                    }
                }
            }
            //跳转到数据库安装页面
            $this->takeSteps();
        }

        //检查并设置session
        if(SessionUtil::get('error')){
            $this->error('环境检测没有通过，请调整环境后重试！');
        }
        $step = SessionUtil::get('step');
        if($step !== 1 && $step !== 2){
            $this->takeSteps(false);
        }
        SessionUtil::set('step', 2);

        $this->display();
    }

    /**
     * 实际安装数据库表和管理员账号设置
     * @return void
     */
    public function third(){
        if(SessionUtil::get('step') != 2){
            $this->takeSteps(2);
        }
        $this->display();

        //创建数据表
        $this->createTables();

        Util::flushMessageToClient('开始注册创始人帐号...');
        $dbconfig = SessionUtil::get('database_info');
        $memberModel = new MemberModel();
        $memberModel->init($dbconfig);
        $admin = SessionUtil::get('admin_info');
        $db_config = SessionUtil::get('database_info');
        $rst = $memberModel->registerMember($admin,$db_config['prefix']);
        if(is_string($rst) or !$rst){
            Util::flushMessageToClient('创始人帐号注册失败！'.$rst);
        }else{
            Util::flushMessageToClient('创始人帐号注册完成！');
        }
//        $this->registerAdmin(SessionUtil::get('admin_info'));

        if(SessionUtil::get('error')){
            //show_msg();
            Util::flushMessageToClient('安装错误！');
        } else {
            SessionUtil::set('step', 3);
            Storage::write(self::$lock_path,'Install complete!');
            $this->redirect('cms/install/complete');
        }
    }

    /**
     * 完成显示页面
     * @throws \System\Exception\ParameterInvalidException
     */
    public function complete(){
        $step = SessionUtil::get('step');
        if(!$step){
            $this->redirect('cms/install/index');
        }
//        elseif($step != 3) {
//            $this->redirect("Install/step{$step}");
//        }
        // 写入安装锁定文件
        Storage::write(self::$lock_path, 'lock');
        if(!SessionUtil::get('update')){
            //创建配置文件
            $this->assign('info',SessionUtil::get('config_file'));
        }
        SessionUtil::clear('step');
        SessionUtil::clear('error');
        SessionUtil::clear('update');
        $this->display();
    }

    /**
     * 步骤跳转
     * @param bool|true $forward true表示进入下一步，false表示返回上一步，int类型表示跳到制定的步骤
     */
    private function takeSteps($forward=true){
        if(is_bool($forward)){
            $curstep = SessionUtil::get('step');
            $forward? ++$curstep: --$curstep;
        }else{
            $curstep = $forward;
        }
        $this->redirect(self::$steps[$curstep]);
    }

    /**
     * 创建数据表
     */
    private function createTables(){
        $dbconfig = SessionUtil::get('database_info');
        $installModel = new InstallModel($dbconfig);
        //读取SQL文件
        $sqls = Storage::read(BASE_PATH.'Data/CMS/install.sql');
        //设置前缀
        $sqls = str_replace(' `onethink_'," `{$dbconfig['prefix']}",  $sqls);
//        Util::dump($sqls);exit;
        $sqls = str_replace("\r", "\n", $sqls);//windows下转化换行符
        $sqls = explode(";\n", $sqls);


//        Util::dump($dbconfig);exit;
        //开始安装
        Util::flushMessageToClient('开始安装数据库...');
        foreach ($sqls as $sql) {
            $sql = trim($sql);
            if(empty($sql) or substr($sql,0,2) === '--') continue;

            $msg = $installModel->execSql($sql);
            if(is_array($msg)){
                if(!$msg[0]){
                    SessionUtil::set('error',true);
                }
                Util::flushMessageToClient($msg[1]);
            }
        }
    }

    /**
     * 函数、扩展、方法的检测
     * @return array 检测数据
     */
    private function checkFunc(){
        $items = array(
            array('pdo',    '支持',   'success',  '类'),
            array('pdo_mysql',  '支持',   'success',  '模块'),
            array('file_get_contents',  '支持',   'success',  '函数'),
            array('mb_strlen',		   '支持',    'success',  '函数'),
        );

        foreach ($items as &$val) {
            if(('类'==$val[3] && !class_exists($val[0]))
                || ('模块'==$val[3] && !extension_loaded($val[0]))
                || ('函数'==$val[3] && !function_exists($val[0]))
            ){
                $val[1] = '不支持';
                $val[2] = 'error';
                SessionUtil::set('error', true);
            }
        }
        return $items;
    }

     /**
     * 目录，文件读写检测
     * @return array 检测数据
     */
    private function checkDirfile(){
        $items = array(
            //文件类型、所需状态、检测结果、目录名称(不完整)
            array('dir',  '可写', 'success', 'Data/CMS/'),
        );

        //目录检测
        foreach ($items as &$val) {
            $item =	BASE_PATH . $val[3];
            if('dir' == $val[0]){//如果是目录
                if(!is_writable($item)) {
                    if(is_dir($item)) {
                        $val[1] = '可读';
                        $val[2] = 'error';
                        SessionUtil::set('error', true);
                    } else {
                        $val[1] = '不存在';
                        $val[2] = 'error';
                        SessionUtil::set('error', true);
                    }
                }
            }else{//如果是文件
                if(file_exists($item)) {
                    if(!is_writable($item)) {
                        $val[1] = '不可写';
                        $val[2] = 'error';
                        SessionUtil::set('error', true);
                    }
                } else {
                    if(!is_writable(dirname($item))) {
                        $val[1] = '不存在';
                        $val[2] = 'error';
                        SessionUtil::set('error', true);
                    }
                }
            }
        }
        return $items;
    }

    /**
     * 运行环境检测
     * @return array
     */
    private function checkEnv(){
        $items = array(
            //检测相名称、所需配置、？、当前详细信息、检测结果
            'os'      => array('操作系统', '不限制', '类Unix', PHP_OS, 'success'),
            'php'     => array('PHP版本', '5.3', '5.3+', PHP_VERSION, 'success'),
            'upload'  => array('附件上传', '不限制', '2M+', '未知', 'success'),
            'gd'      => array('GD库', '2.0', '2.0+', '未知', 'success'),
            'disk'    => array('磁盘空间', '5M', '不限制', '未知', 'success'),
        );

        //PHP环境检测
        if($items['php'][3] < $items['php'][1]){
            $items['php'][4] = 'error';
            SessionUtil::set('error', true);
        }

        //附件上传检测
        if(@ini_get('file_uploads')){
            $items['upload'][3] = ini_get('upload_max_filesize');
        }else{
            //配置项目不存在
            SessionUtil::set('error', true);
        }

        //GD库检测
        $tmp = function_exists('gd_info') ? gd_info() : array();
        if(empty($tmp['GD Version'])){
            $items['gd'][3] = '未安装';
            $items['gd'][4] = 'error';
            SessionUtil::set('error', true);
        } else {
            $items['gd'][3] = $tmp['GD Version'];
        }
        unset($tmp);

        //磁盘空间检测
        if(function_exists('disk_free_space')) {
            $items['disk'][3] = SEK::byteFormat(disk_free_space(BASE_PATH));
        }
        return $items;
    }

}