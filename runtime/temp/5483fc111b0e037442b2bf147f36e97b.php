<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:54:"../application/template/default/admin\user\detail.html";i:1487646908;s:54:"../application/template/default/admin\base\common.html";i:1487645348;s:56:"../application/template/default/admin\base\left_nav.html";i:1487645348;}*/ ?>
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
    <a href="<?php echo url('/admin/index/index'); ?>"><li class="iconfont sizing"><span>&#xe605;</span>后台首页</li></a>
    <a href="<?php echo url('/admin/project/lists'); ?>"><li class="iconfont sizing"><span>&#xe6c9;</span>项目管理</li></a>
    <a href="<?php echo url('/admin/task/lists'); ?>"><li class="iconfont sizing"><span>&#xe613;</span>任务管理</li></a>
    <a href="<?php echo url('/admin/weburl/lists'); ?>"><li class="iconfont sizing"><span>&#xe60d;</span>收藏管理</li></a>
    <a href="<?php echo url('/admin/article/lists'); ?>"><li class="iconfont sizing"><span>&#xe704;</span>文档管理</li></a>
    <a href="<?php echo url('/admin/user/lists'); ?>"><li class="iconfont sizing"><span>&#xe647;</span>用户管理</li></a>
</ul><!-- 左边导航 -->

<div class="rightcon sizing"><!-- 右边内容 -->
    <div class="publicdiv pl20 pr20 pt8 pb20 bf sizing">          
          <div class="userincon pt20 pb20 pr20 borb sizing"><!-- 基本信息 -->
            
            <div class="userawp sizing">
                <div class="useravatar" onClick="loadWindow('<?php echo url('/index/task/action?ac=useravatar'); ?>');"><img src="tpl_img/match_img.png" class="publicimg" /></div>
            </div>
            <div class="mt20 z pr" style="width:270px;">
                <p class="fs16 fwb">
                    未读取
                    <a href="javascript:void(0);" class="iconfont fs12 cwp" title="编辑信息" onClick="loadWindow('<?php echo url('/index/task/action?ac=userinform'); ?>');">&#xe71d;</a>
                </p>
                <p>职位：未读取</p>
                <p>部门：未读取</p>
            </div>
            
            <ul class="uscsul"><!-- 数据统计 -->
                <li class="borr sizing">
                    <span class="iconfont">&#xe759;</span>
                    <p class="fwb">我的任务</p>
                    <h3>已完成：<b>未读取</b></h3>
                    <h3>未完成：<b>未读取</b></h3>
                </li>
                <li class="borr sizing">
                    <span class="iconfont">&#xe623;</span>
                    <p class="fwb">总工时</p>
                    <h3>预计：<b>未读取</b></h3>
                    <h3>完成：<b>未读取</b></h3>
                </li>
                <li class="sizing">
                    <span class="iconfont">&#xe61e;</span>
                    <p class="fwb">当月工时</p>
                    <h3>预计：<b>未读取</b></h3>
                    <h3>完成：<b>未读取</b></h3>
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
                        
                            <li class="publicdiv pr tlist pr70 h25 lh25 ellipsis">
                                <span class="w40p db z">未读取</span>
                                <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>">未读取</a>
                                <span class="tlisty">未读取</span>
                            </li>
                            
                        </div>
                    </div>
                </div><!--待完成任务-->
                
                <div class="w50 z pr10 sizing"><!--当前参与项目-->
                    <div class="panel" style="border:1px #f2dede solid;">
                        <h3 class="mbn mtn fs15 pl15 pt15 pb15 sizing bg-danger">
                            当前参与项目
                            <a href="javascript:void(0);" class="y fs12 mr10">更多>></a>
                        </h3>
                        <div class="panel-body">
                            
                            <p class="publicdiv mbn fwb">
                                <span>ID</span>
                                <span class="ml20">任务名称</span>
                                <span class="y">状态</span>
                            </p>
                        
                            <li class="publicdiv pr tlist pr70 h25 lh25 ellipsis">
                                <span class="w40p db z">未读取</span>
                                <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>">未读取</a>
                                <span class="tlisty">未读取</span>
                            </li>
                            
                        </div>
                    </div>
                </div><!--当前参与项目-->
                
            </div>
            
            <div class="publicdiv">
                <div class="w50 z pr10 sizing"><!--最近动态-->
                    <div class="panel" style="border:1px #d9edf7 solid;">
                        <h3 class="mbn mtn fs15 pl15 pt15 pb15 sizing bg-info">最近动态</h3>
                        <div class="panel-body">
                            <p class="publicdiv mbn fwb">
                                <span>ID</span>
                                <span class="ml20">动态名称</span>
                                <span class="y">时间</span>
                            </p>
                        
                            <li class="publicdiv pr tlist pr100 h25 lh25 ellipsis">
                                <span class="w40p db z">未读取</span>
                                <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>">未读取</a>
                                <span class="tlisty">未读取</span>
                            </li>
                            
                        </div>
                    </div>
                </div><!--最近动态-->
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


 <!-- 用于加载js代码 --}

<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
	
</div>

	<!-- /底部 -->
</body> 
</html>


