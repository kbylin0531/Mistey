<?php /* Smarty version 3.1.24, created on 2015-09-15 13:00:57
         compiled from "E:/Web/Webroot/Mistey/Application/Koe/View/User/install.html" */ ?>
<?php
/*%%SmartyHeaderCode:699055f7a609928580_28210840%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'de813e7bebbbcab49f82da2a012709de9b12363b' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Koe/View/User/install.html',
      1 => 1442293253,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '699055f7a609928580_28210840',
  'variables' => 
  array (
    'wall_page_url' => 0,
    'errors' => 0,
    'error' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_55f7a6099958e5_30061733',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f7a6099958e5_30061733')) {
function content_55f7a6099958e5_30061733 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '699055f7a609928580_28210840';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>环境检测</title>
	<link rel="Shortcut Icon" href="<?php echo @constant('URL_KOE_STATIC_PATH');?>
/favicon.ico">
	<link href="<?php echo @constant('URL_KOE_STYLE_PATH');?>
/bootstrap.css" rel="stylesheet"/>
	<link rel="stylesheet" href="<?php echo @constant('URL_KOE_STYLE_PATH');?>
/font-awesome/css/font-awesome.css">
	<!--[if IE 7]>
	<link rel="stylesheet" href="<?php echo @constant('URL_KOE_STYLE_PATH');?>
/font-awesome/css/font-awesome-ie7.css">
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo @constant('URL_KOE_STYLE_PATH');?>
/login.css">
</head>

<body>
    <div class="background" style='background-image:url("<?php echo $_smarty_tpl->tpl_vars['wall_page_url']->value;?>
")'></div>
	<div class="loginbox" >
		<div class="title">
			<div class="logo"><i class="icon-cloud"></i>Koe</div>
			<div class='info'>——Koe Info</div>
		</div>
		<div class="form" style="padding: 10px 20px;">
			<h3>php_env_check</h3>
            <?php if ($_smarty_tpl->tpl_vars['errors']->value == false) {?>
                <div class="success">
                    <h4>Successful!</h4>
                    Use the following account login
                    <br/>root：admin/admin(need change)<br/>default：demo/demo<br/>guest：guest/guest</div></div><div class="guest">
                    <a href="<?php echo @constant('URL_BASE_PATH');?>
/koe/user/loginFirst">login</a>
                </div>
            <?php } else { ?>
                <div class="error">
                    <h4>error:</h4>
                    <ul>
                        
                        <?php
$_from = $_smarty_tpl->tpl_vars['errors']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['error'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['error']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['error']->value) {
$_smarty_tpl->tpl_vars['error']->_loop = true;
$foreach_error_Sav = $_smarty_tpl->tpl_vars['error'];
?>
                            <li><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</li>
                        <?php
$_smarty_tpl->tpl_vars['error'] = $foreach_error_Sav;
}
?>
                    </ul>
                </div>
                <div class="guest">
                    <a href="<?php echo @constant('URL_BASE_PATH');?>
/koe/user/loginFirst">Ignore</a>
                </div>
            <?php }?>
		</div>
	</div>
<div class="common_footer">test Mist</div>
</body>
</html><?php }
}
?>