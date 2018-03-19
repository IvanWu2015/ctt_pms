jQuery.fn.limit=function(){
    var self = jQuery("span[limit]"); 
    self.each(function(){ 
        var objString = jQuery(this).text(); 
        var objLength = jQuery(this).text().length; 
        var num = jQuery(this).attr("limit"); 
        if(objLength > num){ 
            jQuery(this).attr("title",objString); 
            objString = jQuery(this).html(objString.substring(0,num)); 
        } 
    }) 
} 
jQuery(function(){ 
    jQuery(document.body).limit(); 
})