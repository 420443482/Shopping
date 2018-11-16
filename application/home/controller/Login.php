<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Session;
class Login extends Controller
{
	//显示登陆页
    public function index()
    {
        return $this->fetch('index');
    }
    //登陆验证
    public function login_home(){
        $username = input('post.username');//登陆账号
        $password = input('post.password');//登陆密码
        $remember = input('post.remember');//是否保存这个登陆信息
        $member = Db::name('member_info')->where("member_name=:member_name OR member_phone=:member_phone",
            ['member_name' => $username, 'member_phone' => $username])->where(array('member_password'=>md5($password)))->find();

        if(!$member){
            //记录登陆信息
            $msg['code'] = '0';
            $msg['msg'] = '登陆失败，账号密码错误';
        }else{
            Session::set('member_id',$member['member_id']);
            $msg['code'] = '1';
        }
        if(!empty($remember)){
            Session::set('username',$username);
            Session::set('password',$password);
        }
        return   json($msg);

    }
}

