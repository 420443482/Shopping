<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:80:"D:\phpStudy\WWW\web\Shopping\public/../application/admin\view\index\content.html";i:1521163948;s:80:"D:\phpStudy\WWW\web\Shopping\public/../application/admin\view\public\header.html";i:1521163948;s:78:"D:\phpStudy\WWW\web\Shopping\public/../application/admin\view\public\left.html";i:1521163948;}*/ ?>
﻿﻿<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>婴儿游运</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="/static/css/index/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/index/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/index/AdminLTE.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/index/_all-skins.min.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/index/supershopui.common.min.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/example.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/sweet-alert.css" />
    <link rel="stylesheet" type="text/css" href="/static/js/skin/style.css" />
    <link rel="stylesheet" type="text/css" href="/static/js/skin/layer.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/goods/bootstrap-switch.css" />

    <style type="text/css">
        html {
            overflow: hidden;
        }
    </style>

    <!--<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>-->
    <!--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>-->
</head>

<body class="hold-transition skin-blue sidebar-mini fixed">
<!--<div class="wrapper">-->
    <header class="main-header">
        <a href="" class="logo">
            <span class="logo-mini"><b>S</b>UI</span>
            <span class="logo-lg"><b>婴儿游运</b></span>
        </a>

        <nav class="navbar navbar-static-top" role="navigation">
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#" id="workbench">工作台 <span class="sr-only">(current)</span></a></li>
                    <li><a href="#" id="management">管理中心</a></li>
                    <li><a href="#" >档案中心</a></li>
                    <li><a href="#">资格审查</a></li>
                    <li><a href="#" id="setting">基础设置</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">会员 <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                            <li class="divider"></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                </ul>

            </div>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">4</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">你有4条提醒</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <div class="pull-left">
                                                <img src="/static/img/girl1.png" class="img-circle" alt="User Image">
                                            </div>
                                            <h4>
                                                新用户注册
                                                <small><i class="fa fa-clock-o"></i> 现在</small>
                                            </h4>
                                            <p>新用户注册!</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">查看所有提醒</a></li>
                        </ul>
                    </li>
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">你有10条消息</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 新用户注册!
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">查看所有</a></li>
                        </ul>
                    </li>
                    <li class="dropdown tasks-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger">9</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">您有9条消息</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <h3>
                                                婴儿游运第一版发布啦
                                                <small class="pull-right">20%</small>
                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="sr-only">20% 完成</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#">查看所有</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo \think\Request::instance()->session('staff_portrait'); ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo \think\Request::instance()->session('staff_account'); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="<?php echo \think\Request::instance()->session('staff_portrait'); ?>" class="img-circle" alt="User Image">
                                <p>
                                    <?php echo \think\Request::instance()->session('staff_account'); ?>
                                    <small><?php echo \think\Request::instance()->session('date'); ?>注册</small>
                                </p>
                            </li>
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">个人信息</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">设置</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">主题</a>
                                    </div>
                                </div>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">个人中心</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo url('loginOut'); ?>" class="btn btn-default btn-flat">退出</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

<script type="text/javascript" src="/static/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/static/js/supershopui.common.js"></script>
<script type="text/javascript" src="/static/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/static/js/sweet-alert.js"></script>
<script type="text/javascript" src="/static/js/goods/bootstrap-switch.js"></script>


﻿<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo \think\Request::instance()->session('staff_portrait'); ?>" class="img-circle" alt="用户头像">
            </div>
            <div class="pull-left info">
                <p style="margin-top: 0;"><?php echo \think\Request::instance()->session('staff_account'); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
            </div>
        </div>
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                            <button style="margin-top:0; margin-right:0;" type="submit" name="search" id="search-btn" class="btn btn-flat">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
            </div>
        </form>
        <ul class="sidebar-menu"></ul>
    </section>
</aside>
<script src="/static/js/left/left.js" type="text/javascript"></script>
</body>


    <div class="content-wrapper" id="content-wrapper" style="background-color: #FFF">
        <div class="page-content-body " id="tab-page-content">
            <section class="content">
            <div class="row pre-scrollable" style=" overflow:scroll; width:100%; ">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>150</h3>
                            <p> 新订单数</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">更多 <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>53<sup style="font-size: 20px">%</sup>
                            </h3>
                            <p>营收增长</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">更多 <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>44</h3>
                            <p>用户注册量</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">更多<i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>65</h3>
                            <p>独立访客</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">更多 <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
        </div>
    </section>
    </div>
    </div>
