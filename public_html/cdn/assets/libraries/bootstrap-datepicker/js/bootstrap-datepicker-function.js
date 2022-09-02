function acDatepickerPlaceholder(e) {
    $(e).attr("placeholder", "DD/MM/YYYY");
    $(e).attr("readonly", "readonly");
    $(e).attr("maxlength", "10");
}

function acDatepickerPlaceholderRange(e) {
    $(e).children('input').attr("placeholder", "DD/MM/YYYY");
    $(e).children('input').attr("title", "Start Date");
    $(e).children('input + input').attr("placeholder", "DD/MM/YYYY");
    $(e).children('input + input').attr("title", "End Date");
    $(e).children('input').attr("readonly", "readonly");
    $(e).children('input').attr("maxlength", "10");
    $(e).children('input').css({"float": "left", "width": "50%"});
    $(e).children('input + input').css({"margin-left": "-1px"});
}

function acDatepickerBasic(e,titleHere){
    $(e).datepicker({
        format: "dd/mm/yyyy",
        todayBtn: true,
        clearBtn: true,
        autoclose: true,
        todayHighlight: true,
        enableOnReadonly: true,
        templates: {
            leftArrow: '&lsaquo;',
            rightArrow: '&rsaquo;'
        },
        title: titleHere,
        zIndexOffset: 1,
        maxViewMode: 2,
        minViewMode: 0,
        startDate: '01-01-1900',
        endDate: '31-12-2050',
    });
    acDatepickerPlaceholder (e);
}

function acDatepickerPrevDisabled(e){
    $(e).datepicker({
        format: "dd/mm/yyyy",
        todayBtn: true,
        clearBtn: true,
        autoclose: true,
        todayHighlight: true,
        enableOnReadonly: true,
        templates: {
            leftArrow: '&lsaquo;',
            rightArrow: '&rsaquo;'
        },
        title: titleHere,
        zIndexOffset: 1,
        maxViewMode: 2,
        minViewMode: 0,
        startDate: new Date(),
        endDate: '31-12-2050',
    });
    acDatepickerPlaceholder (e);
}

function acDatepickerNextDisabled(e){
    $(e).datepicker({
        format: "dd/mm/yyyy",
        todayBtn: true,
        clearBtn: true,
        autoclose: true,
        todayHighlight: true,
        enableOnReadonly: true,
        templates: {
            leftArrow: '&lsaquo;',
            rightArrow: '&rsaquo;'
        },
        title: titleHere,
        zIndexOffset: 1,
        maxViewMode: 2,
        minViewMode: 0,
        startDate: '01-01-1900',
        endDate: new Date(),
    });
    acDatepickerPlaceholder (e);
}

function acDatepickerBasicRange(e,titleHere){
    $(e).datepicker({
        format: "dd/mm/yyyy",
        todayBtn: true,
        clearBtn: true,
        autoclose: true,
        todayHighlight: true,
        enableOnReadonly: true,
        templates: {
            leftArrow: '&lsaquo;',
            rightArrow: '&rsaquo;'
        },
        title: titleHere,
        zIndexOffset: 1,
        maxViewMode: 2,
        minViewMode: 0,
        startDate: '01-01-1900',
        endDate: '31-12-2050',
    });
    acDatepickerPlaceholderRange (e);
}

$(document).ready(function() {
    acDatepickerBasicRange (".ac-datepicker-basic", "Select the (EVENT) date.");
});