<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 21:23
 */
namespace Application\Koe\Model;

use System\Core\Model;

class KoeModel extends Model{
    protected $db = null;
    protected $in;
    protected $config;

    /**
     * ���캯��
     * @param $config
     */
    public function __construct($config = null){
        global  $in;
        $this -> in = $in;
        null !== $config and $this -> config = $config;
        parent::__construct();
    }

    /**
     * ��ȡ���ݿ�
     * @return null
     */
    function db(){
        if ($this ->db != NULL) {
            return $this ->db;
        }else{
            return null;
        }
    }
}