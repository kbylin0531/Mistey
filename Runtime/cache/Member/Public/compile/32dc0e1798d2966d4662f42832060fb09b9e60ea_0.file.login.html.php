<?php /* Smarty version 3.1.24, created on 2015-10-13 22:44:04
         compiled from "F:/Web/Webroot/Mist/Application/Member/View/Public/login.html" */ ?>
<?php
/*%%SmartyHeaderCode:6966561d18b4e55195_33750682%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '32dc0e1798d2966d4662f42832060fb09b9e60ea' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Member/View/Public/login.html',
      1 => 1444747442,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6966561d18b4e55195_33750682',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561d18b4ed91f8_67758829',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561d18b4ed91f8_67758829')) {
function content_561d18b4ed91f8_67758829 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '6966561d18b4e55195_33750682';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>欢迎登录后台管理系统--我爱模板网 www.5imoban.net</title>
    <link href="<?php echo @constant('URL_ASSERTS_SAMPLE_PATH');?>
/css/style.css" rel="stylesheet" type="text/css" />
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_ASSERTS_SAMPLE_PATH');?>
/js/jquery.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_ASSERTS_SAMPLE_PATH');?>
/js/cloud.js"  type="text/javascript"><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('URL_PUBLIC_PATH');?>
/js/rsa/Barrett.js" ><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('URL_PUBLIC_PATH');?>
/js/rsa/BigInt.js" ><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('URL_PUBLIC_PATH');?>
/js/rsa/RSA_Stripped.js" ><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
 language="javascript">
        $(function(){
            var loginbox = $(".loginbox");
            var dologin = $("#dologin");
            var login_form = $("#login");
            var password_input = $("#password");

            var rsa_n = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCzV0H7g2oRHAnqhVltcF9OWv5Z"+
            "RQexCukLlR36Dxd5zgEOkAMfYrkbu+kceL9SLg9KnHUz1pW5SM4Yayif0XH9kBvw"+
            "/gCI8IZDE87/rzd9hWBQZEPL7LfErZoBRSiF9D3kMwkrocsX3+DP38xgOL8RA1bV"+
            "X3y6NOt2p3vMINedZwIDAQAB";

            dologin.click(function () {
                setMaxDigits(131); //131 => n的十六进制位数/2+3
                var key      = new RSAKeyPair("10001", '', rsa_n); //10001 => e的十六进制
                var password = password_input.val();
                password = encryptedString(key, password); //不支持汉字
                password_input.val(password);
                login_form.submit();
                alert(password);
            });

            loginbox.css({'position':'absolute','left':($(window).width()-692)/2});
            $(window).resize(function(){
                loginbox.css({'position':'absolute','left':($(window).width()-692)/2});
            })
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
        <form action="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'Member/Public/check'),$_smarty_tpl);?>
" method="post" id="login">
            <ul>
                <li>
                    <input name="username" type="text" class="loginuser" id="username" value="admin"  />
                </li>
                <li>
                    <input name="password" type="text" class="loginpwd" id="password" value="123456" />
                </li>
                <li>
                    <input name="" type="button" class="loginbtn" value="登录" id="dologin"  />
                    <label><input name="" type="checkbox" value="" checked="checked" />记住密码</label>
                    <label><a href="#">忘记密码？</a></label>
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