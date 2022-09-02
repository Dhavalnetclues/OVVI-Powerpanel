var ignoreItems = '';
var selectedItems = '';
var PublicationDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionPublicationModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/publications/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionPublicationModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionPublicationModule #publicationcategory-id').val(),
                    status: '',
                    searchValue: $('#sectionPublicationModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionPublicationModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionPublicationModule #record-table tr').length > 1) {
                        $('#sectionPublicationModule #record-table #not-found').remove();
                    }
                    $('#datatable_publication_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
        getCategory: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var ajaxUrl = site_url + '/powerpanel/publications-category/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionPublicationModule #publicationcategory-id').html(result);
                },
                complete: function () {

                }
            });
        },
        resizeModuleModal: function () {
            var publicationModuleH = 0;
            var publicationModuleW = 0;

            var publicationTableH = 0;


            if ($.cookie('publicationModuleH')) {
                var publicationModuleH = $.cookie('publicationModuleH');
                $('#sectionPublicationModule .modal-dialog').css('height', publicationModuleH);
                $('#sectionPublicationModule .modal-content').css('height', publicationModuleH);
            }
            if ($.cookie('publicationModuleW')) {
                var publicationModuleW = $.cookie('publicationModuleW');
                $('#sectionPublicationModule .modal-dialog').css('width', publicationModuleW);
                $('#sectionPublicationModule .modal-content').css('width', publicationModuleW);
            }

            if ($.cookie('publicationTableH')) {
                var publicationTableH = $.cookie('publicationTableH');
                $('#sectionPublicationModule #mcscroll').css('height', publicationTableH);
            }


            // $('#sectionPublicationModule .modal-dialog').css('left','40%');
            // $('#sectionPublicationModule .modal-dialog').css('top','40%');
            // $('#sectionPublicationModule .modal-dialog').css('transform','translate(-50%, -50%)');


            $('#sectionPublicationModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    publicationModuleH = $(this).height();
                    publicationModuleW = $(this).width();

                    publicationTableH = $('#mcscroll').height();
                    publicationTableW = $('#mcscroll').width();

                    $.cookie('publicationTableH', publicationTableH);
                    $.cookie('publicationModuleH', publicationModuleH);
                    $.cookie('publicationModuleW', publicationModuleW);
                }
            });
            // .resize(function(){
            //     if ($('#sectionPublicationModule #record-table tr').length < $('input[name=total_records]').val()) {
            //         start += range;
            //         end += range;
            //         PublicationDataTable.init(start, range);
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
var extraclass = '';
var desc = '';
$(document).on('click', '.publication-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionPublicationModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.publication', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionPublicationModule').modal({
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

$('#sectionPublicationModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
   
    $('#datatable_publication_ajax').closest('.col-md-12').loading('start');
    PublicationDataTable.resizeModuleModal();
    validateSectionPublication.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    PublicationDataTable.getCategory();
    $('#frmSectionPublicationModule #publicationcategory-id option:first').prop('selected', true);
    $('#frmSectionPublicationModule #columns option:selected').prop('selected', false);
    $('#frmSectionPublicationModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionPublicationModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionPublicationModule input[name=template]').val(template);
    if (template == 'featured-publication') {
        $('#frmSectionPublicationModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
          extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        $('#frmSectionPublicationModule input[name=editing]').val(id);
        $('#frmSectionPublicationModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionPublicationModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionPublicationModule #addSection').text('Update');
        $('#sectionPublicationModule #exampleModalLabel b').text('Update Publication');
    } else {
        $('#frmSectionPublicationModule input[name=editing]').val('');
        $('#frmSectionPublicationModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionPublicationModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionPublicationModule #addSection').text('Add');
        $('#sectionPublicationModule #exampleModalLabel b').text('Publication');
    }
     $('#frmSectionPublicationModule #extra_class').val(extraclass);
    $('#frmSectionPublicationModule #section_title').val(caption);
    $('#frmSectionPublicationModule #section_description').val(desc);
    $('select').selectpicker();

    PublicationDataTable.init(start, range);
    $("#frmSectionPublicationModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_publication_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionPublicationModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            PublicationDataTable.init(start, range);
                        } else {
                            $('#datatable_publication_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionPublicationModule select[name=layoutType] option[class=list]').show();
    $('#sectionPublicationModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionPublication.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionPublicationModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionPublicationModule #record-table').html('');
    PublicationDataTable.init(start, range);
});

$(document).on('change', '#sectionPublicationModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionPublicationModule #record-table').html('');
    PublicationDataTable.init(start, range);
});

$(document).on('change', '#sectionPublicationModule #publicationcategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionPublicationModule #record-table').html('');
    PublicationDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionPublicationModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionPublicationModule #record-table .chkChoose').prop('checked', true);
        $('#sectionPublicationModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionPublicationModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionPublicationModule #record-table .chkChoose').prop('checked', false);
        $('#sectionPublicationModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionPublicationModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionPublicationModule #record-table .chkChoose:checked').length == $('#sectionPublicationModule #record-table tr .chkChoose').length) {
        $('#sectionPublicationModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionPublicationModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionPublicationModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionPublicationModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking