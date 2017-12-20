<?php
namespace app\admin\controller;
use app\admin\model\LoginModel;
use think\Controller;
class Login extends Controller
{
    public function index()
    {

       return $this->fetch('login');

    }

    public function login()
    {
        $user = new LoginModel();
        $data = $user->loginModel($_POST);
        return !empty($data)?true:false;
    }
}

