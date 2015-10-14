<?php /* Smarty version 3.1.24, created on 2015-10-14 22:09:25
         compiled from "F:/Web/Webroot/Mist/Application/Admin/View/Index/left.html" */ ?>
<?php
/*%%SmartyHeaderCode:31533561e6215197405_33854094%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '81995c615fa4501e66e968aa0871a398d7474b1b' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Admin/View/Index/left.html',
      1 => 1444831692,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31533561e6215197405_33854094',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561e621524ec63_01987393',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561e621524ec63_01987393')) {
function content_561e621524ec63_01987393 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '31533561e6215197405_33854094';
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
<div class="lefttop"><span></span>通讯录</div>

<dl class="leftmenu">

    <dd>
        <div class="title">
            <span><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/leftico01.png" /></span>管理信息
        </div>
        <ul class="menuson">
            <li><cite></cite><a href="index.html" target="rightFrame">首页模版</a><i></i></li>
            <li class="active"><cite></cite><a href="right.html" target="rightFrame">数据列表</a><i></i></li>
            <li><cite></cite><a href="imgtable.html" target="rightFrame">图片数据表</a><i></i></li>
            <li><cite></cite><a href="form.html" target="rightFrame">添加编辑</a><i></i></li>
            <li><cite></cite><a href="imglist.html" target="rightFrame">图片列表</a><i></i></li>
            <li><cite></cite><a href="imglist1.html" target="rightFrame">自定义</a><i></i></li>
            <li><cite></cite><a href="tools.html" target="rightFrame">常用工具</a><i></i></li>
            <li><cite></cite><a href="filelist.html" target="rightFrame">信息管理</a><i></i></li>
            <li><cite></cite><a href="tab.html" target="rightFrame">Tab页</a><i></i></li>
            <li><cite></cite><a href="error.html" target="rightFrame">404页面</a><i></i></li>
        </ul>
    </dd>


    <dd>
        <div class="title">
            <span><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/leftico02.png" /></span>其他设置
        </div>
        <ul class="menuson">
            <li><cite></cite><a href="#">编辑内容</a><i></i></li>
            <li><cite></cite><a href="#">发布信息</a><i></i></li>
            <li><cite></cite><a href="#">档案列表显示</a><i></i></li>
        </ul>
    </dd>


    <dd><div class="title"><span><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/leftico03.png" /></span>编辑器</div>
        <ul class="menuson">
            <li><cite></cite><a href="#">自定义</a><i></i></li>
            <li><cite></cite><a href="#">常用资料</a><i></i></li>
            <li><cite></cite><a href="#">信息列表</a><i></i></li>
            <li><cite></cite><a href="#">其他</a><i></i></li>
        </ul>
    </dd>


    <dd><div class="title"><span><img src="<?php echo @constant('URL_CMS_ADMIN_IMG_PATH');?>
/leftico04.png" /></span>日期管理</div>
        <ul class="menuson">
            <li><cite></cite><a href="#">自定义</a><i></i></li>
            <li><cite></cite><a href="#">常用资料</a><i></i></li>
            <li><cite></cite><a href="#">信息列表</a><i></i></li>
            <li><cite></cite><a href="#">其他</a><i></i></li>
        </ul>

    </dd>

</dl>
</body>
</html>
<?php }
}
?>