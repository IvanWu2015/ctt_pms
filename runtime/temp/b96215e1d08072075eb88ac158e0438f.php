<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:57:"../application/template/default/index\project\detail.html";i:1486714478;s:54:"../application/template/default/index\base\common.html";i:1487565884;s:61:"../application/template/default/index\base\project_title.html";i:1487582596;s:57:"../application/template/default/index\base\task_list.html";i:1487319926;}*/ ?>
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
    

<div class="publicdiv  pt10 pb10 ptn sizing">
    
    <div class="publicdiv mb10 sizing">
    <h3 class="mt5">
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
    
    <div class="publicdiv pl20 pr20 pt8 pb20 bf sizing">

        <div class="publicdiv  pt8 pb8 bf sizing">
            <ul class="nav nav-pills">
                <li <?php if(empty($status) && empty($username)): ?>class="active"<?php endif; ?>><a href="<?php echo url('/index/Project/detail/?id='.$project_id); ?>">所有</a></li>
                <li <?php if($status == 'noclosed'): ?>class="active"<?php endif; ?>><a href="<?php echo url('/index/Project/detail/?status=noclosed&id='.$project_id); ?>">未关闭</a></li>
                <li <?php if($status == 'delayed'): ?>class="active"<?php endif; ?>><a href="<?php echo url('/index/Project/detail/?status=delayed&id='.$project_id); ?>">已延期</a></li>
                <li <?php if(!empty($username)): ?>class="active"<?php endif; ?>><a href="<?php echo url('/index/Project/detail/?username='.$_G['username'].'&id='.$project_id); ?>">指派给我的</a></li>

                <?php if(!empty($project_detail)): ?>
                <span class="lh40 ml20">开始：<?php echo $project_detail['begin']; ?></span>
                <span class="lh40 ml10">预计：<?php echo $project_detail['all_time']; ?></span>
                <span class="lh40 ml10">消耗：<?php echo $project_detail['consumed_count']; ?></span>
                <?php endif; ?>
                
                <label class="y">
                    <form action="<?php echo url('/index/project/detail/?id='.$project_id); ?>" method="post">
                        <input type="text" name="keyword" class="bor pt5 pb5 pl5 pr5" placeholder="请输入搜索的内容~" value="<?php echo $keyword; ?>" />
                    <input type="submit" class="pt5 pb5 pl10 pr10 cf" style="background:#428bca; border:1px #428bca solid;" value="搜索" />
                    </form>
                </label>
            </ul>
        </div>
        
        <table class="table table-striped borl bort borr borb">
    <thead style="background:#f1f1f1;">
        <tr>
            <th><a href="<?php echo url('/index/project/detail/?id='.$project_id); ?>">任务名称</a></th>
            <th class="ac">任务类型</th>
            <th class="ac">指派给</th>
            <th class="ac">创建者</th>
            <th class="ac">完成者</th>
            <th class="ac">状态</th>
            <th class="ac"><a href="<?php echo url('/index/project/detail/?orderby=pri&id='.$project_id); ?>">优先级</a></th>
            <th class="ac"><a href="<?php echo url('/index/project/detail/?orderby=estimate&id='.$project_id); ?>">预计</a></th>
            <th class="ac"><a href="<?php echo url('/index/project/detail/?orderby=consumed&id='.$project_id); ?>">消耗</a></th>
            <th class="ac">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($task_list as $task): ?>
        <tr>
            <td><a href="<?php echo url('/index/task/detail/?id='.$task['id']); ?>"><?php echo $task['name']; ?></a></td>
            <td class="ac"><?php echo $_G['type_list'][$task['type']]; ?></td>
            <td class="ac"><a href="<?php echo url('/index/project/detail?id='.$project_id.'&username='.$task['assignedTo'].'&status='.$status); ?>"  class="ac <?php if($task["assignedTo"] == $_G["username"]): ?>cred<?php else: ?>text-primary<?php endif; ?>"><?php echo $user_list[$task['assignedTo']]['realname']; ?></a></td>
            <td class="ac"><?php echo $user_list[$task['openedBy']]['realname']; ?></td>
            <td class="ac"><?php echo $user_list[$task['finishedBy']]['realname']; ?></td>
            <?php if($task['status'] == 'wait'): ?><td class="ac" style="color:#f8b004;">等待</td><?php endif; if($task['status'] == 'done'): ?><td class="ac text-muted">完成</td><?php endif; if($task['status'] == 'closed'): ?><td class="ac text-danger">关闭</td><?php endif; if($task['status'] == 'doing'): ?><td class="ac text-primary">进行中</td><?php endif; ?>
            <td class="ac"><?php echo $task['pri']; ?></td>
            <td class="ac"><?php echo $task['estimate']; ?></td>
            <td class="ac"><?php echo $task['consumed']; ?></td>
            <td class="ac fs15 iconfont">
                <a href="javascript:void(0);" class="btn-link" title="指派" onClick="loadWindow('<?php echo url('/index/task/action?ac=assignTo&id='.$task['id']); ?>');">&#xe752;</a>
                <?php if(($task['status'] == 'done') or ($task['status'] == 'doing')): ?>
                    <a href="javascript:void(0);" class="cc" title="开始">&#xe621;</a>
                <?php else: ?>
                    <a href="javascript:void(0);" class="btn-link" title="开始" onClick="loadWindow('<?php echo url('/index/task/action?ac=start&id='.$task['id']); ?>');">&#xe621;</a>
                <?php endif; ?>
                <a href="javascript:void(0);" class="btn-link fs16" title="工时" onClick="loadWindow('<?php echo url('/index/task/action?ac=worktime&id='.$task['id']); ?>');">&#xe61e;</a>
                <?php if($task['status'] == 'done'): ?>
                    <a href="javascript:void(0);" class="cc fs16" title="完成">&#xe619;</a>
                <?php else: ?>
                    <a href="javascript:void(0);" class="btn-link fs16" title="完成" onClick="loadWindow('<?php echo url('/index/task/action?ac=done&id='.$task['id']); ?>');">&#xe619;</a>
                <?php endif; if(($task['status'] == 'wait') or ($task['status'] == 'doing')): ?>
                    <a href="javascript:void(0);" class="cc" title="关闭">&#xe61c;</a>
                <?php else: ?>
                    <a href="javascript:void(0);" class="btn-link cred" title="关闭" onClick="loadWindow('<?php echo url('/index/task/action?ac=close&id='.$task['id']); ?>');">&#xe61c;</a>
                <?php endif; ?>
                <a href="<?php echo url('/Index/task/add?task_id='.$task['id']); ?>" class="btn-link" title="编辑">&#xe71d;</a>
            </td>
        </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
        
        </div>

        <div class="publicdiv ac pagingw"><?php echo $page; ?></div>
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


