var ignoreItems = '';
var selectedItems = '';
var recTitle = [];

var TeamModule = function () {
    // public functions
    return {
        reInitSortable: function () {
            $("#section-container").sortable('destroy');
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt'
            });
        },
        moduleSectionsTeam: function (caption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class) {
            recids = recids.split(',');
            recTitle = recTitle.split(',');

            if (recids == '') {
                recids = [];
                recTitle = [];
            }

            recids = $.unique(recids);
            //recTitle = $.unique(recTitle);
            var section = '';

            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="team-module">';
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
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link team-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="team" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }

            } else {

                var teamIds = [];
                var teamTitles = [];

                $('.section-item[data-editor=' + edit + '] li').each(function (key, val) {
                    var iId = $(this).data('id');
                    teamIds.push(iId);

                    var iTitle = $(this).text();
                    teamTitles.push(iTitle);

                });

                $.each(recids, function (index, value) {
                    teamIds.push(value);
                    teamTitles.push(recTitle[index]);                
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(teamIds, function (index, value) {
                    if (value != '') {
                        section += '<li  data-id="' + value + '" id="' + value + '-item-' + edit + '">' + teamTitles[index]  + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                    }
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link team-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="team" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);

            }
        },
        teamTemplate: function (val, config, template, edit, configTxt, layout, extra_class) {

            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="team-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item teamTemplate" data-editor="' + iCount + '">';
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
        submitFrmSectionTeamModuleTemplate: function () {
            if ($('#frmSectionTeamModuleTemplate').validate().form()) {
                var edit = $('#frmSectionTeamModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionTeamModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionTeamModuleTemplate input[name=section_title]').val();
                var extra_class = $('#frmSectionTeamModuleTemplate input[name=extra_class]').val();
                var template = $('#frmSectionTeamModuleTemplate input[name=template]').val();
                var config = $('#frmSectionTeamModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionTeamModuleTemplate .config option:selected').text();
                var layout = $('#frmSectionTeamModuleTemplate select[name=layoutType]').val();
                TeamModule.teamTemplate(val, config, template, edit, configTxt, layout, extra_class);
                $('#sectionTeamModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionTeamModule: function () {
            if ($('#frmSectionTeamModule').validate().form()) {
                var edit = $('#frmSectionTeamModule input[name=editing]').val() != '' ? $('#frmSectionTeamModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionTeamModule input[name=template]').val();
                var extra_class = $('#frmSectionTeamModule input[name=extra_class]').val();
                var imgCaption = $('#frmSectionTeamModule input[name=section_title]').val();
                var config = $('#frmSectionTeamModule .config').val();
                var configTxt = $('#frmSectionTeamModule .config option:selected').text();
                var layoutType = $('#frmSectionTeamModule select[name=layoutType]').val();
                var recids = $('#frmSectionTeamModule input[name=selectedIds]').val();
                var recTitle = $('#frmSectionTeamModule input[name=selectedTitles]').val();

                TeamModule.moduleSectionsTeam(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class);
                TeamModule.reInitSortable();
                $('#sectionTeamModule').modal('hide');
            }
        }
    };
}();

var teamDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var sort = $('#sectionTeamModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/team/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionTeamModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    status: '',
                    searchValue: $('#sectionTeamModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionTeamModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);
                    
                    if(result.recordsTotal == 0 || result.found == 0) {
                        $('#frmSectionTeamModule').find('.addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionTeamModule').find('.addSection').removeAttr('disabled');
                    }
                },
                error: function (req, err) {
                    console.log('error:' + err);
                }
            });
        }
    };
}();

var validateSectionteam = function () {
    var handleSectionteam = function () 
    {
        $("#frmSectionTeamModule").validate({
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
                            return $('#frmSectionTeamModule input[name="editing"]').val() == '';
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
                    error.insertBefore($('#frmSectionTeamModule .table-container .table:first'));
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
                $('.alert-danger', $('#frmSectionTeamModule')).show();
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
                TeamModule.submitFrmSectionTeamModule();
                return false;
            }
        });

        $('#frmSectionTeamModule input').keypress(function (e) {
            if (e.which == 13) {
                TeamModule.submitFrmSectionTeamModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSectionteam();
        },
        reset: function () {
            var validator = $("#frmSectionTeamModule").validate();
            validator.resetForm();
        }
    };
}();

var teamTemplate = function () {
    var teamTemplate = function () {
        $("#frmSectionTeamModuleTemplate").validate({
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
                $('.alert-danger', $('#frmSectionTeamModuleTemplate')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                TeamModule.submitFrmSectionTeamModuleTemplate();
                return false;
            }
        });
        $('#frmSectionTeamModuleTemplate input').keypress(function (e) {
            if (e.which == 13) {
                TeamModule.submitFrmSectionTeamModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            teamTemplate();
        },
        reset: function () {
            var validator = $("#frmSectionTeamModuleTemplate").validate();
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

$(document).on('click', '.team-module', function(event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionTeamModule').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('#sectionTeamModule [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionTeamModule [data-dismiss="modal"]').attr( "data-target", "" );
});


$(document).on('click', '.team', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionTeamModule').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
});

$(document).on('click', '.team-template', function (event) 
{
    $('#sectionTeamModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionTeamModuleTemplate [data-dismiss="modal"]').attr( "data-target", "" );

    $('#pgBuiderSections').modal('hide');
    $('#sectionTeamModuleTemplate').modal({
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

        $('#frmSectionTeamModuleTemplate input[name=editing]').val(id);
        $('#frmSectionTeamModuleTemplate input[name=section_title]').val($.trim(value));
        $('#frmSectionTeamModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionTeamModuleTemplate .config option[value=' + config + ']').prop('selected', true);
        $('#frmSectionTeamModuleTemplate input[name=extra_class]').val(extclass);
        $('#frmSectionTeamModuleTemplate .addSection').text('Update');
        $('#frmSectionTeamModuleTemplate #exampleModalLabel b').text('Edit Team');

    } else {

        var value = $(this).text();
        $('#frmSectionTeamModuleTemplate input[name=editing]').val('');
        $('#frmSectionTeamModuleTemplate input[name=section_title]').val($.trim(value));
        //$('#frmSectionTeamModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionTeamModuleTemplate input[name=extra_class]').val('');
        $('#frmSectionTeamModuleTemplate .addSection').text('Add');
        $('#frmSectionTeamModuleTemplate #exampleModalLabel b').text('Add Team');

        $('#sectionTeamModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "modal" );
//        $('#sectionTeamModuleTemplate [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );
    }

    $('#frmSectionTeamModuleTemplate').find('input[name=template]').val($(this).data('filter'));

});

$('#frmSectionTeamModuleTemplate').on('submit', function (e) {
    e.preventDefault();
});

//..End Open while add or edit section

$('#sectionTeamModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
     $('#sectionTeamModule select').selectpicker('');
    //$('#datatable_team_ajax').closest('.col-md-12').loading('start');
    validateSectionteam.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    recTitle = [];
    ignoreItems = [];
    $('#sectionTeamModule input[name=selectedIds]').val(null);
    $('#sectionTeamModule input[name=selectedTitles]').val(null);

    $('#frmSectionTeamModule #columns option:selected').prop('selected', false);
    $('#frmSectionTeamModule #columns option[value=varTitle]').prop('selected', true);
    $('#frmSectionTeamModule #columns option[value=asc]').prop('selected', true);
    $('#frmSectionTeamModule input[name=template]').val(template);
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        var extClass = $('#' + id).data('extclass');
        $('#frmSectionTeamModule input[name=editing]').val(id);
        $('#frmSectionTeamModule input[name=extra_class]').val(extClass);
        $('#frmSectionTeamModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
         if(config != ''){
        $('#frmSectionTeamModule .config').children('option[value=' + config + ']').prop('selected', true);
    }
        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });

        $('#sectionTeamModule .addSection').text('Update');
        $('#sectionTeamModule #exampleModalLabel b').text('Update Team');
    } else {
        $('#frmSectionTeamModule input[name=editing]').val('');
        $('#frmSectionTeamModule input[name=extra_class]').val('');
        //$('#frmSectionTeamModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionTeamModule .config').children('option[value=7]').prop('selected', true);

        $('#sectionTeamModule .addSection').text('Add');
        $('#sectionTeamModule #exampleModalLabel b').text('Team');

        $('#sectionTeamModule [data-dismiss="modal"]').attr( "data-toggle", "modal" );
//        $('#sectionTeamModule [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );
    }

    $('#frmSectionTeamModule input[name=section_title]').val(caption);

    teamDataTable.init(start, range);
    $("#frmSectionTeamModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    if ($('#sectionTeamModule').find('#record-table tr').length < $('input[name=total_records]').val()) {
                        start += range;
                        end += range;
                        teamDataTable.init(start, range);
                    }
                }
            }
        }
    });

}).on('hidden.bs.modal', function () {

    range = 10;
    start = 0;
    end = range;
    $('#sectionTeamModule select[name=layoutType] option[class=list]').show();
    $('#sectionTeamModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
     $('#sectionTeamModule select').selectpicker('destroy');
    validateSectionteam.reset();

});


$('#sectionTeamModuleTemplate').on('shown.bs.modal', function () {
 $('#sectionTeamModuleTemplate select').selectpicker('');
    teamTemplate.init();
}).on('hidden.bs.modal', function () {
     $('#sectionTeamModuleTemplate select').selectpicker('destroy');
    teamTemplate.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});

$(document).on('keyup', '#sectionTeamModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionTeamModule #record-table').html('');
    teamDataTable.init(start, range);
});

$(document).on('change', '#sectionTeamModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionTeamModule #record-table').html('');
    teamDataTable.init(start, range);
});



//Group checkbox checking
$(document).on('change', '#sectionTeamModule .group-checkable', function () {

    if ($(this).prop('checked')) {
        $('#sectionTeamModule #record-table .chkChoose').prop('checked', true);
        $('#sectionTeamModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionTeamModule #record-table .chkChoose:checked').each(function (index, value) {
            var id = $(this).val();
            if (!selectedItems.includes(id)) {
                selectedItems.push(id);
                recTitle.push($(this).data('title'));
            }
        });
    } else {
        $('#sectionTeamModule #record-table .chkChoose').prop('checked', false);
        $('#sectionTeamModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
        recTitle = [];
    }
    $('#sectionTeamModule input[name=selectedIds]').val(selectedItems);
    $('#sectionTeamModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('change', '#sectionTeamModule #record-table .chkChoose', function () {
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

    if ($('#sectionTeamModule #record-table .chkChoose:checked').length == $('#sectionTeamModule #record-table tr .chkChoose').length) {
        $('#sectionTeamModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionTeamModule .group-checkable').prop('checked', false);
    }

    $('#sectionTeamModule input[name=selectedIds]').val(selectedItems);
    $('#sectionTeamModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('click', '#sectionTeamModule #record-table tr', function (e) {
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

$(document).on('change', '#sectionTeamModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});
//..Group checkbox checking