<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:54:"../application/template/default/index\task\action.html";i:1487669228;}*/ ?>
<?php if($ac == 'assignTo'): ?><!--指派-->
<div class="modalwrap h350 sizing">
    <form action="" method="post" id="add_tag">
    <h3 class="modaltitle pl15 pr15 sizing">指派<a href="javascript:void(0);" onClick="modalclose();" class="y fs13 iconfont">&#xe61c;</a></h3>
    <div class="publocdiv pl20 pr20 sizing modalcontnet h230">
    
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn"><b class='cred'>*</b>指派给</label>
            <div class="col-sm-9 pln prn">
                <select name="assignedTo" class="form-control">
                    <?php foreach($user_list as $user): ?>
                    <option <?php if($user['username'] == $task_details['assignedTo']): ?>selected='selected'<?php endif; ?>value="<?php echo $user['username']; ?>" ><?php echo $user['realname']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn"><b class='cred'>*</b>预计工时</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="estimate" id="estimate" class="form-control" placeholder="任务预计工时需要多少小时" /> 
            </div>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">任务说明</label>
            <div class="col-sm-9 pln prn">
                <textarea id="explainmessage" name="desc" class="form-control" style="height:100px;"></textarea>
            </div>
        </div>
        
    </div>
    <div class="modalbottom pt8 pb8 pl15 pr15 sizing">
        <a href="javascript:void(0);" class="modalsearch bor pl20 pr20 y" onClick="assigntosub();">提交</a>
    </div>
    </form>
</div>
<script>
jQuery(function(){
    jQuery('#estimate').keyup(function(){   //预计工时
        if(jQuery('#estimate').val().length < 1){
            jQuery('#estimate').css({border:'1px red solid'});
        }else{
            jQuery('#estimate').css({border:'1px #ccc solid'});
        }
    });
    
});
function assigntosub(){ //表单判断
    var estimate=jQuery('#estimate').val(); //获取预计工时
    if(estimate.length < 1){
        jQuery('#addpr').html("<b class='cred'>*</b> 号为必填项").show(1).delay(2000).hide(1);
        jQuery('#estimate').css({border:'1px red solid'});
    }else{
        modalsearch();
    }
}

function modalsearch(){     //ajax提交
    var url="<?php echo url('/index/task/action?id='.$task_id.'&type=assign'); ?>";
    jQuery.ajax({
        type: 'post',
        url: url,
        data: jQuery('#add_tag').serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);
                modalclose();
                setTimeout("location.reload()",2500);
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);
            }
        }
    });
}
</script>

<?php elseif($ac == 'start'): ?><!--开始-->
<div class="modalwrap h350 sizing">
    <form action="" method="post" id="add_tag">
    <h3 class="modaltitle pl15 pr15 sizing">开始<a href="javascript:void(0);" onClick="modalclose();" class="y fs13 iconfont">&#xe61c;</a></h3>
    <div class="publocdiv pl20 pr20 sizing modalcontnet h230">
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn"><b class='cred'>* </b>消耗</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="consumed" id="consumed" class="form-control" placeholder="输入实际用时时间" /> 
            </div>
            <label for="firstname" class="z mr15 lh34 pln fwn ml10 ar control-label"> 小时</label>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn"><b class='cred'>* </b>剩余工时</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="left" id="left" class="form-control" placeholder="输入剩余工时" /> 
            </div>
            <label for="firstname" class="z mr15 lh34 pln fwn ml10 ar control-label"> 小时</label>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">任务说明</label>
            <div class="col-sm-9 pln prn">
                <textarea id="explainmessage" name="work" class="form-control" style="height:100px;"  placeholder="任务更新说明"></textarea>
            </div>
        </div>
        
    </div>
    <div class="modalbottom pt8 pb8 pl15 pr15 sizing">
        <a href="javascript:void(0);" class="modalsearch bor pl20 pr20 y" onClick="startsub();">提交</a>
    </div>
    </form>
</div>
<script>
jQuery(function(){
    jQuery('#consumed').keyup(function(){   //消耗
        if(jQuery('#consumed').val().length < 1){
            jQuery('#consumed').css({border:'1px red solid'});
        }else{
            jQuery('#consumed').css({border:'1px #ccc solid'});
        }
    });
    jQuery('#left').keyup(function(){   //剩余工时
        if(jQuery('#left').val().length < 1){
            jQuery('#left').css({border:'1px red solid'});
        }else{
            jQuery('#left').css({border:'1px #ccc solid'});
        }
    });
});
function startsub(){ //表单判断
    var page=0;
    var consumed=jQuery('#consumed').val(); //获取消耗
    var left=jQuery('#left').val(); //获取剩余工时
    if(consumed.length < 1){
        jQuery('#consumed').css({border:'1px red solid'});
        var page=1;
    }
    if(left.length < 1){
        jQuery('#left').css({border:'1px red solid'});
        var page=1;
    }
    if(page == "1"){
        jQuery('#addpr').html("<b class='cred'>*</b> 号为必填项").show(1).delay(2000).hide(1);
    }else{
        modalsearch();
    }
}

function modalsearch(){     //ajax提交
    var url="<?php echo url('/index/task/action?id='.$task_id.'&type=task'); ?>";
    jQuery.ajax({
        type: 'post',
        url: url,
        data: jQuery('#add_tag').serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);
                modalclose();
                setTimeout("location.reload()",2500);
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);
            }
        }
    });
}
</script>

<?php elseif($ac == 'worktime'): ?><!--记录工时-->
<div class="modalwrap h350 sizing">
    <form action="" method="post" id="add_tag">
    <h3 class="modaltitle pl15 pr15 sizing">记录工时<a href="javascript:void(0);" onClick="modalclose();" class="y fs13 iconfont">&#xe61c;</a></h3>
    <div class="publocdiv pl20 pr20 sizing modalcontnet h230">
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn"><b class='cred'>* </b>消耗</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="consumed" id="consumed" class="form-control" placeholder="输入实际用时时间" /> 
            </div>
            <label for="firstname" class="z mr15 lh34 pln fwn ml10 ar control-label"> 小时</label>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">剩余工时</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="left" id="consumed" class="form-control" placeholder="输入剩余工时" /> 
            </div>
            <label for="firstname" class="z mr15 lh34 pln fwn ml10 ar control-label"> 小时</label>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">任务说明</label>
            <div class="col-sm-9 pln prn">
                <textarea id="explainmessage" name="work" class="form-control" style="height:100px;"  placeholder="任务更新说明"></textarea>
            </div>
        </div>
        
    </div>
    <div class="modalbottom pt8 pb8 pl15 pr15 sizing">
        <a href="javascript:void(0);" class="modalsearch bor pl20 pr20 y" onClick="worktimesub();">提交</a>
    </div>
    </form>
</div>
<script>
jQuery(function(){
    jQuery('#consumed').keyup(function(){   //消耗
        if(jQuery('#consumed').val().length < 1){
            jQuery('#consumed').css({border:'1px red solid'});
        }else{
            jQuery('#consumed').css({border:'1px #ccc solid'});
        }
    });
});
function worktimesub(){ //表单判断
    var page=0;
    var consumed=jQuery('#consumed').val(); //获取消耗
    if(consumed.length < 1){
        jQuery('#consumed').css({border:'1px red solid'});
        var page=1;
    }
    if(page == "1"){
        jQuery('#addpr').html("<b class='cred'>*</b> 号为必填项").show(1).delay(2000).hide(1);
    }else{
        modalsearch();
    }
}

function modalsearch(){     //ajax提交
    var url="<?php echo url('/index/task/action?id='.$task_id.'&type=taskestimate'); ?>";
    jQuery.ajax({
        type: 'post',
        url: url,
        data: jQuery('#add_tag').serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);
                modalclose();
                setTimeout("location.reload()",2500);
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);
            }
        }
    });
}
</script>

<?php elseif($ac == 'done'): ?><!--完成-->
<div class="modalwrap h350 sizing">
    <form action="" method="post" id="add_tag">
    <h3 class="modaltitle pl15 pr15 sizing">完成<a href="javascript:void(0);" onClick="modalclose();" class="y fs13 iconfont">&#xe61c;</a></h3>
    <div class="publocdiv pl20 pr20 sizing modalcontnet h230">
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">总消耗</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="consumed" id="consumed" class="form-control" placeholder="输入实际用时时间" /> 
            </div>
            <label for="firstname" class="z mr15 lh34 pln fwn ml10 ar control-label"> 小时</label>
        </div>
        
        <input type="hidden" name="status" value="done" />
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">任务说明</label>
            <div class="col-sm-9 pln prn">
                <textarea id="explainmessage" name="work" class="form-control" style="height:100px;"  placeholder="任务更新说明"></textarea>
            </div>
        </div>
        
    </div>
    <div class="modalbottom pt8 pb8 pl15 pr15 sizing">
        <a href="javascript:void(0);" class="modalsearch bor pl20 pr20 y" onClick="modalsearch();">提交</a>
    </div>
    </form>
</div>
<script>
function modalsearch(){     //ajax提交
    var url="<?php echo url('/index/task/action?id='.$task_id.'&type=task'.'&ac=done'); ?>";
    jQuery.ajax({
        type: 'post',
        url: url,
        data: jQuery('#add_tag').serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);
                modalclose();
                setTimeout("location.reload()",2500);
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);
            }
        }
    });
}
</script>

<?php elseif($ac == 'close'): ?><!--关闭-->
<div class="modalwrap h350 sizing">
    <form action="" method="post" id="add_tag">
    <h3 class="modaltitle pl15 pr15 sizing">关闭<a href="javascript:void(0);" onClick="modalclose();" class="y fs13 iconfont">&#xe61c;</a></h3>
    <div class="publocdiv pl20 pr20 sizing modalcontnet h230">
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn"><b class='cred'>* </b>总消耗</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="consumed" id="consumed" class="form-control" placeholder="输入实际用时时间" /> 
            </div>
            <label for="firstname" class="z mr15 lh34 pln fwn ml10 ar control-label"> 小时</label>
        </div>
        
        <input type="hidden" name="status" value="closed" />
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">任务说明</label>
            <div class="col-sm-9 pln prn">
                <textarea id="explainmessage" name="work" class="form-control" style="height:100px;"  placeholder="任务更新说明"></textarea>
            </div>
        </div>
        
    </div>
    <div class="modalbottom pt8 pb8 pl15 pr15 sizing">
        <a href="javascript:void(0);" class="modalsearch bor pl20 pr20 y" onClick="closesub();">提交</a>
    </div>
    </form>
</div>
<script>
jQuery(function(){
    jQuery('#consumed').keyup(function(){   //总消耗
        if(jQuery('#consumed').val().length < 1){
            jQuery('#consumed').css({border:'1px red solid'});
        }else{
            jQuery('#consumed').css({border:'1px #ccc solid'});
        }
    });
});
function closesub(){ //表单判断
    var page=0;
    var consumed=jQuery('#consumed').val(); //获取总消耗
    if(consumed.length < 1){
        jQuery('#consumed').css({border:'1px red solid'});
        var page=1;
    }
    if(page == "1"){
        jQuery('#addpr').html("<b class='cred'>*</b> 号为必填项").show(1).delay(2000).hide(1);
    }else{
        modalsearch();
    }
}

function modalsearch(){     //ajax提交
    var url="<?php echo url('/index/task/action?id='.$task_id.'&type=task'.'&ac=closed'); ?>";
    jQuery.ajax({
        type: 'post',
        url: url,
        data: jQuery('#add_tag').serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);
                modalclose();
                setTimeout("location.reload()",2500);
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);
            }
        }
    });
}
</script>

<?php elseif($ac == 'userinform'): ?><!--编辑信息-->
<div class="modalwrap h470 sizing">
    <form action="" method="post" id="add_tag">
    <h3 class="modaltitle pl15 pr15 sizing">编辑信息<a href="javascript:void(0);" onClick="modalclose();" class="y fs13 iconfont">&#xe61c;</a></h3>
    <div class="publocdiv pl20 pr20 sizing modalcontnet h360">
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">真实姓名</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="realname" id="realname" class="form-control" placeholder="输入真实姓名" value="<?php echo $user_info['realname']; ?>" /> 
            </div>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">昵&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="nickname" id="nickname" class="form-control" value="<?php echo $user_info['nickname']; ?>" placeholder="输入您的个性昵称" /> 
            </div>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">出生日期</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="birthday" id="end_time" value="<?php echo $user_info['birthday']; ?>" class="form-control" placeholder="输入您的出生日期" /> 
            </div>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别</label>
            <div class="col-sm-9 pln prn">
                <label><input type="radio" name="gender" <?php if($user_info['gender'] == m): ?>checked='checked'<?php endif; ?> value="m" /> 男</label>
                <label><input type="radio" name="gender" <?php if($user_info['gender'] == f): ?>checked='checked'<?php endif; ?>  value="f" /> 女</label>
            </div>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">Q&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Q</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="qq" id="qq" class="form-control" placeholder="输入QQ号"  value="<?php echo $user_info['qq']; ?>" /> 
            </div>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="email" id="email" class="form-control" placeholder="输入正确的邮箱"  value="<?php echo $user_info['email']; ?>"/> 
            </div>
        </div>
        
        <div class="form-group w100 mrn mln z">
            <label class="col-sm-2 pt5 control-label ar fwn">手机号码</label>
            <div class="col-sm-9 pln prn">
                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="输入手机号码" value="<?php echo $user_info['mobile']; ?>" /> 
            </div>
        </div>
        
        
    </div>
    <div class="modalbottom pt8 pb8 pl15 pr15 sizing">
        <a href="javascript:void(0);" class="modalsearch bor pl20 pr20 y" onClick="modalsearch();">提交</a>
    </div>
    </form>
</div>
<script type="text/javascript">
$(document).ready(function () {
    var end_time = $("#end_time").val();
    var myDate = new Date();
    if (!end_time) {
        end_time = myDate.toLocaleDateString();
    }
    $('#end_time').daterangepicker({singleDatePicker: true, format: 'YYYY-MM-DD', startDate: end_time}, function (start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });
});

function modalsearch(){     //ajax提交
    var url="<?php echo url('/index/mycenter/info?type=user_info'); ?>";
    jQuery.ajax({
        type: 'post',
        url: url,
        data: jQuery('#add_tag').serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);
                modalclose();
                setTimeout("location.reload()",2500);
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);
            }
        }
    });
}
</script>

<?php elseif($ac == 'useravatar'): ?><!--修改头像-->
<div class="modalwrap h350 sizing" style="width:450px;">
    <form action="" method="post" id="add_tag"  enctype="multipart/form-data">
    <h3 class="modaltitle pl15 pr15 sizing">修改头像<a href="javascript:void(0);" onClick="modalclose();" class="y fs13 iconfont">&#xe61c;</a></h3>
    <div class="publocdiv pl20 pr20 sizing modalcontnet h230">
        
        <div class="imgwrap mb15 mt20" id="imgwrap" style="margin-left:120px;">
            <input type="file" id="cover_image" class="cover" name="avatar" />
            <input type="hidden" id="cover_image_url" class="cover" name="avatar" />
            <img src="" id="coverimg" />
        </div>
        <p class="mbn c6 ac">头像大小建议为700*700像素</p>
        <p class="mbn c6 ac">支持格式：jpg,jpeg,png,gif,bmp</p>
        
    </div>
    <div class="modalbottom pt8 pb8 pl15 pr15 sizing">
        <a href="javascript:void(0);" class="modalsearch bor pl20 pr20 y" onClick="modalsearch();">提交</a>
    </div>
    </form>
</div>

<script>
jQuery(function(){
    if (jQuery('#coverimg').attr('src') == "") {    //封面为空则显示默认图片
        jQuery('#coverimg').attr('src', 'tpl_img/coverimg.png');
    }
    
    //修改头像
    
    jQuery("#cover_image").change(function () {
        var objUrl = getObjectURL(this.files[0]);
        console.log("objUrl = " + objUrl);
        if (objUrl) {
            alert('asaa');
            jQuery("#coverimg").attr("src", objUrl);
            jQuery.ajaxFileUpload({
                type: 'post',
                url: "<?php echo url('/index/mycenter/upload'); ?>",
                secureuri: false,
                fileElementId: 'cover_image',   //文件域ID
                dataType: 'json',
                success: function (data) {
                    if(data.error) {
                        jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);
                    } else {
                        jQuery("#cover_image_url").val(data.url);
                    }
                }
            });
            
        }
    });
    
});

function modalsearch(){     //ajax提交
    var url="<?php echo url('/index/mycenter/upload'); ?>";
    jQuery.ajax({
        type: 'post',
        url: url,
        data: jQuery('#add_tag').serialize(),
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);
                modalclose();
                setTimeout("location.reload()",2500);
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);
            }
        }
    });
}

//建立一個可存取到該file的url
function getObjectURL(file) {
    var url1 = null;
    if (window.createObjectURL != undefined) { // basic
        url1 = window.createObjectURL(file);
    } else if (window.URL != undefined) { // mozilla(firefox)
        url1 = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) { // webkit or chrome
        url1 = window.webkitURL.createObjectURL(file);
    }
    return url1;
}
</script>
<?php endif; ?>