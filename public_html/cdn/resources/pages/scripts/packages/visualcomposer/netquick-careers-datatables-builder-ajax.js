var ignoreItems = '';
var selectedItems = '';
var recTitle = [];

var CareerModule = function () {
    // public functions
    return {
        reInitSortable: function () {
            $("#section-container").sortable('destroy');
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt'
            });
        },
        moduleSectionsCareer: function (caption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class) {
            recids = recids.split(',');
            recTitle = recTitle.split(',');

            if (recids == '') {
                recids = [];
                recTitle = [];
            }

            recids = $.unique(recids);
            recTitle = $.unique(recTitle);
            var section = '';

            //var customize = '<a href="javascript:;" data-module="product" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';

            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="career-module">';
                section += '<i class="action-icon edit fa fa-pencil"></i>';
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
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link career-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="career" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }

            } else {

                var careerIds = [];
                var careerTitles = [];

                $('.section-item[data-editor=' + edit + '] li').each(function (key, val) {
                    var iId = $(this).data('id');
                    careerIds.push(iId);

                    var iTitle = $(this).text();
                    careerTitles.push(iTitle);

                });

                $.each(recids, function (index, value) {
                    careerIds.push(value);
                    careerTitles.push(recTitle[index]);                
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;(' + configTxt + ')</label>';
                section += '<ul class="record-list">';
                $.each(careerIds, function (index, value) {
                    if (value != '') {
                        section += '<li  data-id="' + value + '" id="' + value + '-item-' + edit + '">' + careerTitles[index]  + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                    }
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link career-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="career" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);

            }
        },
        careerTemplate: function (val, config, template, edit, configTxt, layout, extra_class) {

            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="career-template">';
                section += '<i class="action-icon edit fa fa-pencil"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item careerTemplate" data-editor="' + iCount + '">';
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
        submitFrmSectionCareerModuleTemplate: function () {
            if ($('#frmSectionCareerModuleTemplate').validate().form()) {
                var edit = $('#frmSectionCareerModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionCareerModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionCareerModuleTemplate input[name=section_title]').val();
                var extra_class = $('#frmSectionCareerModuleTemplate input[name=extra_class]').val();
                var template = $('#frmSectionCareerModuleTemplate input[name=template]').val();
                var config = $('#frmSectionCareerModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionCareerModuleTemplate .config option:selected').text();
                var layout = $('#frmSectionCareerModuleTemplate select[name=layoutType]').val();
                CareerModule.careerTemplate(val, config, template, edit, configTxt, layout, extra_class);
                $('#sectionCareerModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionCareerModule: function () {
            if ($('#frmSectionCareerModule').validate().form()) {
                var edit = $('#frmSectionCareerModule input[name=editing]').val() != '' ? $('#frmSectionCareerModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionCareerModule input[name=template]').val();
                var extra_class = $('#frmSectionCareerModule input[name=extra_class]').val();
                var imgCaption = $('#frmSectionCareerModule input[name=section_title]').val();
                var config = $('#frmSectionCareerModule .config').val();
                var configTxt = $('#frmSectionCareerModule .config option:selected').text();
                var layoutType = $('#frmSectionCareerModule select[name=layoutType]').val();
                var recids = $('#frmSectionCareerModule input[name=selectedIds]').val();
                var recTitle = $('#frmSectionCareerModule input[name=selectedTitles]').val();

                CareerModule.moduleSectionsCareer(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class);
                CareerModule.reInitSortable();
                $('#sectionCareerModule').modal('hide');
            }
        }
    };
}();

var CareerDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var sort = $('#sectionCareerModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/careers/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionCareerModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionCareerModule #category-id').val(),
                    status: '',
                    searchValue: $('#sectionCareerModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionCareerModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                    if(result.recordsTotal == 0 || result.found == 0) {
                        $('#frmSectionCareerModule').find('.addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionCareerModule').find('.addSection').removeAttr('disabled');
                    }
                },
                error: function (req, err) {
                    console.log('error:' + err);
                }
            });
        },
        getCategory: function () {

            var ajaxUrl = site_url + '/powerpanel/career-category/get_builder_list';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionCareerModule #category-id').html(result);
                }
            });

        }
    };
}();

var validateSectionCareer = function () {
    var handleSectionCareer = function () {
        $("#frmSectionCareerModule").validate({
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
                            return $('#frmSectionCareerModule input[name="editing"]').val() == '';
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
                    error.insertBefore($('#frmSectionCareerModule .table-container .table:first'));
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
                $('.alert-danger', $('#frmSectionCareerModule')).show();
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
                CareerModule.submitFrmSectionCareerModule();
                return false;
            }
        });

        $('#frmSectionCareerModule input').keypress(function (e) {
            if (e.which == 13) {
                CareerModule.submitFrmSectionCareerModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSectionCareer();
        },
        reset: function () {
            var validator = $("#frmSectionCareerModule").validate();
            validator.resetForm();
        }
    };
}();

var careerTemplate = function () {
    var careerTemplate = function () {
        $("#frmSectionCareerModuleTemplate").validate({
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
                $('.alert-danger', $('#frmSectionCareerModuleTemplate')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                CareerModule.submitFrmSectionCareerModuleTemplate();
                return false;
            }
        });
        $('#frmSectionCareerModuleTemplate input').keypress(function (e) {
            if (e.which == 13) {
                CareerModule.submitFrmSectionCareerModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            careerTemplate();
        },
        reset: function () {
            var validator = $("#frmSectionCareerModuleTemplate").validate();
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

$(document).on('click', '.career-module', function(event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionCareerModule').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('#sectionCareerModule [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionCareerModule [data-dismiss="modal"]').attr( "data-target", "" );
});


$(document).on('click', '.careers', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionCareerModule').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
});

$(document).on('click', '.career-template', function (event) 
{
    $('#sectionCareerModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionCareerModuleTemplate [data-dismiss="modal"]').attr( "data-target", "" );

    $('#pgBuiderSections').modal('hide');
    $('#sectionCareerModuleTemplate').modal({
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

        $('#frmSectionCareerModuleTemplate input[name=editing]').val(id);
        $('#frmSectionCareerModuleTemplate input[name=section_title]').val($.trim(value));
        $('#frmSectionCareerModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionCareerModuleTemplate .config option[value=' + config + ']').prop('selected', true);
        $('#frmSectionCareerModuleTemplate input[name=extra_class]').val(extclass);
        $('#frmSectionCareerModuleTemplate .addSection').text('Update');
        $('#frmSectionCareerModuleTemplate #exampleModalLabel b').text('Edit Career');

    } else {

        var value = $(this).text();
        $('#frmSectionCareerModuleTemplate input[name=editing]').val('');
        $('#frmSectionCareerModuleTemplate input[name=section_title]').val($.trim(value));
        //$('#frmSectionCareerModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionCareerModuleTemplate input[name=extra_class]').val('');
        $('#frmSectionCareerModuleTemplate .addSection').text('Add');
        $('#frmSectionCareerModuleTemplate #exampleModalLabel b').text('Add Career');

        $('#sectionCareerModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "modal" );
        $('#sectionCareerModuleTemplate [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );

    }

    $('#frmSectionCareerModuleTemplate').find('input[name=template]').val($(this).data('filter'));

});

$('#frmSectionCareerModuleTemplate').on('submit', function (e) {
    e.preventDefault();
});

//..End Open while add or edit section

$('#sectionCareerModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    //$('#datatable_career_ajax').closest('.col-md-12').loading('start');
    validateSectionCareer.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    recTitle = [];
    ignoreItems = [];
    $('#sectionCareerModule input[name=selectedIds]').val(null);
    $('#sectionCareerModule input[name=selectedTitles]').val(null);
     $('select').selectpicker('destroy');
    CareerDataTable.getCategory();
    $('#frmSectionCareerModule #category-id option:first').prop('selected', true);
    $('#frmSectionCareerModule #columns option:selected').prop('selected', false);
    $('#frmSectionCareerModule #columns option[value=varTitle]').prop('selected', true);
    $('#frmSectionCareerModule #columns option[value=asc]').prop('selected', true);
    $('#frmSectionCareerModule input[name=template]').val(template);
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        var extClass = $('#' + id).data('extclass');
        $('#frmSectionCareerModule input[name=editing]').val(id);
        $('#frmSectionCareerModule input[name=extra_class]').val(extClass);
        $('#frmSectionCareerModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
          if(config != ''){
        $('#frmSectionCareerModule .config').children('option[value=' + config + ']').prop('selected', true);
    }
        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });

        $('#sectionCareerModule .addSection').text('Update');
        $('#sectionCareerModule #exampleModalLabel b').text('Update Career');
    } else {
        $('#frmSectionCareerModule input[name=editing]').val('');
        $('#frmSectionCareerModule input[name=extra_class]').val('');
        //$('#frmSectionCareerModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionCareerModule .config').children('option[value=7]').prop('selected', true);

        $('#sectionCareerModule .addSection').text('Add');
        $('#sectionCareerModule #exampleModalLabel b').text('Career');

        $('#sectionCareerModule [data-dismiss="modal"]').attr( "data-toggle", "modal" );
        $('#sectionCareerModule [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );

    }

    $('#frmSectionCareerModule input[name=section_title]').val(caption);
    $('select').selectpicker();
    CareerDataTable.init(start, range);
    $("#frmSectionCareerModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    if ($('#sectionCareerModule').find('#record-table tr').length < $('input[name=total_records]').val()) {
                        start += range;
                        end += range;
                        CareerDataTable.init(start, range);
                    }
                }
            }
        }
    });

}).on('hidden.bs.modal', function () {

    range = 10;
    start = 0;
    end = range;
    $('#sectionCareerModule select[name=layoutType] option[class=list]').show();
    $('#sectionCareerModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
     $('#sectionCareerModule select').selectpicker('destroy');
    validateSectionCareer.reset();

});


$('#sectionCareerModuleTemplate').on('shown.bs.modal', function () {
$('#sectionCareerModuleTemplate select').selectpicker('');
    careerTemplate.init();
}).on('hidden.bs.modal', function () {
    $('#sectionCareerModuleTemplate select').selectpicker('destroy');
    careerTemplate.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});

$(document).on('keyup', '#sectionCareerModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionCareerModule #record-table').html('');
    CareerDataTable.init(start, range);
});

$(document).on('change', '#sectionCareerModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionCareerModule #record-table').html('');
    CareerDataTable.init(start, range);
});

$(document).on('change', '#sectionCareerModule #category-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionCareerModule #record-table').html('');
    CareerDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionCareerModule .group-checkable', function () {

    if ($(this).prop('checked')) {
        $('#sectionCareerModule #record-table .chkChoose').prop('checked', true);
        $('#sectionCareerModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionCareerModule #record-table .chkChoose:checked').each(function (index, value) {
            var id = $(this).val();
            if (!selectedItems.includes(id)) {
                selectedItems.push(id);
                recTitle.push($(this).data('title'));
            }
        });
    } else {
        $('#sectionCareerModule #record-table .chkChoose').prop('checked', false);
        $('#sectionCareerModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
        recTitle = [];
    }
    $('#sectionCareerModule input[name=selectedIds]').val(selectedItems);
    $('#sectionCareerModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('change', '#sectionCareerModule #record-table .chkChoose', function () {
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

    if ($('#sectionCareerModule #record-table .chkChoose:checked').length == $('#sectionCareerModule #record-table tr .chkChoose').length) {
        $('#sectionCareerModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionCareerModule .group-checkable').prop('checked', false);
    }

    $('#sectionCareerModule input[name=selectedIds]').val(selectedItems);
    $('#sectionCareerModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('click', '#sectionCareerModule #record-table tr', function (e) {
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

$(document).on('change', '#sectionCareerModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});
//..Group checkbox checking