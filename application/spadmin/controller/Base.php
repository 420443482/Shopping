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
        if($_SERVER['PATH_INFO'] != '/spadmin/index/index'){
            if($_SERVER['PATH_INFO'] != '/spadmin/index/desktop.html'){
                if (!in_array($_SERVER['PATH_INFO'],Session::get('powe_list'))) {
                    $this->error('抱歉，您没有该权限', '');
                }
          }
        }
    }
    //加载菜单栏
    public  function  menu(){
        //读取账户角色信息
        $menu_list = [];
        $role_id = Session::get('staff_role');
        $role = Db::name('role')->where(array('role_id'=>$role_id,'is_delete'=>0))->find();
        $ids = explode(',',$role['role_powe_ids']);
        $powe = Db::name('role_powe')->where(array('powe_id'=>array('IN',$ids)))->select();
        foreach ($powe as $v){
            $menu_list[$v['powe_pid']][] = [
                'powe_id'=>$v['powe_id'],
                'powe_name'=>$v['powe_name'],
                'powe_url'=>$v['powe_url']
            ];
            $menu_list['powe_list'][] = $v['powe_url'];
        }
        return $menu_list;
    }
}