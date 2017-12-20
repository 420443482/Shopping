<?php
namespace app\admin\controller;
use app\common\model\User;
use think\Controller;
class Index extends Controller
{
	//显示首页
    public function index()
    {
        return $this->fetch('content');
    }
	
	public function loginOut(){
		 return $this->fetch('login/login');
	}
	
	
}

