<?php
namespace app\admin\model;
use app\common\model\GoodsClass;
use app\common\model\GoodsClassRelevance;
use app\common\model\GoodsInfo;
use think\Model;
use think\Session;

class GoodsModel extends Model{
    //商品分类新增修改
    public function goods_class_save($params){
        $is_display   = empty($params['is_display'])?0:1;
        $is_recommend = empty($params['is_recommend'])?0:1;

        $data = [
        'class_name' => $params['class_name'],
        'child_class_id' => $params['child_class_one'],
        'subgrade_class_id' => $params['child_class_two'],
        'is_display' => $is_display,
        'is_recommend' => $is_recommend,
        'goods_sort' => $params['goods_sort'],
        'goods_update_time' => date('Y-m-d H:i:s',time()),
        ];

        $goods_class = new GoodsClass();
        if(!empty($params['goods_class_id'])){

            $list = $goods_class->where(array('goods_class_id'=>$params['goods_class_id']))->update($data);
        }else{
            $list = $goods_class->insert($data);
        }
        return $list;
    }
    //商品分类数据删除(修改删除状态判定是否删除)
    public function goods_class_delete($goods_class_id){
        $goods_class = new GoodsClass();
        $delete = $goods_class->where(array('goods_class_id'=>$goods_class_id))->whereOr(array('child_class_id'=>$goods_class_id))->update(['is_delete' => 1]);
        return $delete;
    }


    //商品新增修改
    public function goods_save($params){
        $params['goods_is_shipping'] = empty($params['goods_is_shipping'])?0:1;
        $params['goods_is_discount'] = empty($params['goods_is_discount'])?0:1;
        $params['goods_is_grounding'] = empty($params['goods_is_grounding'])?0:1;
        $params['goods_grounding_time'] = strtotime($params['goods_grounding_time']);
        $params['goods_undercarriage_time'] = strtotime($params['goods_undercarriage_time']);
        $params['staff_id'] = session('staff_id');
        $relevance['one_class_id'] = $params['one_class_id'];
        $relevance['two_class_id'] = $params['two_class_id'];
        $relevance['three_class_id'] = $params['three_class_id'];
        $params = array_diff_key($params,$relevance);
        if(count($params['goods_images'])>0)$params['goods_images'] = json_encode($params['goods_images']);
        $goods = new GoodsInfo();
        $data = $goods->insertGetId($params);

        if($data){
            $relevance['goods_id'] = $data;
            $goodsClassRelevance = new GoodsClassRelevance();
            $data = $goodsClassRelevance->insertGetId($relevance);
        }
        return $data;
    }
    //商品显示数据
    public function goods_index($where, $list_page, $list_num){
        $options=[
            'page'=>$list_page,
        ];
        $goods = new GoodsInfo();
        // 查询状态为1的用户数据 并且每页显示10条数据
        $list = $goods->where($where)->order('goods_id', 'desc')->paginate($list_num,false,$options);

        return $list;
    }
}