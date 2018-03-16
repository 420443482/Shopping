<?php
namespace app\admin\model;
use app\common\model\StaffInfo;
use think\Model;
use think\Session;

class LoginModel extends Model{
    //账号登录
    public function login($params){

        $staff = new StaffInfo;
        $params['staff_password'] = md5($params['staff_password']);//密码加密
        $list = $staff->where(array('staff_account'=>$params['staff_account'],'staff_password'=>$params['staff_password'],'staff_delete_status'=>0))->find();//验证登录账户
        if($list){
            $date = date('Y-m-d',strtotime($list['staff_add_time']));
            Session::set('staff_id',$list['staff_id']);
            Session::set('staff_portrait',$list['staff_portrait']);
            Session::set('staff_name',$list['staff_name']);
            Session::set('staff_account',$list['staff_account']);
            Session::set('date',$date);
        }

        return $list;
    }


}