<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/10/11 0011
 * Time: 21:45
 */
namespace Application\Common\Model;
use Application\Installer\Util\InstallKits;
use System\Core\Model;

/**
 * Class AdminModel CMS后台模型基类
 * @package Application\Common\Model
 */
class AdminModel extends Model {

    /**
     * 数据库配置
     * @param array|null $config
     * @throws \Exception
     */
    public function __construct($config=null){
        parent::__construct($config);
    }

    /**
     * 初始化数据库连接
     * 覆盖了父类的init方法，增加了空参数时直接加载Install时候的数据库配置
     * @param array|null|string $config
     */
    public function init(array $config=null){
        if(!isset($config)){
            $config = InstallKits::getDatabaseConfig();
        }
        parent::init($config);
    }

}