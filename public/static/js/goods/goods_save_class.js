$(document).ready(function(){
    $("#is_recommend").bootstrapSwitch({
        onText:'显示',
        offText:'不显示'
    });
    $("#is_display").bootstrapSwitch({
        onText:'显示',
        offText:'不显示'
    });
});
//分类新增修改
$("#goods_class_save").click(function(){
    $.ajax({
        url:'goods/goods_class_save',
        type:"POST",
        data: $('#class_form').serialize(),
        success: function(data){
            if(data.return_status){
                $.fn.modalMsg(data.msg, "success");
                setTimeout($("#tab-page-content").html(data.html), 3000);
            }else{
                $.fn.modalMsg(data.msg, "error");
            }
        },
    });
})
//显示下级分类
$('#child_class_one').change(function(){
    var child_id = $("#child_class_one").val();
    $.ajax({
        url:'goods/goods_class_two',
        type:"POST",
        data: {child_id:child_id},
        success: function(data){
            if(data.return_status){
                $('#child_class_two').html('');
                var dom = " <option style=\"display: none;\" value=\"0\">请选择商品分类</option>"
                for(var i = 0;i<data.data.length;i++){
                    dom += '<option value="'+data.data[i]['goods_class_id']+'">'+data.data[i]['class_name']+'</option>';
                }
                $('#child_class_two').html(dom);
            }else{
                $.fn.modalMsg(data.msg, "error");
            }
        },
    });
})