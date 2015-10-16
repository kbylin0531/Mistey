<?php /* Smarty version 3.1.24, created on 2015-10-16 08:52:24
         compiled from "E:/Web/Webroot/Mistey/Application/Admin/View/Index/left.html" */ ?>
<?php
/*%%SmartyHeaderCode:3165356204a48916e45_50980354%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3519e48de475b632e5ac474f26618aabf45ba76c' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Admin/View/Index/left.html',
      1 => 1444899447,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3165356204a48916e45_50980354',
  'variables' => 
  array (
    'title' => 0,
    'submodules' => 0,
    'submodule' => 0,
    'itemorder' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_56204a4ab49e29_48543279',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_56204a4ab49e29_48543279')) {
function content_56204a4ab49e29_48543279 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '3165356204a48916e45_50980354';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>
    <link href="<?php echo @constant('URL_CMS_ADMIN_CSS_PATH');?>
/style.css" rel="stylesheet" type="text/css" />
    <?php echo '<script'; ?>
 language="JavaScript" src="<?php echo @constant('URL_CMS_ADMIN_JS_PATH');?>
/jquery.js"><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
 type="text/javascript">
        $(function(){
            //导航切换
            $(".menuson li").click(function(){
                $(".menuson li.active").removeClass("active")
                $(this).addClass("active");
            });
            $('.title').click(function(){
                var $ul = $(this).next('ul');
                $('dd').find('ul').slideUp();
                if($ul.is(':visible')){
                    $(this).next('ul').slideUp();
                }else{
                    $(this).next('ul').slideDown();
                }
            });
        })
    <?php echo '</script'; ?>
>
</head>

<body style="background:#f0f9fd;">
<div class="lefttop"><span></span><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</div>

<dl class="leftmenu">
    <?php
$_from = $_smarty_tpl->tpl_vars['submodules']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['submodule'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['submodule']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['submodule']->value) {
$_smarty_tpl->tpl_vars['submodule']->_loop = true;
$foreach_submodule_Sav = $_smarty_tpl->tpl_vars['submodule'];
?>
    <dd>
        <div class="title">
            <span><img src="<?php echo $_smarty_tpl->tpl_vars['submodule']->value['src'];?>
" /></span><?php echo $_smarty_tpl->tpl_vars['submodule']->value['name'];?>

        </div>
        <ul class="menuson">
        <?php
$_from = $_smarty_tpl->tpl_vars['submodule']->value['items'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
$_smarty_tpl->tpl_vars['itemorder'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['itemorder']->value => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
            <?php if ($_smarty_tpl->tpl_vars['itemorder']->value === 0) {?>
                <li class="active"><cite></cite><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['href'];?>
" target="rightFrame"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</a></li>
            <?php } else { ?>
                <li><cite></cite><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['href'];?>
" target="rightFrame"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</a></li>
            <?php }?>
        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
        </ul>
    </dd>
    <?php
$_smarty_tpl->tpl_vars['submodule'] = $foreach_submodule_Sav;
}
?>
</dl>
</body>
</html>
<?php }
}
?>