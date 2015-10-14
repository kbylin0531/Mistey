<?php /* Smarty version 3.1.24, created on 2015-10-14 22:09:25
         compiled from "F:/Web/Webroot/Mist/Application/Admin/View/Index/top.html" */ ?>
<?php
/*%%SmartyHeaderCode:31159561e621514a1e2_30329213%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5986af87b8d0d045fe34e0dea48b98c2f978de47' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Admin/View/Index/top.html',
      1 => 1444831680,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31159561e621514a1e2_30329213',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561e6215207cd6_68851875',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561e6215207cd6_68851875')) {
function content_561e6215207cd6_68851875 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '31159561e621514a1e2_30329213';
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
            //顶部导航切换
            $(".nav li a").click(function(){
                $(".nav li a.selected").removeClass("selected")
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
    <li><a href="default.html" target="rightFrame" class="selected"><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/icon01.png" title="工作台" /><h2>工作台</h2></a></li>
    <li><a href="imgtable.html" target="rightFrame"><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/icon02.png" title="模型管理" /><h2>模型管理</h2></a></li>
    <li><a href="imglist.html"  target="rightFrame"><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/icon03.png" title="模块设计" /><h2>模块设计</h2></a></li>
    <li><a href="tools.html"  target="rightFrame"><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/icon04.png" title="常用工具" /><h2>常用工具</h2></a></li>
    <li><a href="computer.html" target="rightFrame"><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/icon05.png" title="文件管理" /><h2>文件管理</h2></a></li>
    <li><a href="tab.html"  target="rightFrame"><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/icon06.png" title="系统设置" /><h2>系统设置</h2></a></li>
</ul>

<div class="topright">
    <ul>
        <li><span><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/help.png" title="帮助"  class="helpimg"/></span><a href="#">帮助</a></li>
        <li><a href="#">关于</a></li>
        <li><a href="login.html" target="_parent">退出</a></li>
    </ul>

    <div class="user">
        <span>admin</span>
        <i>消息</i>
        <b>5</b>
    </div>

</div>
</body>
</html>
<?php }
}
?>