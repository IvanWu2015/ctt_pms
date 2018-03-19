{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="blue">
    <meta name="format-detection" content="telephone=no">

    <link href="tpl_static/bootstrap/css/docs.css" rel="stylesheet">
    <link href="tpl_static/bootstrap/css/onethink.css" rel="stylesheet">
    <link href="tpl_css/public.css" rel="stylesheet">
    <link href="tpl_css/moblie.css" rel="stylesheet">
    <link href="tpl_css/font/iconfont.css" rel="stylesheet">
    
    <script type="text/javascript" src="tpl_js/public_moblie.js"></script>
    <script type="text/javascript" src="tpl_static/third/jquery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="tpl_css/frozen/css/frozen.css">
    <link rel="stylesheet" href="tpl_css/frozen/demo/demo.css">
    <script src="tpl_css/frozen/lib/zepto.min.js"></script>
    <script src="tpl_css/frozen/js/frozen.js"></script>
</head>
<body>
<div id="moblie_wp">
    <div class="system-message">
        <div class="publicdiv pl10 pr10 iconfont sizing">
        <?php switch ($code) {?>
            <?php case 1:?>
                <div class="prompt_icon ac mt20">&#xe6a0;</div>
                <p class="fs20 ac"><?php echo(strip_tags($msg));?></p>
            <?php break;?>
            <?php case 0:?>
                <div class="prompt_icon ac mt20" style="color:red;">&#xe65f;</div>
                <p class="fs20 ac"><?php echo(strip_tags($msg));?></p>
            <?php break;?>
        <?php } ?>
        <p class="c6 ac pb10">页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></p>
        <a href="<?php echo($url);?>" id="href" class="z ui-btn-lg ui-btn-primary">跳转</a>
        </div>
    </div>
    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</div>
</body>
</html>
