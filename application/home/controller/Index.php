<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Session;
    class Index extends Controller
{
	//显示首页
    public function index()
    {
        $user_id = session('user_id');
        $user_info = [];
        if(!empty($user_id)){
            $user_info = Db::name('user_info')->where(array('user_id'=>$user_id))->find();
        }
        $class_list = Db::name('goods_class')->where(array('is_delete'=>0))->select();
        list($class,$goods_class_array) = [];
        foreach ($class_list as $k=>$v){
            if($v['child_class_id'] == 0){
                $class[$k]['class_name'] =  $v['class_name'];
                $class[$k]['goods_class_id'] =  $v['goods_class_id'];
            }
            if($v['child_class_id'] !=0 && $v['subgrade_class_id'] == 0 ){
                $goods_class_array[$v['child_class_id']][$k]['class_name'] = $v['class_name'];
                $goods_class_array[$v['child_class_id']][$k]['goods_class_id'] = $v['goods_class_id'];
            }
        }
        array_values($class);
        $this->assign('class',$class);
        $this->assign('goods_class_array',$goods_class_array);
        $this->assign('user_info',$user_info);
        return $this->fetch('index');
    }
    //分类菜单显示
    public function class_menu(){
       
    }
}

