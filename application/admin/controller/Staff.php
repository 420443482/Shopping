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
    //无刷新拉取员工数据
    public function staff_search()
    {
        $staff_name = input('post.staff_name');//员工姓名
        $staff_code = input('post.staff_code');//员工编号
        $staff_phone = input('post.staff_phone');//员工手机
        $staff_add_time = input('post.staff_add_time');//员工入职时间

        if(!empty($staff_name)){
            $where['staff_name'] = $staff_name;
        }
        if(!empty($staff_code)){
            $where['staff_code'] = $staff_code;
        }
        if(!empty($staff_phone)){
            $where['staff_phone'] = $staff_phone;
        }
        if(!empty($staff_add_time)){
            $where['staff_add_time'] = $staff_add_time;
        }
        $where['staff_delete_status'] = 0;
        $staff = new StaffInfo();
        // 查询状态为1的用户数据 并且每页显示10条数据
        $list = $staff->where($where)->order('staff_id', 'desc')->paginate(1);
        // 获取分页显示
        $page = $list->render();

        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);

        return $this->fetch('staff_list');
    }
}

