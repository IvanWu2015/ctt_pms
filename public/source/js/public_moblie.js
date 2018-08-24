function pawdopen(){    //密码开 闭
    if(jQuery('#password').attr('type') == 'password'){ //开
        jQuery('#password').attr('type', 'text');
        jQuery('#pawdicon').html('&#xe67c;');           //开 图标
    }else{                                              //闭
        jQuery('#password').attr('type', 'password');
        jQuery('#pawdicon').html('&#xe649;');           //闭 图标
    }
};

function inputopen(name){
    if(jQuery('#' + name).css('display') == 'none'){
        jQuery('#' + name).slideDown();
        jQuery('#inputopen').html('收起更多 &#xe650;');
    }else{
        jQuery('#' + name).slideUp();
        jQuery('#inputopen').html('展开更多 &#xe688;');
    }
}

function navopen(){ //展开导航
    jQuery('#moblieNav').fadeIn();
    jQuery('#nav_ul').css({left: '0'});
    //jQuery('#nav_ul').css({transform: 'translateX(-100%)'});
    jQuery('#wrap').css({transform: 'translateX(50%)'});
    jQuery('#navwp').css({transform: 'translateX(50%)'});
    jQuery('.footer_wp').css({transform: 'translateX(50%)'});
    
}

function navclose(){ //关闭导航
    jQuery('#moblieNav').fadeOut();
    //jQuery('#nav_ul').css({transform: 'translateX(0%)'});
    jQuery('#nav_ul').css({left: '-50%'});
    jQuery('#wrap').css({transform: 'translateX(0)'});
    jQuery('#navwp').css({transform: 'translateX(0)'});
    jQuery('.footer_wp').css({transform: 'translateX(0)'});
}

function searchopen(name){  //搜索打开、关闭
    if(name == 'show'){
        jQuery('#searchwp').fadeIn(500);
        jQuery('#searchwp_div').animate({top: '0px'}, 500);
    }else if(name == 'hide'){
        jQuery('#searchwp').fadeOut(700);
        jQuery('#searchwp_div').animate({top: '-180px'}, 500);
        jQuery('#keyword').val('');
    }
}

function contactopen(name){
    if(jQuery('#' + name).css('display') == 'none'){
        jQuery('#' + name).show();
        jQuery('#' + name + '_icon').css({transform: 'rotate(90deg)'});
    }else{
        jQuery('#' + name).hide();
        jQuery('#' + name + '_icon').css({transform: 'rotate(0deg)'});
    }
}

function articleopen(){ //展开文档导航
    jQuery('#article_nav').fadeIn();
    jQuery('#article_ul').css({left: '-70%'});
    jQuery('#wrap').css({transform: 'translateX(70%)'});
    jQuery('#navwp').css({transform: 'translateX(70%)'});
    jQuery('.footer_wp').css({transform: 'translateX(70%)'});
}

function articleclose(){ //关闭文档导航
    jQuery('#article_nav').fadeOut();
    jQuery('#article_ul').css({left: '-70%'});
    jQuery('#wrap').css({transform: 'translateX(0)'});
    jQuery('#navwp').css({transform: 'translateX(0)'});
    jQuery('.footer_wp').css({transform: 'translateX(0)'});
}

function flienavshow(id) {
    if (jQuery('#flienavclasswp_' + id).css('display') == 'none') {
        jQuery('#flienavicon_' + id).css({transform: 'rotate(90deg)'});
        jQuery('#flienavclasswp_' + id).slideDown();
    } else {
        jQuery('#flienavicon_' + id).css({transform: 'rotate(0deg)'});
        jQuery('#flienavclasswp_' + id).slideUp();
    }

}

function openhref(url){
    window.location.replace(url);
}