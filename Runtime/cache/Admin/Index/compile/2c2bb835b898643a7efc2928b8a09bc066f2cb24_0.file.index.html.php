<?php /* Smarty version 3.1.24, created on 2015-10-14 22:25:25
         compiled from "F:/Web/Webroot/Mist/Application/Admin/View/Index/index.html" */ ?>
<?php
/*%%SmartyHeaderCode:24022561e65d5559fe3_38059398%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2c2bb835b898643a7efc2928b8a09bc066f2cb24' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Admin/View/Index/index.html',
      1 => 1444832713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '24022561e65d5559fe3_38059398',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561e65d55aa2d2_91194223',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561e65d55aa2d2_91194223')) {
function content_561e65d55aa2d2_91194223 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '24022561e65d5559fe3_38059398';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>网站后台管理系统HTML模板--我爱模板网 www.5imoban.net</title>
</head>
<frameset rows="88,*" cols="*" frameborder="no" border="0" framespacing="0">
    <frame src="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'admin/index/top','mode'=>'1'),$_smarty_tpl);?>
" name="topFrame" scrolling="No" noresize="noresize" id="topFrame" title="topFrame" />
    <frameset cols="187,*" frameborder="no" border="0" framespacing="0">
        <frame src="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'admin/index/left','mode'=>'1'),$_smarty_tpl);?>
" name="leftFrame" scrolling="No" noresize="noresize" id="leftFrame" title="leftFrame" />
        <frame src="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'admin/index/main','mode'=>'1'),$_smarty_tpl);?>
" name="rightFrame" id="rightFrame" title="rightFrame" />
    </frameset>
</frameset>
<noframes><body>
</body></noframes>
</html>
<?php }
}
?>