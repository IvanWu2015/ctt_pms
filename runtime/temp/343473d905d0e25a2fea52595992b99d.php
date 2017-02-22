<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"../application/template/default/index\user\login.html";i:1487672645;s:54:"../application/template/default/index\base\common.html";i:1487672645;}*/ ?>
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


	<!-- /头部 -->
	
	<!-- 主体 -->


<!--<div id="main-container" class="container" style="margin-top:50px;">-->
<div class="publicdiv pl20 pr20 sizing" style="margin-top:50px;">
    <div class="prompt" id="prompt"><b class="cred mr5">*</b>项必填</div>
    <div id="addpr"></div>
    <div class="applica" id="applica"></div><!-- 调用页面 -->
    
<section>
    <div class="center-block bf pt20 pr20 pl20 pb20 sizing" style="width:500px; border-radius:3px; margin:0px auto;">
        <h3 class="mtn mb20 ac">用户登录</h3>
        <form class="form-horizontal" role="form" class="login-form" action="" method="post">
            <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label">用户名</label>
                <div class="col-sm-10 pln">
                    <input type="text" class="form-control" id="firstname" placeholder="请输入用户名" ajaxurl="/member/checkUserNameUnique.html" errormsg="请填写1-16位用户名" nullmsg="请填写用户名" datatype="*1-16" value="" name="username" />
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">密码</label>
                <div class="col-sm-10 pln">
                    <input type="password" class="form-control" id="lastname" placeholder="请输入密码" errormsg="密码为6-20位" nullmsg="请填写密码" datatype="*6-20" name="password" />
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">验证码</label>
                <div class="col-sm-10 pln">
                    <input type="text" class="form-control" id="lastname" placeholder="请输入验证码"  errormsg="请填写5位验证码" nullmsg="请填写验证码" datatype="*5-5" name="verify" />
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label"></label>
                <div class="controls">
                    <img id="verify_img" class="verifyimg reloadverify" alt="点击切换" src="<?php echo captcha_src(); ?>" style="cursor:pointer;" onclick="this.src='<?php echo captcha_src(); ?>'">
                </div>
                <div class="controls Validform_checktip text-warning"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="autologin" value="1"> 自动登陆
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">登 陆</button>
                    <a href="<?php echo url('User/register'); ?>" class="ml20">立即注册</a>
                </div>
            </div>
        </form>

    </div>
</section>

</div>

<script type="text/javascript">
    $(function(){
        $(window).resize(function(){
            $("#main-container").css("min-height", $(window).height() - 343);
        }).resize();
    })
</script>

	<!-- /主体 -->




<script type="text/javascript">
    function refreshVerify() {
        var ts = Date.parse(new Date()) / 1000;
        $('#verify_img').attr("src", "/captcha?id=" + ts);
        return;
        }
    $(document)
            .ajaxStart(function () {
                $("button:submit").addClass("log-in").attr("disabled", true);
            })
            .ajaxStop(function () {
                $("button:submit").removeClass("log-in").attr("disabled", false);
            });


    $("form").submit(function () {
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(data) {
            if (data..data.status) {
                window.location.href = data.url;
            } else {
                self.find(".Validform_checktip").text(data.info);
                //刷新验证码
                $(".reloadverify").click();
            }
        }
    });
</script>
 <!-- 用于加载js代码 --}

<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
	
</div>

	<!-- /底部 -->
</body> 
</html>


