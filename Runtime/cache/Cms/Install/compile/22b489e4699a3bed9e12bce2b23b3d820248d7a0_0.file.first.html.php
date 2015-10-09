<?php /* Smarty version 3.1.24, created on 2015-10-09 13:55:16
         compiled from "E:/Web/Webroot/Mistey/Application/Cms/View/Install/first.html" */ ?>
<?php
/*%%SmartyHeaderCode:5384561756c4402240_45857546%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '22b489e4699a3bed9e12bce2b23b3d820248d7a0' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Cms/View/Install/first.html',
      1 => 1444370102,
      2 => 'file',
    ),
    '9087b98d0d59332bbb6cedb6ac409a69c3e2a10d' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Cms/View/Install/base.html',
      1 => 1442458347,
      2 => 'file',
    ),
    '2940af67f08c961de3a18043bd599a1ac25ca200' => 
    array (
      0 => '2940af67f08c961de3a18043bd599a1ac25ca200',
      1 => 0,
      2 => 'string',
    ),
    '9bbf814abe9f8de5e1463d4454f7c8e4cb83118f' => 
    array (
      0 => '9bbf814abe9f8de5e1463d4454f7c8e4cb83118f',
      1 => 0,
      2 => 'string',
    ),
    '9bc0c40c71fcd73a4e46317e48172c20b8deddfd' => 
    array (
      0 => '9bc0c40c71fcd73a4e46317e48172c20b8deddfd',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '5384561756c4402240_45857546',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561756c450bf07_90457120',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561756c450bf07_90457120')) {
function content_561756c450bf07_90457120 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '5384561756c4402240_45857546';
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
$_smarty_tpl->properties['nocache_hash'] = '5384561756c4402240_45857546';
?>

    <li class="active"><a href="javascript:void(0);">安装协议</a></li>
    <li class="active"><a href="javascript:void(0);">环境检测</a></li>
    <li><a href="javascript:void(0);">创建数据库</a></li>
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
$_smarty_tpl->properties['nocache_hash'] = '5384561756c4402240_45857546';
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
$_smarty_tpl->properties['nocache_hash'] = '5384561756c4402240_45857546';
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