var ignoreItems = '';
var selectedItems = '';
var recTitle = [];

var ProductModule = function () {
    // public functions
    return {
        reInitSortable: function () {
            $("#section-container").sortable('destroy');
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt'
            });
        },
        moduleSectionsProduct: function (caption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class) {
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
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="product-module">';
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
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link product-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="product" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var productIds = [];
                var productTitles = [];
                var productCustomized = [];
                var productDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function (key, val) {
                    var iId = $(this).data('id');
                    productIds.push(iId);

                    var iTitle = $(this).text();
                    productTitles.push(iTitle);

                    var Icustomized = $(this).data('customized');
                    if (typeof Icustomized != 'undefined') {
                        productCustomized.push(Icustomized);
                    }


                    var Idescription = $(this).data('description');
                    if (typeof Idescription != 'undefined') {
                        productDescription.push(Idescription.toString());
                    }


                });

                $.each(recids, function (index, value) {
                    productIds.push(value);
                    productTitles.push(recTitle[index]);
                    productCustomized.push(false);
                    productDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;(' + configTxt + ')</label>';
                section += '<ul class="record-list">';
                $.each(productIds, function (index, value) {
                    if (value != '') {
                        section += '<li data-customized="' + productCustomized[index] + '"  data-id="' + value + '" id="' + value + '-item-' + edit + '">' + productTitles[index]  + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                    }
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link product-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-extclass="' + extra_class + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="product" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        productTemplate: function (val, config, template, edit, configTxt, layout, extra_class) {
            var section = '';

            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" data-filter="' + template + '" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="product-template">';
                section += '<i class="action-icon edit fa fa-pencil"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item productTemplate" data-editor="' + iCount + '">';
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
        submitFrmSectionProductModuleTemplate: function () {
            if ($('#frmSectionProductModuleTemplate').validate().form()) {
                var edit = $('#frmSectionProductModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionProductModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionProductModuleTemplate input[name=section_title]').val();
                var extra_class = $('#frmSectionProductModuleTemplate input[name=extra_class]').val();
                var template = $('#frmSectionProductModuleTemplate input[name=template]').val();
                var config = $('#frmSectionProductModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionProductModuleTemplate .config option:selected').text();
                var layout = $('#frmSectionProductModuleTemplate select[name=layoutType]').val();
                ProductModule.productTemplate(val, config, template, edit, configTxt, layout, extra_class);
                $('#sectionProductModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionProductModule: function () {
            if ($('#frmSectionProductModule').validate().form()) {
                var edit = $('#frmSectionProductModule input[name=editing]').val() != '' ? $('#frmSectionProductModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionProductModule input[name=template]').val();
                var extra_class = $('#frmSectionProductModule input[name=extra_class]').val();
                var imgCaption = $('#frmSectionProductModule input[name=section_title]').val();
                var config = $('#frmSectionProductModule .config').val();
                var configTxt = $('#frmSectionProductModule .config option:selected').text();
                var layoutType = $('#frmSectionProductModule select[name=layoutType]').val();
                var recids = $('#frmSectionProductModule input[name=selectedIds]').val();
                var recTitle = $('#frmSectionProductModule input[name=selectedTitles]').val();

                ProductModule.moduleSectionsProduct(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType, extra_class);
                ProductModule.reInitSortable();
                $('#sectionProductModule').modal('hide');
            }
        }
    };
}();

var ProductDataTable = function () {
    // public functions
    return {
        //main function
        init: function (from, to) {
            var sort = $('#sectionProductModule #columns').val();
            var ajaxUrl = site_url + '/powerpanel/products/get_builder_list';

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'JSON',
                data: {
                    critaria: $('#frmSectionProductModule input[name=template]').val(),
                    columns: sort[0],
                    order: sort[1],
                    catValue: $('#sectionProductModule #category-id').val(),
                    status: '',
                    searchValue: $('#sectionProductModule #searchfilter').val(),
                    start: from,
                    length: to,
                    ignore: ignoreItems,
                    selected: selectedItems
                },
                async: false,
                success: function (result) {
                    $('#sectionProductModule #record-table').append(result.data);
                    $('input[name=total_records]').val(result.recordsTotal);
                    $('input[name=found]').val(result.found);

                    if(result.recordsTotal == 0 || result.found == 0) {
                        $('#frmSectionProductModule').find('.addSection').attr('disabled','disabled');
                    }else{
                        $('#frmSectionProductModule').find('.addSection').removeAttr('disabled');
                    }

                },
                error: function (req, err) {
                    console.log('error:' + err);
                }
            });
        },
        getCategory: function () {

            var ajaxUrl = site_url + '/powerpanel/product-category/get_builder_list';
            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                dataType: 'HTML',
                async: false,
                success: function (result) {
                    $('#sectionProductModule #category-id').html(result);
                }
            });

        }
    };
}();

var validateSectionProduct = function () {
    var handleSectionProduct = function () {
        $("#frmSectionProductModule").validate({
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
                            return $('#frmSectionProductModule input[name="editing"]').val() == '';
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
                    error.insertBefore($('#frmSectionProductModule .table-container .table:first'));
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
                $('.alert-danger', $('#frmSectionProductModule')).show();
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
                ProductModule.submitFrmSectionProductModule();
                return false;
            }
        });

        $('#frmSectionProductModule input').keypress(function (e) {
            if (e.which == 13) {
                ProductModule.submitFrmSectionProductModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleSectionProduct();
        },
        reset: function () {
            var validator = $("#frmSectionProductModule").validate();
            validator.resetForm();
        }
    };
}();

var productTemplate = function () {
    var productTemplate = function () {
        $("#frmSectionProductModuleTemplate").validate({
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
                $('.alert-danger', $('#frmSectionProductModuleTemplate')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                ProductModule.submitFrmSectionProductModuleTemplate();
                return false;
            }
        });
        $('#frmSectionProductModuleTemplate input').keypress(function (e) {
            if (e.which == 13) {
                ProductModule.submitFrmSectionProductModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            productTemplate();
        },
        reset: function () {
            var validator = $("#frmSectionProductModuleTemplate").validate();
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

$(document).on('click', '.product-module', function(event) {
    id = $(this).data('id');
    caption = $(this).text();
    template = $(this).data('filter');
    $('#pgBuiderSections').modal('hide');
    $('#sectionProductModule').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#sectionProductModule [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionProductModule [data-dismiss="modal"]').attr( "data-target", "" );
});


$(document).on('click', '.products', function (event) {
    caption = $(this).text();
    template = $(this).data('filter');
    id = '';
    ignoreItems = '';
    $('#pgBuiderSections').modal('hide');
    $('#sectionProductModule').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
});

$(document).on('click', '.product-template', function (event) 
{
    $('#sectionProductModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "" );
    $('#sectionProductModuleTemplate [data-dismiss="modal"]').attr( "data-target", "" );

    $('#pgBuiderSections').modal('hide');
    $('#sectionProductModuleTemplate').modal({
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

        $('#frmSectionProductModuleTemplate input[name=editing]').val(id);
        $('#frmSectionProductModuleTemplate input[name=section_title]').val($.trim(value));
        $('#frmSectionProductModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionProductModuleTemplate .config option[value=' + config + ']').prop('selected', true);
        $('#frmSectionProductModuleTemplate input[name=extra_class]').val(extclass);
        $('#frmSectionProductModuleTemplate .addSection').text('Update');
        $('#frmSectionProductModuleTemplate #exampleModalLabel b').text('Edit Product');

    } else {

        var value = $(this).text();
        $('#frmSectionProductModuleTemplate input[name=editing]').val('');
        $('#frmSectionProductModuleTemplate input[name=section_title]').val($.trim(value));
        //$('#frmSectionProductModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionProductModuleTemplate input[name=extra_class]').val('');
        $('#frmSectionProductModuleTemplate .addSection').text('Add');
        $('#frmSectionProductModuleTemplate #exampleModalLabel b').text('Add Product');

        $('#sectionProductModuleTemplate [data-dismiss="modal"]').attr( "data-toggle", "modal" );
        $('#sectionProductModuleTemplate [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );
    }

    $('#frmSectionProductModuleTemplate').find('input[name=template]').val($(this).data('filter'));

});

$('#frmSectionProductModuleTemplate').on('submit', function (e) {
    e.preventDefault();
});

//..End Open while add or edit section

$('#sectionProductModule').on('shown.bs.modal', function () {
    caption = $.trim(caption);
    //$('#datatable_Product_ajax').closest('.col-md-12').loading('start');
    validateSectionProduct.init();
    $(this).find('.group-checkable').prop('checked', false);
    selectedItems = [];
    recTitle = [];
    ignoreItems = [];
    $('#sectionProductModule input[name=selectedIds]').val(null);
    $('#sectionProductModule input[name=selectedTitles]').val(null);
     $('select').selectpicker('destroy');
    ProductDataTable.getCategory();
    $('#frmSectionProductModule #category-id option:first').prop('selected', true);
    $('#frmSectionProductModule #columns option:selected').prop('selected', false);
    $('#frmSectionProductModule #columns option[value=varTitle]').prop('selected', true);
    $('#frmSectionProductModule #columns option[value=asc]').prop('selected', true);
    $('#frmSectionProductModule input[name=template]').val(template);
    var layout = '';
    if (id != '') {
        caption = $('#' + id).data('caption');
        layout = $('#' + id).data('layout');
        var config = $('#' + id).data('config');
        var extClass = $('#' + id).data('extclass');
        $('#frmSectionProductModule input[name=editing]').val(id);
        $('#frmSectionProductModule input[name=extra_class]').val(extClass);
        $('#frmSectionProductModule select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#frmSectionProductModule .config').children('option[value=' + config + ']').prop('selected', true);

        $('.section-item[data-editor=' + id + '] li').each(function (key, val) {
            var iId = $(this).data('id');
            ignoreItems.push(iId);
        });

        $('#sectionProductModule .addSection').text('Update');
        $('#sectionProductModule #exampleModalLabel b').text('Update Product');
    } else {
        $('#frmSectionProductModule input[name=editing]').val('');
        $('#frmSectionProductModule input[name=extra_class]').val('');
        //$('#frmSectionProductModule select[name=layoutType] option:first').prop('selected', true);
        $('#frmSectionProductModule .config').children('option[value=7]').prop('selected', true);

        $('#sectionProductModule .addSection').text('Add');
        $('#sectionProductModule #exampleModalLabel b').text('Product');

        $('#sectionProductModule [data-dismiss="modal"]').attr( "data-toggle", "modal" );
        $('#sectionProductModule [data-dismiss="modal"]').attr( "data-target", "#pgBuiderSections" );
    }

    $('#frmSectionProductModule input[name=section_title]').val(caption);
    $('select').selectpicker();
    ProductDataTable.init(start, range);
    $("#frmSectionProductModule #mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark",
        callbacks: {
            onTotalScroll: function () {
                if ($('input[name=found]').val() > 0) {
                    if ($('#sectionProductModule').find('#record-table tr').length < $('input[name=total_records]').val()) {
                        start += range;
                        end += range;
                        ProductDataTable.init(start, range);
                    }
                }
            }
        }
    });

}).on('hidden.bs.modal', function () {

    range = 10;
    start = 0;
    end = range;
    $('#sectionProductModule select[name=layoutType] option[class=list]').show();
    $('#sectionProductModule #record-table').html('');
    $(".record-list").sortable().disableSelection();
     $('#sectionProductModule select').selectpicker('destroy');
    validateSectionProduct.reset();

});


$('#sectionProductModuleTemplate').on('shown.bs.modal', function () {
$('#sectionProductModuleTemplate select').selectpicker('');
    productTemplate.init();
}).on('hidden.bs.modal', function () {
    $('#sectionProductModuleTemplate select').selectpicker('destroy');
    productTemplate.reset();
});

$(document).ajaxStart(function () {
    $('.table-scrollable').loader(loaderConfig);
}).ajaxComplete(function () {
    setTimeout(function () {
        $.loader.close(true);
    }, 500);
});

$(document).on('keyup', '#sectionProductModule #searchfilter', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionProductModule #record-table').html('');
    ProductDataTable.init(start, range);
});

$(document).on('change', '#sectionProductModule #columns', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionProductModule #record-table').html('');
    ProductDataTable.init(start, range);
});

$(document).on('change', '#sectionProductModule #category-id', function () {
    range = 10;
    start = 0;
    end = range;
    $('#sectionProductModule #record-table').html('');
    ProductDataTable.init(start, range);
});


//Group checkbox checking
$(document).on('change', '#sectionProductModule .group-checkable', function () {

    if ($(this).prop('checked')) {
        $('#sectionProductModule #record-table .chkChoose').prop('checked', true);
        $('#sectionProductModule #record-table .chkChoose').parent().parent().parent().addClass('selected-record');
        $('#sectionProductModule #record-table .chkChoose:checked').each(function (index, value) {
            var id = $(this).val();
            if (!selectedItems.includes(id)) {
                selectedItems.push(id);
                recTitle.push($(this).data('title'));
            }
        });
    } else {
        $('#sectionProductModule #record-table .chkChoose').prop('checked', false);
        $('#sectionProductModule #record-table .chkChoose').parent().parent().parent().removeClass('selected-record');
        selectedItems = [];
        recTitle = [];
    }
    $('#sectionProductModule input[name=selectedIds]').val(selectedItems);
    $('#sectionProductModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('change', '#sectionProductModule #record-table .chkChoose', function () {
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

    if ($('#sectionProductModule #record-table .chkChoose:checked').length == $('#sectionProductModule #record-table tr .chkChoose').length) {
        $('#sectionProductModule .group-checkable').prop('checked', true);
    } else {
        $('#sectionProductModule .group-checkable').prop('checked', false);
    }

    $('#sectionProductModule input[name=selectedIds]').val(selectedItems);
    $('#sectionProductModule input[name=selectedTitles]').val(recTitle);
});

$(document).on('click', '#sectionProductModule #record-table tr', function (e) {
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

$(document).on('change', '#sectionProductModule #columns', function () {
    if ($(this).find('option:selected').length > 1) {
        //$('#mCSB_1_container').trigger('click');
    }
});
//..Group checkbox checking