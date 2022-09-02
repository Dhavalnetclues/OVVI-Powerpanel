jQuery(function () {
    jQuery('#start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function (ct) {
            this.setOptions({
            })
        },
        timepicker: true
    });
    
    jQuery('#end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function (ct) {
            this.setOptions({
                minDate: jQuery('#start_date_time').val() ? jQuery('#start_date_time').val() : false
            })
        },
        timepicker: true
    });
    
   

});


function KeycheckOnlyDate(e) {
    return false
}