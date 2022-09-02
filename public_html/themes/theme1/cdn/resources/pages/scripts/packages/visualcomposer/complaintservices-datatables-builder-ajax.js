var ignoreItems = '';
var selectedItems = '';
var ComplaintServicesDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionComplaintServicesModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/complaint-services/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionComplaintServicesModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                   
                    status: '',
                    searchValue: $('#sectionComplaintServicesModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionComplaintServicesModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionComplaintServicesModule #record-table tr').length > 1) {
                        $('#sectionComplaintServicesModule #record-table #not-found').remove();
                    }
                    $('#datatable_complaint-services_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
       
        resizeModuleModal: function () {
            var blogsModuleH = 0;
            var blogsModuleW = 0;

            var blogsTableH = 0;


            if ($.cookie('blogsModuleH')) {
                var blogsModuleH = $.cookie('blogsModuleH');
                $('#sectionComplaintServicesModule .modal-dialog').css('height', blogsModuleH);
                $('#sectionComplaintServicesModule .modal-content').css('height', blogsModuleH);
            }
            if ($.cookie('blogsModuleW')) {
                var blogsModuleW = $.cookie('blogsModuleW');
                $('#sectionComplaintServicesModule .modal-dialog').css('width', blogsModuleW);
                $('#sectionComplaintServicesModule .modal-content').css('width', blogsModuleW);
            }

            if ($.cookie('blogsTableH')) {
                var blogsTableH = $.cookie('blogsTableH');
                $('#sectionComplaintServicesModule #mcscroll').css('height', blogsTableH);
            }

            $('#sectionComplaintServicesModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    blogsModuleH = $(this).height();
                    blogsModuleW = $(this).width();

                    blogsTableH = $('#mcscroll').height();
                    blogsTableW = $('#mcscroll').width();

                    $.cookie('blogsTableH', blogsTableH);
                    $.cookie('blogsModuleH', blogsModuleH);
                    $.cookie('blogsModuleW', blogsModuleW);
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
var template = '';
var extraclass = '';
 var desc = '';
$(document).on('click', '.complaint-services-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionComplaintServicesModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.complaint-services', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
     id = $(this).data('id');
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionComplaintServicesModule').modal({
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

$('#sectionComplaintServicesModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_complaint-services_ajax').closest('.col-md-12').loading('start');
    ComplaintServicesDataTable.resizeModuleModal();
    validateSectionComplaintServices.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    
    $('#frmSectionComplaintServicesModule #columns option:selected').prop('selected', false);
    $('#frmSectionComplaintServicesModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionComplaintServicesModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionComplaintServicesModule input[name=template]').val(template);
    if (template == 'featured-complaint-services') {
        $('#frmSectionComplaintServicesModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
         extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionComplaintServicesModule input[name=editing]').val(id);
        $('#frmSectionComplaintServicesModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionComplaintServicesModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionComplaintServicesModule #addSection').text('Update');
        $('#sectionComplaintServicesModule #exampleModalLabel b').text('Update Complaint Services');
    } else {
        $('#frmSectionComplaintServicesModule input[name=editing]').val('');
        $('#frmSectionComplaintServicesModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionComplaintServicesModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionComplaintServicesModule #addSection').text('Add');
        $('#sectionComplaintServicesModule #exampleModalLabel b').text('Complaint Services');
    }

    $('#frmSectionComplaintServicesModule #section_title').val(caption);
    $('#frmSectionComplaintServicesModule #extra_class').val(extraclass);
    $('#frmSectionComplaintServicesModule #section_description').val(desc);
    $('select').selectpicker();

    ComplaintServicesDataTable.init(start, range);
    $("#frmSectionComplaintServicesModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_complaint-services_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionComplaintServicesModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            ComplaintServicesDataTable.init(start, range);
                        } else {
                            $('#datatable_complaint-services_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionComplaintServicesModule select[name=layoutType] option[class=list]').show();
    $('#sectionComplaintServicesModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionComplaintServices.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionComplaintServicesModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionComplaintServicesModule #record-table').html('');
    ComplaintServicesDataTable.init(start, range);
});

$(document).on('change', '#sectionComplaintServicesModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionComplaintServicesModule #record-table').html('');
    ComplaintServicesDataTable.init(start, range);
});

//$(document).on('change', '#sectionComplaintServicesModule #blogscategory-id', function () {
//    range = 10;
//    start = 0;
//    end = range;
//    $('#sectionComplaintServicesModule #record-table').html('');
//    ComplaintServicesDataTable.init(start, range);
//});


//Group checkbox checking
$(document).on('change', '#sectionComplaintServicesModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionComplaintServicesModule #record-table .chkChoose').prop('checked', true);
        $('#sectionComplaintServicesModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionComplaintServicesModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionComplaintServicesModule #record-table .chkChoose').prop('checked', false);
        $('#sectionComplaintServicesModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionComplaintServicesModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionComplaintServicesModule #record-table .chkChoose:checked').length == $('#sectionComplaintServicesModule #record-table tr .chkChoose').length) {
        $('#sectionComplaintServicesModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionComplaintServicesModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionComplaintServicesModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionComplaintServicesModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking