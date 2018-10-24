<?php
/**
 * Created by bingo on 2017/3/22 525945448@qq.com.
 */


namespace app\spadmin\controller;


use think\Controller;
use think\Session;
use think\Db;

class Base extends Controller
{
    public function _initialize(){
        //检测是否安装
        if(!Session::get('pass')){
            $this->redirect(url('spadmin/login/login'));
        }
    }
}