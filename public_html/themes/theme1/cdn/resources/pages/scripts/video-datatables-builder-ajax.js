var ignoreItems = '';
var selectedItems = '';
var VideoalbumDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionVideoAlbumModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/video-gallery/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionVideoAlbumModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionVideoAlbumModule #videoalbumcategory-id').val(),
                    status: '',
                    searchValue: $('#sectionVideoAlbumModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionVideoAlbumModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionVideoAlbumModule #record-table tr').length > 1) {
                        $('#sectionVideoAlbumModule #record-table #not-found').remove();
                    }
                    $('#datatable_videoalbum_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
//        getCategory: function (from, to) {
//            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
//            var ajaxUrl = site_url + '/powerpanel/videoalbum-category/get_builder_list';
//
//            jQuery.ajax({
//                type: "POST",
//                url: ajaxUrl,
//                dataType: 'HTML',
//                async: false,
//                success: function (result) {
//                    $('#sectionVideoAlbumModule #videoalbumcategory-id').html(result);
//                },
//                complete: function () {
//
//                }
//            });
//        },
        resizeModuleModal: function () {
            var videoalbumModuleH = 0;
            var videoalbumModuleW = 0;

            var videoalbumTableH = 0;


            if ($.cookie('videoalbumModuleH')) {
                var videoalbumModuleH = $.cookie('videoalbumModuleH');
                $('#sectionVideoAlbumModule .modal-dialog').css('height', videoalbumModuleH);
                $('#sectionVideoAlbumModule .modal-content').css('height', videoalbumModuleH);
            }
            if ($.cookie('videoalbumModuleW')) {
                var videoalbumModuleW = $.cookie('videoalbumModuleW');
                $('#sectionVideoAlbumModule .modal-dialog').css('width', videoalbumModuleW);
                $('#sectionVideoAlbumModule .modal-content').css('width', videoalbumModuleW);
            }

            if ($.cookie('videoalbumTableH')) {
                var videoalbumTableH = $.cookie('videoalbumTableH');
                $('#sectionVideoAlbumModule #mcscroll').css('height', videoalbumTableH);
            }


            // $('#sectionVideoAlbumModule .modal-dialog').css('left','40%');
            // $('#sectionVideoAlbumModule .modal-dialog').css('top','40%');
            // $('#sectionVideoAlbumModule .modal-dialog').css('transform','translate(-50%, -50%)');


            $('#sectionVideoAlbumModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    videoalbumModuleH = $(this).height();
                    videoalbumModuleW = $(this).width();

                    videoalbumTableH = $('#mcscroll').height();
                    videoalbumTableW = $('#mcscroll').width();

                    $.cookie('videoalbumTableH', videoalbumTableH);
                    $.cookie('videoalbumModuleH', videoalbumModuleH);
                    $.cookie('videoalbumModuleW', videoalbumModuleW);
                }
            });
            // .resize(function(){
            //     if ($('#sectionVideoAlbumModule #record-table tr').length < $('input[name=total_records]').val()) {
            //         start += range;
            //         end += range;
            //         VideoalbumDataTable.init(start, range);
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
$(document).on('click', '.videoalbum-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionVideoAlbumModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.video-gallery', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionVideoAlbumModule').modal({
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

$('#sectionVideoAlbumModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    var desc = $('#' + id).data('desc');
    $('#datatable_videoalbum_ajax').closest('.col-md-12').loading('start');
    VideoalbumDataTable.resizeModuleModal();
    validateVideoAlbumTemplate.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
//    VideoalbumDataTable.getCategory();
    $('#frmSectionVideoAlbumModule #videoalbumcategory-id option:first').prop('selected', true);
    $('#frmSectionVideoAlbumModule #columns option:selected').prop('selected', false);
    $('#frmSectionVideoAlbumModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionVideoAlbumModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionVideoAlbumModule input[name=template]').val(template);
    if (template == 'featured-videoalbum') {
        $('#frmSectionVideoAlbumModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        $('#frmSectionVideoAlbumModule input[name=editing]').val(id);
        $('#frmSectionVideoAlbumModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionVideoAlbumModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionVideoAlbumModule #addSection').text('Update');
        $('#sectionVideoAlbumModule #exampleModalLabel b').text('Update Video Album');
    } else {
        $('#frmSectionVideoAlbumModule input[name=editing]').val('');
        $('#frmSectionVideoAlbumModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionVideoAlbumModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionVideoAlbumModule #addSection').text('Add');
        $('#sectionVideoAlbumModule #exampleModalLabel b').text('Video Album');
    }

    $('#frmSectionVideoAlbumModule #section_title').val(caption);
    $('#frmSectionVideoAlbumModule #section_description').val(desc);
    $('select').selectpicker();

    VideoalbumDataTable.init(start, range);
    $("#frmSectionVideoAlbumModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_videoalbum_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionVideoAlbumModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            VideoalbumDataTable.init(start, range);
                        } else {
                            $('#datatable_videoalbum_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionVideoAlbumModule select[name=layoutType] option[class=list]').show();
    $('#sectionVideoAlbumModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateVideoAlbumTemplate.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionVideoAlbumModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionVideoAlbumModule #record-table').html('');
    VideoalbumDataTable.init(start, range);
});

$(document).on('change', '#sectionVideoAlbumModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionVideoAlbumModule #record-table').html('');
    VideoalbumDataTable.init(start, range);
});

$(document).on('change', '#sectionVideoAlbumModule #videoalbumcategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionVideoAlbumModule #record-table').html('');
    VideoalbumDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionVideoAlbumModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionVideoAlbumModule #record-table .chkChoose').prop('checked', true);
        $('#sectionVideoAlbumModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionVideoAlbumModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionVideoAlbumModule #record-table .chkChoose').prop('checked', false);
        $('#sectionVideoAlbumModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionVideoAlbumModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionVideoAlbumModule #record-table .chkChoose:checked').length == $('#sectionVideoAlbumModule #record-table tr .chkChoose').length) {
        $('#sectionVideoAlbumModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionVideoAlbumModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionVideoAlbumModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionVideoAlbumModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking