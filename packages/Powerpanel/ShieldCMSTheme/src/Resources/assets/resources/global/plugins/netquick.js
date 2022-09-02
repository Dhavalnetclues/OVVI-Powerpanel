$(document).ready(function(){


	$(document).on('click','.read_more',function(){

		$.get( site_url+"/powerpanel/plugins/get_module/"+this.id, function( data ) {

		  $( ".module_details" ).html( data );
		    $('#nqpopup').modal('show')
		});

	});

	$(document).on('click','.action_update',function(){

		$.get( site_url+"/powerpanel/plugins/update_module/"+this.id, function( data ) {

				toastr.options = {
		  		"closeButton": true,
		  		"debug": false,
		  		"positionClass": "toast-top-right",
					"onclick": null,
					"showDuration": "1000",
					"hideDuration": "1000",
					"timeOut": "5000",
					"extendedTimeOut": "1000",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				}
				toastr.success(data.msg);

		});
		$(this).replaceWith('Installed');
	});
});
