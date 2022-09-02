/**
 * This method validates team form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function () {
	var handleTeam = function () {
		$("#frmShows").validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			ignore: [],
			rules: {
				title: {
					required: true,
					noSpace: true
				},
				author_by:{
					required: true,
					noSpace: true
				},
				start_date_time: {
					required: true
				},
				end_date_time: {
            daterange: true,
            required: {
                depends: function() {
                    var isChecked = $('#end_date_time').attr('data-exp');
                    if (isChecked == 0) {
                        if ($('input[name=end_date_time]').val().length == 0) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            }
        },
				short_description: {
					required: true,
					noSpace: true
				},
				img_id: {
					required: true,
				},
				varMetaTitle: {
					required: true,
					noSpace: true
				},
				varMetaDescription: {
					required: true,
					noSpace: true
				},
				'new-alias': {
					specialCharacterCheck: true,
				},
			},
			messages: {
				title: Lang.get('validation.required', { attribute: Lang.get('template.title') }),
				author_by:{ required:'Performer name is required.'},
				start_date_time: Lang.get('validation.required', { attribute: Lang.get('template.startDateTime') }),
				 end_date_time:{
                	required:"End date & time is required.",
                	daterange: "End Date Time must be greater than Start Date Time."
                },
				short_description: Lang.get('validation.required', { attribute: Lang.get('template.shortdescription') }),
				img_id: Lang.get('validation.required', { attribute: Lang.get('template.image') }),
				display_order: { required: Lang.get('validation.required', { attribute: Lang.get('template.displayorder') }) },
				varMetaTitle: Lang.get('validation.required', { attribute: Lang.get('template.metatitle') }),
				varMetaKeyword: Lang.get('validation.required', { attribute: Lang.get('template.metakeyword') }),
				varMetaDescription: Lang.get('validation.required', { attribute: Lang.get('template.metadescription') })
			},
			errorPlacement: function (error, element) {
				if (element.parent('.input-group').length) {
					error.insertAfter(element.parent());
				} else if (element.hasClass('select2')) {
					error.insertAfter(element.next('span'));
				} else {
					error.insertAfter(element);
				}
			},
			invalidHandler: function (event, validator) { //display error alert on form submit
				var errors = validator.numberOfInvalids();
				if (errors) {
					$.loader.close(true);
				}
				$('.alert-danger', $('#frmShows')).show();
			},
			highlight: function (element) { // hightlight error inputs
				$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
			},
			unhighlight: function (element) {
				$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
			},
			submitHandler: function (form) {
				$('body').loader(loaderConfig);
				form.submit();
				$("button[type='submit']").attr('disabled','disabled');
				return false;
			}
		});
		$('#frmShows input').on('keypress', function (e) {
			if (e.which == 13) {
				if ($('#frmShows').validate().form()) {
					$('#frmShows').submit(); //form validation success, call ajax form submit
					$("button[type='submit']").attr('disabled','disabled');
				}
				return false;
			}
		});
	}
	return {
		//main function to initiate the module
		init: function () {
			handleTeam();
		}
	};
}();
jQuery(document).ready(function () {
	Validate.init();
	jQuery.validator.addMethod("noSpace", function (value, element) {
		if (value.trim().length <= 0) {
			return false;
		} else {
			return true;
		}
	}, "This field is required");
	var isChecked = $('#end_date_time').attr('data-exp');
	if (isChecked == 1) {
		$('.expdatelabel').removeClass('no_expiry');
		$('.expiry_lbl').text('Set Expiry');
		$(".expirydate").hide();
		$('#end_date_time').attr('disabled', 'disabled');
	} else {
		$('.expdatelabel').addClass('no_expiry');
		$('.expiry_lbl').text('No Expiry');
		$('#end_date_time').prop('disabled', false);
	}
});


jQuery.validator.addMethod("phoneFormat", function (value, element) {
	// allow any non-whitespace characters as the host part
	return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Enter valid phone number format.');

/*jQuery.validator.addMethod("urlFormat", function(url) {
	return /^http(s)?:\/\/(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(url);
},'Enter valid url format');*/

jQuery.validator.addMethod("emailFormat", function (value, element) {
	// allow any non-whitespace characters as the host part
	return this.optional(element) || /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(value);
}, 'Enter valid email format.');

jQuery.validator.addMethod("minStrict", function (value, element) {
	// allow any non-whitespace characters as the host part
	if (value > 0) {
		return true;
	} else {
		return false;
	}
}, 'Display order must be a number higher than zero.');
jQuery.validator.addMethod("daterange", function (value, element) {
	var fromDateTime = $('#start_date_time').val();
	var toDateTime = $("#end_date_time").val();
	var isChecked = $('#end_date_time').attr('data-exp');
	if (isChecked == 0) {
		toDateTime = new Date(toDateTime);
		fromDateTime = new Date(fromDateTime);

		return toDateTime >= fromDateTime && fromDateTime <= toDateTime;
	} else {
		return true;
	}
	// var fromDate=moment(fromDateTime, ["YYYY-M-D h:mm A"]).format("YYYY-M-D HH:mm");
	// var toDate=moment(toDateTime, ["YYYY-M-D h:mm A"]).format("YYYY-M-D HH:mm");
	// return (toDate > fromDate);
}, "End Date Time must be greater than Start Date Time.");
$('input[name=title]').on('change', function () {
	var title = $(this).val();
	var trim_title = title.trim();
	if (trim_title) {
		$(this).val(trim_title);
		return true;
	}
});
$('.fromButton').on('click', function () {
	$('#start_date_time').datetimepicker('show');
});
$('.toButton').on('click', function () {
	$('#end_date_time').datetimepicker('show');
});
$(document).on("change", '#end_date_time', function () {
	$(this).attr('data-newvalue', $(this).val());
});

$('#noexpiry').on('click', function () {
	var isChecked = $('#end_date_time').attr('data-exp');
	if (isChecked == 0) {
		$('.expdatelabel').removeClass('no_expiry');
		$('.expiry_lbl').text('Set Expiry');
		$('#end_date_time').attr('data-exp', '1');
		$('#end_date_time').attr('disabled', 'disabled');
		$(".expirydate").hide();
		$("#end_date_time").val(null);
		$('#end_date_time').val('').datetimepicker("update");
		$('.expirydate').next('span.help-block').html('');
		$('.expirydate').parent('.form-group').removeClass('has-error');
	} else {
		$('.expdatelabel').addClass('no_expiry');
		$('.expiry_lbl').text('No Expiry');
		$('#end_date_time').attr('data-exp', '0');
		$('#end_date_time').prop('disabled', false);
		$(".expirydate").show();
		if ($('#end_date_time').attr('data-newvalue').length > 0) {
			$("#end_date_time").val($('#end_date_time').attr('data-newvalue'));
		} else {
			$("#end_date_time").val(oldVal);
		}
	}
});
/*********** Remove Image code start Here  *************/
$(document).ready(function () {
	if ($("input[name='img_id']").val() == '') {
		$('.removeimg').hide();
		$('.image_thumb .overflow_layer').css('display', 'none');
	} else {
		$('.removeimg').show();
		$('.image_thumb .overflow_layer').css('display', 'block');
	}
	$(document).on('click', '.removeimg', function (e) {
		$("input[name='img_id']").val('');
		$("input[name='image_url']").val('');
		$(".fileinput-new div img").attr("src", site_url + "/resources/images/upload_file.gif");
		if ($("input[name='img_id']").val() == '') {
			$('.removeimg').hide();
			$('.image_thumb .overflow_layer').css('display', 'none');
		} else {
			$('.removeimg').show();
			$('.image_thumb .overflow_layer').css('display', 'block');
		}
	});
});
/************** Remove Images Code end ****************/