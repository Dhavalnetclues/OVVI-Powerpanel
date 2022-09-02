var ignoreItems = '';
var selectedItems = '';
var recTitle = [];

var ProjectModule = function () {
    // public functions
    return {
        reInitSortable: function () {
            $("#section-container").sortable('destroy');
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt'
            });
        },
        moduleSectionsProject: function (caption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class) {
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
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="project-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
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
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link project-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="project" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var projectIds = [];
                var projectTitles = [];
                var projectCustomized = [];
                var projectDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function (key, val) {
                    var iId = $(this).data('id');
                    projectIds.push(iId);

                    var iTitle = $(this).text();
                    projectTitles.push(iTitle);

                    var Icustomized = $(this).data('customized');
                    if (typeof Icustomized != 'undefined') {
                        projectCustomized.push(Icustomized);
                    }


                    var Idescription = $(this).data('description');
                    if (typeof Idescription != 'undefined') {
                        projectDescription.push(Idescription.toString());
                    }


                });

                $.each(recids, function (index, value) {
                    projectIds.push(value);
                    projectTitles.push(recTitle[index]);
                    projectCustomized.push(false);
                    projectDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(projectIds, function (index, value) {
                    if (value != '') {
                        section += '<li data-customized="' + projectCustomized[index] + '"  data-id="' + value + '" id="' + value + '-item-' + edit + '">' + projectTitles[index]  + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                    }
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link project-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="project" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        projectTemplate: function (val, config, template, edit, configTxt, layout, extra_class) {
            var section = '';

            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="project-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item projectTemplate" data-editor="' + iCount + '">';
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
        submitFrmSectionProjectModuleTemplate: function () {
            if ($('#frmSectionProjectModuleTemplate').validate().form()) {
                var edit = $('#frmSectionProjectModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionProjectModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionProjectModuleTemplate input[name=section_title]').val();
                var extra_class = $('#frmSectionProjectModuleTemplate input[name=extra_class]').val();
                var template = $('#frmSectionProjectModuleTemplate input[name=template]').val();
                var config = $('#frmSectionProjectModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionProjectModuleTemplate .config option:selected').text();
                var layout = $('#frmSectionProjectModuleTemplate select[name=layoutType]').val();
                ProjectModule.projectTemplate(val, config, template, edit, configTxt, layout, extra_class);
                $('#sectionProjectModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionProjectModule: function () {
            if ($('#frmSectionProjectModule').validate().form()) {
                var edit = $('#frmSectionProjectModule input[name=editing]').val() != '' ? $('#frmSectionProjectModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionProjectModule input[name=template]').val();
                var extra_class = $('#frmSectionProjectModule input[name=extra_class]').val();
                var imgCaption = $('#frmSectionProjectModule input[name=section_title]').val();
                var config = $('#frmSectionProjectModule .config').val();
                var configTxt = $('#frmSectionProjectModule .config option:selected').text();
                var layoutType = $('#frmSectionProjectModule select[name=layoutType]').val();
                var recids = $('#frmSectionProjectModule input[name=selectedIds]').val();
                var recTitle = $('#frmSectionProjectModule input[name=selectedTitles]').val();

                ProjectModule.moduleSectionsProject(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class);
                ProjectModule.reInitSortable();
                $('#sectionProjectModule').modal('hide');
            }
        }
    };
}();

var ProjectDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var sort = $('#sectionProjectModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/projects/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionProjectModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionProjectModule #category-id').val(),
                    status: '',
                    searchValue: $('#sectionProjectModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionProjectModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);

                    if(result.recordsTotal == 0 || result.found == 0) {
                        $('#frmSectionProjectModule').find('.addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionProjectModule').find('.addSection').removeAttr('disabled');
                    }

                },
                error: function (req, err) {
                    console.log('error:' + err);
                }
            });
        },
        getCategory: function () {

            var ajaxUrl = site_url + '/powerpanel/project-category/get_builder_list';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionProjectModule #category-id').html(result);
                }
            });

        }
    };
}();

var validateSectionProject = function () {
    var handleSectionProject = function () {
        $("#frmSectionProjectModule").validate({
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
                            return $('#frmSectionProjectModule input[name="editing"]').val() == '';
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
                    error.insertBefore($('#frmSectionProjectModule .table-container .table:first'));
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
                $('.alert-danger', $('#frmSectionProjectModule')).show();
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
                ProjectModule.submitFrmSectionProjectModule();
                return false;
            }
        });

        $('#frmSectionProjectModule input').keypress(function (e) {
            if (e.which == 13) {
                ProjectModule.submitFrmSectionProjectModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSectionProject();
        },
        reset: function () {
            var validator = $("#frmSectionProjectModule").validate();
            validator.resetForm();
        }
    };
}();

var projectTemplate = function () {
    var projectTemplate = function () {
        $("#frmSectionProjectModuleTemplate").validate({
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
                $('.alert-danger', $('#frmSectionProjectModuleTemplate')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                ProjectModule.submitFrmSectionProjectModuleTemplate();
                return false;
            }
        });
        $('#frmSectionProjectModuleTemplate input').keypress(function (e) {
            if (e.which == 13) {
                ProjectModule.submitFrmSectionProjectModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            projectTemplate();
        },
        reset: function () {
            var validator = $("#frmSectionProjectModuleTemplate").validate();
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

$(document).on('click', '.project-module', function(event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionProjectModule').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#sectionProjectModule [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionProjectModule [data-dismiss="modal"]').attr( "data-target", "" );
});


$(document).on('click', '.projects', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionProjectModule').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
});

$(document).on('click', '.project-template', function (event) 
{
    $('#sectionProjectModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionProjectModuleTemplate [data-dismiss="modal"]').attr( "data-target", "" );

    $('#pgBuiderSections').modal('hide');
    $('#sectionProjectModuleTemplate').modal({
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

        $('#frmSectionProjectModuleTemplate input[name=editing]').val(id);
        $('#frmSectionProjectModuleTemplate input[name=section_title]').val($.trim(value));
        $('#frmSectionProjectModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionProjectModuleTemplate .config option[value=' + config + ']').prop('selected', true);
        $('#frmSectionProjectModuleTemplate input[name=extra_class]').val(extclass);
        $('#frmSectionProjectModuleTemplate .addSection').text('Update');
        $('#frmSectionProjectModuleTemplate #exampleModalLabel b').text('Edit Project');

    } else {

        var value = $(this).text();
        $('#frmSectionProjectModuleTemplate input[name=editing]').val('');
        $('#frmSectionProjectModuleTemplate input[name=section_title]').val($.trim(value));
        //$('#frmSectionProjectModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionProjectModuleTemplate input[name=extra_class]').val('');
        $('#frmSectionProjectModuleTemplate .addSection').text('Add');
        $('#frmSectionProjectModuleTemplate #exampleModalLabel b').text('Add Project');

        $('#sectionProjectModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "modal" );
    }

    $('#frmSectionProjectModuleTemplate').find('input[name=template]').val($(this).data('filter'));

});

$('#frmSectionProjectModuleTemplate').on('submit', function (e) {
    e.preventDefault();
});

//..End Open while add or edit section

$('#sectionProjectModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    //$('#datatable_Project_ajax').closest('.col-md-12').loading('start');
    validateSectionProject.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    recTitle = [];
    ignoreItems = [];
    $('#sectionProjectModule input[name=selectedIds]').val(null);
    $('#sectionProjectModule input[name=selectedTitles]').val(null);
     $('select').selectpicker('destroy');
    ProjectDataTable.getCategory();
    $('#frmSectionProjectModule #category-id option:first').prop('selected', true);
    $('#frmSectionProjectModule #columns option:selected').prop('selected', false);
    $('#frmSectionProjectModule #columns option[value=varTitle]').prop('selected', true);
    $('#frmSectionProjectModule #columns option[value=asc]').prop('selected', true);
    $('#frmSectionProjectModule input[name=template]').val(template);
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        var extClass = $('#' + id).data('extclass');
        $('#frmSectionProjectModule input[name=editing]').val(id);
        $('#frmSectionProjectModule input[name=extra_class]').val(extClass);
        $('#frmSectionProjectModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        if(config != ''){
        $('#frmSectionProjectModule .config').children('option[value=' + config + ']').prop('selected', true);
    }
        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });

        $('#sectionProjectModule .addSection').text('Update');
        $('#sectionProjectModule #exampleModalLabel b').text('Update Project');
    } else {
        $('#frmSectionProjectModule input[name=editing]').val('');
        $('#frmSectionProjectModule input[name=extra_class]').val('');
        //$('#frmSectionProjectModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionProjectModule .config').children('option[value=7]').prop('selected', true);

        $('#sectionProjectModule .addSection').text('Add');
        $('#sectionProjectModule #exampleModalLabel b').text('Project');

        $('#sectionProjectModule [data-dismiss="modal"]').attr( "data-toggle", "modal" );
    }

    $('#frmSectionProjectModule input[name=section_title]').val(caption);
    $('select').selectpicker();
    ProjectDataTable.init(start, range);
    $("#frmSectionProjectModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    if ($('#sectionProjectModule').find('#record-table tr').length < $('input[name=total_records]').val()) {
                        start += range;
                        end += range;
                        ProjectDataTable.init(start, range);
                    }
                }
            }
        }
    });

}).on('hidden.bs.modal', function () {

    range = 10;
    start = 0;
    end = range;
    $('#sectionProjectModule select[name=layoutType] option[class=list]').show();
    $('#sectionProjectModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
     $('#sectionProjectModule select').selectpicker('destroy');
    validateSectionProject.reset();

});


$('#sectionProjectModuleTemplate').on('shown.bs.modal', function () {
$('#sectionProjectModuleTemplate select').selectpicker('');
    projectTemplate.init();
}).on('hidden.bs.modal', function () {
    $('#sectionProjectModuleTemplate select').selectpicker('destroy');
    projectTemplate.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});

$(document).on('keyup', '#sectionProjectModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionProjectModule #record-table').html('');
    ProjectDataTable.init(start, range);
});

$(document).on('change', '#sectionProjectModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionProjectModule #record-table').html('');
    ProjectDataTable.init(start, range);
});

$(document).on('change', '#sectionProjectModule #category-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionProjectModule #record-table').html('');
    ProjectDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionProjectModule .group-checkable', function () {

    if ($(this).prop('checked')) {
        $('#sectionProjectModule #record-table .chkChoose').prop('checked', true);
        $('#sectionProjectModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionProjectModule #record-table .chkChoose:checked').each(function (index, value) {
            var id = $(this).val();
            if (!selectedItems.includes(id)) {
                selectedItems.push(id);
                recTitle.push($(this).data('title'));
            }
        });
    } else {
        $('#sectionProjectModule #record-table .chkChoose').prop('checked', false);
        $('#sectionProjectModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
        recTitle = [];
    }
    $('#sectionProjectModule input[name=selectedIds]').val(selectedItems);
    $('#sectionProjectModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('change', '#sectionProjectModule #record-table .chkChoose', function () {
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

    if ($('#sectionProjectModule #record-table .chkChoose:checked').length == $('#sectionProjectModule #record-table tr .chkChoose').length) {
        $('#sectionProjectModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionProjectModule .group-checkable').prop('checked', false);
    }

    $('#sectionProjectModule input[name=selectedIds]').val(selectedItems);
    $('#sectionProjectModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('click', '#sectionProjectModule #record-table tr', function (e) {
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

$(document).on('change', '#sectionProjectModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});
//..Group checkbox checking