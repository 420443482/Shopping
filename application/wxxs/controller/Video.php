<?php
namespace app\wxxs\controller;
use app\common\model\User;
use think\Controller;
use think\Db;

class Video extends Controller
{
   public function video_message(){
       $type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
       if(empty($type))$this->error('请选择视频分类');
       $data = Db::name('video')->where(array('video_type'=>$type))->select();
       echo json_encode(array('code'=>1,'data'=>$data));
       exit;
   }
}

