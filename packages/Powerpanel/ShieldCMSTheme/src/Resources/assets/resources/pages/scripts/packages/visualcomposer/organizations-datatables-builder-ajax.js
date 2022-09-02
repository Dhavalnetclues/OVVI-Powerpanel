var ignoreItems = '';
var selectedItems = '';
var OrganizationsDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionOrganizationsModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/organizations/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionOrganizationsModule input[name=template]').val(),
                    status: '',
                    searchValue: $('#sectionOrganizationsModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionOrganizationsModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionOrganizationsModule #record-table tr').length > 1) {
                        $('#sectionOrganizationsModule #record-table #not-found').remove();
                    }
                    $('#datatable_organizations_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
       
        resizeModuleModal: function () {
            var organizationsModuleH = 0;
            var organizationsModuleW = 0;

            var organizationsTableH = 0;


            if ($.cookie('organizationsModuleH')) {
                var organizationsModuleH = $.cookie('organizationsModuleH');
                $('#sectionOrganizationsModule .modal-dialog').css('height', organizationsModuleH);
                $('#sectionOrganizationsModule .modal-content').css('height', organizationsModuleH);
            }
            if ($.cookie('organizationsModuleW')) {
                var organizationsModuleW = $.cookie('organizationsModuleW');
                $('#sectionOrganizationsModule .modal-dialog').css('width', organizationsModuleW);
                $('#sectionOrganizationsModule .modal-content').css('width', organizationsModuleW);
            }

            if ($.cookie('organizationsTableH')) {
                var organizationsTableH = $.cookie('organizationsTableH');
                $('#sectionOrganizationsModule #mcscroll').css('height', organizationsTableH);
            }
            $('#sectionOrganizationsModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    organizationsModuleH = $(this).height();
                    organizationsModuleW = $(this).width();

                    organizationsTableH = $('#mcscroll').height();
                    organizationsTableW = $('#mcscroll').width();

                    $.cookie('organizationsTableH', organizationsTableH);
                    $.cookie('organizationsModuleH', organizationsModuleH);
                    $.cookie('organizationsModuleW', organizationsModuleW);
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
$(document).on('click', '.organizations-module', function (event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionOrganizationsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.organizations', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionOrganizationsModule').modal({
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

$('#sectionOrganizationsModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    $('#datatable_organizations_ajax').closest('.col-md-12').loading('start');
    OrganizationsDataTable.resizeModuleModal();
    validateSectionOrganizations.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
//    OrganizationsDataTable.getCategory();
    $('#frmSectionOrganizationsModule #organizationscategory-id option:first').prop('selected', true);
    $('#frmSectionOrganizationsModule #columns option:selected').prop('selected', false);
    $('#frmSectionOrganizationsModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionOrganizationsModule input[name=template]').val(template);
  
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        $('#frmSectionOrganizationsModule input[name=editing]').val(id);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionOrganizationsModule #addSection').text('Update');
        $('#sectionOrganizationsModule #exampleModalLabel b').text('Update Organizations');
    } else {
        $('#frmSectionOrganizationsModule input[name=editing]').val('');

        $('#sectionOrganizationsModule #addSection').text('Add');
        $('#sectionOrganizationsModule #exampleModalLabel b').text('Organizations');
    }

    $('#frmSectionOrganizationsModule #section_title').val(caption);
    $('select').selectpicker();

    OrganizationsDataTable.init(start, range);
    $("#frmSectionOrganizationsModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_organizations_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionOrganizationsModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            OrganizationsDataTable.init(start, range);
                        } else {
                            $('#datatable_organizations_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionOrganizationsModule select[name=layoutType] option[class=list]').show();
    $('#sectionOrganizationsModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionOrganizations.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionOrganizationsModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionOrganizationsModule #record-table').html('');
    OrganizationsDataTable.init(start, range);
});

$(document).on('change', '#sectionOrganizationsModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionOrganizationsModule #record-table').html('');
    OrganizationsDataTable.init(start, range);
});

$(document).on('change', '#sectionOrganizationsModule #organizationscategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionOrganizationsModule #record-table').html('');
    OrganizationsDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionOrganizationsModule .group-checkable', function () {
    if ($(this).prop('checked')) {
        $('#sectionOrganizationsModule #record-table .chkChoose').prop('checked', true);
        $('#sectionOrganizationsModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionOrganizationsModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionOrganizationsModule #record-table .chkChoose').prop('checked', false);
        $('#sectionOrganizationsModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionOrganizationsModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionOrganizationsModule #record-table .chkChoose:checked').length == $('#sectionOrganizationsModule #record-table tr .chkChoose').length) {
        $('#sectionOrganizationsModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionOrganizationsModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionOrganizationsModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionOrganizationsModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking