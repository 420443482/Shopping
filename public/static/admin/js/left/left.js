/*!
 * Bootstrap v3.3.6 (http://getbootstrap.com)
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under the MIT license
 */
$(function () {
    if(!window.name){
        $(".sidebar-menu").empty();
        App.fixIframeCotent();

        //菜单格式
        var menus = [
            { id: "10010", text: "我的工作台", isHeader: true },
            {
                id: "10001", text: "统计中心", url: "./dashboard", targetType: "ajax", icon: "fa fa-bar-chart-o",
                // { id: "10002", text: "二级菜单1", url: "dashboard.html", targetType: "ajax", icon: "icon-diamond" },
                // { id: "10017", text: "二级菜单2", url: "../admin/ajax_content2.html", targetType: "ajax", icon: "icon-diamond" }

            },
            {
                id: "10002", text: "人员中心",url: "./staff", targetType: "ajax", icon: "fa fa-child",
            },
            {
                id: "10003", text: "商品中心", isOpen: true, icon: "fa fa-gift",  children: [
                    { id: "10011", text: "商品分类", url: "./goods/goods_class", targetType: "ajax", icon: "icon-diamond" },
                    { id: "10012", text: "商品管理", url: "./goods", targetType: "ajax", icon: "icon-diamond" }

                ]
            }

        ];
        $('.sidebar-menu').sidebarMenu({ data: menus, param: { strUser: 'admin' } });
        //处理菜单ajax方式加载
        App.handleSidebarAjaxContent();
    }
    $("#workbench").click(function () {
        $(".sidebar-menu").empty();
        App.fixIframeCotent();

        //菜单格式
        var menus = [
            { id: "10010", text: "我的工作台", isHeader: true },
            {
                id: "10001", text: "统计中心",url: "./dashboard", targetType: "ajax", icon: "fa fa-bar-chart-o",
            },
            {
                id: "10002", text: "人员中心",url: "./staff", targetType: "ajax", icon: "fa fa-child",

            },
            {
                id: "10003", text: "商品中心", isOpen: true, icon: "fa fa-gift",  children: [
                    { id: "10011", text: "商品分类", url: "./goods/goods_class", targetType: "ajax", icon: "icon-diamond" },
                    { id: "10012", text: "商品管理", url: "./goods", targetType: "ajax", icon: "icon-diamond" }

                ]
            }
        ];
        $('.sidebar-menu').sidebarMenu({ data: menus, param: { strUser: 'admin' } });
        //处理菜单ajax方式加载
        App.handleSidebarAjaxContent();
    })
    $("#management").click(function () {
        $(".sidebar-menu").empty();
        App.fixIframeCotent();
        //菜单格式
        var menus = [
            { id: "10010", text: "管理中心", isHeader: true },
            {
                id: "10001", text: "人员设置", isOpen: true, icon: "icon-diamond",  children: [
                { id: "10002", text: "信息设置", url: "../admin/dashboard.html", targetType: "ajax", icon: "icon-diamond" },
                { id: "10017", text: "人员调度", url: "../ admin/ajax_content2.html", targetType: "ajax", icon: "icon-diamond" }

            ]
            }
        ];
        $('.sidebar-menu').sidebarMenu({ data: menus, param: { strUser: 'admin' } });
        //处理菜单ajax方式加载
        App.handleSidebarAjaxContent();
    })
    $("#setting").click(function () {
        $(".sidebar-menu").empty();
        App.fixIframeCotent();
        //菜单格式
        var menus = [
            { id: "10010", text: "基础设置", isHeader: true },
            {
                id: "10001", text: "一级菜dfafa", isOpen: true, icon: "icon-diamond"
            }
        ];
        $('.sidebar-menu').sidebarMenu({ data: menus, param: { strUser: 'admin' } });
        //处理菜单ajax方式加载
        App.handleSidebarAjaxContent();
    })
    $(".treeview").click(function(){
        $(".treeview").removeClass("active");
        if($(this).attr("data-level") == 1){
            $(".treeview").removeAttr("id");
            $(this).attr("id","mos");
        }
         $(this).addClass("active");
        $(this).find("#mos").addClass("active");
    });
});