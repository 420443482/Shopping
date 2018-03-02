<?php
namespace app\admin\controller;
use app\common\model\User;
use think\Controller;
class Base extends Controller
{
    public function _initialize(){
        $staff_id = session('staff_id');
        if($staff_id == null){
            return $this->redirect('/admin',"请先登录");
        }
    }
}

