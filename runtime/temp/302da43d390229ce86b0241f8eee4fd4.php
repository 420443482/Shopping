<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"/Applications/MAMP/htdocs/Shopping/public/../application/admin/view/goods/goods_class.html";i:1521258416;}*/ ?>
<div class="page-content-body " id="tab-page-content">
    <section class="content">
        <div class="callout callout-info">
            <h4 style="float: left;">提示!</h4>

            <p style="max-width: 100%;color:#FFF;">
                <b style="color: #00a65a;">特别声明</b>：下列为商品分类列表信息
            </p>
        </div>
        <div class="row">
            <div class="col-md-12">

                <!-- AREA CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">商品分类</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">

                            <div id="main" style="width: 100%; height: 600px;">
                                <div class="fixed-table-toolbar"><div class="bs-bars pull-left"><div id="toolbar" class="btn-group">
                                    <button id="btn_add"  type="button" class="btn btn-default">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true" ></span>新增分类
                                    </button>
                                </div>
                                </div></div>
                                <div id="staff_list">
                                    <div class="row pre-scrollable" style=" overflow:scroll; width:100%; max-height: 100%;" id="list_staff">
                                        <table class="table-scrollable table table-hover  table-bordered" style="width: 90%; text-align: center;">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th nowrap="nowrap">序号</th>
                                                <th nowrap="nowrap">分类名称</th>
                                                <th nowrap="nowrap">是否推荐</th>
                                                <th nowrap="nowrap">是否显示</th>
                                                <th nowrap="nowrap">排序</th>
                                                <th nowrap="nowrap"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($list as $class): ?>
                                            <tr id="class_<?php echo $class['goods_class_id']; ?>">
                                            <td class="su_top"><a  class="class_plus" data-id="<?php echo $class['goods_class_id']; ?>" ids="icon/plus-square-o" style=" cursor:pointer"><i class="fa fa-plus-square-o"></i></a></td>
                                            <td><?php echo $class['goods_class_id']; ?></td>
                                            <td><?php echo $class['class_name']; ?></td>
                                            <td><?php echo $class['is_recommend']; ?></td>
                                            <td><?php echo $class['is_display']; ?></td>
                                            <td><?php echo $class['goods_sort']; ?></td>
                                            <td nowrap="nowrap">
                                                <a href="javascript:;" class="btn btn-icon-only purple class_edit" edit-id="<?php echo $class['goods_class_id']; ?>" name="class_edit"><i class="fa fa-edit"></i></a>
                                                <a href="javascript:;" class="btn btn-icon-only red class_delete" delete-id="<?php echo $class['goods_class_id']; ?>" name="class_delete"> <i class="fa fa-times"></i></a>
                                            </td>
                                            </tr>
                                            <?php endforeach; ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </section>
</div>
<script type="text/javascript" src="/static/js/modalmsg.js"></script>
<script type="text/javascript" src="/static/js/goods/goods_class.js"></script>
