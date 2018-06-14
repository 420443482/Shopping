<?php
namespace app\home\controller;
use think\Controller;
use think\Session;
class Register extends Controller
{
	//显示注册页
    public function index()
    {
        return $this->fetch('index');
    }
}

