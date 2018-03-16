<?php
namespace app\admin\controller;
use app\common\model\StaffInfo;
use app\admin\model\StaffModel;
use app\common\model\User;
use think\Controller;
class Staff extends Base
{
    public $staff_model = ['staff_account'=>'', 'staff_name'=>'', 'staff_password'=>'', 'staff_code'=>'', 'staff_phone'=>'', 'staff_address'=>'',
                           'staff_sex'=>'', 'staff_wx'=>'', 'staff_qq'=>'', 'staff_delete_status'=>'', 'staff_portrait'=>'', 'staff_add_time'=>'', 'staff_update_time'=>''];
	//显示首页
    public function index()
    {
        $staff = new StaffInfo();
        // 查询状态为1的用户数据 并且每页显示10条数据
        $list = $staff->where(array('staff_delete_status'=>0))->order('staff_id', 'desc')->paginate(10);
        // 获取分页显示
        $page = $list->render();

        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $list->total());
        $this->assign('list_num',10);
        return $this->fetch('staff');
    }
    //无刷新拉取员工数据
    public function staff_search()
    {
        $list_page= 0;
        $list_num = 10;
        $staff_name = input('post.staff_name');//员工姓名
        $staff_code = input('post.staff_code');//员工编号
        $staff_phone = input('post.staff_phone');//员工手机
        $staff_add_time = input('post.staff_add_time');//员工入职时间
        $staff_page = input('post.page');//第几页
        $staff_page_num = input('post.num');//数据显示条数

        if(!empty($staff_name))$where['staff_name'] = array('like','%'.$staff_name.'%');//根据姓名查询
        if(!empty($staff_code))$where['staff_code'] = array('like','%'.$staff_code.'%');//根据编号查询
        if(!empty($staff_phone))$where['staff_phone'] = array('like','%'.$staff_phone.'%');//根据手机号查询
        if(!empty($staff_add_time))$where['staff_add_time'] = $staff_add_time;//根据创建时间查询
        if(!empty($staff_page_num))$list_num = $staff_page_num;//分页显示条目

        if(!empty($staff_page))$list_page=$staff_page;//第几页
        $where['staff_delete_status'] = 0;//是否已被删除

        $staff = new StaffModel();
        $list = $staff->staff($where,$list_page,$list_num);//获取员工信息数据
        // 获取分页显示
        $page = $list->render();

        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('list_num', $list_num);
        $this->assign('total', $list->total());

        return $this->fetch('staff_list');
    }
    //修改时显示详细信息
    public function staff_display(){
        $time = date('Y-m-d H:i:s', time());
        $staff_id = input('get.staff_id');
        $list = $this->staff_model;//调取表模型
        if(isset($staff_id)){
            $staff = new StaffModel();
            $list = $staff->staff_list_one($staff_id);
        }

        $this->assign('list', $list);
        $this->assign('time', $time);
        return $this->fetch('staff_display');
    }

    //删除员工信息
    public function staff_delete(){

        $return_status = true;
        $msg = '删除成功';
        $id = input("post.staff_id");
        if(!preg_match('/\d+/',$id))return json(array('return_status'=>false,'msg'=>'请选择要删除的数据'));
        $staff = new StaffModel();
        $data = $staff->staff_delete($id);//数据删除
        if(!$data){
            $return_status=false;
            $msg = '删除失败';
        }

        return json(array('return_status'=>$return_status,'msg'=>$msg));
    }
}

