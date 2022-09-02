/**
 * This method validates service form fields
 * since   2016-12-24
 * author  NetQuick
 */
$(document).on('#category_id','change',function() {
    var group = $(this).val();
		getAdminAjax(group);
});


function getAdminAjax(group){
	$.ajax({
			type: "POST",
			url: window.site_url+'powerpanel/workflow/get-admin',
			data: {groupId:group},				
			async: false,
			success: function(data) {
				
			}
		});
}