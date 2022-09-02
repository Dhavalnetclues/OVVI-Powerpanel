var ignoreItems = '';
var selectedItems = '';
var recTitle = [];

var ShowModule = function () {
    // public functions
    return {
        reInitSortable: function () {
            $("#section-container").sortable('destroy');
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt'
            });
        },
        moduleSectionsShows: function (caption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class) {
            recids = recids.split(',');
            recTitle = recTitle.split(',');

            if (recids == '') {
                recids = [];
                recTitle = [];
            }

            recids = $.unique(recids);
            recTitle = $.unique(recTitle);

            var section = '';
            var customize = '<a href="javascript:;" data-module="show" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="show-module">';
                section += '<i class="action-icon edit fa fa-pencil"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;(' + configTxt + ')</label>';
                section += '<ul class="record-list">';
                $.each(recids, function (index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + customize + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link show-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="show" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                
                var showIds = [];
                var showTitles = [];
                var showCustomized = [];
                var showDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function (key, val) {
                    var iId = $(this).data('id');
                    showIds.push(iId);

                    var iTitle = $(this).text();
                    showTitles.push(iTitle);

                    var Icustomized = $(this).data('customized');
                    if (typeof Icustomized != 'undefined') {
                        showCustomized.push(Icustomized);
                    }

                    var Idescription = $(this).data('description');
                    if (typeof Idescription != 'undefined') {
                        showDescription.push(Idescription.toString());
                    }

                });

                $.each(recids, function (index, value) {
                    showIds.push(value);
                    showTitles.push(recTitle[index]);
                    showCustomized.push(false);
                    showDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;(' + configTxt + ')</label>';
                section += '<ul class="record-list">';
                $.each(showIds, function (index, value) {
                    if (value != '') {
                        section += '<li data-customized="' + showCustomized[index] + '"   data-description="' + showDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + showTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                    }
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link show-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                

                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="show" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        showTemplate: function (val, config, template, edit, configTxt, layout, extra_class) {
            var section = '';

            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="show-template">';
                section += '<i class="action-icon edit fa fa-pencil"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item showTemplate" data-editor="' + iCount + '">';
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
        submitFrmSectionShowModuleTemplate: function () {
            if ($('#frmSectionShowModuleTemplate').validate().form()) {
                var edit = $('#frmSectionShowModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionShowModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionShowModuleTemplate input[name=section_title]').val();
                var extra_class = $('#frmSectionShowModuleTemplate input[name=extra_class]').val();
                var template = $('#frmSectionShowModuleTemplate input[name=template]').val();
                var config = $('#frmSectionShowModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionShowModuleTemplate .config option:selected').text();
                var layout = $('#frmSectionShowModuleTemplate select[name=layoutType]').val();
                ShowModule.showTemplate(val, config, template, edit, configTxt, layout, extra_class);
                $('#sectionShowModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionShowModule: function () {
            if ($('#frmSectionShowModule').validate().form()) {
                var edit = $('#frmSectionShowModule input[name=editing]').val() != '' ? $('#frmSectionShowModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionShowModule input[name=template]').val();
                var extra_class = $('#frmSectionShowModule input[name=extra_class]').val();
                var imgCaption = $('#frmSectionShowModule input[name=section_title]').val();
                var config = $('#frmSectionShowModule .config').val();
                var configTxt = $('#frmSectionShowModule .config option:selected').text();
                var layoutType = $('#frmSectionShowModule select[name=layoutType]').val();
                var recids = $('#frmSectionShowModule input[name=selectedIds]').val();
                var recTitle = $('#frmSectionShowModule input[name=selectedTitles]').val();

                ShowModule.moduleSectionsShows(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class);
                ShowModule.reInitSortable();
                $('#sectionShowModule').modal('hide');
            }
        }
    };
}();

var ShowDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var sort = $('#sectionShowModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/shows/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionShowModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionShowModule #category-id').val(),
                    status: '',
                    searchValue: $('#sectionShowModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionShowModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                    if(result.recordsTotal == 0 || result.found == 0) {
                        $('#frmSectionShowModule').find('.addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionShowModule').find('.addSection').removeAttr('disabled');
                    }
                },
                error: function (req, err) {
                    console.log('error:' + err);
                }
            });
        },
        getCategory: function () {

            var ajaxUrl = site_url + '/powerpanel/show-category/get_builder_list';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionShowModule #category-id').html(result);
                }
            });

        }
    };
}();

var validateSectionShow = function () {
    var handleSectionShow = function () {
        $("#frmSectionShowModule").validate({
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
                            return $('#frmSectionShowModule input[name="editing"]').val() == '';
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
                    error.insertBefore($('#frmSectionShowModule .table-container .table:first'));
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
                $('.alert-danger', $('#frmSectionShowModule')).show();
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
                ShowModule.submitFrmSectionShowModule();
                return false;
            }
        });

        $('#frmSectionShowModule input').keypress(function (e) {
            if (e.which == 13) {
                ShowModule.submitFrmSectionShowModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSectionShow();
        },
        reset: function () {
            var validator = $("#frmSectionShowModule").validate();
            validator.resetForm();
        }
    };
}();

var showTemplate = function () {
    var showTemplate = function () {
        $("#frmSectionShowModuleTemplate").validate({
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
                $('.alert-danger', $('#frmSectionShowModuleTemplate')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                ShowModule.submitFrmSectionShowModuleTemplate();
                return false;
            }
        });
        $('#frmSectionShowModuleTemplate input').keypress(function (e) {
            if (e.which == 13) {
                ShowModule.submitFrmSectionShowModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            showTemplate();
        },
        reset: function () {
            var validator = $("#frmSectionShowModuleTemplate").validate();
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

$(document).on('click', '.show-module', function(event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionShowModule').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('#sectionShowModule [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionShowModule [data-dismiss="modal"]').attr( "data-target", "" );
});


$(document).on('click', '.shows', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionShowModule').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
});

$(document).on('click', '.show-template', function (event) 
{
    $('#sectionShowModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionShowModuleTemplate [data-dismiss="modal"]').attr( "data-target", "" );
    $('#pgBuiderSections').modal('hide');

    $('#sectionShowModuleTemplate').modal({
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

        $('#sectionShowModuleTemplate input[name=editing]').val(id);
        $('#sectionShowModuleTemplate input[name=section_title]').val($.trim(value));
        $('#sectionShowModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionShowModuleTemplate .config option[value=' + config + ']').prop('selected', true);
        $('#sectionShowModuleTemplate input[name=extra_class]').val(extclass);
        $('#sectionShowModuleTemplate .addSection').text('Update');
        $('#sectionShowModuleTemplate #exampleModalLabel b').text('Edit Show');
    } else {
        var value = $(this).text();
        $('#sectionShowModuleTemplate input[name=editing]').val('');
        $('#sectionShowModuleTemplate input[name=section_title]').val($.trim(value));
        //$('#sectionShowModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionShowModuleTemplate input[name=extra_class]').val('');
        $('#sectionShowModuleTemplate .addSection').text('Add');
        $('#sectionShowModuleTemplate #exampleModalLabel b').text('Add Show');

        $('#sectionShowModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "modal" );
    }

    $('#sectionShowModuleTemplate input[name=template]').val($(this).data('filter'));

});

$('#frmSectionShowModuleTemplate').on('submit', function (e) {
    e.preventDefault();
});

//..End Open while add or edit section

$('#sectionShowModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    //$('#datatable_events_ajax').closest('.col-md-12').loading('start');
    validateSectionShow.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    recTitle = [];
    ignoreItems = [];
    $('#sectionShowModule input[name=selectedIds]').val(null);
    $('#sectionShowModule input[name=selectedTitles]').val(null);
     $('select').selectpicker('destroy');
    ShowDataTable.getCategory();
    $('#frmSectionShowModule #category-id option:first').prop('selected', true);
    $('#frmSectionShowModule #columns option:selected').prop('selected', false);
    $('#frmSectionShowModule #columns option[value=varTitle]').prop('selected', true);
    $('#frmSectionShowModule #columns option[value=asc]').prop('selected', true);
    $('#frmSectionShowModule input[name=template]').val(template);
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');

        var extClass = $('#' + id).data('extclass');
        $('#frmSectionShowModule input[name=editing]').val(id);
        $('#frmSectionShowModule input[name=extra_class]').val(extClass);
        $('#frmSectionShowModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
         if(config != ''){
        $('#frmSectionShowModule .config').children('option[value=' + config + ']').prop('selected', true);
    }
        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });

        $('#sectionShowModule .addSection').text('Update');
        $('#sectionShowModule #exampleModalLabel b').text('Update Show');
    } else {
        $('#frmSectionShowModule input[name=editing]').val('');
        $('#frmSectionShowModule input[name=extra_class]').val('');
        //$('#frmSectionShowModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionShowModule .config').children('option[value=7]').prop('selected', true);

        $('#sectionShowModule .addSection').text('Add');
        $('#sectionShowModule #exampleModalLabel b').text('Show');

        $('#sectionShowModule [data-dismiss="modal"]').attr( "data-toggle", "modal" );
    }

    $('#frmSectionShowModule input[name=section_title]').val(caption);
    $('select').selectpicker();

    ShowDataTable.init(start, range);
    $("#frmSectionShowModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    if ($('#sectionShowModule').find('#record-table tr').length < $('input[name=total_records]').val()) {
                        start += range;
                        end += range;
                        ShowDataTable.init(start, range);
                    }
                }
            }
        }
    });

}).on('hidden.bs.modal', function () {

    range = 10;
    start = 0;
    end = range;
    $('#sectionShowModule select[name=layoutType] option[class=list]').show();
    $('#sectionShowModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
     $('#sectionShowModule select').selectpicker('destroy');
    validateSectionShow.reset();

});


$('#sectionShowModuleTemplate').on('shown.bs.modal', function () {
$('#sectionShowModuleTemplate select').selectpicker('');
    showTemplate.init();
}).on('hidden.bs.modal', function () {
    $('#sectionShowModuleTemplate select').selectpicker('destroy');
    showTemplate.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});

$(document).on('keyup', '#sectionShowModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionShowModule #record-table').html('');
    ShowDataTable.init(start, range);
});

$(document).on('change', '#sectionShowModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionShowModule #record-table').html('');
    ShowDataTable.init(start, range);
});

$(document).on('change', '#sectionShowModule #category-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionShowModule #record-table').html('');
    ShowDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionShowModule .group-checkable', function () {

    if ($(this).prop('checked')) {
        $('#sectionShowModule #record-table .chkChoose').prop('checked', true);
        $('#sectionShowModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionShowModule #record-table .chkChoose:checked').each(function (index, value) {
            var id = $(this).val();
            if (!selectedItems.includes(id)) {
                selectedItems.push(id);
                recTitle.push($(this).data('title'));
            }
        });
    } else {
        $('#sectionShowModule #record-table .chkChoose').prop('checked', false);
        $('#sectionShowModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
        recTitle = [];
    }
    $('#sectionShowModule input[name=selectedIds]').val(selectedItems);
    $('#sectionShowModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('change', '#sectionShowModule #record-table .chkChoose', function () {
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

    if ($('#sectionShowModule #record-table .chkChoose:checked').length == $('#sectionShowModule #record-table tr .chkChoose').length) {
        $('#sectionShowModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionShowModule .group-checkable').prop('checked', false);
    }

    $('#sectionShowModule input[name=selectedIds]').val(selectedItems);
    $('#sectionShowModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('click', '#sectionShowModule #record-table tr', function (e) {
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

$(document).on('change', '#sectionShowModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});
//..Group checkbox checking