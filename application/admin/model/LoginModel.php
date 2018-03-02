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
        $list = $staff->where(array('staff_account'=>$params['staff_account'],'staff_password'=>$params['staff_password']))->find();//验证登录账户
        if($list){
            Session::set('staff_id',$list['staff_id']);
        }

        return $list;
    }
    //账号注册
    public function register($params){
        $staff = new StaffInfo;
        $staff->data([
            'staff_account'     => $params['staff_account'],
            'staff_name'     => $params['staff_name'],
            'staff_password' => $params['staff_password'],
            'staff_code'     => $params['staff_code'],
            'staff_phone'    => $params['staff_phone'],
            'staff_address'  => $params['staff_address'],
            'staff_sex'      => $params['staff_sex'],
            'staff_wx'       => $params['staff_wx'],
            'staff_qq'       => $params['staff_qq'],
            'staff_staff_update_time' => date('Y-m-d H:i:s',time()),
        ]);
        $staff_id = $staff->save();
        return $staff_id;
    }

}