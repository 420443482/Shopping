/*window.onerror = function(errorMessage, scriptURI, lineNumber,columnNumber,errorObj) {
 console.log("错误信息：" , errorMessage);
 console.log("出错文件：" , scriptURI);
 console.log("出错行号：" , lineNumber);
 console.log("出错列号：" , columnNumber);
 console.log("错误详情：" , errorObj);
 return true;
 }*/
String.prototype.replaceAll = function (s1, s2) {
    return this.replace(new RegExp(s1, "gm"), s2);
}
/*常量*/
var TIMEOUTLIMIT = 8000;
var TIMEOUTTIPS = "连接超时";
var ERROR_SYS = "系统错误";
var api = {};
api.error_img = '/Public/Home/images/photo.gif';
api.ret = [];
api.extend = function (b, a) {
    for (var c in a) {
        b[c] = a[c]
    }
    return b
};
/**表单转JSON 字符串**/
api.formToJson = function (data) {
    data = data.replace(/&/g, "\",\"");
    data = data.replace(/=/g, "\":\"");
    data = data.replaceAll("\r\n", "\\r\\n");
    data = "{\"" + data + "\"}";
    return data;
};
/**消息模块
 **{
	title:'',//提示内容 默认是执行中的动画
	duration:0,//定时自动关闭；为0时不自动关闭；大于等于200时候为定时关闭
	url:'',
}
 **/
api.showPress = function (_con) {
    var config = {title: '<i></i><i></i><i></i><i></i>', duration: 0, url: ''};
    _con && ( config = api.extend(config, _con) );
    if (!document.getElementById('side-mask')) {
        var d = document.createElement("div");
        d.id = 'side-mask';
        d.className = 'f-hide';
        document.body.appendChild(d);
    }
    var sidemask = document.getElementById('side-mask');
    sidemask.innerHTML = '<section>' + config.title + '</section>';
    sidemask.className = '';
    199 < config.duration && setTimeout(function () {
        '' !== config.url ? window.location.href = config.url : api.hidePress();
    }, config.duration);
};
api.hidePress = function () {
    if (document.getElementById('side-mask')) {
        document.getElementById('side-mask').className = 'f-hide';
    }
}

/*
 *单个按钮对话框
 */
api.alert = function (setting, _back) {
    var config = {title: '提示', msg: '消息', buttons: ['确定']};
    config = api.extend(config, setting);
    if (!document.getElementById('api-alert')) {
        var d = document.createElement("div");
        d.id = 'api-alert';
        document.body.appendChild(d);
    }
    var obj = document.getElementById('api-alert');
    obj.style.display = "block";
    obj.innerHTML = '<section class="api-open fillet"><h5>' + config.title + '</h5><p>' + config.msg + '</p><div><button>' + config.buttons[0] + '</button></div></section>';
    obj.children[0].children[2].children[0].focus();
    var hide = function () {
        obj.children[0].className = obj.children[0].className.replaceAll('api-open', 'api-close');
        obj.children[0].addEventListener("webkitAnimationEnd", function () {
            obj.style.display = "none";
        });
        obj.children[0].addEventListener("animationend", function () {
            obj.style.display = "none";
        });
    }
    obj.children[0].children[2].children[0].onclick = function () {
        hide();
        _back && _back({buttonIndex: 1});
    };
}
/*
 *两个按钮对话框
 * {title: 'testtitle',msg: 'testmsg',buttons:['确定','取消']}
 * return buttonIndex:1(确认)/2(取消)
 */
api.confirm = function (setting, _back) {
    var config = {title: '确认', msg: '是否操作？', buttons: ['确定', '取消']};
    config = api.extend(config, setting);
    if (!document.getElementById('api-confirm')) {
        var d = document.createElement("div");
        d.id = 'api-confirm';
        document.body.appendChild(d);
    }
    var obj = document.getElementById('api-confirm');
    obj.style.display = "block";
    obj.innerHTML = '<section class="api-open fillet"><h5>' + config.title + '</h5><p>' + config.msg + '</p><div><button>' + config.buttons[0] + '</button><button>' + config.buttons[1] + '</button></div></section>';
    obj.children[0].children[2].children[0].focus();
    var hide = function () {
        obj.children[0].className = obj.children[0].className.replaceAll('api-open', 'api-close');
        obj.children[0].addEventListener("webkitAnimationEnd", function () {
            obj.style.display = "none";
        });
        obj.children[0].addEventListener("animationend", function () {
            obj.style.display = "none";
        });
    }
    obj.children[0].children[2].children[0].onclick = function () {
        hide();
        _back();
    };
    obj.children[0].children[2].children[1].onclick = function () {
        hide();
        //_back({buttonIndex:2});
    };
}
/*
 *数据验证
 */
/**简易手机号码**/
api.regMobile = function (mobile) {
    return (/^(1)\d{10}$/i.test(mobile)) ? true : false;
}
/**中文验证***/
api.regChinese = function (uname) {
    return (/^[\u4E00-\u9FA5]+$/.test(uname)) ? true : false;
}
/**邮编***/
api.regZipCode = function (num) {
    return (/^\d{6}$/i.test(num)) ? true : false;
}
/***是否包含特殊字符***/
api.illegalChar = function (str) {
    return (/[`~!@#$%^&*()+<>?:"{},.\/;'[\]]/im.test(str)) ? true : false;
}
/***数字***/
api.regNnm = function (num) {
    return (/^[0-9]*[1-9][0-9]*$/i.test(num)) ? true : false;
}
/***正整数****/
api.regParseInt = function (num) {
    return (/^(0|[1-9]\d*)$/i.test(num)) ? true : false;
}
/***非负数****/
api.regFloat = function (num) {
    return (/^\d+(\.{0,1}\d+){0,1}$/i.test(num)) ? true : false;
}


/**unix日期转普通日期**/
api.unixToDate = function (unixTime, isFull, timeZone) {

    if (Number(unixTime) == 0) {
        return '';
    }
    if (typeof (timeZone) == 'number') {
        unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
    }
    var time = new Date(unixTime * 1000);
    var ymdhis = "";
    ymdhis = isFull ? time.Format('YYYY-MM-DD HH:mm:ss') : time.Format('YYYY-MM-DD');
    return ymdhis;
}

Date.prototype.Format = function (formatStr) {
    var str = formatStr;
    var Week = ['日', '一', '二', '三', '四', '五', '六'];
    str = str.replace(/yyyy|YYYY/, this.getFullYear());
    str = str.replace(/yy|YY/, (this.getYear() % 100) > 9 ? (this.getYear() % 100).toString() : '0' + (this.getYear() % 100));

    str = str.replace(/MM/, this.getMonth() > 8 ? (this.getMonth() + 1).toString() : '0' + (this.getMonth() + 1));
    str = str.replace(/M/g, this.getMonth() + 1);

    str = str.replace(/w|W/g, Week[this.getDay()]);

    str = str.replace(/dd|DD/, this.getDate() > 9 ? this.getDate().toString() : '0' + this.getDate());
    str = str.replace(/d|D/g, this.getDate());

    str = str.replace(/hh|HH/, this.getHours() > 9 ? this.getHours().toString() : '0' + this.getHours());
    str = str.replace(/h|H/g, this.getHours());
    str = str.replace(/mm/, this.getMinutes() > 9 ? this.getMinutes().toString() : '0' + this.getMinutes());
    str = str.replace(/m/g, this.getMinutes());

    str = str.replace(/ss|SS/, this.getSeconds() > 9 ? this.getSeconds().toString() : '0' + this.getSeconds());
    str = str.replace(/s|S/g, this.getSeconds());
    return str;
}
/**
 * 秒转时间
 *
 * @access  public
 * @param (int)$second 秒
 * @return  00:00
 */
api.secondToTime = function ($second) {
    $hh = 0;
    $ii = Math.floor($second / 60);
    $ss = $second % 60;
    $hh = 0;
    $dd = 0;
    if (60 < $ii) {
        $hh = Math.floor($ii / 60);
        $ii = $ii % 60;
    }
    if (24 < $hh) {
        $dd = Math.floor($hh / 24);
        $hh = $hh % 24;
    }
    return (0 < $dd ? $dd + '天' : '') + (0 < $hh ? $hh + '时' : '') + (0 < $ii ? $ii + '分' : '') + $ss + '秒';
}
/*
 *ajax拉取数据
 _url 地址
 _json 传递数据
 _back 正确回调
 _err 错误回调
 _a 是否显示加载项目
 */
api.ajax = function (_url, _json, _back, _err, _a) {
    $.ajax({
        beforeSend: function () {
            api.showPress();
        },
        url: _url,// 跳转到 action
        type: 'post',
        cache: false,
        async: false, /*true异步执行，false*/
        data: _json,
        dataType: 'json',
        timeout: 8000,
        success: function (ret) {
            if (!ret) {
                api.showPress({title: '返回格式错误', duration: 1500});
                return false;
            }
            var url = ret.url ? ret.url : '';
            if (ret.state || 1 == ret.code) {
                ( ret.msg && '' !== ret.msg ) ? api.showPress({
                    title: ret.msg,
                    duration: 2000,
                    url: url
                }) : api.hidePress();
                _back && _back(ret);
            } else {
                !ret.alert ? api.showPress({
                    title: '提示：' + ret.msg,
                    duration: 1500
                }) : (api.hidePress(), api.alert({title: '提示', msg: ret.msg}));
                _err && _err(ret);
            }
        },
        error: function (xmlHttpRequest, error) {
            if (error == 'timeout') { //超时
                api.showPress({title: '网络太差', duration: 1500});
            } else {
                api.showPress({title: '系统错误:' + error, duration: 1500});
            }

        }
    });
}


/**
 * 判断json是否为空对象
 * */
api.jsonEmpty = function (json) {
    for (x in json) {
        return true
    }
    return false;
};
/**获取复选框的值*/
api.checkbox = function (name) {
    var arr = [];
    var checkbox = document.getElementsByName(name);
    for (var i = 0; i < checkbox.length; i++) {
        if (checkbox[i].checked == true) {
            arr.push(checkbox[i].value);
        }
    }
    return arr;
}

/*全选*/
api.checkAll = function (pt, son) {
    $(document).on('click', pt, function () {
        var _self = this;
        $(son).each(function () {
            this.checked = _self.checked;
        });
    });
}

/*下拉框选中*/
/*选中某个下拉框*/
api.selected = function (the_name, by, search) {
    $("select[name='" + the_name + "'] option ").each(function () {
        console.log($(this).val())
        if (by == 1) {
            if (search == $(this).val()) {
                $(this).prop("selected", "selected");
            }
        } else {
            if (search == $(this).text()) {
                $(this).prop("selected", "selected");
            }
        }
    })
}


api.page = {
    init: function (_obj, _c) {
        var _this = api.extend({page: 1, size: 10, total: 0}, _c);
        _this.layout = ['first', 'prev', 'links', 'next', 'last', 'sep', 'refresh'];
        _this.numlen = 10;
        $(_obj).html('<table cellspacing="0" cellpadding="0" border="0">' +
            '<tbody>' +
            '<tr>' +

            '<td>' +
            '<a href="javascript:void(0)" class="l-btn l-btn-small l-btn-plain l-btn-disabled l-btn-plain-disabled" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text l-btn-empty">&nbsp;</span><span class="l-btn-icon pagination-first">&nbsp;</span></span></a>' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0)" class="l-btn l-btn-small l-btn-plain l-btn-disabled l-btn-plain-disabled" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text l-btn-empty">&nbsp;</span><span class="l-btn-icon pagination-prev">&nbsp;</span></span></a>' +
            '</td>' +
            '<td class="pagination-links">' +
            '<a class="pagination-link l-btn l-btn-small l-btn-plain l-btn-selected l-btn-plain-selected" href="javascript:void(0)" group="" id=""><span class="l-btn-left"><span class="l-btn-text">1</span></span></a>' +
            '</td>' +
            '<td><a href="javascript:void(0)" class="l-btn l-btn-small l-btn-plain l-btn-disabled l-btn-plain-disabled" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text l-btn-empty">&nbsp;</span><span class="l-btn-icon pagination-next">&nbsp;</span></span></a>' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0)" class="l-btn l-btn-small l-btn-plain l-btn-disabled l-btn-plain-disabled" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text l-btn-empty">&nbsp;</span><span class="l-btn-icon pagination-last">&nbsp;</span></span></a>' +
            '</td>' +
            '<td>' +
            '<div class="pagination-btn-separator"></div>' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0)" class="l-btn l-btn-small l-btn-plain" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text l-btn-empty">&nbsp;</span><span class="l-btn-icon pagination-load">&nbsp;</span></span></a>' +
            '</td>' +
            '<td>' +
             '<select name="page_select" class="page_select" style="width: auto; margin: 0;">' +
             '<option value="10">10</option>' +
             '<option value="15">15</option>' +
             '<option value="30">30</option>' +
             '<option value="45">45</option>' +
             '<option value="60">60</option>' +
             '</select>' +
             '</td>' +
            '</tr>' +
            '</tbody></table><div class="pagination-info">显示0到0,共0记录</div>');
        _this.setTotal = function (total) {
            _this.total = total;
            _this.t_page = Math.ceil(_this.total / _this.size) || 1;
            _this.set();
            $('.page_select option').each(function(){
                if($(this).val()==page_size){
                    $(this).attr('selected','selected');
                }
            })
            $('.page_select').change(function(){
                $.cookie('page_size',$(this).val());
                location.reload();
            })
        }
        $(_obj).on('click', 'table a.l-btn-plain', function () {
            if ($(this).hasClass('l-btn-disabled')) {
                return false;
            }
            _this.page = parseInt(_this.page);
            var td = $(this).parent(),
                idx = $('tr td', $(_obj)).index(td);
            switch (_this.layout[idx]) {
                case 'first':
                    _this.page = 1;
                    _this.onSelectPage && _this.onSelectPage(_this.page, _this.size);
                    break;
                case 'prev':
                    _this.page -= 1;
                    !_this.page && (_this.page = 1);
                    _this.onSelectPage && _this.onSelectPage(_this.page, _this.size);
                    break;
                case 'links':
                    _this.page = parseInt($('.l-btn-text', this).html());
                    _this.onSelectPage && _this.onSelectPage(_this.page, _this.size);
                    break;
                case 'next':
                    _this.page += 1;
                    _this.page > _this.t_page && (_this.page = _this.t_page);
                    _this.onSelectPage && _this.onSelectPage(_this.page, _this.size);
                    break;
                case 'last':
                    _this.page = _this.t_page;
                    _this.onSelectPage && _this.onSelectPage(_this.page, _this.size);
                    break;
                case 'refresh':
                    _this.onRefreshPage && _this.onRefreshPage(_this.page, _this.size);
                    break;
                default:
                    break;
            }
        });

        _this.set = function () {
            !_this.t_page && (_this.t_page = 1);
            _this.page > _this.t_page && (_this.page = _this.t_page);
            1 == Number(_this.page) ? $('.pagination-first,.pagination-prev', $(_obj)).parents('a').addClass('l-btn-disabled') : $('.pagination-first,.pagination-prev', $(_obj)).parents('a').removeClass('l-btn-disabled');
            _this.t_page == _this.page ? $('.pagination-last,.pagination-next', $(_obj)).parents('a').addClass('l-btn-disabled') : $('.pagination-last,.pagination-next', $(_obj)).parents('a').removeClass('l-btn-disabled');
            var links = '';
            for (var k in _this.layout) {
                if ('links' == _this.layout[k]) {
                    var start = _this.page - Math.floor(_this.numlen / 2);
                    start < 1 && ( start = 1);
                    if (_this.page < start) {
                        start = 1;
                    }
                    var end = start + _this.numlen - 1
                    if (end > _this.t_page) {
                        end = _this.t_page;
                    }
                    for (var i = start; i <= end; i++) {
                        links += '<a class="pagination-link l-btn l-btn-small l-btn-plain ' + (i == _this.page ? ' l-btn-plain-selected' : '') + ' " href="javascript:void(0)" group="" id=""><span class="l-btn-left"><span class="l-btn-text">' + i + '</span></span></a>';
                    }
                    $('table td:eq(' + k + ')', $(_obj)).html(links);
                }
            }
            $('.pagination-info', $(_obj)).html('显示' + ((_this.page - 1) * _this.size + 1) + '到' + (_this.size * _this.page) + ',共' + _this.total + '记录');
        }

        _this.setTotal(0);
        return _this;
    }
}

api.view = function (file, showImg, end) {

    if (!file.files[0]) {
        return false;
    }
    if (!file.value.match(/.jpg|.gif|.png|.jpeg/i)) {
        layer.alert('您选择的图片格式不正确，请重新选择！', {title: "错误信息", icon: 2});
        //api.showPress({title: '您选择的图片格式不正确，请重新选择！', duration: 3000});
        return false;
    }
    if (file.files[0].size > 10 * 1024 * 1024) {
        layer.alert('您选择的图片不能大于10M，请重新选择', {title: "错误信息", icon: 2});
        return false;
    }
    var myPhoto = document.getElementById(showImg);
    var reader = new FileReader();
    reader.onload = function (e) {
        var dataURL = e.target.result;
        myPhoto.src = dataURL;
        myPhoto.style.display = 'block';
        end && end(dataURL);
    };
    reader.readAsDataURL(file.files[0]);

}