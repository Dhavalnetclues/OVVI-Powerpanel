var ignoreItems = '';
var selectedItems = '';
var EventsDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionEventsModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/events/get_builder_list';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionEventsModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionEventsModule #category-id').val(),
                    status: '',
                    searchValue: $('#sectionEventsModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionEventsModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionEventsModule #record-table tr').length > 1) {
                        $('#sectionEventsModule #record-table #not-found').remove();
                    }
                    $('#datatable_events_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
        getCategory: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var ajaxUrl = site_url + '/powerpanel/event-category/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionEventsModule #category-id').html(result);
                },
                complete: function () {

                }
            });
        },
        resizeModuleModal: function () {
            var eventsModuleH = 0;
            var eventsModuleW = 0;

            var eventsTableH = 0;


            if ($.cookie('eventsModuleH')) {
                var eventsModuleH = $.cookie('eventsModuleH');
                $('#sectionEventsModule .modal-dialog').css('height', eventsModuleH);
                $('#sectionEventsModule .modal-content').css('height', eventsModuleH);
            }
            if ($.cookie('eventsModuleW')) {
                var eventsModuleW = $.cookie('eventsModuleW');
                $('#sectionEventsModule .modal-dialog').css('width', eventsModuleW);
                $('#sectionEventsModule .modal-content').css('width', eventsModuleW);
            }

            if ($.cookie('eventsTableH')) {
                var eventsTableH = $.cookie('eventsTableH');
                $('#sectionEventsModule #mcscroll').css('height', eventsTableH);
            }

            $('#sectionEventsModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    eventsModuleH = $(this).height();
                    eventsModuleW = $(this).width();

                    eventsTableH = $('#mcscroll').height();
                    eventsTableW = $('#mcscroll').width();

                    $.cookie('eventsTableH', eventsTableH);
                    $.cookie('eventsModuleH', eventsModuleH);
                    $.cookie('eventsModuleW', eventsModuleW);
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
$(document).on('click', '.events-module', function (event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionEventsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.events', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionEventsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$('#sectionEventsModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
     
    $('#datatable_events_ajax').closest('.col-md-12').loading('start');
    EventsDataTable.resizeModuleModal();
    validateSectionEvents.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    EventsDataTable.getCategory();
    $('#frmSectionEventsModule #category-id option:first').prop('selected', true);
    $('#frmSectionEventsModule #columns option:selected').prop('selected', false);
    $('#frmSectionEventsModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionEventsModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionEventsModule input[name=template]').val(template);
    if (template == 'featured-events') {
        $('#frmSectionEventsModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionEventsModule input[name=editing]').val(id);
        $('#frmSectionEventsModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionEventsModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionEventsModule #addSection').text('Update');
        $('#sectionEventsModule #exampleModalLabel b').text('Update Events');
    } else {
        $('#frmSectionEventsModule input[name=editing]').val('');
        $('#frmSectionEventsModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionEventsModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionEventsModule #addSection').text('Add');
        $('#sectionEventsModule #exampleModalLabel b').text('Events');
    }

    $('#frmSectionEventsModule #section_title').val(caption);
     $('#frmSectionEventsModule #extra_class').val(extraclass);
    $('#frmSectionEventsModule #section_description').val(desc);
    $('select').selectpicker();

    EventsDataTable.init(start, range);
    $("#frmSectionEventsModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_events_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionEventsModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            EventsDataTable.init(start, range);
                        } else {
                            $('#datatable_events_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionEventsModule select[name=layoutType] option[class=list]').show();
    $('#sectionEventsModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionEvents.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionEventsModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionEventsModule #record-table').html('');
    EventsDataTable.init(start, range);
});

$(document).on('change', '#sectionEventsModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionEventsModule #record-table').html('');
    EventsDataTable.init(start, range);
});

$(document).on('change', '#sectionEventsModule #category-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionEventsModule #record-table').html('');
    EventsDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionEventsModule .group-checkable', function () {
    if ($(this).prop('checked')) {
        $('#sectionEventsModule #record-table .chkChoose').prop('checked', true);
        $('#sectionEventsModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionEventsModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionEventsModule #record-table .chkChoose').prop('checked', false);
        $('#sectionEventsModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionEventsModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionEventsModule #record-table .chkChoose:checked').length == $('#sectionEventsModule #record-table tr .chkChoose').length) {
        $('#sectionEventsModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionEventsModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionEventsModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionEventsModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking