<?php
namespace app\common\controller;


use think\Controller;
use think\Request;

class Upload  extends Controller
{
    public $images_upload;//文件库
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $images['table_name'] = 'images_upload';
        $this->images_upload = new Save($images);
    }

    //上传操作
    public function upload($class = 'default')
    {
        $file = request()->file('Filedata');
        $result =[
            'return_status' => false,
            'msg'=>'上传失败'
        ];

        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'spadmin/'.$class.'/images');
            $result = array(
                'savename' => $info->getFilename(),//保存名称
                'savepath' => $info->getSaveName(),//保存路径
                'return_status' => true,//状态
            );
            $path = '/spadmin/'.$class.'/images/'.$result['savepath'];
            $data['data']['images_adress'] = $path;
            $data['data']['class'] = $class;
            $this->images_upload->add($data);
        }

        return $result;
    }
    //多图上传
    public function map_upload($class = 'default'){
        $ima_type = substr($_FILES['file']['type'],6);
        $imgname = md5(uniqid(rand())).'.'.$ima_type;
        $tmp = $_FILES['file']['tmp_name'];
        $date = date('Ymd',time());
        $filepath = ROOT_PATH . 'public' . DS . 'spadmin/'.$class.'/images/'.$date.'/';
        $this->createDir($filepath);
        move_uploaded_file($tmp,$filepath.$imgname);
        $path = '/spadmin/'.$class.'/images/'.$date.'/'.$imgname;
        $data['data']['images_adress'] = $path;
        $data['data']['class'] = $class;
        $this->images_upload->add($data);
        return array('code'=>1,'data'=>$path);
    }
    //创建多个目录
    public function createDir($path){

        if (!file_exists($path)){
            $this->createDir(dirname($path));
            mkdir($path, 0777);
        }

    }
}

