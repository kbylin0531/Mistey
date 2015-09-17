<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/16
 * Time: 12:09
 */
namespace Application\Cms\Controller;
use System\Core\Controller;
use System\Core\Dao;
use System\Core\Storage;
use System\Utils\SessionUtil;
use System\Utils\Util;

class InstallController extends Controller{

    private static $lock_path = null;

    public function __construct(){
        parent::__construct();
        self::$lock_path = BASE_PATH.'Data/install.lock';
        if(Storage::hasFile(self::$lock_path)){
            $this->error('CMS已经安装完毕!');
        }

        //静态文件目录
        defined('URL_STATIC_PATH') or define('URL_STATIC_PATH',URL_PUBLIC_PATH.'CMS/');
        defined('URL_CMS_STATIC_PATH') or define('URL_CMS_STATIC_PATH',URL_PUBLIC_PATH.'CMS/');


    }

    /**
     * 协议页面
     */
    public function index(){
        $this->assign('action',true);
        $this->display();
    }

    /**
     * 第一步操作页面
     */
    public function first(){
        $env = $this->checkEnv();
        $dirfile = $this->checkDirfile();
        $funcs = $this->checkFunc();

        SessionUtil::set('step', 1);

        $this->assign('action',true);
        $this->assign('env',$env);
        $this->assign('dirfile', $dirfile);
        $this->assign('funcs',$funcs);
        $this->assign(array(
            'prev_url'  => util::url('Cms/install/index'),
            'next_url'  => util::url('Cms/install/second'),
        ));
        $this->display();
    }

    /**
     * 数据库配置
     * @param array $db 数据库连接配置
     * @param array $admin 数据库管理员配置
     * @return void
     */
    public function second($db = null, $admin = null){
        if(IS_POST){
            //检测管理员信息
            if(Util::checkInvalidExistInStrict(true,
                !is_array($admin) ,
                empty($admin[0]) , //名称
                empty($admin[1]),//密码 2-密码确认
                empty($admin[3])//邮箱
            ) ){
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
            if(Util::checkInvalidExistInStrict(true,
                !is_array($db), empty($db[0]),empty($db[1]) , empty($db[2]) , empty($db[3])) ){
                //任意一项为空
                $this->error('请填写完整的数据库配置');
            }else{
                $config = array();
                list($config['type'], $config['host'], $config['dbname'], $config['username'],
                    $config['password'],$config['port'],$config['prefix']) = $db;
                //缓存数据库配置
                SessionUtil::set('database_info', $config);
                //创建数据库
                $dao  = new Dao($config);
                $rst = $dao->createDatabase(htmlspecialchars($config['dbname']));
                if(is_string($rst)){
                    //数据库创建失败
                    $this->error($rst);
                }
            }
            //跳转到数据库安装页面
            $this->redirect('Cms/install/third');
        }


        if(SessionUtil::get('error')){
            $this->error('环境检测没有通过，请调整环境后重试！');
        }
        $step = SessionUtil::get('step');
        if($step != 1 && $step != 2){
            $this->redirect('Cms/install/first');
        }
        SessionUtil::set('step', 2);

        $self_url = Util::url('cms/install/second');
        $prev_url = Util::url('cms/install/first');
        $this->assign('self_url',$self_url);
        $this->assign('prev_url',$prev_url);
        $this->display();
    }

    public function third(){
        if(SessionUtil::get('step') != 2){
            $this->redirect('Cms/install/second');
        }
        $this->display();

        $dbcondig = SessionUtil::get('database_info');
        $dao = new Dao($dbcondig);

        //创建数据表
        $this->createTables($dao);
        $this->registerAdmin($dao,SessionUtil::get('admin_info'));
    }

    /**
     * @param Dao $dao
     * @param $admin
     */
    private function registerAdmin($dao,$admin){
        Util::flushMessageToClient('开始注册创始人帐号...');
        $sql = "INSERT INTO `ucenter_member` VALUES " .
            "('1', '[NAME]', '[PASS]', '[EMAIL]', '', '[TIME]', '[IP]', 0, 0, '[TIME]', '1')";

        $password = Util::pwd($admin['password']);
        $sql = str_replace(
            array( '[NAME]', '[PASS]', '[EMAIL]', '[TIME]', '[IP]'),
            array( $admin['username'], $password, $admin['email'], $_SERVER['REQUEST_TIME'], get_client_ip(1)),
            $sql);
        //执行sql
        $dao->exec($sql);

        $sql = "INSERT INTO `member` VALUES ".
            "('1', '[NAME]', '0', '0000-00-00', '', '0', '1', '0', '[TIME]', '0', '[TIME]', '1');";
        $sql = str_replace(
            array('[NAME]', '[TIME]'),
            array( $admin['username'], $_SERVER['REQUEST_TIME']),
            $sql);
        $dao->exec($sql);
        Util::flushMessageToClient('创始人帐号注册完成！');
    }

    private function createTables($dao){
        //读取SQL文件
        $sqls = file_get_contents(BASE_PATH.'Data/CMS/install.sql');
        $sqls = str_replace("\r", "\n", $sqls);//windows下转化换行符
        $sqls = explode(";\n", $sqls);

        //开始安装
        Util::flushMessageToClient('开始安装数据库...');
        foreach ($sqls as $sql) {
            $sql = trim($sql);
            if(empty($sql)) continue;
            if(strtoupper(substr($sql, 0, 12)) == 'CREATE TABLE') {
                $name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $sql);
                $msg  = "创建数据表{$name}";
                if($dao->exec($sql)){
                    Util::flushMessageToClient($msg . '...成功');
                } else {
                    Util::flushMessageToClient($msg . '...失败！', 'error');
                    SessionUtil::set('error', true);
                }
            } else {
                $dao->exec($sql);
            }
        }
    }

    /**
     * 函数检测
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

    private /**
     * 目录，文件读写检测
     * @return array 检测数据
     */
    function checkDirfile(){
        $items = array(
            array('dir',  '可写', 'success', 'Data/CMS/'),
        );

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
            } else {//如果是文件
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

    private function checkEnv(){
    $items = array(
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
    if(@ini_get('file_uploads'))
        $items['upload'][3] = ini_get('upload_max_filesize');

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
        $items['disk'][3] = floor(disk_free_space(BASE_PATH) / (1024*1024)).'M';
    }

    return $items;
}

}