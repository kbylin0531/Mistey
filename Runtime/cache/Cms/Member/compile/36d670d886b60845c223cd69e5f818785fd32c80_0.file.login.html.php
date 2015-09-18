<?php /* Smarty version 3.1.24, created on 2015-09-18 11:14:16
         compiled from "E:/Web/Webroot/Mistey/Application/Cms/View/Member/login.html" */ ?>
<?php
/*%%SmartyHeaderCode:2827255fb8188c27525_06495005%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '36d670d886b60845c223cd69e5f818785fd32c80' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Cms/View/Member/login.html',
      1 => 1442544702,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2827255fb8188c27525_06495005',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_55fb8188d21f93_68489004',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fb8188d21f93_68489004')) {
function content_55fb8188d21f93_68489004 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '2827255fb8188c27525_06495005';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>欢迎您的登录</title>
    <link rel="stylesheet" type="text/css" href="<?php echo @constant('URL_CMS_ADMIN_CSS_PATH');?>
/login.css" media="all">
    <link rel="stylesheet" type="text/css" href="<?php echo @constant('URL_CMS_ADMIN_CSS_PATH');?>
/default_color.css" media="all">

    <!--[if lt IE 9]>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('URL_PUBLIC_PATH');?>
/js/jquery-1.10.2.min.js"><?php echo '</script'; ?>
>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('URL_PUBLIC_PATH');?>
/js/jquery-2.0.3.min.js"><?php echo '</script'; ?>
>
    <!--<![endif]-->
    <?php echo '<script'; ?>
 type="text/javascript">
        $(function () {
            /* 登陆表单获取焦点变色 */
            $(".login-form").on("focus", "input", function(){
                $(this).closest('.item').addClass('focus');
            }).on("blur","input",function(){
                $(this).closest('.item').removeClass('focus');
            });

            //表单提交
            $(document)
                    .ajaxStart(function(){
                        $("button:submit").addClass("log-in").attr("disabled", true);
                    })
                    .ajaxStop(function(){
                        $("button:submit").removeClass("log-in").attr("disabled", false);
                    });

            $("form").submit(function(){
                var self = $(this);
                $.post(self.attr("action"), self.serialize(), success, "json");
                return false;

                function success(data){
                    if(data.status){
                        window.location.href = data.url;
                    } else {
                        self.find(".check-tips").text(data.info);
                        //刷新验证码
                        $(".reloadverify").click();
                    }
                }
            });

            $(function(){
                //初始化选中用户名输入框
                $("#itemBox").find("input[name=username]").focus();
                //刷新验证码
                var verifyimg = $(".verifyimg").attr("src");
                $(".reloadverify").click(function(){
                    if( verifyimg.indexOf('?')>0){
                        $(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
                    }else{
                        $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
                    }
                });

                //placeholder兼容性
                //如果支持
                function isPlaceholer(){
                    var input = document.createElement('input');
                    return "placeholder" in input;
                }
                //如果不支持
                var itemBoxInput = $("#itemBox input");
                if(!isPlaceholer()){
                    $(".placeholder_copy").css({
                        display:'block'
                    });
                    itemBoxInput.keydown(function(){
                        $(this).parents(".item").next(".placeholder_copy").css({
                            display:'none'
                        })
                    });
                    itemBoxInput.blur(function(){
                        if($(this).val()==""){
                            $(this).parents(".item").next(".placeholder_copy").css({
                                display:'block'
                            })
                        }
                    })


                }
            });
        });

    <?php echo '</script'; ?>
>


</head>
<body id="login-page">
<div id="main-content">

    <!-- 主体 -->
    <div class="login-body">
        <div class="login-main pr">
            <form action="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'cms/member/login'),$_smarty_tpl);?>
" method="post" class="login-form">
                <h3 class="welcome"><i class="login-logo"></i>OneThink管理平台</h3>
                <div id="itemBox" class="item-box">
                    <div class="item">
                        <i class="icon-login-user"></i>
                        <input type="text" name="username" placeholder="请填写用户名" autocomplete="off" />
                    </div>
                    <span class="placeholder_copy placeholder_un">请填写用户名</span>
                    <div class="item b0">
                        <i class="icon-login-pwd"></i>
                        <input type="password" name="password" placeholder="请填写密码" autocomplete="off" />
                    </div>
                    <span class="placeholder_copy placeholder_pwd">请填写密码</span>
                    <div class="item verifycode">
                        <i class="icon-login-verifycode"></i>
                        <input type="text" name="verify" placeholder="请填写验证码" autocomplete="off">
                        <a class="reloadverify" title="换一张" href="javascript:void(0)">换一张？</a>
                    </div>
                    <span class="placeholder_copy placeholder_check">请填写验证码</span>
                    <div>
                        <img class="verifyimg reloadverify" alt="点击切换" src="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'cms/member/createVerify'),$_smarty_tpl);?>
">
                    </div>
                </div>
                <div class="login_btn_panel">
                    <button class="login-btn" type="submit">
                        <span class="in"><i class="icon-loading"></i>登 录 中 ...</span>
                        <span class="on">登 录</span>
                    </button>
                    <div class="check-tips"></div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
<?php }
}
?>