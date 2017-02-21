jQuery(function(){
	
	
	jQuery('.coninformation li:even').addClass('sizing conli000');	//单数
	
	jQuery('.coninformation li').each(function(index){	//删除提示
		jQuery('.coninformation li').eq(index).find('.bdelete').bind('click',function(){
			$('.coninformation li').find('.deprompt').hide();
			$('.coninformation li').eq(index).find('.deprompt').show();
		});
	});
	jQuery('.dele_no').bind('click',function(){	//关闭删除提示
		jQuery('.deprompt').hide();
	});
	
	jQuery('.coninformation li').each(function(index){
		jQuery('.coninformation li').eq(index).find('.bid').text(index+1);
	});
    
	jQuery('.coninformation li').each(function(index){	//产品优点
		jQuery('.coninformation li').eq(index).find('.radvantage').hover(function(){
			jQuery('.coninformation li').eq(index).find('.radvantage_details').show();
		},function(){
			jQuery('.coninformation li').eq(index).find('.radvantage_details').hide();
		});
	});
	
	jQuery('.coninformation li').each(function(index){	//产品缺点
		jQuery('.coninformation li').eq(index).find('.rshortcoming').hover(function(){
			jQuery('.coninformation li').eq(index).find('.rshortcoming_details').show();
		},function(){
			jQuery('.coninformation li').eq(index).find('.rshortcoming_details').hide();
		});
	});
	
	jQuery('.coninformation li').each(function(index){	//个人简介
		jQuery('.coninformation li').eq(index).find('.uintroduction').hover(function(){
			jQuery('.coninformation li').eq(index).find('.uintroduction_details').show();
		},function(){
			jQuery('.coninformation li').eq(index).find('.uintroduction_details').hide();
		});
	});
	
	jQuery("#checkAll01").click(function() {
		jQuery('#checkbox01').attr("checked",this.checked); 
	});
	jQuery("#checkAll02").click(function() {
		jQuery('#checkbox02').attr("checked",this.checked); 
	});
	jQuery("#checkAll03").click(function() {
		jQuery('#checkbox03').attr("checked",this.checked); 
	});
	jQuery("#checkAll04").click(function() {
		jQuery('#checkbox04').attr("checked",this.checked); 
	});
	
});
