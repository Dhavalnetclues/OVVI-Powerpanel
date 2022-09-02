/**
Custom module for you to write your own javascript functions
**/
// DELETE MESSAGE
var DELETE_ATLEAST_ONE = 'Please select atleast one record to be delete.';
var DELETE_CONFIRM_MESSAGE = '<div class="form-body"><div class="form-group"><label for="site_name">Type DELETE to confirm</label><br/><input type="text" name="confirm" id="ipConfirm"/><span class="help-block" style="color:red"></span></div></div>';
var Custom = function () {
		// private functions & variables
		var myFunc = function(text) {
			alert(text);
		} 
		// public functions
		return {
				//main function
				init: function () {
						//initialize here something.            
				}, 
				recycleRecords : function(ajaxUrl,tableRef,modalId){
					var matches = [];
					$(".chkDelete:checked").each(function() {
							matches.push(this.value);
					}); 

					jQuery.ajax({
							type: "POST",
							url: ajaxUrl,
							data: {
									"ids": matches,
									"module" : tableRef
							},
							async: false,        
							success: function(result)
							{
								$('#confirm').modal('hide');
								$('#restore-item').modal('hide');               
								var $lmTable = $(".dataTable").dataTable( { bRetrieve : true } );
								$lmTable.fnDraw();
								return false;
							}
					});
			}
		};
}();

jQuery(document).ready(function() {    
	 Custom.init();
		$(document).on('click','.deleteMass', function(e){
				e.preventDefault();
				var tableRef = 'logs';
				var CheckedLength = $("input[type='checkbox'][name='delete']:checked").length;
				if (CheckedLength == 0) {        
						$('#confirm .delMsg').html(DELETE_ATLEAST_ONE);
						$('#delete').hide();
				}
				if (CheckedLength > 0)
				{      
					$('#confirm .delMsg').html(DELETE_CONFIRM_MESSAGE); 
					$('#delete').show();
				}
				$('#confirm').modal({ backdrop: 'static', keyboard: false });
				$(document).on('click', '#delete', function() {           
							if($('#ipConfirm').val().length < 1)
							{
								$('#confirm .help-block').text('Please enter DELETE to confirm');
							}
							else if($('#ipConfirm').val() == 'DELETE')
							{
								Custom.recycleRecords(site_url+'/powerpanel/log/DeleteRecord',tableRef,'#confirm');
							}
							else{
								$('#confirm .help-block').text('Sorry, please enter exctly DELETE to confirm');
							}
					});
		});
});
/***
Usage
***/
//Custom.doSomeStuff();