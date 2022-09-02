var ValidateSearch = function() {

    var handleGlobalSearch = function() {

        $("#frmFrontSearch").validate({

            errorElement: 'span', //default input error message container

            errorClass: 'help-block', // default input error message class

            ignore: [],

            rules: {

                frontSearch: {

                    required: true,

                    xssProtection: true,

                    no_url: true

                }

            },

            messages: {

                frontSearch: {

                    required: "Search Term is required",

                    xssProtection: "Please enter valid input",

                    no_url: "Url not allowed"

                },

            },

            errorPlacement: function(error, element) {

                if (element.attr('name') == 'g-recaptcha-response') {

                    error.insertAfter(element.parent().parent());

                } else {

                    error.insertAfter(element);

                }

            },

            invalidHandler: function(event, validator) { //display error alert on form submit   

                $('.alert-danger', $('#frmFrontSearch')).show();

            },

            highlight: function(element) { // hightlight error inputs

                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group

            },

            unhighlight: function(element) {

                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group

            },

            submitHandler: function(form) {

                SetBackGround();

                form.submit();

            }

        });

        $('#frmFrontSearch input').keypress(function(e) {

            if (e.which == 13) {

                if ($('#frmFrontSearch').validate().form()) {

                    SetBackGround();

                    $("#frmFrontSearch").submit();

                }

                return false;

            }

        });

    }

    return {

        //main function to initiate the module

        init: function() {

            handleGlobalSearch();

        }

    };

}();





//Header Search===================================================

/*$( "#frmFrontSearch,#frmBannerFrontSearch,#frmFrontSearchMob" ).submit(function( event ) {

	SetBackGround();

});*/

var initialSearchWebVal = $('#frontSearchHeaderWeb').val();

$('#frontSearchHeaderWeb').keyup(function(e) {

    var currentTerm = $(this).val();

    var len = currentTerm.length;

    var resultId = $('#frontSearchHeaderWebRes');

    if (currentTerm != initialSearchWebVal) {

        $.ajax({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },

            async: true,

            url: site_url + '/search/auto-complete',

            type: 'POST',

            dataType: 'HTML',

            data: {

                term: currentTerm,

                '_token': $('meta[name="csrf-token"]').attr('content')

            },

            beforeSend: function() {

                resultId.html('<div class="autocomplete_search_loader_inner text-center"><img src="' + site_url + '/cdn/assets/images/loader-search.svg" ></div>');

            },

            success: function(data) {

                resultId.find('.autocomplete_search_loader_inner').remove();

                resultId.html(data);

            },

            complete: function() {

                initialSearchWebVal = currentTerm;

                /*$(".srchlist_result").mCustomScrollbar({

                				theme:"minimal-dark",                   

                		}); */

            }

        });

    }

    if (len == 0) {

        resultId.html(null);

        initialSearchWebVal = '';

    }

});

//./Header Search=================================================

//Header Mobile Search===================================================

var initialSearchMobVal = $('#frontSearchHeaderMob').val();

$(document).on('keyup', '#frontSearchHeaderMob', function() {

    var currentTerm = $(this).val();

    var len = currentTerm.length;

    var resultId = $('#frontSearchHeaderMobRes');

    if (currentTerm != initialSearchMobVal) {

        $.ajax({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },

            async: true,

            url: site_url + '/search/auto-complete',

            type: 'POST',

            dataType: 'HTML',

            data: {

                term: currentTerm,

                '_token': $('meta[name="csrf-token"]').attr('content')

            },

            beforeSend: function() {

                resultId.html('<div class="autocomplete_search_loader_inner text-center"><img src="' + site_url + '/cdn/assets/images/loader-search.svg" ></div>');

            },

            success: function(data) {

                resultId.find('.autocomplete_search_loader_inner').remove();

                resultId.html(data);

            },

            complete: function() {

                initialSearchMobVal = currentTerm;

                /* $(".srchlist_result").mCustomScrollbar({

										theme:"minimal-dark",                   

								});*/

            }

        });

    }

    if (len == 0) {

        resultId.html(null);

        initialSearchMobVal = '';

    }

});

//./Header Mobile Search=================================================

//Header Mobile Search===================================================

var initialSearchBannerVal = $('#frontSearchBanner').val();

$('#frontSearchBanner').keyup(function(e) {

    var currentTerm = $(this).val();

    var len = currentTerm.length;

    var resultId = $('#frontSearchBannerRes');

    if (currentTerm != initialSearchBannerVal) {

        $.ajax({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },

            async: true,

            url: site_url + '/search/auto-complete',

            type: 'POST',

            dataType: 'HTML',

            data: {

                term: currentTerm,

                '_token': $('meta[name="csrf-token"]').attr('content')

            },

            beforeSend: function() {

                resultId.html('<div class="autocomplete_search_loader_inner text-center"><img src="' + site_url + '/cdn/assets/images/loader-search.svg" ></div>');

            },

            success: function(data) {

                resultId.find('.autocomplete_search_loader_inner').remove();

                resultId.html(data);

            },

            complete: function() {

                initialSearchBannerVal = currentTerm;

                /*$(".srchlist_result").mCustomScrollbar({

										theme:"minimal-dark",                   

								}); 

*/
            }

        });

    }

    if (len == 0) {

        resultId.html(null);

        initialSearchBannerVal = '';

    }

});

//./Header Mobile Search=================================================

$(document).on('click', '#frontSearchHeaderWebRes li', function() {

    /*$('#frontSearchHeaderWeb').val($(this).text());

    setTimeout(function() {

    		if ($('#frontSearchHeaderWeb').val().length > 0) {

    				$('#frmFrontSearch').trigger('submit');

    		}

    }, 500);*/

});

$(document).on('click', '#frontSearchHeaderMobRes li', function() {

    /*$('#frontSearchHeaderMob').val($(this).text());

    setTimeout(function() {

    		if ($('#frontSearchHeaderMob').val().length > 0) {

    				$('#frmFrontSearchMob').trigger('submit');

    		}

    }, 500);*/

});

$(document).on('click', '#frontSearchBannerRes li', function() {

    /*$('#frontSearchBanner').val($(this).text());

    setTimeout(function() {

    		if ($('#frontSearchBanner').val().length > 0) {

    				$('#frmBannerFrontSearch').trigger('submit');

    		}

    }, 500);*/

});



$(document).on("click", "#docsearchfoundscroll", function() {

    var docsectionStartPosition = $("#docsearchlist").offset().top - $("header").height() - 100;

    $('html, body').animate({

        scrollTop: docsectionStartPosition

    }, 'slow');

});



jQuery.validator.addMethod("xssProtection", function(value, element) {

    // allow any non-whitespace characters as the host part

    return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;

}, 'Enter valid input');



$.validator.addMethod('no_url', function(value, element) {

    var re = /^[a-zA-Z0-9\-\.\:\\]+\.(com|org|net|mil|edu|COM|ORG|NET|MIL|EDU)$/;

    var re1 = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

    var trimmed = $.trim(value);

    if (trimmed == '') {

        return true;

    }

    if (trimmed.match(re) == null && re1.test(trimmed) == false) {

        return true;

    }

}, "URL doesn't allowed");



ValidateSearch.init();