<?php
namespace app\admin\controller;
use app\common\model\User;
use think\Controller;
use think\Db;
use think\Session;

class Base extends Controller
{
    public function _initialize(){
        $staff_id = session('staff_id');
        if($staff_id == null){
            return $this->redirect('/admin',"请先登录");
        }
        $this->menu();
    }
    //加载菜单
    public function menu(){
        $menu = Db::name('menu')->select();
        list($menu_one,$menu_two) = [];
        foreach ($menu as $v){
            if($v['pid'] == 0){
                $menu_one[] = $v;
            }else{
                $menu_two[$v['pid']][]=$v;
            }
        }
        $this->assign('menu_one',$menu_one);
        $this->assign('menu_two',$menu_two);

    }
}

