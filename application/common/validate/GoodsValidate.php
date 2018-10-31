<?php
namespace app\common\validate;
use think\Validate;
//商品属性验证器
class GoodsValidate extends Validate{
    //检测对应字段
    protected  $rule = [
        ['goods_name', 'require|max:50', '商品名称不能为空|商品名称最多不能超过25个字符'],
        ['goods_summary' ,'require' ,'商品描述不能为空'],
        ['one_class_id' ,'gt:0' ,'请选择商品分类'],
        ['two_class_id' ,'gt:0' ,'请选择下级商品分类'],
        ['three_class_id' ,'gt:0' ,'请选择最下级商品分类'],
        ['goods_market_price' ,'number'  ,'请输入数字(市场价)'],
        ['goods_buying_price','number' ,'请输入数字(进货价)'],
        ['goods_sales_price', 'number' ,'请输入数字(销售价)'],
        ['goods_stock', 'number' ,'请输入数字(库存)'],

    ];
//    //提示信息
//    protected    $msg = [
//        'goods_name.require'    => '商品名称不能为空',
//        'goods_name.max'        => '商品名称最多不能超过25个字符',
//        'goods_summary.require' => '商品描述不能为空',
//        'child_class_one.gt'    => '请选择商品分类',
//        'goods_market_price.number' => '请输入数字(市场价)',
//        'goods_buying_price.number' => '请输入数字(进货价)',
//        'goods_sales_price.number'  => '请输入数字(销售价)',
//    ];
    //验证场景 add 新增 edit 修改
    protected  $scene = [
        'save' =>  ['goods_name','goods_summary','one_class_id','two_class_id','three_class_id','goods_market_price',
                    'goods_buying_price','goods_sales_price','goods_stock','goods_images'],

    ];
}
