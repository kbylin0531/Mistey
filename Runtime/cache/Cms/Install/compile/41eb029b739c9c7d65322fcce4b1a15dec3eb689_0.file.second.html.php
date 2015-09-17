<?php /* Smarty version 3.1.24, created on 2015-09-17 13:52:52
         compiled from "E:/Web/Webroot/Mistey/Application/Cms/View/Install/second.html" */ ?>
<?php
/*%%SmartyHeaderCode:1287855fa5534359cd0_49121215%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '41eb029b739c9c7d65322fcce4b1a15dec3eb689' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Cms/View/Install/second.html',
      1 => 1442469168,
      2 => 'file',
    ),
    '9087b98d0d59332bbb6cedb6ac409a69c3e2a10d' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Cms/View/Install/base.html',
      1 => 1442458347,
      2 => 'file',
    ),
    'b5d220eb349cd9663760b692c08f13ca72c8b4a9' => 
    array (
      0 => 'b5d220eb349cd9663760b692c08f13ca72c8b4a9',
      1 => 0,
      2 => 'string',
    ),
    '3a016255f9620c4ebff40209e4a4ff4cce076c35' => 
    array (
      0 => '3a016255f9620c4ebff40209e4a4ff4cce076c35',
      1 => 0,
      2 => 'string',
    ),
    '9877b6ce73042bdd1c2ee75a7d75e26e645f4f82' => 
    array (
      0 => '9877b6ce73042bdd1c2ee75a7d75e26e645f4f82',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '1287855fa5534359cd0_49121215',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_55fa5534410047_97800863',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa5534410047_97800863')) {
function content_55fa5534410047_97800863 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1287855fa5534359cd0_49121215';
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
$_smarty_tpl->properties['nocache_hash'] = '1287855fa5534359cd0_49121215';
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
$_smarty_tpl->properties['nocache_hash'] = '1287855fa5534359cd0_49121215';
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
$_smarty_tpl->properties['nocache_hash'] = '1287855fa5534359cd0_49121215';
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