<?php
namespace app\admin\controller;
use app\admin\model\GoodsModel;
use app\common\model\GoodsClass;
use app\common\model\User;
use think\Controller;
class Goods extends Base
{
    public $details=['class_name'=>''];
	//显示商品列表信息
    public function index()
    {
       return $this->fetch('goods/goods');
    }


    //商品详情页
    public function goods_details(){
        $title = "商品-新增";
        $goods_class = new GoodsClass();
        $where['is_delete'] = 0;
        $class  = $goods_class->where($where)->select();
        $class_array = [];
        foreach ($class as $v) {
            if ($v['child_class_id'] == 0 && $v['subgrade_class_id'] == 0) {
                $class_array[] = $v;
            }
        }
        $time = date('Y-m-d H:i:s',time());
        $this->assign('class_array',$class_array);
        $this->assign('title',$title);
        $this->assign('time',$time);

        return $this->fetch('goods/goods_save');
    }
    //商品新增
    public function goods_add(){
        $a = $_POST['images'];
        print_r($a);
        exit;
    }
    //商品分类列表显示
    public function goods_class(){
        $goods_class = new GoodsClass();
        $where['is_delete'] = 0;
        $where['child_class_id'] = 0;
        $where['subgrade_class_id'] = 0;

        $class= $goods_class->where($where)->select();
        if($class){
            $class = collection($class)->toArray();
            foreach ($class as &$v){
                $v['is_display'] = $v['is_display'] == 1 ?'是':'否';
                $v['is_recommend'] = $v['is_recommend'] == 1 ?'是':'否';

            }
        }
        $this->assign('list',$class);
        return $this->fetch('goods/goods_class');
    }

    //显示分类指定下级数据
    public function goods_class_view(){
        $return_status = true;
        $msg = "success";
        $child_class_id =    input('post.child_class_id');
        $subgrade_class_id = input('post.subgrade_class_id');
        $action = input('post.action');

        $where['is_delete'] = 0;
        if($action == 'child_class'){
        $where['child_class_id'] = $child_class_id;
        $where['subgrade_class_id'] = 0;
        }else{
        $where['subgrade_class_id'] = $subgrade_class_id;
        }
        $goods_class = new GoodsClass();

        $child_class= $goods_class->where($where)->select();
        if($child_class)
        {
            $child_class = collection($child_class)->toArray();
            foreach ($child_class as &$v){
                $v['is_display'] = $v['is_display'] == 1 ?'是':'否';
                $v['is_recommend'] = $v['is_recommend'] == 1 ?'是':'否';

            }
        }else{
            $return_status = false;
            $msg = "暂无下级分类，请添加";
        }
        return json(array('return_status'=>$return_status,'msg'=>$msg,'data'=>$child_class));

    }
        //商品分类显示详情页
    public function goods_save_class(){
        $class_id = input('post.class_id');
        $title = '商品分类-新增';
        $goods_class = new GoodsClass();
        $where['is_delete'] = 0;
        $list = $goods_class->where($where)->select();
        $details=[];
        $class_array=[];//顶级分类
        $list = collection($list)->toArray();
        if($list){
            $list = collection($list)->toArray();
            $class_array_cl = array_column($list,'class_name','goods_class_id');//分类ID对应的名称
            foreach ($list as $v){
            if($v['child_class_id']== 0 && $v['subgrade_class_id'] == 0 ){
                $class_array[$v['goods_class_id']] = $v['class_name'];
            }
            if(!empty($class_id) && $v['goods_class_id'] == $class_id){
                $title = '商品分类-编辑';
                $details = $v;
                if($v['child_class_id'] != 0)$details['child_class_name'] =$class_array_cl[$v['child_class_id']];
                if($v['subgrade_class_id'] != 0)$details['subgrade_class_name'] =$class_array_cl[$v['subgrade_class_id']];

            }
        }
        }
        $this->assign('title',$title);
        $this->assign('details',$details);
        $this->assign('class_array',$class_array);
        return $this->fetch('goods/goods_save_class');
    }
    //分类第二级显示列表
    public function goods_class_two(){
        $return_status = true;
        $msg = "success";
        $child_id = input('post.child_id');

        $where['is_delete'] = 0;
        $where['subgrade_class_id'] = 0;
        $where['child_class_id'] = $child_id;
        $goods_class = new GoodsClass();
        $list = $goods_class->where($where)->select();
        if(empty($list)){$return_status=false;$msg='该分类下无子级分类，请添加';}
        return json(array('return_status'=>$return_status,'msg'=>$msg,'data'=>$list));

    }
    //分类第三级显示列表
    public function goods_class_three(){
        $return_status = true;
        $msg = "success";
        $subgrade_class_id = input('post.subgrade_class_id');
        $where['is_delete'] = 0;
        $where['subgrade_class_id'] = $subgrade_class_id;
        $goods_class = new GoodsClass();
        $list = $goods_class->where($where)->select();
        if(empty($list)){$return_status=false;$msg='该分类下无子级分类，请添加';}
        return json(array('return_status'=>$return_status,'msg'=>$msg,'data'=>$list));

    }
    //商品分类新增或编辑
    public function goods_class_save(){
        $return_status = true;
        $msg = '更新成功';
        $params['goods_class_id'] = input('post.goods_class_id');//分类ID
        $params['class_name'] = input('post.class_name');//分类名称
        $params['child_class_one'] = input('post.child_class_one');//顶级分类
        $params['child_class_two'] = input('post.child_class_two');//下级分类
        $params['is_display'] = input('post.is_display');//是否显示
        $params['is_recommend'] = input('post.is_recommend');//是否推荐
        $params['goods_sort'] = input('post.goods_sort');//排序（从小到大）
        if(empty($params['class_name']))return json(array('return_status'=>false,'msg'=>'请输入分类名称'));
        $good_class = new GoodsModel($params);
        $data = $good_class->goods_class_save($params);
        $goods_class_html = $this->goods_class();
        if(!$data){
            $return_status=false;
            $msg = '更新成功';
        }
        return json(array('return_status'=>$return_status,'msg'=>$msg,'html'=>$goods_class_html));

    }

    //删除商品分类
    public function goods_class_delete(){

        $return_status = true;
        $msg = '删除成功';
        $goods_class_id = input("post.goods_class_id");
        $staff = new GoodsModel();
        $data = $staff->goods_class_delete($goods_class_id);//数据删除
        if(!$data){
            $return_status=false;
            $msg = '删除失败';
        }
        return json(array('return_status'=>$return_status,'msg'=>$msg));
    }
}

