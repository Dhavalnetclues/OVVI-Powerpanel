var ignoreItems = '';
var selectedItems = '';
var CandWServiceDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionCandWServiceModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/candwservice/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionCandWServiceModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                   
                    status: '',
                    searchValue: $('#sectionCandWServiceModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionCandWServiceModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionCandWServiceModule #record-table tr').length > 1) {
                        $('#sectionCandWServiceModule #record-table #not-found').remove();
                    }
                    $('#datatable_candwservice_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
      
        resizeModuleModal: function () {
            var blogsModuleH = 0;
            var blogsModuleW = 0;

            var blogsTableH = 0;


            if ($.cookie('blogsModuleH')) {
                var blogsModuleH = $.cookie('blogsModuleH');
                $('#sectionCandWServiceModule .modal-dialog').css('height', blogsModuleH);
                $('#sectionCandWServiceModule .modal-content').css('height', blogsModuleH);
            }
            if ($.cookie('blogsModuleW')) {
                var blogsModuleW = $.cookie('blogsModuleW');
                $('#sectionCandWServiceModule .modal-dialog').css('width', blogsModuleW);
                $('#sectionCandWServiceModule .modal-content').css('width', blogsModuleW);
            }

            if ($.cookie('blogsTableH')) {
                var blogsTableH = $.cookie('blogsTableH');
                $('#sectionCandWServiceModule #mcscroll').css('height', blogsTableH);
            }

            $('#sectionCandWServiceModule .modal-content').resizable({
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
$(document).on('click', '.candwservice-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionCandWServiceModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.candwservice', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionCandWServiceModule').modal({
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

$('#sectionCandWServiceModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_candwservice_ajax').closest('.col-md-12').loading('start');
    CandWServiceDataTable.resizeModuleModal();
    validateSectionCandWService.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
   
   
    $('#frmSectionCandWServiceModule #columns option:selected').prop('selected', false);
    $('#frmSectionCandWServiceModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionCandWServiceModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionCandWServiceModule input[name=template]').val(template);
    if (template == 'featured-candwservice') {
        $('#frmSectionCandWServiceModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
         extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionCandWServiceModule input[name=editing]').val(id);
        $('#frmSectionCandWServiceModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionCandWServiceModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionCandWServiceModule #addSection').text('Update');
        $('#sectionCandWServiceModule #exampleModalLabel b').text('Update C&W service');
    } else {
        $('#frmSectionCandWServiceModule input[name=editing]').val('');
        $('#frmSectionCandWServiceModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionCandWServiceModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionCandWServiceModule #addSection').text('Add');
        $('#sectionCandWServiceModule #exampleModalLabel b').text('C&W service');
    }

    $('#frmSectionCandWServiceModule #section_title').val(caption);
    $('#frmSectionCandWServiceModule #extra_class').val(extraclass);
    $('#frmSectionCandWServiceModule #section_description').val(desc);
    $('select').selectpicker();

    CandWServiceDataTable.init(start, range);
    $("#frmSectionCandWServiceModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_candwservice_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionCandWServiceModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            CandWServiceDataTable.init(start, range);
                        } else {
                            $('#datatable_candwservice_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionCandWServiceModule select[name=layoutType] option[class=list]').show();
    $('#sectionCandWServiceModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionCandWService.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionCandWServiceModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionCandWServiceModule #record-table').html('');
    CandWServiceDataTable.init(start, range);
});

$(document).on('change', '#sectionCandWServiceModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionCandWServiceModule #record-table').html('');
    CandWServiceDataTable.init(start, range);
});




//Group checkbox checking
$(document).on('change', '#sectionCandWServiceModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionCandWServiceModule #record-table .chkChoose').prop('checked', true);
        $('#sectionCandWServiceModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionCandWServiceModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionCandWServiceModule #record-table .chkChoose').prop('checked', false);
        $('#sectionCandWServiceModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionCandWServiceModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionCandWServiceModule #record-table .chkChoose:checked').length == $('#sectionCandWServiceModule #record-table tr .chkChoose').length) {
        $('#sectionCandWServiceModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionCandWServiceModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionCandWServiceModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionCandWServiceModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking