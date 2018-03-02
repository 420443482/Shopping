<?php
namespace app\admin\controller;
use app\admin\model\LoginModel;
use app\common\model\StaffInfo;

use think\Controller;
class Login extends Controller
{
    public function index()
    {

       return $this->fetch('login');

    }
    //账号注册显示
    public function register()
    {
        return $this->fetch('register');
    }
    //后台账户登录
    public function staff_dl(){

        $staff = new LoginModel();
        $data = $staff->login($_POST);
        if($data)return json(array('return_status'=>true,'msg'=>"success"));
    }
    //账号注册显示
    public function staff_add()
    {
        $return_status = true;
        $msg = '注册成功';
        $params['staff_account'] = input('post.staff_account');//员工账号
        $params['staff_name'] = input('post.staff_name');//员工姓名
        $params['staff_password'] = input('post.staff_password');//员工登录密码
        $params['repeat_password'] = input('post.repeat_password');//员工重复密码
        $params['staff_phone'] = input('post.staff_phone');//员工手机号
        $params['staff_address'] = input('post.staff_address');//员工住址
        $params['staff_sex'] = input('post.staff_sex');//员工性别
        $params['staff_wx'] = input('post.staff_wx');//员工微信
        $params['staff_qq'] = input('post.staff_qq');//员工QQ
        $staff = new StaffInfo();
        $list = $staff->where(array('staff_account'=>$params['staff_account']))->find();//判断账户是否已经存在
        if($list)return json(array('return_status'=>false,'msg'=>'该账户已经存在，请重新输入'));
        if(!eregi("[^\x80-\xff]",$params['staff_name']) || empty($params['staff_name'])) return json(array('return_status'=>false,'msg'=>'姓名只能输入中文'));
        if($params['staff_password'] != $params['repeat_password']) return json(array('return_status'=>false,'msg'=>'二次输入密码不相等，请重新输入'));
        if(!preg_match('/^0?1[3|4|5|6|7|8][0-9]\d{8}$/', $params['staff_phone'] ) || empty($params['staff_phone'])) return json(array('return_status'=>false,'msg'=>'手机号码输入不合格或为空'));
        if(empty($params['staff_address']))return json(array('return_status'=>false,'msg'=>'员工住址不能为空'));
        if($params['staff_sex']<=0)return json(array('return_status'=>false,'msg'=>'请选择性别'));
        $params['staff_password'] = md5($params['staff_password']);//md5密码加密
        $params['staff_code'] = date('Y'). date('m'). date('d').rand(10,99).rand(10,99);//生成员工编号
        $staff = new LoginModel();
        $data = $staff->register($params);
        if($data)return json(array('return_status'=>$return_status,'msg'=>$msg));
    }
}

