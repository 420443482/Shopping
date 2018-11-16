<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\Request;
use think\Session;
class Payment extends Controller
{
    public $member_info;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $member_id = Session::get('member_id');
        !empty($member_id)?$this->member_info =Db::name('member_info')->where(array('member_id'=>$member_id))->find():$this->member_info = [];
    }
    //购物车订单支付
	public function  payment_order(){
        $cart_id = $_REQUEST['cart_id'];
        $cart_id_array = explode(',',$cart_id);

        if(count($cart_id_array)>0){
            //修改购物车商品状态为，待支付
            Db::name('goods_cart')->where(array('cart_id'=>array('IN',$cart_id_array)))->update(array('is_submit'=>1));
        }

	    //收货地址列表
        $receipt_address_list = Db::name('receipt_address')->where(array('is_delete'=>0,'member_id'=>$this->member_info['member_id']))->order('ctime desc')->select();
        //支付方式列表
        $payment_mode_list = Db::name('payment_mode')->where(array('is_delete'=>0))->select();
        //需要支付的商品
        $cart_list = Db::name('goods_cart')
            ->alias('c')
            ->join('goods_info i','c.goods_id = i.goods_id')
            ->where(array('c.is_delete'=>0,'i.is_delete'=>0,'c.is_purchase'=>0,'c.cart_id'=>array('IN',$cart_id_array)))->order('c.ctime desc')->select();
        //当前收货地址
        $this->assign('cart_id',$cart_id);
        $this->assign('cart_list',$cart_list);
        $this->assign('payment_mode_list',$payment_mode_list);
        $this->assign('receipt_address_list',$receipt_address_list);
        $this->assign('member_info',$this->member_info);
	    return view();
    }
    //新增收货人地址
    public function  address_add(){
       $params['member_id'] = $this->member_info['member_id'];
       $params['province'] = '湖南省';
       $params['city'] = '长沙市';
       $params['area'] = '芙蓉区';
       $params['xx_address'] = $_REQUEST['xx_address'];
       $params['zip_code'] = $_REQUEST['zip_code'];
       $params['receipt_name'] = $_REQUEST['receipt_name'];
       $params['receipt_phone'] = $_REQUEST['receipt_phone'];
       $params['ctime'] = $_REQUEST['ctime'];
       $params['address_bname'] = empty($_REQUEST['address_bname'])?'':$_REQUEST['address_bname'];
        //验证器验证
        $validate = Loader:: validate('AddressValidate');
        if(!$validate->scene('save')->check($params)){
            $this->error($validate->getError());
        }
        $address = Db::name('receipt_address')->insert($params);
        if($address){
            $this->success('新增成功');
        }else{
            $this->error('新增失败');
        }
    }

    //提交订单
    public function submit_order(){
        $address_id = $_REQUEST['address_id'];//收货地址ID
        $payment_mone_id = $_REQUEST['payment_mone_id'];//支付方式ID
        $cart_ids = $_REQUEST['cart_ids'];//购物车商品ID
        $remarkText = $_REQUEST['remarkText'];//订单备注

        if(empty($address_id)){
            $this->error('收货地址不能为空');
        }
        if(empty($payment_mone_id)){
            $this->error('支付方式不能为空');
        }

        switch ($payment_mone_id){
            case '1':
                //货到付款
                $this->error('正在努力开发中');
                break;
            case '1':
                //在线付款
                $this->error('正在努力开发中');
                break;
            default :
                //余额支付
                $this->payment_balance($address_id,$payment_mone_id,$cart_ids,$remarkText);
                break;
        }
    }
    //余额支付
    public function payment_balance($address_id,$payment_mone_id,$cart_ids,$remarkText=''){

            $price = Db::name('goods_cart')
            ->alias('c')
            ->join('goods_info i','c.goods_id = i.goods_id')
            ->where(array('c.is_delete'=>0,'i.is_delete'=>0,'c.is_purchase'=>0,'c.cart_id'=>array('IN',$cart_ids)))->field('IFNULL(SUM(c.number*i.goods_sales_price),0) as price')->find();

            if($price['price'] > $this->member_info['member_balance']){
                $this->error('您的余额不足，请充值');
            }
            $params['order_code'] = date('Ymd',time()).rand(1000,9999).rand(1000,999);
            $params['goods_ids'] = $cart_ids;
            $params['member_id'] = $this->member_info['member_id'];
            $params['payment_price'] = $price['price'];
            $params['payment_mone_id'] = $payment_mone_id;
            $params['address_id'] = $address_id;
            $params['Remarks'] = $remarkText;
            $params['ctime'] = time();

            $order_id = Db::name('goods_order')->insertGetId($params);
            try{
                if($order_id){
                Db::name('goods_order')->where(array('order_id'=>$order_id))->update(array('is_payment'=>1));
                }
                $money = $this->member_info['member_balance']-$price['price'];
                Db::name('member_info')->where(array('member_id'=>$this->member_info['member_id']))->update(array('member_balance'=>$money));
                $code = true;
                // 提交事务
                Db::commit();

            } catch (\Exception $e) {
                $code = false;
                // 回滚事务
                Db::rollback();

            }

            if($code){
                $this->success('提交成功');
            }else{
                $this->error('提交失败');
            }
    }
    //支付成功页面
    public function payment_success(){
        return view();
    }
    //支付失败页面
    public function payment_error(){
        return view();
    }
}

