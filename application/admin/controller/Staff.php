<?php
namespace app\admin\controller;
use app\common\model\StaffInfo;
use app\common\model\User;
use think\Controller;
class Staff extends Controller
{
	//显示首页
    public function index()
    {
        $staff = new StaffInfo();
        // 查询状态为1的用户数据 并且每页显示10条数据
        $list = $staff->where(array('staff_delete_status'=>0))->order('staff_id', 'desc')->paginate(1);
        // 获取分页显示
        $page = $list->render();

        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);

        return $this->fetch('staff');
    }
    //显示首页
    public function staff_list()
    {
        $staff = new StaffInfo();
        // 查询状态为1的用户数据 并且每页显示10条数据
        $list = $staff->where(array('staff_delete_status'=>0))->order('staff_id', 'desc')->paginate(1);
        // 获取分页显示
        $page = $list->render();

        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);

        return $this->fetch('staff_list');
    }
}

