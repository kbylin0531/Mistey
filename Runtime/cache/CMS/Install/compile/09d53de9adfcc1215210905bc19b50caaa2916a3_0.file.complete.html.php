<?php /* Smarty version 3.1.24, created on 2015-09-17 21:14:02
         compiled from "F:/Web/Webroot/Mist/Application/Cms/View/Install/complete.html" */ ?>
<?php
/*%%SmartyHeaderCode:1026555fabc9aa67eb7_99152011%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '09d53de9adfcc1215210905bc19b50caaa2916a3' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Cms/View/Install/complete.html',
      1 => 1442490477,
      2 => 'file',
    ),
    'ac254d8bfafc50a46c6321b867dee20e0b7f4db3' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Cms/View/Install/base.html',
      1 => 1442397397,
      2 => 'file',
    ),
    'c07bfbe4306968324259f0832a9f3d8e4d4ab2f8' => 
    array (
      0 => 'c07bfbe4306968324259f0832a9f3d8e4d4ab2f8',
      1 => 0,
      2 => 'string',
    ),
    '1de2bb4d94a26a094db51e2f8ef4e60f48e639c5' => 
    array (
      0 => '1de2bb4d94a26a094db51e2f8ef4e60f48e639c5',
      1 => 0,
      2 => 'string',
    ),
    'dc80b4904230e9b43c034a0812b48abc39316743' => 
    array (
      0 => 'dc80b4904230e9b43c034a0812b48abc39316743',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '1026555fabc9aa67eb7_99152011',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_55fabc9ac62fe7_33634200',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fabc9ac62fe7_33634200')) {
function content_55fabc9ac62fe7_33634200 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1026555fabc9aa67eb7_99152011';
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
$_smarty_tpl->properties['nocache_hash'] = '1026555fabc9aa67eb7_99152011';
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
$_smarty_tpl->properties['nocache_hash'] = '1026555fabc9aa67eb7_99152011';
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
$_smarty_tpl->properties['nocache_hash'] = '1026555fabc9aa67eb7_99152011';
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