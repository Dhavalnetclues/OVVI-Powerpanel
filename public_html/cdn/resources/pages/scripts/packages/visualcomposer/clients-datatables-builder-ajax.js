var ignoreItems = '';
var selectedItems = '';
var recTitle = [];

var ClientModule = function () {
    // public functions
    return {
        reInitSortable: function () {
            $("#section-container").sortable('destroy');
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt'
            });
        },
        moduleSectionsClient: function (caption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class) {
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
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="client-module">';
                section += '<i class="action-icon edit fa fa-pencil"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(recids, function (index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index]  + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link client-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="client" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var clientIds = [];
                var clientTitles = [];
                var clientCustomized = [];
                var clientDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function (key, val) {
                    var iId = $(this).data('id');
                    clientIds.push(iId);

                    var iTitle = $(this).text();
                    clientTitles.push(iTitle);

                    var Icustomized = $(this).data('customized');
                    if (typeof Icustomized != 'undefined') {
                        clientCustomized.push(Icustomized);
                    }


                    var Idescription = $(this).data('description');
                    if (typeof Idescription != 'undefined') {
                        clientDescription.push(Idescription.toString());
                    }


                });

                $.each(recids, function (index, value) {
                    clientIds.push(value);
                    clientTitles.push(recTitle[index]);
                    clientCustomized.push(false);
                    clientDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(clientIds, function (index, value) {
                    if (value != '') {
                        section += '<li data-customized="' + clientCustomized[index] + '"  data-id="' + value + '" id="' + value + '-item-' + edit + '">' + clientTitles[index]  + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                    }
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link client-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="client" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        clientTemplate: function (val, config, template, edit, configTxt, layout, extra_class) {
            var section = '';

            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="client-template">';
                section += '<i class="action-icon edit fa fa-pencil"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item clientTemplate" data-editor="' + iCount + '">';
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
        submitFrmSectionClientModuleTemplate: function () {
            if ($('#frmSectionClientModuleTemplate').validate().form()) {
                var edit = $('#frmSectionClientModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionClientModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionClientModuleTemplate input[name=section_title]').val();
                var extra_class = $('#frmSectionClientModuleTemplate input[name=extra_class]').val();
                var template = $('#frmSectionClientModuleTemplate input[name=template]').val();
                var config = $('#frmSectionClientModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionClientModuleTemplate .config option:selected').text();
                var layout = $('#frmSectionClientModuleTemplate select[name=layoutType]').val();
                ClientModule.clientTemplate(val, config, template, edit, configTxt, layout, extra_class);
                $('#sectionClientModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionClientModule: function () {
            if ($('#frmSectionClientModule').validate().form()) {
                var edit = $('#frmSectionClientModule input[name=editing]').val() != '' ? $('#frmSectionClientModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionClientModule input[name=template]').val();
                var extra_class = $('#frmSectionClientModule input[name=extra_class]').val();
                var imgCaption = $('#frmSectionClientModule input[name=section_title]').val();
                var config = $('#frmSectionClientModule .config').val();
                var configTxt = $('#frmSectionClientModule .config option:selected').text();
                var layoutType = $('#frmSectionClientModule select[name=layoutType]').val();
                var recids = $('#frmSectionClientModule input[name=selectedIds]').val();
                var recTitle = $('#frmSectionClientModule input[name=selectedTitles]').val();

                ClientModule.moduleSectionsClient(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class);
                ClientModule.reInitSortable();
                $('#sectionClientModule').modal('hide');
            }
        }
    };
}();

var ClientDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var sort = $('#sectionClientModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/client/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionClientModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionClientModule #category-id').val(),
                    status: '',
                    searchValue: $('#sectionClientModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionClientModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);

                    if(result.recordsTotal == 0 || result.found == 0) {
                        $('#frmSectionClientModule').find('.addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionClientModule').find('.addSection').removeAttr('disabled');
                    }

                },
                error: function (req, err) {
                    console.log('error:' + err);
                }
            });
        },
        getCategory: function () {

            var ajaxUrl = site_url + '/powerpanel/client-category/get_builder_list';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionClientModule #category-id').html(result);
                }
            });

        }
    };
}();

var validateSectionClient = function () {
    var handleSectionClient = function () {
        $("#frmSectionClientModule").validate({
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
                            return $('#frmSectionClientModule input[name="editing"]').val() == '';
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
                    error.insertBefore($('#frmSectionClientModule .table-container .table:first'));
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
                $('.alert-danger', $('#frmSectionClientModule')).show();
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
                ClientModule.submitFrmSectionClientModule();
                return false;
            }
        });

        $('#frmSectionClientModule input').keypress(function (e) {
            if (e.which == 13) {
                ClientModule.submitFrmSectionClientModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSectionClient();
        },
        reset: function () {
            var validator = $("#frmSectionClientModule").validate();
            validator.resetForm();
        }
    };
}();

var clientTemplate = function () {
    var clientTemplate = function () {
        $("#frmSectionClientModuleTemplate").validate({
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
                $('.alert-danger', $('#frmSectionClientModuleTemplate')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                ClientModule.submitFrmSectionClientModuleTemplate();
                return false;
            }
        });
        $('#frmSectionClientModuleTemplate input').keypress(function (e) {
            if (e.which == 13) {
                ClientModule.submitFrmSectionClientModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            clientTemplate();
        },
        reset: function () {
            var validator = $("#frmSectionClientModuleTemplate").validate();
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

$(document).on('click', '.client-module', function(event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionClientModule').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#sectionClientModule [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionClientModule [data-dismiss="modal"]').attr( "data-target", "" );
});


$(document).on('click', '.clients', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionClientModule').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
});

$(document).on('click', '.client-template', function (event) 
{
    $('#sectionClientModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionClientModuleTemplate [data-dismiss="modal"]').attr( "data-target", "" );

    $('#pgBuiderSections').modal('hide');
    $('#sectionClientModuleTemplate').modal({
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

        $('#frmSectionClientModuleTemplate input[name=editing]').val(id);
        $('#frmSectionClientModuleTemplate input[name=section_title]').val($.trim(value));
        $('#frmSectionClientModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionClientModuleTemplate .config option[value=' + config + ']').prop('selected', true);
        $('#frmSectionClientModuleTemplate input[name=extra_class]').val(extclass);
        $('#frmSectionClientModuleTemplate .addSection').text('Update');
        $('#frmSectionClientModuleTemplate #exampleModalLabel b').text('Edit Client');

    } else {

        var value = $(this).text();
        $('#frmSectionClientModuleTemplate input[name=editing]').val('');
        $('#frmSectionClientModuleTemplate input[name=section_title]').val($.trim(value));
        //$('#frmSectionClientModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionClientModuleTemplate input[name=extra_class]').val('');
        $('#frmSectionClientModuleTemplate .addSection').text('Add');
        $('#frmSectionClientModuleTemplate #exampleModalLabel b').text('Add Client');

        $('#sectionClientModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "modal" );
    }

    $('#frmSectionClientModuleTemplate').find('input[name=template]').val($(this).data('filter'));

});

$('#frmSectionClientModuleTemplate').on('submit', function (e) {
    e.preventDefault();
});

//..End Open while add or edit section

$('#sectionClientModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    //$('#datatable_Client_ajax').closest('.col-md-12').loading('start');
    validateSectionClient.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    recTitle = [];
    ignoreItems = [];
    $('#sectionClientModule input[name=selectedIds]').val(null);
    $('#sectionClientModule input[name=selectedTitles]').val(null);
     $('select').selectpicker('destroy');
    ClientDataTable.getCategory();
    $('#frmSectionClientModule #category-id option:first').prop('selected', true);
    $('#frmSectionClientModule #columns option:selected').prop('selected', false);
    $('#frmSectionClientModule #columns option[value=varTitle]').prop('selected', true);
    $('#frmSectionClientModule #columns option[value=asc]').prop('selected', true);
    $('#frmSectionClientModule input[name=template]').val(template);
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        var extClass = $('#' + id).data('extclass');
        $('#frmSectionClientModule input[name=editing]').val(id);
        $('#frmSectionClientModule input[name=extra_class]').val(extClass);
        $('#frmSectionClientModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        if(config != ''){
        $('#frmSectionClientModule .config').children('option[value=' + config + ']').prop('selected', true);
    }
        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });

        $('#sectionClientModule .addSection').text('Update');
        $('#sectionClientModule #exampleModalLabel b').text('Update Client');
    } else {
        $('#frmSectionClientModule input[name=editing]').val('');
        $('#frmSectionClientModule input[name=extra_class]').val('');
        //$('#frmSectionClientModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionClientModule .config').children('option[value=7]').prop('selected', true);

        $('#sectionClientModule .addSection').text('Add');
        $('#sectionClientModule #exampleModalLabel b').text('Client');

        $('#sectionClientModule [data-dismiss="modal"]').attr( "data-toggle", "modal" );
    }

    $('#frmSectionClientModule input[name=section_title]').val(caption);
    $('select').selectpicker();
    ClientDataTable.init(start, range);
    $("#frmSectionClientModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    if ($('#sectionClientModule').find('#record-table tr').length < $('input[name=total_records]').val()) {
                        start += range;
                        end += range;
                        ClientDataTable.init(start, range);
                    }
                }
            }
        }
    });

}).on('hidden.bs.modal', function () {

    range = 10;
    start = 0;
    end = range;
    $('#sectionClientModule select[name=layoutType] option[class=list]').show();
    $('#sectionClientModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
     $('#sectionClientModule select').selectpicker('destroy');
    validateSectionClient.reset();

});


$('#sectionClientModuleTemplate').on('shown.bs.modal', function () {
$('#sectionClientModuleTemplate select').selectpicker('');
    clientTemplate.init();
}).on('hidden.bs.modal', function () {
    $('#sectionClientModuleTemplate select').selectpicker('destroy');
    clientTemplate.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});

$(document).on('keyup', '#sectionClientModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionClientModule #record-table').html('');
    ClientDataTable.init(start, range);
});

$(document).on('change', '#sectionClientModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionClientModule #record-table').html('');
    ClientDataTable.init(start, range);
});

$(document).on('change', '#sectionClientModule #category-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionClientModule #record-table').html('');
    ClientDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionClientModule .group-checkable', function () {

    if ($(this).prop('checked')) {
        $('#sectionClientModule #record-table .chkChoose').prop('checked', true);
        $('#sectionClientModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionClientModule #record-table .chkChoose:checked').each(function (index, value) {
            var id = $(this).val();
            if (!selectedItems.includes(id)) {
                selectedItems.push(id);
                recTitle.push($(this).data('title'));
            }
        });
    } else {
        $('#sectionClientModule #record-table .chkChoose').prop('checked', false);
        $('#sectionClientModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
        recTitle = [];
    }
    $('#sectionClientModule input[name=selectedIds]').val(selectedItems);
    $('#sectionClientModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('change', '#sectionClientModule #record-table .chkChoose', function () {
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

    if ($('#sectionClientModule #record-table .chkChoose:checked').length == $('#sectionClientModule #record-table tr .chkChoose').length) {
        $('#sectionClientModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionClientModule .group-checkable').prop('checked', false);
    }

    $('#sectionClientModule input[name=selectedIds]').val(selectedItems);
    $('#sectionClientModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('click', '#sectionClientModule #record-table tr', function (e) {
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

$(document).on('change', '#sectionClientModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});
//..Group checkbox checking