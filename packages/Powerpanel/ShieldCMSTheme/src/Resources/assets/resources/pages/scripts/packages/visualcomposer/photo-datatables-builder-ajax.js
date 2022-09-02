var ignoreItems = '';
var selectedItems = '';
var PhotoalbumDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionPhotoAlbumModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/photo-album/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionPhotoAlbumModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionPhotoAlbumModule #photoalbumcategory-id').val(),
                    status: '',
                    searchValue: $('#sectionPhotoAlbumModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionPhotoAlbumModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionPhotoAlbumModule #record-table tr').length > 1) {
                        $('#sectionPhotoAlbumModule #record-table #not-found').remove();
                    }
                    $('#datatable_photoalbum_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
        resizeModuleModal: function () {
            var photoalbumModuleH = 0;
            var photoalbumModuleW = 0;

            var photoalbumTableH = 0;


            if ($.cookie('photoalbumModuleH')) {
                var photoalbumModuleH = $.cookie('photoalbumModuleH');
                $('#sectionPhotoAlbumModule .modal-dialog').css('height', photoalbumModuleH);
                $('#sectionPhotoAlbumModule .modal-content').css('height', photoalbumModuleH);
            }
            if ($.cookie('photoalbumModuleW')) {
                var photoalbumModuleW = $.cookie('photoalbumModuleW');
                $('#sectionPhotoAlbumModule .modal-dialog').css('width', photoalbumModuleW);
                $('#sectionPhotoAlbumModule .modal-content').css('width', photoalbumModuleW);
            }

            if ($.cookie('photoalbumTableH')) {
                var photoalbumTableH = $.cookie('photoalbumTableH');
                $('#sectionPhotoAlbumModule #mcscroll').css('height', photoalbumTableH);
            }
            $('#sectionPhotoAlbumModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    photoalbumModuleH = $(this).height();
                    photoalbumModuleW = $(this).width();

                    photoalbumTableH = $('#mcscroll').height();
                    photoalbumTableW = $('#mcscroll').width();

                    $.cookie('photoalbumTableH', photoalbumTableH);
                    $.cookie('photoalbumModuleH', photoalbumModuleH);
                    $.cookie('photoalbumModuleW', photoalbumModuleW);
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
$(document).on('click', '.photoalbum-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionPhotoAlbumModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.photoalbum', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionPhotoAlbumModule').modal({
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

$('#sectionPhotoAlbumModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_photoalbum_ajax').closest('.col-md-12').loading('start');
    PhotoalbumDataTable.resizeModuleModal();
    validateSectionPhotoAlbum.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
//    PhotoalbumDataTable.getCategory();
    $('#frmSectionPhotoAlbumModule #photoalbumcategory-id option:first').prop('selected', true);
    $('#frmSectionPhotoAlbumModule #columns option:selected').prop('selected', false);
    $('#frmSectionPhotoAlbumModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionPhotoAlbumModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionPhotoAlbumModule input[name=template]').val(template);
    if (template == 'featured-photoalbum') {
        $('#frmSectionPhotoAlbumModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        desc = $('#' + id).data('desc');
       extraclass = $('#' + id).data('extraclass');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        $('#frmSectionPhotoAlbumModule input[name=editing]').val(id);
        $('#frmSectionPhotoAlbumModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionPhotoAlbumModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionPhotoAlbumModule #addSection').text('Update');
        $('#sectionPhotoAlbumModule #exampleModalLabel b').text('Update Photo Album');
    } else {
        $('#frmSectionPhotoAlbumModule input[name=editing]').val('');
        $('#frmSectionPhotoAlbumModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionPhotoAlbumModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionPhotoAlbumModule #addSection').text('Add');
        $('#sectionPhotoAlbumModule #exampleModalLabel b').text('Photo Album');
    }
    $('#frmSectionPhotoAlbumModule #section_title').val(caption);
    $('#frmSectionPhotoAlbumModule #extra_class').val(extraclass);
    $('#frmSectionPhotoAlbumModule #section_description').val(desc);
    $('select').selectpicker();

    PhotoalbumDataTable.init(start, range);
    $("#frmSectionPhotoAlbumModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_photoalbum_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionPhotoAlbumModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            PhotoalbumDataTable.init(start, range);
                        } else {
                            $('#datatable_photoalbum_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionPhotoAlbumModule select[name=layoutType] option[class=list]').show();
    $('#sectionPhotoAlbumModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionPhotoAlbum.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionPhotoAlbumModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionPhotoAlbumModule #record-table').html('');
    PhotoalbumDataTable.init(start, range);
});

$(document).on('change', '#sectionPhotoAlbumModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionPhotoAlbumModule #record-table').html('');
    PhotoalbumDataTable.init(start, range);
});

$(document).on('change', '#sectionPhotoAlbumModule #photoalbumcategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionPhotoAlbumModule #record-table').html('');
    PhotoalbumDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionPhotoAlbumModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionPhotoAlbumModule #record-table .chkChoose').prop('checked', true);
        $('#sectionPhotoAlbumModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionPhotoAlbumModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionPhotoAlbumModule #record-table .chkChoose').prop('checked', false);
        $('#sectionPhotoAlbumModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionPhotoAlbumModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionPhotoAlbumModule #record-table .chkChoose:checked').length == $('#sectionPhotoAlbumModule #record-table tr .chkChoose').length) {
        $('#sectionPhotoAlbumModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionPhotoAlbumModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionPhotoAlbumModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionPhotoAlbumModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking