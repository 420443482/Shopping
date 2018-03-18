<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"/Applications/MAMP/htdocs/Shopping/public/../application/admin/view/login/login.html";i:1521260585;}*/ ?>
﻿<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
    <title>婴儿游运</title>
    <link rel="stylesheet" type="text/css" href="/static/css/login/login.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/login/layout.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/example.css" />
    <script type="text/javascript" src="/static/js/sweet-alert.js"></script>
    <link rel="stylesheet" type="text/css" href="/static/css/sweet-alert.css" />
    <style>
        .ibar {
            display: none;
        }
    </style>
</head>

<body class="login-bg">
    <div class="main ">
        <!--登录-->
        <div class="login-dom login-max">
            <div class="logo text-center">
                <a href="#">
                    <img src="/static/img/logo.png" width="180px" height="180px" />
                </a>
            </div>
            <div class="login container " id="login">
                <p class="text-big text-center logo-color">
                    同一个账号，包含一切
                </p>

                <p class="text-center margin-small-top logo-color text-small">
                    婴儿游运
                </p>
                <form class="login-form"  method="post" id="user_from" autocomplete="off">
                    <input type="hidden" name="__token__" value="<?php echo \think\Request::instance()->token(); ?>" />
                    <div class="login-box border text-small" id="box">
                        <div class="name border-bottom">
                            <input type="text" placeholder="编号/账号" id="staff_account" name="staff_account" datatype="*" nullmsg="请填写帐号信息" />
                        </div>
                        <div class="pwd">
                            <input type="password" placeholder="密码" datatype="*" id="staff_password" name="staff_password" nullmsg="请填写帐号密码" />
                        </div>
                    </div>
                    <input type="button" class="btn text-center login-btn" value="立即登录" />
                </form>
                <div class="forget">

                    <a href="#" class="forget-pwd text-small fl"> 忘记登录密码？</a><a href="/admin/login/register" class="forget-new text-small fr" id="forget-new">注册账号</a>
                </div>
            </div>
        </div>
        <div class="footer text-center text-small ie">
            Copyright 2017 版权所有 ©yuchen 2017-2017      <a href="#" target="_blank">湘ICP备16024545号-1</a>
            <span class="margin-left margin-right">|</span>
        </div>
        <div class="popupDom">
            <div class="popup text-default">
            </div>
        </div>
    </div>
</body>

<script type="text/javascript" src="/static/js/jquery.min.js"></script>

<script type="text/javascript">
    $(function(){
        $('.btn').click(function(){
            $.ajax({
                type: "POST",
                url: "/admin/login/staff_dl",
                data: $('#user_from').serialize(),
                dataType: "json",
                success: function(data){
                   if(data.return_status){
                       window.location.href="/admin/index";
                   }else{
                       swal("帐号或密码错误，请重新输入");

                   }
                }
            });
        });
    });
</script>
</html>