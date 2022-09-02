var Static_block = function() {
	var handleLogin = function() {
		$('.static_block_form').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class			
			ignore: [],
			rules: {
				title: {
					required:true,
					noSpace:true
				},
				varExternalLink:{
					url:true
				}
			},
			messages: {
				title: Lang.get('validation.required', { attribute: Lang.get('template.title') }),
				varExternalLink:{
					url:'Please enter valid URL'
				}
			},
			errorPlacement: function (error, element) { if (element.parent('.input-group').length) { error.insertAfter(element.parent()); } else if (element.hasClass('select2')) { error.insertAfter(element.next('span')); } else { error.insertAfter(element); } },
			invalidHandler: function(event, validator) { //display error alert on form submit
					var errors = validator.numberOfInvalids();
			    if (errors) {
			    	$.loader.close(true);
			    } 
					$('.alert-danger', $('.static_block_form')).show();
				},
			highlight: function(element) { // hightlight error inputs
					$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
				},
			unhighlight: function(element) {
					$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
				},
			submitHandler: function(form) {
				$('body').loader(loaderConfig);
				form.submit(); // form validation success, call ajax form submit
				$("button[type='submit']").attr('disabled','disabled');
			}
		});
		$('.form_control input').on('keypress',function(e) {
			if (e.which == 13) {
				if ($('.form_control').validate().form()) {
					$('.form_control').submit(); //form validation success, call ajax form submit
					$("button[type='submit']").attr('disabled','disabled');
				}
				return false;
			}
		});
	}
	return {
		//main function to initiate the module
		init: function() {
			handleLogin();
		}
	};
}();
jQuery(document).ready(function() {
	Static_block.init();
	jQuery.validator.addMethod("noSpace", function(value, element){
		if(value.trim().length <= 0){
			return false; 	
		}else{
			return true; 	
		}
	}, "This field is required");
});
$('input[name=title]').on('change',function(){
	var title = $(this).val();
  var trim_title = title.trim();
  if(trim_title) {
  	$(this).val(trim_title);
  	return true;
 	}
});


/*********** Remove Image code start Here  *************/
$(document).ready(function() 
{
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
		$(".fileinput-new div img").attr("src", site_url+ '/resources/images/upload_file.gif');

		if($("input[name='img_id']").val() == '') {  
				$('.removeimg').hide();
				$('.image_thumb .overflow_layer').css('display','none');
			}else{
				$('.removeimg').show();
				 $('.image_thumb .overflow_layer').css('display','block');
			}			 
	});
});
/************** Remove Images Code end ****************/

/*********** Remove Video code start Here  *************/
$(document).ready(function() 
{
	 	if($("input[name='video_id']").val() == ''){  
				$('.removeVideo').hide();
				$('.video_thumb .overflow_layer').css('display','none');
		 }else{
			 $('.removeVideo').show();
				 $('.video_thumb .overflow_layer').css('display','block');
		 }

	 $(document).on('click', '.removeVideo', function(e) 
	 {    	 	
		 $("input[name='video_id']").val('');
		 $("#video_name").val('');

		 $(".video-fileinput div img").attr("src", site_url+ '/resources/images/video_upload_file.gif');
		if($("input[name='video_id']").val() == '') {  
				$('.removeVideo').hide();
				$('.video_thumb .overflow_layer').css('display','none');
			}else{
				$('.removeVideo').show();
				 $('.video_thumb .overflow_layer').css('display','block');
			}			 
	});
});
/************** Remove Video code end ****************/
