/**
 * This method validates Role form fields
 * since   2016-12-24
 * author  NetQuick
 */

var modalMenuItemId;
var loaderConfig={
	autoCheck: false,
	size: 16,
	bgColor: 'rgba(0, 0, 0, 0.25)',
	bgOpacity: 0.5,
	fontColor: 'rgba(16, 128, 242, 90)',
	title: 'Loading...'
};

 var Validate = function() {
		var handleRole = function() {
				 $("#frmRole").validate({
					errorElement: 'span', //default input error message container
					errorClass: 'help-block', // default input error message class
					ignore:[],
					rules: {
						name: {
							required:true,
							noSpace:true,
              xssProtection: true,
              no_url: true
						},
						display_name: {
							required:true,
							noSpace:true
						},
						permission:"required"
					},
					messages: {
						name:{ requied:"Name is required"},
						display_name: "Display name is required",	
						permission:"Permission is required"
						
					},
					errorPlacement: function (error, element) { if (element.parent('.input-group').length) { error.insertAfter(element.parent()); } else if (element.hasClass('select2')) { error.insertAfter(element.next('span')); } else { error.insertAfter(element); } },
					invalidHandler: function(event, validator) { //display error alert on form submit
								var errors = validator.numberOfInvalids();
								if (errors) {
									$.loader.close(true);
								}   
								$('.alert-danger', $('#frmRole')).show();
						},
					highlight: function(element) { // hightlight error inputs
								$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
						},
					unhighlight: function(element) {
								$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
						},
					submitHandler: function (form) {
						$('body').loader(loaderConfig);
						form.submit();
						return false;
					}
				});
				$('#frmRole input').keypress(function(e) {
						if (e.which == 13) {
								if ($('#frmRole').validate().form()) {
										$('#frmRole').submit(); //form validation success, call ajax form submit
								}
								return false;
						}
				});
		}	 
		return {
				//main function to initiate the module
				init: function() {
						handleRole();
				}
		};
}();
jQuery(document).ready(function() {   	 
	 Validate.init();
	 hideActions();

	 if(!editing){
		/*$('.banners .per_edit input[type=checkbox]').trigger('click');
		$('.banners .per_list input[type=checkbox]').trigger('click');
		$('.pages .per_edit input[type=checkbox]').trigger('click');
		$('.pages .per_list input[type=checkbox]').trigger('click');*/
	}

	 jQuery.validator.addMethod("noSpace", function(value, element){
		if(value.trim().length <= 0){
			return false; 	
		}else{
			return true; 	
		}
	}, "This field is required");	

	$.each($('.grp-sec .module-activation'), function() {
			if ($(this).prop('checked')) {	    	
				$(this).closest('.grp-sec').find('.group-activation').bootstrapSwitch('state', true);
			}
	});

});
jQuery.validator.addMethod("phoneFormat", function(value, element) {
	// allow any non-whitespace characters as the host part
	return this.optional( element ) || /((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}/.test( value );
}, 'Please enter a valid phone number.');
$('input[type=text]').change(function(){
	var input = $(this).val();
	var trim_input = input.trim();
	if(trim_input) {
		$(this).val(trim_input);
		return true;
	}
});

var prevent=false;
var grpPrevent=false;
$('.module-activation').on('switchChange.bootstrapSwitch', function (event, state) {
		$('#frmRole').loader(loaderConfig);
		var switchState=$(this).bootstrapSwitch('state');
		if(switchState) {			
			if(!prevent){
				$(this).parent().parent().parent().parent().parent().find('.right_permis input[type=checkbox]:visible').prop('checked', true);
			}
		} else {
			$(this).parent().parent().parent().parent().parent().find('.right_permis input[type=checkbox]:visible').prop('checked', false);
			prevent=false;
		}
		
		if($(this).parent().parent().parent().parent().parent().parent().parent().find('.module-activation:checked').length > 0) {
			$(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.group-activation').bootstrapSwitch('state', true);
		}else{			
			$(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.group-activation').bootstrapSwitch('state', false);
		}
		grpPrevent=false;

		// if($('.per_add input[type=checkbox]').is(':visible') == false)
		// {
		// 	$('.per_add input[type=checkbox]:checked').removeAttr('checked');
		// }
		setTimeout(function(){ $.loader.close(true); }, 1000);
});



$('.group-activation').on('switchChange.bootstrapSwitch', function (event, state) {
		$('#frmRole').loader(loaderConfig);
		var switchState=$(this).bootstrapSwitch('state');
		if(switchState) {			
			if(grpPrevent){
				$(this).parent().parent().parent().parent().parent().find('.right_permis input[type=checkbox]').prop('checked', true);
				$(this).parent().parent().parent().parent().parent().find('.activation input[type=checkbox]').bootstrapSwitch('state',true);
			}
		} else {
			$(this).parent().parent().parent().parent().parent().find('.right_permis input[type=checkbox]').prop('checked', false);
			$(this).parent().parent().parent().parent().parent().find('.activation input[type=checkbox]').bootstrapSwitch('state',false);
			grpPrevent=true;
		}

		// if($('.per_add input[type=checkbox]').is(':visible') == false)
		// {
		// 	$('.per_add input[type=checkbox]:checked').removeAttr('checked');
		// }

	 setTimeout(function(){ $.loader.close(true); }, 1000);
});


$('.right_permis input[type=checkbox]').on('click', function (event, state) {
		if($(this).parent().parent().children().children('input[type=checkbox]:checked').length < 1) {
			$(this).parent().parent().parent().find('.module-activation').bootstrapSwitch('state', false);
			prevent=false;			
		}else{
			prevent=true;						
			$(this).parent().parent().parent().find('.module-activation').bootstrapSwitch('state', true);
		}

		if($(this).parent().parent().parent().parent().parent().find('.module-activation:checked').length < 1) {
			$(this).parent().parent().parent().parent().parent().parent().find('.group-activation').bootstrapSwitch('state', false);
			
		}else{
									
			$(this).parent().parent().parent().parent().parent().parent().find('.group-activation').bootstrapSwitch('state', true);
		}
		grpPrevent=false;

});

$('#isadmin').on('switchChange.bootstrapSwitch', function (event, state) {
	$('.per_delete input[type=checkbox]:checked').removeAttr('checked');
	$('.per_publish input[type=checkbox]:checked').removeAttr('checked');
	if(!editing && state){
		$('.banners .per_add input[type=checkbox]').trigger('click');
		$('.pages .per_add input[type=checkbox]').trigger('click');	
	}

	if(editing){
		editHandleActions();
	}

	hideActions();
});

function hideActions()
{
	if(!$('#isadmin').bootstrapSwitch('state')){		
		$('.per_delete').hide();
		$('.per_delete input[type=checkbox]').attr('disabled',true);
		$('.per_publish').hide();
		$('.per_publish input[type=checkbox]').attr('disabled',true);
		// $('.per_add').hide();
		// $('.per_add input[type=checkbox]').attr('disabled',true);
		$('.user-management').closest('.grp-sec').hide();
		$('.user-management input[type=checkbox]').prop('checked',false);
		// $('.per_add input[type=checkbox]:checked').parent().show();
		// $('.per_add input[type=checkbox]:checked').removeAttr('disabled');

		
		if(!editing){
			$(".module-activation").bootstrapSwitch('state', false);
			prevent=true;
			
			$('.banners .per_edit input[type=checkbox]').trigger('click');
			$('.banners .per_list input[type=checkbox]').trigger('click');
			
			
			$('.pages .per_edit input[type=checkbox]').trigger('click');
			$('.pages .per_list input[type=checkbox]').trigger('click');

			prevent=false;
		}
	}else{	
		

		$('.per_delete').show();
		$('.per_delete input[type=checkbox]').removeAttr('disabled');
		$('.per_publish').show();
		$('.per_publish input[type=checkbox]').removeAttr('disabled');
		// $('.per_add').show();
		// $('.per_add input[type=checkbox]').removeAttr('disabled',true);		
		$('.user-management').closest('.grp-sec').show();

		if(!editing){
			prevent=false;
			$(".module-activation").bootstrapSwitch('state', true);
			$(".module-activation").trigger('change');
			$('.banners .per_delete input[type=checkbox]').trigger('click');
			$('.banners .per_publish input[type=checkbox]').trigger('click');	

			$('.pages .per_delete input[type=checkbox]').trigger('click');
			$('.pages .per_publish input[type=checkbox]').trigger('click');
		}
	}
}

function editHandleActions(){
	if(!$('#isadmin').bootstrapSwitch('state')){
			
			$(".module-activation").bootstrapSwitch('state', false);	
			prevent=true;
			$(".module-activation").trigger('change');
			prevent=false;
	}else{
			prevent=false;
			$(".module-activation").bootstrapSwitch('state', true);
			$(".module-activation").trigger('change');

			$( ".module-activation" ).each(function( index ) {
				$(this).parent().parent().parent().parent().parent().find('.right_permis input[type=checkbox]').prop('checked', true);  
			});
	}
}