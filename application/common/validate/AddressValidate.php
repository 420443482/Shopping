<?php
namespace app\common\validate;
use think\Validate;
//商品属性验证器
class AddressValidate extends Validate{
    protected  $rule = [
        ['province' ,'require' ,'省份不能为空'],
        ['city', 'require', '市不能为空'],
        ['area' ,'require' ,'区不能为空'],
        ['zip_code' ,'require' ,'邮编不能为空'],
        ['xx_address' ,'require' ,'详细地址不能为空'],
        ['receipt_name' ,'require' ,'收货名称不能为空'],
        ['receipt_phone' ,'require'  ,'手机号码不能为空'],

    ];
    //验证场景
    protected  $scene = [
        'save' =>  ['province','city','staff_role','area','xx_address','receipt_name',
            'zip_code','receipt_phone'],
    ];
}
