jQuery(function () {

    jQuery('#sofpul li:eq(0)').find('#sofplisp').css({background:'#00b9f5'});
    jQuery('#sofpul li:eq(1)').find('#sofplisp').css({background:'#ff2945'});
    jQuery('#sofpul li:eq(2)').find('#sofplisp').css({background:'#007eb6'});
    jQuery('#sofpul li:eq(3)').find('#sofplisp').css({background:'#ffd100'});
    jQuery('#sofpul li:eq(4)').find('#sofplisp').css({background:'#6bdb2d'});
    jQuery('#sofpul li:eq(5)').find('#sofplisp').css({background:'#7167db'});
    jQuery('#sofpul li:eq(6)').find('#sofplisp').css({background:'#ff8600'});
    jQuery('#sofpul li:eq(7)').find('#sofplisp').css({background:'#00c0d3'});
    jQuery('#sofpul li:eq(6)').find('#sofplisp').css({background:'#5cb85c'});
    jQuery('#sofpul li:eq(7)').find('#sofplisp').css({background:'#5bc0de'});
    
});

function shownav(){ 
    if(jQuery('#left_nav').css('left') == '0px'){ 
        jQuery('#left_nav').animate({left:'-260px'},500); 
        jQuery('.rightcon').animate({paddingLeft:'0px'},500); 
        jQuery('#showa').html('&#xe624;'); 
    }else{ 
        jQuery('#left_nav').animate({left:'0px'},500); 
        jQuery('.rightcon').animate({paddingLeft:'271px'},500); 
        jQuery('#showa').html('&#xe606;'); 
    } 
} 