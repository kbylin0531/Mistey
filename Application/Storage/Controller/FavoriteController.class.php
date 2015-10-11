<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/11
 * Time: 19:03
 */
namespace Application\Koe\Controller;
use Utils\Koe\FileCache;
use Utils\Koe\KoeTool;

/**
 * Class FavoriteController 收藏夹控制器
 * @package Application\Koe\Controller
 */
class FavoriteController extends KoeController{
    private $sql;
    function __construct(){
        parent::__construct();
        $this->sql=new FileCache($this->config['user_fav_file']);
    }

    /**
     * 获取收藏夹json
     */
    public function get() {
        KoeTool::show_json($this->sql->get());
    }

    /**
     * 添加
     */
    public function add() {
        $res=$this->sql->add($this->in['name'],
            array(
                'name'=>$this->in['name'],
                'path'=>$this->in['path']
            )
        );
        if($res) KoeTool::show_json($this->L['success']);
        KoeTool::show_json($this->L['error_repeat'],false);
    }

    /**
     * 编辑
     */
    public function edit() {
        //查找到一条记录，修改为该数组
        $to_array=array(
            'name'=>$this->in['name_to'],
            'path'=>$this->in['path_to']
        );
        if($this->sql->replace_update(
            $this->in['name'],$this->in['name_to'],$to_array)){
            KoeTool::show_json($this->L['success']);
        }
        KoeTool::show_json($this->L['error_repeat'],false);
    }

    /**
     * 删除
     */
    public function del() {
        if($this->sql->delete($this->in['name'])){
            KoeTool::show_json($this->L['success']);
        }
        KoeTool::show_json($this->L['error'],false);
    }
}