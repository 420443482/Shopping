<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/4
 * Time: 10:02
 */
namespace app\common\Controller;
use think\Config;
use think\Controller;
use think\Db;

class Save extends Controller
{
    public $table_name;//表名
    public $order = 'ctime desc';//排序方式
    public  function __construct($data)
    {
        if(empty($data))$this->error('未传入指定参数');
        $this->table_name = $data['table_name'];
    }

    //增
    public function add($data)
    {
        $result = Db::name($this->table_name)->insertGetId($data['data']);
        ding_log('add',Db::name('')->getLastSql());
        return $result;
    }

    //删
    public function del($data)
    {
        $result = Db::name($this->table_name)->where($data['where'])->delete();
        ding_log('del',Db::name('')->getLastSql());
        return $result;
    }

    //改
    public function edit($data)
    {
        $result = Db::name($this->table_name)->where($data['where'])->update($data['data']);
        ding_log('edit',Db::name('')->getLastSql());
        //防止修改是，数据未做变动提交返回false的问题
        $result !== false ?  $result = true : $result = false;
        return $result;
    }

    //分页查询
    public function select($data = [])
    {
        $this->order = isset($data['order'])?$data['order']:$this->order;
        $listRows =  Config::get("paginate.list_rows");
        $list = Db::name($this->table_name)->where($data['where'])->order($this->order)->paginate($listRows, false);
        ding_log('select',Db::name('')->getLastSql());
        return $list;
    }
    //单条查询
    public function selectFind($data=[])
    {
        $list = Db::name($this->table_name)->where($data['where'])->find();
        ding_log('select',Db::name('')->getLastSql());
        return $list;
    }
    //全部查询
    public function selectAll($data=[])
    {
        $list = Db::name($this->table_name)->where($data['where'])->select();
        ding_log('select',Db::name('')->getLastSql());
        return $list;
    }
}