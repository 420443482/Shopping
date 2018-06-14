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
        $user = Db::name('user_info')->where(array('user_name'=>$username,'user_password'=>md5($password)))->find();

        if(!$user){
            //记录登陆信息
            $msg['code'] = '0';
            $msg['msg'] = '登陆失败，账号密码错误';
        }else{
            Session::set('user_id',$user['user_id']);
            $msg['code'] = '1';
        }
        if(!empty($remember)){
            Session::set('username',$username);
            Session::set('password',$password);
        }
        return   json($msg);

    }
}

