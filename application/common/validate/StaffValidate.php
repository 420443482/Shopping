<?php
namespace app\common\validate;
use think\Validate;
//商品属性验证器
class StaffValidate extends Validate{
    //检测对应字段
    protected  $rule = [
        ['staff_portrait' ,'require' ,'头像不能为空'],
        ['staff_name', 'require|chs', '姓名不能为空|姓名请输入中文'],
        ['staff_account' ,'require' ,'账户不能为空'],
        ['staff_role' ,'gt:0' ,'请选择角色'],
        ['staff_password' ,'require|confirm:password' ,'密码不能为空|密码输入不一致,请重新输入'],
        ['staff_phone' ,'require'  ,'手机号码不能为空'],
        ['staff_address','require' ,'请输入住址信息'],

    ];
    //验证场景
    protected  $scene = [
        'save' =>  ['staff_portrait','staff_name','staff_role','staff_account','staff_password','staff_phone',
                    'staff_address'],
    ];
}
