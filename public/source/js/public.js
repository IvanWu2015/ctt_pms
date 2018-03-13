jQuery(function () {
    
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
 
function expressinf(id){    //快递添加页，联系人信息载入         收件信息
    var name = jQuery('#extslist_' + id + ' #ex_name').text();              //姓名
    var tel = jQuery('#extslist_' + id + ' #ex_tel').text();                //电话
    var address = jQuery('#extslist_' + id + ' #ex_address').text();        //地址

    jQuery('#to_name').val(name);
    jQuery('#to_tel').val(tel);
    jQuery('#from_address').val(address);
} 

function from_expressinf(id){    //快递添加页，联系人信息载入    发件信息
    var name = jQuery('#fextslist_' + id + ' #ex_name').text();              //姓名
    var tel = jQuery('#fextslist_' + id + ' #ex_tel').text();                //电话
    var address = jQuery('#fextslist_' + id + ' #ex_address').text();        //地址

    jQuery('#from_name').val(name);
    jQuery('#from_tel').val(tel);
    jQuery('#to_address').val(address);
} 

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

function pubinput(name){
    var inputId = jQuery('#' + name).val();
    if(inputId.length < 1){
        jQuery('#' + name).css({border: '1px red solid'});
        jQuery('#' + name + '_prompt').show();
    }else{
        jQuery('#' + name).css({border: '1px #dedede solid'});
        jQuery('#' + name + '_prompt').hide();
    }
}

function pubselect(name){
    var inputId = jQuery('#' + name).val();
    if(inputId == '0'){
        jQuery('#' + name).css({border: '1px red solid'});
        jQuery('#' + name + '_prompt').show();
    }else{
        jQuery('#' + name).css({border: '1px #dedede solid'});
        jQuery('#' + name + '_prompt').hide();
    }
}


function articleformsub(){      //文档表单判断
    var name = jQuery('#name').val();               //标题
    var class_id = jQuery('#class_id').val();       //分类
    var pid = 0;
    
    if (name.length < 1) {	//标题
        jQuery('#name').css({border: '1px red solid'});
        jQuery('#name_prompt').show();
        pid = 1;
    }
    
    if (class_id  == '0') {	//分类
        jQuery('#class_id').css({border: '1px red solid'});
        jQuery('#class_id_prompt').show();
        pid = 1;
    }
    
    if(pid == 1){
        jQuery('#addpr').html('<b class="cred">* </b>为必填项').show(1).delay(2000).hide(1);
        if(scroll=="off") return; jQuery('html,body').animate({scrollTop:0},400);
        event.preventDefault();
    }
}

function weburlformsub(){      //收藏表单判断
    var project_id = jQuery('#project_id').val();    //所属项目
    var name = jQuery('#name').val();                //标题
    var class_id = jQuery('#class_id').val();        //分类
    var url = jQuery('#url').val();                  //URL
    var pid = 0;
    
    if (project_id  == '0') {	//所属项目
        jQuery('#project_id').css({border: '1px red solid'});
        jQuery('#project_id_prompt').show();
        pid = 1;
    }
    
    if (name.length < 1) {	//标题
        jQuery('#name').css({border: '1px red solid'});
        jQuery('#name_prompt').show();
        pid = 1;
    }
    
    if (class_id  == '0') {	//分类
        jQuery('#class_id').css({border: '1px red solid'});
        jQuery('#class_id_prompt').show();
        pid = 1;
    }
    
    if (url == '0') {	//URL
        jQuery('#url').css({border: '1px red solid'});
        jQuery('#url_prompt').show();
        pid = 1;
    }
    
    if(pid == 1){
        jQuery('#addpr').html('<b class="cred">* </b>为必填项').show(1).delay(2000).hide(1);
        if(scroll=="off") return; jQuery('html,body').animate({scrollTop:0},400);
        event.preventDefault();
    }
}

function productformsub(){      //产品表单判断
    var name = jQuery('#name').val();                         //产品名称
    var code = jQuery('#code').val();                         //产品代号
    var po = jQuery('#po').val();                             //项目负责人
    var createdVersion = jQuery('#createdVersion').val();     //版本号
    var pid = 0;
    
    if (name.length < 1) {	//产品名称
        jQuery('#name').css({border: '1px red solid'});
        jQuery('#name_prompt').show();
        pid = 1;
    }
    
    if (code.length < 1) {	//产品代号
        jQuery('#code').css({border: '1px red solid'});
        jQuery('#code_prompt').show();
        pid = 1;
    }
    
    if (po  == '0') {	//项目负责人
        jQuery('#po').css({border: '1px red solid'});
        jQuery('#po_prompt').show();
        pid = 1;
    }
    
    if (createdVersion.length < 1) {	//版本号
        jQuery('#createdVersion').css({border: '1px red solid'});
        jQuery('#createdVersion_prompt').show();
        pid = 1;
    }
    
    if(pid == 1){
        jQuery('#addpr').html('<b class="cred">* </b>为必填项').show(1).delay(2000).hide(1);
        if(scroll=="off") return; jQuery('html,body').animate({scrollTop:0},400);
        event.preventDefault();
    }
}

function companyformsub(){      //公司表单判断
    var name = jQuery('#name').val();                         //公司名称
    var contact_name = jQuery('#contact_name').val();         //联系人
    var contact_number = jQuery('#contact_number').val();     //联系电话
    var work_address = jQuery('#work_address').val();         //办公地址
    var pid = 0;
    
    if (name.length < 1) {	//公司名称
        jQuery('#name').css({border: '1px red solid'});
        jQuery('#name_prompt').show();
        pid = 1;
    }
    
    if (contact_name.length < 1) {	//联系人
        jQuery('#contact_name').css({border: '1px red solid'});
        jQuery('#contact_name_prompt').show();
        pid = 1;
    }
    
    if (contact_number.length < 1) {	//联系电话
        jQuery('#contact_number').css({border: '1px red solid'});
        jQuery('#contact_number_prompt').show();
        pid = 1;
    }
    
    if (work_address.length < 1) {	//办公地址
        jQuery('#work_address').css({border: '1px red solid'});
        jQuery('#work_address_prompt').show();
        pid = 1;
    }
    
    if(pid == 1){
        jQuery('#addpr').html('<b class="cred">* </b>为必填项').show(1).delay(2000).hide(1);
        if(scroll=="off") return; jQuery('html,body').animate({scrollTop:0},400);
        event.preventDefault();
    }
}

function contactsformsub(){      //联系人表单判断
    var name = jQuery('#name').val();                //姓名
    var tel = jQuery('#tel').val();                  //电话
    var address = jQuery('#address').val();          //地址
    var pid = 0;
    
    if (name.length < 1) {	//姓名
        jQuery('#name').css({border: '1px red solid'});
        jQuery('#name_prompt').show();
        pid = 1;
    }
    
    if (tel.length < 1) {	//电话
        jQuery('#tel').css({border: '1px red solid'});
        jQuery('#tel_prompt').show();
        pid = 1;
    }
    
    if (address.length < 1) {	//地址
        jQuery('#address').css({border: '1px red solid'});
        jQuery('#address_prompt').show();
        pid = 1;
    }
    
    if(pid == 1){
        jQuery('#addpr').html('<b class="cred">* </b>为必填项').show(1).delay(2000).hide(1);
        if(scroll=="off") return; jQuery('html,body').animate({scrollTop:0},400);
        event.preventDefault();
    }
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

function contactsopen(){    //收件信息 添加到公司联系人
    if(jQuery('#addcontacts').is(':checked')){
        jQuery('#contactssubwp').slideDown();
    }else{
        jQuery('#contactssubwp').slideUp();
    }
}

function from_contactsopen(){    //发件信息 添加到公司联系人
    if(jQuery('#addfromcontacts').is(':checked')){
        jQuery('#contactssubwp_from').slideDown();
    }else{
        jQuery('#contactssubwp_from').slideUp();
    }
}

function loadexopen(){      //从联系人中载入
    if(jQuery('#loadwp').css('display') == 'none'){
        jQuery('#loadwp').slideDown();
    }else{
        jQuery('#loadwp').slideUp();
    }
}

function fromopen(){      //从联系人中载入
    if(jQuery('#fromwp').css('display') == 'none'){
        jQuery('#fromwp').slideDown();
    }else{
        jQuery('#fromwp').slideUp();
    }
}

function contactssub(url){     //ajax请求添加到联系人
    jQuery.ajax({
        type: 'post',
        url: url,
        data: {
            ac: 'add_contact',
            company_id: jQuery('#company_id').val(),    //所属公司
            name: jQuery('#to_name').val(),              //名称
            tel: jQuery('#to_tel').val(),                //电话
            address: jQuery('#from_address').val()     //地址
        },
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);
                jQuery('#contactssubwp').slideUp();
                jQuery('#addcontacts').attr('checked', false);
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);
            }
        }
    });
}

function from_exsubmit(url){     //ajax请求载入联系数据 发件信息
    if(jQuery('#from_keyword').val() != ""){
        jQuery.ajax({
            type: 'post',
            url: url,
            data: {
                ac:'get_contact',
                keyword: jQuery('#from_keyword').val()
            },
            dataType: 'json',
            success: function (data) {
                if(data.length > 0){
                    jQuery('#from_extsul').html('');
                    var from_extsulhtml = '';
                    for(var i=0; i < data.length; i++){
                        var from_extsul = from_extsulhtml + "<li id='fextslist_" + i + "' onClick='from_expressinf(" + i + ");'>" +
                                    "<h3><span id='ex_name'>" + data[i].name + "</span><span id='ex_tel' class='y fs12'>" + data[i].tel + "</span></h3>"+
                                    "<p id='ex_address'>" + data[i].address + "</p>"+
                                    "</li>";
                        jQuery('#from_extsul').append(from_extsul);
                    }
                }else{
                    jQuery('#from_extsul').html('<p class="ac pt10">未搜索到相关数据~</p>');
                }
            }
        });
    }
}

function exsubmit(url){     //ajax请求载入联系数据 收件信息
    if(jQuery('#keyword').val() != ""){
        jQuery.ajax({
            type: 'post',
            url: url,
            data: {
                ac:'get_contact',
                keyword: jQuery('#keyword').val()
            },
            dataType: 'json',
            success: function (data) {
                if(data.length > 0){
                    jQuery('#extsul').html('');
                    var inshtml = '';
                    for(var i=0; i < data.length; i++){
                        var inswp = inshtml + "<li id='extslist_" + i + "' onClick='expressinf(" + i + ");'>" +
                                    "<h3><span id='ex_name'>" + data[i].name + "</span><span id='ex_tel' class='y fs12'>" + data[i].tel + "</span></h3>"+
                                    "<p id='ex_address'>" + data[i].address + "</p>"+
                                    "</li>";
                        jQuery('#extsul').append(inswp);
                    }
                }else{
                    jQuery('#extsul').html('<p class="ac pt10">未搜索到相关数据~</p>');
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
            ac: 'add_contact',
            company_id: jQuery('#company_id').val(),    //所属公司
            from_name: jQuery('#from_name').val(),              //名称
            from_tel: jQuery('#from_tel').val(),                //电话
            to_address: jQuery('#to_address').val()     //地址
        },
        dataType: 'json',
        success: function (data) {
            if (data.result == 'success') {
                jQuery('#addpr').html('操作成功！').show(1).delay(2000).hide(1);
                jQuery('#contactssubwp_from').slideUp();
                jQuery('#addfromcontacts').attr('checked', false);
            }else{
                jQuery('#addpr').html(data.error).show(1).delay(2000).hide(1);
            }
        }
    });
}

jQuery(document).keydown(function (e) {
    if (e.which == 13) {     //按回车键键提交 从联系人中载入数据搜索
        if(jQuery('#loadwp').css('display') == "block"){
            exsubmit();
            from_exsubmit();
            event.preventDefault();
        }
    }
});