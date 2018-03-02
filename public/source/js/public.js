jQuery(function () {
    jQuery('#name').keyup(function () {	//任务名称
        if (jQuery('#name').val().length < 1) {
            jQuery('#name').css({border: '1px red solid'});
        } else {
            jQuery('#name').css({border: '1px #dedede solid'});
        }
    });
    
    jQuery('#estimate').keyup(function () {	//预计工时
        if (jQuery('#estimate').val().length < 1) {
            jQuery('#estimate').css({border: '1px red solid'});
        } else {
            jQuery('#estimate').css({border: '1px #dedede solid'});
        }
    });

    jQuery('#editor').keyup(function () {	//任务名称
        var arr = [];
        arr.push(UE.getEditor('editor').getContent());
        var arrjoin = arr.join("\n");
        if (arrjoin.length < 1) {
            jQuery('#editor').css({border: '1px red solid'});
        } else {
            jQuery('#editor').css({border: '1px #dedede solid'});
        }
    });
    
});

function loadWindow(url) {
    if (arguments[1]) {
        jQuery.ajax({
            type: 'POST',
            url: url,
            data: {jq_ajax: 1},
            dataType: 'json',
            success: function (data) {
                if (data.result == 'failed') {
                    alert(data.error);
                } else {
                    jQuery('#applica').show();
                    jQuery.get(url,
                            function (data) {
                                $("#applica").html(data);
                            }, "json");
                }
                return;
            }
        });
    } else {
        jQuery('#applica').show();
        jQuery.get(url,
                function (data) {
                    $("#applica").html(data);
                }, "json");
        return;
    }
}


/*function loadWindow(url) {  //弹窗标签
 jQuery('#applica').show();
 jQuery.get(url,
 function(data){
 $("#applica").html(data);
 },"json");
 return;
 }*/
 
function inputopen(name){
    if(jQuery('#' + name).css('display') == 'none'){
        jQuery('#' + name).slideDown();
        jQuery('#inputopen').html('收起更多 &#xe650;');
    }else{
        jQuery('#' + name).slideUp();
        jQuery('#inputopen').html('展开更多 &#xe688;');
    }
}
 
jQuery(document).keydown(function (e) {
    if (e.which == 27) {     //按Esc键关闭弹窗
        modalclose();
    }
});
function modalclose() {
    jQuery('#applica').hide().html('');
}

function formsubif() {
    var name = jQuery('#name').val();     //任务名称
    var estimate = jQuery('#estimate').val();     //预计工时

    var arr = [];
    arr.push(UE.getEditor('editor').getContent());
    var arrjoin = arr.join("\n");

    if (name.length < 1) {	//单用户购买最大值
        jQuery('#name').css({border: '1px red solid'});
        jQuery('#prompt').show(1).delay(2000).hide(1);
        event.preventDefault();
    }
    if (estimate.length < 1) {	//单用户购买最大值
        jQuery('#estimate').css({border: '1px red solid'});
        jQuery('#prompt').show(1).delay(2000).hide(1);
        event.preventDefault();
    }
    if (arrjoin.length < 1) {	//单用户购买最大值
        jQuery('#editor').css({border: '1px red solid'});
        jQuery('#prompt').show(1).delay(2000).hide(1);
        event.preventDefault();
    }
}

function casearch(val){
    
    if(val == 1){
        jQuery('#casearch_1').addClass('filenavattr');
        jQuery('#casearch_2').removeClass('filenavattr');
        jQuery('#casearch_3').removeClass('filenavattr');
        jQuery('#fileleftNav').show();
        jQuery('#articlewp').hide();
        jQuery('#iuser').hide();
    } else if(val == 2){
        jQuery('#casearch_1').removeClass('filenavattr');
        jQuery('#casearch_2').addClass('filenavattr');
        jQuery('#casearch_3').removeClass('filenavattr');
        jQuery('#fileleftNav').hide();
        jQuery('#articlewp').show();
        jQuery('#iuser').hide();
    } else if(val == 3){
        jQuery('#casearch_1').removeClass('filenavattr');
        jQuery('#casearch_2').removeClass('filenavattr');
        jQuery('#casearch_3').addClass('filenavattr');
        jQuery('#fileleftNav').hide();
        jQuery('#articlewp').hide();
        jQuery('#iuser').show();
    }
    
}

function keyscreenopen(){   //搜索页筛选
    if(jQuery('#keyscreen').css('display') == 'none'){
        jQuery('#keyscreen').slideDown();
        jQuery('#keyscreenopen').html('&#xe650; 收起筛选');
    }else{
        jQuery('#keyscreen').slideUp();
        jQuery('#keyscreenopen').html('&#xe688; 展开筛选');
    }
}