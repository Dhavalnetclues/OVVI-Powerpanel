var ignoreItems = '';
var selectedItems = '';
var BoardofDirectorsDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to,pagination) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionBoardofDirectorsModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/boardofdirectors/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionBoardofDirectorsModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                   
                    status: '',
                    searchValue: $('#sectionBoardofDirectorsModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionBoardofDirectorsModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                    if(pagination == false ){
                        
                    if(result.recordsTotal == 0 || result.found == 0 && ignoreItems == '' ) {
                       
                        $('#frmSectionBoardofDirectorsModule').find('#addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionBoardofDirectorsModule').find('#addSection').removeAttr('disabled');
                    }
                }
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionBoardofDirectorsModule #record-table tr').length > 1) {
                        $('#sectionBoardofDirectorsModule #record-table #not-found').remove();
                    }
                    $('#datatable_boardofdirectors_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
       
        resizeModuleModal: function () {
            var blogsModuleH = 0;
            var blogsModuleW = 0;

            var blogsTableH = 0;


            if ($.cookie('blogsModuleH')) {
                var blogsModuleH = $.cookie('blogsModuleH');
                $('#sectionBoardofDirectorsModule .modal-dialog').css('height', blogsModuleH);
                $('#sectionBoardofDirectorsModule .modal-content').css('height', blogsModuleH);
            }
            if ($.cookie('blogsModuleW')) {
                var blogsModuleW = $.cookie('blogsModuleW');
                $('#sectionBoardofDirectorsModule .modal-dialog').css('width', blogsModuleW);
                $('#sectionBoardofDirectorsModule .modal-content').css('width', blogsModuleW);
            }

            if ($.cookie('blogsTableH')) {
                var blogsTableH = $.cookie('blogsTableH');
                $('#sectionBoardofDirectorsModule #mcscroll').css('height', blogsTableH);
            }

            $('#sectionBoardofDirectorsModule .modal-content').resizable({
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
$(document).on('click', '.boardofdirectors-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionBoardofDirectorsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.boardofdirectors', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionBoardofDirectorsModule').modal({
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

$('#sectionBoardofDirectorsModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_boardofdirectors_ajax').closest('.col-md-12').loading('start');
    BoardofDirectorsDataTable.resizeModuleModal();
    validateSectionBoardofDirectors.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    
    $('#frmSectionBoardofDirectorsModule #columns option:selected').prop('selected', false);
    $('#frmSectionBoardofDirectorsModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionBoardofDirectorsModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionBoardofDirectorsModule input[name=template]').val(template);
    if (template == 'featured-boardofdirectors') {
        $('#frmSectionBoardofDirectorsModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
         extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionBoardofDirectorsModule input[name=editing]').val(id);
        $('#frmSectionBoardofDirectorsModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionBoardofDirectorsModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionBoardofDirectorsModule #addSection').text('Update');
        $('#sectionBoardofDirectorsModule #exampleModalLabel b').text('Update Board of Directors');
    } else {
        $('#frmSectionBoardofDirectorsModule input[name=editing]').val('');
        $('#frmSectionBoardofDirectorsModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionBoardofDirectorsModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionBoardofDirectorsModule #addSection').text('Add');
        $('#sectionBoardofDirectorsModule #exampleModalLabel b').text('Board of Directors');
    }

    $('#frmSectionBoardofDirectorsModule #section_title').val(caption);
    $('#frmSectionBoardofDirectorsModule #extra_class').val(extraclass);
    $('#frmSectionBoardofDirectorsModule #section_description').val(desc);
    $('select').selectpicker();

    BoardofDirectorsDataTable.init(start, range,false);
    $("#frmSectionBoardofDirectorsModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_boardofdirectors_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionBoardofDirectorsModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            BoardofDirectorsDataTable.init(start, range,true);
                        } else {
                            $('#datatable_boardofdirectors_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionBoardofDirectorsModule select[name=layoutType] option[class=list]').show();
    $('#sectionBoardofDirectorsModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionBoardofDirectors.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionBoardofDirectorsModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionBoardofDirectorsModule #record-table').html('');
    BoardofDirectorsDataTable.init(start, range);
});

$(document).on('change', '#sectionBoardofDirectorsModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionBoardofDirectorsModule #record-table').html('');
    BoardofDirectorsDataTable.init(start, range);
});

//$(document).on('change', '#sectionBoardofDirectorsModule #blogscategory-id', function () {
//    range = 10;
//    start = 0;
//    end = range;
//    $('#sectionBoardofDirectorsModule #record-table').html('');
//    BoardofDirectorsDataTable.init(start, range);
//});


//Group checkbox checking
$(document).on('change', '#sectionBoardofDirectorsModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionBoardofDirectorsModule #record-table .chkChoose').prop('checked', true);
        $('#sectionBoardofDirectorsModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionBoardofDirectorsModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionBoardofDirectorsModule #record-table .chkChoose').prop('checked', false);
        $('#sectionBoardofDirectorsModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionBoardofDirectorsModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionBoardofDirectorsModule #record-table .chkChoose:checked').length == $('#sectionBoardofDirectorsModule #record-table tr .chkChoose').length) {
        $('#sectionBoardofDirectorsModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionBoardofDirectorsModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionBoardofDirectorsModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionBoardofDirectorsModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking