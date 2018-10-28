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
        list($class,$goods_class_array) = array([],[]);
        $user_id = session('user_id');
        $user_info = [];
        if(!empty($user_id)){
            $user_info = Db::name('user_info')->where(array('user_id'=>$user_id))->find();
        }
        //侧边菜单显示
        $class_list = Db::name('goods_class')->where(array('is_display'=>1,'is_delete'=>0))->select();

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
        //商城首页商品
        $where = [
            'i.goods_is_exhibition' => 1,
            'i.is_delete' => 0,
            'c.is_display' => 1,
            'c.is_delete' => 0,
        ];
        $field = 'i.goods_name,i.goods_id,r.goods_id,r.one_class_id,
                  c.goods_class_id,i.goods_sales_price,i.goods_summary,i.goods_images';
        $goods_list = Db::name('goods_info')
            ->alias('i')
            ->join('yr_goods_class_relevance r','i.goods_id = r.goods_id')
            ->join('yr_goods_class c','r.one_class_id = c.goods_class_id')
            ->where($where)
            ->field($field)
            ->order('c.goods_sort desc')
            ->select();
        $goods_column = [];
        foreach ($goods_list as $k=>$v){
            $images = json_decode($v['goods_images'],true);
            $v['goods_images'] = $images[0];
            $goods_column[$v['goods_class_id']][] = $v;
        }

        $goods_class_name = array_column($class_list,'class_name','goods_class_id');
        $this->assign('goods_class_name',$goods_class_name);
        $this->assign('goods_column',$goods_column);
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

