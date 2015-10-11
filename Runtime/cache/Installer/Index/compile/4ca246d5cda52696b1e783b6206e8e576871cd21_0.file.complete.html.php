<?php /* Smarty version 3.1.24, created on 2015-10-11 19:16:00
         compiled from "F:/Web/Webroot/Mist/Application/Installer/View/Index/complete.html" */ ?>
<?php
/*%%SmartyHeaderCode:22006561a44f087cec1_20423859%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4ca246d5cda52696b1e783b6206e8e576871cd21' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Index/complete.html',
      1 => 1444543265,
      2 => 'file',
    ),
    'b835d4b9fe4c28bb6f0f7d06d927e08e74bc7677' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Index/base.html',
      1 => 1442397397,
      2 => 'file',
    ),
    '7197b800286c3368ece27a82b78378429f92ae2a' => 
    array (
      0 => '7197b800286c3368ece27a82b78378429f92ae2a',
      1 => 0,
      2 => 'string',
    ),
    '74e40884b99aab7bdbfa0e3e2c796398e63c7e3e' => 
    array (
      0 => '74e40884b99aab7bdbfa0e3e2c796398e63c7e3e',
      1 => 0,
      2 => 'string',
    ),
    'a867a5f1fbd4646b27a2c8f1221ab37a94f9b366' => 
    array (
      0 => 'a867a5f1fbd4646b27a2c8f1221ab37a94f9b366',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '22006561a44f087cec1_20423859',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561a44f0a0c9c6_85578919',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561a44f0a0c9c6_85578919')) {
function content_561a44f0a0c9c6_85578919 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '22006561a44f087cec1_20423859';
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
$_smarty_tpl->properties['nocache_hash'] = '22006561a44f087cec1_20423859';
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
$_smarty_tpl->properties['nocache_hash'] = '22006561a44f087cec1_20423859';
?>

    <h1>完成</h1>
    <p>安装完成！</p>

    </div>
</div>


<!-- Footer
================================================== -->
<footer class="footer navbar-fixed-bottom">
    <div class="container">
        <div>
            <?php
$_smarty_tpl->properties['nocache_hash'] = '22006561a44f087cec1_20423859';
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