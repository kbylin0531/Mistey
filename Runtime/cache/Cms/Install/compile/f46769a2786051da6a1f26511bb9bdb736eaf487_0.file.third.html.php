<?php /* Smarty version 3.1.24, created on 2015-09-17 13:53:28
         compiled from "E:/Web/Webroot/Mistey/Application/Cms/View/Install/third.html" */ ?>
<?php
/*%%SmartyHeaderCode:785155fa5558471ad8_97740423%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f46769a2786051da6a1f26511bb9bdb736eaf487' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Cms/View/Install/third.html',
      1 => 1442390018,
      2 => 'file',
    ),
    '9087b98d0d59332bbb6cedb6ac409a69c3e2a10d' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Cms/View/Install/base.html',
      1 => 1442458347,
      2 => 'file',
    ),
    'f79e58532912730008656a9f1e6faf2dcd803150' => 
    array (
      0 => 'f79e58532912730008656a9f1e6faf2dcd803150',
      1 => 0,
      2 => 'string',
    ),
    '871275be747908513ea230ad94073d59304943c6' => 
    array (
      0 => '871275be747908513ea230ad94073d59304943c6',
      1 => 0,
      2 => 'string',
    ),
    '75559b745514772b524eb00cc222a64d19c91dd2' => 
    array (
      0 => '75559b745514772b524eb00cc222a64d19c91dd2',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '785155fa5558471ad8_97740423',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_55fa55586d3d93_82916244',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa55586d3d93_82916244')) {
function content_55fa55586d3d93_82916244 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '785155fa5558471ad8_97740423';
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
$_smarty_tpl->properties['nocache_hash'] = '785155fa5558471ad8_97740423';
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
$_smarty_tpl->properties['nocache_hash'] = '785155fa5558471ad8_97740423';
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
$_smarty_tpl->properties['nocache_hash'] = '785155fa5558471ad8_97740423';
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