var areaJson = null;
function showLocation(province , city , town) {
    $.getJSON('/static/admin/area/location.json',function(data){
        areaJson = data;
        initArea(province , city , town);
    });

}
function initArea(province , city , town){
    var loc	= new Location(areaJson);
    var title	= ['省份' , '地级市' , '市、县、区'];
    $.each(title , function(k , v) {
        title[k]	= '<option value="">'+v+'</option>';
    })
    $('#loc_province').append(title[0]);
    $('#loc_city').append(title[1]);
    $('#loc_town').append(title[2]);

    $('#loc_province').change(function() {
        $('#loc_city').empty();
        $('#loc_city').append(title[1]);
        loc.fillOption('loc_city' , '0,'+$('#loc_province').val());
        $('#loc_city').change()
    })

    $('#loc_city').change(function() {
        $('#loc_town').empty();
        $('#loc_town').append(title[2]);
        loc.fillOption('loc_town' , '0,' + $('#loc_province').val() + ',' + $('#loc_city').val());
    })

    $('#loc_town').change(function() {
        $('input[name=location_id]').val($(this).val());
    });

    if (province) {
        loc.fillOption('loc_province' , '0' , province);
        $('#loc_province option[value='+province+']').prop('selected',true);
        if (city) {
            loc.fillOption('loc_city' , '0,'+province , city);
            $('#loc_city option[value='+city+']').prop('selected',true);
            if (town) {
                loc.fillOption('loc_town' , '0,'+province+','+city , town);
                $('#loc_town option[value='+town+']').prop('selected',true);
            }
        }

    } else {
        loc.fillOption('loc_province' , '0');
    }
};