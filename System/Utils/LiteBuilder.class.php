<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/12
 * Time: 9:33
 */
namespace System\Utils;
use System\Core\Storage;
use System\Mist;

class LiteBuilder {

    /**
     * �������ʱ����
     * @param string $litefile ���ɵ�lite�ļ�����
     * @param array $filelist �ļ��б�
     */
    public static function build($litefile = null,$filelist=null) {
        static $_cache = array();
        isset($litefile) or $litefile = RUNTIME_PATH.APP_NAME.'.lite.php';
        //��ȡ���еĶ���ĳ���
//        $constants = get_defined_constants(TRUE);
//        $content = self::buildArrayDefine($constants);
        // ��ȡ�����б��ļ�
        isset($filelist) or $filelist = Mist::getClasses();
        $content = '';
        // �����ļ�
        foreach ($filelist as $file){
            if(Storage::hasFile($file)){
                if(!isset($_cache[$file])){
                    $content .= self::compile($file);
                    $_cache[$file] = true;
                }
            }
        }
        // ��������Lite�ļ�
        Storage::writeFile($litefile,'<?php '.$content);
//        Storage::writeFile($litefile,self::stripWhitespace('<?php '.$content));
    }

    /**
     * ��ȡ�ļ�����������
     * @param string $filename �ļ���
     * @return string
     */
    public static function compile($filename) {
        $content    =   php_strip_whitespace($filename);//ɾ��PHP�����е�ע�ͺͿո�
        $content    =   trim(substr($content, 5));//ȥ�� '<?php'
        // �滻�����ռ�
        if(0===strpos($content,'namespace')){
            $content    =   preg_replace('/namespace\s(.*?);/','namespace \\1{',$content,1);
        }else{
            $content    =   'namespace {'.$content;
        }
        //ȥ��  '? >'   Ҳ�п��ܲ�������
        if ('?>' == substr($content, -2))
            $content    = substr($content, 0, -2);
        return $content.'}';
    }

    /**
     * ȥ�������еĿհ׺�ע��
     * @param string $content ��������
     * @return string
     */
    public static function stripWhitespace($content) {
        $stripStr   = '';
        //����phpԴ��
        $tokens     = token_get_all($content);
        $last_space = false;
        for ($i = 0, $j = count($tokens); $i < $j; $i++) {
            if (is_string($tokens[$i])) {
                $last_space = false;
                $stripStr  .= $tokens[$i];
            } else {
                switch ($tokens[$i][0]) {
                    //���˸���PHPע��
                    case T_COMMENT:
                    case T_DOC_COMMENT:
                        break;
                    //���˿ո�
                    case T_WHITESPACE:
                        if (!$last_space) {
                            $stripStr  .= ' ';
                            $last_space = true;
                        }
                        break;
                    case T_START_HEREDOC:
                        $stripStr .= '<<<Mist ';
                        break;
                    case T_END_HEREDOC:
                        $stripStr .=  'Mist; ';
                        for($k = $i+1; $k < $j; $k++) {
                            if(is_string($tokens[$k]) && $tokens[$k] == ';') {
                                $i = $k;
                                break;
                            } else if($tokens[$k][0] == T_CLOSE_TAG) {
                                break;
                            }
                        }
                        break;
                    default:
                        $last_space = false;
                        $stripStr  .= $tokens[$i][1];
                }
            }
        }
        return $stripStr;
    }

    /**
     * �����������ɳ�������
     * @param array $array ��������
     * @return string
     */
    public static function buildArrayDefine(array $array) {
        $content = ' ';
        foreach ($array as $key => $val) {
            $key = strtoupper($key);
            $content .= "defined('{$key}') or ";
            if (is_int($val) || is_float($val)) {
                $content .= "define('{$key}',{$val});";
            } elseif (is_bool($val)) {
                $val = ($val) ? 'true' : 'false';
                $content .= "define('{$key}',{$val});";
            } elseif (is_string($val)) {
                $val = addslashes($val);
                $content .= "define('{$key}','{$val}'');";
            }
            $content    .= ' ';
        }
        return $content;
    }
}