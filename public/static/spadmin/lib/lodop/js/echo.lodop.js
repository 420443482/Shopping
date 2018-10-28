/**
 * Created by Administrator on 2016/12/1.
 */
/*var expreInfo = {
    'STO': '网址:www.sto.cn 客服电话:95543',
    'YD': '网址:www.yundaex.com 客服电话:95546',
    'YTO': '网址:www.yto.net.cn 客服电话:95554',
    'ZTO': '网址:www.zto.com 95311',
    'HTKY': '网址:www.800bestex.com 客服电话:400-956-5656',
    'SF': '网址:www.sf-express.com 客服电话:95338',
    'HHTT': '网址:www.ttkdex.com 客服电话:400-188-8888',
    'QFKD': '网址:www.qfkd.com.cn 客服电话:400-100-0001',
    'UC': '网址:网址:www.uc56.com 客服电话:400-1111-119',
    'GTO': '网址:www.gto365.com 客服电话:400-111-1123',
    'RFD': '网址:www.rufengda.com 客服电话:400-010-6660',
    'JD': '网址:www.jd-ex.com 客服电话:400-603-3600',
    'SURE': '网址:www.sure56.com 客服电话:400-158-9888',
    'YZPY': '网址:www.chinapost.com.cn 客服电话:11185',
    'DBL': '网址:www.deppon.com 客服电话:95353',
    'ANE': '网址:www.ane56.com 客服电话:400-104-0088'
};*/

function expreHtml( data, obj, expreInfo , view){

        var html = '';
        var _data = data;
        var sender = data.sender;
        for( var i = 0; i < _data['res'].length; i++ ){
            var subres = _data['res'][i];
            var _no = parseInt(subres.express)|| '';
            var ostr = '';
            for( k in subres['order_extInfo'] ){
                ostr += subres['order_extInfo'][k]['pname']+' * '+subres['order_extInfo'][k]['num']+'<br>';
            }
            html +=
                '<div class="page">'+
                '<div class="divtop">'+
                '<ul class="topul clearfix">'+
                '<li class="li1"><b>快递包裹</b></li>'+
                '<li class="li2 licode">'+$('<div>').append(barcodeFn(_no)).html()+'<p>'+_no+'</p></li>'+
                '</ul>'+
                '<table class="toptable">'+
                '<thead>'+
                '<tr>'+
                '<th colspan="2">'+sender.city+"转 "+subres.city+"（投递局）"+'</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>'+
                '<tr class="trst1">'+
                '<td class="tds1" style="padding:4mm 0;">收<br />件</span></td>'+
                '<td class="tds2">'+
                '<p>'+subres.geter_name+' '+ subres.geter_mobile + '<br>' + subres.pro +' ' + subres.city +' ' + subres.area + ' '+ subres.street+'</p>'+
                '</td>'+
                '</tr>'+
                '</tbody>'+
                '</table>'+
                '<table class="toptable">'+
                '<tbody>'+
                '<tr>'+
                '<td class="tds3">'+
                '<ul>'+
                '<li>'+
                '<span class="tit">订单号：</span><span class="cont">'+
                '<p>'+subres.order_sn+'</p></span>'+
                '</li>'+
                '<li>'+
                '<span class="tit">品名：</span><span class="cont">'+
                '<p>'+ostr+'</p>'+
                '</span>'+
                '</li>'+
                '</ul>'+
                '</td>'+
                '<td class="tds4">'+
                '<p>收件人/代收件人：</p>'+
                '<div style="height:26mm;"></div>'+
                '<p>（代收款：　元）</p>'+
                '<div class="subdiv">'+
                '<p class="tl">签收时间：</p>'+
                '<p class="tr">　年　月　日　时</p>'+
                '</div>'+
                '</td>'+
                '</tr>'+
                '</tbody>'+
                '</table>'+
                '</div>'+
                '<div class="footdiv">'+
                '<ul class="topul clearfix">'+
                '<li class="li2 licode">'+ $('<div>').append(barcodeFn(_no)).html()+
                '<p>'+_no+'</p>'+
                '</li>'+
                '<li class="li1"><b>快递包裹</b></li>'+
                '</ul>'+
                '<table class="toptable">'+
                '<tbody>'+
                '<tr>'+
                '<td class="tds1">收<br />件</td>'+
                '<td colspan="2" class="tds3 pl1">'+
                '<p>'+subres.geter_name+' '+ subres.geter_mobile + '<br>' + subres.pro +' ' + subres.city +' ' + subres.area + ' '+ subres.street+'</p>'+
                '</td>'+
                '</tr>'+
                '<tr>'+
                '<td class="tds1">寄<br />件</td>'+
                '<td class="tds3 pl1 pr">'+
                '<p>'+sender.fname + sender.cellphone +'\n'+ sender.pro +' '+ sender.city +' ' + sender.area +' ' + sender.street+'\n</p>'+
                '<p class="tr"><span class="sjzhi">（收寄局）</span></p>'+
                '</td>'+
                '<td rowspan="3" class="tdsys">已验视<br />　验视人：</td>'+
                '</tr>'+
                '<tr>'+
                '<td colspan="2" class="td15">订单号：'+subres.order_sn+'</td>'+
                '</tr>'+
                '<tr>'+
                '</tr>'+
                '</tbody>'+
                '</table>'+
                '</div>'+
                '</div>';
        }
    $(obj).append(html);
}

/*
* barcodeFn
* 条形码
**/
function barcodeFn( num ){
    return $('<span style="display: inline-block;">').barcode(encodeURI(num), "code128",{barWidth:2, barHeight:30,showHRI:false});
}

/*
* Preview 参数说明
* _data 要打印的快递信息(JSON)
* expreName　快递名
* size　打印尺寸（备用）0默认100mm*180mm
* logoUrl　快递logo url
**/
function Preview( data, expreInfo ) {
    var LODOP;
    LODOP = getLodop();

    var html = '';
    var _data = data;
    var sender = data.sender;
    for (var i = 0; i < _data['res'].length; i++) {
        var subres = _data['res'][i];
        var _no = parseInt(subres.express)|| '';
 
        LODOP.NewPage();
        LODOP.SET_PRINT_PAGESIZE(1, '100mm', '180mm', '');
        LODOP.ADD_PRINT_TEXT(15, 10, 150, 60, "快递包裹");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 22);
        LODOP.SET_PRINT_STYLEA(0, "Bold", 1);

        LODOP.ADD_PRINT_TEXT(70, 10, 377, 40, _data.sender.city + "转 " + subres.city + "（投递局）");
        /**/
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 14.5);
        LODOP.SET_PRINT_STYLEA(0, "Bold", 1);

        LODOP.ADD_PRINT_TEXT(130, 6, 377, 40, "收\n件");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 14.5);

        LODOP.ADD_PRINT_TEXT(110, 34, 330, 88, subres.geter_name + ' ' + subres.geter_mobile + '\n' + subres.pro + ' ' + subres.city + ' ' + subres.area + ' ' + subres.street);
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 14.5);

        LODOP.ADD_PRINT_TEXT(220, 6, 335, 200, "订单号：" + subres.order_sn);
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 11);

        LODOP.ADD_PRINT_TEXT(240, 6, 335, 200, "　品名：");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 11);

        var ostr = '';
        for (k in subres['order_extInfo']) {
            ostr += subres['order_extInfo'][k]['pname'] + ' * ' + subres['order_extInfo'][k]['sun'] + '\n';
        }

        LODOP.ADD_PRINT_TEXT(240, 70, 335, 200, ostr);    //产品列表
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 11);

        LODOP.ADD_PRINT_TEXT(220, 205, 335, 170, "收件人/代收件人：");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 13.5);

        LODOP.ADD_PRINT_TEXT(340, 205, 335, 170, "(代收款：　　元)");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 13.5);

        LODOP.ADD_PRINT_TEXT(364, 205, 335, 170, "签收时间：");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 11);

        LODOP.ADD_PRINT_TEXT(384, 255, 335, 170, "年　月　日　时");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 11);

        LODOP.ADD_PRINT_BARCODE(426, 10, 188, 37, "128Auto", _no + i);
        LODOP.ADD_PRINT_TEXT(426, 210, 200, 40, "快递包裹");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 22);
        LODOP.SET_PRINT_STYLEA(0, "Bold", 1);

        LODOP.ADD_PRINT_TEXT(490, 6, 377, 40, "收\n件");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 14.5);

        LODOP.ADD_PRINT_TEXT(486, 34, 335, 60, subres.geter_name + ' ' + subres.geter_mobile + '\n' + subres.pro + ' ' + subres.city + ' ' + subres.area + ' ' + subres.street);
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 11);

        LODOP.ADD_PRINT_TEXT(570, 6, 377, 40, "寄\n件");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 14.5);

        LODOP.ADD_PRINT_TEXT(560, 34, 335, 70, sender.fname + sender.cellphone + '\n' + sender.pro + ' ' + sender.city + ' ' + sender.area + ' ' + sender.street + '\n');
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 10.8);

        LODOP.ADD_PRINT_TEXT(600, 180, 90, 40, "(收寄局)");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 14.5);

        LODOP.ADD_PRINT_TEXT(636, 6, 260, 30, "订单号：" + subres.order_sn);
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 11);

       // LODOP.ADD_PRINT_TEXT(660, 6, 280, 30, _no);
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 9);

        LODOP.ADD_PRINT_TEXT(580, 290, 280, 90, "已验视\n验视人：");
        LODOP.SET_PRINT_STYLEA(0, "FontName", "SimHei");
        LODOP.SET_PRINT_STYLEA(0, "FontSize", 11);

        //LODOP.ADD_PRINT_BARCODE(Top,Left,Width,Height,BarCodeType,BarCodeValue);
        LODOP.ADD_PRINT_BARCODE(10, 150, 211, 45, "128Auto", _no);
        //LODOP.ADD_PRINT_LINE(top,left,height,width,0,2);
        LODOP.ADD_PRINT_LINE(60, 0, 60, 377, 0, 1);
        LODOP.ADD_PRINT_LINE(100, 0, 100, 377, 0, 1);
        LODOP.ADD_PRINT_LINE(100, 30, 210, 30, 0, 1);
        LODOP.ADD_PRINT_LINE(210, 0, 210, 377, 0, 1);
        LODOP.ADD_PRINT_LINE(210, 200, 400, 200, 0, 1);
        LODOP.ADD_PRINT_LINE(476, 0, 476, 377, 0, 1);
        LODOP.ADD_PRINT_LINE(550, 0, 550, 377, 0, 1);
        LODOP.ADD_PRINT_LINE(476, 30, 630, 30, 0, 1);
        LODOP.ADD_PRINT_LINE(630, 0, 630, 264, 0, 1);
        //LODOP.ADD_PRINT_LINE(656, 0, 656, 264, 0, 1);
        LODOP.ADD_PRINT_LINE(550, 264, 680, 264, 0, 1);
        LODOP.ADD_PRINT_LINE(415,0,415,377,2,1);//分割线
    }
    LODOP.PREVIEW();
};