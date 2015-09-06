<?php return array (
  'custom' => 
  array (
  ),
  'database' => 
  array (
    'MASTER_NO' => 0,
    'DB_CONNECT' => 
    array (
      0 => 
      array (
        'type' => 'mysql',
        'dbname' => 'ot-1.1',
        'username' => 'root',
        'password' => '123456',
        'host' => 'localhost',
        'port' => '3306',
        'charset' => 'UTF8',
        'dsn' => NULL,
        'options' => 
        array (
          3 => 2,
        ),
      ),
    ),
  ),
  'guide' => 
  array (
  ),
  'modules' => 
  array (
    'Home' => 
    array (
    ),
    'Admin/User' => 
    array (
    ),
  ),
  'route' => 
  array (
    'DIRECT_ROUTE_RULES' => 
    array (
    ),
    'INDIRECT_ROUTE_RULES' => 
    array (
    ),
  ),
  'template' => 
  array (
    'TEMPLATE_LAYOUT_ON' => false,
    'TEMPLATE_LAYOUT_FILENAME' => NULL,
  ),
  'url' => 
  array (
    'URL_MODULE_VARIABLE' => 'm',
    'URL_CONTROLLER_VARIABLE' => 'c',
    'URL_ACTION_VARIABLE' => 'a',
    'URL_COMPATIBLE_VARIABLE' => 'pathinfo',
    'MM_BRIDGE' => '/',
    'MC_BRIDGE' => '/',
    'CA_BRIDGE' => '/',
    'AP_BRIDGE' => '_',
    'PP_BRIDGE' => '-',
    'PKV_BRIDGE' => '_',
    'MASQUERADE_TAIL' => 'html',
    'REWRITE_HIDDEN' => '/index.php',
    'DEFAULT_MODULE' => 'Home',
    'DEFAULT_CONTROLLER' => 'Index',
    'DEFAULT_ACTION' => 'index',
    'DOMAIN_DEPLOY_ON' => true,
    'FUL_DOMAIN' => 'minshuttler.com',
    'SUB_DOMAIN_DEPLOY_RULES' => 
    array (
      'news' => 
      array (
        0 => 'Admin/User',
        1 => 'Name',
        2 => 'look',
        3 => 'a=1&b=2&c=3',
      ),
      'sports' => 'http://www.qq.com',
    ),
  ),
); ?>