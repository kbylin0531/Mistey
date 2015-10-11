<?php /* Smarty version 3.1.24, created on 2015-10-11 19:30:31
         compiled from "F:/Web/Webroot/Mist/Application/Installer/View/Install/third.html" */ ?>
<?php
/*%%SmartyHeaderCode:10406561a485711b614_67270157%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b21d6b266dbaf34ecccfccee1d6cb6c9b61b8066' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Install/third.html',
      1 => 1442396278,
      2 => 'file',
    ),
    '79ed9e960377688596021cabf2379c178813033d' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Install/base.html',
      1 => 1442397397,
      2 => 'file',
    ),
    'f5e549c6408e7975bea5c4a49f27439e4b0d7ab3' => 
    array (
      0 => 'f5e549c6408e7975bea5c4a49f27439e4b0d7ab3',
      1 => 0,
      2 => 'string',
    ),
    '128f0f04aa6e0800a37c45880130e5281612e89f' => 
    array (
      0 => '128f0f04aa6e0800a37c45880130e5281612e89f',
      1 => 0,
      2 => 'string',
    ),
    '1bbb2a553db61e22f2856c9767d4c9a26d68f652' => 
    array (
      0 => '1bbb2a553db61e22f2856c9767d4c9a26d68f652',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '10406561a485711b614_67270157',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561a4857268b73_31361432',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561a4857268b73_31361432')) {
function content_561a4857268b73_31361432 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '10406561a485711b614_67270157';
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
$_smarty_tpl->properties['nocache_hash'] = '10406561a485711b614_67270157';
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
$_smarty_tpl->properties['nocache_hash'] = '10406561a485711b614_67270157';
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
$_smarty_tpl->properties['nocache_hash'] = '10406561a485711b614_67270157';
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