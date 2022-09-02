/**
 * This method validates service form fields
 * since   2016-12-24
 * author  NetQuick
 */
var Validate = function () {
	var handleProject = function () {
			$("#frmProject").validate({
					errorElement: 'span', //default input error message container
					errorClass: 'help-block', // default input error message class
					rules: {
							title: {
									required: true,
									noSpace: true
							},
							"category_id[]": {
									required: true,
							},
							status: {
									required: true,
							},
							display_order: {
									required: true,
									minStrict: true,
									number: true,
									noSpace: true
							},
							/*short_description: {
									required: true,
									noSpace: true
							},*/
							varMetaTitle: {
									required: true,
									noSpace: true
							},
							varMetaKeyword: {
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
							/*team: {
									required: true
							},*/
							sale_price: {
									//required: true,
									number: true,
									maxlength: 12,
									minmumPrices:true,
									checkallzero:true
							},
							/*latitude: {
									required: true
							},
							longitude: {
									required: true
							},*/
					},
					messages: {
							title: Lang.get('validation.required', {attribute: Lang.get('template.title')}),
							"category_id[]": {
									required: 'Please select category.'
							},
							status: {
									required: 'Please select status.'
							},
							//short_description: Lang.get('validation.required', {attribute: Lang.get('template.shortdescription')}),
							display_order: {required: Lang.get('validation.required', {attribute: Lang.get('template.displayorder')})},
							varMetaTitle: Lang.get('validation.required', {attribute: Lang.get('template.metatitle')}),
							varMetaKeyword: Lang.get('validation.required', {attribute: Lang.get('template.metakeyword')}),
							varMetaDescription: Lang.get('validation.required', {attribute: Lang.get('template.metadescription')}),
							/*team: {
									required: 'Please select user'
							},*/
							sale_price: {
									//required: 'Please enter price',
									maxlength: 'Please enter no more than 10 digits.',
							},
							latitude: {
									required: 'Please enter latitude.'
							},
							longitude: {
									required: 'Please enter longitude.'
							},
							/*discount_value: {
									required: 'Please enter discount value',
									maxlength: 'Please enter no more than 10 digits.',
							}*/
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
							$('.alert-danger', $('#frmProject')).show();
					},
					highlight: function (element) { // hightlight error inputs
							$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
					},
					unhighlight: function (element) {
							$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
					},
					submitHandler: function (form) {
							//calculateSaleprice();
							$('body').loader(loaderConfig);
							form.submit();
							$("button[type='submit']").attr('disabled','disabled');
							return false;
					}
			});
			$('#frmProject input').on('keypress',function (e) {
					if (e.which == 13) {
							if ($('#frmProject').validate().form()) {
									$('#frmProject').submit(); //form validation success, call ajax form submit
									$("button[type='submit']").attr('disabled','disabled');
							}
							return false;
					}
			});
	}
	return {
			//main function to initiate the module
			init: function () {
					handleProject();
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
	}, "Space is not allowed.");

	/*code for check input field only number with decimal allowed */
	$('.amountfield').on('keypress',function (event) {
			return isNumberWithDescimal(event, this);
	});
 jQuery.validator.addMethod('checkallzero', function (value,element) { 
	 if (value != '') {
			var zerosReg = /[1-9]/g;
			if (!zerosReg.test(value)) {
					return false;
			} else {
					return true;
			}
		}else{
			return true;
		}  
	 }, 'Please enter valid number.');

	$('select[name="category"]').on('change',function() {
			var selVal = $(this).val();
			if(selVal == 1) {
					$('#projectbedshtml, #projectbathshtml').removeClass('hide');
					$('#projectwidthhtml, #projectdepthhtml').addClass('hide');
			} else if(selVal == 2) {
					$('#projectbathshtml').removeClass('hide');
					$('#projectbedshtml, #projectwidthhtml, #projectdepthhtml').addClass('hide');
			} else if(selVal == 3){
					$('#projectwidthhtml, #projectdepthhtml').removeClass('hide');
					$('#projectbedshtml, #projectbathshtml').addClass('hide');
			} else {
					$('#projectbedshtml, #projectbathshtml').removeClass('hide');
					$('#projectwidthhtml, #projectdepthhtml').addClass('hide');
			}
	});
});

jQuery.validator.addMethod("salepriceCompare", function (value, element) {
	var regularPrice = $('#varRegularPrice').val();
	var salePrice = $("#varSalePrice").val();
	if (parseFloat(regularPrice) != "" && parseFloat(regularPrice) > 0) {
			return parseFloat(salePrice) <= parseFloat(regularPrice);
	}

}, "Please enter the less then value form regular price.");

jQuery.validator.addMethod("phoneFormat", function (value, element) {
	// allow any non-whitespace characters as the host part
	return this.optional(element) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test(value);
}, 'Please enter a valid phone number.');

jQuery.validator.addMethod("minStrict", function (value, element) {
	// allow any non-whitespace characters as the host part
	if (value > 0) {
			return true;
	} else {
			return false;
	}
}, 'Display order must be a number higher than zero.');

jQuery.validator.addMethod("minmumPrices", function(value, element) {
// allow any non-whitespace characters as the host part
if (value > 0) {
		return true;
} else {
		return false;
}
}, 'Must be a number greater than zero');



$('input[type=text]').on('change',function () {
	var input = $(this).val();
	var trim_input = input.trim();
	if (trim_input) {
			$(this).val(trim_input);
			return true;
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

	$(document).on('click', '.removeimg', function (e)
	{
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

	$('#category_id').select2({
		placeholder: "Select category",
		width: '100%'
	});
});
/************** Remove Images Code end ****************/
function isNumberWithDescimal(evt, element) {

	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (
					(charCode != 46 || $(element).val().indexOf('.') != -1) && // “.” CHECK DOT, AND ONLY ONE.
					(charCode < 48 || charCode > 57) && charCode != 8) {
							return false;
					}
	return true;
}