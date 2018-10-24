<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

use \think\Request;
use think\Config;
use think\Session;
$basename = Request::instance()->root();
if (pathinfo($basename, PATHINFO_EXTENSION) == 'php') {
    $basename = dirname($basename);
}

return [
    // 模板参数替换
    'view_replace_str' => [
        '__ROOT__'   => $basename,
        '__STATIC__' => $basename . '/static/spadmin',
        '__LIB__'    => $basename . '/static/spadmin/lib',

    ],
    //验证配置类 路径
    'validate_path' => APP_PATH . DS . 'admin' . DS,
    'version' => 'v1.0.1',
    'system_name' =>'YR商城后台管理中心',
    'company_name' =>'YR商城后台管理中心',
    'short_company_name' =>'YR商城后台管理中心',
    'company_address'=>'湖南省 长沙市 岳麓区',
    'cache' => [
        // 使用复合缓存类型
        'type' => 'complex',
        // 默认使用的缓存
        'default'   =>  [
            // 驱动方式
            'type'   => 'File',
            // 缓存保存目录
            'path'   => CACHE_PATH,
        ],
    ],
    'session' => [
        'prefix' => 'manage',
        'type' => '',
        'auto_start' => true,
    ],
    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 10,
    ],
    //性别配置
    'sex' =>[
        1=>'男',
        2=>'女'
    ],
    //是否配置
    'is_true'=>[
        0=>'否',
        1=>'是',
    ],
];
