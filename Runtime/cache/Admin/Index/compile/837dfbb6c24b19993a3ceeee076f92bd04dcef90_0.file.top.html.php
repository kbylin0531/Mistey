<?php /* Smarty version 3.1.24, created on 2015-10-15 16:30:08
         compiled from "E:/Web/Webroot/Mistey/Application/Admin/View/Index/top.html" */ ?>
<?php
/*%%SmartyHeaderCode:4638561f64101c5de5_36621249%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '837dfbb6c24b19993a3ceeee076f92bd04dcef90' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Admin/View/Index/top.html',
      1 => 1444897746,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4638561f64101c5de5_36621249',
  'variables' => 
  array (
    'modules' => 0,
    'order' => 0,
    'module' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561f641022c998_92741619',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561f641022c998_92741619')) {
function content_561f641022c998_92741619 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '4638561f64101c5de5_36621249';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <link href="<?php echo @constant('URL_CMS_ADMIN_CSS_PATH');?>
/style.css" rel="stylesheet" type="text/css" />
    <?php echo '<script'; ?>
 language="JavaScript" src="<?php echo @constant('URL_CMS_ADMIN_JS_PATH');?>
/jquery.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript">
        $(function(){
            //顶部导航切换
            $(".nav li a").click(function(){
                $(".nav li a.selected").removeClass("selected");
                $(this).addClass("selected");
            })

        })
    <?php echo '</script'; ?>
>


</head>
<body style="background:url(<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/topbg.gif) repeat-x;">
<div class="topleft">
    <a href="main.html" target="_parent"><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/logo.png" title="系统首页" /></a>
</div>

<ul class="nav">
    <?php
$_from = $_smarty_tpl->tpl_vars['modules']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['module'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['module']->_loop = false;
$_smarty_tpl->tpl_vars['order'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['order']->value => $_smarty_tpl->tpl_vars['module']->value) {
$_smarty_tpl->tpl_vars['module']->_loop = true;
$foreach_module_Sav = $_smarty_tpl->tpl_vars['module'];
?>
        <li>
            <?php if (0 === $_smarty_tpl->tpl_vars['order']->value) {?>
                <a href="<?php echo $_smarty_tpl->tpl_vars['module']->value['href'];?>
" target="leftFrame" class="selected">
            <?php } else { ?>
                <a href="<?php echo $_smarty_tpl->tpl_vars['module']->value['href'];?>
" target="leftFrame">
            <?php }?>
                <img src="<?php echo $_smarty_tpl->tpl_vars['module']->value['imgsrc'];?>
"  />
                <h2><?php echo $_smarty_tpl->tpl_vars['module']->value['title'];?>
</h2>
            </a>
        </li>
    <?php
$_smarty_tpl->tpl_vars['module'] = $foreach_module_Sav;
}
?>
</ul>

<div class="topright">
    <ul>
        <li><span><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/help.png" title="帮助"  class="helpimg"/></span><a href="#">帮助</a></li>
        <li><a href="#">关于</a></li>
        <li><a href="login.html" target="_parent">退出</a></li>
    </ul>

    <div class="user">
        <span id="username">admin</span>
        <i id="mtip">消息</i>
        <b id="mnum">5</b>
    </div>

</div>
</body>
</html>
<?php }
}
?>