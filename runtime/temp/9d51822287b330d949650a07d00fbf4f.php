<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:54:"../application/template/default/index\project\add.html";i:1487319926;s:54:"../application/template/default/index\base\common.html";i:1487565884;}*/ ?>
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
    
<div class="container" style="width:1170px;">
<div class="publicdiv pl20 pr20 pt8 bf sizing">
    <div class="publicdiv mb20 ptlrb10 bf sizing">
        <h3 class="ac"><?php if($project_id == 0): ?>添加项目<?php endif; if($project_id != 0): ?>修改项目<?php endif; ?></h3>
        <form action="" method="post" class="form-horizontal" role="form">
            <div class="modal-body">
            
                    <div class="form-group w100 mrn mln z">
                        <label class="col-sm-2 control-label" style="width:130px;">项目名称：</label>
                        <div class="col-sm-10 pln prn" style="width:910px;">
                            <input type="text" name="name" class="form-control" id="focusedInput" placeholder="请输入项目名称" value="<?php echo $project_details['name']; ?>">
                        </div>
                    </div>
      
                    <div class="form-group w100 mrn mln z">
                        <label class="col-sm-2 control-label" style="width:130px;">该项目成员：</label>
                        <div class="col-sm-10 pln prn" style="width:910px;">
                            <?php foreach($user_list as $user): ?>
                            <label class="mr10"><input type="checkbox" name="username[<?php echo $user['uid']; ?>]" class="z" <?php if(in_array($user['username'],$team_list)): ?> checked="ckecked"<?php endif; ?> value="<?php echo $user['username']; ?>" /> <?php echo $user['realname']; ?>
                            <input type="hidden" name="ids[<?php echo $user['uid']; ?>]" value="<?php echo $user['username']; ?>" class="z" /></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="form-group w100 mrn mln z">
                        <label class="col-sm-2 control-label" style="width:130px;">项目负责人：</label>
                        <div class="col-sm-10 pln prn" style="width:910px;">
                            <select class="form-control" name='project_admin'>
                                <?php foreach($user_list as $user): ?>
                                <option value="<?php echo $user['username']; ?>"  <?php if($user['username'] == $project_details['project_admin']): ?>selected="selected"<?php endif; ?>    ><?php echo $user['realname']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group w50 mrn mln z">
                        <label class="col-sm-3 control-label">开始时间：</label>
                        <div class="col-sm-9 pln prn">
                            <input type="text" name="begin" id="begin_time" class="form-control" placeholder="请输入开始时间" value="<?php echo $project_details['begin']; ?>" /> 
                        </div>
                    </div>
                    
                    <div class="form-group w50 mrn mln z">
                        <label class="col-sm-3 control-label">结束时间：</label>
                        <div class="col-sm-9 pln prn">
                            <input type="text" name="end" class="form-control" id="end_time" placeholder="请输入结束时间" value="<?php echo $project_details['end']; ?>" />
                        </div>
                    </div>
                    
                    <div class="form-group w50 mrn mln z">
                        <label class="col-sm-3 control-label">项目状态：</label>
                        <div class="col-sm-9 pln prn">
                            <select class="form-control" name="status">
                                <option class="form-control"  <?php if($project_details['status'] == 'wait'): ?>selected="selected"<?php endif; ?> value="wait" >等待</option>
                                <option class="form-control"  <?php if($project_details['status'] == 'doing'): ?>selected="selected"<?php endif; ?> value="doing" >进行中</option>
                                <option class="form-control"  <?php if($project_details['status'] == 'done'): ?>selected="selected"<?php endif; ?> value="done" >完成</option>
                                <option class="form-control" <?php if($project_details['status'] == 'closed'): ?>selected="selected"<?php endif; ?> value="closed" >关闭</option>
                            </select>
                        </div>
                    </div>

                    
                    <script type="text/javascript">
                    $(document).ready(function() {
                        var begin_time = $("#begin_time").val();
                        var end_time = $("#end_time").val();
                        var myDate = new Date(); 
                        if(!begin_time) {
                            begin_time = myDate.toLocaleDateString();
                        }
                        if(!end_time) {   
                            end_time = myDate.toLocaleDateString();
                        }
                       $('#begin_time').daterangepicker({singleDatePicker:true,format: 'YYYY-MM-DD',startDate: begin_time}, function(start, end, label) {
                         console.log(start.toISOString(), end.toISOString(), label);
                       });
                       $('#end_time').daterangepicker({ singleDatePicker: true,format: 'YYYY-MM-DD',startDate: end_time}, function(start, end, label) {
                         console.log(start.toISOString(), end.toISOString(), label);
                       });
                    });
                   </script>

                   <div class="form-group w50 mrn mln z">
                       <label class="col-sm-3 control-label">版本号：</label>
                       <div class="col-sm-9 pln prn">
                           <input type="text" name="code" class="form-control" id="focusedInput" placeholder="请输入版本号" value="<?php echo $project_details['code']; ?>" />
                       </div>
                   </div>
                   
                    <script type="text/javascript" charset="utf-8" src="tpl_static/ueditor/ueditor.config.js"></script>
                    <script type="text/javascript" charset="utf-8" src="tpl_static/ueditor/ueditor.all.min.js"> </script>
                    <script type="text/javascript" charset="utf-8" src="tpl_static/ueditor/lang/zh-cn/zh-cn.js"></script>
                    
                    <div class="form-group w100 mrn mln z">
                        <label class="col-sm-2 control-label" style="width:130px;">项目说明</label>
                        <div class="col-sm-10 pln prn" style="width:910px;">
                            <!--<textarea id="explainmessage" name="desc" class="form-control"><?php echo $project_details['desc']; ?></textarea>-->
                            <script id="editor" type="text/plain" class="z" name="desc" style="width:100% !important; height:200px;"><?php echo $project_details['desc']; ?></script>
                        </div>
                    </div>
                    
                    <script type="text/javascript">
                        //实例化编辑器
                        //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
                        var ue = UE.getEditor('editor',{toolbars: [['fullscreen', 'source', 'undo', 'redo', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', 'attachment']]});

                        function disableBtn(str) {
                            var div = document.getElementById('btns');
                            var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
                            for (var i = 0, btn; btn = btns[i++];) {
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
                            for (var i = 0, btn; btn = btns[i++];) {
                                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
                            }
                        }
                    </script>
                   
                </div>
            <div class="modal-footer z btno w100">
                <input type="submit" class="btn btn-primary btn-lg" value="完成" />
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


