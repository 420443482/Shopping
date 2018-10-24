<?php
/**
 * Created by bingo on 2017/3/22 525945448@qq.com.
 */


namespace app\spadmin\controller;

use app\index\lib\passwd;
use think\Controller;
use think\Db;
use think\Loader;
use think\Session;

class Login extends Controller
{
    //用户后台登录
    public function login(){
        $token = $this->request->token('__token__', 'sha1');
        if($this->request->isPost()){
            $account = $_REQUEST['staff_account'];
            $pwd = $_REQUEST['staff_password'];

            $admin_account = Db::name('staff_info')->where(array('staff_account'=>$account,'staff_password'=>md5($pwd)))->find();
            if($admin_account){
                Session::set('pass',1);
                Session::set('staff_id',$admin_account['staff_id']);
                $this->success('成功',url('/spadmin/index/index','','',true));
            }else{
                $this->error('账号或密码错误');
            }
        }
        $this->assign('token', $token);
        return view( 'index' );
    }
    //修改密码
    public function editPass(){
        $controller = 'admin_user';
        if($this->request->isAjax()){
            $data = $this->request->param();
            // 验证
            try{
                $validate =Loader::validate($controller);
                if (!$validate->check($data)) {
                    return ajax_return_adv_error($validate->getError());
                }
            }catch (\Exception $e){
                //不捕获异常
            }
            // 简单的直接使用db更新
            Db::startTrans();
            $model = Db::name($controller);
            try {
                //检测帐号密码是否正确
                $check = $model->where(['username' => Session::get('user.username') , 'password' => passwd($data['old_pass']) ])->find();
                if($check){
                    //检测输入密码是否相同
                    if($data['new_pass'] === $data['new_pass_re']){
                        $model->where('username', $check['username'])->update(['password' => passwd($data['new_pass'])]);
                        // 提交事务
                        Db::commit();
                    }else{
                        return ajax_return_error('两次输入的密码不一致，修改失败。');
                    }
                }else{
                    return ajax_return_error('旧密码验证不通过，修改失败。');
                }

            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();

                return ajax_return_adv_error($e->getMessage());
            }
            return ajax_return_adv("修改成功", false , false, false ,url('index'));
        }
        return view('editpass');
    }

    //推出登录
    public function logout(){
        Session::start();
        Session::destroy();
        $this->redirect(url('/spadmin'));
    }
}