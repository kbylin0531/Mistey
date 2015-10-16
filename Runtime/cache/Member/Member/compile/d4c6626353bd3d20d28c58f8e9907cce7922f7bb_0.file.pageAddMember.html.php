<?php /* Smarty version 3.1.24, created on 2015-10-16 16:39:44
         compiled from "E:/Web/Webroot/Mistey/Application/Member/View/Member/pageAddMember.html" */ ?>
<?php
/*%%SmartyHeaderCode:123045620b7d093bbd1_36729640%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd4c6626353bd3d20d28c58f8e9907cce7922f7bb' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Member/View/Member/pageAddMember.html',
      1 => 1444984782,
      2 => 'file',
    ),
    'b04e7b7c45eeea30274d8d5022aa1359b88bbf2a' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Member/View/basic.html',
      1 => 1444984248,
      2 => 'file',
    ),
    'cf6bcb03252949599896994a17a146a614599741' => 
    array (
      0 => 'cf6bcb03252949599896994a17a146a614599741',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '123045620b7d093bbd1_36729640',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_5620b7d09b5c36_52758029',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5620b7d09b5c36_52758029')) {
function content_5620b7d09b5c36_52758029 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '123045620b7d093bbd1_36729640';
?>
<!DOCTYPE html>
<html>
<head lang="en">
    
        <meta charset="UTF-8">
        <title>用户中心</title>
        <link rel="stylesheet" type="text/css" href="<?php echo @constant('URL_EASYUI_PATH');?>
/themes/metro/easyui.css">
        <link rel="stylesheet" type="text/css" href="<?php echo @constant('URL_EASYUI_PATH');?>
/themes/icon.css">
        <link rel="stylesheet" type="text/css" href="<?php echo @constant('URL_EASYUI_PATH');?>
/themes/color.css">
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('URL_PUBLIC_PATH');?>
js/jquery-1.7.2.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('URL_EASYUI_PATH');?>
/jquery.easyui.min.js"><?php echo '</script'; ?>
>
    
</head>
<body>
<?php
$_smarty_tpl->properties['nocache_hash'] = '123045620b7d093bbd1_36729640';
?>


<form id="ff" method="post">
        <label for="name">Name:</label>
        <input class="easyui-validatebox" type="text" name="name" data-options="required:true" />
        <label for="email">Email:</label>
        <input class="easyui-validatebox" type="text" name="email" data-options="validType:'email'" />
</form>


</body>
</html><?php }
}
?>