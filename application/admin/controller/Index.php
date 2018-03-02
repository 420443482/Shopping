<?php
namespace app\admin\controller;
use app\common\model\User;
use think\Controller;
use think\Session;
    class Index extends Base
{
	//显示首页
    public function index()
    {
        return $this->fetch('content');
    }
	
	public function loginOut(){
        Session::delete('staff_id');//清除staff_id缓存
        return $this->fetch('login/login');
	}


	
}

