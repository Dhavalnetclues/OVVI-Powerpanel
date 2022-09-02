// code for alias
var editContent = '';
var controller = '';
var stroke = 0;
/* for edit functionality */
var ex_alias = $('.aliasField').val();
if (ex_alias != '') {
    var dataCategoryAlias = '';
    if ($('select[name=category_id]').length > 0) {
        dataCategoryAlias = $('select[name=category_id]').children('option:selected').data('categryalias');
    }
    var buidLink = '/' + moduleAlias + '/';
    //    if (typeof dataCategoryAlias != 'undefined' && dataCategoryAlias != '') {
    //        buidLink += dataCategoryAlias + '/' + ex_alias;
    //    } else {
    buidLink += ex_alias;
    //    }
    var site_url1 = stripTrailingSlash(site_url);
    var links = site_url1 + buidLink.replace(/\/+/g, "/");
    if (moduleAlias != 'static-block') {
        $('.alias-group').removeClass('hide');
    }
    $('.alias').html(links);
    // $('.alias').attr('href',links);
    $('.alias').css('text-decoration', 'none');
    $('.alias').css('cursor', 'text');
    $('.snippet_alias').html(links);
    $('.seo_editor').css('display', 'block');
}

if (user_action == 'edit') {
    stroke++;
}


/* for edit functionality */
$(document).on('change', 'select[name=category_id]', function() {
    var dataCategoryAlias = $(this).children('option:selected').data('categryalias');
    onChangeAliasEvent(dataCategoryAlias);
});

$(document).on('change', '.hasAlias', function() {
    var categoryAlias = $('select[name=category_id]').length > 0 ? $('select[name=category_id]').children('option:selected').data('categryalias') : '';
    onChangeAliasEvent(categoryAlias);
});

function onChangeAliasEvent(dataCategoryAlias) {
    var str = $('.hasAlias').val();
    if (str.trim().length <= 0) {
        return false;
    } else {
        if (stroke < 1) {
            controller = $('.hasAlias').data('url');
            if ($('select[name=category_id]').length == 0) {
                aliasGenerate(str, dataCategoryAlias);
                stroke++;
            } else {
                if (stroke < 1) {
                    aliasGenerate(str, dataCategoryAlias);
                }
            }
        } else {
            aliasGenerateEdit($('.aliasField').val(), dataCategoryAlias);
        }
    }
}

$('.hasAlias').keypress(function(e) {
    if (e.which == 13) {
        $('input[name=title]').trigger('change');
    }
});

function aliasGenerateEdit(str, dataCategoryAlias) {
    var buidLink = '/' + moduleAlias + '/';
    buidLink += dataCategoryAlias + '/' + str;
    var site_url1 = stripTrailingSlash(site_url);
    var links = site_url1 + buidLink.replace(/\/+/g, "/");
    editContent = str;
    $('.alias').html(links);
    $('.alias').css('text-decoration', 'none');
    $('.alias').css('cursor', 'text');
    $('.aliasField').val(str);
    $('#new-alias').val(str);
    $('.alias-group').removeClass('hide');
    $('.snippet_alias').html(links);
    $('.seo_editor').css('display', 'block');
}

function aliasGenerate(str, dataCategoryAlias) {
    controller = $('.hasAlias').data('url');
    var ajaxurl = site_url + '/powerpanel/aliasGenerate';
    $.ajax({
        url: ajaxurl,
        data: {
            alias: str
        },
        type: "POST",
        dataType: "json",
        async: false,
        success: function(data) {
            if (data.alias[1]) {
                $('#aliasAlert').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
            var buidLink = '/' + moduleAlias + '/';
            //            if (dataCategoryAlias != '') {
            //                buidLink += dataCategoryAlias + '/' + data.alias[0];
            //            } else {
            buidLink += data.alias[0];
            //            }

            var site_url1 = stripTrailingSlash(site_url);
            var links = site_url1 + buidLink.replace(/\/+/g, "/");
            editContent = data.alias[0];
            $('.alias').html(links);
            $('.alias').css('text-decoration', 'none');
            $('.alias').css('cursor', 'text');
            $('.aliasField').val(data.alias[0]);
            $('#new-alias').val(data.alias[0]);
            $('.alias-group').removeClass('hide');
            $('.snippet_alias').html(links);
            $('.seo_editor').css('display', 'block');
            displayShortCode(moduleAlias, data.alias[0]);
        },
        complete: function() {

            var updated_alias = $('.aliasField').val();
            var dataCategoryAlias = $('select[name=category_id]').children('option:selected').data('categryalias');
            var buidLink = '/' + moduleAlias + '/';
            //            if (typeof dataCategoryAlias != 'undefined') {
            //                buidLink += dataCategoryAlias + '/' + updated_alias;
            //            } else {
            buidLink += updated_alias;
            //            }
            var site_url1 = stripTrailingSlash(site_url);
            var links = site_url1 + buidLink.replace(/\/+/g, "/");
            var previewurl = site_url + '/previewpage?url=' + site_url1 + '/' + moduleAlias;
            // if ($('select[name=category_id]').length > 0) {
            // 	dataCategoryAlias = $('select[name=category_id]').children('option:selected').data('categryalias');							
            // 	previewurl = site_url +'/'+moduleAlias+'/'+dataCategoryAlias;
            // }
            if (formpageurl != 'page_template') {
                var frontURL = '<a href="javascript:void(0);" class="editAlias" title="Edit"></a><a class="without_bg_icon openLink" onClick="generatePreview(\'' + previewurl + '\');" title="Open Link" ><i class="ri-external-link-line" aria-hidden="true"></i></a>';
                $('.alias-group').html('<div class="form-group"><label class="form_title" for="site_name">Url :</label> <a href="javascript:void;" class="alias">' + links + '</a><a href="javascript:void(0);" class="editAlias"> <i class="ri-pencil-line"></i>  ' + frontURL + '  </a></div>');

                $('#previewid').html('<a class="btn btn-green-drake" onClick="generatePreview(\'' + previewurl + '\');" title="Preview" >Preview</a>');
            } else {
                $('#previewid').html('<a class="without_bg_icon openLink" onClick="generatePreview(\'' + previewurl + '\');" title="Preview" ><i class="ri-external-link-line" aria-hidden="true"></i>Preview</a>');
            }
        },
        error: function() {
            console.log('error!');
        }
    });
}


// $(document).on('click', '.openLink', function(e) {
// 		e.preventDefault();
// 		e.stopPropagation();
// 		var redirection = $(this).attr('href');
// 		generatePreview(redirection);
// });

function generatePreview(redirection) {
    if (typeof preview_add_route !== 'undefined') {
        if (typeof builder !== 'undefined') {
            builder.fillFormObj();
        }
        $.ajax({
            url: preview_add_route,
            data: previewForm.serialize(),
            type: "POST",
            dataType: "json",
            async: false,
            success: function(data) {
                if (data.status != 'error') {
                    previewForm.find('input[name=previewId]').val(data.status);
                    previewForm.find('input[name=oldAlias]').val(data.alias);

                    //                    if ($('select[name=category_id]').length > 0) {
                    //                        dataCategoryAlias = $('select[name=category_id]').children('option:selected').data('categryalias');
                    //                        redirection = redirection + '/' + dataCategoryAlias;
                    //                    }

                    redirection = stripTrailingSlash(redirection);

                    redirection = redirection + '/' + previewForm.find('input[name=previewId]').val() + '/preview';
                    if (isDetailPage) {
                        redirection += '/detail';
                    }
                    window.open(redirection, '_blank');
                } else {
                    console.log(data.message)
                }
            },
            error: function() {
                console.log('error!');
            }
        });
    }
}

$(document).on('click', '.editAlias', function() {
    var updated_alias = $('.aliasField').val();
    var dataCategoryAlias = $('select[name=category_id]').children('option:selected').data('categryalias');
    var buidLink = '/' + moduleAlias + '/';
    //    if (typeof dataCategoryAlias != 'undefined') {
    //        buidLink += dataCategoryAlias + '/' + updated_alias;
    //    } else {
    buidLink += updated_alias;
    //    }
    var site_url1 = stripTrailingSlash(site_url);
    var links = site_url1 + buidLink.replace(/\/+/g, "/");
    var previewurl = site_url1 + '/' + moduleAlias;

    // if ($('select[name=category_id]').length > 0) {
    // 	dataCategoryAlias = $('select[name=category_id]').children('option:selected').data('categryalias');
    // 	previewurl = site_url +'/'+moduleAlias +'/'+ dataCategoryAlias;
    // }

    var frontURL = '<a href="javascript:void(0);" class="editAlias" title="Edit"></a><a class="without_bg_icon openLink" onClick="generatePreview(\'' + previewurl + '\');" title="Open Link" ><i class="ri-external-link-line" aria-hidden="true"></i></a>';
    $('.alias-group').html('<div class="form-group"><label class="form_title" for="site_name">Url :</label> <a href="javascript:void;" class="alias">' + links + '</a><a href="javascript:void(0);" class="editAlias"> <i class="ri-pencil-line"></i>  ' + frontURL + '  </a></div><div class="editAliasForm"><div class="form-group form-md-line-input form-md-floating-label"><label class="form_title" for="site_name">Alias</label><input placeholder="Alias" class="form-control input-sm edited" name="new-alias" id="new-alias" value="' + updated_alias + '" type="text" maxlength="150"/></div><a class="btn btn-green-drake btn-xs update-alias" href="javascript:void(0);">Update</a>&nbsp;<a class="btn btn-green-drake btn-xs regenerate-alias" href="javascript:void(0);">Regenerate</a>&nbsp<a class="btn btn-outline red btn-xs cancle-alias" href="javascript:void(0);">Cancel</a></div></div>');
});


$(document).on('click', '.update-alias', function() {
    var newAlias = $('#new-alias').val();
    if ($('#new-alias').parent('div').hasClass('has-error')) {
        return false;
    }
    var oldAlias = $('input[name=oldAlias]').val();
    if (newAlias != oldAlias) {
        if (newAlias.length > 0) {
            if ($('select[name=category_id]').length == 0) {
                aliasGenerate(newAlias);
            } else {
                var dataCategoryAlias = $('select[name=category_id]').children('option:selected').data('categryalias');
                aliasGenerate(newAlias, dataCategoryAlias);
            }
        }
    }
    $('.editAliasForm').hide();
});

$(document).on('click', '.editAlias', function() {
    $('.editAliasForm').show();
});

$(document).on('click', '.cancle-alias', function() {
    if ($('#new-alias').parent('div').hasClass('has-error')) {
        $('#new-alias').parent('div').removeClass('has-error');
        $('#new-alias').parent('div').find('span#new-alias-error').html('');
    }
    var oldAlias = $('.aliasField').val();
    $('#new-alias').val(oldAlias);
    $('.editAliasForm').hide();
});


$(document).on('click', '.regenerate-alias', function () {
    if ($('#new-alias').parent('div').hasClass('has-error')) {
        return false;
    }
    stroke = 0;
    if ($('select[name=parent_category_id]').length == 0) {
        $('.hasAlias').trigger('change');
    } else {
        $('select[name=parent_category_id]').trigger('change');
    }

    if ($('#new-alias').parent('div').hasClass('has-error')) {
        $('#new-alias').parent('div').removeClass('has-error');
        $('#new-alias').parent('div').find('span#new-alias-error').html('');
    }

});

//$(document).on('click', '.regenerate-alias', function() {
//
//    if ($('#new-alias').parent('div').hasClass('has-error')) {
//        return false;
//    }
//    if ($('input[name=oldAlias]').val() == undefined || $('input[name=oldAlias]').val() == '') {
//        stroke = 0;
//        if ($('select[name=category_id]').length == 0) {
//            $('.hasAlias').trigger('change');
//        } else {
//            $('select[name=category_id]').trigger('change');
//        }
//    } else {
//        var oldAlias = $('input[name=title]').val();
//        var dataCategoryAlias = $('select[name=category_id]').children('option:selected').data('categryalias');
//        var buidLink = '/' + moduleAlias + '/';
//        if (dataCategoryAlias != '' && $('select[name=category_id]').length > 0) {
//            buidLink += dataCategoryAlias + '/' + oldAlias;
//        } else {
//            buidLink += oldAlias;
//        }
//        var site_url1 = stripTrailingSlash(site_url);
//        var links = site_url1 + buidLink.replace(/\/+/g, "/");
//        $('.alias').html(links);
//        $('.alias').css('text-decoration', 'none');
//        $('.alias').css('cursor', 'text');
//        $('.aliasField').val(oldAlias);
//        $('#new-alias').val(oldAlias);
//        $('.snippet_alias').html(links);
//        $('.seo_editor').css('display', 'block');
//        $('.alias-group').removeClass('hide');
//    }
//
//    if ($('#new-alias').parent('div').hasClass('has-error')) {
//        $('#new-alias').parent('div').removeClass('has-error');
//        $('#new-alias').parent('div').find('span#new-alias-error').html('');
//    }
//    $('.editAliasForm').hide();
//});
// code for alias

/*validation for alias */
jQuery.validator.addMethod("specialCharacterCheck", function(value, element) {
    var re = /[`~!@#$%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = re.test(value);
    if (!isSplChar) {
        return true;
    }
}, "Special character not allowed.");

function displayShortCode(moduleAlias, gnAlias) {
    if (moduleAlias == 'static-block') {
        $("#shortCodeDiv").find('#shortCode').html(gnAlias);
        $("#shortCodeDiv").show();
        $(".alias-group").hide();
    } else {
        $(".alias-group").show();
        $("#shortCodeDiv").hide();
    }
}

function stripTrailingSlash(str) {
    if (str.substr(-1) === '/') {
        return str.substr(0, str.length - 1);
    }
    return str;
}