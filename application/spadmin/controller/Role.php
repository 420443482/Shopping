<?php
namespace app\spadmin\controller;


use app\common\Controller\Save;
use think\Controller;
use think\Db;
use think\Request;

class Role  extends Base
{
    public $role;//角色库
    public $role_powe;//权限栏目表
    public $powe_del_id = [];//栏目无限极删除ID存储
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $role_table['table_name'] = 'role';
        $role_powe_table['table_name'] = 'role_powe';
        $this->role = new Save($role_table);
        $this->role_powe = new Save($role_powe_table);
    }
    //角色列表
    public function role_list()
    {
        $font = [];
        if(isset($_REQUEST['role_name'])){
            $data['where']['role_name'] = array('like','%'.$_REQUEST['role_name'].'%');
            $font['role_name'] = $_REQUEST['role_name'];
        }
        $data['where']['is_delete'] = 0;
        $list = $this->role->select($data);
        $this->assign("page", $list->render());
        $this->assign("count",$list->count());
        $this->assign('list',$list);
        $this->assign('where',$font);
        return view();
    }
    //角色参数列表
    public function role_parameter_list(){
        $params['role_powe_ids'] = isset($_REQUEST['role_powe_ids'])?$_REQUEST['role_powe_ids']:[];
        $params['role_name']     = isset($_REQUEST['role_name'])?$_REQUEST['role_name']:'';
        $params['role_remark']   = isset($_REQUEST['role_remark'])?$_REQUEST['role_remark']:'';
        if(count($params['role_powe_ids']) <= 0){
            $this->error('请至少选择一个权限');
        }else{
            $params['role_powe_ids'] = implode(',',$params['role_powe_ids']);
        }
        if(empty($params['role_name'])){
            $this->error('请输入角色名称');
        }
        $is_where['where']['role_name'] = $_REQUEST['role_name'];
        if(isset($_REQUEST['role_id'])){
            $is_where['where']['role_id'] = array('neq',$_REQUEST['role_id']);
        }
        $is_name = $this->role->selectFind($is_where);
        if($is_name){
            $this->error('该角色名称已存在');
        }
        return $params;
    }
    //新增角色
    public function role_add(){
        if($this->request->isAjax()){
            $data['data'] = $this->role_parameter_list();
            $result = $this->role->add($data);
            if($result){
                $this->success('新增成功');
            }else{
                $this->error('新增失败');
            }
        }else{
            //权限栏目查询
            $data['where']['is_delete'] = 0;
            $list = $this->role_powe->selectAll($data);
            $column = [];
            //权限栏目分组显示
            foreach ($list  as $v){
                $column[$v['powe_pid']][] = [
                        'powe_id' => $v['powe_id'],
                        'powe_name'=> $v['powe_name']
                    ];
            }
            $this->assign('column',$column);
        }
        return view('role_edit');
    }
    //删除角色
    public function role_delete(){

        $data['where']['role_id'] = $_REQUEST['role_id'];
        $data['data']['is_delete'] = 1;
        $result = $this->role->edit($data);
        if($result){
            $this->success('删除成功');
        }else{
            $this->success('删除失败');
        }
    }
    //编辑角色
    public function role_edit(){
        if($this->request->isAjax()){
            $data['where']['role_id'] = $_REQUEST['role_id'];
            $data['data'] = $this->role_parameter_list();
            $result = $this->role->edit($data);
            if($result){
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败');
            }
        }else{
            $role['where']['role_id'] = $_REQUEST['role_id'];
            $role_list = $this->role->selectFind($role);
            $in_role_ids = explode(',',$role_list['role_powe_ids']);
            //权限栏目查询
            $data['where']['is_delete'] = 0;
            $list = $this->role_powe->selectAll($data);
            $column = [];
            //权限栏目分组显示
            foreach ($list  as $v){
                $column[$v['powe_pid']][] = [
                    'powe_id' => $v['powe_id'],
                    'powe_name'=> $v['powe_name']
                ];
            }
            $this->assign('list',$role_list);
            $this->assign('column',$column);
            $this->assign('in_role_ids',$in_role_ids);

        }
        return view('role_edit');
    }
    //栏目参数
    public function role_powe_parameter_list()
    {
        $pid_one = isset($_REQUEST['pid_one'])?$_REQUEST['pid_one']:0;
        $pid_two = isset($_REQUEST['pid_two'])?$_REQUEST['pid_two']:0;
        $params['powe_name'] = isset($_REQUEST['powe_name'])?$_REQUEST['powe_name']:'';
        $params['powe_url'] = isset($_REQUEST['powe_url'])?$_REQUEST['powe_url']:'';
        if(empty($params['powe_name']) || empty($params['powe_url'])){
            $this->error('名称和功能不能为空');
        }
        if($pid_one !=0 && $pid_two !=0){
            $params['powe_pid'] = $pid_two;
        }elseif($pid_one !=0 && $pid_two == 0){
            $params['powe_pid'] = $pid_one;
        }else{
            $params['powe_pid'] = 0;
        }
        return $params;
    }
    //栏目列表
    public function role_powe_list()
    {
        $font = [];
        if (isset($_REQUEST['powe_name'])){
            $data['where']['powe_name'] = array('like','%'.$_REQUEST['powe_name'].'%');
            $font['powe_name'] = $_REQUEST['powe_name'];
        }
        $data['where']['is_delete'] = 0;
        if(isset($_REQUEST['pid'])){
            $data['where']['powe_pid'] = $_REQUEST['pid'];
            $find['where']['powe_id'] = $_REQUEST['pid'];
            $role_powe_find =  $this->role_powe->selectFind($find);
            $this->assign('role_powe_find',$role_powe_find);
            $font['pid'] = $_REQUEST['pid'];
        }else{
            $data['where']['powe_pid'] = 0;
            $font['pid'] = 0;
        }

        $list = $this->role_powe->select($data);
        $this->assign('list',$list);
        $this->assign("page", $list->render());
        $this->assign("count",$list->count());
        $this->assign("where",$font);

        return view();
    }
    //新增栏目
    public function role_powe_add(){
        if($this->request->isAjax()){
            $data['data'] = $this->role_powe_parameter_list();
            $result = $this->role_powe->add($data);
            if($result){
                $this->success('新增栏目成功');
            }else{
                $this->error('新增栏目失败');
            }
        }
        return view('role_powe_edit');
    }
    //编辑栏目
    public function role_powe_edit(){
        if($this->request->isAjax()){
            $data['where']['powe_id'] = $_REQUEST['powe_id'];
            $data['data'] = $this->role_powe_parameter_list();
            $result = $this->role_powe->edit($data);
            if($result){
                $this->success('编辑栏目成功');
            }else{
                $this->error('编辑栏目失败');
            }
        }else{
            $data['where']['powe_id'] = $_REQUEST['powe_id'];
            $result = $this->role_powe->selectFind($data);
            if($result['powe_pid'] == 0){
                $result['pid_one'] = 0;
                $result['pid_two'] = 0;
            }else{
                $result_2 = $this->role_powe->selectFind(array('where'=>['powe_id'=>$result['powe_pid']]));
                if($result_2['powe_pid'] == 0){
                    $result['pid_one'] = $result_2['powe_id'];
                    $result['pid_two'] = 0;
                }else{
                    $result_3 = $this->role_powe->selectFind(array('where'=>['powe_id'=>$result_2['powe_pid']]));
                    $result['pid_one'] = $result_3['powe_id'];
                    $result['pid_two'] = $result_2['powe_id'];
                }
            }
            $role_powe_name = role_powe_name();
            $this->assign('list',$result);
            $this->assign('role_powe_name',$role_powe_name);
        }
        return view();
    }
    //删除栏目(要做成无限极分类删除的简易操作)
    public function role_powe_delete(){
        $powe_id = $_REQUEST['powe_id'];
        $this->dg($powe_id);
        if(count($this->powe_del_id) > 0){
            array_unshift($this->powe_del_id,$powe_id);

        }else{
            $this->powe_del_id = $powe_id;
        }

        $data['where']['powe_id'] = array('IN',$this->powe_del_id);
        $data['data']['is_delete'] = 1;
        $result = $this->role_powe->edit($data);
        if($result){
            $this->success('删除栏目成功');
        }else{
            $this->error('删除栏目失败');
        }

    }
    //无限极父子关系删除
    public function dg($powe_id){
        if(is_array($powe_id))
        $this->powe_del_id = array_merge($this->powe_del_id,$powe_id);
        $array = [];
        $data['where']['powe_pid'] = array('IN',$powe_id);
        $powe = $this->role_powe->selectAll($data);
        if(!empty($powe)){
            foreach ($powe as $v){
                $array[] = $v['powe_id'];
            }
            $this->dg($array);
        }
    }

    //加载栏目下拉菜单
    public function role_powe_spinner(){
        $data['where']['powe_pid'] = $_REQUEST['pid'];
        $result = $this->role_powe->selectAll($data);
        $this->success('','',$result);
    }
}

