<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"D:\phpStudy\WWW\web\Shopping\public/../application/admin\view\staff\staff.html";i:1521163948;}*/ ?>
   <style>
       .pagination{
           margin-top: 0px;
       }
   </style>
    <div class="page-content-body " id="tab-page-content">
        <div class="box-body" style="padding-bottom:0px;">
            <div class="panel panel-default" >
                <div class="panel-heading">查询条件</div>
                <div class="panel-body">
                    <form id="formSearch" class="form-horizontal">
                        <div class="form-group" style="margin-top:15px">
                            <label class="control-label col-sm-1" for="staff_name">姓名</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="staff_name" name="staff_name">
                            </div>
                            <label class="control-label col-sm-1" for="staff_code">编号</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="staff_code" name="staff_code">
                            </div>
                            <label class="control-label col-sm-1" for="staff_phone">手机号码</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="staff_phone" name="staff_phone">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:15px">
                            <label class="control-label col-sm-1"for="staff_add_time" >入职时间</label>
                            <div class="col-sm-3">
                            <a class='input-group date' id='datetimepicker1'>
                                    <input type='date' class="form-control" id='staff_add_time' name="staff_add_time" />
                                    <span class="input-group-addon" >
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </a>
                        </div>
                            <div class="col-sm-2" style="text-align:right; float: right">
                                <button type="button" style="margin-left:50px" id="btn_query"   class="btn btn-primary">查询</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="content-iframe" style="background-color: #ffffff; ">

                <div class="fixed-table-toolbar"><div class="bs-bars pull-left"><div id="toolbar" class="btn-group">
                    <button id="btn_add"  type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true" ></span>新增
                    </button>
                    <button id="btn_delete" type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除
                    </button>
                </div>
                </div></div>
        <div id="staff_list">
            <div class="row pre-scrollable" style=" overflow:scroll; width:102%; max-height: 60%;" id="list_staff">
                <table class="table-scrollable table table-hover  table-bordered" style="width: 90%; text-align: center;">
                    <thead>
                    <tr>
                        <th class="bs-checkbox " style="width: 36px;padding-bottom:11px; " data-field="0" tabindex="0"><div class="th-inner "><input name="btSelectAll" type="checkbox"></div><div class="fht-cell"></div></th>
                        <th nowrap="nowrap">序号</th>
                        <th nowrap="nowrap">员工姓名</th>
                        <th nowrap="nowrap">编号</th>
                        <th nowrap="nowrap">手机号码</th>
                        <th nowrap="nowrap">住址</th>
                        <th nowrap="nowrap">性别</th>
                        <th nowrap="nowrap">微信号</th>
                        <th nowrap="nowrap">QQ</th>
                        <th nowrap="nowrap">入职时间</th>
                        <th nowrap="nowrap">操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$staff): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td class="bs-checkbox "><input data-index="0" data-staff-id="<?php echo $staff['staff_id']; ?>" name="btSelectItem" type="checkbox"></td>
                        <td><?php echo $staff['staff_id']; ?></td>
                        <td><?php echo $staff['staff_name']; ?></td>
                        <td><?php echo $staff['staff_code']; ?></td>
                        <td><?php echo $staff['staff_phone']; ?></td>
                        <td><?php echo $staff['staff_address']; ?></td>
                        <td><?php echo $staff['staff_sex']; ?></td>
                        <td><?php echo $staff['staff_wx']; ?></td>
                        <td><?php echo $staff['staff_qq']; ?></td>
                        <td><?php echo $staff['staff_add_time']; ?></td>
                        <td nowrap="nowrap">
                            <a href="javascript:;" class="btn btn-icon-only purple" edit-id="<?php echo $staff['staff_id']; ?>" name="staff_edit"><i class="fa fa-edit"></i></a>
                            <a href="javascript:;" class="btn btn-icon-only red" data-id="<?php echo $staff['staff_id']; ?>" name="staff_delete"> <i class="fa fa-times"></i></a>
                        </td>
                    </tr>

                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="fixed-table-pagination" style="display: block;"><div class="pull-left pagination-detail"><span class="pagination-info">显示 1 到 <?php echo $list_num; ?> 条记录，总共<?php echo $total; ?>条记录</span><span class="page-list">每页显示 <span class="btn-group dropup"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="page-size" id="page_num"><?php echo $list_num; ?></span> <span class="caret"></span></button><ul class="dropdown-menu" role="menu"><li class="active"><a href="javascript:void(0)">10</a></li><li><a href="javascript:void(0)">25</a></li><li><a href="javascript:void(0)">50</a></li><li><a href="javascript:void(0)">100</a></li></ul></span> 条记录</span></div><div class="pull-right pagination" style="margin: 0px;">
            <?php echo $page; ?>
            </div>
            </div>
        </div>
    </div>
        <input type="hidden" name="default_page" value="1">
        <input type="hidden" name="default_num" value="10">
    </div>
    </div>
<script src="/static/js/staff/staff.js" type="text/javascript"></script>

