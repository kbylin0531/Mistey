<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 21:01
 */
namespace Utils\Koe;
/**
 * ���ݵĻ���洢�ࣻkey=>value ģʽ��value�����������������ݡ�
 * �������̲��ԣ���ȡ���5000��/s  ����д��1000��/s
 * add   ��ӵ������ݣ��Ѵ����򷵻�false
 * reset �����������ݣ��������������������
 * get:  ��ȡ���ݣ���ȡȫ������ȡָ��key���ݣ���ȡָ�����key������;���ҷ�ʽ��ȡ��������
 *     1. get();
 *     2. get("demo")
 *     3. get(array('demo','admin'))
 *     4. get('group','','root')
 * update: �������ݣ�����ָ��key���ݣ���ȡָ�����key������; ���ҷ�ʽ���¶�������
 *     1. update("demo",array('name'=>'ddd',...))
 *     2. update(array('demo','admin'),array(array('name'...),array('name'...)))
 *     3. update('group','system','root')
 *
 * replace_update($key_old,$key_new,$value_new)�滻��ʽ���£�����key���µ�����
 *
 * delete:  ��ȡ���ݣ���ȡȫ������ȡָ��key���ݣ���ȡָ�����key������;���ҷ�ʽ��ȡ��������
 *     1. delete("demo")
 *     2. delete(array('demo','admin'))
 *     3. delete('group','','root')
 *     ����:====================================
 *     ['sss':['name':'sss','group':'root'],'bbb':['name':'bbb','group':'root']
 *     ,'ccc':['name':'ccc','group':'system'],'ddd':['name':'ddd','group':'root']
 *     ���ҷ�ʽɾ��  delete('group','','root');
 *     ���ҷ�ʽ����  update('group','system','root');
 *     ���ҷ�ʽ��ȡ  get('group','','root');
 */
define('CONFIG_EXIT', '<?php exit;?>');
class FileCache{
    private $data;
    private $file;

    function __construct($file)
    {
        $this->file = $file;
        $this->data = self::load($file);

        defined('CONFIG_EXIT') or define('CONFIG_EXIT', '<?php exit;?>');
    }

    /**
     * �����������ݣ��������������������
     * @param array $list
     */
    public function reset($list = array())
    {
        $this->data = $list;
        self::save($this->file, $this->data);
    }

    /**
     * ���һ�����ݣ������ظ�������Ѵ����򷵻�false;1k��/s
     * @param $k
     * @param $v
     * @return bool
     */
    public function add($k, $v)
    {
        if (!isset($this->data[$k])) {
            $this->data[$k] = $v;
            self::save($this->file, $this->data);
            return true;
        }
        return false;
    }

    /**
     * ��ȡ����;�������򷵻�false;100w��/s
     * @param string $k �����򷵻�ȫ��;
     * @param string $v Ϊ�ַ����������key��ȡ���ݣ�ֻ��һ������
     * @param bool|false $search_value ����ʱ����ʾ�Բ��ҵķ�ʽɸѡ����ɸѡ����Ϊ $key=$k ֵΪ$search_value�����ݣ�����
     * @return array|bool|mixed
     */
    public function get($k = '', $v = '', $search_value = false)
    {
        if ($k === '') return $this->data;

        $search = array();
        if ($search_value === false) {
            if (is_array($k)) {
                //�������ݻ�ȡ
                $num = count($k);
                for ($i = 0; $i < $num; $i++) {
                    $search[$k[$i]] = $this->data[$k[$i]];
                }
                return $search;
            } else if (isset($this->data[$k])) {
                //�������ݻ�ȡ
                return $this->data[$k];
            }
        } else {
            //�����������ݷ�ʽ��ȡ�����ض���
            foreach ($this->data as $key => $val) {
                if ($val[$k] == $search_value) {
                    $search[$key] = $this->data[$key];
                }
            }
            return $search;
        }
        return false;
    }

    /**
     * ��������;������;��������һ���������򷵻�false;�����б���
     * @param string $k Ϊ�ַ����������keyֻ����һ������
     * @param array $v array  array($key1,$key2,...),array($value1,$value2,...)
     *              ���ʾ���¶�������
     * @param bool|false $search_value
     * @return bool ����ʱ����ʾ�Բ��ҵķ�ʽ���������е�����
     */
    public function update($k, $v, $search_value = false)
    {
        if ($search_value === false) {
            if (is_array($k)) {
                //�������ݸ���
                $num = count($k);
                for ($i = 0; $i < $num; $i++) {
                    $this->data[$k[$i]] = $v[$i];
                }
                self::save($this->file, $this->data);
                return true;
            } else if (isset($this->data[$k])) {
                //�������ݸ���
                $this->data[$k] = $v;
                self::save($this->file, $this->data);
                return true;
            }
        } else {
            //���ҷ�ʽ���£����¶���
            foreach ($this->data as $key => $val) {
                if ($val[$k] == $search_value) {
                    $this->data[$key][$k] = $v;
                }
            }
            self::save($this->file, $this->data);
            return true;
        }
        return false;
    }

    /**
     * �滻��ʽ���£�����key���µ�����
     * @param $key_old
     * @param $key_new
     * @param $value_new
     * @return bool
     */
    public function replace_update($key_old, $key_new, $value_new)
    {
        if (isset($this->data[$key_old])) {
//            $value = $this->data[$key_old];
            unset($this->data[$key_old]);
            $this->data[$key_new] = $value_new;
            self::save($this->file, $this->data);
            return true;
        }
        return false;
    }

    /**
     * ɾ��;�����ڷ���false
     * @param $k
     * @param string $v
     * @param bool|false $search_value
     * @return bool
     */
    public function delete($k, $v = '', $search_value = false)
    {
        if ($search_value === false) {
            if (is_array($k)) {
                //�������ݸ���
                $num = count($k);
                for ($i = 0; $i < $num; $i++) {
                    unset($this->data[$k[$i]]);
                }
                self::save($this->file, $this->data);
                return true;
            } else if (isset($this->data[$k])) {
                //��������ɾ��
                unset($this->data[$k]);
                self::save($this->file, $this->data);
                return true;
            }
        } else {
            //�����������ݷ�ʽɾ����ɾ������
            foreach ($this->data as $key => $val) {
                if ($val[$k] == $search_value) {
                    unset($this->data[$key]);
                }
            }
            self::save($this->file, $this->data);
            return true;
        }
        return false;
    }


    /**
     * ����
     * @param $arr
     * @param $key
     * @param string $type
     * @return array
     */
    public static function arr_sort(&$arr, $key, $type = 'asc')
    {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$key];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }

    /**
     * �������ݣ��������ɳ�������
     * @param $file
     * @return array|mixed
     */
    public static function load($file)
    {//10000����Ҫ4s ���������첻��
        if (!file_exists($file)) touch($file);
        $str = file_get_contents($file);
        $str = substr($str, strlen(CONFIG_EXIT));
        $data = json_decode($str, true);
        if (is_null($data)) $data = array();
        return $data;
    }

    /**
     * �������ݣ�
     * @param $file
     * @param $data
     */
    public static function save($file, $data)
    {//10000����Ҫ6s
        if (!$file) return;
        if ($fp = fopen($file, "w")) {
            if (flock($fp, LOCK_EX)) {  // ��������������
                $str = CONFIG_EXIT . json_encode($data);
                fwrite($fp, $str);
                fflush($fp);            // flush output before releasing the lock
                flock($fp, LOCK_UN);    // �ͷ�����
            }
            fclose($fp);
        }
    }
}