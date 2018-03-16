$(function(){
    var num;
    var page = 1;
    //确认是否提交弹出提示框
    $.fn.modalConfirm = function (content, callBack) {
        layer.confirm(content, {
            icon: "fa-exclamation-circle",
            title: "系统提示",
            btn: ['确认', '取消'],
            btnclass: ['btn btn-primary', 'btn btn-danger'],
        }, function () {
            callBack(true);
        }, function () {
            callBack(false)
        });
    }
    //提示框显示
    $.fn.modalMsg = function (content, type) {
        var iconType = 0;
        if (type != undefined) {
            var icon = "";
            if (type == 'success') {
                icon = "fa-check-circle";
                iconType = 1;
            }
            if (type == 'error') {
                icon = "fa-times-circle";
                iconType = 2;
            }
            if (type == 'warning') {
                icon = "fa-exclamation-circle";
                iconType = 3;
            }
            top.layer.msg(content, { icon: iconType, time: 1000, shift: 5 });
            top.$(".layui-layer-msg").find('i.' + iconType).parents('.layui-layer-msg').addClass('layui-layer-msg-' + type);
        } else {
            top.layer.msg(content);
        }
    }
    $(".pagination li a").attr('href', '#');
    $(".pagination li a").attr('name', 'btn_a');
    $(".dropdown-menu li a").attr('name', 'btn_num')
    //切换显示条目
    $("a[name='btn_num']").click(function(){
        num =  $(this).html();
        $('input[name="default_num"]').val(num);
        getData(1);
    });
    //无刷新数据分页
    $("a[name='btn_a']").click(function() {
        page = $(this).html();
        if(page == '»'){
            page = parseInt($('input[name="default_page"]').val())+1;
        }else if(page == '«'){
            page = parseInt($('input[name="default_page"]').val())-1;
        }
        $('input[name="default_page"]').val(page);
        getData(page)
    });

    //数据删除
    $("a[name='staff_delete']").click(function(){
        var id = $(this).attr("data-id");
        $.fn.modalConfirm("确认要提交么？", function(status) {
            if(status == false) return false;
            $("div[class$='layer-anim']").hide();
            $("div[class$='layui-layer-shade']").hide();
            $.ajax({
                url: 'staff/staff_delete',
                type: "POST",
                data: {staff_id: id},
                success: function (data) {
                    if (data.return_status) {
                        $.fn.modalMsg(data.msg, "success");
                    } else {
                        $.fn.modalMsg(data.msg, "error");
                    }
                },
                complete: function () {
                    if ($('input[name="default_page"]').val() != '') {
                        page = $('input[name="default_page"]').val();
                    }
                    getData(page);
                }
            });
        });
    });
    $("a[name='staff_edit']").click(function(){
        var staff_id = $(this).attr("edit-id");
        $.fn.modalOpen({
            id: "Form",
            title: '编辑员工信息',
            url: 'staff/staff_display?staff_id='+staff_id,
            width: "750px",
            height: "550px",
            callBack: function (iframeId) {
                $.ajax({
                    url: 'login/staff_add',
                    type: "POST",
                    data:$.param({'staff_id':staff_id}) + '&' + $("iframe[id^='layui-layer-iframe']").contents().find("#form1").serialize(),
                    success: function (data) {
                        if (data.return_status) {
                            $.fn.modalMsg(data.msg, "success");
                            $("div[class$='layer-anim']").remove();
                            $("div[class$='layui-layer-shade']").remove();
                            if ($('input[name="default_page"]').val() != '') {
                                page = $('input[name="default_page"]').val();
                            }
                            getData(page);
                        } else {
                            $.fn.modalMsg(data.msg, "error");
                            return false;
                        }
                    },
                });
            }
        });

    });
    function getData(page) {
        num =  $('input[name="default_num"]').val();
        $.ajax({
            url:'staff/staff_search',
            type:"POST",
            data:$.param({'page':page,'num':num}) + '&' + $('#formSearch').serialize(),
            success: function(data){
                $("#staff_list").html(data);
            },
        });
    }
});
//复选框点击全选
$("input[name='btSelectAll']").click(function(){
    var checked = $(this).is(':checked');
    if(checked){
        $("#staff_list :checkbox").prop("checked", true);
    }else{
        $("#staff_list :checkbox").prop("checked", false);
    }
});

