<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 21:28
 */
namespace Utils\Koe;

/**
 * ϵͳ������				filesize(),file_exists(),pathinfo(),rname(),unlink(),filemtime(),is_readable(),is_wrieteable();
 * ��ȡ�ļ���ϸ��Ϣ		file_info($file_name)
 * ��ȡ�ļ�����ϸ��Ϣ		path_info($dir)
 * �ݹ��ȡ�ļ�����Ϣ		path_info_more($dir,&$file_num=0,&$path_num=0,&$size=0)
 * ��ȡ�ļ������ļ��б�	path_list($dir)
 * ·����ǰ�ļ�[��]��		get_path_this($path)
 * ��ȡ·����Ŀ¼			get_path_father($path)
 * ɾ���ļ�				del_file($file)
 * �ݹ�ɾ���ļ���			del_dir($dir)
 * �ݹ鸴���ļ���			copy_dir($source, $dest)
 * ����Ŀ¼				mk_dir($dir, $mode = 0777)
 * �ļ���С��ʽ��			size_format($bytes, $precision = 2)
 * �ж��Ƿ����·��		path_is_absolute( $path )
 * ��չ�����ļ�����		ext_type($ext)
 * �ļ�����				file_download($file)
 * �ļ����ص�������		file_download_this($from, $file_name)
 * ��ȡ�ļ�(��)Ȩ��		get_mode($file)  //rwx_rwx_rwx [�ļ�����Ҫϵͳ����]
 * �ϴ��ļ�(���������)	upload($fileInput, $path = './');//
 * ��ȡ�����ļ���			get_config($file, $ini, $type="string")
 * �޸������ļ���			update_config($file, $ini, $value,$type="string")
 * д��־��LOG_PATH��		write_log('dd','default|.�Խ�Ŀ¼.','log|error|warning|debug|info|db')
 *
 * Class FileTool �ļ���������
 * @package Utils
 */
class FileTool{

    /**
     * �������Ϊ�������ʱ���д��������ó�����룬
     * �������û�к�����޹�ʱ������ʱ�����ϵͳ���롣
     * @param $str
     * @return string
     */
    public static function iconv_app($str){
        global $config;
        $result = iconv($config['system_charset'], $config['app_charset'], $str);
        if (strlen($result)==0) {
            $result = $str;
        }
        return $result;
    }

    /**
     * @param $str
     * @return string
     */
    public static function iconv_system($str){
        global $config;
        $result = iconv($config['app_charset'], $config['system_charset'], $str);
        if (strlen($result)==0) {
            $result = $str;
        }
        return $result;
    }

    /**
     * @param $path
     * @return int
     */
    public static function get_filesize($path){
        // ĳЩ�����filesize�����
        @$ret = abs(sprintf("%u",filesize($path)));
        return (int)$ret;}
    /**
     * ��ȡ�ļ���ϸ��Ϣ
     * �ļ����ӳ������ת����ϵͳ����,����utf8��ϵͳ������ҪΪgbk
     * @param $path
     * @return array
     */
    public static function file_info($path){
        $name = self::get_path_this($path);
        $size = self::get_filesize($path);
        $info = array(
            'name'			=> self::iconv_app($name),
            'path'			=> self::iconv_app(self::get_path_father($path)),
            'ext'			=> self::get_path_ext($path),
            'type' 			=> 'file',
            'mode'			=> self::get_mode($path),
            'atime'			=> fileatime($path), //������ʱ��
            'ctime'			=> filectime($path), //����ʱ��
            'mtime'			=> filemtime($path), //����޸�ʱ��
            'is_readable'	=> intval(is_readable($path)),
            'is_writeable'	=> intval(is_writeable($path)),
            'size'			=> $size,
            'size_friendly'	=> self::size_format($size, 2)
        );
        return $info;
    }
    /**
     * ��ȡ�ļ���ϸ��Ϣ
     * @param $path
     * @return array
     */
    public static function folder_info($path){
        $info = array(
            'name'			=> self::iconv_app(self::get_path_this($path)),
            'path'			=> self::iconv_app(self::get_path_father($path)),
            'type' 			=> 'folder',
            'mode'			=> self::get_mode($path),
            'atime'			=> fileatime($path), //����ʱ��
            'ctime'			=> filectime($path), //����ʱ��
            'mtime'			=> filemtime($path), //����޸�ʱ��
            'is_readable'	=> intval(is_readable($path)),
            'is_writeable'	=> intval(is_writeable($path))
        );
        return $info;
    }


    /**
     * ��ȡһ��·��(�ļ���&�ļ�) ��ǰ�ļ�[��]��
     * test/11/ ==>11 test/1.c  ==>1.c
     * @param $path
     * @return string
     */
    public static function get_path_this($path){
        $path = str_replace('\\','/', rtrim(trim($path),'/'));
        return substr($path,strrpos($path,'/')+1);
    }
    /**
     * ��ȡһ��·��(�ļ���&�ļ�) ��Ŀ¼
     * /test/11/==>/test/   /test/1.c ==>/www/test/
     * @param $path
     * @return string
     */
    public static function get_path_father($path){
        $path = str_replace('\\','/', rtrim(trim($path),'/'));
        return substr($path, 0, strrpos($path,'/')+1);
    }
    /**
     * ��ȡ��չ��
     * @param $path
     * @return string
     */
    public static function get_path_ext($path){
        $name = self::get_path_this($path);
        $ext = '';
        if(strstr($name,'.')){
            $ext = substr($name,strrpos($name,'.')+1);
            $ext = strtolower($ext);
        }
        if (strlen($ext)>3 && preg_match("/([\x81-\xfe][\x40-\xfe])/", $ext, $match)) {
            $ext = '';
        }
        return $ext;
    }

    /**
     * �Զ���ȡ���ظ��ļ�(��)��
     * �������$file_add ����������Զ�������  a.txt Ϊa{$file_add}.txt
     * @param $path
     * @param string $file_add
     * @return string
     */
    public static function get_filename_auto($path,$file_add = ""){
        $i=1;
        $father = self::get_path_father($path);
        $name =  self::get_path_this($path);
        $ext = self::get_path_ext($name);
        if (strlen($ext)>0) {
            $ext='.'.$ext;
            $name = substr($name,0,strlen($name)-strlen($ext));
        }
        while(file_exists($path)){
            if (isset($file_add) && $file_add != '') {
                $path = $father.$name.$file_add.$ext;
//                $file_add.'-';
            }else{
                $path = $father.$name.'('.$i.')'.$ext;
                $i++;
            }
        }
        return $path;
    }

    /**
     * �ж��ļ����Ƿ��д
     * @param $path
     * @return bool
     */
    public static function path_writable($path) {
        $file = $path.'/test'.time().'.txt';
        $dir  = $path.'/test'.time();
        if(@is_writable($path) && @touch($file) && @unlink($file)) return true;
        if(@mkdir($dir,0777) && @rmdir($dir)) return true;
        return false;
    }

    /**
     * ��ȡ�ļ�����ϸ��Ϣ,�ļ�������ʱ���ã��������ļ����������ļ��������ܴ�С
     * @param $path
     * @return array
     */
    public static function path_info($path){
        //if (!is_dir($path)) return false;
        $pathinfo = self::_path_info_more($path);//��Ŀ¼�ļ���Сͳ����Ϣ
        $folderinfo = self::folder_info($path);
        return array_merge($pathinfo,$folderinfo);
    }

    /**
     * ��������Ƿ�Ϸ�
     * @param $path
     * @return bool
     */
    public static function path_check($path){
        $check = array('/','\\',':','*','?','"','<','>','|');
        $path = rtrim($path,'/');
        $path = self::get_path_this($path);
        foreach ($check as $v) {
            if (strstr($path,$v)) {
                return false;
            }
        }
        return true;
    }

    /**
     * �ݹ��ȡ�ļ�����Ϣ�� ���ļ����������ļ��������ܴ�С
     * @param $dir
     * @param int $file_num
     * @param int $path_num
     * @param int $size
     * @return array
     */
    public static function _path_info_more($dir, &$file_num = 0, &$path_num = 0, &$size = 0){
        if (!$dh = opendir($dir)) return false;
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    $file_num ++;
                    $size += self::get_filesize($fullpath);
                } else {
                    self::_path_info_more($fullpath, $file_num, $path_num, $size);
                    $path_num ++;
                }
            }
        }
        closedir($dh);
        $pathinfo['file_num'] = $file_num;
        $pathinfo['folder_num'] = $path_num;
        $pathinfo['size'] = $size;
        $pathinfo['size_friendly'] = self::size_format($size);
        return $pathinfo;
    }


    /**
     * ��ȡ��ѡ�ļ���Ϣ,�������ļ����������ļ��������ܴ�С����Ŀ¼Ȩ��
     * @param $list
     * @param $time_type
     * @return array
     */
    public static function path_info_muti($list,$time_type){
        if (count($list) == 1) {
            if ($list[0]['type']=="folder"){
                return self::path_info($list[0]['path']);//,$time_type
            }else{
                return self::file_info($list[0]['path']);//,$time_type
            }
        }
        $pathinfo = array(
            'file_num'		=> 0,
            'folder_num'	=> 0,
            'size'			=> 0,
            'size_friendly'	=> '',
            'father_name'	=> '',
            'mod'			=> ''
        );
        foreach ($list as $val){
            if ($val['type'] == 'folder') {
                $pathinfo['folder_num'] ++;
                $temp = self::path_info($val['path']);
                $pathinfo['folder_num']	+= $temp['folder_num'];
                $pathinfo['file_num']	+= $temp['file_num'];
                $pathinfo['size'] 		+= $temp['size'];
            }else{
                $pathinfo['file_num']++;
                $pathinfo['size'] += self::get_filesize($val['path']);
            }
        }
        $pathinfo['size_friendly'] = self::size_format($pathinfo['size']);
        $father_name = self::get_path_father($list[0]['path']);
        $pathinfo['mode'] = self::get_mode($father_name);
        return $pathinfo;
    }

    /**
     * ��ȡ�ļ������б���Ϣ
     * ������Ҫ��ȡ���ļ���·��,Ϊ�������
     * @param string $dir ������β/   d:/wwwroot/test/
     * @param bool|true $list_file
     * @param bool|false $check_children
     * @return array
     */
    public static function path_list($dir,$list_file=true,$check_children=false){
        $dir = rtrim($dir,'/').'/';
        if (!is_dir($dir) || !($dh = opendir($dir))){
            return array('folderlist'=>array(),'filelist'=>array());
        }
        $folderlist = array();$filelist = array();//�ļ������ļ�
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != ".." && $file != ".svn" ) {
                $fullpath = $dir . $file;
                if (is_dir($fullpath)) {
                    $info = self::folder_info($fullpath);
                    if($check_children){
                        $info['isParent'] = self::path_haschildren($fullpath,$list_file);
                    }
                    $folderlist[] = $info;
                } else if($list_file) {//�Ƿ��г��ļ�
                    $info = self::file_info($fullpath);
                    if($check_children) $info['isParent'] = false;
                    $filelist[] = $info;
                }
            }
        }
        closedir($dh);
        return array('folderlist' => $folderlist,'filelist' => $filelist);
    }

    /**
     * �ж��ļ����Ƿ��������ݡ�����Ϊ�ļ�����ֻɸѡ�ļ��в��㡿
     * @param $dir
     * @param bool|false $check_file
     * @return bool
     */
    public static function path_haschildren($dir,$check_file=false){
        $dir = rtrim($dir,'/').'/';
        if (!$dh = @opendir($dir)) return false;
        while (($file = readdir($dh)) !== false){
            if ($file != "." && $file != "..") {
                $fullpath = $dir.$file;
                if ($check_file) {//����Ŀ¼�����ļ���˵����������
                    if(is_dir($fullpath.'/') || is_file($fullpath)) return true;
                }else{//ֻ�����û���ļ�
                    @$ret =(is_dir($fullpath.'/'));
                    return (bool)$ret;
                }
            }
        }
        closedir($dh);
        return false;
    }

    /**
     * ɾ���ļ� �����������Ϊ����ϵͳ����. win--gbk
     * @param $fullpath
     * @return bool
     */
    public static function del_file($fullpath){
        if (!@unlink($fullpath)) { // ɾ�����ˣ������޸��ļ�Ȩ��
            @chmod($fullpath, 0777);
            if (!@unlink($fullpath)) {
                return false;
            }
        }
        return true;
    }

    /**
     * ɾ���ļ��� �����������Ϊ����ϵͳ����. win--gbk
     * @param $dir
     * @return bool
     */
    public static function del_dir($dir){
        if (!$dh = opendir($dir)) return false;
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . '/' . $file;
                if (!is_dir($fullpath)) {
                    if (!unlink($fullpath)) { // ɾ�����ˣ������޸��ļ�Ȩ��
                        chmod($fullpath, 0777);
                        if (!unlink($fullpath)) {
                            return false;
                        }
                    }
                } else {
                    if (!self::del_dir($fullpath)) {
                        chmod($fullpath, 0777);
                        if (!self::del_dir($fullpath)) return false;
                    }
                }
            }
        }
        closedir($dh);
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * �����ļ���
     * eg:��D:/wwwroot/����wordpress���Ƶ�
     *	D:/wwwroot/www/explorer/0000/del/1/
     * ĩβ������Ҫ��б�ܣ����Ƶ���ַ�������Դ�ļ�������
     * �ͻὫwordpress�����ļ����Ƶ�D:/wwwroot/www/explorer/0000/del/1/����
     * $from = 'D:/wwwroot/wordpress';
     * $to = 'D:/wwwroot/www/explorer/0000/del/1/wordpress';
     * @param $source
     * @param $dest
     * @return bool
     */
    public static function copy_dir($source, $dest){
        if (!$dest) return false;
        if ($source == substr($dest,0,strlen($source))) return false;//��ֹ���޵ݹ�
        $result = false;
        if (is_file($source)) {
            if ($dest[strlen($dest)-1] == '/') {
                $__dest = $dest . "/" . basename($source);
            } else {
                $__dest = $dest;
            }
            $result = copy($source, $__dest);
            chmod($__dest, 0777);
        }elseif (is_dir($source)) {
            if ($dest[strlen($dest)-1] == '/') {
                $dest = $dest . basename($source);
                mkdir($dest, 0777);
            } else {
                mkdir($dest, 0777);
            }
            if (!$dh = opendir($source)) return false;
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    if (!is_dir($source . "/" . $file)) {
                        $__dest = $dest . "/" . $file;
                    } else {
                        $__dest = $dest . "/" . $file;
                    }
                    $result = self::copy_dir($source . "/" . $file, $__dest);
                }
            }
            closedir($dh);
        }
        return $result;
    }

    /**
     * ����Ŀ¼
     * @param string $dir
     * @param int $mode
     * @return bool
     */
    public static function mk_dir($dir, $mode = 0777){
        if (is_dir($dir) || mkdir($dir, $mode))
            return true;
        if (!self::mk_dir(dirname($dir), $mode))
            return false;
        return mkdir($dir, $mode);
    }

    /**
     * @param string $path �ļ��� $dir �������ص��ļ���array files �������ص��ļ�array
     * @param $dir
     * @param $file
     * @param int $deepest �Ƿ������ݹ飻$deep �ݹ�㼶
     * @param int $deep
     * @return bool
     */
    public static function recursion_dir($path,&$dir,&$file,$deepest=-1,$deep=0){
        $path = rtrim($path,'/').'/';
        if (!is_array($file)) $file=array();
        if (!is_array($dir)) $dir=array();
        if (!$dh = opendir($path)) return false;
        while(($val=readdir($dh)) !== false){
            if ($val=='.' || $val=='..') continue;
            $value = strval($path.$val);
            if (is_file($value)){
                $file[] = $value;
            }else if(is_dir($value)){
                $dir[]=$value;
                if ($deepest==-1 || $deep<$deepest){
                    self::recursion_dir($value."/",$dir,$file,$deepest,$deep+1);
                }
            }
        }
        closedir($dh);
        return true;
    }
    /*
     * $search Ϊ�������ַ���
     * is_content ��ʾ�Ƿ������ļ�����;Ĭ�ϲ�����
     * is_case  ��ʾ���ִ�Сд,Ĭ�ϲ�����
     */
    public static function path_search($path,$search,$is_content=false,$file_ext='',$is_case=false){
        $ext_arr=explode("|",$file_ext);
        self::recursion_dir($path,$dirs,$files,-1,0);
        $strpos = 'stripos';//�Ƿ����ִ�Сд
        if ($is_case) $strpos = 'strpos';

        $filelist = array();
        $folderlist = array();
        foreach($files as $f){
            $ext = self::get_path_ext($f);
            $path_this = self::get_path_this($f);
            if ($file_ext !='' && !in_array($ext,$ext_arr)) continue;//�ļ����Ͳ����û��޶���
            if ($strpos($path_this,$search) !== false){//�����ļ���;�ѵ��ͷ��أ��Ѳ�������
                $filelist[] = self::file_info($f);
                continue;
            }
            if ($is_content && is_file($f)){
                $fp = fopen($f, "r");
                $content = @fread($fp,self::get_filesize($f));
                fclose($fp);
                if ($strpos($content,self::iconv_app($search)) !== false){
                    $filelist[] = self::file_info($f);
                }
            }
        }
        if ($file_ext == '') {//û�޶���չ����������ļ���
            foreach($dirs as $f){
                $path_this = self::get_path_this($f);
                if ($strpos($path_this,$search) !== false){
                    $folderlist[]= array(
                        'name'  => self::iconv_app(self::get_path_this($f)),
                        'path'  => self::iconv_app(self::get_path_father($f))
                    );
                }
            }
        }
        return array('folderlist' => $folderlist,'filelist' => $filelist);
    }

    /**
     * �޸��ļ����ļ���Ȩ��
     * @param $path
     * @param $mod
     * @return bool
     */
    public static function chmod_path($path,$mod){
        //$mod = 0777;//
        if (!isset($mod)) $mod = 0777;
        if (!is_dir($path)) return chmod($path,$mod);
        if (!$dh = opendir($path)) return false;
        while (($file = readdir($dh)) !== false){
            if ($file != "." && $file != "..") {
                $fullpath = $path . '/' . $file;
                return self::chmod_path($fullpath,$mod);
            }
        }
        closedir($dh);
        return chmod($path,$mod);
    }

    /**
     * �ļ���С��ʽ��
     * @param int $bytes �ļ���С
     * @param int $precision ������С����
     * @return string
     */
    public static function size_format($bytes, $precision = 2){
        if ($bytes == 0) return "0 B";
        $unit = array(
            'TB' => 1099511627776,  // pow( 1024, 4)
            'GB' => 1073741824,		// pow( 1024, 3)
            'MB' => 1048576,		// pow( 1024, 2)
            'kB' => 1024,			// pow( 1024, 1)
            'B ' => 1,				// pow( 1024, 0)
        );
        foreach ($unit as $un => $mag) {
            if (doubleval($bytes) >= $mag)
                return round($bytes / $mag, $precision).' '.$un;
        }
        return null;
    }

    /**
     * �ж�·���ǲ��Ǿ���·��
     * ����true('/foo/bar','c:\windows').
     * @param $path
     * @return bool ����true��Ϊ����·��������Ϊ���·��
     */
    public static function path_is_absolute($path){
        if (realpath($path) == $path)// *nux �ľ���·�� /home/my
            return true;
        if (strlen($path) == 0 || $path[0] == '.')
            return false;
        if (preg_match('#^[a-zA-Z]:\\\\#', $path))// windows �ľ���·�� c:\aaa\
            return true;
        return (bool)preg_match('#^[/\\\\]#', $path); //����·�� ���� / �� \����·������������Ϊ���·��
    }

    /**
     * ��ȡ��չ�����ļ�����
     * @param string $ext ��չ��
     * @return int|string
     */
    public static function ext_type($ext){
        $ext2type = array(
            'text' => array('txt','ini','log','asc','csv','tsv','vbs','bat','cmd','inc','conf','inf'),
            'code'		=> array('css','htm','html','php','js','c','cpp','h','java','cs','sql','xml'),
            'picture'	=> array('jpg','jpeg','png','gif','ico','bmp','tif','tiff','dib','rle'),
            'audio'		=> array('mp3','ogg','oga','mid','midi','ram','wav','wma','aac','ac3','aif','aiff','m3a','m4a','m4b','mka','mp1','mx3','mp2'),
            'flash'		=> array('swf'),
            'video'		=> array('rm','rmvb','flv','mkv','wmv','asf','avi','aiff','mp4','divx','dv','m4v','mov','mpeg','vob','mpg','mpv','ogm','ogv','qt'),
            'document'	=> array('doc','docx','docm','dotm','odt','pages','pdf','rtf','xls','xlsx','xlsb','xlsm','ppt','pptx','pptm','odp'),
            'rar_achieve'	=> array('rar','arj','tar','ace','gz','lzh','uue','bz2'),
            'zip_achieve'	=> array('zip','gzip','cab','tbz','tbz2'),
            'other_achieve' => array('dmg','sea','sit','sqx')
        );
        foreach ($ext2type as $type => $exts) {
            if (in_array($ext, $exts)) {
                return $type;
            }
        }
        return null;
    }

    /**
     * ������ļ�����
     * Ĭ���Ը�����ʽ���أ�$downloadΪfalseʱ��Ϊ����ļ�
     * @param $file
     * @param bool|false $download
     */
    public static function file_put_out($file,$download=false){
        if (!is_file($file)) KoeTool::show_json('not a file!');
        set_time_limit(0);
        //ob_clean();//���֮ǰ�����������
        if (!file_exists($file)) KoeTool::show_json('file not exists',false);
        if (isset($_SERVER['HTTP_RANGE']) && ($_SERVER['HTTP_RANGE'] != "") &&
            preg_match("/^bytes=([0-9]+)-$/i", $_SERVER['HTTP_RANGE'], $match) && ($match[1] < filesize($file)/* $fsize*/)) {
            $start = $match[1];
        }else{
            $start = 0;
        }
        $size = self::get_filesize($file);
        header("Cache-Control: public");
        header("X-Powered-By: kodExplorer.");
        if ($download) {
            header("Content-Type: application/octet-stream");
            $filename = self::get_path_this($file);//�����IE������ʱ������������
            if(preg_match('/MSIE/',$_SERVER['HTTP_USER_AGENT'])){
                $filename = str_replace('+','%20',urlencode($filename));
            }
            header("Content-Disposition: attachment;filename=".$filename);
        }else{
            $mime = WebTool::get_file_mime(self::get_path_ext($file));
            header("Content-Type: ".$mime);
        }
        if ($start > 0){
            header("HTTP/1.1 206 Partial Content");
            header("Content-Ranges: bytes".$start ."-".($size - 1)."/" .$size);
            header("Content-Length: ".($size - $start));
        }else{
            header("Accept-Ranges: bytes");
            header("Content-Length: $size");
        }

        $fp = fopen($file, "rb");
        fseek($fp, $start);
        while (!feof($fp)) {
            print (fread($fp, 1024 * 8)); //����ļ�
            flush();
            ob_flush();
        }
        fclose($fp);
    }

    /**
     * �ļ����ص�������
     * @param $from
     * @param $file_name
     * @return bool
     */
    public static function file_download_this($from, $file_name){
        set_time_limit(0);
        $fp = @fopen ($from, "rb");
        if ($fp){
            $new_fp = @fopen ($file_name, "wb");
            fclose($new_fp);

            $temp_file = $file_name.'.download';
            $download_fp = @fopen ($temp_file, "wb");
            while(!feof($fp)){
                if(!file_exists($file_name)){//ɾ��Ŀ���ļ�������ֹ����
                    fclose($download_fp);
                    self::del_file($temp_file);
                    self::del_file($file_name);
                    return false;
                }
                fwrite($download_fp, fread($fp, 1024 * 8 ), 1024 * 8);
            }
            //������ɣ���������ʱ�ļ���Ŀ���ļ�
            self::del_file($file_name);
            $rename_ret = @rename($temp_file,$file_name);
            return (bool)$rename_ret;
        }else{
            return false;
        }
    }

    /**
     * ��ȡ�ļ�(��)Ȩ�� rwx_rwx_rwx
     * @param $file
     * @return string
     */
    public static function get_mode($file){
        $Mode = fileperms($file);
        $theMode = ' '.decoct($Mode);
        $theMode = substr($theMode,-4);
        $Owner = array();$Group=array();$World=array();
        if ($Mode &0x1000) $Type = 'p'; // FIFO pipe
        elseif ($Mode &0x2000) $Type = 'c'; // Character special
        elseif ($Mode &0x4000) $Type = 'd'; // Directory
        elseif ($Mode &0x6000) $Type = 'b'; // Block special
        elseif ($Mode &0x8000) $Type = '-'; // Regular
        elseif ($Mode &0xA000) $Type = 'l'; // Symbolic Link
        elseif ($Mode &0xC000) $Type = 's'; // Socket
        else $Type = 'u'; // UNKNOWN
        // Determine les permissions par Groupe
        $Owner['r'] = ($Mode &00400) ? 'r' : '-';
        $Owner['w'] = ($Mode &00200) ? 'w' : '-';
        $Owner['x'] = ($Mode &00100) ? 'x' : '-';
        $Group['r'] = ($Mode &00040) ? 'r' : '-';
        $Group['w'] = ($Mode &00020) ? 'w' : '-';
        $Group['e'] = ($Mode &00010) ? 'x' : '-';
        $World['r'] = ($Mode &00004) ? 'r' : '-';
        $World['w'] = ($Mode &00002) ? 'w' : '-';
        $World['e'] = ($Mode &00001) ? 'x' : '-';
        // Adjuste pour SUID, SGID et sticky bit
        if ($Mode &0x800) $Owner['e'] = ($Owner['e'] == 'x') ? 's' : 'S';
        if ($Mode &0x400) $Group['e'] = ($Group['e'] == 'x') ? 's' : 'S';
        if ($Mode &0x200) $World['e'] = ($World['e'] == 'x') ? 't' : 'T';
        $Mode = $Type.$Owner['r'].$Owner['w'].$Owner['x'].' '.
            $Group['r'].$Group['w'].$Group['e'].' '.
            $World['r'].$World['w'].$World['e'];
        return $Mode.' ('.$theMode.') ';
    }

    /**
     * ��ȡ�����ϴ������ֵ
     * return * byte
     */
    public static function get_post_max(){
        $upload = ini_get('upload_max_filesize');
        $upload = $upload==''?ini_get('upload_max_size'):$upload;
        $post = ini_get('post_max_size');
        $upload = intval($upload)*1024*1024;
        $post = intval($post)*1024*1024;
        return $upload<$post?$upload:$post;
    }

    /**
     * �ļ��ϴ����������ļ��ϴ�,����ֶ������
     * ����demo
     * upload('file','D:/www/');
     * @param $fileInput
     * @param string $path
     */
    public static function upload($fileInput, $path = './'){
//        global $L;
        global $L;
        $file = $_FILES[$fileInput];
        if (!isset($file)) KoeTool::show_json($L['upload_error_null'],false);

        $file_name = self::iconv_system($file['name']);
        $save_path = self::get_filename_auto($path.$file_name);
        if(move_uploaded_file($file['tmp_name'],$save_path)){
            KoeTool::show_json($L['upload_success'],true,self::iconv_app($save_path));
        }else {
            KoeTool::show_json($L['move_error'],false);
        }
    }

    //��Ƭ�ϴ�����
    public static function upload_chunk($fileInput, $path = './',$temp_path){
//        global $config,$L;
        global $L;
        $file = $_FILES[$fileInput];
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        if (!isset($file)) KoeTool::show_json($L['upload_error_null'],false);
        $file_name = self::iconv_system($file['name']);

        if ($chunks>1) {//�����ϴ�����һ����ǰ��˳��
            $temp_file_pre = $temp_path.md5($temp_path.$file_name).'.part';
            if (self::get_filesize($file['tmp_name']) ==0) {
                KoeTool::show_json($L['upload_success'],false,'chunk_'.$chunk.' error!');
            }
            if(move_uploaded_file($file['tmp_name'],$temp_file_pre.$chunk)){
                $done = true;
                for($index = 0; $index<$chunks; $index++ ){
                    if (!file_exists($temp_file_pre.$index)) {
                        $done = false;
                        break;
                    }
                }
                if (!$done){
                    KoeTool::show_json($L['upload_success'],true,'chunk_'.$chunk.' success!');
                }

                $save_path = $path.$file_name;
                $out = fopen($save_path, "wb");
                if ($done && flock($out, LOCK_EX)) {
                    for( $index = 0; $index < $chunks; $index++ ) {
                        if (!$in = fopen($temp_file_pre.$index,"rb")) break;
                        while ($buff = fread($in, 4096)) {
                            fwrite($out, $buff);
                        }
                        fclose($in);
                        unlink($temp_file_pre.$index);
                    }
                    flock($out, LOCK_UN);
                    fclose($out);
                }
                KoeTool::show_json($L['upload_success'],true,self::iconv_app($save_path));
            }else {
                KoeTool::show_json($L['move_error'],false);
            }
        }

        //�����ϴ�
        $save_path = self::get_filename_auto($path.$file_name); //�Զ�������
        if(move_uploaded_file($file['tmp_name'],$save_path)){
            KoeTool::show_json($L['upload_success'],true,self::iconv_app($save_path));
        }else {
            KoeTool::show_json($L['move_error'],false);
        }
    }

    /**
     * д��־
     * @param string $log   ��־��Ϣ
     * @param string $type  ��־���� [system|app|...]
     * @param string $level ��־����
     * @return boolean
     */
    public static function write_log($log, $type = 'default', $level = 'log'){
        $now_time = date('[y-m-d H:i:s]');
        $now_day  = date('Y_m_d');
        // ��������������־Ŀ��λ��
        $target   = LOG_PATH . strtolower($type) . '/';
        self::mk_dir($target, 0777);
        if (! is_writable($target)) exit('path can not write!');
        switch($level){// �ּ�д��־
            case 'error':	$target .= 'Error_' . $now_day . '.log';break;
            case 'warning':	$target .= 'Warning_' . $now_day . '.log';break;
            case 'debug':	$target .= 'Debug_' . $now_day . '.log';break;
            case 'info':	$target .= 'Info_' . $now_day . '.log';break;
            case 'db':		$target .= 'Db_' . $now_day . '.log';break;
            default:		$target .= 'Log_' . $now_day . '.log';break;
        }
        //�����־�ļ���С, �������ô�С��������
        if (file_exists($target) && self::get_filesize($target) <= 100000) {
            $file_name = substr(basename($target),0,strrpos(basename($target),'.log')).'.log';
            rename($target, dirname($target) .'/'. $file_name);
        }
        clearstatcache();
        return error_log("$now_time $log\n", 3, $target);
    }
}