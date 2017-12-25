<?php
namespace app\admin\controller;
use app\common\model\User;
use think\Controller;
class Dashboard extends Controller
{
	//显示首页
    public function index()
    {
        return $this->fetch('dashboard');
    }
	
}

