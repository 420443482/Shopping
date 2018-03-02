<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"D:\phpStudy\WWW\web\Shopping\public/../application/admin\view\staff\staff_list.html";i:1519974415;}*/ ?>
<table class='table table-striped' style='width: 90%; text-align: center;'>
        <thead>
        <tr>
            <th>序号</th>
            <th>员工姓名</th>
            <th>编号</th>
            <th>手机号码</th>
            <th>住址</th>
            <th>性别</th>
            <th>微信号</th>
            <th>QQ</th>
            <th>入职时间</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$staff): $mod = ($i % 2 );++$i;?>
        <tr>
            <td><?php echo $staff['staff_id']; ?></td>
            <td><?php echo $staff['staff_name']; ?></td>
            <td><?php echo $staff['staff_code']; ?></td>
            <td><?php echo $staff['staff_phone']; ?></td>
            <td><?php echo $staff['staff_address']; ?></td>
            <td><?php echo $staff['staff_sex']; ?></td>
            <td><?php echo $staff['staff_wx']; ?></td>
            <td><?php echo $staff['staff_qq']; ?></td>
            <td><?php echo $staff['staff_add_time']; ?></td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
</table>

