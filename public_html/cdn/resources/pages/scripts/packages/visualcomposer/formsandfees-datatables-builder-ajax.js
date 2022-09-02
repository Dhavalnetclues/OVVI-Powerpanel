var ignoreItems = '';
var selectedItems = '';
var FormsandFeesDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionFormsandFeesModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/forms-and-fees/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionFormsandFeesModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                   
                    status: '',
                    searchValue: $('#sectionFormsandFeesModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionFormsandFeesModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionFormsandFeesModule #record-table tr').length > 1) {
                        $('#sectionFormsandFeesModule #record-table #not-found').remove();
                    }
                    $('#datatable_forms-and-fees_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
       
        resizeModuleModal: function () {
            var blogsModuleH = 0;
            var blogsModuleW = 0;

            var blogsTableH = 0;


            if ($.cookie('blogsModuleH')) {
                var blogsModuleH = $.cookie('blogsModuleH');
                $('#sectionFormsandFeesModule .modal-dialog').css('height', blogsModuleH);
                $('#sectionFormsandFeesModule .modal-content').css('height', blogsModuleH);
            }
            if ($.cookie('blogsModuleW')) {
                var blogsModuleW = $.cookie('blogsModuleW');
                $('#sectionFormsandFeesModule .modal-dialog').css('width', blogsModuleW);
                $('#sectionFormsandFeesModule .modal-content').css('width', blogsModuleW);
            }

            if ($.cookie('blogsTableH')) {
                var blogsTableH = $.cookie('blogsTableH');
                $('#sectionFormsandFeesModule #mcscroll').css('height', blogsTableH);
            }

            $('#sectionFormsandFeesModule .modal-content').resizable({
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
$(document).on('click', '.forms-and-fees-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionFormsandFeesModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.forms-and-fees', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionFormsandFeesModule').modal({
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

$('#sectionFormsandFeesModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_forms-and-fees_ajax').closest('.col-md-12').loading('start');
    FormsandFeesDataTable.resizeModuleModal();
    validateSectionFormsandFees.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    
    $('#frmSectionFormsandFeesModule #columns option:selected').prop('selected', false);
    $('#frmSectionFormsandFeesModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionFormsandFeesModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionFormsandFeesModule input[name=template]').val(template);
    if (template == 'featured-forms-and-fees') {
        $('#frmSectionFormsandFeesModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
         extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionFormsandFeesModule input[name=editing]').val(id);
        $('#frmSectionFormsandFeesModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionFormsandFeesModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionFormsandFeesModule #addSection').text('Update');
        $('#sectionFormsandFeesModule #exampleModalLabel b').text('Update Forms and Fees');
    } else {
        $('#frmSectionFormsandFeesModule input[name=editing]').val('');
        $('#frmSectionFormsandFeesModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionFormsandFeesModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionFormsandFeesModule #addSection').text('Add');
        $('#sectionFormsandFeesModule #exampleModalLabel b').text('Forms and Fees');
    }

    $('#frmSectionFormsandFeesModule #section_title').val(caption);
    $('#frmSectionFormsandFeesModule #extra_class').val(extraclass);
    $('#frmSectionFormsandFeesModule #section_description').val(desc);
    $('select').selectpicker();

    FormsandFeesDataTable.init(start, range);
    $("#frmSectionFormsandFeesModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_forms-and-fees_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionFormsandFeesModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            FormsandFeesDataTable.init(start, range);
                        } else {
                            $('#datatable_forms-and-fees_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionFormsandFeesModule select[name=layoutType] option[class=list]').show();
    $('#sectionFormsandFeesModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionFormsandFees.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionFormsandFeesModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionFormsandFeesModule #record-table').html('');
    FormsandFeesDataTable.init(start, range);
});

$(document).on('change', '#sectionFormsandFeesModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionFormsandFeesModule #record-table').html('');
    FormsandFeesDataTable.init(start, range);
});

//$(document).on('change', '#sectionFormsandFeesModule #blogscategory-id', function () {
//    range = 10;
//    start = 0;
//    end = range;
//    $('#sectionFormsandFeesModule #record-table').html('');
//    FormsandFeesDataTable.init(start, range);
//});


//Group checkbox checking
$(document).on('change', '#sectionFormsandFeesModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionFormsandFeesModule #record-table .chkChoose').prop('checked', true);
        $('#sectionFormsandFeesModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionFormsandFeesModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionFormsandFeesModule #record-table .chkChoose').prop('checked', false);
        $('#sectionFormsandFeesModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionFormsandFeesModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionFormsandFeesModule #record-table .chkChoose:checked').length == $('#sectionFormsandFeesModule #record-table tr .chkChoose').length) {
        $('#sectionFormsandFeesModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionFormsandFeesModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionFormsandFeesModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionFormsandFeesModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking