var ignoreItems = '';
var selectedItems = '';
var recTitle = [];

var GalleryModule = function () {
    // public functions
    return {
        reInitSortable: function () {
            $("#section-container").sortable('destroy');
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt'
            });
        },
        moduleSectionsGallery: function (caption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class) {
            recids = recids.split(',');
            recTitle = recTitle.split(',');

            if (recids == '') {
                recids = [];
                recTitle = [];
            }

            recids = $.unique(recids);
            //recTitle = $.unique(recTitle);
            var section = '';

            //var customize = '<a href="javascript:;" data-module="product" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';

            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="gallery-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;(' + configTxt + ')</label>';
                section += '<ul class="record-list">';
                $.each(recids, function (index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index]  + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link gallery-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="gallery" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }

            } else {

                var galleryIds = [];
                var imageTitles = [];

                $('.section-item[data-editor=' + edit + '] li').each(function (key, val) {
                    var iId = $(this).data('id');
                    galleryIds.push(iId);

                    var iTitle = $(this).text();
                    imageTitles.push(iTitle);

                });

                $.each(recids, function (index, value) {
                    galleryIds.push(value);
                    imageTitles.push(recTitle[index]);                
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;(' + configTxt + ')</label>';
                section += '<ul class="record-list">';
                $.each(galleryIds, function (index, value) {
                    if (value != '') {
                        section += '<li  data-id="' + value + '" id="' + value + '-item-' + edit + '">' + imageTitles[index]  + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                    }
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link gallery-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="gallery" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);

            }
        },
        galleryTemplate: function (val, config, template, edit, configTxt, layout, extra_class) {

            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="gallery-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item galleryTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b>(' + configTxt + ')</label><ul><li>Template: ' + template + '</li></ul></div>';
                section += '<input data-extclass="' + extra_class + '" id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-config="' + config + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }

            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b>(' + configTxt + ')</label><ul><li>Template: ' + template + '</li></ul></div>';
                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-layout="' + layout + '" data-type="' + template + '" data-config="' + config + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        submitFrmSectionGalleryModuleTemplate: function () {
            if ($('#frmSectionGalleryModuleTemplate').validate().form()) {
                var edit = $('#frmSectionGalleryModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionGalleryModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionGalleryModuleTemplate input[name=section_title]').val();
                var extra_class = $('#frmSectionGalleryModuleTemplate input[name=extra_class]').val();
                var template = $('#frmSectionGalleryModuleTemplate input[name=template]').val();
                var config = $('#frmSectionGalleryModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionGalleryModuleTemplate .config option:selected').text();
                var layout = $('#frmSectionGalleryModuleTemplate select[name=layoutType]').val();
                GalleryModule.galleryTemplate(val, config, template, edit, configTxt, layout, extra_class);
                $('#sectionGalleryModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionGalleryModule: function () {
            if ($('#frmSectionGalleryModule').validate().form()) {
                var edit = $('#frmSectionGalleryModule input[name=editing]').val() != '' ? $('#frmSectionGalleryModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionGalleryModule input[name=template]').val();
                var extra_class = $('#frmSectionGalleryModule input[name=extra_class]').val();
                var imgCaption = $('#frmSectionGalleryModule input[name=section_title]').val();
                var config = $('#frmSectionGalleryModule .config').val();
                var configTxt = $('#frmSectionGalleryModule .config option:selected').text();
                var layoutType = $('#frmSectionGalleryModule select[name=layoutType]').val();
                var recids = $('#frmSectionGalleryModule input[name=selectedIds]').val();
                var recTitle = $('#frmSectionGalleryModule input[name=selectedTitles]').val();

                GalleryModule.moduleSectionsGallery(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class);
                GalleryModule.reInitSortable();
                $('#sectionGalleryModule').modal('hide');
            }
        }
    };
}();

var GalleryDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var sort = $('#sectionGalleryModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/gallery/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionGalleryModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    status: '',
                    searchValue: $('#sectionGalleryModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionGalleryModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                    if(result.recordsTotal == 0 || result.found == 0) {
                        $('#frmSectionGalleryModule').find('.addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionGalleryModule').find('.addSection').removeAttr('disabled');
                    }
                },
                error: function (req, err) {
                    console.log('error:' + err);
                }
            });
        }
    };
}();

var validateSectionGallery = function () {
    var handleSectionGallery = function () 
    {
        $("#frmSectionGalleryModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true
                },
                section_config: {
                    required: true,
                    noSpace: true
                },
                layoutType: {
                    required: true,
                },
                'delete[]': {
                    required: {
                        depends: function () {
                            return $('#frmSectionGalleryModule input[name="editing"]').val() == '';
                        }
                    }
                }
            },
            messages: {
                section_title: "Caption is required",
                section_config: "Configurations is required",
                layoutType: "Please select layout",
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionGalleryModule .table-container .table:first'));
                } else if (element.attr('class') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionGalleryModule')).show();
            },
            highlight: function (element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function (element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function (form) {
                GalleryModule.submitFrmSectionGalleryModule();
                return false;
            }
        });

        $('#frmSectionGalleryModule input').keypress(function (e) {
            if (e.which == 13) {
                GalleryModule.submitFrmSectionGalleryModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSectionGallery();
        },
        reset: function () {
            var validator = $("#frmSectionGalleryModule").validate();
            validator.resetForm();
        }
    };
}();

var galleryTemplate = function () {
    var galleryTemplate = function () {
        $("#frmSectionGalleryModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true
                },
                layoutType: {
                    required: true
                },
                section_config: {
                    required: true,
                    noSpace: true
                }
            },
            messages: {
                section_title: {
                    required: "Title is required"
                },
                layoutType: {
                    required: "Layout is required"
                },
                section_config: {
                    required: "Configurations is required"
                }
            },
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('class') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionGalleryModuleTemplate')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                GalleryModule.submitFrmSectionGalleryModuleTemplate();
                return false;
            }
        });
        $('#frmSectionGalleryModuleTemplate input').keypress(function (e) {
            if (e.which == 13) {
                GalleryModule.submitFrmSectionGalleryModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            galleryTemplate();
        },
        reset: function () {
            var validator = $("#frmSectionGalleryModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();


var range = 5;
var start = 0;
var end = range;

//..Open while add or edit section
var id = '';
var caption = '';
var template = '';

$(document).on('click', '.gallery-module', function(event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionGalleryModule').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('#sectionGalleryModule [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionGalleryModule [data-dismiss="modal"]').attr( "data-target", "" );
});


$(document).on('click', '.gallery-list', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionGalleryModule').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
});

$(document).on('click', '.gallery-template', function (event) 
{
    $('#sectionGalleryModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionGalleryModuleTemplate [data-dismiss="modal"]').attr( "data-target", "" );

    $('#pgBuiderSections').modal('hide');
    $('#sectionGalleryModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var layout = '';
    if (typeof id != 'undefined') 
    {
        var extclass = $('#' + id).data('extclass');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');

        $('#frmSectionGalleryModuleTemplate input[name=editing]').val(id);
        $('#frmSectionGalleryModuleTemplate input[name=section_title]').val($.trim(value));
        $('#frmSectionGalleryModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionGalleryModuleTemplate .config option[value=' + config + ']').prop('selected', true);
        $('#frmSectionGalleryModuleTemplate input[name=extra_class]').val(extclass);
        $('#frmSectionGalleryModuleTemplate .addSection').text('Update');
        $('#frmSectionGalleryModuleTemplate #exampleModalLabel b').text('Edit Image');

    } else {

        var value = $(this).text();
        $('#frmSectionGalleryModuleTemplate input[name=editing]').val('');
        $('#frmSectionGalleryModuleTemplate input[name=section_title]').val($.trim(value));
        //$('#frmSectionGalleryModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionGalleryModuleTemplate input[name=extra_class]').val('');
        $('#frmSectionGalleryModuleTemplate .addSection').text('Add');
        $('#frmSectionGalleryModuleTemplate #exampleModalLabel b').text('Add Image');

        $('#sectionGalleryModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "modal" );
        $('#sectionGalleryModuleTemplate [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );
    }

    $('#frmSectionGalleryModuleTemplate').find('input[name=template]').val($(this).data('filter'));

});

$('#frmSectionGalleryModuleTemplate').on('submit', function (e) {
    e.preventDefault();
});

//..End Open while add or edit section

$('#sectionGalleryModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
     $('#sectionGalleryModule select').selectpicker('');
    //$('#datatable_testimonial_ajax').closest('.col-md-12').loading('start');
    validateSectionGallery.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    recTitle = [];
    ignoreItems = [];
    $('#sectionGalleryModule input[name=selectedIds]').val(null);
    $('#sectionGalleryModule input[name=selectedTitles]').val(null);

    $('#frmSectionGalleryModule #columns option:selected').prop('selected', false);
    $('#frmSectionGalleryModule #columns option[value=varTitle]').prop('selected', true);
    $('#frmSectionGalleryModule #columns option[value=asc]').prop('selected', true);
    $('#frmSectionGalleryModule input[name=template]').val(template);
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        var extClass = $('#' + id).data('extclass');
        $('#frmSectionGalleryModule input[name=editing]').val(id);
        $('#frmSectionGalleryModule input[name=extra_class]').val(extClass);
        $('#frmSectionGalleryModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionGalleryModule .config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });

        $('#sectionGalleryModule .addSection').text('Update');
        $('#sectionGalleryModule #exampleModalLabel b').text('Update Image');
    } else {
        $('#frmSectionGalleryModule input[name=editing]').val('');
        $('#frmSectionGalleryModule input[name=extra_class]').val('');
        //$('#frmSectionGalleryModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionGalleryModule .config').children('option[value=7]').prop('selected', true);

        $('#sectionGalleryModule .addSection').text('Add');
        $('#sectionGalleryModule #exampleModalLabel b').text('Gallery');

        $('#sectionGalleryModule [data-dismiss="modal"]').attr( "data-toggle", "modal" );
        $('#sectionGalleryModule [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );
    }

    $('#frmSectionGalleryModule input[name=section_title]').val(caption);

    GalleryDataTable.init(start, range);
    $("#frmSectionGalleryModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    if ($('#sectionGalleryModule').find('#record-table tr').length < $('input[name=total_records]').val()) {
                        start += range;
                        end += range;
                        GalleryDataTable.init(start, range);
                    }
                }
            }
        }
    });

}).on('hidden.bs.modal', function () {

    range = 10;
    start = 0;
    end = range;
    $('#sectionGalleryModule select[name=layoutType] option[class=list]').show();
    $('#sectionGalleryModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
     $('#sectionGalleryModule select').selectpicker('destroy');
    validateSectionGallery.reset();

});


$('#sectionGalleryModuleTemplate').on('shown.bs.modal', function () {
$('#sectionGalleryModuleTemplate select').selectpicker('');
    galleryTemplate.init();
}).on('hidden.bs.modal', function () {
    $('#sectionGalleryModuleTemplate select').selectpicker('destroy');
    galleryTemplate.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});

$(document).on('keyup', '#sectionGalleryModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionGalleryModule #record-table').html('');
    GalleryDataTable.init(start, range);
});

$(document).on('change', '#sectionGalleryModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionGalleryModule #record-table').html('');
    GalleryDataTable.init(start, range);
});



//Group checkbox checking
$(document).on('change', '#sectionGalleryModule .group-checkable', function () {

    if ($(this).prop('checked')) {
        $('#sectionGalleryModule #record-table .chkChoose').prop('checked', true);
        $('#sectionGalleryModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionGalleryModule #record-table .chkChoose:checked').each(function (index, value) {
            var id = $(this).val();
            if (!selectedItems.includes(id)) {
                selectedItems.push(id);
                recTitle.push($(this).data('title'));
            }
        });
    } else {
        $('#sectionGalleryModule #record-table .chkChoose').prop('checked', false);
        $('#sectionGalleryModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
        recTitle = [];
    }
    $('#sectionGalleryModule input[name=selectedIds]').val(selectedItems);
    $('#sectionGalleryModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('change', '#sectionGalleryModule #record-table .chkChoose', function () {
    var id = $(this).val();
    if ($(this).prop('checked')) {

        if (!selectedItems.includes(id)) {
            selectedItems.push(id);
            recTitle.push($(this).data('title'));
        }

        $(this).parent().parent().parent().addClass('selected-record');

    } else {
        selectedItems.pop(id);
        recTitle.pop($(this).data('title'));
        $(this).parent().parent().parent().removeClass('selected-record');
    }

    if ($('#sectionGalleryModule #record-table .chkChoose:checked').length == $('#sectionGalleryModule #record-table tr .chkChoose').length) {
        $('#sectionGalleryModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionGalleryModule .group-checkable').prop('checked', false);
    }

    $('#sectionGalleryModule input[name=selectedIds]').val(selectedItems);
    $('#sectionGalleryModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('click', '#sectionGalleryModule #record-table tr', function (e) {
    var $cell = $(e.target).closest('td');
    if ($cell.index() > 0) {
        if ($(this).find('.chkChoose').prop('checked')) {
            $(this).find('.chkChoose').prop('checked', false).trigger('change');
            $(this).removeClass('selected-record');
        } else {
            $(this).find('.chkChoose').prop('checked', true).trigger('change');
            $(this).addClass('selected-record');
        }
    }
});

$(document).on('change', '#sectionGalleryModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});
//..Group checkbox checking