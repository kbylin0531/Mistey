<?php /* Smarty version 3.1.24, created on 2015-10-16 15:18:21
         compiled from "E:/Web/Webroot/Mistey/Application/Member/View/Member/showList.html" */ ?>
<?php
/*%%SmartyHeaderCode:38835620a4bdecd510_80104766%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '89d1e3f6f749a6b303d9fab7a320bf2cf372fe95' => 
    array (
      0 => 'E:/Web/Webroot/Mistey/Application/Member/View/Member/showList.html',
      1 => 1444979876,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '38835620a4bdecd510_80104766',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_5620a4bdf244c5_94612942',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5620a4bdf244c5_94612942')) {
function content_5620a4bdf244c5_94612942 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '38835620a4bdecd510_80104766';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>用户中心</title>
    <link rel="stylesheet" href="<?php echo @constant('URL_UIKIT_PATH');?>
/css/uikit.min.css" />

    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_UIKIT_PATH');?>
/vendor/jquery.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_UIKIT_PATH');?>
/js/uikit.min.js" ><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_UIKIT_PATH');?>
/vendor/highlight/highlight.js"><?php echo '</script'; ?>
>
    <link rel="stylesheet" href="<?php echo @constant('URL_UIKIT_PATH');?>
/vendor/highlight/highlight.css">

    <link rel="stylesheet" href="<?php echo @constant('URL_UIKIT_PATH');?>
/css/docs.css">
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_UIKIT_PATH');?>
/js/docs.js"><?php echo '</script'; ?>
>

</head>
<body>
<table class="uk-table">
    <caption>Table caption</caption>
    <thead>
    <tr>
        <th>Table Heading</th>
        <th>Table Heading</th>
        <th>Table Heading</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td>Table Footer</td>
        <td>Table Footer</td>
        <td>Table Footer</td>
    </tr>
    </tfoot>
    <tbody>
    <tr>
        <td>Table Data</td>
        <td>Table Data</td>
        <td>Table Data</td>
    </tr>
    <tr>
        <td>Table Data</td>
        <td>Table Data</td>
        <td>Table Data</td>
    </tr>
    <tr>
        <td>Table Data</td>
        <td>Table Data</td>
        <td>Table Data</td>
    </tr>
    </tbody>
</table>
</body>
</html><?php }
}
?>