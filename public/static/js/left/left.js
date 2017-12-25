/*!
 * Bootstrap v3.3.6 (http://getbootstrap.com)
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under the MIT license
 */
$(function () {
    $("#workbench").click(function () {
        $(".sidebar-menu").empty();

        App.fixIframeCotent();
        //菜单格式
        var menus = [
            { id: "10010", text: "我的工作台", isHeader: true },
            {
                id: "10001", text: "一级菜单", isOpen: true, icon: "icon-diamond",  children: [
                { id: "10002", text: "二级菜单1", url: "dashboard.html", targetType: "ajax", icon: "icon-diamond" },
                { id: "10017", text: "二级菜单2", url: "../admin/ajax_content2.html", targetType: "ajax", icon: "icon-diamond" }

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
                id: "10001", text: "一级菜dfafa", isOpen: true, icon: "icon-diamond",  children: [
                { id: "10002", text: "二级菜单1", url: "../admin/dashboard.html", targetType: "ajax", icon: "icon-diamond" },
                { id: "10017", text: "二级菜单2", url: "../admin/ajax_content2.html", targetType: "ajax", icon: "icon-diamond" }

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
});