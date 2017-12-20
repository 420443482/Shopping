<?php
namespace app\admin\model;
use app\common\model\UserInfo;
use think\Model;

class LoginModel extends Model{

    public function loginModel($post){
        $user = new UserInfo();
        $list = $user->where(array('user'=>$post['username'],'password'=>$post['password']))->find();
        return $list;

    }

}