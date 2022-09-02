var ignoreItems = '';
var selectedItems = '';
var AlertsDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionAlertsModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/alerts/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionAlertsModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    status: '',
                    catValue: $('#sectionAlertsModule #type-id').val(),
                    searchValue: $('#sectionAlertsModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionAlertsModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionAlertsModule #record-table tr').length > 1) {
                        $('#sectionAlertsModule #record-table #not-found').remove();
                    }
                    $('#datatable_alerts_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
       
        resizeModuleModal: function () {
            var alertsModuleH = 0;
            var alertsModuleW = 0;

            var alertsTableH = 0;


            if ($.cookie('alertsModuleH')) {
                var alertsModuleH = $.cookie('alertsModuleH');
                $('#sectionAlertsModule .modal-dialog').css('height', alertsModuleH);
                $('#sectionAlertsModule .modal-content').css('height', alertsModuleH);
            }
            if ($.cookie('alertsModuleW')) {
                var alertsModuleW = $.cookie('alertsModuleW');
                $('#sectionAlertsModule .modal-dialog').css('width', alertsModuleW);
                $('#sectionAlertsModule .modal-content').css('width', alertsModuleW);
            }

            if ($.cookie('alertsTableH')) {
                var alertsTableH = $.cookie('alertsTableH');
                $('#sectionAlertsModule #mcscroll').css('height', alertsTableH);
            }

            $('#sectionAlertsModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    alertsModuleH = $(this).height();
                    alertsModuleW = $(this).width();

                    alertsTableH = $('#mcscroll').height();
                    alertsTableW = $('#mcscroll').width();

                    $.cookie('alertsTableH', alertsTableH);
                    $.cookie('alertsModuleH', alertsModuleH);
                    $.cookie('alertsModuleW', alertsModuleW);
                }
            });
        }
    };
}();

var range = 10;
var start = 0;
var end = range;

//..Open while add or edit section
var id = '';
var caption = '';
var extraclass = '';
var template = '';
$(document).on('click', '.alerts-module', function (event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionAlertsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.alerts', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionAlertsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});
$(document).on('click', '.config-class .dropdown-toggle', function (event) {
    $(".config-class").addClass("open");
});
$(document).on('click', '.layout-class .dropdown-toggle', function (event) {
    $(".layout-class").addClass("open");
});
$(document).on('click', '.cat-class .dropdown-toggle', function (event) {
    $(".cat-class").addClass("open");
});
$(document).on('click', '.sort-class .dropdown-toggle', function (event) {
    $(".sort-class").addClass("open");
});
//..End Open while add or edit section

$('#sectionAlertsModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
   
    $('#datatable_alerts_ajax').closest('.col-md-12').loading('start');
    AlertsDataTable.resizeModuleModal();
    validateSectionAlerts.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    $('#frmSectionAlertsModule #alertscategory-id option:first').prop('selected', true);
    $('#frmSectionAlertsModule #columns option:selected').prop('selected', false);
    $('#frmSectionAlertsModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionAlertsModule input[name=template]').val(template);
  
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
         extraclass = $('#' + id).data('extraclass');
        $('#frmSectionAlertsModule input[name=editing]').val(id);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionAlertsModule #addSection').text('Update');
        $('#sectionAlertsModule #exampleModalLabel b').text('Update Alerts');
    } else {
        $('#frmSectionAlertsModule input[name=editing]').val('');

        $('#sectionAlertsModule #addSection').text('Add');
        $('#sectionAlertsModule #exampleModalLabel b').text('Alerts');
    }

    $('#frmSectionAlertsModule #section_title').val(caption);
    $('#frmSectionAlertsModule #extra_class').val(extraclass);
    $('select').selectpicker();

    AlertsDataTable.init(start, range);
    $("#frmSectionAlertsModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_alerts_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionAlertsModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            AlertsDataTable.init(start, range);
                        } else {
                            $('#datatable_alerts_ajax').closest('.col-md-12').loading('stop');
                        }
                    }, 1000);
                }
            }
        }
    });


}).on('hidden.bs.modal', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionAlertsModule select[name=layoutType] option[class=list]').show();
    $('#sectionAlertsModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionAlerts.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionAlertsModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionAlertsModule #record-table').html('');
    AlertsDataTable.init(start, range);
});

$(document).on('change', '#sectionAlertsModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionAlertsModule #record-table').html('');
    AlertsDataTable.init(start, range);
});

$(document).on('change', '#sectionAlertsModule #type-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionAlertsModule #record-table').html('');
    AlertsDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionAlertsModule .group-checkable', function () {
    if ($(this).prop('checked')) {
        $('#sectionAlertsModule #record-table .chkChoose').prop('checked', true);
        $('#sectionAlertsModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionAlertsModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionAlertsModule #record-table .chkChoose').prop('checked', false);
        $('#sectionAlertsModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionAlertsModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionAlertsModule #record-table .chkChoose:checked').length == $('#sectionAlertsModule #record-table tr .chkChoose').length) {
        $('#sectionAlertsModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionAlertsModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionAlertsModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionAlertsModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking