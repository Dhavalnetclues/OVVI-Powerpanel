var DELETE_ATLEAST_ONE = 'Please select atleast one record to be deleted.';
var DELETE_CONFIRM_MESSAGE = 'Caution! The selected records will be deleted. Press DELETE to confirm.';
var URL = '';
var alias = '';
$(document).ready(function() 
{

			$(document).on('click','.delete',function(e)
			{
							e.preventDefault();
							var controller=$(this).data('controller');
							alias=$(this).data('alias');
							 URL =site_url+'/powerpanel/'+controller+'/destroy';
							deleteItemModal();
							$('#confirm .delMsg').text(DELETE_CONFIRM_MESSAGE);
							$('#delete').show();
			});
	

			$(document).on('click','#delete',function() 
			{		
				deleteItem(URL,alias);
			});

			$(document).on('click','#deleteAll',function()
			{
							Delete_Records();
			});

			$(document).on('click','.deleteMass',function(e)
			{					
							e.preventDefault();
							var CheckedLength = $("input[type='checkbox'][class='chkDelete']:checked").length;
							if (CheckedLength == 0) {
											$('#confirmForAll .delMsg').text(DELETE_ATLEAST_ONE);
											$('#deleteAll').hide();
							}
							if (CheckedLength > 0) {
											$('#confirmForAll .delMsg').text(DELETE_CONFIRM_MESSAGE);
											$('#deleteAll').show();
							}
							deleteMultiple();
			});				

				

});

$('.group-checkable').click(function()
{
		if($(this).parent().attr('class')!='checked') {
						$('.chkDelete').each(function() {
										$(this).prop('checked', true);
										$(this).parent().addClass('checked');
						});
		}else{
						$('.chkDelete').each(function() {
										$(this).prop('checked', false);
										$(this).parent().removeClass('checked');
						});
		}
});
	
function deleteItem(ajaxUrl,slug) 
{	

				$.ajax({
						url: ajaxUrl,
						data: {alias:slug},
						type: "POST",
						dataType: "HTML",
						success: function(data) {
										$('#confirm').modal('hide');
										var $lmTable = $(".dataTable").dataTable({
														bRetrieve : true
										});
										$lmTable.fnDraw();
						},
						error: function() 
						{
							console.log('error!');
						},
						async:false
				});
}

function Delete_Records()
{
				var matches = [];
				$(".chkDelete:checked").each(function() {
								matches.push(this.value);
				});
				jQuery.ajax({
								type: "POST",
								url: MODULE_URL,
								data: {
												"ids": matches
								},
								async: false,
								success: function(result) {
												$('#confirmForAll').modal('hide');
												var $lmTable = $(".dataTable").dataTable( {
																bRetrieve : true
												});
												$lmTable.fnDraw();

								}
				});
}

function deleteMultiple() {	
	$('#confirmForAll').modal({
					backdrop: 'static', 
					keyboard: false
	});
}

function deleteItemModal()
{
	$('#confirm').modal({
					backdrop: 'static', 
					keyboard: false
	});
}