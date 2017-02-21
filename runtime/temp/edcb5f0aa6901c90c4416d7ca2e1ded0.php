<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:57:"../application/template/default/index\project\weburl.html";i:1487580507;s:54:"../application/template/default/index\base\common.html";i:1487565884;s:61:"../application/template/default/index\base\project_title.html";i:1487580507;}*/ ?>
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
    

<div class="publicdiv pt10 pb10 ptn sizing">

    <div class="publicdiv bf pl20 pr20 pb10 mb20 sizing">
    <h3>
        <a href="<?php echo url('/index/Project/detail/?id='.$project_id); ?>"><?php echo $project_detail['name']; ?></a>
        <span class="fs14">
            <a <?php if($_G['action'] == 'detail'): ?>class="ml10 btn-link"<?php else: ?>class="ml10"<?php endif; ?> href="<?php echo url('/index/Project/detail?id='.$project_id); ?>">任务</a>
            <a <?php if($_G['action'] == 'group'): ?>class="ml10 btn-link"<?php else: ?>class="ml10"<?php endif; ?> href="<?php echo url('/index/Project/group?project_id='.$project_id); ?>">团队</a>
            <a <?php if($_G['action'] == 'action'): ?>class="ml10 btn-link"<?php else: ?>class="ml10"<?php endif; ?> href="<?php echo url('/index/Project/action?project_id='.$project_id); ?>">动态</a>
            <a <?php if($_G['action'] == 'info'): ?>class="ml10 btn-link"<?php else: ?>class="ml10"<?php endif; ?> href="<?php echo url('/index/Project/info?project_id='.$project_id); ?>">概况</a>
            <a <?php if($_G['action'] == 'weburl'): ?>class="ml10 btn-link"<?php else: ?>class="ml10"<?php endif; ?> href="<?php echo url('/index/Project/weburl?project_id='.$project_id); ?>">收藏</a>
            <a <?php if($_G['action'] == 'article'): ?>class="ml10 btn-link"<?php else: ?>class="ml10"<?php endif; ?> href="<?php echo url('/index/Project/article?project_id='.$project_id); ?>">文档</a>
        </span>
        <?php if($project_id > 0): ?>
        <span class="pull-right">
            <a href="<?php echo url('/index/task/add/?project_id='.$project_id); ?>" class="btn btn-info">添加任务</a>
        </span>
        <?php endif; ?>
    </h3>
</div>
    
    <div class="publicdiv pl20 pr20 pt20 pb20 bf sizing">

        <table class="table table-striped borl bort borr borb">
            <thead style="background:#f1f1f1;">
                <tr>
                    <th class="">标题</th>
                    <th class="">链接</th>
                    <th class="ac">所属项目</th>
                    <th class="ac">备注</th>
                    <th class="ac">状态</th>
                    <th class="ac">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($weburl_list as $weburl): ?>
                <tr id="projectlist">
                    <td><a href="<?php echo url('/index/project/detail/?id='.$project['id']); ?>"><?php echo $weburl['title']; ?></a></td>
                    <td class=""><a href="<?php echo $weburl['url']; ?>" target="_bacnk"><?php echo $weburl['url']; ?></a></td>
                    <td class="ac"><?php echo $weburl['name']; ?></td>
                    <td class="ac"><?php echo $weburl['explain']; ?></td>
                    <td class="ac"><?php if($weburl['acl'] == open): ?>公开<?php else: ?>私有<?php endif; ?></td>
                    <td class="ac iconfont">
                        <a href="<?php echo url('/index/weburl/add/?id='.$weburl['id']); ?>" class="btn-link" title="编辑">&#xe71d;</a>
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


