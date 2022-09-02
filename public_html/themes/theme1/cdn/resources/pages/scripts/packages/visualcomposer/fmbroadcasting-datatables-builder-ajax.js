var ignoreItems = '';
var selectedItems = '';
var FMBroadcastingDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionFMBroadcastingModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/fmbroadcasting/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionFMBroadcastingModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                   
                    status: '',
                    searchValue: $('#sectionFMBroadcastingModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionFMBroadcastingModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionFMBroadcastingModule #record-table tr').length > 1) {
                        $('#sectionFMBroadcastingModule #record-table #not-found').remove();
                    }
                    $('#datatable_fmbroadcasting_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
       
        resizeModuleModal: function () {
            var blogsModuleH = 0;
            var blogsModuleW = 0;

            var blogsTableH = 0;


            if ($.cookie('blogsModuleH')) {
                var blogsModuleH = $.cookie('blogsModuleH');
                $('#sectionFMBroadcastingModule .modal-dialog').css('height', blogsModuleH);
                $('#sectionFMBroadcastingModule .modal-content').css('height', blogsModuleH);
            }
            if ($.cookie('blogsModuleW')) {
                var blogsModuleW = $.cookie('blogsModuleW');
                $('#sectionFMBroadcastingModule .modal-dialog').css('width', blogsModuleW);
                $('#sectionFMBroadcastingModule .modal-content').css('width', blogsModuleW);
            }

            if ($.cookie('blogsTableH')) {
                var blogsTableH = $.cookie('blogsTableH');
                $('#sectionFMBroadcastingModule #mcscroll').css('height', blogsTableH);
            }

            $('#sectionFMBroadcastingModule .modal-content').resizable({
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
$(document).on('click', '.fmbroadcasting-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionFMBroadcastingModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.fmbroadcasting', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionFMBroadcastingModule').modal({
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

$('#sectionFMBroadcastingModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_fmbroadcasting_ajax').closest('.col-md-12').loading('start');
    FMBroadcastingDataTable.resizeModuleModal();
    validateSectionFMBroadcasting.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    
    $('#frmSectionFMBroadcastingModule #columns option:selected').prop('selected', false);
    $('#frmSectionFMBroadcastingModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionFMBroadcastingModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionFMBroadcastingModule input[name=template]').val(template);
    if (template == 'featured-fmbroadcasting') {
        $('#frmSectionFMBroadcastingModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
         extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionFMBroadcastingModule input[name=editing]').val(id);
        $('#frmSectionFMBroadcastingModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionFMBroadcastingModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionFMBroadcastingModule #addSection').text('Update');
        $('#sectionFMBroadcastingModule #exampleModalLabel b').text('Update FM Broadcasting');
    } else {
        $('#frmSectionFMBroadcastingModule input[name=editing]').val('');
        $('#frmSectionFMBroadcastingModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionFMBroadcastingModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionFMBroadcastingModule #addSection').text('Add');
        $('#sectionFMBroadcastingModule #exampleModalLabel b').text('FM Broadcasting');
    }

    $('#frmSectionFMBroadcastingModule #section_title').val(caption);
    $('#frmSectionFMBroadcastingModule #extra_class').val(extraclass);
    $('#frmSectionFMBroadcastingModule #section_description').val(desc);
    $('select').selectpicker();

    FMBroadcastingDataTable.init(start, range);
    $("#frmSectionFMBroadcastingModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_fmbroadcasting_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionFMBroadcastingModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            FMBroadcastingDataTable.init(start, range);
                        } else {
                            $('#datatable_fmbroadcasting_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionFMBroadcastingModule select[name=layoutType] option[class=list]').show();
    $('#sectionFMBroadcastingModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionFMBroadcasting.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionFMBroadcastingModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionFMBroadcastingModule #record-table').html('');
    FMBroadcastingDataTable.init(start, range);
});

$(document).on('change', '#sectionFMBroadcastingModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionFMBroadcastingModule #record-table').html('');
    FMBroadcastingDataTable.init(start, range);
});

//$(document).on('change', '#sectionFMBroadcastingModule #blogscategory-id', function () {
//    range = 10;
//    start = 0;
//    end = range;
//    $('#sectionFMBroadcastingModule #record-table').html('');
//    FMBroadcastingDataTable.init(start, range);
//});


//Group checkbox checking
$(document).on('change', '#sectionFMBroadcastingModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionFMBroadcastingModule #record-table .chkChoose').prop('checked', true);
        $('#sectionFMBroadcastingModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionFMBroadcastingModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionFMBroadcastingModule #record-table .chkChoose').prop('checked', false);
        $('#sectionFMBroadcastingModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionFMBroadcastingModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionFMBroadcastingModule #record-table .chkChoose:checked').length == $('#sectionFMBroadcastingModule #record-table tr .chkChoose').length) {
        $('#sectionFMBroadcastingModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionFMBroadcastingModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionFMBroadcastingModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionFMBroadcastingModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking