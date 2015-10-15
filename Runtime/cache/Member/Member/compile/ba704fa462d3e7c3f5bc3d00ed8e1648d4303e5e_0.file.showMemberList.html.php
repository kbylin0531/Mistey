<?php /* Smarty version 3.1.24, created on 2015-10-15 22:34:03
         compiled from "F:/Web/Webroot/Mist/Application/Member/View/Member/showMemberList.html" */ ?>
<?php
/*%%SmartyHeaderCode:4154561fb95b3e23c3_61953259%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ba704fa462d3e7c3f5bc3d00ed8e1648d4303e5e' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Member/View/Member/showMemberList.html',
      1 => 1444919577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4154561fb95b3e23c3_61953259',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561fb95b42e680_81570284',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561fb95b42e680_81570284')) {
function content_561fb95b42e680_81570284 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '4154561fb95b3e23c3_61953259';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>用户中心</title>
    <link rel="stylesheet" href="<?php echo URL_UIKIT_CSS_PATH;?>
/uikit.min.css" />
    <?php echo '<script'; ?>
 src="<?php echo URL_PUBLIC_PATH;?>
js/jquery-1.11.3.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo URL_UIKIT_JS_PATH;?>
/uikit.min.js" ><?php echo '</script'; ?>
>

    <!-- 特别组建需要引入专门的 js和css -->
    <link rel="stylesheet" href="<?php echo URL_UIKIT_CSS_PATH;?>
/components/accordion.min.css" />
    <?php echo '<script'; ?>
 src="<?php echo URL_UIKIT_JS_PATH;?>
/components/accordion.js" ><?php echo '</script'; ?>
>
</head>
<body>
<div class="uk-accordion" data-uk-accordion>

    <h3 class="uk-accordion-title">Heading 1</h3>
    <div class="uk-accordion-content">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
    </div>

    <h3 class="uk-accordion-title">Heading 2</h3>
    <div class="uk-accordion-content">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
    </div>

    <h3 class="uk-accordion-title">Heading 3</h3>
    <div class="uk-accordion-content">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
    </div>

</div>

</body>
</html><?php }
}
?>