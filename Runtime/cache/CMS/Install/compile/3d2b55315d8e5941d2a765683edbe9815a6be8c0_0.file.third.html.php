<?php /* Smarty version 3.1.24, created on 2015-09-17 20:15:39
         compiled from "F:/Web/Webroot/Mist/Application/Cms/View/Install/third.html" */ ?>
<?php
/*%%SmartyHeaderCode:1431555faaeeb32c9c6_42724722%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3d2b55315d8e5941d2a765683edbe9815a6be8c0' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Cms/View/Install/third.html',
      1 => 1442396278,
      2 => 'file',
    ),
    'ac254d8bfafc50a46c6321b867dee20e0b7f4db3' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Cms/View/Install/base.html',
      1 => 1442397397,
      2 => 'file',
    ),
    '1a8b6d99a580df7b6a49b869280dee85f1033128' => 
    array (
      0 => '1a8b6d99a580df7b6a49b869280dee85f1033128',
      1 => 0,
      2 => 'string',
    ),
    'da67af18f6e790060ac4e95f6a7f58d91760d4ea' => 
    array (
      0 => 'da67af18f6e790060ac4e95f6a7f58d91760d4ea',
      1 => 0,
      2 => 'string',
    ),
    '915f0a38065a76b71b67d853551ed9d025adc83d' => 
    array (
      0 => '915f0a38065a76b71b67d853551ed9d025adc83d',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '1431555faaeeb32c9c6_42724722',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_55faaeeb4786d8_76307554',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55faaeeb4786d8_76307554')) {
function content_55faaeeb4786d8_76307554 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1431555faaeeb32c9c6_42724722';
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
$_smarty_tpl->properties['nocache_hash'] = '1431555faaeeb32c9c6_42724722';
?>

    <li class="active"><a href="javascript:void(0);">安装协议</a></li>
    <li class="active"><a href="javascript:void(0);">环境检测</a></li>
    <li class="active"><a href="javascript:void(0);">创建数据库</a></li>
    <li class="active"><a href="javascript:void(0);">安装</a></li>
    <li><a href="javascript:void(0);">完成</a></li>

                </ul>
            </div>
        </div>
    </div>
</div>

<div class="jumbotron masthead">
    <div class="container">
        <?php
$_smarty_tpl->properties['nocache_hash'] = '1431555faaeeb32c9c6_42724722';
?>

    <h1>安装</h1>
    <div id="show-list" class="install-database"></div>

    <?php echo '<script'; ?>
 type="text/javascript">
        var list   = document.getElementById('show-list');
        function showmsg(msg, classname){
            var li = document.createElement('p');
            li.innerHTML = msg;
            classname && li.setAttribute('class', classname);
            list.appendChild(li);
            document.scrollTop += 30;
        }
    <?php echo '</script'; ?>
>

    </div>
</div>


<!-- Footer
================================================== -->
<footer class="footer navbar-fixed-bottom">
    <div class="container">
        <div>
            <?php
$_smarty_tpl->properties['nocache_hash'] = '1431555faaeeb32c9c6_42724722';
?>

    <button class="btn btn-warning btn-large disabled">正在安装，请稍后...</button>

        </div>
    </div>
</footer>
</body>
</html>
<?php }
}
?>