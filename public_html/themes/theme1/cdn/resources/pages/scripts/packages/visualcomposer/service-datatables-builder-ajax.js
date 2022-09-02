var ignoreItems = '';
var selectedItems = '';
var ServiceDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionServiceModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/service/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionServiceModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionServiceModule #servicecategory-id').val(),
                    status: '',
                    searchValue: $('#sectionServiceModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionServiceModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionServiceModule #record-table tr').length > 1) {
                        $('#sectionServiceModule #record-table #not-found').remove();
                    }
                    $('#datatable_service_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
        getCategory: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var ajaxUrl = site_url + '/powerpanel/service-category/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionServiceModule #servicecategory-id').html(result);
                },
                complete: function () {

                }
            });
        },
        resizeModuleModal: function () {
            var blogsModuleH = 0;
            var blogsModuleW = 0;

            var blogsTableH = 0;


            if ($.cookie('blogsModuleH')) {
                var blogsModuleH = $.cookie('blogsModuleH');
                $('#sectionServiceModule .modal-dialog').css('height', blogsModuleH);
                $('#sectionServiceModule .modal-content').css('height', blogsModuleH);
            }
            if ($.cookie('blogsModuleW')) {
                var blogsModuleW = $.cookie('blogsModuleW');
                $('#sectionServiceModule .modal-dialog').css('width', blogsModuleW);
                $('#sectionServiceModule .modal-content').css('width', blogsModuleW);
            }

            if ($.cookie('blogsTableH')) {
                var blogsTableH = $.cookie('blogsTableH');
                $('#sectionServiceModule #mcscroll').css('height', blogsTableH);
            }

            $('#sectionServiceModule .modal-content').resizable({
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
$(document).on('click', '.service-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionServiceModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.service', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionServiceModule').modal({
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

$('#sectionServiceModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_service_ajax').closest('.col-md-12').loading('start');
    ServiceDataTable.resizeModuleModal();
    validateSectionService.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    ServiceDataTable.getCategory();
    $('#frmSectionServiceModule #servicecategory-id option:first').prop('selected', true);
    $('#frmSectionServiceModule #columns option:selected').prop('selected', false);
    $('#frmSectionServiceModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionServiceModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionServiceModule input[name=template]').val(template);
    if (template == 'featured-service') {
        $('#frmSectionServiceModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
         extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionServiceModule input[name=editing]').val(id);
        $('#frmSectionServiceModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionServiceModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionServiceModule #addSection').text('Update');
        $('#sectionServiceModule #exampleModalLabel b').text('Update Service');
    } else {
        $('#frmSectionServiceModule input[name=editing]').val('');
        $('#frmSectionServiceModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionServiceModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionServiceModule #addSection').text('Add');
        $('#sectionServiceModule #exampleModalLabel b').text('Service');
    }

    $('#frmSectionServiceModule #section_title').val(caption);
    $('#frmSectionServiceModule #extra_class').val(extraclass);
    $('#frmSectionServiceModule #section_description').val(desc);
    $('select').selectpicker();

    ServiceDataTable.init(start, range);
    $("#frmSectionServiceModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_service_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionServiceModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            ServiceDataTable.init(start, range);
                        } else {
                            $('#datatable_service_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionServiceModule select[name=layoutType] option[class=list]').show();
    $('#sectionServiceModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionService.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionServiceModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionServiceModule #record-table').html('');
    ServiceDataTable.init(start, range);
});

$(document).on('change', '#sectionServiceModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionServiceModule #record-table').html('');
    ServiceDataTable.init(start, range);
});

$(document).on('change', '#sectionServiceModule #servicecategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionServiceModule #record-table').html('');
    ServiceDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionServiceModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionServiceModule #record-table .chkChoose').prop('checked', true);
        $('#sectionServiceModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionServiceModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionServiceModule #record-table .chkChoose').prop('checked', false);
        $('#sectionServiceModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionServiceModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionServiceModule #record-table .chkChoose:checked').length == $('#sectionServiceModule #record-table tr .chkChoose').length) {
        $('#sectionServiceModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionServiceModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionServiceModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionServiceModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking