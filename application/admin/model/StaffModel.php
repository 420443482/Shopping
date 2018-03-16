<?php
namespace app\admin\model;
use app\common\model\StaffInfo;
use think\Model;
use think\Session;

class StaffModel extends Model{
    //员工数据列表显示
    public function staff($where, $list_page, $list_num){
        $options=[
            'page'=>$list_page,
        ];
        $staff = new StaffInfo();
        // 查询状态为1的用户数据 并且每页显示10条数据
        $list = $staff->where($where)->order('staff_id', 'desc')->paginate($list_num,false,$options);
        return $list;
    }
    //账号添加和编辑
    public function register($params){
        $staff = new StaffInfo;

        $data = [
            'staff_account'  => $params['staff_account'],
            'staff_name'     => $params['staff_name'],
            'staff_password' => $params['staff_password'],
            'staff_code'     => $params['staff_code'],
            'staff_phone'    => $params['staff_phone'],
            'staff_address'  => $params['staff_address'],
            'staff_portrait' => $params['staff_portrait'],
            'staff_sex'      => $params['staff_sex'],
            'staff_wx'       => $params['staff_wx'],
            'staff_qq'       => $params['staff_qq'],
            'staff_update_time' => date('Y-m-d H:i:s',time()),
        ];
        if(!empty($params['staff_id'])){
            $staff_id =   $staff->where(array('staff_id'=>$params['staff_id']))->update($data);
        }else{
            $staff_id = $staff->insert($data);
        }
        return $staff_id;
    }
    //员工数据删除(修改删除状态判定是否删除)
    public function staff_delete($id){
        $staff = new StaffInfo();
        $delete = $staff->where(array('staff_id'=>$id))->update(['staff_delete_status' => 1]);
      
        return $delete;
    }
    //根据员工ID条件查询信息
    public function staff_list_one($staff_id){
        $staff = new StaffInfo();
        $data = $staff->where(array('staff_id'=>$staff_id,'staff_delete_status'=>0))->find();
        return $data;
    }
}