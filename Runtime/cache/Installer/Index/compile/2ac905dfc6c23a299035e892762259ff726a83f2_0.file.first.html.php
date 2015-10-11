<?php /* Smarty version 3.1.24, created on 2015-10-11 19:13:35
         compiled from "F:/Web/Webroot/Mist/Application/Installer/View/Index/first.html" */ ?>
<?php
/*%%SmartyHeaderCode:26459561a445f684915_60547801%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2ac905dfc6c23a299035e892762259ff726a83f2' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Index/first.html',
      1 => 1444561939,
      2 => 'file',
    ),
    'b835d4b9fe4c28bb6f0f7d06d927e08e74bc7677' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Index/base.html',
      1 => 1442397397,
      2 => 'file',
    ),
    '1ead2e2e3bc00e467a8ada9f107d0db3c0cac674' => 
    array (
      0 => '1ead2e2e3bc00e467a8ada9f107d0db3c0cac674',
      1 => 0,
      2 => 'string',
    ),
    '879dc30b92fd50e21c12659fd758490c3ff7c20e' => 
    array (
      0 => '879dc30b92fd50e21c12659fd758490c3ff7c20e',
      1 => 0,
      2 => 'string',
    ),
    'da17dacb25446c32fe374300f87ecfa423efe43f' => 
    array (
      0 => 'da17dacb25446c32fe374300f87ecfa423efe43f',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '26459561a445f684915_60547801',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561a445fa32b63_18281027',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561a445fa32b63_18281027')) {
function content_561a445fa32b63_18281027 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '26459561a445f684915_60547801';
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
$_smarty_tpl->properties['nocache_hash'] = '26459561a445f684915_60547801';
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
$_smarty_tpl->properties['nocache_hash'] = '26459561a445f684915_60547801';
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
$_smarty_tpl->properties['nocache_hash'] = '26459561a445f684915_60547801';
?>

    <a class="btn btn-success btn-large" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'installer/index/index'),$_smarty_tpl);?>
">上一步</a>
    <a class="btn btn-primary btn-large" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'installer/index/second'),$_smarty_tpl);?>
">下一步</a>

        </div>
    </div>
</footer>
</body>
</html>
<?php }
}
?>