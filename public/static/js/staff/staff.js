/*!
 * Bootstrap v3.3.6 (http://getbootstrap.com)
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under the MIT license
 */
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
    //弹出新增页面
    $.fn.modalOpen = function (options) {
        var defaults = {
            id: null,
            title: '系统窗口',
            width: "100px",
            height: "100px",
            url: '',
            shade: 0.3,
            btn: ['确认', '关闭'],
            btnclass: ['btn btn-primary', 'btn btn-danger'],
            callBack: null
        };
        var options = $.extend(defaults, options);
        var _width = top.$(window).width() > parseInt(options.width.replace('px', '')) ? options.width : top.$(window).width() + 'px';
        var _height = top.$(window).height() > parseInt(options.height.replace('px', '')) ? options.height : top.$(window).height() + 'px';
        layer.open({
            id: options.id,
            type: 2,
            shade: options.shade,
            title: options.title,
            fix: false,
            area: [_width, _height],
            content: options.url,
            btn: options.btn,
            btnclass: options.btnclass,
            yes: function () {
                options.callBack(options.id)
            }, cancel: function () {
                return true;
            }
        });
    }
    $(".pagination li a").attr('href', '#');
    $(".pagination li a").attr('name', 'btn_a');
    $(".dropdown-menu li a").attr('name', 'btn_num');
    //切换数据显示条目
    $("a[name='btn_num']").click(function(){
        num =  $(this).html();
        $('input[name="default_num"]').val(num);
        getData(1);
    });
    //按条件查询
    $("#btn_query").click(function(){
        getData(1);
    });
    //无刷新数据分页
    $("a[name='btn_a']").click(function() {
        page = $(this).html();
        if(page == '»'){
            page = parseInt($('input[name="default_page"]').val())+1;
        }else if(page == '«'){
            page = parseInt($('input[name="default_page"]').val())-1;
        }else if(isNaN(page)){
            page = 1;
        }
        $('input[name="default_page"]').val(page);
        getData(page);
    });

    //新增员工数据
    $("#btn_add").click(function(){
        $.fn.modalOpen({
            id: "Form",
            title: '新增员工',
            url: 'staff/staff_display',
            width: "750px",
            height: "550px",
            callBack: function (iframeId) {
                $.ajax({
                    url: 'login/staff_add',
                    type: "POST",
                    data: $("#layui-layer-iframe1").contents().find("#form1").serialize(),
                    success: function (data) {
                        if (data.return_status) {
                            $.fn.modalMsg(data.msg, "success");
                            $("div[class$='layer-anim']").remove();
                            $("div[class$='layui-layer-shade']").remove();
                            getData(page);
                        } else {
                            $.fn.modalMsg(data.msg, "error");
                            return false;
                        }
                    }
                });

            }
        });
    });
    //单条数据删除
    $("a[name='staff_delete']").click(function(){
        var id = $(this).attr("data-id");
        $.fn.modalConfirm("确认要删除么？", function(status) {
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
    //批量删除
    $("#btn_delete").click(function(){
        $.fn.modalConfirm("确认要批量删除么？", function(status) {
            if (status == false) return false;
            var id_array = new Array;
            $('input[name="btSelectItem"]:checked').each(function (i) {
                id_array[i] = $(this).attr("data-staff-id");
            });
            var staff_id = id_array.join(',');//数组转换以逗号隔开的字符串
            $.ajax({
                url: 'staff/staff_delete',
                type: "POST",
                data: {staff_id: staff_id},
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
    //无刷新显示数据
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

    //复选框点击全选
    $("input[name='btSelectAll']").click(function(){
        var checked = $(this).is(':checked');
        if(checked){
            $("#staff_list :checkbox").prop("checked", true);
        }else{
            $("#staff_list :checkbox").prop("checked", false);
        }
    });
});