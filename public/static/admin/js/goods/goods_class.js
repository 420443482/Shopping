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
        url:'goods_save_class',
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
        url:'goods_class_view',
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
                        "<a href='javascript:;' class='btn btn-icon-only red class_delete' attribute='child_class' delete-id="+data.data[i]['goods_class_id']+" name='class_delete'> <i class='fa fa-times'></i></a>"+
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
        url:'goods_class_view',
        type:"POST",
        data:{subgrade_class_id:subgrade_class_id,action:'subgrade_class'},
        async:false,
        success: function(data){
            if(data.return_status){
                for(var i=0; i<data.data.length; i++){
                    $("#child_class_"+subgrade_class_id).after(
                        "<tr class=subgrade_class_"+child_class_id+"_"+subgrade_class_id+" id=subgrade_class_"+data.data[i]['goods_class_id']+">"+
                        "<td style='padding-left: 3rem;'></td>" +
                        "<td>"+data.data[i]['goods_class_id']+"</td>" +
                        "<td>"+data.data[i]['class_name']+"</td>" +
                        "<td>"+data.data[i]['is_recommend']+"</td>" +
                        "<td>"+data.data[i]['is_display']+"</td>" +
                        "<td>"+data.data[i]['goods_sort']+"</td>" +
                        "<td nowrap='nowrap'>"+
                        "<a href='javascript:;' class='btn btn-icon-only purple class_edit' edit-id="+data.data[i]['goods_class_id']+" name='class_edit'><i class='fa fa-edit'></i></a>"+
                        "<a href='javascript:;' class='btn btn-icon-only red class_delete' attribute='subgrade_class' delete-id="+data.data[i]['goods_class_id']+" name='class_delete'> <i class='fa fa-times'></i></a>"+
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
    $("tr[class^=child_class_"+child_class_id+"]").remove();
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
    var  attribute = $(this).attr("attribute");

    $.fn.modalConfirm("确认要删除么？", function(status) {
        if(status == false) return false;
        $.ajax({
            url: 'goods_class_delete',
            type: "POST",
            data: {goods_class_id: goods_class_id},
            success: function (data) {
                if (data.return_status) {
                    $("#"+attribute+"_"+goods_class_id).remove();
                    $.fn.modalMsg(data.msg, "success");
                } else {
                    $.fn.modalMsg(data.msg, "error");
                }
            }

        });
    });
});