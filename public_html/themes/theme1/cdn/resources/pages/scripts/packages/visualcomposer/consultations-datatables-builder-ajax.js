var ignoreItems = '';
var selectedItems = '';
var ConsultationsDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionConsultationsModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/consultations/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionConsultationsModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionConsultationsModule #blogscategory-id').val(),
                    status: '',
                    searchValue: $('#sectionConsultationsModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionConsultationsModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionConsultationsModule #record-table tr').length > 1) {
                        $('#sectionConsultationsModule #record-table #not-found').remove();
                    }
                    $('#datatable_consultations_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
        
        resizeModuleModal: function () {
            var blogsModuleH = 0;
            var blogsModuleW = 0;

            var blogsTableH = 0;


            if ($.cookie('blogsModuleH')) {
                var blogsModuleH = $.cookie('blogsModuleH');
                $('#sectionConsultationsModule .modal-dialog').css('height', blogsModuleH);
                $('#sectionConsultationsModule .modal-content').css('height', blogsModuleH);
            }
            if ($.cookie('blogsModuleW')) {
                var blogsModuleW = $.cookie('blogsModuleW');
                $('#sectionConsultationsModule .modal-dialog').css('width', blogsModuleW);
                $('#sectionConsultationsModule .modal-content').css('width', blogsModuleW);
            }

            if ($.cookie('blogsTableH')) {
                var blogsTableH = $.cookie('blogsTableH');
                $('#sectionConsultationsModule #mcscroll').css('height', blogsTableH);
            }

            $('#sectionConsultationsModule .modal-content').resizable({
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
$(document).on('click', '.consultations-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionConsultationsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.consultations', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionConsultationsModule').modal({
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

$('#sectionConsultationsModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_consultations_ajax').closest('.col-md-12').loading('start');
    ConsultationsDataTable.resizeModuleModal();
    validateSectionConsultations.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    
    $('#frmSectionConsultationsModule #blogscategory-id option:first').prop('selected', true);
    $('#frmSectionConsultationsModule #columns option:selected').prop('selected', false);
    $('#frmSectionConsultationsModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionConsultationsModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionConsultationsModule input[name=template]').val(template);
    if (template == 'featured-consultations') {
        $('#frmSectionConsultationsModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
         extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionConsultationsModule input[name=editing]').val(id);
        $('#frmSectionConsultationsModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionConsultationsModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionConsultationsModule #addSection').text('Update');
        $('#sectionConsultationsModule #exampleModalLabel b').text('Update Consultations');
    } else {
        $('#frmSectionConsultationsModule input[name=editing]').val('');
        $('#frmSectionConsultationsModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionConsultationsModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionConsultationsModule #addSection').text('Add');
        $('#sectionConsultationsModule #exampleModalLabel b').text('Consultations');
    }

    $('#frmSectionConsultationsModule #section_title').val(caption);
    $('#frmSectionConsultationsModule #extra_class').val(extraclass);
    $('#frmSectionConsultationsModule #section_description').val(desc);
    $('select').selectpicker();

    ConsultationsDataTable.init(start, range);
    $("#frmSectionConsultationsModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_consultations_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionConsultationsModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            ConsultationsDataTable.init(start, range);
                        } else {
                            $('#datatable_consultations_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionConsultationsModule select[name=layoutType] option[class=list]').show();
    $('#sectionConsultationsModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionConsultations.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionConsultationsModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionConsultationsModule #record-table').html('');
    ConsultationsDataTable.init(start, range);
});

$(document).on('change', '#sectionConsultationsModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionConsultationsModule #record-table').html('');
    ConsultationsDataTable.init(start, range);
});

$(document).on('change', '#sectionConsultationsModule #blogscategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionConsultationsModule #record-table').html('');
    ConsultationsDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionConsultationsModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionConsultationsModule #record-table .chkChoose').prop('checked', true);
        $('#sectionConsultationsModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionConsultationsModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionConsultationsModule #record-table .chkChoose').prop('checked', false);
        $('#sectionConsultationsModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionConsultationsModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionConsultationsModule #record-table .chkChoose:checked').length == $('#sectionConsultationsModule #record-table tr .chkChoose').length) {
        $('#sectionConsultationsModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionConsultationsModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionConsultationsModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionConsultationsModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking