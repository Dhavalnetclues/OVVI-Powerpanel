var ignoreItems = '';
var selectedItems = '';
var FaqsDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to,pagination) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionFaqsModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/faq/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionFaqsModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    status: '',
                    catValue: $('#sectionFaqsModule #faqcategory-id').val(),
                    searchValue: $('#sectionFaqsModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionFaqsModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                      if(pagination == false ){
                        
                    if(result.recordsTotal == 0 || result.found == 0 && ignoreItems == '' ) {
                       
                        $('#frmSectionFaqsModule').find('#addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionFaqsModule').find('#addSection').removeAttr('disabled');
                    }
                      }
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionFaqsModule #record-table tr').length > 1) {
                        $('#sectionFaqsModule #record-table #not-found').remove();
                    }
                    $('#datatable_faqs_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
        getCategory: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var ajaxUrl = site_url + '/powerpanel/faq-category/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionFaqsModule #faqcategory-id').html(result);
                },
                complete: function () {

                }
            });
        },
        resizeModuleModal: function () {
            var faqsModuleH = 0;
            var faqsModuleW = 0;

            var faqsTableH = 0;


            if ($.cookie('faqsModuleH')) {
                var faqsModuleH = $.cookie('faqsModuleH');
                $('#sectionFaqsModule .modal-dialog').css('height', faqsModuleH);
                $('#sectionFaqsModule .modal-content').css('height', faqsModuleH);
            }
            if ($.cookie('faqsModuleW')) {
                var faqsModuleW = $.cookie('faqsModuleW');
                $('#sectionFaqsModule .modal-dialog').css('width', faqsModuleW);
                $('#sectionFaqsModule .modal-content').css('width', faqsModuleW);
            }

            if ($.cookie('faqsTableH')) {
                var faqsTableH = $.cookie('faqsTableH');
                $('#sectionFaqsModule #mcscroll').css('height', faqsTableH);
            }

            $('#sectionFaqsModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    faqsModuleH = $(this).height();
                    faqsModuleW = $(this).width();

                    faqsTableH = $('#mcscroll').height();
                    faqsTableW = $('#mcscroll').width();

                    $.cookie('faqsTableH', faqsTableH);
                    $.cookie('faqsModuleH', faqsModuleH);
                    $.cookie('faqsModuleW', faqsModuleW);
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
$(document).on('click', '.faqs-module', function (event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionFaqsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.faqs', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionFaqsModule').modal({
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

$('#sectionFaqsModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_faqs_ajax').closest('.col-md-12').loading('start');
    FaqsDataTable.resizeModuleModal();
    validateSectionFaqs.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    FaqsDataTable.getCategory();
    $('#frmSectionFaqsModule #faqscategory-id option:first').prop('selected', true);
    $('#frmSectionFaqsModule #columns option:selected').prop('selected', false);
    $('#frmSectionFaqsModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionFaqsModule input[name=template]').val(template);
  
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        extraclass = $('#' + id).data('extraclass');
        $('#frmSectionFaqsModule input[name=editing]').val(id);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionFaqsModule #addSection').text('Update');
        $('#sectionFaqsModule #exampleModalLabel b').text('Update Faqs');
    } else {
        $('#frmSectionFaqsModule input[name=editing]').val('');

        $('#sectionFaqsModule #addSection').text('Add');
        $('#sectionFaqsModule #exampleModalLabel b').text('Faqs');
    }

    $('#frmSectionFaqsModule #section_title').val(caption);
    $('#frmSectionFaqsModule #extra_class').val(extraclass);
    $('select').selectpicker();

    FaqsDataTable.init(start, range,false);
    $("#frmSectionFaqsModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_faqs_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionFaqsModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            FaqsDataTable.init(start, range,true);
                        } else {
                            $('#datatable_faqs_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionFaqsModule select[name=layoutType] option[class=list]').show();
    $('#sectionFaqsModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionFaqs.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionFaqsModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionFaqsModule #record-table').html('');
    FaqsDataTable.init(start, range);
});

$(document).on('change', '#sectionFaqsModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionFaqsModule #record-table').html('');
    FaqsDataTable.init(start, range);
});

$(document).on('change', '#sectionFaqsModule #faqcategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionFaqsModule #record-table').html('');
    FaqsDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionFaqsModule .group-checkable', function () {
    if ($(this).prop('checked')) {
        $('#sectionFaqsModule #record-table .chkChoose').prop('checked', true);
        $('#sectionFaqsModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionFaqsModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionFaqsModule #record-table .chkChoose').prop('checked', false);
        $('#sectionFaqsModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionFaqsModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionFaqsModule #record-table .chkChoose:checked').length == $('#sectionFaqsModule #record-table tr .chkChoose').length) {
        $('#sectionFaqsModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionFaqsModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionFaqsModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionFaqsModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking