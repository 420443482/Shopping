$("a[name='admin_login']").click(function () {
    var account = $(this).attr('path');
    $.ajax({
        url: '/admin/login/admin_login_home',
        type: 'POST',
        // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
        data:$.param({account:account}),
        success:function(data){
            layer.msg(data.msg);
            if(data.code == 1){
                window.open(data.url,'_blank');//PC网页式跳转
            }
        }
    });
});