var ignoreItems = '';
var selectedItems = '';
var NewsDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
           
            var ajaxUrl = site_url + '/powerpanel/news/get_builder_list';

            
        },
     
        resizeModuleModal: function () {
            var newsModuleH = 0;
            var newsModuleW = 0;

            var newsTableH = 0;


            if ($.cookie('newsModuleH')) {
                var newsModuleH = $.cookie('newsModuleH');
                $('#sectionNewsModule .modal-dialog').css('height', newsModuleH);
                $('#sectionNewsModule .modal-content').css('height', newsModuleH);
            }
            if ($.cookie('newsModuleW')) {
                var newsModuleW = $.cookie('newsModuleW');
                $('#sectionNewsModule .modal-dialog').css('width', newsModuleW);
                $('#sectionNewsModule .modal-content').css('width', newsModuleW);
            }

            if ($.cookie('newsTableH')) {
                var newsTableH = $.cookie('newsTableH');
                $('#sectionNewsModule #mcscroll').css('height', newsTableH);
            }


            // $('#sectionNewsModule .modal-dialog').css('left','40%');
            // $('#sectionNewsModule .modal-dialog').css('top','40%');
            // $('#sectionNewsModule .modal-dialog').css('transform','translate(-50%, -50%)');


            $('#sectionNewsModule .modal-content').resizable({
                alsoResize: ".modal-dialog,#mcscroll",
                minHeight: 607,
                minWidth: 750,
                maxHeight: 727,
                stop: function (e, u) {
                    newsModuleH = $(this).height();
                    newsModuleW = $(this).width();

                    newsTableH = $('#mcscroll').height();
                    newsTableW = $('#mcscroll').width();

                    $.cookie('newsTableH', newsTableH);
                    $.cookie('newsModuleH', newsModuleH);
                    $.cookie('newsModuleW', newsModuleW);
                }
            });
            // .resize(function(){
            //     if ($('#sectionNewsModule #record-table tr').length < $('input[name=total_records]').val()) {
            //         start += range;
            //         end += range;
            //         NewsDataTable.init(start, range);
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
$(document).on('click', '.news-module', function (event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionNewsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).on('click', '.news', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionNewsModule').modal({
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

$('#sectionNewsModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    
    $('#datatable_news_ajax').closest('.col-md-12').loading('start');
    NewsDataTable.resizeModuleModal();
    validateSectionNews.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    ignoreItems = [];
    $('select').selectpicker('destroy');
   
    $('#frmSectionNewsModule #newscategory-id option:first').prop('selected', true);
    $('#frmSectionNewsModule #columns option:selected').prop('selected', false);
    $('#frmSectionNewsModule #columns option[value=dtDateTime]').prop('selected', true);
    $('#frmSectionNewsModule #columns option[value=desc]').prop('selected', true);
    $('#frmSectionNewsModule input[name=template]').val(template);
   
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        extraclass = $('#' + id).data('extraclass');
     desc = $("#" + id).data('desc');
        var config = $('#' + id).data('config');
        $('#frmSectionNewsModule input[name=editing]').val(id);
        
        $('#frmSectionNewsModule #config').children('option[value=' + config + ']').prop('selected', true);
        
        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });
        $('#sectionNewsModule #addSection').text('Update');
        $('#sectionNewsModule #exampleModalLabel b').text('Update News');
    } else {
        $('#frmSectionNewsModule input[name=editing]').val('');
        
        $('#frmSectionNewsModule #config').children('option[value=7]').prop('selected', true);

        $('#sectionNewsModule #addSection').text('Add');
        $('#sectionNewsModule #exampleModalLabel b').text('News');
    }
    $('#frmSectionNewsModule #extra_class').val(extraclass);
    $('#frmSectionNewsModule #section_title').val(caption);
   
    $('select').selectpicker();

    NewsDataTable.init(start, range);
    $("#frmSectionNewsModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    $('#datatable_news_ajax').closest('.col-md-12').loading('start');
                    setTimeout(function () {
                        if ($('#sectionNewsModule #record-table tr').length < $('input[name=total_records]').val()) {
                            start += range;
                            end += range;
                            NewsDataTable.init(start, range);
                        } else {
                            $('#datatable_news_ajax').closest('.col-md-12').loading('stop');
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
   
    $('#sectionNewsModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    validateSectionNews.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});


$(document).on('keyup', '#sectionNewsModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionNewsModule #record-table').html('');
    NewsDataTable.init(start, range);
});

$(document).on('change', '#sectionNewsModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionNewsModule #record-table').html('');
    NewsDataTable.init(start, range);
});

$(document).on('change', '#sectionNewsModule #newscategory-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionNewsModule #record-table').html('');
    NewsDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionNewsModule .group-checkable', function () {
    if ($(this).prop('checked')) {
        $('#sectionNewsModule #record-table .chkChoose').prop('checked', true);
        $('#sectionNewsModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionNewsModule #record-table .chkChoose:checked').each(function (index, value) {
            selectedItems.push($(this).val());
        });
    } else {
        $('#sectionNewsModule #record-table .chkChoose').prop('checked', false);
        $('#sectionNewsModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
    }
});

$(document).on('change', '#sectionNewsModule #record-table .chkChoose', function () {
    if ($(this).prop('checked')) {
        selectedItems.push($(this).val());
        $(this).parent().parent().parent().addClass('selected-record');
    } else {
        selectedItems.pop($(this).val());
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionNewsModule #record-table .chkChoose:checked').length == $('#sectionNewsModule #record-table tr .chkChoose').length) {
        $('#sectionNewsModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionNewsModule .group-checkable').prop('checked', false);
    }
});

$(document).on('click', '#sectionNewsModule #record-table tr', function () {
    if ($(this).find('.chkChoose').prop('checked')) {
        $(this).find('.chkChoose').prop('checked', false);
        $(this).removeClass('selected-record');
    } else {
        $(this).find('.chkChoose').prop('checked', true);
        $(this).addClass('selected-record');
    }
    $(this).find('.chkChoose').trigger('change');
});

$(document).on('change', '#sectionNewsModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});

//..Group checkbox checking