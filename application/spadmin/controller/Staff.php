<?php
namespace app\spadmin\controller;


use app\common\controller\Save;
use app\common\controller\Upload;
use think\Config;
use think\Db;
use think\Loader;

class Staff  extends Base
{
    /***
     * 员工信息
     * staff_portrait 头像
     * staff_name     姓名
     * staff_account  账号
     * staff_password 密码
     * staff_address  住址
     * staff_sex      性别
     * staff_wx       微信
     * staff_qq       QQ
     * staff_code     编号
     * */
    public $staff;//员工表
    public $images_upload;//图片库
    public function  __construct()
    {
        parent::__construct();
        $data['table_name'] = 'staff_info';
        $data['order'] = 'staff_id desc';
        $img_data['table_name'] = 'images_upload';
        $this->staff =new Save($data);
        $this->images_upload = new Save($img_data);
    }

    //员工列表信息
    public function staff_list()
    {
         $font = [];
         $phone = $_REQUEST['phone'];//电话
         $name = $_REQUEST['name'];//姓名
         $code = $_REQUEST['code'];//编号
         if(!empty($phone)){
             $data['where']['staff_phone'] = array('like','%'.$phone.'%');
             $font['phone'] = $phone;
         }
         if(!empty($name)){
             $data['where']['staff_name'] = array('like','%'.$name.'%');
             $font['name'] = $name;
         }
        if(!empty($code)){
            $data['where']['staff_code'] = array('like','%'.$code.'%');
            $font['code'] = $code;
        }
         $data['where']['staff_delete_status'] = 0;
         $list = $this->staff->select($data);
         $this->assign("page", $list->render());
         $this->assign("count",$list->count());
         $this->assign('list',$list);
         $this->assign('where',$font);
         return view();
    }
    //员工参数验证
    public function staff_parameter_list(){
        list($ver_staff_where) = array([]);
        $params['staff_portrait'] = $_REQUEST['upload_img'];
        $params['staff_name'] = $_REQUEST['staff_name'];
        $params['staff_account'] = $_REQUEST['staff_account'];
        $params['staff_phone'] = $_REQUEST['staff_phone'];
        $params['staff_password'] = $_REQUEST['staff_password'];
        $params['password'] = $_REQUEST['password'];
        $params['staff_address'] = $_REQUEST['staff_address'];
        $params['staff_sex'] = $_REQUEST['staff_sex'];
        $params['staff_wx'] = $_REQUEST['staff_wx'];
        $params['staff_qq'] = $_REQUEST['staff_qq'];
        $params['staff_code'] = date('Ymd').rand(1000,9999);
        $params['staff_update_time'] = date('Y-m-d H:i:s');
        //验证器验证
        $validate = Loader:: validate('StaffValidate');
        if(!$validate->scene('save')->check($params)){
            $this->error($validate->getError());
        }
        $ver_phone = preg_phone($params['staff_phone']);
        if(empty($ver_phone)){
            $this->error('手机号码格式不正确');
        }

        //验证手机号或账号是否已经存在
        if(!empty($_REQUEST['staff_id'])){
            $ver_staff_where['staff_id'] = array('neq',$_REQUEST['staff_id']);
        }

        $ver_staff_with['staff_account'] = $params['staff_account'];
        $ver_staff_with['staff_phone'] = $params['staff_phone'];

        $verification = Db::name('staff_info')->where($ver_staff_where)
            ->where("staff_account=:staff_account OR staff_phone=:staff_phone",
                ['staff_account' => $ver_staff_with['staff_account'], 'staff_phone' => $ver_staff_with['staff_phone']])->find();

        if($verification){
            $this->error('手机号或账号已经存在');
        }
        unset($params['password']);//去除重复密码字段
        //正常操作
        $params['staff_password'] = md5($params['staff_password']);
        return $params;
    }
    //新增员工信息
    public function staff_add(){
        if($this->request->isAjax()) {
            $data['data'] = $this->staff_parameter_list();
            $result = $this->staff->add($data);
            $msg = '新增';
            if($result){
                $img_data['where']['images_adress'] = $data['data']['staff_portrait'];
                $img_data['data']['status'] = 1;
                $this->images_upload->edit($img_data);
                $this->success($msg.'成功');
            }else{
                $this->error($msg.'失败');
            }
        }
        return view('/staff/staff_edit');
    }
    //编辑员工信息
    public function staff_edit(){
        if($this->request->isAjax()){
            $staff_where['where']['staff_id'] = $_REQUEST['staff_id'];
            $staff_portrait = $this->staff->selectFind($staff_where); //获取当前使用图片
            $img_data['where']['images_adress'] = $staff_portrait['staff_portrait'];
            $img_data['data']['status'] = 0;
            $this->images_upload->edit($img_data);

            $data['data'] = $this->staff_parameter_list();
            $data['where']['staff_id'] = $_REQUEST['staff_id'];
            $result = $this->staff->edit($data);
            $msg = '编辑';
            if($result){
                $img_data['where']['images_adress'] = $data['data']['staff_portrait'];
                $img_data['data']['status'] = 1;
                $this->images_upload->edit($img_data);
                $this->success($msg.'成功');
            }else{
                $this->error($msg.'失败');
            }
        }else{
            $staff_id = $_REQUEST['staff_id'];
            $data['where']['staff_id']= $staff_id;
            $result = $this->staff->selectFind($data);
            $this->assign('val',$result);
            $this->assign('staff_id',$staff_id);
        }
        return view();
    }
    //删除员工操作
    public function staff_delete(){
        $data['where']['staff_id'] = $_REQUEST['staff_id'];
        $data['data']['staff_delete_status'] = 1;
        $result = $this->staff->edit($data);
        if($result){
            $this->success('删除成功');
        }else{
            $this->success('删除失败');
        }
    }
    //文件上传
    public function  staff_upload(){
        $class = 'staff';
        $upload = new Upload();
        $data = $upload->upload($class);
        return json($data);
    }
}

