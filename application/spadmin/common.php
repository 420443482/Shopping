<?php
/**
 * Created by bingo on 2017/2/7 525945448@qq.com.
 */
//------------------------
// 公共函数
//-------------------------

use think\Session;
use think\Response;
use think\Request;
use think\Url;
use think\Cache;
use think\Db;
use think\Config;
/**
 * CURLFILE 兼容性处理 php < 5.5
 * 一定不要修改、删除，否则 curl 可能无法上传文件
 */
if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '')
    {
        return "@$filename;filename="
        . ($postname ?: basename($filename))
        . ($mimetype ? ";type=$mimetype" : '');
    }
}

/**
 * flash message
 *
 * flash("?KEY") 判断是否存在flash message KEY 返回bool值
 * flash("KEY") 获取flash message，存在返回具体值，不存在返回null
 * flash("KEY","VALUE") 设置flash message
 * @param string $key
 * @param bool|string $value
 * @return bool|mixed|null
 */
function flash($key, $value = false)
{
    $prefix = 'flash_';
    // 判断是否存在flash message
    if ('?' == substr($key, 0, 1)) {
        return Session::has($prefix . substr($key, 1));
    } else {
        $flash_key = $prefix . $key;
        if (false === $value) {
            // 获取flash
            $ret = Session::pull($flash_key);

            return null === $ret ? null : unserialize($ret);
        } else {
            // 设置flash
            return Session::set($flash_key, serialize($value));
        }
    }
}

/**
 * 表格排序筛选
 * @param string $name  单元格名称
 * @param string $field 排序字段
 * @return string
 */
function sort_by($name, $field = '')
{
    $sort = Request::instance()->param('_sort');
    $param = Request::instance()->get();
    $param['_sort'] = ($sort == 'asc' ? 'desc' : 'asc');
    $param['_order'] = $field;
    $url = Url::build(Request::instance()->action(), $param);

    return Request::instance()->param('_order') == $field ?
        "<a href='{$url}' title='点击排序' class='sorting-box sorting-{$sort}'>{$name}</a>" :
        "<a href='{$url}' title='点击排序' class='sorting-box sorting'>{$name}</a>";
}

/**
 * 用于高亮搜索关键词
 * @param string $string 原文本
 * @param string $needle 关键词
 * @param string $class  span标签class名
 * @return mixed
 */
function high_light($string, $needle = '', $class = 'c-red')
{
    return $needle !== '' ? str_replace($needle, "<span class='{$class}'>" . $needle . "</span>", $string) : $string;
}

/**
 * 用于显示状态操作按钮
 * @param int $status        0|1|-1状态
 * @param int $id            对象id
 * @param string $field      字段，默认id
 * @param string $controller 默认当前控制器
 * @return string
 */
function show_status($status, $id, $field = 'id', $controller = '')
{
    $controller === '' && $controller = Request::instance()->controller();
    switch ($status) {
        // 恢复
        case 0 :
            $ret = '<a href="javascript:;" onclick="ajax_req(\'' . Url::build($controller . '/resume', [$field => $id]) . '\',{},change_status,[this,\'resume\'])" class="label label-success radius" title="点击恢复">恢复</a>';
            break;
        // 禁用
        case 1 :
            $ret = '<a href="javascript:;" onclick="ajax_req(\'' . Url::build($controller . '/forbid', [$field => $id]) . '\',{},change_status,[this,\'forbid\'])" class="label label-warning radius" title="点击禁用">禁用</a>';
            break;
        // 还原
        case -1 :
            $ret = '<a href="javascript:;" onclick="ajax_req(\'' . Url::build($controller . '/recycle', [$field => $id]) . '\')" class="label label-secondary radius" title="点击还原">还原</a>';
            break;
    }

    return $ret;
}

/**
 * 显示状态
 * @param int $status     0|1|-1
 * @param bool $imageShow true只显示图标|false只显示文字
 * @return string
 */
function get_status($status, $imageShow = true)
{
    switch ($status) {
        case 0 :
            $showText = '禁用';
            $showImg = '<i class="Hui-iconfont c-warning status" title="禁用">&#xe631;</i>';
            break;
        case -1 :
            $showText = '删除';
            $showImg = '<i class="Hui-iconfont c-danger status" title="删除">&#xe6e2;</i>';
            break;
        case 1 :
        default :
            $showText = '正常';
            $showImg = '<i class="Hui-iconfont c-success status" title="正常">&#xe615;</i>';

    }

    return ($imageShow === true) ? $showImg : $showText;
}


/**
 * 从二维数组中取出自己要的KEY值
 * @param  array $arrData
 * @param string $key
 * @param $im true 返回逗号分隔
 * @return array
 */
function filter_value($arrData, $key, $im = false)
{
    $re = [];
    foreach ($arrData as $k => $v) {
        if (isset($v[$key])) $re[] = $v[$key];
    }
    if (!empty($re)) {
        $re = array_flip(array_flip($re));
        sort($re);
    }

    return $im ? implode(',', $re) : $re;
}

/**
 * 重设键，转为array(key=>array())
 * @param array $arr
 * @param string $key
 * @return array
 */
function reset_by_key($arr, $key)
{
    $re = [];
    foreach ($arr as $v) {
        $re[$v[$key]] = $v;
    }

    return $re;
}

/**
 * 节点遍历
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param int $root
 * @return array
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
    // 创建Tree
    $tree = [];
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = [];
        $tree_deep = [];
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            } else {
                if (isset($refer[$parentId])) {

                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}
/**
 * 节点遍历形成select
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param int $root
 * @return array
 */
function list_to_tree_select($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0 , $selected = 0 ,$name = 'select' ,$val = 'id' , $text ='name' )
{
    // 创建Tree
    '<select class="select" size="1" name="'.$name.'">';
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = [];
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}


/**
 * 统一密码加密方式，如需变动直接修改此处
 * @param $password
 * @return string
 */
function password_hash_tp($password)
{
    return hash("md5", trim($password));
}

/**
 * 生成随机字符串
 * @param string $prefix
 * @return string
 */
function get_random($prefix = '')
{
    return $prefix . base_convert(time() * 1000, 10, 36) . "_" . base_convert(microtime(), 10, 36) . uniqid();
}

/**
 * 获取自定义配置
 * @param string|int $name 配置项的key或者value，传key返回value，传value返回key
 * @param string $conf
 * @param bool $key        传递的是否是配置键名，默认是，则返回配置信息
 * @return int|string
 */
function get_conf($name, $conf, $key = true)
{
    $arr = config("conf." . $conf);
    if ($key) return $arr[$name];
    foreach ($arr as $k => $v) {
        if ($v == $name) {
            return $k;
        }
    }
}


/**
 * 多维数组合并（支持多数组）
 * @return array
 */
function array_merge_multi()
{
    $args = func_get_args();
    $array = [];
    foreach ($args as $arg) {
        if (is_array($arg)) {
            foreach ($arg as $k => $v) {
                if (is_array($v)) {
                    $array[$k] = isset($array[$k]) ? $array[$k] : [];
                    $array[$k] = array_merge_multi($array[$k], $v);
                } else {
                    $array[$k] = $v;
                }
            }
        }
    }

    return $array;
}


/**
 * 将list_to_tree的树还原成列表
 * @param array $tree
 * @param string $child
 * @param string $order
 * @param int $level
 * @param null $filter
 * @param array $list
 * @return array
 */
function tree_to_list($tree, $filter = null, $child = '_child', $order = 'id', $level = 0, &$list = [])
{
    if (is_array($tree)) {
        if (!is_callable($filter)) {
            $filter = function (&$refer, $level) {
                $refer['level'] = $level;
            };
        }
        foreach ($tree as $key => $value) {
            $refer = $value;
            unset($refer[$child]);
            $filter($refer, $level);
            $list[] = $refer;
            if (isset($value[$child])) {
                tree_to_list($value[$child], $filter, $child, $order, $level + 1, $list);
            }
        }
    }

    return $list;
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list   查询结果
 * @param string $field 排序的字段名
 * @param array $sortBy 排序类型
 *                      asc正向排序 desc逆向排序 nat自然排序
 * @return array|bool
 */
function list_sort_by($list, $field, $sortBy = 'asc')
{
    if (is_array($list)) {
        $refer = $resultSet = [];
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortBy) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc': // 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ($refer as $key => $val)
            $resultSet[] = &$list[$key];

        return $resultSet;
    }

    return false;
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;

    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 构造 select html
 *
 *
 *
 *
*/

function select( $args = 'index-task-edit',$name, $select = 0 , $val = 'val', $text = 'text'){
    $static_select_config = [
        //约定键名为 分组-控制器-操作
        'index-task-edit' => [
            ['val' => '.sh','text' => 'shell脚本'],
            ['val' => '.php','text' => 'php脚本'],
        ]
    ];

    if(is_array($args)){
        $base_arr = $args;
    }else{
        $base_arr = $static_select_config[$args];
    }

    $html = '<select class="select" size="1" name="'.$name.'">';
    foreach ($base_arr as $items) {
        if ($select == $items[$val]) {
            $html .= '<option selected value="' . $items[$val] . '">' . $items[$text] . '</option>';
        } else {
            $html .= '<option value="' . $items[$val] . '">' . $items[$text] . '</option>';
        }
    }
    $html .= '</select>';
    return $html;
}


function getdir($basedir , &$arr = [] ,&$current_dir = '', &$num = 0 ){
    if ($dh = opendir($basedir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..'){
                $dirname = $basedir.DS.$file;
                if (is_dir($dirname)) {
                    $current_dir = $file;
                    $arr[$current_dir] = [];
                    $arr[$current_dir][] = [
                        'chmod' => substr(decoct(fileperms($dirname)),2),
                        'type'  => filetype($dirname),
                        'size'  => (int) (filesize($dirname)/1024). ' KB',
                        'name'  => $file,
                        'ctime' => date('Y-m-d H:i:s' , filectime($dirname))
                    ];
                    getdir($dirname ,$arr , $current_dir ,$num);
                }else{
                    $arr[$current_dir][] = [
                        'chmod' => substr(decoct(fileperms($dirname)),2),
                        'type'  => filetype($dirname),
                        'size'  => (int)(filesize($dirname)/1024). ' KB',
                        'name'  => '&nbsp;&nbsp;&nbsp;&nbsp;'.$file,
                        'ctime' => date('Y-m-d H:i:s' , filectime($dirname))
                    ];
                    $num++;
                }
            }
        }

    }
    return array($arr,$num);
}

//获取当前系统平台
function get_os(){
    switch (true) {
        case stristr(PHP_OS, 'DAR'): return 'mac';
        case stristr(PHP_OS, 'WIN'): return 'win';
        case stristr(PHP_OS, 'LINUX'): return 'linux';
        default : return 'unkonw';
    }
}


/**
 * 尝试多次
 * @param int $times 执行次数
 * @param      $func 执行的方法
 * @param boolean $boo 重复的依据
 * @param int $sleep 停顿时间
 * */
function try_times( $times , $func ,$boo = false ,$sleep = 0){
    while ($times > 0) {
        if(is_callable($func) && !$boo ){
            $boo = call_user_func($func);
        }
        $times--;
        $sleep && sleep($sleep);
    }
    return $boo;
}

function succes_pc($res){
    echo '<div class="Huialert Huialert-success" style="z-index: 999"><i class="icon-remove"></i>'.$res.'</div>';
}

function error_pc($res){
    echo '<div class="Huialert Huialert-error" style="z-index: 999"><i class="icon-remove"></i>'.$res.'</div>';
}




function upload($path = ROOT_PATH . 'public' . DS . 'uploads'. DS . 'product' . DS , $imgName = 'imgs'){

    if(isset($_FILES[$imgName]['name']) && $_FILES[$imgName]['name'] != ''){
        //$file = request()->file('imgs');
        $file = Request::instance()->file('imgs');

        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move('.'.$path);
        if($info){
            return ['code' => 0 , 'path' =>$path.$info->getSaveName()];
        }else{
            // 上传失败获取错误信息
            return ['code' => 1 , 'msg' =>$file->getError() ];
        }
    }else{
        return ['code' => 1 , 'error' =>'没有上传图片' ];
    }

}


/*fe*/

function subList($args, $start, $end)
{

    if ((count($args) * 1 - 1) < $start) {
        return $args;
    };
    $temp = array();
    for ($start; $start < $end; $start++) {
        isset($args[$start]) && array_push($temp, $args[$start]);
    }
    return $temp;
}
/*
 * 手机号码验证
 * */
function preg_phone($mobile){
    if(preg_match("/^1[34578]\d{9}$/", $mobile)){
        return true;
    }else{
        return false;
    }
}
function error_ajax($msg){
    exit(json_encode(['code' => 1 , 'msg' =>$msg ]));
}
//会员等级名称
function member_level_name(){
    $level =  Db::name('member_level')->select();
    $level_name = array_column($level,'level_name','level_size');
    return $level_name;
}

//去除多个数组索引
function unset_key($array,$unset_array){
    foreach ($unset_array as $k=>$v){
            unset($array[$v]);
    }
    return $array;
}
//商品分类名称
function goods_class_name(){
    $goods_class = Db::name('goods_class')->select();
    $goods_class_name = array_column($goods_class,'class_name','goods_class_id');
    return $goods_class_name;
}
/*写文件*/
function ding_log($pay_type,$content){
    $filename =$_SERVER['DOCUMENT_ROOT']. '/spadmin/log/'.date('Y-m-d').'/';
    createDir($filename);
    file_put_contents($filename.$pay_type.'.txt',"执行日期："."\r\n".date('Y-m-d H:i:s',time()).  ' ' . "\n" .$content."\n");
}
//创建多个目录
function createDir($path){

    if (!file_exists($path)){
        createDir(dirname($path));
        mkdir($path, 0777);
    }

}
//角色信息
function role_list(){
    $list = Db::name('role')->where(array('is_delete'=>0))->select();
    $result = array_column($list,'role_name','role_id');
    return $result;
}
//栏目名称
function role_powe_name(){
    $data['where']['is_delete'] = 0;
    $role_powe = Db::name('role_powe')->where(array('is_delete'=>0))->select();
    $role_powe_name = array_column($role_powe,'powe_name','powe_id');
    return $role_powe_name;
}
