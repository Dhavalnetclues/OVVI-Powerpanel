/**
 * This method validates banner form fields
 * since   2017-01-27
 * author  NetQuick
 */
var Validate = function() {
    var handleBanner = function() {
        $("#frmshareoption").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            rules: {
                txtDescription: "required",
                'socialmedia[]': "required",
            },
            messages: {
                // varTitle: {
                //     required: 'Please enter title.'
                // },
                txtDescription: {
                    required: 'Please enter description.'
                },
                "socialmedia[]": {
                    required: 'Please select any social media.'
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("type") == "radio") {
                    error.insertAfter($(element).parents('.mt-radio-inline'));
                }
                if (element.attr("type") == "checkbox") {
                    error.insertAfter($(element).parents('.mt-radio-inline'));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#frmshareoption')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) { // for demo
                // console.log('in', form);
                var postdata = $("#frmshareoption").serialize();
                console.log(postdata);
                // return false;
                $.ajax({
                    type: "POST",
                    url: onePushShare,
                    data: postdata,
                    async: false,
                    success: function(result) {
                        console.log('res', result)
                        toastr.success('Shared on socialmedia', { timeOut: 5000 });
                        $('#confirm_share').modal('hide');
                    }
                });
                return false;
            }
        });

        $('#frmshareoption input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#frmshareoption').validate().form()) {
                    $('#frmshareoption').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    return {
        //main function to initiate the module
        init: function() {
            handleBanner();
        }

    };

}();

$(document).on('click', '.socialShare', function() {
    if (event.target.value === 'facebook') {
        if (event.target.checked === true) {
            console.log($('#frmshareoption input[name=image_url]').val())

            var facebookPreview = "<div id='facebook-preview'><div class='col-12'><p>" + $("#txtDescription").val() + "</p>" +
                (($('#frmshareoption input[name=image_url]').val() !== '' && $('#frmshareoption input[name=image_url]').val() !== null) ? "<div class='fileinput-preview thumbnail' style='width:100%;float:left; height:120px;position: relative;'><img src=" + $('#frmshareoption input[name=image_url]').val() + "></div>" : "") + "</div></div>"
            $("#social_preview").append(facebookPreview);
        } else {
            $("#facebook-preview").remove();
        }

    }

    if (event.target.value === 'twitter') {
        if (event.target.checked === true) {
            var twitterPreview = "<div id='twitter-preview'><div class='col-12'><p>" + $("#txtDescription").val() + "</p>" + (($('#frmshareoption input[name=image_url]').val() !== '' && $('#frmshareoption input[name=image_url]').val() !== null) ? "<div class='fileinput-preview thumbnail' style='width:100%;float:left; height:120px;position: relative;'><img src=" + $('#frmshareoption input[name=image_url]').val() + "></div>" : '') + "</div></div>"
            $("#social_preview").append(twitterPreview);
        } else {
            $("#twitter-preview").remove();
        }
    }

});

jQuery(document).ready(function(event) {
    Validate.init();
});

$(document).on('click', '.share', function() {
    $('#frmshareoption input[name=frontLink]').val($(this).data('link'));
    $('#frmshareoption input[name=socialImage]').val('');
    // if ($('#img1 .thumbnail_container').find('.thumbnail').length) {
    //     $(".thumbnail").remove();
    // }
    //	$('#frmshareoption input[name=frontImg]').val($(this).data('images'));
    
    $.ajax({
        type: "POST",
        url: onePushGetRec,
        data: {
            alias: $(this).data('alias'),
            modal: $(this).data('modal'),
            namespace: $(this).data('namespace'),
            modulehasimage: $('#frmshareoption input[name=modulehasimage]').val(),
            moduleimagefiledname: $('#frmshareoption input[name=moduleimagefieldname]').val()
        },
        dataType: 'JSON',
        async: false,
        success: function(data) {
            $('#confirm_share').modal('show');
            $('#frmshareoption input[name=frontImg]').val(data[0].modulefieldImgId);
            // $('#frmshareoption input[name=varTitle]').val(data[0].varMetaTitle);
            $('#frmshareoption textarea[name=txtDescription]').val(data[0].varTitle);
            // $('#frmshareoption img').prop('src', data[0].imgsrc);
            if ($('#social_preview').find('#facebook-preview').length) {
                $("#facebook-preview").remove();
            }
            if ($('#social_preview').find('#twitter-preview').length) {
                $("#twitter-preview").remove();
            }
            if (data[0].modulefieldImgId != undefined) {
                $("#shareDetailDivClass").removeClass('col-md-8').removeClass('col-md-12').addClass('col-md-8');
                $("#shreimgdiv").show();
            } else {
                $("#shareDetailDivClass").removeClass('col-md-8').removeClass('col-md-12').addClass('col-md-12');
                $("#shreimgdiv").hide();
            }
        }
    });
});