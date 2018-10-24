<?php
namespace app\spadmin\controller;


use think\Controller;
use think\Db;

class Index  extends Base
{
    public function index()
    {
        return view('index');
    }

    //桌面
    public function desktop(){

        return view('desktop');
    }

}

