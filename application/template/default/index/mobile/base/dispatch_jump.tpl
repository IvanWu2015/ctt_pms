{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <link href="tpl_static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="tpl_static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="tpl_static/bootstrap/js/bootstrap.min.js"></script>

</head>
<body>

    <div style="width:1000px; margin:100px auto;">
    <?php switch ($code) {?>
        <?php case 1:?>
            <div class="alert alert-success">
                <h3><?php echo(strip_tags($msg));?></h3>
                页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
            </div>
        <?php break;?>
        <?php case 0:?>
            <div class="alert alert-info">
                <h3><?php echo(strip_tags($msg));?></h3>
                页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
            </div>
        <?php break;?>
    <?php } ?>
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
</body>
</html>