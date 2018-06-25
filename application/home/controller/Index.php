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
        list($activity_goods)=[];
        if(!empty($user_id)){
            $user_info = Db::name('user_info')->where(array('user_id'=>$user_id))->find();
        }
        //商品显示
        $goods  = Db::name('goods_info')->where(array('is_delete'=>0))->select();
        $goods_Array = array_reduce($goods,function(&$goods_Array,$v){
            $goods_Array[$v['goods_id']] = $v;
            return $goods_Array;
        });

        //侧边菜单显示
        $class_list = Db::name('goods_class')->where(array('is_display'=>0,'is_delete'=>0))->select();
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
        //活动商品显示
        $activity = Db::name('activity')->where(array('is_status'=>0))->select();
        foreach ($activity as $k=>$v){
            $goods_id = json_decode($v['goods_id']);
            $activity_goods[$k] = $v;
            foreach ($goods_id as $g){
                if(isset($goods_Array[$g]))
                $activity_goods[$k]['goods_info'][] =$goods_Array[$g];//指定活动旗下商品
            }
        }
        $this->assign('activity_goods',$activity_goods);
        $this->assign('class',$class);
        $this->assign('goods_class_array',$goods_class_array);
        $this->assign('user_info',$user_info);
        return $this->fetch('index');
    }
    //分类菜单整体显示
    public function class_menu(){
        $goods_class_id = input('post.goods_class_id');
        $class_list = Db::name('goods_class')->where(array('goods_class_id'=>$goods_class_id))->whereOr(array('child_class_id'=>$goods_class_id))->order('child_class_id asc')->select();
        $max_class = $class_list[0];
        unset($class_list[0]);
        $slice_list = array_values($class_list);
        $child_class = array_filter($slice_list,function($var){
            if($var['subgrade_class_id'] ==0){
                return true;
            }
            return false;
        });
        $subgrade_class = array_filter($slice_list,function($var){
            if($var['subgrade_class_id'] >0){
                return true;
            }
            return false;
        });
        foreach ($subgrade_class as $v){
            $two_classification[$v['subgrade_class_id']][] = $v;
        }
        foreach ($child_class as $k=>$v){
            $name =  array('class_name'=>$v['class_name'],'goods_class_id'=>$v['goods_class_id']);
            $max_class['one_class'][$k] = $name;
            $max_class['one_class'][$k]['two_class'] = isset($two_classification[$v['goods_class_id']])?$two_classification[$v['goods_class_id']]:[];
        }
        return json(array('code'=>1,'data'=>$max_class));
    }


}

