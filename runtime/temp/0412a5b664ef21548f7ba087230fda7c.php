<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:54:"../application/template/default/index\task\detail.html";i:1487582975;s:54:"../application/template/default/index\base\common.html";i:1487668775;}*/ ?>
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
    
<!-- Contents ================================================== -->
<section id="contents" class="mtn">
    <div class="publicdiv mb10 sizing">
        <h3 class="mt5">
            <a href="<?php echo url('/index/Project/detail/?id='.$project_id); ?>"><?php echo $project_detail['name']; ?></a>
            <span class="fs14">
                <a <?php if($_G['action'] == 'detail'): ?>class="ml10 btn-link"<?php else: ?>class="ml10"<?php endif; ?> href="<?php echo url('/index/Project/detail?id='.$project_id); ?>">任务</a>
                <a <?php if($_G['action'] == 'group'): ?>class="ml10 btn-link"<?php else: ?>class="ml10"<?php endif; ?> href="<?php echo url('/index/Project/group?project_id='.$project_id); ?>">团队</a>
                <a <?php if($_G['action'] == 'action'): ?>class="ml10 btn-link"<?php else: ?>class="ml10"<?php endif; ?> href="<?php echo url('/index/Project/action?project_id='.$project_id); ?>">动态</a>
                <a <?php if($_G['action'] == 'info'): ?>class="ml10 btn-link"<?php else: ?>class="ml10"<?php endif; ?> href="<?php echo url('/index/Project/info?project_id='.$project_id); ?>">概况</a>
            </span>
        </h3>
    </div>

    <div class="publicdiv bf pb20 sizing" id="action_wrap">
        <h3 class="mtn mb10 fwb pb15 fs16 pt15 pl20 pr20 sizing" style="background:#f8fafe; border-bottom:1px solid #e5e5e5;">
            <?php echo $task_detail['name']; ?>
            <span class="y derkwt fs13 iconfont">
                <a href="javascript:void(0);" class="btn-link" title="指派" onClick="loadWindow('<?php echo url('/index/task/action?ac=assignTo&id='.$task_id.'&type=assign'); ?>');">&#xe752; 指派</a>
                <?php if(($task_detail['status'] == 'done') or ($task_detail['status'] == 'doing')): ?>
                    <a href="javascript:void(0);" class="btn-link cc" title="开始">&#xe621; 开始</a>
                <?php else: ?>
                    <a href="javascript:void(0);" class="btn-link" title="开始" onClick="loadWindow('<?php echo url('/index/task/action?ac=start&id='.$task_id.'&type=task'); ?>');">&#xe621; 开始</a>
                <?php endif; ?>
                <a href="javascript:void(0);" class="btn-link" title="工时" onClick="loadWindow('<?php echo url('/index/task/action?ac=worktime&id='.$task_id.'&type=taskestimate'); ?>');">&#xe61e; 工时</a>
                <?php if($task_detail['status'] == 'done'): ?>
                    <a href="javascript:void(0);" class="btn-link cc" title="完成">&#xe619; 完成</a>
                <?php else: ?>
                    <a href="javascript:void(0);" class="btn-link" title="完成" onClick="loadWindow('<?php echo url('/index/task/action?ac=done&id='.$task_id.'&type=task'); ?>');">&#xe619; 完成</a>
                <?php endif; if(($task_detail['status'] == 'wait') or ($task_detail['status'] == 'doing')): ?>
                    <a href="javascript:void(0);" class="btn-link cc" title="关闭">&#xe61c; 关闭</a>
                <?php else: ?>
                    <a href="javascript:void(0);" class="btn-link" title="关闭" onClick="loadWindow('<?php echo url('/index/task/action?ac=close&id='.$task_id.'&type=task'); ?>');">&#xe61c; 关闭</a>
                <?php endif; ?>
                <a href="<?php echo url('/index/task/add/?task_id='.$task_id); ?>" class="btn-link" title="编辑">&#xe71d; 编辑</a>
            </span>
        </h3>
        
        <div class="publicdiv pl20 pr20 sizing">
            <div class="span7 mt20 mln z" style="width:72%;">
                <div class="publicdiv pr bor pt20 pb20 pl20 mb20 pr20 sizing">
                    <span class="pa bf pl8 pr8 lh34 fs16" style="left:10px; top:-20px;">任务描述</span>
                    <?php echo $task_detail['desc']; ?>
                </div>
                
                <div class="publicdiv pr bor pt20 pb20 mt20 pl20 pr20 sizing">
                    <span class="pa bf pl8 pr8 lh34 fs18" style="left:10px; top:-20px;">任务动态</span>
                    
                    <div class="publicdiv mt20 pt20 pb20 pl20 pr pr20 bf6f6f6 sizing">
                        <?php foreach($action_list as $action): ?>
                            <?php echo $action['date']; ?>
                            由<b class="ml5 mr5"><?php echo $action['actor']; ?></b><?php echo $action['actionname']; if($action['comment'] != null): ?>
                            <div class="publicdiv mb10" style="margin-left:140px;">
                                <div class="z br3 mt10 pl10 pr10 pt5 pb5 pr ncnt" style="background:#D3DAE0;"><?php echo trim($action['comment']); ?></div>
                            </div>
                            <?php endif; ?>
                            <br/>
                        <?php endforeach; ?>
                    </div>  
                    
                    <form action="<?php echo url('/index/task/action?id='.$task_id.'&type=commented'); ?>" method="post">
                    <script type="text/javascript" charset="utf-8" src="tpl_static/ueditor/ueditor.config.js"></script>
                    <script type="text/javascript" charset="utf-8" src="tpl_static/ueditor/ueditor.all.min.js"></script>
                    <script type="text/javascript" charset="utf-8" src="tpl_static/ueditor/lang/zh-cn/zh-cn.js"></script>

                    <div class="publicdiv mt20">
                        <script id="editor" type="text/plain" class="z" name="work" style="width:100% !important; height:100px;">备注</script>
                    </div>

                    <script type="text/javascript">
                        jQuery(function () {
                            jQuery('#action_wrap #action_list').each(function (index) {
                                jQuery('#action_wrap #action_list').eq(index).find('.action_id').text(index + 1);
                            });
                        });

                        //实例化编辑器
                        //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
                        var ue = UE.getEditor('editor', {toolbars: [['fullscreen', 'source', 'undo', 'redo', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', 'attachment']]});

                        function disableBtn(str) {
                            var div = document.getElementById('btns');
                            var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
                            for (var i = 0, btn; btn = btns[i++]; ) {
                                if (btn.id == str) {
                                    UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
                                } else {
                                    btn.setAttribute("disabled", "true");
                                }
                            }
                        }
                        function enableBtn() {
                            var div = document.getElementById('btns');
                            var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
                            for (var i = 0, btn; btn = btns[i++]; ) {
                                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
                            }
                        }
                    </script>
                    
                    <div class="publicdiv mt10 ac">
                        <input type="submit" class="btn btn-primary pl20 pr20" value="完成" />
                    </div>
                    
                    </form>
                    
                </div>
                
                
                
                
            </div>
            <div class="span4 mt20 y" style="width:25%;">
                <div class="publicdiv pr bor pt20 pb20 pl20 mln pr20 sizing">
                    <span class="pa bf pl8 pr8 lh34 fs16" style="left:10px; top:-20px;">基本信息</span>
                    <p>项目名称：<span class="text-primary mr20 ml10"><?php echo $task_detail['name']; ?></span></p>
                    <p>创&nbsp;建&nbsp;&nbsp;者：<span class="text-primary mr20 ml10"><?php echo $user_list[$task_detail['openedBy']]['realname']; ?></span></p>
                    <p>指&nbsp;派&nbsp;&nbsp;给：<span class="text-primary mr20 ml10"><?php echo $user_list[$task_detail['assignedTo']]['realname']; ?></span></p>
                    <p>优&nbsp;先&nbsp;&nbsp;级：<span class=" ml10"><?php echo $task_detail['pri']; ?></span></p>
                    <p>预计耗时：<span class="text-primary mr20 ml10"><?php echo $task_detail['estimate']; ?></span></p>
                    <p>开始时间：<span class=" ml10"><?php echo $task_detail['openedDate']; ?></span></p>
                    <p>结束时间：<span class=" ml10"><?php echo $task_detail['deadline']; ?></span></p>
                </div>

                <div class="publicdiv pr bor pt20 pb20 pl20 mln mt20 mb10 pr20 sizing">
                    <span class="pa bf pl8 pr8 lh34 fs16" style="left:10px; top:-20px;">工时信息</span>
                    <p>预计时间：<span class=" ml10"><?php echo $task_detail['estStarted']; ?></span></p>
                    <p>真正时间：<span class=" ml10"><?php if($task_detail['realStarted'] > 2001-01-01): ?><?php echo $task_detail['realStarted']; else: endif; ?></span></p>
                </div>

                <div class="publicdiv pr bor pt20 pb20 pl20 mln mt20 pr20 sizing">
                    <span class="pa bf pl8 pr8 lh34 fs16" style="left:10px; top:-20px;">任务的一生</span>
                    <p>由谁创建：<span class=" ml10"><?php echo $user_list[$task_detail['openedBy']]['realname']; ?>于<?php echo $task_detail['openedDate']; ?></span></p>
                    <p>由谁完成：<span class=" ml10"><?php if(!empty($task_detail['finishedBy'])): ?><?php echo $user_list[$task_detail['finishedBy']]['realname']; ?> 于 <?php echo $task_detail['finishedDate']; endif; ?></span></p>
                    <p>由谁取消：<span class=" ml10"><?php if(!empty($task_detail['canceledBy'])): ?><?php echo $user_list[$task_detail['canceledBy']]['realname']; ?>于 <?php echo $task_detail['canceledDate']; endif; ?></span></p>
                    <p>由谁关闭：<span class=" ml10"><?php if(!empty($task_detail['closedBy'])): ?><?php echo $user_list[$task_detail['closedBy']]['realname']; ?> 于 <?php echo $task_detail['closedDate']; endif; ?></span></p>
                    <p>最后编辑：<span class=" ml10"><?php if(!empty($task_detail['lastEditedBy'])): ?><?php echo $user_list[$task_detail['lastEditedBy']]['realname']; ?> 于 <?php echo $task_detail['lastEditedDate']; endif; ?></span></p>
                </div>
            </div>
            
        </div>
    </div>

    <div class="publicdiv bort bf pl20 pr20 pt20 pb20 lh34 sizing">该任务所用的总时间为 <span class="text-primary fs25"><?php echo $task_detail['consumed']; ?></span> 小时</div>

</section>
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


