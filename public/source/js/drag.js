jQuery(function () {
    
    //弹窗的手动功能
    var mousex = 0, mousey = 0;
    var divLeft, divTop;
    jQuery('#modalwrap').mousedown(function(e)
    {
        var offset = jQuery(this).offset();
        divLeft = parseInt(offset.left,10);
        divTop = parseInt(offset.top,10);
        mousey = e.pageY;
        mousex = e.pageX;
        jQuery(this).bind('mousemove',dragElement);
    });

    function dragElement(event)
    {
        var left = divLeft + (event.pageX - mousex);
        var top = divTop + (event.pageY - mousey);
        jQuery(this).css(
        {
            //'top' :  top + 'px',
            //'left' : left + 'px',
            'position' : 'fixed',
            'top': '0',
            'left': '0',
            'right': '0',
            'bottom': '0',
            'margin-top' : top + 'px',
            'margin-left' : left + 'px'
        });
        return false;
    }
    jQuery(document).mouseup(function()
    {
        jQuery('#modalwrap').unbind('mousemove');
    });

});