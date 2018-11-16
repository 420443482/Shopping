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
    public $goods_class_relevance;//商品分类关联表
    public $member_info;//会员信息
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $goods['table_name'] = 'goods_info';
        $goods_assess['table_name'] = 'goods_assess';
        $goods_class_relevance['table_name'] = 'goods_class_relevance';
        $this->goods = new Save($goods);
        $this->goods_assess = new Save($goods_assess);
        $this->goods_class_relevance = new Save($goods_class_relevance);
        $member_id = Session::get('member_id');
        !empty($member_id)?$this->member_info = Db::name('member_info')->where(array('member_id'=>$member_id))->find():$this->member_info = [];
    }

    //显示商品详情页
    public function index()
    {
        $goods_data['where']['goods_id'] = $_REQUEST['goods_id'];
        $goods_list = $this->goods->selectFind($goods_data);
        $goods_images = json_decode($goods_list['goods_images'],true);

        //看了又看的商品展示
        $goods_to5 = Db::name('goods_info')
            ->where('goods_id','IN',function($query) use ($goods_list){
                $class_rel = $this->goods_class_relevance->selectFind(array('where'=>['goods_id'=>$goods_list['goods_id']]));
                $query->table('yr_goods_class_relevance')->where(array('one_class_id'=>$class_rel['one_class_id']))->field('goods_id');
            })
            ->where(array('goods_id'=>array('neq',$_REQUEST['goods_id'])))->limit(5)->field('goods_id,goods_name,goods_summary,goods_images,goods_sales_price')->select();
        //相关分类显示
        $goods_xg_class = Db::name('goods_class')
            ->where('subgrade_class_id','EQ',function($query) use ($goods_list){
                $query->table('yr_goods_class_relevance')->where(array('goods_id'=>$goods_list['goods_id']))->field('two_class_id');
            })
            ->limit(10)->field('goods_class_id,class_name')->select();
        //最新商品显示
        $goods_zx = Db::name('goods_info')->where(array('is_delete'=>0,'goods_id'=>array('neq',$_REQUEST['goods_id'])))->order('goods_grounding_time desc')->limit(3)->select();
        //累计评价
        $goods_assess_count = Db::name('goods_assess')->where(array('goods_id'=>$_REQUEST['goods_id']))->count();
        //显示购物车数量
        if(!empty($this->member_info['member_id'])){
            $cart_count = Db::name('goods_cart')->where(array('member_id'=>$this->member_info['member_id'], 'is_delete'=>0, 'is_purchase'=>0,'is_submit'=>0))->count();
        }else{
            $cart_count = 0;
        }
        $this->assign('cart_count',$cart_count);
        $this->assign('goods_assess_count',$goods_assess_count);
        $this->assign('goods_zx',$goods_zx);
        $this->assign('member_info',$this->member_info);
        $this->assign('goods_to5',$goods_to5);
        $this->assign('goods_list',$goods_list);
        $this->assign('goods_xg_class',$goods_xg_class);
        $this->assign('goods_images',$goods_images);
        return view();
    }
    //加入购物车
    public function goods_cart(){
      if(empty($this->member_info)){
         $code = 1;
         $show_info = file_get_contents(ROOT_PATH.'/public/static/home/cart_html/cart_login.html');
      }else{
         //商品加入购物车
         $goods = json_decode($_REQUEST['goods'],true);
         $cart_data = [
           'goods_id'=> $goods['goods_id'],
           'member_id' => $this->member_info['member_id'],
           'ctime' => time(),
           'number'=>$goods['number']
         ];

         $goods_cart = Db::name('goods_cart')->insertGetId($cart_data);

         if($goods_cart){
             $code = 1;
             //查询该账户购物车的数量与总价
             $cart = Db::name('goods_cart')
                 ->alias('c')
                 ->join('goods_info i','c.goods_id = i.goods_id')
                 ->field('IFNULL(COUNT(*),0) AS count , SUM(i.goods_sales_price*c.number) as price')->where(array('c.is_delete'=>0,'i.is_delete'=>0,'c.is_purchase'=>0,'c.is_submit'=>0))->find();
             //模板显示内容替换
             $show_info = file_get_contents(ROOT_PATH.'/public/static/home/cart_html/show_info.html');
             $show_info =  str_replace('shop_money',$cart['price'],$show_info);
             $show_info = str_replace('count',$cart['count'],$show_info);
         }else{
             $code=0;
             $show_info ='';
         }


      }
      return json(array('show_info'=>$show_info,'code'=>$code));
    }
    //购物车列表
    public function cart_list(){
        //显示购物车数量
        if(!empty($this->member_info['member_id'])){
            $cart_count = Db::name('goods_cart')->where(array('member_id'=>$this->member_info['member_id'], 'is_delete'=>0, 'is_purchase'=>0,'is_submit'=>0))->count();
        }else{
            $cart_count = 0;
        }
        //购物车商品
        $cart_list = Db::name('goods_cart')
            ->alias('c')
            ->join('goods_info i','c.goods_id = i.goods_id')
            ->where(array('c.is_delete'=>0,'i.is_delete'=>0,'c.is_purchase'=>0,'c.is_submit'=>0))->order('c.ctime desc')->select();
        $this->assign('cart_count',$cart_count);
        $this->assign('member_info',$this->member_info);
        $this->assign('cart_list',$cart_list);
        return view();
    }


}

