<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Session;
class Register extends Controller
{
	//显示注册页
    public function index()
    {
        return $this->fetch('index');
    }
    //会员账号注册
    public function register(){
        $mobile_phone = input('post.mobile_phone');//手机号码
        $password = input('post.password');//登陆密码
        $phone_yzm = input('post.mobile_code');//手机短信验证码
        if(empty($mobile_phone) || empty($password) || empty($phone_yzm)){
            return json(array('code'=>0,'msg'=>'注册资料不完善请重新填写'));
        }
        $user_info = Db::name('user_info')->where(array('user_phone'=>$mobile_phone))->find();
        if($user_info)return json(array('code'=>0,'msg'=>'该手机账号已被注册'));
        if(!$this->sendMail($phone_yzm))return json(array('code'=>0,'msg'=>'手机验证码输入有误'));
        $data['user_phone']  = $mobile_phone;
        $data['user_name']   = $mobile_phone;
        $data['user_password']  = md5($password);
        $info = Db::name('user_info')->insertGetId($data);
        if(!$info)return json(array('code'=>0,'msg'=>'账号密码有误，请重新输入'));
        return json(array('code'=>1,'msg'=>'注册成功'));
    }
    //短信验证码验证(功能待开发)
    public function sendMail($phone_yzm){
        return true;
    }
}

