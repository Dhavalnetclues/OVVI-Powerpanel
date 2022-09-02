function generate_seocontent(formname) {

    $('#auto-generate').loader(loaderConfig);

    var postdata = $("#" + formname).serialize();
    var description = '';

    if ($('.modal-body .ck-content').length > 0) {
    } else if ($('.ck-content').length > 0) {
        var html = editors["txtDescription"].getData();
        var dom = document.createElement("DIV");
        dom.innerHTML = html;
        var plain_text = (dom.textContent || dom.innerText);
        description = plain_text;
    }

    if (description != '' && description.length > 0) {
        description = encodeURIComponent(description);
        description = description.replace(/^(?:&nbsp;|\xa0|<br \/>)$/, " ");
        description = description.replace(/&#39;/g, "'");
        description = description.replace(/&quot;/g, '"');
        description = description.replace(/&nbsp;/g, " ");
        description = description.replace(/&nbsp;/g, " ");
        description = description.replace(/<p><\/p>/g, " ");

    } else {

        if (document.getElementById('varShortDescription') != null) {
            description = document.getElementById('varShortDescription').value;
            description = description.length > 0 ? description : '';
        }
    }

    $.ajax({
        type: 'POST',
        url: site_url + '/powerpanel/generate-seo-content',
        data: postdata + '&ajax=Y&description=' + description,
        async: false,
        success: function(data) {
            if (data.length > 0) {
                var str = data.split('*****');
                if (str[0].length > 0 && str[0] != undefined) {
                    $('#varMetaTitle').val(str[0].replace(/\s+/g, " "));
                }
                if (str[1].length > 0 && str[0] != undefined) {
                    $('#varMetaKeyword').val(str[1].replace(/\s+/g, " "));
                }
                //	if ($('#varMetaDescription').val().length < 1) {
                if (str[2].replace(/\s+/g, " ").length < 1 && str[0] != undefined) {
                    $('#varMetaDescription').val(str[0].replace(/\s+/g, " "));
                } else {
                    $('#varMetaDescription').val(str[2].replace(/\s+/g, " "));
                }
                //}
            }
        },
        complete: function() {
            $.loader.close(true);
        }
    });
}

var EcommerceProductsEdit = function() {
    var initComponents = function() {
        //init maxlength handler
        $('.maxlength-handler').maxlength({
            limitReachedClass: "label label-danger",
            alwaysShow: true,
            threshold: 5,
            twoCharLinebreak: false
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            initComponents();
        }
    };
}();

jQuery(document).ready(function() {
    EcommerceProductsEdit.init();
    //CKEDITOR.instances.txtDescription.fire('blur');	
    try {
        user_action;
    } catch (e) {
        if (e.name == "ReferenceError") {
            $('.seoField').blur(function() {
                generate_seocontent(seoFormId);
            });
            $(".seoField").keypress(function(e) {
                if (e.which == 13) {
                    $('input[name=title]').trigger('blur');
                }
            });
        }
    }
});

$('form').submit(function() {
    if (user_action != undefined && user_action == 'add') {
        if ($('#varMetaTitle').length) {
            if ($('#varMetaTitle').val().length < 1 && $('#varMetaDescription').val().length < 1) {
                generate_seocontent(seoFormId);
            }
        }
    }
});

// $("button[name=saveandexit],button[name=saveandedit]").click(function() {
//     generate_seocontent(seoUrl, seoFormId);
// });