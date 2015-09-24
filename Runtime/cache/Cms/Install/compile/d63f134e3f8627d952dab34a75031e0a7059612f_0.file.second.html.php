<?php /* Smarty version 3.1.24, created on 2015-09-17 20:14:52
         compiled from "F:/Web/Webroot/Mist/Application/Cms/View/Install/second.html" */ ?>
<?php
/*%%SmartyHeaderCode:353555faaebc1dc086_68369827%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd63f134e3f8627d952dab34a75031e0a7059612f' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Cms/View/Install/second.html',
      1 => 1442490477,
      2 => 'file',
    ),
    'ac254d8bfafc50a46c6321b867dee20e0b7f4db3' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Cms/View/Install/base.html',
      1 => 1442397397,
      2 => 'file',
    ),
    '154c2f76d44e8298ff831ce50dc306dc43620947' => 
    array (
      0 => '154c2f76d44e8298ff831ce50dc306dc43620947',
      1 => 0,
      2 => 'string',
    ),
    '68f3bca6a71be05f2d35367e593ce2914aacc428' => 
    array (
      0 => '68f3bca6a71be05f2d35367e593ce2914aacc428',
      1 => 0,
      2 => 'string',
    ),
    '589c5c6839b3a085b082b35a0e44afc98fbe03b5' => 
    array (
      0 => '589c5c6839b3a085b082b35a0e44afc98fbe03b5',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '353555faaebc1dc086_68369827',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_55faaebc33b178_05687344',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55faaebc33b178_05687344')) {
function content_55faaebc33b178_05687344 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '353555faaebc1dc086_68369827';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OneThink 安装</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/modules/install/css/install.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/bootstrap/js/html5shiv.js"><?php echo '</script'; ?>
>
    <![endif]-->
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/jquery-1.10.2.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/bootstrap/js/bootstrap.js"><?php echo '</script'; ?>
>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!-- Navbar
================================================== -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" target="_blank" href="http://www.onethink.cn">OneThink</a>
            <div class="nav-collapse collapse">
                <ul id="step" class="nav">
                    <?php
$_smarty_tpl->properties['nocache_hash'] = '353555faaebc1dc086_68369827';
?>

    <li class="active"><a href="javascript:void(0);">安装协议</a></li>
    <li class="active"><a href="javascript:void(0);">环境检测</a></li>
    <li class="active"><a href="javascript:void(0);">创建数据库</a></li>
    <li><a href="javascript:void(0);">安装</a></li>
    <li><a href="javascript:void(0);">完成</a></li>

                </ul>
            </div>
        </div>
    </div>
</div>

<div class="jumbotron masthead">
    <div class="container">
        <?php
$_smarty_tpl->properties['nocache_hash'] = '353555faaebc1dc086_68369827';
?>

    <h1>创建数据库</h1>
    <form action="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'cms/install/second'),$_smarty_tpl);?>
" method="post" target="_self">
        <div class="create-database">
            <div>
                <select name="db[]">
                    <option>mysql</option>
                </select>
                <span>数据库类型</span>
            </div>
            <div>
                <input type="text" name="db[]" value="127.0.0.1">
                <span>数据库服务器，数据库服务器IP，一般为127.0.0.1</span>
            </div>
            <div>
                <input type="text" name="db[]" value="">
                <span>数据库名</span>
            </div>
            <div>
                <input type="text" name="db[]" value="">
                <span>数据库用户名</span>
            </div>
            <div>
                <input type="password" name="db[]" value="">
                <span>数据库密码</span>
            </div>
            <div>
                <input type="text" name="db[]" value="">
                <span>数据库端口，数据库服务连接端口，一般为3306</span>
            </div>

            <div>
                <input type="text" name="db[]" value="onethink_">
                <span>数据表前缀，同一个数据库运行多个系统时请修改为不同的前缀</span>
            </div>
        </div>

        <div class="create-database">
            <h2>创始人帐号信息</h2>
            <div>
                <input type="text" name="admin[]" value="Administrator">
                <span>用户名</span>
            </div>
            <div>
                <input type="password" name="admin[]" value="">
                <span>密码</span>
            </div>
            <div>
                <input type="password" name="admin[]" value="">
                <span>确认密码</span>
            </div>
            <div>
                <input type="text" name="admin[]" value="" />
                <span>邮箱，请填写正确的邮箱便于收取提醒邮件</span>
            </div>
        </div>
    </form>

    </div>
</div>


<!-- Footer
================================================== -->
<footer class="footer navbar-fixed-bottom">
    <div class="container">
        <div>
            <?php
$_smarty_tpl->properties['nocache_hash'] = '353555faaebc1dc086_68369827';
?>

    <a class="btn btn-success btn-large" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'cms/install/third'),$_smarty_tpl);?>
">上一步</a>
    <button id="submit" type="button" class="btn btn-primary btn-large" onclick="$('form').submit();return false;">
        下一步
    </button>

        </div>
    </div>
</footer>
</body>
</html>
<?php }
}
?>