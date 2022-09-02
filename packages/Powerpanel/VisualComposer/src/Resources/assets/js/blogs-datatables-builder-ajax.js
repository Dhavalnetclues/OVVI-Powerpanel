var ignoreItems = '';
var selectedItems = '';
var BlogsDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var sort = $('#sectionBlogsModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/blogs/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionBlogsModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionBlogsModule #blogscategory-id').val(),
                    status: '',
                    searchValue: $('#sectionBlogsModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionBlogsModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                },
                error: function (req, err) {
                    console.log('error:' + err);
                },
                complete: function () {
                    if ($('#sectionBlogsModule #record-table tr').length > 1) {
                        $('#sectionBlogsModule #record-table #not-found').remove();
                    }
                    $('#datatable_blogs_ajax').closest('.col-md-12').loading('stop');
                }
            });
        },
        getCategory: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var ajaxUrl = site_url + '/powerpanel/blog-category/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionBlogsModule #blogscategory-id').html(result);
                },
                complete: function () {

                }
            });
        },
        resizeModuleModal: function () {
            var blogsModuleH = 0;
            var blogsModuleW = 0;

            var blogsTableH = 0;


            if ($.cookie('blogsModuleH')) {
                var blogsModuleH = $.cookie('blogsModuleH');
                $('#sectionBlogsModule .modal-dialog').css('height', blogsModuleH);
                $('#sectionBlogsModule .modal-content').css('height', blogsModuleH);
            }
            if ($.cookie('blogsModuleW')) {
                var blogsModuleW = $.cookie('blogsModuleW');
                $('#sectionBlogsModule .modal-dialog').css('width', blogsModuleW);
                $('#sectionBlogsModule .modal-content').css('width', blogsModuleW);
            }

            if ($.cookie('blogsTableH')) {
                var blogsTableH = $.cookie('blogsTableH');
                $('#sectionBlogsModule #mcscroll').css('height', blogsTableH);
            }

            $('#sectionBlogsModule .modal-content').resizable({
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
$(document).on('click', '.blogs-module', function (event) {
   
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionBlogsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.blogs', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionBlogsModule').modal({
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

$('#sectionBlogsModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_blogs_ajax').closest('.col-md-12').loading('start');
    BlogsDataTable.resizeModuleModal();
    validateSectionBlogs.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
    BlogsDataTable.getCategory();
    $('#frmSectionBlogsModule #blogscategory-id option:first').prop('selected', true);
    $('#frmSectionBlogsModule #columns option:selected').prop('selected', false);
    $('#frmSectionBlogsModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionBlogsModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionBlogsModule input[name=template]').val(template);
    if (template == 'featured-blogs') {
        $('#frmSectionBlogsModule select[name=layoutType] option[class=list]').hide();
    }
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
         extraclass = $('#' + id).data('extraclass');
        desc = $('#' + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionBlogsModule input[name=editing]').val(id);
        $('#frmSectionBlogsModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionBlogsModule #config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionBlogsModule #addSection').text('Update');
        $('#sectionBlogsModule #exampleModalLabel b').text('Update Blogs');
    } else {
        $('#frmSectionBlogsModule input[name=editing]').val('');
        $('#frmSectionBlogsModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionBlogsModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionBlogsModule #addSection').text('Add');
        $('#sectionBlogsModule #exampleModalLabel b').text('Blogs');
    }

    $('#frmSectionBlogsModule #section_title').val(caption);
    $('#frmSectionBlogsModule #extra_class').val(extraclass);
    $('#frmSectionBlogsModule #section_description').val(desc);
    $('select').selectpicker();

    BlogsDataTable.init(start, range);
    $("#frmSectionBlogsModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_blogs_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionBlogsModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            BlogsDataTable.init(start, range);
                        } else {
                            $('#datatable_blogs_ajax').closest('.col-md-12').loading('stop');
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
    $('#sectionBlogsModule select[name=layoutType] option[class=list]').show();
    $('#sectionBlogsModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionBlogs.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionBlogsModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionBlogsModule #record-table').html('');
    BlogsDataTable.init(start, range);
});

$(document).on('change', '#sectionBlogsModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionBlogsModule #record-table').html('');
    BlogsDataTable.init(start, range);
});

$(document).on('change', '#sectionBlogsModule #blogscategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionBlogsModule #record-table').html('');
    BlogsDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionBlogsModule .group-checkable', function () {
   
    if ($(this).prop('checked')) {
        $('#sectionBlogsModule #record-table .chkChoose').prop('checked', true);
        $('#sectionBlogsModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionBlogsModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionBlogsModule #record-table .chkChoose').prop('checked', false);
        $('#sectionBlogsModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionBlogsModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionBlogsModule #record-table .chkChoose:checked').length == $('#sectionBlogsModule #record-table tr .chkChoose').length) {
        $('#sectionBlogsModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionBlogsModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionBlogsModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionBlogsModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking