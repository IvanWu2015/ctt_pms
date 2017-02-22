<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:55:"../application/template/default/index\weburl\lists.html";i:1487732416;s:54:"../application/template/default/index\base\common.html";i:1487672645;}*/ ?>
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
<script type="text/javascript" src="tpl_js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="tpl_js/ajaxfileupload.js"></script>
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
    
<div class="publicdiv pt20 pb20 ptn sizing">
    <div class="publicdiv pl20 pr20 pt8 pb20 bf sizing">
        
        <div class="publicdiv borb mb20 pt10 pb10 bf sizing">
            <h3>网址列表</h3>
            <ul class="nav nav-pills">
                <span class="pull-right">
                    <a href="<?php echo url('/index/weburl/add'); ?>" class="btn btn-info">添加网址</a> 
                </span>
            </ul>
        </div>
        
        <table class="table table-striped borl bort borr borb">
            <thead style="background:#f1f1f1;">
                <tr>
                    <th class="">标题</th>
                    <th class="">链接</th>
                    <th class="ac">所属项目</th>
                    <th class="ac">发布人</th>
                    <th class="ac">状态</th>
                    <th class="ac">时间</th>
                    <th class="ac">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($weburl_list as $weburl): ?>
                <tr id="projectlist">
                    <td><?php echo $weburl['title']; ?></td>
                    <td class=""><a href="<?php echo $weburl['url']; ?>" target="_bacnk"><?php echo $weburl['url']; ?></a></td>
                    <td class="ac"><a href="<?php echo url('/index/project/detail/?id='.$weburl['project']); ?>"><?php echo $weburl['name']; ?></a></td>
                    <td class="ac">未读取</td>
                    <td class="ac"><?php if($weburl['acl'] == open): ?>公开<?php else: ?>私有<?php endif; ?></td>
                    <td class="ac"><?php echo $weburl['time']; ?></td>
                    <td class="ac iconfont">
                        <a href="<?php echo url('/index/weburl/add/?id='.$weburl['id']); ?>" class="btn-link" title="编辑">&#xe71d;</a>
                        <a href="<?php echo url('/index/weburl/lists/?deleted=1&id='.$weburl['id']); ?>" class="cred fs16" title="删除">&#xe601;</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

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


