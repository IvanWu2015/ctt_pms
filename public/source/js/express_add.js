function loadexopen(){      //从联系人中载入
    if(jQuery('#loadwp').css('display') == 'none'){
        jQuery('#loadwp').slideDown();  //显示
    }else{
        jQuery('#loadwp').slideUp();  //隐藏
    }
}

function contactssub(url){     //ajax请求添加到联系人
    jQuery.ajax({
        type: 'post',
        url: url,
        data: {
            //请求传输的数据
            ac: 'add_contact',
            company_id: jQuery('#company_id').val(),     //所属公司
            name: jQuery('#to_name').val(),              //名称
            tel: jQuery('#to_tel').val(),                //电话
            address: jQuery('#from_address').val()       //地址
        },
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);      //提示，2秒后自动关闭
                jQuery('#contactssubwp').slideUp();                                   //关闭窗口
                jQuery('#addcontacts').attr('checked', false);                        //取消选择
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);        //提示报错信息，2秒后自动关闭
            }
        }
    });
}

function exsubmit(url){     //ajax请求载入联系数据 收件信息
    if(jQuery('#keyword').val() != ""){
        jQuery.ajax({
            type: 'post',
            url: url,
            data: {
                //请求传输的数据
                ac:'get_contact',
                keyword: jQuery('#keyword').val()
            },
            dataType: 'json',
            success: function (data) {
                if(data.length > 0){
                    jQuery('#extsul').html('');
                    var inshtml = '';
                    for(var i=0; i < data.length; i++){
                        //循环显示列表
                        var inswp = inshtml + "<li id='extslist_" + i + "' onClick='expressinf(" + i + ");'>" +
                                    "<h3><span id='ex_name'>" + data[i].name + "</span><span id='ex_tel' class='y fs12'>" + data[i].tel + "</span></h3>"+
                                    "<p id='ex_address'>" + data[i].address + "</p>"+
                                    "</li>";
                        jQuery('#extsul').append(inswp);     //渲染到版块里
                    }
                }else{
                    jQuery('#extsul').html('<p class="ac pt10">未搜索到相关数据~</p>');
                }
            }
        });
    }
}

function from_exsubmit(url){     //ajax请求载入联系数据 发件信息
    if(jQuery('#from_keyword').val() != ""){
        jQuery.ajax({
            type: 'post',
            url: url,
            data: {
                //请求传输的数据
                ac:'get_contact',
                keyword: jQuery('#from_keyword').val()
            },
            dataType: 'json',
            success: function (data) {
                if(data.length > 0){
                    jQuery('#from_extsul').html('');
                    var from_extsulhtml = '';
                    for(var i=0; i < data.length; i++){
                        //循环显示列表
                        var from_extsul = from_extsulhtml + "<li id='fextslist_" + i + "' onClick='from_expressinf(" + i + ");'>" +
                                    "<h3><span id='ex_name'>" + data[i].name + "</span><span id='ex_tel' class='y fs12'>" + data[i].tel + "</span></h3>"+
                                    "<p id='ex_address'>" + data[i].address + "</p>"+
                                    "</li>";
                        jQuery('#from_extsul').append(from_extsul);     //渲染到版块里
                    }
                }else{
                    jQuery('#from_extsul').html('<p class="ac pt10">未搜索到相关数据~</p>');
                }
            }
        });
    }
}

function fromcontactssub(url){     //发件信息  ajax请求添加到联系人
    jQuery.ajax({
        type: 'post',
        url: url,
        data: {
            //请求传输的数据
            ac: 'add_contact',
            company_id: jQuery('#company_id').val(),    //所属公司
            name: jQuery('#from_name').val(),      //名称
            tel: jQuery('#from_tel').val(),        //电话
            address: jQuery('#to_address').val()     //地址
        },
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);     //提示，2秒后自动关闭
                jQuery('#contactssubwp_from').slideUp();                             //关闭窗口
                jQuery('#addfromcontacts').attr('checked', false);                   //取消选择
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);       //提示报错信息，2秒后自动关闭
            }
        }
    });
}

function otherdesc(name){   //物品说明
    if(name == '其他'){
        jQuery('#other_desc').show();
    }else{
        jQuery('#other_desc').hide();
        jQuery('#other_desc').val('');  //清空“其他”输入框的值
        jQuery('#other').val('');   //清空“其他”单选按钮的值
    }
}

function otherinput(){  //物品说明 其他 输入框架正在输入
    var other_desc = jQuery('#other_desc').val();   //获取正在输入的内容
    jQuery('#other').val(other_desc);   //将获取正在输入的内容传到 “其他”单选按钮里
}

function fromopen(){      //从联系人中载入
    if(jQuery('#fromwp').css('display') == 'none'){
        jQuery('#fromwp').slideDown();
    }else{
        jQuery('#fromwp').slideUp();
    }
}

function from_contactsopen(){    //发件信息 添加到公司联系人
    if(jQuery('#addfromcontacts').is(':checked')){
        jQuery('#contactssubwp_from').slideDown();  //显示
    }else{
        jQuery('#contactssubwp_from').slideUp();  //隐藏
    }
}