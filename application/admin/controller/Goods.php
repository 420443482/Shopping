<?php
namespace app\admin\controller;
use app\admin\model\GoodsModel;
use app\common\model\GoodsClass;
use app\common\model\GoodsClassRelevance;
use app\common\model\GoodsInfo;
use app\common\model\User;
use think\Controller;
use think\Loader;
use think\Validate;

class Goods extends Base
{

    //显示商品列表信息
    public function index()
    {
        $goods_name = input('post.goods_name');
        list($font) = array([]);
        if(!empty($goods_name))
        {
            $font['goods_name'] = $goods_name;
            $where['goods_name'] = array('like',"%$goods_name%");
        }
        $where['is_delete']= 0;
        $list_page= 0;
        $list_num = 10;
        $goods_page = input('post.page');//第几页
        $goods_page_num = input('post.num');//数据显示条数


        if(!empty($goods_page_num))$list_num = $goods_page_num;//分页显示条目
        if(!empty($goods_page))$list_page=$goods_page;//第几页

        $goods = new GoodsModel();
        $list = $goods->goods_index($where, $list_page, $list_num);
        // 获取分页显示
        $page = $list->render();


        foreach ($list as $v){
            $v['goods_grounding_time'] = date('Y-m-d',$v['goods_grounding_time']);
            $v['goods_is_shipping'] =  $v['goods_is_shipping'] == 1 ?'是':'否';
            $v['goods_is_discount'] =  $v['goods_is_discount'] == 1 ?'是':'否';
            $v['goods_is_grounding'] = $v['goods_is_grounding'] == 1 ?'是':'否';
            $images = json_decode($v['goods_images'],true);
            $v['goods_images'] = $images[0];
        }

        // 模板变量赋值
        $this->assign('font', $font);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $list->total());
        $this->assign('list_num',$list_num);
        return $this->fetch('goods/goods');
    }


    //商品详情页
    public function goods_details(){
        $title = "商品-新增";
        $goods_id = input('post.goods_id');//商品ID
        $title = empty($goods_id)?"商品-新增":"商品-编辑";
        $goods = new GoodsInfo();//实例化商品信息表
        $goods_list = [];
        $goods_image_array = [];
        $goods_class_data = [];
        if(!empty($goods_id)){
            $goods_list = $goods->where(array('goods_id'=>$goods_id))->find();
            $relevance = new GoodsClassRelevance();//实例化商品信息分类关联表
            $relevance = $relevance->where(array('goods_id'=>$goods_id))->find();
            $goods_list['goods_grounding_time'] = date('Y-m-d',$goods_list['goods_grounding_time']);
            $goods_list['goods_undercarriage_time'] = $goods_list['goods_undercarriage_time']>0?date('Y-m-d',$goods_list['goods_undercarriage_time']):'';
            $goods_images = json_decode($goods_list['goods_images'],true);
            if(count($goods_images)>0){//详图显示
                for ($i=0; $i<count($goods_images); $i++){
                    $goods_image_array[$i]['images_path'] = $goods_images[$i];
                    $goods_image_array[$i]['images_name'] = substr($goods_images[$i] , 25 );
                }
            }
        }
        $goods_class = new GoodsClass();//实例化商品分类表
        $where['is_delete'] = 0;
        $class  = $goods_class->where($where)->select();
        $class_array = [];
        foreach ($class as $v) {
            if ($v['child_class_id'] == 0 && $v['subgrade_class_id'] == 0) {
                $class_array[] = $v;
            }
            if(isset($relevance)){
                if($v['goods_class_id'] == $relevance['one_class_id']){
                    $goods_class_data['child_class_one']['name'] =$v['class_name'];
                    $goods_class_data['child_class_one']['id'] =$relevance['one_class_id'];
                }
                if($v['goods_class_id'] == $relevance['two_class_id']){
                    $goods_class_data['child_class_two']['name'] =$v['class_name'];
                    $goods_class_data['child_class_two']['id'] =$relevance['two_class_id'];
                }
                if($v['goods_class_id'] == $relevance['three_class_id']){
                    $goods_class_data['child_class_three']['name'] =$v['class_name'];
                    $goods_class_data['child_class_three']['id'] =$relevance['three_class_id'];
                }
            }
        }

        $time = date('Y-m-d H:i:s',time());
        $this->assign('goods_class_data',$goods_class_data);
        $this->assign('goods_image_array',$goods_image_array);
        $this->assign('goods_list',$goods_list);
        $this->assign('class_array',$class_array);
        $this->assign('title',$title);
        $this->assign('time',$time);

        return $this->fetch('goods/goods_save');
    }

    //商品新增
    public function goods_add(){
        $return_status = true;
        $msg = '添加成功';
        $params['goods_images'] = isset($_POST['goods_images'])?$_POST['goods_images']:[];
        $params['goods_description'] = $_POST['content'];
        $params['goods_name'] = input('post.goods_name');//商品名称
        $params['goods_summary'] = input('post.goods_summary');//商品描述
        $params['one_class_id'] = input('post.child_class_one');//商品顶级分类
        $params['two_class_id'] = input('post.child_class_two');//商品二级分类
        $params['three_class_id'] = input('post.child_class_three');//商品三级分类
        $params['goods_market_price'] = input('post.goods_market_price');//市场价
        $params['goods_buying_price'] = input('post.goods_buying_price');//进货价
        $params['goods_sales_price'] = input('post.goods_sales_price');//销售价
        $params['goods_stock'] = input('post.goods_stock');//商品库存
        $params['goods_is_shipping'] = input('post.goods_is_shipping');//是否包邮
        $params['goods_is_discount'] = input('post.goods_is_discount');//是否折扣
        $params['goods_is_grounding'] = input('post.goods_is_grounding');//是否上架
        $params['goods_grounding_time'] = input('post.goods_grounding_time');//上架时间
        $params['goods_undercarriage_time'] = input('post.goods_undercarriage_time');//下架时间
        $params['goods_undercarriage_time'] = input('post.goods_undercarriage_time');//下架时间
        $goods_id  = input('post.goods_id');//下架时间

        $images_temp = [];//临时图片存储
        //参数验证
        $validate = Loader:: validate('GoodsValidate');
        if(!$validate->scene('add')->check($params)){
            return json(array('msg'=>$validate->getError()));
        }
        if(count($params['goods_images'])<=0){
            return json(array('return_status'=>false,'msg'=>'请至少上传一张商品图'));
        }

        //存入富文本图片
        if(!empty($params['goods_description']))
        {
            //正则表达式匹配查找图片路径
            $pattern='/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.jpeg|\.png]))[\'|\"].*?[\/]?>/i';
            preg_match_all($pattern,$params['goods_description'],$res);
            $num=count($res[1]);
            $is_path = true;
            for($i=0;$i<$num;$i++)
            {
                $ueditor_img=$res[1][$i];//临时存储图片地址名称
                $newimg=str_replace('/uploads_temp/','/uploads/',$ueditor_img);//指定存入正式存储路径
                $path = substr($newimg,0,33);//存储路径
                //新建文件夹
                if(!is_dir($_SERVER['DOCUMENT_ROOT'].$path)){
                    mkdir(iconv('UTF-8','GBK',$_SERVER['DOCUMENT_ROOT'].$path),0777,true);
                }

                //转移图片
                if(copy($_SERVER['DOCUMENT_ROOT'].$ueditor_img, $_SERVER['DOCUMENT_ROOT'].$newimg))
                {
                    $images_temp[] =$_SERVER['DOCUMENT_ROOT'].$ueditor_img;//保存临时图片路径
//                    str_replace('/uploads_temp/','/uploads/',$ueditor_img);//正式图片地址
//                    unlink($_SERVER['DOCUMENT_ROOT'].$ueditor_img);//删除文件
                }else{

                    $is_path = false;
                }

            }

            if($is_path || $goods_id)$params['goods_description'] = str_replace('/uploads_temp/','/uploads/',$params['goods_description']);//富文本中临时目录替换成正式目录

        }
        $goods = new GoodsModel();
        $data = $goods->goods_save($params,$goods_id);//商品新增修改
        $html = $this->index();
        if(!$data){
            $return_status = false;
            $msg = '添加失败';
        }else{
            foreach ($images_temp as $v){
                unlink($v);//删除临时图片
            }
        }
        return json(array('return_status'=>$return_status,'msg'=>$msg, 'html'=>$html));

    }
    //商品数据删除
    public function goods_delete(){
        $return_status = true;
        $msg = '删除成功';
        $goods_id = input("post.goods_id");
        $goods = new GoodsModel();
        $data = $goods->goods_delete($goods_id);//数据删除
        if(!$data){
            $return_status=false;
            $msg = '删除失败';
        }
        return json(array('return_status'=>$return_status,'msg'=>$msg));
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
        $goods_class = new GoodsModel();
        $data = $goods_class->goods_class_delete($goods_class_id);//数据删除
        if(!$data){
            $return_status=false;
            $msg = '删除失败';
        }
        return json(array('return_status'=>$return_status,'msg'=>$msg));
    }
}

