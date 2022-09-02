/**
 * This method validates service form fields
 * since   2016-12-24
 * author  NetQuick
 */
 var Validate = function() {
		var handleProduct = function() { 
				 $("#frmProduct").validate({
					errorElement: 'span', //default input error message container
					errorClass: 'help-block', // default input error message class
					rules: {					
						title: {
							required:true,
							noSpace:true
						},
						display_order: {
							required: true,
							minStrict: true,
							number: true,
							noSpace:true
						},
						short_description:{
							required:true,
							noSpace:true
						},
						varMetaTitle: {
							required:true,
							noSpace:true
						},
						varMetaKeyword:{
							required:true,
							noSpace:true
						},
						varMetaDescription:{
							required:true,
							noSpace:true
						},
						'new-alias':{
							specialCharacterCheck:true,
						},
						regular_price: {
							required:true,
							minmumPrices:true,
							checkallzero:true,
							maxlength:6
						},
						sale_price: 
						{
							maxlength:6,
							salepriceCompare:{
										depends: function(){
											var regularPrice = $('#varRegularPrice').val();
											var discountType = $("input[name='discountType']:checked").val();
											if(regularPrice !="" && regularPrice > 0 && discountType==""){
													return true;
											}
										}
							}
						},
						discount_value:{
							required:{
								depends: function(){
										var discountType = $("input[name='discountType']:checked").val();
										if(discountType !=""){
												return true;
										}
									}
							},
							maxlength:6,
							minmumPrices:true,
							checkallzero:true,
							discountPriceCompare:{
										depends: function(){
											var regularPrice = $('#varRegularPrice').val();
											var discountType = $("input[name='discountType']:checked").val();
											if(regularPrice !="" && regularPrice > 0 && discountType=="flat"){
													return true;
											}
										}
							},
							max:{
								param: 100,
								depends: function(){
										var discountType = $("input[name='discountType']:checked").val();
										if(discountType == 'percentage'){
												return '100';	
										}else{
												return false;
										}
								}
							}
						},
					},
					messages: {
						title: Lang.get('validation.required', { attribute: Lang.get('template.title') }),
						short_description: Lang.get('validation.required', { attribute: Lang.get('template.shortdescription') }),
						display_order: { required: Lang.get('validation.required', { attribute: Lang.get('template.displayorder') }) },
						varMetaTitle: Lang.get('validation.required', { attribute: Lang.get('template.metatitle') }),
						varMetaKeyword: Lang.get('validation.required', { attribute: Lang.get('template.metakeyword') }),
						varMetaDescription: Lang.get('validation.required', { attribute: Lang.get('template.metadescription') }),
						regular_price:{
								required:'Price is required.',
								maxlength:'Please enter no more than 6 digits.'
						},
						sale_price:{
								maxlength:'Please enter no more than 6 digits.',
						},
						discount_value:{
								required:'Please enter discount value.',
								maxlength:'Please enter no more than 6 digits.',
						}
					},
					errorPlacement: function (error, element) { if (element.parent('.input-group').length) { error.insertAfter(element.parent()); } else if (element.hasClass('select2')) { error.insertAfter(element.next('span')); } else { error.insertAfter(element); } },
					invalidHandler: function(event, validator) { //display error alert on form submit
								var errors = validator.numberOfInvalids();
						    if (errors) {
						    	$.loader.close(true);
						    }   
								$('.alert-danger', $('#frmProduct')).show();
						},
					highlight: function(element) { // hightlight error inputs
								$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
						},
					unhighlight: function(element) {
								$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
						},
					submitHandler: function (form) {
						calculateSaleprice();
						$('body').loader(loaderConfig);
						form.submit();
						$("button[type='submit']").attr('disabled','disabled');
						return false;
					}
				});
				$('#frmProduct input').on('keypress',function(e) {
						if (e.which == 13) {
								if ($('#frmProduct').validate().form()) {
										$('#frmProduct').submit(); //form validation success, call ajax form submit
										$("button[type='submit']").attr('disabled','disabled');
								}
								return false;
						}
				});
		}	 
		return {
				//main function to initiate the module
				init: function() {
						handleProduct();
				}
		};
}();
jQuery(document).ready(function() {
	 Validate.init();
	 calculateSaleprice();
	 
	 jQuery.validator.addMethod("noSpace", function(value, element){
		if(value.trim().length <= 0){
			return false; 	
		}else{
			return true; 	
		}
	}, "Space is not allowed.");

	 /*code for check input field only number with decimal allowed */
	 $('.amountfield').on('keypress',function (event) {
            return isNumberWithDescimal(event, this);
   });

  if($("#discountValue").val()=="") { $("#discount_div").hide();  } else { $("#discount_div").show(); }
 
 /*code for */
 if($("#discountValue").val()==""){
  $('#varRegularPrice').on('keyup', function(){
    if($('#varRegularPrice').val().length == 0 || $('#varRegularPrice').val() == ''){
    	$("#discount_div").hide();
       //$('.amountfield').val('');
       // $('#radio-neutral').val('');
       // $('#radio-no').val('');
       $('.neutral').attr("checked", "checked");
       $('.yes').prop('checked',false);
       $('.no').prop('checked',false);
       $('input.DisType').attr('disabled', 'disabled');
       // $("#discountValue").attr('disabled', 'disabled');
       
     }else{
     	$('input.DisType').prop('disabled', false);
     	//$("#discount_div").show();
     	//$("#discountValue").prop('disabled', false);

     }
  });
}else{
	$('input.DisType').prop('disabled', false);
}


   $(".chk_discount").on('change',function(){
   		var discountType = $(this).val();
   		if(discountType ==""){
   			//alert();
			$("#discount_div").hide();
			$("#varSalePrice").attr('readonly',true);
   		}else{
   			  $("#discount_div").show();
   			  $("#varSalePrice").attr('readonly',true);		
   		}
   		if($("#sale_price_div").hasClass('has-error')){
   			$("#sale_price_div").removeClass('has-error');
   			$("#sale_price_div").find('.help-block').html('');
   		}	
   		if($("#discount_div").hasClass('has-error')){
   			$("#discount_div").removeClass('has-error');
   			$("#discount_div").find('.help-block').html('');
   		}
   		
   		calculateSaleprice();
   		
   });


   $("#varRegularPrice").on('keyup',function(event){
   			calculateSaleprice();
   }).on('change',function(event){
				calculateSaleprice();
   }).on('blur',function(event){
				calculateSaleprice();
   });

   $("#discountValue").on('keyup',function(event){
   			calculateSaleprice();
   }).on('change',function(event){
				calculateSaleprice();
   }).on('blur',function(event){
				calculateSaleprice();
   });

});

jQuery.validator.addMethod('checkallzero', function (value,element) { 
        var zerosReg = /[1-9]/g;
        if (!zerosReg.test(value)) {
            return false;
        } else {
            return true;
        }
     }, 'Please enter valid input.');

jQuery.validator.addMethod("salepriceCompare", function(value, element) {
		var regularPrice = $('#varRegularPrice').val();
		var salePrice    = $("#varSalePrice").val();
		if(parseFloat(regularPrice) !="" && parseFloat(regularPrice) > 0){
			return parseFloat(salePrice) <= parseFloat(regularPrice);
		}

}, "Please enter the less then value form regular price.");

jQuery.validator.addMethod("discountPriceCompare", function(value, element) {
		var regularPrice = $('#varRegularPrice').val();
		var discountPrice    = $("#discountValue").val();
		var discountType = $("input[name='discountType']:checked").val();
		if(parseFloat(regularPrice) !="" && parseFloat(regularPrice) > 0)
			return parseFloat(discountPrice) <= parseFloat(regularPrice);
		else
			$("#discountValue").val('');
}, "Please enter the less then value form regular price.");

jQuery.validator.addMethod("phoneFormat", function(value, element) {
	// allow any non-whitespace characters as the host part
	return this.optional( element ) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test( value );
}, 'Please enter a valid phone number.');

jQuery.validator.addMethod("minStrict", function(value, element) {
	// allow any non-whitespace characters as the host part
	if(value>0){
		return true;
	}else{
		return false;
	}
}, 'Display order must be a number higher than zero.');
$('input[type=text]').on('change',function(){
	var input = $(this).val();
	var trim_input = input.trim();
	if(trim_input) {
		$(this).val(trim_input);
		return true;
	}
});

jQuery.validator.addMethod("minmumPrices", function(value, element) {
	// allow any non-whitespace characters as the host part
	if (value > 0) {
			return true;
	} else {
			return false;
	}
}, 'Must be a number greater than zero');

 
    

/*********** Remove Image code start Here  *************/
    $(document).ready(function() {
    if($("input[name='img_id']").val() == ''){  
        $('.removeimg').hide();
        $('.image_thumb .overflow_layer').css('display','none');
     }else{
       $('.removeimg').show();
          $('.image_thumb .overflow_layer').css('display','block');
     }

     $(document).on('click', '.removeimg', function(e) 
     {          
	      $("input[name='img_id']").val('');
	      $("input[name='image_url']").val('');
	      $(".fileinput-new div img").attr("src",site_url+ "/resources/images/upload_file.gif");

	      if($("input[name='img_id']").val() == ''){  
	      $('.removeimg').hide();
	              $('.image_thumb .overflow_layer').css('display','none');
    }else{
     $('.removeimg').show();
        $('.image_thumb .overflow_layer').css('display','block');
    }            
    });
});
/************** Remove Images Code end ****************/
function isNumberWithDescimal(evt, element) {

    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function calculateSaleprice(){
		var regularPrice = $("#varRegularPrice").val();
		
		var salePrice = 0;
		var discountType = $("input[name='discountType']:checked").val();
		var discountValue = 0;
		var discount_percentage = 0;
		if(regularPrice != "" && regularPrice > 0)
		{
				regularPrice = parseFloat(regularPrice);

				if(discountType != "")
				{
						discountValue = $("#discountValue").val();
						discountValue = parseFloat(discountValue);
						if(discountType == 'flat'){
								if(discountValue < regularPrice){
										salePrice = regularPrice - discountValue;
								}else{
										salePrice = 0;
								}  		
						}else if( discountType == 'percentage'){
								if(discountValue <= 100 &&  discountValue > 0){
										discount_percentage = (parseFloat(regularPrice) * parseFloat(discountValue)) / 100;
										salePrice = regularPrice - parseFloat(discount_percentage);
								}else{
										salePrice = 0;
								}	
						}
				}
				if(salePrice != "" && discountType!=""){
					salePrice = parseFloat(salePrice);
				}
		}
		
		$("#varSalePrice").val(salePrice);
}    

function KeycheckOnlyPhonenumber(e) {
    var t = 0;
    t = document.all ? 3 : document.getElementById ? 1 : document.layers ? 2 : 0;
    if (document.all) e = window.event;
    var n = "";
    var r = "";
    if (t == 2) {
        if (e.which > 0) n = "(" + String.fromCharCode(e.which) + ")";
        r = e.which
    } else {
        if (t == 3) {
            r = window.event ? event.keyCode : e.which
        } else {
            if (e.charCode > 0) n = "(" + String.fromCharCode(e.charCode) + ")";
            r = e.charCode
        }
    }
    if (r >= 65 && r <= 90 || r >= 97 && r <= 122 || r >= 33 && r <= 39 || r >= 42 && r <= 42 || r >= 44 && r <= 44 || r >= 46 && r <= 47 || r >= 58 && r <= 64 || r >= 91 && r <= 96 || r >= 123 && r <= 126 || r == 32) {
        return false
    }
    return true
}
/*********** Remove Image code start Here  *************/
	$(document).ready(function() {
		if($("input[name='img_id']").val() == ''){  
					$('.removeimg').hide();
					$('.image_thumb .overflow_layer').css('display','none');
			 }else{
				 $('.removeimg').show();
					$('.image_thumb .overflow_layer').css('display','block');
			 }

	$(document).on('click', '.removeimg', function(e) 
		 {    	 	
			$("input[name='img_id']").val('');
			$("input[name='image_url']").val('');
			$(".fileinput-new div img").attr("src",site_url+ "/resources/images/upload_file.gif");

			if($("input[name='img_id']").val() == ''){  
					$('.removeimg').hide();
					$('.image_thumb .overflow_layer').css('display','none');
				}else{
				 $('.removeimg').show();
					$('.image_thumb .overflow_layer').css('display','block');
				}			 
		});

		if ($("input[name='doc_id']").val() == '') {
			$('.document_thumb .overflow_layer').css('display', 'none');
		} else {
			$('.document_thumb .overflow_layer').css('display', 'block');
		}
	
		$(document).on('click', '.removeDocument', function (e) 
		{
			$("input[name='doc_id']").val('');

			$("#document_name").val('');
			$("#document_name").hide();
			$(".document-fileinput img").attr("src", site_url + '/resources/images/upload_file.gif');
			if ($("input[name='doc_id']").val() == '') {
				$('.document_thumb .overflow_layer').css('display', 'none');
			} else {
				$('.document_thumb .overflow_layer').css('display', 'block');
			}
		});

});
/************** Remove Images Code end ****************/
