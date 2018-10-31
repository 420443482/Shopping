<?php
namespace app\home\controller;
use app\common\Controller\Save;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
class Details extends Controller
{
    public $goods;//商品表
    public $goods_assess;//商品评价表
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $goods['table_name'] = 'goods_info';
        $goods_assess['table_name'] = 'goods_assess';
        $this->goods = new Save($goods);
        $this->goods_assess = new Save($goods_assess);

    }

    //显示商品详情页
    public function index()
    {
        $goods_data['where']['goods_id'] = $_REQUEST['goods_id'];
        $goods_list = $this->goods->selectFind($goods_data);
        $goods_images = json_decode($goods_list['goods_images'],true);
        $this->assign('goods_list',$goods_list);
        $this->assign('goods_images',$goods_images);
        return view();
    }

}

