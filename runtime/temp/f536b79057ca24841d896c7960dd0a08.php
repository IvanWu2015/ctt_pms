<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:56:"../application/template/default/admin\article\lists.html";i:1487565884;s:54:"../application/template/default/admin\base\common.html";i:1487565884;s:56:"../application/template/default/admin\base\left_nav.html";i:1487565884;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $navtitle; ?> - 盛景网络项目管理后台</title>
<link href="tpl_static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="tpl_static/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<link href="tpl_static/bootstrap/css/docs.css" rel="stylesheet">
<link href="tpl_static/bootstrap/css/onethink.css" rel="stylesheet">
<link href="tpl_css/public.css" rel="stylesheet">
<link href="tpl_css/admin/public.css" rel="stylesheet">
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
<script type="text/javascript" src="tpl_static/bootstrap/js/moment.js"></script>
<script type="text/javascript" src="tpl_static/bootstrap/js/daterangepicker.js"></script>

<!--<![endif]-->


</head>
<body>
	<!-- 头部 -->

<!-- 导航条 ================================================== -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="publicdiv pl20 pr20 sizing">
            
            <ul class="nav nav-pills">
                <img src="tpl_img/bg_icon.png" class="z mt10 mb10" height="50" />
                <li class="mt20 mb10 ml20"><a class="active" href="<?php echo url('/index/index/index'); ?>">首页</a></li>
                <li class="mt20 mb10"><a href="<?php echo url('Index/project/lists'); ?>">项目</a></li>
                <li class="mt20 mb10"><a href="<?php echo url('Index/weburl/lists'); ?>">收藏</a></li>
                <li class="mt20 mb10"><a href="<?php echo url('Index/article/lists'); ?>">文档</a></li>
                <?php if($_G['is_admin']): ?>
                <li class="dropdown mt20 mb10">
                    <a href="<?php echo url('Index/index'); ?>" class="dropdown-toggle" data-toggle="dropdown">后台入口 <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="<?php echo url('/admin/project/lists'); ?>">项目管理</a></li>
                      <li><a href="<?php echo url('/admin/task/lists'); ?>">任务管理</a></li>
                      <li><a href="<?php echo url('/admin/weburl/lists'); ?>">收藏管理</a></li>
                      <li><a href="<?php echo url('/admin/article/lists'); ?>">文章管理</a></li>
                      <li><a href="<?php echo url('/admin/user/lists'); ?>">用户管理</a></li>
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


<div id="wrap" style="margin-top:20px;">
        
<ul class="left_nav"><!-- 左边导航 -->
    <a href="<?php echo url('/admin/project/lists'); ?>"><li class="iconfont sizing"><span>&#xe6c9;</span>项目管理</li></a>
    <a href="<?php echo url('/admin/task/lists'); ?>"><li class="iconfont sizing"><span>&#xe613;</span>任务管理</li></a>
    <a href="<?php echo url('/admin/weburl/lists'); ?>"><li class="iconfont sizing"><span>&#xe60d;</span>收藏管理</li></a>
    <a href="<?php echo url('/admin/article/lists'); ?>"><li class="iconfont sizing"><span>&#xe704;</span>文档管理</li></a>
    <a href="<?php echo url('/admin/user/lists'); ?>"><li class="iconfont sizing"><span>&#xe647;</span>用户管理</li></a>
</ul><!-- 左边导航 -->

<div class="rightcon sizing"><!-- 右边内容 -->
    <div class="publicdiv pl20 pr20 pt8 pb20 bf sizing">
        <table class="table">
          <h3 class="ac">文档管理</h3>
          <thead>
              <tr>
                <th class="">标题</th>
                <th class="">添加人</th>
                <th class="ac">分类</th>
                <th class="ac">项目</th>
                <th class="ac">时间</th>
                <th class="ac">操作</th>
              </tr>
          </thead>
          <tbody>
          <?php foreach($article_list as $article): ?>
          <tr id="projectlist">
              <td><a href="<?php echo url('/index/article/detail?id='.$article['id']); ?>"><?php echo $article['title']; ?></a></td>
              <td class="ac"><?php echo $article['username']; ?></td>
              <td class="ac"><?php echo $article['class_name']; ?></td>
              <td class="ac"><?php echo $article['project_name']; ?></td>
              <td class="ac"><?php echo $article['time']; ?></td>
              <td class="ac iconfont">
                <a href="<?php echo url('/index/article/add?id='.$article['id']); ?>" class="btn-link">&#xe71d;</a>
                <label><input type="checkbox" class="bselect" id="delete" name="delete[<?php echo $task['id']; ?>]" value="1" /> 删除<label>
              </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
          <div class="publicdiv"><?php echo $page; ?></div>
        </table>
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


 <!-- 用于加载js代码 --}

<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
	
</div>

	<!-- /底部 -->
</body> 
</html>


