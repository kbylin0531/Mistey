<?php /* Smarty version 3.1.24, created on 2015-09-17 20:14:46
         compiled from "F:/Web/Webroot/Mist/Application/Cms/View/Install/first.html" */ ?>
<?php
/*%%SmartyHeaderCode:344355faaeb6f2e784_49703202%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '19d93d6290963aabeed6ffcd249861194186f660' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Cms/View/Install/first.html',
      1 => 1442490477,
      2 => 'file',
    ),
    'ac254d8bfafc50a46c6321b867dee20e0b7f4db3' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Cms/View/Install/base.html',
      1 => 1442397397,
      2 => 'file',
    ),
    '9d246728161fed5f3047494944df19f5bec0d62f' => 
    array (
      0 => '9d246728161fed5f3047494944df19f5bec0d62f',
      1 => 0,
      2 => 'string',
    ),
    '705e31f600257c974ee1a7691ea1b126b34d4127' => 
    array (
      0 => '705e31f600257c974ee1a7691ea1b126b34d4127',
      1 => 0,
      2 => 'string',
    ),
    '34171c21ff486358ced48236af69f499c828d4ba' => 
    array (
      0 => '34171c21ff486358ced48236af69f499c828d4ba',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '344355faaeb6f2e784_49703202',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_55faaeb7254bf8_84435151',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55faaeb7254bf8_84435151')) {
function content_55faaeb7254bf8_84435151 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '344355faaeb6f2e784_49703202';
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
$_smarty_tpl->properties['nocache_hash'] = '344355faaeb6f2e784_49703202';
?>

    <li class="active"><a href="javascript:void(0);">安装协议</a></li>
    <li class="active"><a href="javascript:void(0);">环境检测</a></li>
    <li><a href="javascript:void(0);">创建数据库</a></li>
    <li>
        <a href="javascript:void(0);">
                安装
        </a>
    </li>
    <li><a href="javascript:void(0);">完成</a></li>

                </ul>
            </div>
        </div>
    </div>
</div>

<div class="jumbotron masthead">
    <div class="container">
        <?php
$_smarty_tpl->properties['nocache_hash'] = '344355faaeb6f2e784_49703202';
?>

    <h1>环境检测</h1>
    <table class="table">
        <caption><h2>运行环境</h2></caption>
        <thead>
        <tr>
            <th>项目</th>
            <th>所需配置</th>
            <th>当前配置</th>
        </tr>
        </thead>
        <tbody>
        
            <?php
$_from = $_smarty_tpl->tpl_vars['env']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value[0];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value[1];?>
</td>
                <td><i class="ico-<?php echo $_smarty_tpl->tpl_vars['item']->value[4];?>
">&nbsp;</i><?php echo $_smarty_tpl->tpl_vars['item']->value[3];?>
</td>
            </tr>
            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
        </tbody>
    </table>
    <table class="table">
        <caption><h2>依赖性</h2></caption>
        <thead>
        <tr>
            <th>名称</th>
            <th>类型</th>
            <th>检查结果</th>
        </tr>
        </thead>
        <tbody>
            
            <?php
$_from = $_smarty_tpl->tpl_vars['funcs']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                <tr>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value[0];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value[3];?>
</td>
                    <td><i class="ico-<?php echo $_smarty_tpl->tpl_vars['item']->value[2];?>
">&nbsp;</i><?php echo $_smarty_tpl->tpl_vars['item']->value[1];?>
</td>
                </tr>
            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
        </tbody>
    </table>
    <?php if ($_smarty_tpl->tpl_vars['dirfile']->value != false) {?>
        <table class="table">
            <caption><h2>目录/文件权限</h2></caption>
            <thead>
            <tr>
                <th>目录/文件</th>
                <th>所需状态</th>
                <th>当前状态</th>
            </tr>
            </thead>
            <tbody>
            
            <?php
$_from = $_smarty_tpl->tpl_vars['dirfile']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value[3];?>
</td>
                <td><i class="ico-success">&nbsp;</i>可写</td>
                <td><i class="ico-<?php echo $_smarty_tpl->tpl_vars['item']->value[2];?>
">&nbsp;</i><?php echo $_smarty_tpl->tpl_vars['item']->value[1];?>
</td>
            </tr>
            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
            </tbody>
        </table>
    <?php }?>


    </div>
</div>


<!-- Footer
================================================== -->
<footer class="footer navbar-fixed-bottom">
    <div class="container">
        <div>
            <?php
$_smarty_tpl->properties['nocache_hash'] = '344355faaeb6f2e784_49703202';
?>

    <a class="btn btn-success btn-large" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'Cms/install/index'),$_smarty_tpl);?>
">上一步</a>
    <a class="btn btn-primary btn-large" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'Cms/install/second'),$_smarty_tpl);?>
">下一步</a>

        </div>
    </div>
</footer>
</body>
</html>
<?php }
}
?>