<?php
namespace app\admin\model;
use app\common\model\ImagesUpload;
use think\Model;
use think\Session;

class ImagesUploadModel extends Model{
    //图片库添加
    public function images_add($path){
        $images = new ImagesUpload();
        $data = [
          'images_adress' => $path,
        ];

        $images_id = $images->insert($data);
        return $images_id;
    }
}