<?php /* Smarty version 3.1.24, created on 2015-10-11 19:13:33
         compiled from "F:/Web/Webroot/Mist/Application/Installer/View/Index/index.html" */ ?>
<?php
/*%%SmartyHeaderCode:9791561a445daedfe5_25857078%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '044544fcf7e11b237d8c943f520d015b8b222a8a' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Index/index.html',
      1 => 1444561948,
      2 => 'file',
    ),
    'b835d4b9fe4c28bb6f0f7d06d927e08e74bc7677' => 
    array (
      0 => 'F:/Web/Webroot/Mist/Application/Installer/View/Index/base.html',
      1 => 1442397397,
      2 => 'file',
    ),
    '833591e55d7b2114980a064ddb7e1411bc524ae7' => 
    array (
      0 => '833591e55d7b2114980a064ddb7e1411bc524ae7',
      1 => 0,
      2 => 'string',
    ),
    '19eb74d42213107001ff7b7e9761d2f4c1af718e' => 
    array (
      0 => '19eb74d42213107001ff7b7e9761d2f4c1af718e',
      1 => 0,
      2 => 'string',
    ),
    'b952960a2268768bf132bd55e178e836e9e35947' => 
    array (
      0 => 'b952960a2268768bf132bd55e178e836e9e35947',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '9791561a445daedfe5_25857078',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_561a445dbbdfa5_66391328',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561a445dbbdfa5_66391328')) {
function content_561a445dbbdfa5_66391328 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '9791561a445daedfe5_25857078';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OneThink 安装</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/modules/install/css/install.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/bootstrap/js/html5shiv.js"><?php echo '</script'; ?>
>
    <![endif]-->
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/jquery-1.10.2.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo @constant('URL_CMS_STATIC_PATH');?>
/bootstrap/js/bootstrap.js"><?php echo '</script'; ?>
>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!-- Navbar
================================================== -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" target="_blank" href="http://www.onethink.cn">OneThink</a>
            <div class="nav-collapse collapse">
                <ul id="step" class="nav">
                    <?php
$_smarty_tpl->properties['nocache_hash'] = '9791561a445daedfe5_25857078';
?>

    <li class="active"><a href="javascript:void(0);">安装协议</a></li>
    <li><a href="javascript:void(0);">环境检测</a></li>
    <li><a href="javascript:void(0);">创建数据库</a></li>
    <li><a href="javascript:void(0);">安装</a></li>
    <li><a href="javascript:void(0);">完成</a></li>

                </ul>
            </div>
        </div>
    </div>
</div>

<div class="jumbotron masthead">
    <div class="container">
        <?php
$_smarty_tpl->properties['nocache_hash'] = '9791561a445daedfe5_25857078';
?>

    <h1>OneThink V1.1 安装协议</h1>
    <p>本CMS模块修改自OneThink V1.1</p>

    <p>版权所有 (c) 2013~2014，上海顶想信息科技有限公司保留所有权利。</p>

    <p>感谢您选择<a href="http://www.onethink.cn" target="_blank">OneThink</a>，希望我们的努力能为您提供一个简单、强大的站点解决方案。</p>

    <p>用户须知：本协议是您与顶想公司之间关于您使用OneThink产品及服务的法律协议。无论您是个人或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，包括免除或者限制顶想责任的免责条款及对您的权利限制。请您审阅并接受或不接受本服务条款。如您不同意本服务条款及/或顶想随时对其的修改，您应不使用或主动取消OneThink产品。否则，您的任何对OneThink的相关服务的注册、登陆、下载、查看等使用行为将被视为您对本服务条款全部的完全接受，包括接受顶想对服务条款随时所做的任何修改。</p>

    <p>本服务条款一旦发生变更, 顶想将在产品官网上公布修改内容。修改后的服务条款一旦在网站公布即有效代替原来的服务条款。您可随时登陆官网查阅最新版服务条款。如果您选择接受本条款，即表示您同意接受协议各项条件的约束。如果您不同意本服务条款，则不能获得使用本服务的权利。您若有违反本条款规定，顶想公司有权随时中止或终止您对OneThink产品的使用资格并保留追究相关法律责任的权利。</p>

    <p>在理解、同意、并遵守本协议的全部条款后，方可开始使用OneThink产品。您也可能与顶想公司直接签订另一书面协议，以补充或者取代本协议的全部或者任何部分。</p>

    <p>顶想公司拥有OneThink的全部知识产权，包括商标和著作权。本软件只供许可协议，并非出售。顶想只允许您在遵守本协议各项条款的情况下复制、下载、安装、使用或者以其他方式受益于本软件的功能或者知识产权。</p>

    <p>
        OneThink遵循Apache Licence2开源协议，并且免费使用（但不包括其衍生产品、插件或者服务）。Apache Licence是著名的非盈利开源组织Apache采用的协议。该协议和BSD类似，鼓励代码共享和尊重原作者的著作权，允许代码修改，再作为开源或商业软件发布。需要满足的条件：<br/>
        1． 需要给用户一份Apache Licence ；<br/>
        2． 如果你修改了代码，需要在被修改的文件中说明；<br/>
        3． 在延伸的代码中（修改和有源代码衍生的代码中）需要带有原来代码中的协议，商标，专利声明和其他原来作者规定需要包含的说明；<br/>
        4． 如果再发布的产品中包含一个Notice文件，则在Notice文件中需要带有本协议内容。你可以在Notice中增加自己的许可，但不可以表现为对Apache Licence构成更改。
    </p>

    </div>
</div>


<!-- Footer
================================================== -->
<footer class="footer navbar-fixed-bottom">
    <div class="container">
        <div>
            <?php
$_smarty_tpl->properties['nocache_hash'] = '9791561a445daedfe5_25857078';
?>

    <a class="btn btn-primary btn-large" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['U'][0][0]->U(array('url'=>'installer/index/first'),$_smarty_tpl);?>
">同意安装协议</a>
    <a class="btn btn-large" href="http://www.baidu.com">不同意</a>

        </div>
    </div>
</footer>
</body>
</html>
<?php }
}
?>