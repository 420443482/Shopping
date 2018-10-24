<?php
namespace app\spadmin\controller;


use app\common\Controller\Save;
use think\Controller;
use think\Db;
use think\Request;

class Member  extends Base
{
    /*
     *member_info 会员信息表名
     *  member_phone 会员手机
     *  member_name  会员姓名
     *
     *member_level 会员等级表名
     *
     * */
    public $member;//会员信息对象
    public $member_level;//会员等级对象

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $member_table['table_name'] = 'member_info';
        $member_level_table['table_name'] = 'member_level';
        $this->member = new Save($member_table);
        $this->member_level = new Save($member_level_table);
    }

    //会员列表
    public function member_list()
    {
        list($font) = array([]);
        $phone = $_REQUEST['phone'];//电话
        $name = $_REQUEST['name'];//姓名
        $level = $_REQUEST['level'];//等级
        if(!empty($phone)){
            $data['where']['member_phone'] = array('like','%'.$phone.'%');
            $font['phone'] = $phone;
        }
        if(!empty($name)){
            $data['where']['member_name'] = array('like','%'.$name.'%');
            $font['name'] = $name;
        }
        if(!empty($level)){
            $data['where']['member_level'] = $level;
            $font['level'] = $level;
        }
        $level_name = member_level_name();
        $data['where']['is_delete'] = 0;
        $list= $this->member->select($data);
        $this->assign("page", $list->render());
        $this->assign("count",$list->count());
        $this->assign('list',$list);
        $this->assign('where',$font);
        $this->assign('level_name',$level_name);
        return view();
    }
    //删除会员操作
    public function member_delete(){
        $data['where']['member_id'] = $_REQUEST['member_id'];
        $data['data']['is_delete'] = 1;
        $result = $this->member->edit($data);
        if($result){
            $this->success('删除成功');
        }else{
            $this->success('删除失败');
        }
    }


    //会员等级
    public function member_level_list(){
        $list= $this->member_level->select();
        $this->assign('list',$list);
        $this->assign("page", $list->render());
        $this->assign("count",$list->count());
        return view();
    }
}

