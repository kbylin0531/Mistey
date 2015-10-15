<?php /* Smarty version 3.1.24, created on 2015-10-15 20:46:23
         compiled from "F:/Web/Webroot/Mist/Application/Admin/View/Index/left.html" */ ?>
<?php
/*%%SmartyHeaderCode:27267561fa01fd50047_76434321%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '81995c615fa4501e66e968aa0871a398d7474b1b' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Admin/View/Index/left.html',
      1 => 1444913182,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '27267561fa01fd50047_76434321',
  'variables' => 
  array (
    'title' => 0,
    'submodules' => 0,
    'submodule' => 0,
    'mainhref' => 0,
    'href' => 0,
    'name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561fa01fe31f54_71986743',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561fa01fe31f54_71986743')) {
function content_561fa01fe31f54_71986743 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '27267561fa01fd50047_76434321';
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
    <?php $_smarty_tpl->tpl_vars['mainhref'] = new Smarty_Variable(null, null, 0);?>
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
$_smarty_tpl->tpl_vars['href'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['href']->_loop = false;
$_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['name']->value => $_smarty_tpl->tpl_vars['href']->value) {
$_smarty_tpl->tpl_vars['href']->_loop = true;
$foreach_href_Sav = $_smarty_tpl->tpl_vars['href'];
?>
                <?php if ($_smarty_tpl->tpl_vars['mainhref']->value === null) {?>
                    <li class="active"><cite></cite><a href="<?php echo $_smarty_tpl->tpl_vars['href']->value;?>
" target="rightFrame"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</a></li>
                <?php $_smarty_tpl->tpl_vars['mainhref'] = new Smarty_Variable($_smarty_tpl->tpl_vars['href']->value, null, 0);?>
                <?php } else { ?>
                    <li><cite></cite><a href="<?php echo $_smarty_tpl->tpl_vars['href']->value;?>
" target="rightFrame"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</a></li>
                <?php }?>

            <?php
$_smarty_tpl->tpl_vars['href'] = $foreach_href_Sav;
}
?>
        </ul>
    </dd>
    <?php
$_smarty_tpl->tpl_vars['submodule'] = $foreach_submodule_Sav;
}
?>

    
    <?php echo '<script'; ?>
>
        var rightFrame = parent.document.getElementById('rightFrame');
        rightFrame.src = "<?php echo $_smarty_tpl->tpl_vars['mainhref']->value;?>
";
//        console.log(rightFrame);
    <?php echo '</script'; ?>
>
</dl>
</body>
</html>
<?php }
}
?>