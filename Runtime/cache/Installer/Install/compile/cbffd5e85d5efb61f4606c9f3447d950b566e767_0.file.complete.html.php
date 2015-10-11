<?php /* Smarty version 3.1.24, created on 2015-10-11 19:30:32
         compiled from "F:/Web/Webroot/Mist/Application/Installer/View/Install/complete.html" */ ?>
<?php
/*%%SmartyHeaderCode:10546561a4858d2c9c7_77603744%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cbffd5e85d5efb61f4606c9f3447d950b566e767' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Install/complete.html',
      1 => 1444543265,
      2 => 'file',
    ),
    '79ed9e960377688596021cabf2379c178813033d' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Install/base.html',
      1 => 1442397397,
      2 => 'file',
    ),
    'b33630732f8483397d70183f654a69ac568d0329' => 
    array (
      0 => 'b33630732f8483397d70183f654a69ac568d0329',
      1 => 0,
      2 => 'string',
    ),
    'f35c060d53d94ca9fc4d686204e96e7939966c54' => 
    array (
      0 => 'f35c060d53d94ca9fc4d686204e96e7939966c54',
      1 => 0,
      2 => 'string',
    ),
    'c302dbeadeba52f9dc165833caa9bc573140a392' => 
    array (
      0 => 'c302dbeadeba52f9dc165833caa9bc573140a392',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '10546561a4858d2c9c7_77603744',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561a4858e26315_32852556',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561a4858e26315_32852556')) {
function content_561a4858e26315_32852556 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '10546561a4858d2c9c7_77603744';
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
$_smarty_tpl->properties['nocache_hash'] = '10546561a4858d2c9c7_77603744';
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
$_smarty_tpl->properties['nocache_hash'] = '10546561a4858d2c9c7_77603744';
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
$_smarty_tpl->properties['nocache_hash'] = '10546561a4858d2c9c7_77603744';
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