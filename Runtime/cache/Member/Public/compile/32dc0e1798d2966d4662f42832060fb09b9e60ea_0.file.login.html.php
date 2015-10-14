<?php /* Smarty version 3.1.24, created on 2015-10-14 21:54:10
         compiled from "F:/Web/Webroot/Mist/Application/Member/View/Public/login.html" */ ?>
<?php
/*%%SmartyHeaderCode:29748561e5e827a0bc1_61926777%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '32dc0e1798d2966d4662f42832060fb09b9e60ea' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Member/View/Public/login.html',
      1 => 1444830847,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '29748561e5e827a0bc1_61926777',
  'variables' => 
  array (
    'error' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561e5e8281daa4_69443603',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561e5e8281daa4_69443603')) {
function content_561e5e8281daa4_69443603 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '29748561e5e827a0bc1_61926777';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>欢迎登录后台管理系统--我爱模板网 www.5imoban.net</title>
    <link href="<?php echo @constant('URL_ASSERTS_SAMPLE_PATH');?>
/css/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo @constant('URL_PUBLIC_PATH');?>
/css/jquery.toastmessage.css" rel="stylesheet" type="text/css" />

    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_ASSERTS_SAMPLE_PATH');?>
/js/jquery.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_ASSERTS_SAMPLE_PATH');?>
/js/cloud.js"  type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_PUBLIC_PATH');?>
js/md5.js"  type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_PUBLIC_PATH');?>
js/jquery.toastmessage.js"  type="text/javascript"><?php echo '</script'; ?>
>


    <?php echo '<script'; ?>
 language="javascript">
        $(function(){
            var error = parseInt("<?php echo $_smarty_tpl->tpl_vars['error']->value;?>
");
            var loginbox = $(".loginbox");
            var dologin = $("#dologin");
            var login_form = $("#login");
            var password = $("#password");
            var verifyimg = $("#verifyimg");



            verifyimg.click(function(){
                var src = verifyimg.attr("src");
                if( src.indexOf('?')>0){
                    $(".verifyimg").attr("src", src+'&random='+Math.random());
                }else{
                    $(".verifyimg").attr("src", src.replace(/\?.*$/,'')+'?'+Math.random());
                }
            });

            dologin.click(function () {
                var pwd = password.val();
                password.val(md5(pwd));
                login_form.submit();
            });

            loginbox.css({'position':'absolute','left':($(window).width()-692)/2});
            $(window).resize(function(){
                loginbox.css({'position':'absolute','left':($(window).width()-692)/2});
            });
            switch (error){
                case 1 :Toast.error('用户名密码错误！');
                    break;
                case 2 :Toast.error('验证码错误！');
                    break;
                case 3 :Toast.error('验证码为空！');
                    break;
            }
        });
    <?php echo '</script'; ?>
>
    <style type="text/css">
        body {
            background-color:#1c77ac;
            background-image:url(<?php echo @constant('URL_ASSERTS_SAMPLE_PATH');?>
/images/light.png);
            background-repeat:no-repeat;
            background-position:center top;
            overflow:hidden;
        }
    </style>
</head>

<body >

<div id="mainBody">
    <div id="cloud1" class="cloud"></div>
    <div id="cloud2" class="cloud"></div>
</div>


<div class="logintop">
    <span>欢迎登录后台管理界面平台</span>
    <ul>
        <li><a href="#">回首页</a></li>
        <li><a href="#">帮助</a></li>
        <li><a href="#">关于</a></li>
    </ul>
</div>

<div class="loginbody">

    <span class="systemlogo"></span>
    <div class="loginbox">
        <form action="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'Member/Public/login'),$_smarty_tpl);?>
" method="post" id="login">
            <ul>
                <li>
                    <input name="username" type="text" class="loginuser" id="username" value="admin"  />
                </li>
                <li>
                    <input name="password" type="password" class="loginpwd" id="password" value="123456" />
                </li>
                <li>
                    <input name="verify" type="text"  class="loginvrf" id="verify" value="" />
                    <img class="verifyimg" id="verifyimg" alt="点击切换" src="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'member/public/createVerify'),$_smarty_tpl);?>
" />
                </li>
                <li style="clear: both;margin-top: 80px;">
                    <input name="" type="button"  class="loginbtn" value="登录" id="dologin"  />
                    <label  ><input name="" type="checkbox" value="" checked="checked" />记住密码</label>
                    <label ><a href="#">忘记密码？</a></label>
                </li>
            </ul>
        </form>
    </div>

</div>



<div class="loginbm">版权所有  2013  uimaker.com 仅供学习交流，勿用于任何商业用途</div>
</body>
</html>
<?php }
}
?>