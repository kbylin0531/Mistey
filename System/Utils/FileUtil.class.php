<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/12
 * Time: 12:32
 */
namespace System\Utils;

/**
 * Class FileUtil �ļ���������
 * @package System\Utils
 */
class FileUtil {

    /**
     * ��ȡĿ¼�µ������ļ�
     * @param string $path Ŀ¼��·��
     * @param bool|true $clear ���ԭ���ļ�¼
     * @return array �ļ����Ͷ�Ӧ��·��
     * @throws \Exception
     */
    public static function readDirFiles($path,$clear=true){
        static $_file = array();
        $clear and $_file = array();
        if (is_dir($path)) {
            $handler = opendir ($path);
            while (($filename = readdir( $handler )) !== false) {//δ�������һ���ļ�   ������
                if ($filename !== '.' && $filename !== '..' ) {//�ļ���ȥ .��..
                    if(is_file($path . '/' . $filename)) {
                        $_file[$filename] = $path . '/' . $filename;
                    }elseif(is_dir($path . '/' . $filename)) {
                        self::readDirFiles($path . '/' . $filename,false);//�ݹ�
                    }
                }
            }
            closedir($handler);//�ر�Ŀ¼ָ��
        }else{
            throw new \Exception("Path '{$path}' is not a dirent!");
        }
        return $_file;
    }




}