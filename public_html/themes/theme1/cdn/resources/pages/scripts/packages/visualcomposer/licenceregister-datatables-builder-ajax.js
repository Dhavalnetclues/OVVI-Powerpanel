var ignoreItems = '';
var selectedItems = '';
var LicenceRegisterDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionLicenceRegisterModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/licence-register/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionLicenceRegisterModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                   
                    status: '',
                    searchValue: $('#sectionLicenceRegisterModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionLicenceRegisterModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionLicenceRegisterModule #record-table tr').length > 1) {
                        $('#sectionLicenceRegisterModule #record-table #not-found').remove();
                    }
                    $('#datatable_licence-register_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
       
        resizeModuleModal: function () {
            var blogsModuleH = 0;
            var blogsModuleW = 0;

            var blogsTableH = 0;


            if ($.cookie('blogsModuleH')) {
                var blogsModuleH = $.cookie('blogsModuleH');
                $('#sectionLicenceRegisterModule .modal-dialog').css('height', blogsModuleH);
                $('#sectionLicenceRegisterModule .modal-content').css('height', blogsModuleH);
            }
            if ($.cookie('blogsModuleW')) {
                var blogsModuleW = $.cookie('blogsModuleW');
                $('#sectionLicenceRegisterModule .modal-dialog').css('width', blogsModuleW);
                $('#sectionLicenceRegisterModule .modal-content').css('width', blogsModuleW);
            }

            if ($.cookie('blogsTableH')) {
                var blogsTableH = $.cookie('blogsTableH');
                $('#sectionLicenceRegisterModule #mcscroll').css('height', blogsTableH);
            }

            $('#sectionLicenceRegisterModule .modal-content').resizable({
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
$(document).on('click', '.licence-register-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionLicenceRegisterModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.licence-register', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionLicenceRegisterModule').modal({
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

$('#sectionLicenceRegisterModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_licence-register_ajax').closest('.col-md-12').loading('start');
    LicenceRegisterDataTable.resizeModuleModal();
    validateSectionLicenceRegister.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    
    $('#frmSectionLicenceRegisterModule #columns option:selected').prop('selected', false);
    $('#frmSectionLicenceRegisterModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionLicenceRegisterModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionLicenceRegisterModule input[name=template]').val(template);
    if (template == 'featured-licence-register') {
        $('#frmSectionLicenceRegisterModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
         extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionLicenceRegisterModule input[name=editing]').val(id);
        $('#frmSectionLicenceRegisterModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionLicenceRegisterModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionLicenceRegisterModule #addSection').text('Update');
        $('#sectionLicenceRegisterModule #exampleModalLabel b').text('Update Licence Register');
    } else {
        $('#frmSectionLicenceRegisterModule input[name=editing]').val('');
        $('#frmSectionLicenceRegisterModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionLicenceRegisterModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionLicenceRegisterModule #addSection').text('Add');
        $('#sectionLicenceRegisterModule #exampleModalLabel b').text('Licence Register');
    }

    $('#frmSectionLicenceRegisterModule #section_title').val(caption);
    $('#frmSectionLicenceRegisterModule #extra_class').val(extraclass);
    $('#frmSectionLicenceRegisterModule #section_description').val(desc);
    $('select').selectpicker();

    LicenceRegisterDataTable.init(start, range);
    $("#frmSectionLicenceRegisterModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_licence-register_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionLicenceRegisterModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            LicenceRegisterDataTable.init(start, range);
                        } else {
                            $('#datatable_licence-register_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionLicenceRegisterModule select[name=layoutType] option[class=list]').show();
    $('#sectionLicenceRegisterModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionLicenceRegister.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionLicenceRegisterModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionLicenceRegisterModule #record-table').html('');
    LicenceRegisterDataTable.init(start, range);
});

$(document).on('change', '#sectionLicenceRegisterModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionLicenceRegisterModule #record-table').html('');
    LicenceRegisterDataTable.init(start, range);
});

//$(document).on('change', '#sectionLicenceRegisterModule #blogscategory-id', function () {
//    range = 10;
//    start = 0;
//    end = range;
//    $('#sectionLicenceRegisterModule #record-table').html('');
//    LicenceRegisterDataTable.init(start, range);
//});


//Group checkbox checking
$(document).on('change', '#sectionLicenceRegisterModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionLicenceRegisterModule #record-table .chkChoose').prop('checked', true);
        $('#sectionLicenceRegisterModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionLicenceRegisterModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionLicenceRegisterModule #record-table .chkChoose').prop('checked', false);
        $('#sectionLicenceRegisterModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionLicenceRegisterModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionLicenceRegisterModule #record-table .chkChoose:checked').length == $('#sectionLicenceRegisterModule #record-table tr .chkChoose').length) {
        $('#sectionLicenceRegisterModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionLicenceRegisterModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionLicenceRegisterModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionLicenceRegisterModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking