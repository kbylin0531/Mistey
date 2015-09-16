<?php /* Smarty version 3.1.24, created on 2015-09-16 15:14:58
         compiled from "E:/Web/Webroot/Mistey/Application/CMS/View/Install/second.html" */ ?>
<?php
/*%%SmartyHeaderCode:733555f916f2085f34_73132294%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ca60cd11f4298ec1d69088d5d9db12ac7fc7c2a1' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/CMS/View/Install/second.html',
      1 => 1442387696,
      2 => 'file',
    ),
    '2ed3ac0621486126734ae5b0abd94edf638d232d' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/CMS/View/Install/base.html',
      1 => 1442383113,
      2 => 'file',
    ),
    'e614418c8b4e26e40db97a5c465cd67b0a40731b' => 
    array (
      0 => 'e614418c8b4e26e40db97a5c465cd67b0a40731b',
      1 => 0,
      2 => 'string',
    ),
    '6da90e19e071e560c94be857ffadaa631593555e' => 
    array (
      0 => '6da90e19e071e560c94be857ffadaa631593555e',
      1 => 0,
      2 => 'string',
    ),
    '1f3b904cc5afe592a2d9dcd385ef18335cf448a8' => 
    array (
      0 => '1f3b904cc5afe592a2d9dcd385ef18335cf448a8',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '733555f916f2085f34_73132294',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_55f916f2119e30_26756464',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f916f2119e30_26756464')) {
function content_55f916f2119e30_26756464 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '733555f916f2085f34_73132294';
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
    <link href="<?php echo @constant('URL_STATIC_PATH');?>
/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo @constant('URL_STATIC_PATH');?>
/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo @constant('URL_STATIC_PATH');?>
/modules/install/css/install.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_STATIC_PATH');?>
/bootstrap/js/html5shiv.js"><?php echo '</script'; ?>
>
    <![endif]-->
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_STATIC_PATH');?>
/jquery-1.10.2.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_STATIC_PATH');?>
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
$_smarty_tpl->properties['nocache_hash'] = '733555f916f2085f34_73132294';
?>

    <li class="active"><a href="javascript:;">安装协议</a></li>
    <li class="active"><a href="javascript:;">环境检测</a></li>
    <li class="active"><a href="javascript:;">创建数据库</a></li>
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
$_smarty_tpl->properties['nocache_hash'] = '733555f916f2085f34_73132294';
?>

    <h1>创建数据库</h1>
    <form action="<?php echo $_smarty_tpl->tpl_vars['self_url']->value;?>
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
$_smarty_tpl->properties['nocache_hash'] = '733555f916f2085f34_73132294';
?>

    <a class="btn btn-success btn-large" href="<?php echo $_smarty_tpl->tpl_vars['prev_url']->value;?>
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