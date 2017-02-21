<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:63:"../application/template/default/index\mycenter\action_list.html";i:1487242913;s:54:"../application/template/default/index\base\common.html";i:1487565884;s:55:"../application/template/default/index\mycenter\nav.html";i:1487565884;}*/ ?>
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
    

<ul class="left_nav"><!-- 左边导航 -->
    <a href="<?php echo url('mycenter/index'); ?>"><li class="iconfont sizing"><span>&#xe605;</span>个人中心</li></a>
    <a href="<?php echo url('mycenter/task_list'); ?>"><li class="iconfont sizing"><span>&#xe759;</span>我的任务</li></a>
    <a href="<?php echo url('mycenter/action_list'); ?>"><li class="iconfont sizing"><span>&#xe622;</span>我的动态</li></a>
    <a href="<?php echo url('mycenter/weburl_list'); ?>"><li class="iconfont sizing"><span>&#xe60e;</span>我的收藏</li></a>
    <a href="<?php echo url('mycenter/article_list'); ?>"><li class="iconfont sizing"><span>&#xe704;</span>我的文档</li></a>
</ul><!-- 左边导航 -->

<div class="rightcon sizing"><!-- 右边内容 -->
       
    <div class="publicdiv bf">
    
        <h3 class="pt10 pb10 pl20 pr20 fs16 sizing iconfont">&#xe622; 我的动态</h3>
        
        <div class="publicdiv pl20 pr20 pt8 pb8 bf sizing">
        <section id="contents" class="mtn">
                <table class="table table-striped borl bort borr borb">
                    <thead style="background:#f1f1f1;">
                        <tr>
                            <th class="ac">项目名称</th>
                            <th class="">动态</th>
                            <th class="ac">类型</th>
                            <th class="ac">操作者</th>
                            <th class="ac">动作</th>
                            <th class="ac">时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($action_list as $project): ?>
                        <tr>
                            <td class="ac"><?php echo $project['parent_name']; ?></td>
                            <td class="">
                                <?php if($project['action'] == 'opened'): ?>创建了
                                <?php if($project['objectType'] == 'project'): ?><?php echo $project['project_name']; ?>项目<?php elseif($project['objectType'] == 'task'): ?><?php echo $project['task_name']; endif; endif; if($project['action'] == 'closed'): ?>关闭<?php if($project['objectType'] == 'task'): ?><?php echo $project['task_name']; endif; elseif($project['action'] == 'started'): ?>开始<?php if($project['objectType'] == 'task'): ?><?php echo $project['task_name']; endif; if($project['objectType'] == 'project'): ?><?php echo $project['project_name']; endif; elseif($project['action'] == 'finished'): ?>完成<?php if($project['objectType'] == 'task'): ?><?php echo $project['task_name']; endif; if($project['objectType'] == 'project'): ?><?php echo $project['project_name']; endif; elseif($project['action'] == 'edited'): ?>编辑<?php if($project['objectType'] == 'task'): ?><?php echo $project['task_name']; endif; if($project['objectType'] == 'project'): ?><?php echo $project['project_name']; endif; elseif($project['action'] == 'activated'): ?>激活<?php if($project['objectType'] == 'task'): ?><?php echo $project['task_name']; endif; elseif($project['action'] == 'assigned'): ?>分配<?php if($project['objectType'] == 'task'): ?><?php echo $project['task_name']; endif; elseif($project['action'] == 'paused'): ?>暂停<?php if($project['objectType'] == 'task'): ?><?php echo $project['task_name']; endif; elseif($project['action'] == 'deleted'): ?>删除<?php if($project['objectType'] == 'task'): ?><?php echo $project['task_name']; endif; elseif($project['action'] == 'canceled'): ?>取消<?php if($project['objectType'] == 'task'): ?><?php echo $project['task_name']; endif; endif; ?>
                                <br/>
                                <?php echo $project['comment']; ?>
                            </td>
                            <td class="ac">
                                <?php if($project['objectType'] == 'project'): ?>项目
                                <?php elseif($project['objectType'] == 'task'): ?>任务
                                <?php elseif($project['objectType'] == 'user'): ?>用户
                                <?php elseif($project['objectType'] == 'build'): ?>建造
                                <?php elseif($project['objectType'] == 'product'): ?>产品
                                <?php elseif($project['objectType'] == 'bug'): ?>BUG
                                <?php endif; ?>
                            </td>
                            <td class="ac"><?php echo $project['actor']; ?></td>
                            <td class="ac">
                                <?php if($project['action'] == 'edited'): ?>编辑
                                <?php elseif($project['action'] == 'opened'): ?>创建
                                <?php elseif($project['action'] == 'changed'): ?>改变
                                <?php elseif($project['action'] == 'closed'): ?>关闭
                                <?php elseif($project['action'] == 'started'): ?>开始
                                <?php elseif($project['action'] == 'finished'): ?>完成
                                <?php elseif($project['action'] == 'canceled'): ?>取消
                                <?php elseif($project['action'] == 'deleted'): ?>删除
                                <?php elseif($project['action'] == 'restarted'): ?>重启
                                <?php elseif($project['action'] == 'paused'): ?>暂停
                                <?php elseif($project['action'] == 'assigned'): ?>分配
                                <?php elseif($project['action'] == 'commented'): ?>评论
                                <?php elseif($project['action'] == 'recordestimate'): ?>记录工时
                                <?php elseif($project['action'] == 'activated'): ?>激活
                                <?php endif; ?>
                            </td>
                            <td class="ac"><?php echo $project['date']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="publicdiv ac pagingw"><?php echo $page; ?></div>
            </section>
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


