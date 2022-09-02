var ignoreItems = '';
var selectedItems = '';
var LinksDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionLinksModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/links/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionLinksModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    status: '',
                    catValue: $('#sectionLinksModule #linkcategory-id').val(),
                    searchValue: $('#sectionLinksModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionLinksModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionLinksModule #record-table tr').length > 1) {
                        $('#sectionLinksModule #record-table #not-found').remove();
                    }
                    $('#datatable_links_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
        getCategory: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var ajaxUrl = site_url + '/powerpanel/links-category/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionLinksModule #linkcategory-id').html(result);
                },
                complete: function () {

                }
            });
        },
        resizeModuleModal: function () {
            var linksModuleH = 0;
            var linksModuleW = 0;

            var linksTableH = 0;


            if ($.cookie('linksModuleH')) {
                var linksModuleH = $.cookie('linksModuleH');
                $('#sectionLinksModule .modal-dialog').css('height', linksModuleH);
                $('#sectionLinksModule .modal-content').css('height', linksModuleH);
            }
            if ($.cookie('linksModuleW')) {
                var linksModuleW = $.cookie('linksModuleW');
                $('#sectionLinksModule .modal-dialog').css('width', linksModuleW);
                $('#sectionLinksModule .modal-content').css('width', linksModuleW);
            }

            if ($.cookie('linksTableH')) {
                var linksTableH = $.cookie('linksTableH');
                $('#sectionLinksModule #mcscroll').css('height', linksTableH);
            }

            $('#sectionLinksModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    linksModuleH = $(this).height();
                    linksModuleW = $(this).width();

                    linksTableH = $('#mcscroll').height();
                    linksTableW = $('#mcscroll').width();

                    $.cookie('linksTableH', linksTableH);
                    $.cookie('linksModuleH', linksModuleH);
                    $.cookie('linksModuleW', linksModuleW);
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
var extraclass = '';
var template = '';
$(document).on('click', '.links-module', function (event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionLinksModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.links', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionLinksModule').modal({
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

$('#sectionLinksModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
   
    $('#datatable_links_ajax').closest('.col-md-12').loading('start');
    LinksDataTable.resizeModuleModal();
    validateSectionLinks.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    LinksDataTable.getCategory();
    $('#frmSectionLinksModule #linkscategory-id option:first').prop('selected', true);
    $('#frmSectionLinksModule #columns option:selected').prop('selected', false);
    $('#frmSectionLinksModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionLinksModule input[name=template]').val(template);
  
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
         extraclass = $('#' + id).data('extraclass');
        $('#frmSectionLinksModule input[name=editing]').val(id);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionLinksModule #addSection').text('Update');
        $('#sectionLinksModule #exampleModalLabel b').text('Update Links');
    } else {
        $('#frmSectionLinksModule input[name=editing]').val('');

        $('#sectionLinksModule #addSection').text('Add');
        $('#sectionLinksModule #exampleModalLabel b').text('Links');
    }

    $('#frmSectionLinksModule #section_title').val(caption);
    $('#frmSectionLinksModule #extra_class').val(extraclass);
    $('select').selectpicker();

    LinksDataTable.init(start, range);
    $("#frmSectionLinksModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_links_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionLinksModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            LinksDataTable.init(start, range);
                        } else {
                            $('#datatable_links_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionLinksModule select[name=layoutType] option[class=list]').show();
    $('#sectionLinksModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionLinks.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionLinksModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionLinksModule #record-table').html('');
    LinksDataTable.init(start, range);
});

$(document).on('change', '#sectionLinksModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionLinksModule #record-table').html('');
    LinksDataTable.init(start, range);
});

$(document).on('change', '#sectionLinksModule #linkcategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionLinksModule #record-table').html('');
    LinksDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionLinksModule .group-checkable', function () {
    if ($(this).prop('checked')) {
        $('#sectionLinksModule #record-table .chkChoose').prop('checked', true);
        $('#sectionLinksModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionLinksModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionLinksModule #record-table .chkChoose').prop('checked', false);
        $('#sectionLinksModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionLinksModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionLinksModule #record-table .chkChoose:checked').length == $('#sectionLinksModule #record-table tr .chkChoose').length) {
        $('#sectionLinksModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionLinksModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionLinksModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionLinksModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking