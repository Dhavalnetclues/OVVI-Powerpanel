</div>
<!-- Main Wrapper E -->
<script src="{{ $CDN_PATH.'assets/libraries/jquery-validation/js/jquery.validate.min.js' }}"></script>
<script src="{{ $CDN_PATH.'assets/libraries/jquery-validation/js/additional-methods.min.js' }}"></script>
<script src="{{ $CDN_PATH.'assets/js/passwordprotect.js' }}"></script>

<!-- Java Script S -->
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/placeholder/jquery.placeholder.min.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/browser-upgrade/js/browser-upgrade.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/bootstrap/3.3.7/js/bootstrap.min.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/3.3.7/js/bootstrap-select.min.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/3.3.7/js/bootstrap-select-function.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/OwlCarousel2-2.3.4/js/owl.carousel.min.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/fancybox-master/new/js/jquery.fancybox.min.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/loader/js/loader.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/back-top/js/back-top.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/malihu-custom-scrollbar-plugin-master/js/jquery.mCustomScrollbar.concat.min.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/menu/js/menu_01.js'}}"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/js/custom.js'}}"></script>

<script src="{{ $CDN_PATH.'assets/js/onlinepolling.js' }}"></script>
<script src="{{ $CDN_PATH.'assets/js/emailtofriend.js' }}"></script>
<script src="{{ $CDN_PATH.'assets/js/common_form_validation.js' }}"></script>

<!-- Java Script E -->
<script type="text/javascript">
	$("#subscription_form").validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block error', // default input error message class
		ignore: [],
		rules: {
			email: {
				email:true,
				required: true,
				no_url: true,
				xssProtection: true,
				/*emailFormat: true,*/
			},
		},
		messages: {
			email: {
				required: "Email Field is required",
			}
		},
		errorPlacement: function (error, element) {
			error.insertAfter(element);
		},
		invalidHandler: function (event, validator) { //display error alert on form submit
			$('.alert-danger', $('#subscription_form')).show();
		},
		highlight: function (element) { // hightlight error inputs
			$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
		},
		submitHandler: function (form) {
			form.submit();
			return false;
		},
		submitHandler: function (form) {
			//grecaptcha.execute();
		}
	});
	$('#subscription_form input').keypress(function (e) {
		if (e.which == 13) {
			if ($('#subscription_form').validate().form()) {
				$('#subscription_form').submit(); //form validation success, call ajax form submit
			}
			return false;
		}
	});
	$("#subscription_form").on("submit", function(event) {
		event.preventDefault();
		$('#subscription_form .error').html('');
		$('#subscription_form .success').html('');
		
		if ($("#subscription_form").valid()){
			var frmData = $(this).serialize();
				jQuery.ajax({
					type: "POST",
					url: site_url + '/news-letter',
					data: frmData,
					dataType: 'json',
					async: true,
					//start: SetBackGround(),
					success: function(data) {
						//UnSetBackGround();
						for (var key in data) {
							if (key == 'error') {
								$('#subscription_form .error').html(data[key]);
							} else {
							$('#subscription_form .error').html('');
								$('#subscription_form .success').html('');
								$('#subscription_form .success').append('<label class="success">' + data[key] + '</label>');
								$('#subscription_form input[name=email]').val('');
							}
						}
					}
				});
		}
	});
	$(document).on("change", "#subscription_form input[name=email]", function(){
		if ($(this).val() == ""){
			$('#subscription_form .error').html('');
			$('#subscription_form .success').html('');
		}
	});
	$.validator.addMethod('no_url', function (value, element) {
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
	jQuery.validator.addMethod("xssProtection", function (value, element) {
		// allow any non-whitespace characters as the host part
		return this.optional(element) || /<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/.test(value) == false ? true : false;
	}, 'Enter valid input');

	$.validator.addMethod("quillemptyValidation", function (value, element) {
	    if (value != '' && value =='<p><br></p>') {
	        return false;
	    } else {
	        return true;
	    }
	}, 'This field is required');
        
         $(document).ready(function($)
    {
        // Set initial zoom level
        var zoom_level=100;

        // Click events
        $('#zoom_in').click(function() { zoom_page(10, $(this)) });
        $('#zoom_out').click(function() { zoom_page(-10, $(this)) });
        $('#zoom_reset').click(function() { zoom_page(0, $(this)) });

        // Zoom function
        function zoom_page(step, trigger)
        {
            // Zoom just to steps in or out
            if(zoom_level>=120 && step>0 || zoom_level<=80 && step<0) return;

            // Set / reset zoom
            if(step==0) zoom_level=100;
            else zoom_level=zoom_level+step;

            // Set page zoom via CSS
            $('#wrapper').css({
                transform: 'scale('+(zoom_level/100)+')', // set zoom
                transformOrigin: '50% 0' // set transform scale base
            });

            // Adjust page to zoom width
            if(zoom_level>100) $('#wrapper').css({ width: (zoom_level*1.01)+'%' });
            else $('#wrapper').css({ width: '100%' });

            // Activate / deaktivate trigger (use CSS to make them look different)
            if(zoom_level>=120 || zoom_level<=80) trigger.addClass('disabled');
            else trigger.parents('ul').find('.disabled').removeClass('disabled');
            if(zoom_level!=100) $('#zoom_reset').removeClass('disabled');
            else $('#zoom_reset').addClass('disabled');
        }
    });
</script>
@yield('footer_scripts')
</body>
<!-- Body E -->
</html>