<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:54:"../application/template/default/admin\index\index.html";i:1487672645;s:54:"../application/template/default/admin\base\common.html";i:1487672645;s:56:"../application/template/default/admin\base\left_nav.html";i:1487672645;}*/ ?>
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
<script type="text/javascript" src="tpl_js/admin/public.js"></script>
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
    <div class="publicdiv pl10 pr10 bf sizing">
        <h3 class="ac">后台首页</h3>
        <ul class="data_statistics iconfont"><!--盘局 -->
            <a href="javascript:void(0);"><li class="w20 z pl10 pr10 sizing">
                <div class="datasta dcs01">
                    <div class="aict ac">&#xe6c9;</div>
                    <h3>未读取</h3>
                    <p>项目管理</p>
                </div>
            </li></a>

            <a href="javascript:void(0);"><li class="w20 z pl10 pr10 sizing">
                <div class="datasta dcs02 sizing">
                    <div class="aict ac">&#xe613;</div>
                    <h3>未读取</h3>
                    <p>任务管理</p>
                </div>
            </li></a>

            <a href="javascript:void(0);"><li class="w20 z pl10 pr10 sizing">
                <div class="datasta dcs03 sizing">
                    <div class="aict ac">&#xe60d;</div>
                    <h3>未读取</h3>
                    <p>收藏管理</p>
                </div>
            </li></a>

            <a href="javascript:void(0);"><li class="w20 z pl10 pr10 sizing">
                <div class="datasta dcs04 sizing">
                    <div class="aict ac">&#xe704;</div>
                    <h3>未读取</h3>
                    <p>文档管理</p>
                </div>
            </li></a>

            <a href="javascript:void(0);"><li class="w20 z pl10 pr10 sizing">
                <div class="datasta dcs05 sizing">
                    <div class="aict ac">&#xe647;</div>
                    <h3>未读取</h3>
                    <p>用户管理</p>
                </div>
            </li></a>

        </ul><!--盘局 -->
        
    </div>
    
    <div class="publicdiv mt20 bf pl20 pr20 pt20 pb20 sizing">
        
        <div class="w33 z pr20 sizing"><!--各项目进度-->
            <div class="panel" style="border:1px #F37B53 solid;">
                <h3 class="mbn mtn fs15 cf pl15 pt15 pb15 sizing" style="background:#F37B53;">
                    各项目进度
                    <a href="<?php echo url('/admin/project/lists'); ?>" class="y cf fs12 mr10">更多>></a>
                </h3>
                <div class="panel-body" id="sofpul">
                    
                    <li class="sofpli">
                        <span class="slid iconfont">&#xe621;</span>
                        <h3 class="mtn mb8 fwn fs13 ellipsis">视频站1.0版本开发</h3>
                        <p><span style="width:50%;" id="sofplisp"></span></p>
                        <span class="slri">50%</span>
                    </li>
                    <li class="sofpli">
                        <span class="slid iconfont">&#xe621;</span>
                        <h3 class="mtn mb8 fwn fs13 ellipsis">视频站1.0版本开发</h3>
                        <p><span style="width:24%;" id="sofplisp"></span></p>
                        <span class="slri">24%</span>
                    </li>
                    <li class="sofpli">
                        <span class="slid iconfont">&#xe621;</span>
                        <h3 class="mtn mb8 fwn fs13 ellipsis">视频站1.0版本开发</h3>
                        <p><span style="width:79%;" id="sofplisp"></span></p>
                        <span class="slri">79%</span>
                    </li>
                    <li class="sofpli">
                        <span class="slid iconfont">&#xe621;</span>
                        <h3 class="mtn mb8 fwn fs13 ellipsis">视频站1.0版本开发</h3>
                        <p><span style="width:90%;" id="sofplisp"></span></p>
                        <span class="slri">90%</span>
                    </li>
                    <li class="sofpli">
                        <span class="slid iconfont">&#xe621;</span>
                        <h3 class="mtn mb8 fwn fs13 ellipsis">视频站1.0版本开发</h3>
                        <p><span style="width:100%;" id="sofplisp"></span></p>
                        <span class="slri">100%</span>
                    </li>
                    
                </div>
            </div>
        </div><!--各项目进度-->
        
        <div class="w33 z pr20 sizing"><!--待完成任务-->
            <div class="panel" style="border:1px #FFB400 solid;">
                <h3 class="mbn mtn fs15 pl15 cf pt15 pb15 sizing" style="background:#FFB400;">
                    待完成任务
                    <a href="<?php echo url('/index/Mycenter/task_list/?status=ontdone'); ?>" class="y cf fs12 mr10">更多>></a>
                </h3>
                <div class="panel-body">
                    
                    <p class="publicdiv mbn fwb">
                        <span>ID</span>
                        <span class="ml20">任务名称</span>
                        <span class="y">类型</span>
                    </p>
                
                    <li class="publicdiv pr tlist pr70 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr70 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr70 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr70 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr70 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr70 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr70 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    
                </div>
            </div>
        </div><!--待完成任务-->
        
        <div class="w33 z sizing"><!--最新文档-->
            <div class="panel" style="border:1px #9D4A9C solid;">
                <h3 class="mbn mtn fs15 pl15 cf bf5 pt15 pb15 sizing" style="background:#9D4A9C;">
                    最新文档
                    <a href="<?php echo url('/admin/article/lists'); ?>" class="y cf fs12 mr10">更多>></a>
                </h3>
                <div class="panel-body">
                    
                    <p class="publicdiv mbn fwb">
                        <span>ID</span>
                        <span class="ml20">文档名称</span>
                        <span class="y">时间</span>
                    </p>
                
                    <li class="publicdiv pr tlist pr100 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr100 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr100 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr100 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li><li class="publicdiv pr tlist pr100 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr100 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    <li class="publicdiv pr tlist pr100 lh35 ellipsis">
                        <span class="w40p db z">未读取</span>
                        <a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>" class="cf1">未读取</a>
                        <span class="tlisty">未读取</span>
                    </li>
                    
                </div>
            </div>
        </div><!--最新文档-->
        
        
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


