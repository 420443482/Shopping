$(function (){
    $('#selsect').click(function(event) {
        $('#fileselect').click();
    });
    //选择文件
    $('#fileselect').change(function(event) {
        event.preventDefault();
        var n=event.target.files.length;
        var file;
        for (var i = 0; i < n; i++) {
            file=event.target.files[i];
            html5up(file);
        };
    });
    //上传操作
    function html5up(file){
        /* Act on the event */
        var form_data=new FormData();
        // form_data.append('timestamp',"{$time}");
        // form_data.append('token','{:md5("unique_salt".$time)}');
        form_data.append("Filedata",file);
        $.ajax({
            url: '/spadmin/staff/staff_upload',
            type: 'POST',
            processData: false,
            contentType: false,
            data: form_data,
        })
            .done(function(data) {
                // alert(data);
                if (!data.return_status) {
                    alert(data.info);
                    return false;
                };
                put_img(data.savepath,data.savename);//添加图片到img_box
                console.log("success");
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });

    }
    //添加图片到img_box
    function put_img(savepath,savename){
        var img_url=savepath;
        var path = '/spadmin/staff/images/';
        var new_img='<img  style="width:150px;height: 100px; margin-left:15px; margin-top: -10px;" src="'+path+''+img_url+'" alt="test" class="img">';
        new_img+='</div>';
        $('.img_box').html(new_img);
        $("#upload_img").val(path+img_url);
    }
});

