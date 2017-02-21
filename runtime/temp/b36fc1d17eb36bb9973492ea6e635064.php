<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:57:"../application/template/default/index\article\detail.html";i:1487565884;s:54:"../application/template/default/index\base\common.html";i:1487565884;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $navtitle; ?> - 盛景网络项目管理系统</title>
<link href="tpl_static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="tpl_static/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<link href="tpl_static/bootstrap/css/docs.css" rel="stylesheet">
<link href="tpl_static/bootstrap/css/onethink.css" rel="stylesheet">
<link href="tpl_css/public.css" rel="stylesheet">
<link href="tpl_css/font/iconfont.css" rel="stylesheet">

<link href="tpl_static/bootstrap/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="all" href="tpl_static/bootstrap/css/daterangepicker-bs3.css" />

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="tpl_static/bootstrap/js/html5shiv.js"></script>
<![endif]-->

<!--[if lt IE 9]>
<script type="text/javascript" src="tpl_static/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script type="text/javascript" src="tpl_static/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="tpl_static/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="tpl_js/public.js"></script>
<script type="text/javascript" src="tpl_static/bootstrap/js/moment.js"></script>
<script type="text/javascript" src="tpl_static/bootstrap/js/daterangepicker.js"></script>

<!--<![endif]-->


</head>
<body>
	<!-- 头部 -->

<!-- 导航条 ================================================== -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <!--<div class="container">-->
        <div class="publicdiv pl20 pr20 sizing">
            
            <ul class="nav nav-pills">
                <img src="tpl_img/bg_icon.png" class="z mt10 mb10" height="50" />
                <li class="mt20 mb10 ml20"><a class="active" href="<?php echo url('/index/mycenter'); ?>">首页</a></li>
                <li class="mt20 mb10"><a href="<?php echo url('project/lists'); ?>">项目</a></li>
                <li class="mt20 mb10"><a href="<?php echo url('weburl/lists'); ?>">收藏</a></li>
                <li class="mt20 mb10"><a href="<?php echo url('article/lists'); ?>">文档</a></li>
                <?php if($_G['is_admin']): ?>
                <li class="dropdown mt20 mb10">
                    <a href="<?php echo url('Admin/index'); ?>" class="dropdown-toggle" data-toggle="dropdown">后台入口 <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="<?php echo url('/admin/project/lists'); ?>">项目管理</a></li>
                      <li><a href="<?php echo url('/admin/task/lists'); ?>">任务管理</a></li>
                      <li><a href="<?php echo url('/admin/weburl/lists'); ?>">收藏管理</a></li>
                      <li><a href="<?php echo url('/admin/article/lists'); ?>">文章管理</a></li>
                      <li><a href="<?php echo url('/admin/user/lists'); ?>">用户管理</a></li>
					  <li><a href="<?php echo url('/admin/sort/lists'); ?>">分类管理</a></li>
                    </ul>
                </li>
                <?php endif; if($_G['uid'] > 0): ?>
                <li class="mt20 mb10 ml20 y">
                    <a class="active" data-toggle="dropdown" href="<?php echo url('index/index'); ?>"><?php echo get_username(); ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo url('User/profile'); ?>">修改密码</a></li>
                        <li><a href="<?php echo url('User/logout'); ?>">退出</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="mt20 mb10 ml20 y"><a class="active" href="<?php echo url('User/register'); ?>">注册</a></li>
                <li class="mt20 mb10 ml20 y"><a class="active" href="<?php echo url('User/login'); ?>">登录</a></li>
                <?php endif; ?>
                
            </ul>
            
        </div>
    </div>
</div>

	<!-- /头部 -->
	
	<!-- 主体 -->


<!--<div id="main-container" class="container" style="margin-top:50px;">-->
<div class="publicdiv pl20 pr20 sizing" style="margin-top:50px;">
    <div class="prompt" id="prompt"><b class="cred mr5">*</b>项必填</div>
    <div id="addpr"></div>
    <div class="applica" id="applica"></div><!-- 调用页面 -->
    

<div class="publicdiv bf pb20 sizing" id="action_wrap">
        <h3 class="mtn mb10 pb15 pt15 pl20 pr20 sizing" style="background:#f8fafe; border-bottom:1px solid #e5e5e5;">
            <?php echo $article_detail['title']; ?>
            <span class="y derkwt fs13 iconfont">
                <a href="<?php echo url('/index/article/add'); ?>" class="btn-link" title="编辑">&#xe71d; 编辑</a>
            </span>
        </h3>
        
        <div class="publicdiv pl20 pr20 sizing">
            <div class="span7 mt20 mln z" style="width:72%;">
                <div class="publicdiv pr bor pt20 pb20 pl20 mb20 pr20 sizing">
                    <span class="pa bf pl8 pr8 lh34 fs18 fwb" style="left:10px; top:-20px;">文档内容</span>
                    <?php echo $article_detail['contents']; ?>
                </div>
                
            </div>
            <div class="span4 mt20 y" style="width:25%;">
                <div class="publicdiv pr bor pt20 pb20 pl20 mln pr20 sizing">
                    <span class="pa bf pl8 pr8 lh34 fs18 fwb" style="left:10px; top:-20px;">基本信息</span>
                    <p>分类：<span class="text-primary mr20 ml10"><?php echo $article_detail['class_name']; ?></span></p>
                    <p>产品：<span class="text-primary mr20 ml10"><?php echo $article_detail['']; ?></span></p>
                    <p>项目：<span class="text-primary mr20 ml10"><?php echo $article_detail['project_name']; ?></span></p>
                    <p>时间：<span class="text-primary mr20 ml10"><?php echo $article_detail['time']; ?></span></p>
                    <p>性质：<span class="text-primary mr20 ml10"><?php if($article_detail['acl'] == 'private'): ?>私有<?php else: ?>开放<?php endif; ?></span></p>
                    <p>发布者：<span class="text-primary mr20 ml10"><?php echo $article_detail['username']; ?></span></p>
                </div>
            </div>
            
        </div>
    </div>


</div>

<script type="text/javascript">
    $(function(){
        $(window).resize(function(){
            $("#main-container").css("min-height", $(window).height() - 343);
        }).resize();
    })
</script>

	<!-- /主体 -->

    <!-- 底部
    ================================================== -->
    <footer class="footer mtn publicdiv">
      <div class="container">
          <p> CopyRight <strong><a href="http://www.chinatt.com" target="_blank">ChinaTT.com</a></strong> 2016</p>
      </div>
    </footer>


 <!-- 用于加载js代码 --}

<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
	
</div>

	<!-- /底部 -->
</body> 
</html>


