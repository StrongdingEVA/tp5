<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:55:"F:\tp5\public/../application/index\view\index\show.html";i:1542707963;s:48:"F:\tp5\application\index\view\common\public.html";i:1542271560;s:48:"F:\tp5\application\index\view\common\common.html";i:1542271704;s:46:"F:\tp5\application\index\view\common\head.html";i:1542271886;s:48:"F:\tp5\application\index\view\common\header.html";i:1542698421;s:48:"F:\tp5\application\index\view\common\footer.html";i:1542271896;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    
    <meta charset="utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Home</title>
<meta name="keywords" content="">
<meta name="description" content="">
<link rel="stylesheet" type="text/css" href="/static/index/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/static/index/css/nprogress.css">
<link rel="stylesheet" type="text/css" href="/static/index/css/style.css">
<link rel="stylesheet" type="text/css" href="/static/index/css/font-awesome.min.css">
<link rel="apple-touch-icon-precomposed" href="/static/index/images/icon.png">
<link rel="shortcut icon" href="/static/index/images/favicon.ico">
<script src="/static/index/js/jquery-2.1.4.min.js"></script>
<script src="/static/index/js/nprogress.js"></script>
<script src="js/jquery.lazyload.min.js"></script>
<!--[if gte IE 9]>
<script src="/static/index/js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="/static/index/js/html5shiv.min.js" type="text/javascript"></script>
<script src="/static/index/js/respond.min.js" type="text/javascript"></script>
<script src="/static/index/js/selectivizr-min.js" type="text/javascript"></script>
<![endif]-->
<!--[if lt IE 9]>
<script>window.location.href='upgrade-browser.html';</script>
<![endif]-->

</head>
<body class="user-select">
    
<header class="header">
    <nav class="navbar navbar-default" id="navbar">
        <div class="container">
            <div class="header-topbar hidden-xs link-border">
                <ul class="site-nav topmenu">
                    <?php if(!$uinfo): ?>
                    <li><a href="#" >登录</a></li>
                    <li><a href="#" rel="nofollow" >注册</a></li>
                    <?php else: ?>
                    <li><a href="#" rel="nofollow" ><?php echo $uinfo['nickname']; ?></a></li>
                    <?php endif; ?>
                    <!--<li><a href="#" title="RSS订阅" >-->
                        <!--<i class="fa fa-rss">-->
                        <!--</i> RSS订阅-->
                    <!--</a></li>-->
                </ul>
                勤记录 懂分享</div>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar" aria-expanded="false"> <span class="sr-only"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <h1 class="logo hvr-bounce-in"><a href="#" title="木庄网络博客"><img src="images/201610171329086541.png" alt="木庄网络博客"></a></h1>
            </div>
            <div class="collapse navbar-collapse" id="header-navbar">
                <form class="navbar-form visible-xs" action="/Search" method="post">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control" placeholder="请输入关键字" maxlength="20" autocomplete="off">
                        <span class="input-group-btn">
            <button class="btn btn-default btn-search" name="search" type="submit">搜索</button>
            </span> </div>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <?php if(is_array($nav) || $nav instanceof \think\Collection || $nav instanceof \think\Paginator): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <li><a data-cont="<?php echo $item['nav_name']; ?>" <?php if($item['is_blank']): ?>target="_blank"<?php endif; ?> title="<?php echo $item['nav_name']; ?>" href="index.html"><?php echo $item['nav_name']; ?></a></li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
    111111

</body>
    
    <footer class="footer">
    <div class="container">
        <p>Copyright &copy; 2016.Company name All rights reserved.More Templates <a href="http://www.cssmoban.com/" target="_blank" title="模板之家">模板之家</a> - Collect from <a href="http://www.cssmoban.com/" title="网页模板" target="_blank">网页模板</a></p>
    </div>
    <div id="gotop"><a class="gotop"></a></div>
</footer>
<script src="/static/index/js/bootstrap.min.js"></script>
<script src="/static/index/js/jquery.ias.js"></script>
<script src="/static/index/js/scripts.js"></script>

</html>