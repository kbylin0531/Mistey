<?php /* Smarty version 3.1.24, created on 2015-10-09 16:50:24
         compiled from "E:/Web/Webroot/Mistey/Application/Cms/View/Install/complete.html" */ ?>
<?php
/*%%SmartyHeaderCode:1829756177fd0998b36_23401284%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ecbfb57140d3041eb6f45e87b0bfc1fa51a4a690' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Cms/View/Install/complete.html',
      1 => 1442480068,
      2 => 'file',
    ),
    '9087b98d0d59332bbb6cedb6ac409a69c3e2a10d' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Cms/View/Install/base.html',
      1 => 1442458347,
      2 => 'file',
    ),
    '33c54b984918e1b0ca6616507e23e82503f56dfb' => 
    array (
      0 => '33c54b984918e1b0ca6616507e23e82503f56dfb',
      1 => 0,
      2 => 'string',
    ),
    '1312035f12ae60294020fba950a41466dd21e94a' => 
    array (
      0 => '1312035f12ae60294020fba950a41466dd21e94a',
      1 => 0,
      2 => 'string',
    ),
    '51cd7e5dbd39e746147946f513d290d2d761e321' => 
    array (
      0 => '51cd7e5dbd39e746147946f513d290d2d761e321',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '1829756177fd0998b36_23401284',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_56177fd0b751d2_37153077',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_56177fd0b751d2_37153077')) {
function content_56177fd0b751d2_37153077 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1829756177fd0998b36_23401284';
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
$_smarty_tpl->properties['nocache_hash'] = '1829756177fd0998b36_23401284';
?>

    <li class="active"><a href="javascript:void(0);">安装协议</a></li>
    <li class="active"><a href="javascript:void(0);">环境检测</a></li>
    <li class="active"><a href="javascript:void(0);">创建数据库</a></li>
    <li class="active"><a href="javascript:void(0);">安装</a></li>
    <li class="active"><a href="javascript:void(0);">完成</a></li>

                </ul>
            </div>
        </div>
    </div>
</div>

<div class="jumbotron masthead">
    <div class="container">
        <?php
$_smarty_tpl->properties['nocache_hash'] = '1829756177fd0998b36_23401284';
?>

    <h1>完成</h1>
    <p>安装完成！</p>
    <?php if ($_smarty_tpl->tpl_vars['info']->value != false) {?>
        <?php echo $_smarty_tpl->tpl_vars['info']->value;?>

    <?php }?>

    </div>
</div>


<!-- Footer
================================================== -->
<footer class="footer navbar-fixed-bottom">
    <div class="container">
        <div>
            <?php
$_smarty_tpl->properties['nocache_hash'] = '1829756177fd0998b36_23401284';
?>

    <a class="btn btn-primary btn-large" href="javascript:void(0);">登录后台</a>
    <a class="btn btn-success btn-large" href="javascript:void(0);">访问首页</a>
    <?php echo '<script'; ?>
 type="text/javascript" src="http://tajs.qq.com/stats?sId=30545910" charset="UTF-8"><?php echo '</script'; ?>
>

        </div>
    </div>
</footer>
</body>
</html>
<?php }
}
?>