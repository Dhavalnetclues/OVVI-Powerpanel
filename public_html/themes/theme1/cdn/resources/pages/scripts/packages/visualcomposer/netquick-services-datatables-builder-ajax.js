var ignoreItems = '';
var selectedItems = '';
var recTitle = [];

var ServiceModule = function () {
    // public functions
    return {
        reInitSortable: function () {
            $("#section-container").sortable('destroy');
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt'
            });
        },
        moduleSectionsServices: function (caption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class) {
            recids = recids.split(',');
            recTitle = recTitle.split(',');

            if (recids == '') {
                recids = [];
                recTitle = [];
            }

            recids = $.unique(recids);
            recTitle = $.unique(recTitle);

            var section = '';

            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="service-module">';
                section += '<i class="action-icon edit fa fa-pencil"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(recids, function (index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link service-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="service" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var serviceIds = [];
                var serviceTitles = [];
                var serviceCustomized = [];
                var serviceDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function (key, val) {
                    var iId = $(this).data('id');
                    serviceIds.push(iId);

                    var iTitle = $(this).text();
                    serviceTitles.push(iTitle);

                    var Icustomized = $(this).data('customized');
                    if (typeof Icustomized != 'undefined') {
                        serviceCustomized.push(Icustomized);
                    }

                    
                    var Idescription = $(this).data('description');
                    if (typeof Idescription != 'undefined') {
                        serviceDescription.push(Idescription.toString());
                    }


                });

                $.each(recids, function (index, value) {
                    serviceIds.push(value);
                    serviceTitles.push(recTitle[index]);
                    serviceCustomized.push(false);
                    serviceDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(serviceIds, function (index, value) {
                    if (value != '') {
                        section += '<li data-customized="' + serviceCustomized[index] + '"  data-description="' + serviceDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + serviceTitles[index]  + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                    }
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link service-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="service" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        serviceTemplate: function (val, config, template, edit, configTxt, layout, extra_class) {
            var section = '';

            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="service-template">';
                section += '<i class="action-icon edit fa fa-pencil"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item serviceTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label><ul><li>Template: ' + template + '</li></ul></div>';
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
                section += '<div class="col-md-12"><label><b>' + val + '</b></label><ul><li>Template: ' + template + '</li></ul></div>';
                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-layout="' + layout + '" data-type="' + template + '" data-config="' + config + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        submitFrmSectionServiceModuleTemplate: function () {
            if ($('#frmSectionServiceModuleTemplate').validate().form()) {
                var edit = $('#frmSectionServiceModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionServiceModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionServiceModuleTemplate input[name=section_title]').val();
                var extra_class = $('#frmSectionServiceModuleTemplate input[name=extra_class]').val();
                var template = $('#frmSectionServiceModuleTemplate input[name=template]').val();
                var config = $('#frmSectionServiceModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionServiceModuleTemplate .config option:selected').text();
                var layout = $('#frmSectionServiceModuleTemplate select[name=layoutType]').val();
                ServiceModule.serviceTemplate(val, config, template, edit, configTxt, layout, extra_class);
                $('#sectionServiceModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionServiceModule: function () {
            if ($('#frmSectionServiceModule').validate().form()) {
                var edit = $('#frmSectionServiceModule input[name=editing]').val() != '' ? $('#frmSectionServiceModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionServiceModule input[name=template]').val();
                var extra_class = $('#frmSectionServiceModule input[name=extra_class]').val();
                var imgCaption = $('#frmSectionServiceModule input[name=section_title]').val();
                var config = $('#frmSectionServiceModule .config').val();
                var configTxt = $('#frmSectionServiceModule .config option:selected').text();
                var layoutType = $('#frmSectionServiceModule select[name=layoutType]').val();
                var recids = $('#frmSectionServiceModule input[name=selectedIds]').val();
                var recTitle = $('#frmSectionServiceModule input[name=selectedTitles]').val();

                ServiceModule.moduleSectionsServices(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class);
                ServiceModule.reInitSortable();
                $('#sectionServiceModule').modal('hide');
            }
        }
    };
}();

var ServiceDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var sort = $('#sectionServiceModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/services/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionServiceModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionServiceModule #category-id').val(),
                    status: '',
                    searchValue: $('#sectionServiceModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionServiceModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                    if(result.recordsTotal == 0 || result.found == 0) {
                        $('#frmSectionServiceModule').find('.addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionServiceModule').find('.addSection').removeAttr('disabled');
                    }

                },
                error: function (req, err) {
                    console.log('error:' + err);
                }
            });
        },
        getCategory: function () {

            var ajaxUrl = site_url + '/powerpanel/service-category/get_builder_list';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionServiceModule #category-id').html(result);
                }
            });

        }
    };
}();

var validateSectionService = function () {
    var handleSectionService = function () {
        $("#frmSectionServiceModule").validate({
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
                            return $('#frmSectionServiceModule input[name="editing"]').val() == '';
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
                    error.insertBefore($('#frmSectionServiceModule .table-container .table:first'));
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
                $('.alert-danger', $('#frmSectionServiceModule')).show();
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
                ServiceModule.submitFrmSectionServiceModule();
                return false;
            }
        });

        $('#frmSectionServiceModule input').keypress(function (e) {
            if (e.which == 13) {
                ServiceModule.submitFrmSectionServiceModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSectionService();
        },
        reset: function () {
            var validator = $("#frmSectionServiceModule").validate();
            validator.resetForm();
        }
    };
}();

var serviceTemplate = function () {
    var serviceTemplate = function () {
        $("#frmSectionServiceModuleTemplate").validate({
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
                $('.alert-danger', $('#frmSectionServiceModuleTemplate')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                ServiceModule.submitFrmSectionServiceModuleTemplate();
                return false;
            }
        });
        $('#frmSectionServiceModuleTemplate input').keypress(function (e) {
            if (e.which == 13) {
                ServiceModule.submitFrmSectionServiceModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            serviceTemplate();
        },
        reset: function () {
            var validator = $("#frmSectionServiceModuleTemplate").validate();
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

$(document).on('click', '.service-module', function(event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionServiceModule').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#sectionServiceModule [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionServiceModule [data-dismiss="modal"]').attr( "data-target", "" );
});


$(document).on('click', '.services', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionServiceModule').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
});

$(document).on('click', '.service-template', function (event) 
{
    $('#sectionServiceModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionServiceModuleTemplate [data-dismiss="modal"]').attr( "data-target", "" );

    $('#pgBuiderSections').modal('hide');
    $('#sectionServiceModuleTemplate').modal({
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

        $('#frmSectionServiceModuleTemplate input[name=editing]').val(id);
        $('#frmSectionServiceModuleTemplate input[name=section_title]').val($.trim(value));
        $('#frmSectionServiceModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionServiceModuleTemplate .config option[value=' + config + ']').prop('selected', true);
        $('#frmSectionServiceModuleTemplate input[name=extra_class]').val(extclass);
        $('#frmSectionServiceModuleTemplate .addSection').text('Update');
        $('#frmSectionServiceModuleTemplate #exampleModalLabel b').text('Edit Service');

    } else {

        var value = $(this).text();
        $('#frmSectionServiceModuleTemplate input[name=editing]').val('');
        $('#frmSectionServiceModuleTemplate input[name=section_title]').val($.trim(value));
        //$('#frmSectionServiceModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionServiceModuleTemplate input[name=extra_class]').val('');
        $('#frmSectionServiceModuleTemplate .addSection').text('Add');
        $('#frmSectionServiceModuleTemplate #exampleModalLabel b').text('Add Service');

        $('#sectionServiceModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "modal" );
        $('#sectionServiceModuleTemplate [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );

    }

    $('#frmSectionServiceModuleTemplate').find('input[name=template]').val($(this).data('filter'));

});

$('#frmSectionServiceModuleTemplate').on('submit', function (e) {
    e.preventDefault();
});

//..End Open while add or edit section

$('#sectionServiceModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    //$('#datatable_service_ajax').closest('.col-md-12').loading('start');
    validateSectionService.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    recTitle = [];
    ignoreItems = [];
    $('#sectionServiceModule input[name=selectedIds]').val(null);
    $('#sectionServiceModule input[name=selectedTitles]').val(null);
     $('select').selectpicker('destroy');
    ServiceDataTable.getCategory();
    $('#frmSectionServiceModule #category-id option:first').prop('selected', true);
    $('#frmSectionServiceModule #columns option:selected').prop('selected', false);
    $('#frmSectionServiceModule #columns option[value=varTitle]').prop('selected', true);
    $('#frmSectionServiceModule #columns option[value=asc]').prop('selected', true);
    $('#frmSectionServiceModule input[name=template]').val(template);
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        var extClass = $('#' + id).data('extclass');
        $('#frmSectionServiceModule input[name=editing]').val(id);
        $('#frmSectionServiceModule input[name=extra_class]').val(extClass);
        $('#frmSectionServiceModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionServiceModule .config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });

        $('#sectionServiceModule .addSection').text('Update');
        $('#sectionServiceModule #exampleModalLabel b').text('Update Service');
    } else {
        $('#frmSectionServiceModule input[name=editing]').val('');
        $('#frmSectionServiceModule input[name=extra_class]').val('');
        //$('#frmSectionServiceModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionServiceModule .config').children('option[value=7]').prop('selected', true);

        $('#sectionServiceModule .addSection').text('Add');
        $('#sectionServiceModule #exampleModalLabel b').text('Service');

        $('#sectionServiceModule [data-dismiss="modal"]').attr( "data-toggle", "modal" );
        $('#sectionServiceModule [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );
    }

    $('#frmSectionServiceModule input[name=section_title]').val(caption);
    $('select').selectpicker();
    ServiceDataTable.init(start, range);
    $("#frmSectionServiceModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    if ($('#sectionServiceModule').find('#record-table tr').length < $('input[name=total_records]').val()) {
                        start += range;
                        end += range;
                        ServiceDataTable.init(start, range);
                    }
                }
            }
        }
    });

}).on('hidden.bs.modal', function () {

    range = 10;
    start = 0;
    end = range;
    $('#sectionServiceModule select[name=layoutType] option[class=list]').show();
    $('#sectionServiceModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
     $('#sectionServiceModule select').selectpicker('destroy');
    validateSectionService.reset();

});


$('#sectionServiceModuleTemplate').on('shown.bs.modal', function () {
$('#sectionServiceModuleTemplate select').selectpicker('');
    serviceTemplate.init();
}).on('hidden.bs.modal', function () {
     $('#sectionServiceModuleTemplate select').selectpicker('destroy');
    serviceTemplate.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});

$(document).on('keyup', '#sectionServiceModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionServiceModule #record-table').html('');
    ServiceDataTable.init(start, range);
});

$(document).on('change', '#sectionServiceModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionServiceModule #record-table').html('');
    ServiceDataTable.init(start, range);
});

$(document).on('change', '#sectionServiceModule #category-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionServiceModule #record-table').html('');
    ServiceDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionServiceModule .group-checkable', function () {

    if ($(this).prop('checked')) {
        $('#sectionServiceModule #record-table .chkChoose').prop('checked', true);
        $('#sectionServiceModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionServiceModule #record-table .chkChoose:checked').each(function (index, value) {
            var id = $(this).val();
            if (!selectedItems.includes(id)) {
                selectedItems.push(id);
                recTitle.push($(this).data('title'));
            }
        });
    } else {
        $('#sectionServiceModule #record-table .chkChoose').prop('checked', false);
        $('#sectionServiceModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
        recTitle = [];
    }
    $('#sectionServiceModule input[name=selectedIds]').val(selectedItems);
    $('#sectionServiceModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('change', '#sectionServiceModule #record-table .chkChoose', function () {
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

    if ($('#sectionServiceModule #record-table .chkChoose:checked').length == $('#sectionServiceModule #record-table tr .chkChoose').length) {
        $('#sectionServiceModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionServiceModule .group-checkable').prop('checked', false);
    }

    $('#sectionServiceModule input[name=selectedIds]').val(selectedItems);
    $('#sectionServiceModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('click', '#sectionServiceModule #record-table tr', function (e) {
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

$(document).on('change', '#sectionServiceModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});
//..Group checkbox checking