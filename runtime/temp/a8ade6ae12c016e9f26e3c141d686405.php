<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:51:"../application/template/default/index\task\add.html";i:1487319926;s:54:"../application/template/default/index\base\common.html";i:1487559130;}*/ ?>
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
                <li class="mt20 mb10"><a href="<?php echo url('article/lists'); ?>">文章</a></li>
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
    
<div class="container" style="width:1170px;">
<div class="publicdiv pl20 pr20 pt8 bf sizing">
    <div class="publicdiv mb20 ptlrb10 bf sizing">
        <h3 class="ac">
            <?php if($task_id == 0): ?>添加任务<?php endif; if($task_id != 0): ?>修改任务<?php endif; ?>
        </h3>
        <?php if($task_id == 0): ?><form action="<?php echo url('/index/task/add'); ?>" method="post" class="form-horizontal" role="form"><?php endif; if($task_id != 0): ?><form action="<?php echo url('/index/task/add?task_id='.$task_id); ?>" method="post" class="form-horizontal" role="form"><?php endif; ?>
                <div class="modal-body">
                    <div class="form-group w100 mrn mln z">
                        <label class="col-sm-2 control-label" style="width:130px;"><b class="cred">* </b>所属项目：</label>
                        <div class="col-sm-10 pln prn" style="width:910px;">
                            <select name="project_id" class="form-control">
                                <?php foreach($project_list as $temp_project): ?>
                                <option <?php if($temp_project['id'] == $project_id): ?>selected='selected'<?php endif; ?> value="<?php echo $temp_project['id']; ?>" ><?php echo $temp_project['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group w100 mrn mln z">
                        <label class="col-sm-2 control-label" style="width:130px;"><b class="cred">* </b>任务名称：</label>
                        <div class="col-sm-10 pln prn" style="width:910px;">
                            <input type="text" name="name" class="form-control" id="name" placeholder="请输入任务名称" value="<?php echo $task_details['name']; ?>">
                        </div>
                    </div>

                    <div class="form-group w50 mrn mln z">
                        <label class="col-sm-3 control-label">指派给：</label>
                        <div class="col-sm-9 pln prn">
                            <select name="assignedTo" class="form-control">
                                <?php foreach($user_list as $user): ?>
                                <option <?php if($user['username'] == $task_details['assignedTo']): ?>selected='selected'<?php endif; ?>value="<?php echo $user['username']; ?>" ><?php echo $user['realname']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group w50 mrn mln z">
                        <label class="col-sm-3 control-label">任务类型：</label>
                        <div class="col-sm-9 pln prn">
                            <select name="type" id="type" class="form-control" onchange="setOwners(this.value)">
                                <?php foreach($_G['type_list'] as $key => $temp_type): ?>
                                <option <?php if($key == $task_details['type']): ?>selected='selected'<?php endif; ?>value="<?php echo $key; ?>" ><?php echo $temp_type; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group w50 mrn mln z">
                        <label class="col-sm-3 control-label"><b class="cred">* </b>预计工时：</label>
                        <div class="col-sm-9 pln prn">
                            <input type="text" name="estimate" id="estimate" class="form-control" placeholder="任务预计工时需要多少小时" value="<?php echo $task_details['estimate']; ?>" /> 
                        </div>
                    </div>

                    <div class="form-group w50 mrn mln z">
                        <label class="col-sm-3 control-label">任务优先级：</label>
                        <div class="col-sm-9 pln prn">
                            <select  class="form-control" name="pri">
                                <option value="0" <?php if($task_details['pri'] == '0'): ?>selected="selected"<?php endif; ?> >0</option>
                                <option value="1" <?php if($task_details['pri'] == '1'): ?>selected="selected"<?php endif; ?> >1</option>
                                <option value="2" <?php if($task_details['pri'] == '2'): ?>selected="selected"<?php endif; ?> >2</option>
                                <option value="3" <?php if($task_details['pri'] == '3'): ?>selected="selected"<?php endif; ?> >3</option>
                                <option value="4" <?php if($task_details['pri'] == '4'): ?>selected="selected"<?php endif; ?> >4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group w50 mrn mln z">
                        <label class="col-sm-3 control-label">开始时间：</label>
                        <div class="col-sm-9 pln prn">
                            <input type="text" name="realStarted" class="form-control" id="begin_time" placeholder="请输入开始时间" value="<?php echo $task_details['realStarted']; ?>" />
                        </div>
                    </div>

                    <div class="form-group w50 mrn mln z">
                        <label class="col-sm-3 control-label">最后期限：</label>
                        <div class="col-sm-9 pln prn">
                            <input type="text" name="deadline" class="form-control" id="end_time" placeholder="请输入结束时间" value="<?php echo $task_details['deadline']; ?>" />
                        </div>
                    </div>

                    <div class="form-group w50 mrn mln z">
                        <label class="col-sm-3 control-label">任务状态：</label>
                        <div class="col-sm-9 pln prn">
                            <select class="form-control" name="status">
                                <option class="form-control"  <?php if($task_details['status'] == 'wait'): ?>selected="selected"<?php endif; ?> value="wait" >等待</option>
                                <option class="form-control"  <?php if($task_details['status'] == 'doing'): ?>selected="selected"<?php endif; ?> value="doing" >进行中</option>
                                <option class="form-control"  <?php if($task_details['status'] == 'done'): ?>selected="selected"<?php endif; ?> value="done" >完成</option>
                                <option class="form-control" <?php if($task_details['status'] == 'closed'): ?>selected="selected"<?php endif; ?> value="closed" >关闭</option>
                            </select>
                        </div>
                    </div>

                    <script type="text/javascript" charset="utf-8" src="tpl_static/ueditor/ueditor.config.js"></script>
                    <script type="text/javascript" charset="utf-8" src="tpl_static/ueditor/ueditor.all.min.js"></script>
                    <script type="text/javascript" charset="utf-8" src="tpl_static/ueditor/lang/zh-cn/zh-cn.js"></script>

                    <div class="form-group z">
                        <label class="col-sm-2 control-label" style="width:145px;"><b class="cred">* </b>任务说明：</label>
                        <div class="col-sm-10 pln" style="width:922px;">
                            <!--<textarea id="explainmessage" name="desc" class="form-control"><?php echo $task_details['desc']; ?></textarea>-->
                            <script id="editor" type="text/plain" class="z" name="desc" style="width:100% !important; height:250px;"><?php echo $task_details['desc']; ?></script>
                        </div>
                    </div>

                    <script type="text/javascript">
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

                    <script type="text/javascript">
                        $(document).ready(function () {
                            var begin_time = $("#begin_time").val();
                            var end_time = $("#end_time").val();
                            var myDate = new Date();
                            if (!begin_time) {
                                begin_time = myDate.toLocaleDateString();
                            }
                            if (!end_time) {
                                end_time = myDate.toLocaleDateString();
                            }
                            $('#begin_time').daterangepicker({singleDatePicker: true, format: 'YYYY-MM-DD', startDate: begin_time}, function (start, end, label) {
                                console.log(start.toISOString(), end.toISOString(), label);
                            });
                            $('#end_time').daterangepicker({singleDatePicker: true, format: 'YYYY-MM-DD', startDate: end_time}, function (start, end, label) {
                                console.log(start.toISOString(), end.toISOString(), label);
                            });
                        });
                    </script>
                </div>
                <div class="modal-footer ptn mtn btno ac z w100">
                    <input type="submit" class="btn btn-primary btn-lg pl20 pr20" onClick="formsubif();" value="完成" />
                </div>
            </form>

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


