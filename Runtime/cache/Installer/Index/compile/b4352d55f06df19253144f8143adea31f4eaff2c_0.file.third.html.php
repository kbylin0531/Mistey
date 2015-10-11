<?php /* Smarty version 3.1.24, created on 2015-10-11 19:15:57
         compiled from "F:/Web/Webroot/Mist/Application/Installer/View/Index/third.html" */ ?>
<?php
/*%%SmartyHeaderCode:20778561a44eddb8873_83862078%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b4352d55f06df19253144f8143adea31f4eaff2c' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Index/third.html',
      1 => 1442396278,
      2 => 'file',
    ),
    'b835d4b9fe4c28bb6f0f7d06d927e08e74bc7677' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Index/base.html',
      1 => 1442397397,
      2 => 'file',
    ),
    'bd82839b46c953d4389cde2b1f85a956dc4bf2ec' => 
    array (
      0 => 'bd82839b46c953d4389cde2b1f85a956dc4bf2ec',
      1 => 0,
      2 => 'string',
    ),
    '585396021c2138cef09bbd6112529f8f48bbdec9' => 
    array (
      0 => '585396021c2138cef09bbd6112529f8f48bbdec9',
      1 => 0,
      2 => 'string',
    ),
    '2da4918382509ca3df2992cb3280b6efb2d1cc3c' => 
    array (
      0 => '2da4918382509ca3df2992cb3280b6efb2d1cc3c',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '20778561a44eddb8873_83862078',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561a44edea88e4_95501990',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561a44edea88e4_95501990')) {
function content_561a44edea88e4_95501990 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '20778561a44eddb8873_83862078';
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
$_smarty_tpl->properties['nocache_hash'] = '20778561a44eddb8873_83862078';
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
$_smarty_tpl->properties['nocache_hash'] = '20778561a44eddb8873_83862078';
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
$_smarty_tpl->properties['nocache_hash'] = '20778561a44eddb8873_83862078';
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