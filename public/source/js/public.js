jQuery(function(){
    jQuery('#name').keyup(function(){	//任务名称
		if(jQuery('#name').val().length < 1){
			jQuery('#name').css({border:'1px red solid'});
		}else{
			jQuery('#name').css({border:'1px #dedede solid'});
		}
	});
    
    jQuery('#estimate').keyup(function(){	//预计工时
		if(jQuery('#estimate').val().length < 1){
			jQuery('#estimate').css({border:'1px red solid'});
		}else{
			jQuery('#estimate').css({border:'1px #dedede solid'});
		}
	});
    
    jQuery('#editor').keyup(function(){	//任务名称
        var arr = [];
        arr.push(UE.getEditor('editor').getContent());
        var arrjoin=arr.join("\n");
		if(arrjoin.length < 1){
			jQuery('#editor').css({border:'1px red solid'});
		}else{
			jQuery('#editor').css({border:'1px #dedede solid'});
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
                if (data.error) {
                    alert(data.error);
                } else {
                    jQuery('#applica').show();
                    jQuery.get(url,
                      function(data){
                        $("#applica").html(data);
                      },"json");
                    return;
                }
            }
        });
    } else {
        jQuery('#applica').show();
        jQuery.get(url,
          function(data){
            $("#applica").html(data);
          },"json");
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
jQuery(document).keydown(function(e){
    if(e.which == 27) {     //按Esc键关闭弹窗
        modalclose();
    }
});
function modalclose(){
    jQuery('#applica').hide().html('');
}

function formsubif(){
    var name=jQuery('#name').val();     //任务名称
    var estimate=jQuery('#estimate').val();     //预计工时
    
    var arr = [];
	arr.push(UE.getEditor('editor').getContent());
	var arrjoin=arr.join("\n");
    
    if(name.length < 1){	//单用户购买最大值
		jQuery('#name').css({border:'1px red solid'});
		jQuery('#prompt').show(1).delay(2000).hide(1);
		event.preventDefault();
	}
    if(estimate.length < 1){	//单用户购买最大值
		jQuery('#estimate').css({border:'1px red solid'});
		jQuery('#prompt').show(1).delay(2000).hide(1);
		event.preventDefault();
	}
    if(arrjoin.length < 1){	//单用户购买最大值
		jQuery('#editor').css({border:'1px red solid'});
		jQuery('#prompt').show(1).delay(2000).hide(1);
		event.preventDefault();
	}
}