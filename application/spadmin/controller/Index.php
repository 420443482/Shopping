<?php
namespace app\spadmin\controller;


use think\Controller;
use think\Db;
use think\Session;

class Index  extends Base
{
    public function index()
    {
        //加载菜单栏
        $menu = $this->menu();
        Session::set('powe_list',$menu['powe_list']);
        $this->assign('list',$menu);
        return view('index');
    }

    //桌面
    public function desktop(){

        return view('desktop');
    }

}

