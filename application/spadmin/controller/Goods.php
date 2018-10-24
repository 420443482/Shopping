<?php
namespace app\spadmin\controller;


use app\common\Controller\Save;
use think\Controller;
use think\Db;
use think\Exception;
use think\Loader;
use think\Request;
use think\Session;

class Goods  extends Base
{
    /***
     * 商品信息
     * goods_name  商品名称
     * goods_images  商品详图
     * goods_summary 商品描述
     * goods_market_price 市场价
     * goods_buying_price   进货价
     * goods_sales_price    销售价
     * goods_stock          库存
     * one_class_id         顶级分类 （goods_class_relevance）
     * two_class_id         子级分类 （goods_class_relevance）
     * three_class_id       子子级分类（goods_class_relevance）
     * goods_is_shipping    是否包邮
     * goods_is_discount    是否折扣
     * goods_is_grounding   是否上架
     * goods_grounding_time 上架时间
     * goods_undercarriage_time 下架时间
     * goods_description 简介
     * goods_images 详图
     * */
    public  $goods;//商品
    public  $goods_class;//商品分类
    public  $goods_class_relevance;//商品和商品分类关联
    public  $images_upload;//图片库

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $goods_table['table_name'] = 'goods_info';
        $this->goods = new Save($goods_table);
        $goods_class_table['table_name'] = 'goods_class';
        $this->goods_class = new Save($goods_class_table);
        $goods_class_relevance_table['table_name'] = 'goods_class_relevance';
        $this->goods_class_relevance = new Save($goods_class_relevance_table);
        $images_upload_table['table_name'] = 'images_upload';
        $this->images_upload = new Save($images_upload_table);
    }
    //商品列表
    public function goods_list()
    {
        list($font,$data) = array([],[]);
        $goods_name = isset($_REQUEST['goods_name'])?$_REQUEST['goods_name']:'';
        if(!empty($goods_name)){
            $data['where']['goods_name'] = array('like','%'.$goods_name.'%');
            $font['goods_name'] = $goods_name;
        }
        $data['where']['is_delete'] = 0;
        $list = $this->goods->select($data);

        $this->assign("page", $list->render());
        $this->assign("count",$list->count());
        $this->assign('list',$list);
        $this->assign('where',$font);
        return view();
    }
    //商品参数列表
    public function  goods_parameter_list(){
        $params['staff_id'] = Session::get('staff_id');
        $params['goods_name'] = isset($_REQUEST['goods_name'])?$_REQUEST['goods_name']:'';
        $params['goods_images'] = isset($_REQUEST['map_images'])?$_REQUEST['map_images']:[];
        $params['goods_summary'] = isset($_REQUEST['goods_summary'])?$_REQUEST['goods_summary']:'';
        $params['goods_stock'] = isset($_REQUEST['goods_stock'])?$_REQUEST['goods_stock']:0;
        $params['goods_market_price'] = isset($_REQUEST['goods_market_price'])?$_REQUEST['goods_market_price']:0;
        $params['goods_buying_price'] = isset($_REQUEST['goods_buying_price'])?$_REQUEST['goods_buying_price']:0;
        $params['goods_sales_price'] = isset($_REQUEST['goods_sales_price'])?$_REQUEST['goods_sales_price']:0;
        $params['one_class_id'] = isset($_REQUEST['one_class_id'])?$_REQUEST['one_class_id']:0;
        $params['two_class_id'] = isset($_REQUEST['two_class_id'])?$_REQUEST['two_class_id']:0;
        $params['three_class_id'] = isset($_REQUEST['three_class_id'])?$_REQUEST['three_class_id']:0;
        $params['goods_is_shipping'] = isset($_REQUEST['goods_is_shipping'])?1:0;
        $params['goods_is_discount'] = isset($_REQUEST['goods_is_discount'])?1:0;
        $params['goods_is_grounding'] = isset($_REQUEST['goods_is_grounding'])?1:0;
        $params['goods_grounding_time'] = isset($_REQUEST['goods_grounding_time'])?strtotime($_REQUEST['goods_grounding_time']):time();
        $params['goods_undercarriage_time'] = isset($_REQUEST['goods_undercarriage_time'])?strtotime($_REQUEST['goods_undercarriage_time']):'';
        $params['goods_description'] = isset($_REQUEST['goods_description'])?$_REQUEST['goods_description']:'';
        //验证器验证
        $validate = Loader:: validate('GoodsValidate');
        if(!$validate->scene('save')->check($params)){
            $this->error($validate->getError());
        }
        if(!empty($params['goods_undercarriage_time'])){
            if( $params['goods_grounding_time'] > $params['goods_undercarriage_time']){
                $this->error('上架时间不能大于下架时间');
            }
        }
        $params['goods_images'] = json_encode($params['goods_images']);
        //需要去掉的索引，在商品表中不存在
        $unset_array = [
            'one_class_id',
            'two_class_id',
            'three_class_id'
        ];
        $data['data'] = unset_key($params,$unset_array);
        $data['relevance'] = $params;
        return $data;
    }
    //商品分类下拉列表加载
    public function  goods_class_spinner(){
        $class_level = isset($_REQUEST['class_level'])?$_REQUEST['class_level']:'';
        $goods_class_id = isset($_REQUEST['goods_class_id'])?$_REQUEST['goods_class_id']:'';
        if($class_level != 1 && $goods_class_id == 0){
            $this->error('请点击有效分类');
        }
        $data = [];

        switch ($class_level){
            case '2':
                $data['where']['child_class_id'] = $goods_class_id;
                $data['where']['subgrade_class_id'] = 0;
                break;
            case '3':
                $data['where']['subgrade_class_id'] = $goods_class_id;
                break;
            default:
                $data['where']['child_class_id'] = 0;
                $data['where']['subgrade_class_id'] = 0;
                break;
        }

        $result = $this->goods_class->selectAll($data);
        $this->success('','',$result);
    }
    //商品新增
    public function goods_add(){
        if($this->request->isAjax()){
            $params = $this->goods_parameter_list();
            try{
                $goods_id = $this->goods->add($params);
                $relevance['data'] = [
                    'goods_id'=> $goods_id,
                    'one_class_id'=> $params['relevance']['one_class_id'],
                    'two_class_id'=> $params['relevance']['two_class_id'],
                    'three_class_id'=> $params['relevance']['three_class_id'],
                ];
                $this->goods_class_relevance->add($relevance);
                $images = json_decode($params['data']['goods_images']);
                $implode_images = implode(',',$images);
                $images['where']['images_adress'] = array('IN',$implode_images);
                $images['data']['status'] = 1;
                $this->images_upload->edit($images);
                Db::commit();
                $result = true;
            }catch (\Exception $e) {
                Db::rollback(); //事物回滚
                $result = false;
            }
            if($result){
                $this->success('新增成功');
            }else{
                $this->error('新增失败');
            }
        }
        return view('goods/goods_edit');
    }
    //商品编辑
    public function goods_edit(){
        $goods_id = $_REQUEST['goods_id'];
        if($this->request->isAjax()){
            $params = $this->goods_parameter_list();
            $params['where']['goods_id'] = $goods_id;
            try{
                $this->goods->edit($params);
                $relevance['data'] = [
                    'one_class_id'=> $params['relevance']['one_class_id'],
                    'two_class_id'=> $params['relevance']['two_class_id'],
                    'three_class_id'=> $params['relevance']['three_class_id'],
                ];
                $relevance['where']['goods_id'] = $goods_id;
                $this->goods_class_relevance->edit($relevance);
                $images = json_decode($params['data']['goods_images']);
                $implode_images = implode(',',$images);
                $images['where']['images_adress'] = array('IN',$implode_images);
                $images['data']['status'] = 1;
                $this->images_upload->edit($images);
                Db::commit();
                $result = true;
            }catch (\Exception $e) {
                Db::rollback(); //事物回滚
                $result = false;
            }
            if($result){
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败');
            }
        }else{
            $list = Db::name('goods_info')
            ->alias('a')
            ->join('goods_class_relevance w','a.goods_id = w.goods_id')
            ->where(array('a.goods_id'=>$goods_id))->find();
            $class_name = goods_class_name();
            $this->assign('list',$list);
            $this->assign('class_name',$class_name);
        }
        return view();
    }
    //删除商品操作
    public function goods_delete(){
        try{
            $data['where']['goods_id'] = $_REQUEST['goods_id'];
            $data['data']['is_delete'] = 1;
            $this->goods->edit($data);
            $relevance['where']['goods_id'] = $_REQUEST['goods_id'];
            $relevance['data']['is_delete'] = 1;
            $this->goods_class_relevance->edit($relevance);
            $goods_image = $this->goods->selectFind($data);//查询该商品正在使用的图片
            $images = json_decode($goods_image['goods_images']);
            $implode_images = implode(',',$images);
            $images['where']['images_adress'] = array('IN',$implode_images);
            $images['data']['status'] = 1;
            $this->images_upload->edit($images);
            Db::commit();//提交
            $result = true;
        }catch (\Exception $e){
            Db::rollback(); //事物回滚
            $result = false;
        }
        if($result){
            $this->success('删除成功');
        }else{
            $this->success('删除失败');
        }
    }
    //多图上传
    public function  map_upload_images(){
        $class = 'goods';
        $upload = new \app\common\controller\Upload();
        $result = $upload->map_upload($class);
        return json($result);
    }

    //商品分类
    public function goods_class_list(){
        $data['where']['is_delete'] = 0;
        $font = [];
        if($_REQUEST['class_name']){
            $data['where']['class_name'] = array('like','%'.$_REQUEST['class_name'].'%');
            $font['class_name'] = $_REQUEST['class_name'];
        }

        if(isset($_REQUEST['class_level'])){
            $data['where']['class_level'] = $_REQUEST['class_level'] + 1;
            $class_id = $data['where']['class_level'] == 2 ? 'child_class_id':'subgrade_class_id';
            $data['where'][$class_id] = $_REQUEST['goods_class_id'];
            $goods_class = $this->goods_class->selectFind(array('where'=>['goods_class_id'=> $_REQUEST['goods_class_id']]));
            $this->assign("goods_class",$goods_class);
            $font['class_level'] = $_REQUEST['class_level'];
        }else{
            $data['where']['class_level'] = 1;
        }

        $list = $this->goods_class->select($data);
        $this->assign("page", $list->render());
        $this->assign("count",$list->count());
        $this->assign('list',$list);
        $this->assign('where',$font);
        return view();
    }
    //商品分类参数列表
    public function goods_class_parameter_list(){
        $params['class_name'] = isset($_REQUEST['class_name'])?$_REQUEST['class_name']:'';
        $params['child_class_id'] = isset($_REQUEST['child_class_id'])?$_REQUEST['child_class_id']:0;
        $params['subgrade_class_id'] = isset($_REQUEST['subgrade_class_id'])?$_REQUEST['subgrade_class_id']:0;
        $params['is_recommend'] = isset($_REQUEST['is_recommend'])?1:0;
        $params['is_display'] = isset($_REQUEST['is_display'])?1:0;
        $params['goods_update_time'] = date('Y-m-d H:i:s',time());
        if($params['child_class_id'] != 0 && $params['subgrade_class_id'] == 0){
            $params['class_level'] = 2;
        }elseif ($params['child_class_id'] != 0 && $params['subgrade_class_id'] != 0){
            $params['class_level'] = 3;
        }else{
            $params['class_level'] = 1;
        }
        if(empty($params['class_name'])){
            $this->error('请填写分类名称');
        }
        return $params;
    }
    //新增商品分类
    public function  goods_class_add(){
        if($this->request->isAjax()){
            $data['data'] = $this->goods_class_parameter_list();
            $result = $this->goods_class->add($data);
            if($result){
                $this->success('新增分类成功');
            }else{
                $this->error('新增分类失败');
            }
        }
        return view('goods_class_edit');
    }
    //编辑商品分类
    public function goods_class_edit(){
        if($this->request->isAjax()){
            $data['data'] = $this->goods_class_parameter_list();
            $data['where']['goods_class_id'] = $_REQUEST['goods_class_id'];
            $result = $this->goods_class->edit($data);
            if($result){
                $this->success('编辑分类成功');
            }else{
                $this->error('编辑分类失败');
            }
        }else{
            $data['where']['goods_class_id'] = $_REQUEST['goods_class_id'];
            $list = $this->goods_class->selectFind($data);
            $class_name = goods_class_name();
            $this->assign('class_name',$class_name);
            $this->assign('list',$list);
        }
        return view();
    }
    //删除商品分类
    public function goods_class_delete(){

        $data['goods_class_id'] = $_REQUEST['goods_class_id'];
        $data['is_delete'] = 0;
        $result =Db::name('goods_class')->where($data)->whereOr(array('child_class_id'=>$_REQUEST['goods_class_id']))
            ->whereOr(array('subgrade_class_id'=>$_REQUEST['goods_class_id']))->update(array('is_delete'=>1));
        if($result){
            $this->success('删除成功');
        }else{
            $this->success('删除失败');
        }
    }

}

