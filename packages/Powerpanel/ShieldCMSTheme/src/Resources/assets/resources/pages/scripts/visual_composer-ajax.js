function getVisualComposer() {
    console.log('in get method')
    var ajaxurl = site_url + '/powerpanel/visualcomposer/get_Visual_data';
    $.ajax({
        url: ajaxurl,
        type: "GET",
        beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-Token', $('input[name="_token"]').val());},
        success: function (data) {
            visual_data = JSON.parse(data)
            console.log(visual_data)
        },
        // complete: function () {
            
        // },
        error: function () {
            console.log('error!');
        }
    });
}


jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });

    getVisualComposer();

});
