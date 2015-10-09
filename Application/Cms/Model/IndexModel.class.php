<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/9
 * Time: 16:53
 */
namespace Application\Cms\Model;
use System\Core\Model;

/**
 * Class IndexModel CMS模型基类
 * @package Application\Cms\Model
 */
class IndexModel extends Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 保存数据库配置到模块配置中
     * @param array $config
     * @return bool 是否成功创建配置文件
     */
    public function storeDatabaseConfig(array $config){
        $file = BASE_PATH . $this->modules_name . '/Configure/database.config.php';
    }

}