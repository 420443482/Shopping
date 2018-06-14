<?php
namespace app\home\controller;
use think\Controller;
use think\Session;
    class Index extends Controller
{
	//显示首页
    public function index()
    {

        return $this->fetch('index');
    }
}

