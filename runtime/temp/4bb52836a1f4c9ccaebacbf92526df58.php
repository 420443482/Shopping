<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"D:\phpStudy\WWW\web\Shopping\public/../application/admin\view\goods\goods_class.html";i:1521163948;}*/ ?>
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

<script>
    $(function(){
       $("#btn_add").click(function(){
           getData('');
       });
    });
    $(document).off('click', '.class_edit').on('click', '.class_edit', function(e) {
        var class_id = $(this).attr('edit-id');
        getData(class_id);
    })
    //无刷新显示数据
    function getData(class_id) {
        $.ajax({
            url:'goods/goods_save_class',
            type:"POST",
            data:{class_id:class_id},
            success: function(data){

                $("#tab-page-content").html(data);
            },
        });
    }
    $(document).off('click', '.class_plus').on('click', '.class_plus', function(e) {
        var child_class_id = $(this).attr('data-id');
        $.ajax({
            url:'goods/goods_class_view',
            type:"POST",
            data:{child_class_id:child_class_id,action:'child_class'},
            async:false,
            success: function(data){
               if(data.return_status){
                   for(var i=0; i<data.data.length; i++){
                       $("#class_"+child_class_id).after(
                           "<tr  id=child_class_"+data.data[i]['goods_class_id']+" class=child_class_"+child_class_id+">"+
                           "<td style='padding-left: 3rem;' class='sup_bottom'><a  style=\" cursor:pointer\"class='class_plus_one' child-id="+child_class_id+"  data-id="+data.data[i]['goods_class_id']+" ids=\"icon/plus-square-o\"><i class=\"fa fa-plus-square-o\"></i></a></td>\n" +
                           "<td>"+data.data[i]['goods_class_id']+"</td>" +
                           "<td>"+data.data[i]['class_name']+"</td>" +
                           "<td>"+data.data[i]['is_recommend']+"</td>" +
                           "<td>"+data.data[i]['is_display']+"</td>" +
                           "<td>"+data.data[i]['goods_sort']+"</td>" +
                           "<td nowrap='nowrap'>"+
                           "<a href='javascript:;' class='btn btn-icon-only purple class_edit' edit-id="+data.data[i]['goods_class_id']+" name='class_edit'><i class='fa fa-edit'></i></a>"+
                           "<a href='javascript:;' class='btn btn-icon-only red class_delete' delete-id="+data.data[i]['goods_class_id']+" name='class_delete'> <i class='fa fa-times'></i></a>"+
                           "</td>"+
                           "</tr>"
                       );
                   }
                   $("#class_"+child_class_id ).find(".su_top").html("<a class='class_reduce'  style=\" cursor:pointer\" data-id="+child_class_id+" ids=\"icon/minus-square-o\"><i class=\"fa fa-minus-square-o\"></i></a>");
               }else{
                   $.fn.modalMsg(data.msg, "error");
               }
            }
        });
    });
    $(document).off('click', '.class_plus_one').on('click', '.class_plus_one', function(e) {
        var subgrade_class_id = $(this).attr('data-id');
        var child_class_id = $(this).attr('child-id');

        $.ajax({
            url:'goods/goods_class_view',
            type:"POST",
            data:{subgrade_class_id:subgrade_class_id,action:'subgrade_class'},
            async:false,
            success: function(data){
                if(data.return_status){
                    for(var i=0; i<data.data.length; i++){
                        $("#child_class_"+subgrade_class_id).after(
                            "<tr class=subgrade_class_"+child_class_id+"_"+subgrade_class_id+" >"+
                            "<td style='padding-left: 3rem;'></td>\n" +
                            "<td>"+data.data[i]['goods_class_id']+"</td>" +
                            "<td>"+data.data[i]['class_name']+"</td>" +
                            "<td>"+data.data[i]['is_recommend']+"</td>" +
                            "<td>"+data.data[i]['is_display']+"</td>" +
                            "<td>"+data.data[i]['goods_sort']+"</td>" +
                            "<td nowrap='nowrap'>"+
                            "<a href='javascript:;' class='btn btn-icon-only purple class_edit' edit-id="+data.data[i]['goods_class_id']+" name='class_edit'><i class='fa fa-edit'></i></a>"+
                            "<a href='javascript:;' class='btn btn-icon-only red class_delete' delete-id="+data.data[i]['goods_class_id']+" name='class_delete'> <i class='fa fa-times'></i></a>"+
                            "</td>"+
                            "</tr>"
                        );
                    }
                    $("#child_class_"+subgrade_class_id ).find(".sup_bottom").html("<a style=\" cursor:pointer\" class='class_reduce_one' child-id="+child_class_id+" data-id="+subgrade_class_id+" ids=\"icon/minus-square-o\"><i class=\"fa fa-minus-square-o\"></i></a>");
                }else{
                    $.fn.modalMsg(data.msg, "error");
                }
            }
        });
    });
    $(document).on('click', '.class_reduce', function(e) {
        var child_class_id = $(this).attr('data-id');
        $('.child_class_'+child_class_id).remove();
        $("tr[class^=subgrade_class_"+child_class_id+"]").remove();

        $(this).html("<i class=\"fa fa-plus-square-o\"></i>");
        $(this).attr('class','class_plus');

    });
    $(document).on('click', '.class_reduce_one', function(e) {
        var subgrade_class_id = $(this).attr('data-id');
        var child_class_id = $(this).attr('child-id');

        $("tr[class^=subgrade_class_"+child_class_id+"_"+subgrade_class_id+"]").remove();

        $(this).html("<i class=\"fa fa-plus-square-o\"></i>");
        $(this).attr('class','class_plus_one');

    });

    //单条数据删除
    $(document).off('click', '.class_delete').on('click', '.class_delete', function(e) {
        var  goods_class_id = $(this).attr("delete-id");
        $.fn.modalConfirm("确认要删除么？", function(status) {
            if(status == false) return false;
            $.ajax({
                url: 'goods/goods_class_delete',
                type: "POST",
                data: {goods_class_id: goods_class_id},
                success: function (data) {
                    if (data.return_status) {
                        $("#class_"+goods_class_id).remove();
                        $.fn.modalMsg(data.msg, "success");
                    } else {
                        $.fn.modalMsg(data.msg, "error");
                    }
                }

            });
        });
    });
</script>