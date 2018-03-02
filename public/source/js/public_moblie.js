function pawdopen(){    //密码开 闭
    if(jQuery('#password').attr('type') == 'password'){ //开
        jQuery('#password').attr('type', 'text');
        jQuery('#pawdicon').html('&#xe67c;');           //开 图标
    }else{                                              //闭
        jQuery('#password').attr('type', 'password');
        jQuery('#pawdicon').html('&#xe649;');           //闭 图标
    }
};

function navopen(){ //展开导航
    jQuery('#moblie_nav').fadeIn();
    jQuery('#nav_ul').css({left: '0'});
    //jQuery('#nav_ul').css({transform: 'translateX(-100%)'});
    jQuery('#wrap').css({transform: 'translateX(50%)'});
    jQuery('#navwp').css({transform: 'translateX(50%)'});
    jQuery('.footer_wp').css({transform: 'translateX(50%)'});
}

function navclose(){ //关闭导航
    jQuery('#moblie_nav').fadeOut();
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

function openhref(url){
    window.location.replace(url);
}