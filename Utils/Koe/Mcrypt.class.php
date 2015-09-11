<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 21:15
 */
namespace Utils\Koe;

class Mcrypt
{

    public static $default_key = 'a!takA:dlmcldEv,e';

    /**
     * �ַ��ӽ��ܣ�һ��һ��,�ɶ�ʱ������Ч
     *
     * @param string $string ԭ�Ļ�������
     *          param string $operation ����(encode | decode)
     * @param string $key ��Կ
     * @param int $expiry ������Ч��,��λs,0 Ϊ������Ч
     * @return string ������ ԭ�Ļ��� ���� base64_encode ����������
     */
    public static function encode($string, $key = '', $expiry = 3600)
    {
        $ckey_length = 4;
        $key = md5($key ? $key : self::$default_key); //�����ܳ�
        $keya = md5(substr($key, 0, 16));         //��������������֤
        $keyb = md5(substr($key, 16, 16));         //���ڱ仯���ɵ����� (��ʼ������IV)
        $keyc = substr(md5(microtime()), -$ckey_length);
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        $string = sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        $box = range(0, 255);
        // �����ܳײ������������
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // �ӽ��ܣ����ܳײ��ó��ܳ׽��������ת���ַ�
        $result = '';
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        $result = $keyc . str_replace('=', '', base64_encode($result));
        $result = str_replace(array('+', '/', '='), array('-', '_', '.'), $result);
        return $result;
    }

    /**
     * �ַ��ӽ��ܣ�һ��һ��,�ɶ�ʱ������Ч
     *
     * @param string $string ԭ�Ļ�������
     *  param string $operation ����(encode | decode)
     * @param string $key ��Կ
     *  param int $expiry ������Ч��,��λs,0 Ϊ������Ч
     * @return string ������ ԭ�Ļ��� ���� base64_encode ����������
     */
    public static function decode($string, $key = '')
    {
        $string = str_replace(array('-', '_', '.'), array('+', '/', '='), $string);
        $ckey_length = 4;
        $key = md5($key ? $key : self::$default_key); //�����ܳ�
        $keya = md5(substr($key, 0, 16));         //��������������֤
        $keyb = md5(substr($key, 16, 16));         //���ڱ仯���ɵ����� (��ʼ������IV)
        $keyc = substr($string, 0, $ckey_length);

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        $string = base64_decode(substr($string, $ckey_length));
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // �����ܳײ������������
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // �ӽ��ܣ����ܳײ��ó��ܳ׽��������ת���ַ�
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0)
            && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)
        ) {
            return substr($result, 26);
        } else {
            return '';
        }
    }
}