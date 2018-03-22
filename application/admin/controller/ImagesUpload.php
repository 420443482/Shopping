<?php
namespace app\admin\controller;
use app\common\model\User;
use think\Controller;
use think\Session;
    class ImagesUpload extends Controller
{
    //上传操作
    public function upload()
    {

        $token = input('post.token');
        $timestamp = input('post.timestamp');
        $file = request()->file('Filedata');
        $verifyToken = md5('unique_salt' . $timestamp);
        $data =[
            'return_status' => false,
            'msg'=>'上传失败'
        ];
        if ($file && $token == $verifyToken) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/images');
            $data = array(
                'savename' => $info->getFilename(),//保存名称
                'savepath' => $info->getSaveName(),//保存路径
                'return_status' => true,//状态
            );
        }
        return json($data);
    }
    //删除图片
    public function del(){
//        if (!IS_AJAX) {
//            exit('需要AJAX提交信息');
//        }
        $img_url=input('post.img_url');


//        $img_url=substr($img_url);


        $filename = './'.$img_url;

        //删除
        if(file_exists($filename)){
            $data = [
                'return_status' => true,
                'msg' =>'删除成功',
            ];
            unlink($filename);
        }else{
            $data = [
                'return_status' => false,
                'msg' =>'删除失败:原头像未找到',
            ];
        }
        return json($data);

    }
}

