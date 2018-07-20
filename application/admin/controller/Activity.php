<?php
namespace app\admin\controller;
use app\common\model\User;
use think\Controller;
use think\Db;
use think\Session;
    class Activity extends Base
{
	//活动列表
    public function index()
    {
        $activity = Db::name("activity")->select();
        $this->assign('list',$activity);
        return $this->fetch('activity');
    }
}

