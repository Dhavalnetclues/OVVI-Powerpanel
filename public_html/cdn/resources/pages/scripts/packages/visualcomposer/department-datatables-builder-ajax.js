var ignoreItems = '';
var selectedItems = '';
var DepartmentDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionDepartmentModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/department/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionDepartmentModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    status: '',
                    searchValue: $('#sectionDepartmentModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionDepartmentModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionDepartmentModule #record-table tr').length > 1) {
                        $('#sectionDepartmentModule #record-table #not-found').remove();
                    }
                    $('#datatable_department_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
       
        resizeModuleModal: function () {
            var departmentModuleH = 0;
            var departmentModuleW = 0;

            var departmentTableH = 0;


            if ($.cookie('departmentModuleH')) {
                var departmentModuleH = $.cookie('departmentModuleH');
                $('#sectionDepartmentModule .modal-dialog').css('height', departmentModuleH);
                $('#sectionDepartmentModule .modal-content').css('height', departmentModuleH);
            }
            if ($.cookie('departmentModuleW')) {
                var departmentModuleW = $.cookie('departmentModuleW');
                $('#sectionDepartmentModule .modal-dialog').css('width', departmentModuleW);
                $('#sectionDepartmentModule .modal-content').css('width', departmentModuleW);
            }

            if ($.cookie('departmentTableH')) {
                var departmentTableH = $.cookie('departmentTableH');
                $('#sectionDepartmentModule #mcscroll').css('height', departmentTableH);
            }


            // $('#sectionDepartmentModule .modal-dialog').css('left','40%');
            // $('#sectionDepartmentModule .modal-dialog').css('top','40%');
            // $('#sectionDepartmentModule .modal-dialog').css('transform','translate(-50%, -50%)');


            $('#sectionDepartmentModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    departmentModuleH = $(this).height();
                    departmentModuleW = $(this).width();

                    departmentTableH = $('#mcscroll').height();
                    departmentTableW = $('#mcscroll').width();

                    $.cookie('departmentTableH', departmentTableH);
                    $.cookie('departmentModuleH', departmentModuleH);
                    $.cookie('departmentModuleW', departmentModuleW);
                }
            });
            // .resize(function(){
            //     if ($('#sectionDepartmentModule #record-table tr').length < $('input[name=total_records]').val()) {
            //         start += range;
            //         end += range;
            //         DepartmentDataTable.init(start, range);
            //     } 
            //  });
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
$(document).on('click', '.department-module', function (event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionDepartmentModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.department', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionDepartmentModule').modal({
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

$('#sectionDepartmentModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    $('#datatable_department_ajax').closest('.col-md-12').loading('start');
    DepartmentDataTable.resizeModuleModal();
    validateSectionDepartment.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
//    DepartmentDataTable.getCategory();
    $('#frmSectionDepartmentModule #departmentcategory-id option:first').prop('selected', true);
    $('#frmSectionDepartmentModule #columns option:selected').prop('selected', false);
    $('#frmSectionDepartmentModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionDepartmentModule input[name=template]').val(template);
  
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        $('#frmSectionDepartmentModule input[name=editing]').val(id);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionDepartmentModule #addSection').text('Update');
        $('#sectionDepartmentModule #exampleModalLabel b').text('Update Department');
    } else {
        $('#frmSectionDepartmentModule input[name=editing]').val('');

        $('#sectionDepartmentModule #addSection').text('Add');
        $('#sectionDepartmentModule #exampleModalLabel b').text('Department');
    }

    $('#frmSectionDepartmentModule #section_title').val(caption);
    $('select').selectpicker();

    DepartmentDataTable.init(start, range);
    $("#frmSectionDepartmentModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_department_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionDepartmentModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            DepartmentDataTable.init(start, range);
                        } else {
                            $('#datatable_department_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionDepartmentModule select[name=layoutType] option[class=list]').show();
    $('#sectionDepartmentModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionDepartment.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionDepartmentModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionDepartmentModule #record-table').html('');
    DepartmentDataTable.init(start, range);
});

$(document).on('change', '#sectionDepartmentModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionDepartmentModule #record-table').html('');
    DepartmentDataTable.init(start, range);
});

$(document).on('change', '#sectionDepartmentModule #departmentcategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionDepartmentModule #record-table').html('');
    DepartmentDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionDepartmentModule .group-checkable', function () {
    if ($(this).prop('checked')) {
        $('#sectionDepartmentModule #record-table .chkChoose').prop('checked', true);
        $('#sectionDepartmentModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionDepartmentModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionDepartmentModule #record-table .chkChoose').prop('checked', false);
        $('#sectionDepartmentModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionDepartmentModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionDepartmentModule #record-table .chkChoose:checked').length == $('#sectionDepartmentModule #record-table tr .chkChoose').length) {
        $('#sectionDepartmentModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionDepartmentModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionDepartmentModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionDepartmentModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking