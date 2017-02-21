<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:57:"../application/template/default/index\mycenter\index.html";i:1487650658;s:54:"../application/template/default/index\base\common.html";i:1487668775;s:55:"../application/template/default/index\mycenter\nav.html";i:1487565884;}*/ ?>
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
    

<ul class="left_nav"><!-- 左边导航 -->
    <a href="<?php echo url('mycenter/index'); ?>"><li class="iconfont sizing"><span>&#xe605;</span>个人中心</li></a>
    <a href="<?php echo url('mycenter/task_list'); ?>"><li class="iconfont sizing"><span>&#xe759;</span>我的任务</li></a>
    <a href="<?php echo url('mycenter/action_list'); ?>"><li class="iconfont sizing"><span>&#xe622;</span>我的动态</li></a>
    <a href="<?php echo url('mycenter/weburl_list'); ?>"><li class="iconfont sizing"><span>&#xe60e;</span>我的收藏</li></a>
    <a href="<?php echo url('mycenter/article_list'); ?>"><li class="iconfont sizing"><span>&#xe704;</span>我的文档</li></a>
</ul><!-- 左边导航 -->

<div class="rightcon sizing"><!-- 右边内容 -->
    
    <div class="publicdiv bf">
        <h3 class="pt10 pb10 pl20 pr20 fs16 sizing iconfont" style="display:none;">&#xe605; 个人中心</h3>
        
        <div class="userincon pt20 pb20 pr20 borb sizing"><!-- 基本信息 -->
            
            <div class="userawp sizing">
                <div class="useravatar" onClick="loadWindow('<?php echo url('/index/task/action?ac=useravatar'); ?>');"><img src="tpl_img/match_img.png" class="publicimg" /></div>
            </div>
            <div class="mt20 z pr" style="width:270px;">
                <p class="fs16 fwb">
                    <?php echo $user['realname']; ?>
                    <a href="javascript:void(0);" class="iconfont fs12 cwp" title="编辑信息" onClick="loadWindow('<?php echo url('/index/task/action?ac=userinform'); ?>');">&#xe71d;</a>
                </p>
                <p>职位：<?php echo $user['group_name']; ?></p>
                <p>部门：<?php echo $user['depe_name']; ?></p>
            </div>
            
            <ul class="uscsul"><!-- 数据统计 -->
                <li class="borr sizing">
                    <span class="iconfont">&#xe759;</span>
                    <p class="fwb">我的任务</p>
                    <h3>已完成：<b><?php echo $my_task_count; ?></b></h3>
                    <h3>未完成：<b><?php echo $not_status_count; ?></b></h3>
                </li>
                <li class="borr sizing">
                    <span class="iconfont">&#xe623;</span>
                    <p class="fwb">总工时</p>
                    <h3>预计：<b><?php echo $estimate_count; ?></b></h3>
                    <h3>完成：<b><?php echo $consumed_count; ?></b></h3>
                </li>
                <li class="sizing">
                    <span class="iconfont">&#xe61e;</span>
                    <p class="fwb">当月工时</p>
                    <h3>预计：<b><?php echo $same_month_estimate_count; ?></b></h3>
                    <h3>完成：<b><?php echo $same_month_consumed_count; ?></b></h3>
                </li>
            </ul><!-- 数据统计 -->
            
        </div><!-- 基本信息 -->
        
        <div class="publicdiv pl20 pr20 mt20 mb20 sizing">
            <div class="publicdiv">
                <div class="w50 z pr10 sizing"><!--待完成任务-->
                    <div class="panel" style="border:1px #f8f2d1 solid;">
                        <h3 class="mbn mtn fs15 pl15 pt15 pb15 sizing" style="background:#f8f2d1;">
                            待完成任务
                            <a href="<?php echo url('/index/Mycenter/task_list/?status=ontdone'); ?>" class="y fs12 mr10">更多>></a>
                        </h3>
                        <div class="panel-body">
                            
                            <p class="publicdiv mbn fwb">
                                <span>ID</span>
                                <span class="ml20">任务名称</span>
                                <span class="y">类型</span>
                            </p>
                        
                            <?php foreach($task_list as $task): ?>
                            <li class="publicdiv pr tlist pr70 h25 lh25 ellipsis">
                                <span class="w40p db z"><?php echo $task['id']; ?></span>
                                <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>"><?php echo $task['name']; ?></a>
                                <span class="tlisty"><?php echo $task['type']; ?></span>
                            </li>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div><!--待完成任务-->
                
                <div class="w50 z pl10 sizing"><!--我的动态-->
                    <div class="panel" style="border:1px #f2dede solid;">
                        <h3 class="mbn mtn fs15 pl15 pt15 pb15 sizing bg-danger">我的动态</h3>
                        <div class="panel-body">
                            <?php foreach($action_list as $action): ?>
                            <li class="publicdiv h25 lh25 ellipsis"><a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>">
                                    <?php if(($action['tname'] == null) and ($name == null)): ?>
                                    您于&nbsp;<?php echo $action['date']; ?> &nbsp;&nbsp;<?php echo $action['actionname']; ?> &nbsp;<?php echo $action['typename']; ?> <?php echo $action['tname']; ?><?php echo $action['pname']; else: ?>
                                您于&nbsp;<?php echo $action['date']; ?> &nbsp;&nbsp;对 <?php echo $action['tname']; ?><?php echo $action['pname']; ?> &nbsp;进行&nbsp;<?php echo $action['actionname']; ?>&nbsp;的操作
                                    <?php endif; ?>
                                </a></li>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div><!--我的动态-->
            </div>
            
            <div class="publicdiv">
                <div class="w50 z pr10 sizing"><!--我的收藏-->
                    <div class="panel" style="border:1px #d9edf7 solid;">
                        <h3 class="mbn mtn fs15 pl15 pt15 pb15 sizing bg-info">我的收藏</h3>
                        <div class="panel-body">
                            <p class="publicdiv mbn fwb">
                                <span>ID</span>
                                <span class="ml20">收藏名称</span>
                                <span class="y">时间</span>
                            </p>
                        
                            <?php foreach($weburl_list as $weburl): ?>
                            <li class="publicdiv pr tlist pr100 h25 lh25 ellipsis">
                                <span class="w40p db z"><?php echo $weburl['id']; ?></span>
                                <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>"><?php echo $weburl['title']; ?></a>
                                <span class="tlisty"><?php echo $weburl['time']; ?></span>
                            </li>
                            <?php endforeach; ?>
                            
                        </div>
                    </div>
                </div><!--我的收藏-->
                
                <div class="w50 z pl10 sizing"><!--我的文档-->
                    <div class="panel" style="border:1px #dff0d8 solid;">
                        <h3 class="mbn mtn fs15 pl15 pt15 pb15 sizing bg-success">我的文档</h3>
                        <div class="panel-body">
                            <p class="publicdiv mbn fwb">
                                <span>ID</span>
                                <span class="ml20">文档名称</span>
                                <span class="y">时间</span>
                            </p>
                        
                            <?php foreach($article_list as $article): ?>
                            <li class="publicdiv pr tlist pr100 h25 lh25 ellipsis">
                                <span class="w40p db z"><?php echo $article['id']; ?></span>
                                <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>"><?php echo $article['title']; ?></a>
                                <span class="tlisty"><?php echo $article['time']; ?></span>
                            </li>
                            <?php endforeach; ?>
                            
                        </div>
                    </div>
                </div><!--我的文档-->
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


