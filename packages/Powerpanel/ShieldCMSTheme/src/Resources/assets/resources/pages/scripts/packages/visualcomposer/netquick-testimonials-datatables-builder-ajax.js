var ignoreItems = '';
var selectedItems = '';
var recTitle = [];

var TestimonialModule = function () {
    // public functions
    return {
        reInitSortable: function () {
            $("#section-container").sortable('destroy');
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt'
            });
        },
        moduleSectionsTestimonial: function (caption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class) {
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
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="testimonial-module">';
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
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link testimonial-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="testimonial" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }

            } else {

                var testimonialIds = [];
                var testimonialTitles = [];

                $('.section-item[data-editor=' + edit + '] li').each(function (key, val) {
                    var iId = $(this).data('id');
                    testimonialIds.push(iId);

                    var iTitle = $(this).text();
                    testimonialTitles.push(iTitle);

                });

                $.each(recids, function (index, value) {
                    testimonialIds.push(value);
                    testimonialTitles.push(recTitle[index]);                
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;(' + configTxt + ')</label>';
                section += '<ul class="record-list">';
                $.each(testimonialIds, function (index, value) {
                    if (value != '') {
                        section += '<li  data-id="' + value + '" id="' + value + '-item-' + edit + '">' + testimonialTitles[index]  + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                    }
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link testimonial-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="testimonial" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);

            }
        },
        testimonialTemplate: function (val, config, template, edit, configTxt, layout, extra_class) {

            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="testimonial-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item testimonialTemplate" data-editor="' + iCount + '">';
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
        submitFrmSectionTestimonialModuleTemplate: function () {
            if ($('#frmSectionTestimonialModuleTemplate').validate().form()) {
                var edit = $('#frmSectionTestimonialModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionTestimonialModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionTestimonialModuleTemplate input[name=section_title]').val();
                var extra_class = $('#frmSectionTestimonialModuleTemplate input[name=extra_class]').val();
                var template = $('#frmSectionTestimonialModuleTemplate input[name=template]').val();
                var config = $('#frmSectionTestimonialModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionTestimonialModuleTemplate .config option:selected').text();
                var layout = $('#frmSectionTestimonialModuleTemplate select[name=layoutType]').val();
                TestimonialModule.testimonialTemplate(val, config, template, edit, configTxt, layout, extra_class);
                $('#sectionTestimonialModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionTestimonialModule: function () {
            if ($('#frmSectionTestimonialModule').validate().form()) {
                var edit = $('#frmSectionTestimonialModule input[name=editing]').val() != '' ? $('#frmSectionTestimonialModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionTestimonialModule input[name=template]').val();
                var extra_class = $('#frmSectionTestimonialModule input[name=extra_class]').val();
                var imgCaption = $('#frmSectionTestimonialModule input[name=section_title]').val();
                var config = $('#frmSectionTestimonialModule .config').val();
                var configTxt = $('#frmSectionTestimonialModule .config option:selected').text();
                var layoutType = $('#frmSectionTestimonialModule select[name=layoutType]').val();
                var recids = $('#frmSectionTestimonialModule input[name=selectedIds]').val();
                var recTitle = $('#frmSectionTestimonialModule input[name=selectedTitles]').val();

                TestimonialModule.moduleSectionsTestimonial(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class);
                TestimonialModule.reInitSortable();
                $('#sectionTestimonialModule').modal('hide');
            }
        }
    };
}();

var TestimonialDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var sort = $('#sectionTestimonialModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/testimonials/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionTestimonialModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    status: '',
                    searchValue: $('#sectionTestimonialModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionTestimonialModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                    if(result.recordsTotal == 0 || result.found == 0) {
                        $('#frmSectionTestimonialModule').find('.addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionTestimonialModule').find('.addSection').removeAttr('disabled');
                    }
                },
                error: function (req, err) {
                    console.log('error:' + err);
                }
            });
        }
    };
}();

var validateSectionTestimonial = function () {
    var handleSectionTestimonial = function () 
    {
        $("#frmSectionTestimonialModule").validate({
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
                            return $('#frmSectionTestimonialModule input[name="editing"]').val() == '';
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
                    error.insertBefore($('#frmSectionTestimonialModule .table-container .table:first'));
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
                $('.alert-danger', $('#frmSectionTestimonialModule')).show();
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
                TestimonialModule.submitFrmSectionTestimonialModule();
                return false;
            }
        });

        $('#frmSectionTestimonialModule input').keypress(function (e) {
            if (e.which == 13) {
                TestimonialModule.submitFrmSectionTestimonialModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSectionTestimonial();
        },
        reset: function () {
            var validator = $("#frmSectionTestimonialModule").validate();
            validator.resetForm();
        }
    };
}();

var testimonialTemplate = function () {
    var testimonialTemplate = function () {
        $("#frmSectionTestimonialModuleTemplate").validate({
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
                $('.alert-danger', $('#frmSectionTestimonialModuleTemplate')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                TestimonialModule.submitFrmSectionTestimonialModuleTemplate();
                return false;
            }
        });
        $('#frmSectionTestimonialModuleTemplate input').keypress(function (e) {
            if (e.which == 13) {
                TestimonialModule.submitFrmSectionTestimonialModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            testimonialTemplate();
        },
        reset: function () {
            var validator = $("#frmSectionTestimonialModuleTemplate").validate();
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

$(document).on('click', '.testimonial-module', function(event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionTestimonialModule').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('#sectionTestimonialModule [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionTestimonialModule [data-dismiss="modal"]').attr( "data-target", "" );
});


$(document).on('click', '.testimonials', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionTestimonialModule').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
});

$(document).on('click', '.testimonial-template', function (event) 
{
    $('#sectionTestimonialModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionTestimonialModuleTemplate [data-dismiss="modal"]').attr( "data-target", "" );

    $('#pgBuiderSections').modal('hide');
    $('#sectionTestimonialModuleTemplate').modal({
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

        $('#frmSectionTestimonialModuleTemplate input[name=editing]').val(id);
        $('#frmSectionTestimonialModuleTemplate input[name=section_title]').val($.trim(value));
        $('#frmSectionTestimonialModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionTestimonialModuleTemplate .config option[value=' + config + ']').prop('selected', true);
        $('#frmSectionTestimonialModuleTemplate input[name=extra_class]').val(extclass);
        $('#frmSectionTestimonialModuleTemplate .addSection').text('Update');
        $('#frmSectionTestimonialModuleTemplate #exampleModalLabel b').text('Edit Testimonial');

    } else {

        var value = $(this).text();
        $('#frmSectionTestimonialModuleTemplate input[name=editing]').val('');
        $('#frmSectionTestimonialModuleTemplate input[name=section_title]').val($.trim(value));
        //$('#frmSectionTestimonialModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionTestimonialModuleTemplate input[name=extra_class]').val('');
        $('#frmSectionTestimonialModuleTemplate .addSection').text('Add');
        $('#frmSectionTestimonialModuleTemplate #exampleModalLabel b').text('Add Testimonial');

        $('#sectionTestimonialModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "modal" );
        $('#sectionTestimonialModuleTemplate [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );
    }

    $('#frmSectionTestimonialModuleTemplate').find('input[name=template]').val($(this).data('filter'));

});

$('#frmSectionTestimonialModuleTemplate').on('submit', function (e) {
    e.preventDefault();
});

//..End Open while add or edit section

$('#sectionTestimonialModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
       $('#sectionTestimonialModule select').selectpicker();
    //$('#datatable_testimonial_ajax').closest('.col-md-12').loading('start');
    validateSectionTestimonial.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    recTitle = [];
    ignoreItems = [];
    $('#sectionTestimonialModule input[name=selectedIds]').val(null);
    $('#sectionTestimonialModule input[name=selectedTitles]').val(null);

    $('#frmSectionTestimonialModule #columns option:selected').prop('selected', false);
    $('#frmSectionTestimonialModule #columns option[value=varTitle]').prop('selected', true);
    $('#frmSectionTestimonialModule #columns option[value=asc]').prop('selected', true);
    $('#frmSectionTestimonialModule input[name=template]').val(template);
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        var extClass = $('#' + id).data('extclass');
        $('#frmSectionTestimonialModule input[name=editing]').val(id);
        $('#frmSectionTestimonialModule input[name=extra_class]').val(extClass);
        $('#frmSectionTestimonialModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
         if(config != ''){
        $('#frmSectionTestimonialModule .config').children('option[value=' + config + ']').prop('selected', true);
    }

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });

        $('#sectionTestimonialModule .addSection').text('Update');
        $('#sectionTestimonialModule #exampleModalLabel b').text('Update Testimonial');
    } else {
        $('#frmSectionTestimonialModule input[name=editing]').val('');
        $('#frmSectionTestimonialModule input[name=extra_class]').val('');
        //$('#frmSectionTestimonialModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionTestimonialModule .config').children('option[value=7]').prop('selected', true);

        $('#sectionTestimonialModule .addSection').text('Add');
        $('#sectionTestimonialModule #exampleModalLabel b').text('Testimonial');

        $('#sectionTestimonialModule [data-dismiss="modal"]').attr( "data-toggle", "modal" );
        $('#sectionTestimonialModule [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );
    }

    $('#frmSectionTestimonialModule input[name=section_title]').val(caption);

    TestimonialDataTable.init(start, range);
    $("#frmSectionTestimonialModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    if ($('#sectionTestimonialModule').find('#record-table tr').length < $('input[name=total_records]').val()) {
                        start += range;
                        end += range;
                        TestimonialDataTable.init(start, range);
                    }
                }
            }
        }
    });

}).on('hidden.bs.modal', function () {

    range = 10;
    start = 0;
    end = range;
    $('#sectionTestimonialModule select[name=layoutType] option[class=list]').show();
    $('#sectionTestimonialModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
    $('#sectionTestimonialModule select').selectpicker('destroy');
    validateSectionTestimonial.reset();

});


$('#sectionTestimonialModuleTemplate').on('shown.bs.modal', function () {
 $('#sectionTestimonialModuleTemplate select').selectpicker('');
    testimonialTemplate.init();
}).on('hidden.bs.modal', function () {
     $('#sectionTestimonialModuleTemplate select').selectpicker('destroy');
    testimonialTemplate.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});

$(document).on('keyup', '#sectionTestimonialModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionTestimonialModule #record-table').html('');
    TestimonialDataTable.init(start, range);
});

$(document).on('change', '#sectionTestimonialModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionTestimonialModule #record-table').html('');
    TestimonialDataTable.init(start, range);
});



//Group checkbox checking
$(document).on('change', '#sectionTestimonialModule .group-checkable', function () {

    if ($(this).prop('checked')) {
        $('#sectionTestimonialModule #record-table .chkChoose').prop('checked', true);
        $('#sectionTestimonialModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionTestimonialModule #record-table .chkChoose:checked').each(function (index, value) {
            var id = $(this).val();
            if (!selectedItems.includes(id)) {
                selectedItems.push(id);
                recTitle.push($(this).data('title'));
            }
        });
    } else {
        $('#sectionTestimonialModule #record-table .chkChoose').prop('checked', false);
        $('#sectionTestimonialModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
        recTitle = [];
    }
    $('#sectionTestimonialModule input[name=selectedIds]').val(selectedItems);
    $('#sectionTestimonialModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('change', '#sectionTestimonialModule #record-table .chkChoose', function () {
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

    if ($('#sectionTestimonialModule #record-table .chkChoose:checked').length == $('#sectionTestimonialModule #record-table tr .chkChoose').length) {
        $('#sectionTestimonialModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionTestimonialModule .group-checkable').prop('checked', false);
    }

    $('#sectionTestimonialModule input[name=selectedIds]').val(selectedItems);
    $('#sectionTestimonialModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('click', '#sectionTestimonialModule #record-table tr', function (e) {
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

$(document).on('change', '#sectionTestimonialModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});
//..Group checkbox checking