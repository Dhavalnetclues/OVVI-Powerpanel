var ignoreItems = '';
var selectedItems = '';
var RegisterofApplicationsDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionRegisterofApplicationsModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/register-application/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionRegisterofApplicationsModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                   
                    status: '',
                    searchValue: $('#sectionRegisterofApplicationsModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionRegisterofApplicationsModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionRegisterofApplicationsModule #record-table tr').length > 1) {
                        $('#sectionRegisterofApplicationsModule #record-table #not-found').remove();
                    }
                    $('#datatable_registerapplication_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
       
        resizeModuleModal: function () {
            var blogsModuleH = 0;
            var blogsModuleW = 0;

            var blogsTableH = 0;


            if ($.cookie('blogsModuleH')) {
                var blogsModuleH = $.cookie('blogsModuleH');
                $('#sectionRegisterofApplicationsModule .modal-dialog').css('height', blogsModuleH);
                $('#sectionRegisterofApplicationsModule .modal-content').css('height', blogsModuleH);
            }
            if ($.cookie('blogsModuleW')) {
                var blogsModuleW = $.cookie('blogsModuleW');
                $('#sectionRegisterofApplicationsModule .modal-dialog').css('width', blogsModuleW);
                $('#sectionRegisterofApplicationsModule .modal-content').css('width', blogsModuleW);
            }

            if ($.cookie('blogsTableH')) {
                var blogsTableH = $.cookie('blogsTableH');
                $('#sectionRegisterofApplicationsModule #mcscroll').css('height', blogsTableH);
            }

            $('#sectionRegisterofApplicationsModule .modal-content').resizable({
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
$(document).on('click', '.register-application-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionRegisterofApplicationsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.register-application', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionRegisterofApplicationsModule').modal({
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

$('#sectionRegisterofApplicationsModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_registerapplication_ajax').closest('.col-md-12').loading('start');
    RegisterofApplicationsDataTable.resizeModuleModal();
    validateSectionRegisterofApplications.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    
    $('#frmSectionRegisterofApplicationsModule #columns option:selected').prop('selected', false);
    $('#frmSectionRegisterofApplicationsModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionRegisterofApplicationsModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionRegisterofApplicationsModule input[name=template]').val(template);
    if (template == 'featured-register-application') {
        $('#frmSectionRegisterofApplicationsModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
         extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionRegisterofApplicationsModule input[name=editing]').val(id);
        $('#frmSectionRegisterofApplicationsModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionRegisterofApplicationsModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionRegisterofApplicationsModule #addSection').text('Update');
        $('#sectionRegisterofApplicationsModule #exampleModalLabel b').text('Update Register of Applications');
    } else {
        $('#frmSectionRegisterofApplicationsModule input[name=editing]').val('');
        $('#frmSectionRegisterofApplicationsModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionRegisterofApplicationsModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionRegisterofApplicationsModule #addSection').text('Add');
        $('#sectionRegisterofApplicationsModule #exampleModalLabel b').text('Register of Applications');
    }

    $('#frmSectionRegisterofApplicationsModule #section_title').val(caption);
    $('#frmSectionRegisterofApplicationsModule #extra_class').val(extraclass);
    $('#frmSectionRegisterofApplicationsModule #section_description').val(desc);
    $('select').selectpicker();

    RegisterofApplicationsDataTable.init(start, range);
    $("#frmSectionRegisterofApplicationsModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_registerapplication_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionRegisterofApplicationsModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            RegisterofApplicationsDataTable.init(start, range);
                        } else {
                            $('#datatable_registerapplication_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionRegisterofApplicationsModule select[name=layoutType] option[class=list]').show();
    $('#sectionRegisterofApplicationsModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionRegisterofApplications.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionRegisterofApplicationsModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionRegisterofApplicationsModule #record-table').html('');
    RegisterofApplicationsDataTable.init(start, range);
});

$(document).on('change', '#sectionRegisterofApplicationsModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionRegisterofApplicationsModule #record-table').html('');
    RegisterofApplicationsDataTable.init(start, range);
});

//$(document).on('change', '#sectionRegisterofApplicationsModule #blogscategory-id', function () {
//    range = 10;
//    start = 0;
//    end = range;
//    $('#sectionRegisterofApplicationsModule #record-table').html('');
//    RegisterofApplicationsDataTable.init(start, range);
//});


//Group checkbox checking
$(document).on('change', '#sectionRegisterofApplicationsModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionRegisterofApplicationsModule #record-table .chkChoose').prop('checked', true);
        $('#sectionRegisterofApplicationsModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionRegisterofApplicationsModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionRegisterofApplicationsModule #record-table .chkChoose').prop('checked', false);
        $('#sectionRegisterofApplicationsModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionRegisterofApplicationsModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionRegisterofApplicationsModule #record-table .chkChoose:checked').length == $('#sectionRegisterofApplicationsModule #record-table tr .chkChoose').length) {
        $('#sectionRegisterofApplicationsModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionRegisterofApplicationsModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionRegisterofApplicationsModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionRegisterofApplicationsModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking