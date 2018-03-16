<?php
namespace app\admin\model;
use app\common\model\GoodsClass;
use think\Model;
use think\Session;

class GoodsModel extends Model{
    //商品分类修改
    public function goods_class_save($params){
        $is_display   = empty($params['is_display'])?1:0;
        $is_recommend = empty($params['is_recommend'])?1:0;

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
}