// code for alias
var editContent='';
var controller='';
$('.alias-group').hide();
/* for edit functionality */
var ex_alias = $('.aliasField').val();
if(ex_alias != '')
{
	var links = site_url+'/'+ex_alias;
	$('.alias-group').show();
	$('.alias').html(links);
	// $('.alias').attr('href',links);
	$('.alias').css('text-decoration','none');
	$('.alias').css('cursor','text');
}
/* for edit functionality */
$(document).on('change','.hasAlias',function(){
	var str = $(this).val();
	controller=$(this).data('url');
	aliasGenerate(str);
});
$('.hasAlias').keypress(function(e) {
	if(e.which == 13) {
		$('input[name=title]').trigger('change');
	}
});
function aliasGenerate(str)
{                               
		controller=$('.hasAlias').data('url');
		var ajaxurl =site_url+'/'+controller+'/aliasGenerate';

		$.ajax({
				url: ajaxurl,
				data: { alias:str },
				type: "POST",         
				dataType: "json",        
				success: function(data) {

						var links =  site_url+'/'+controller+'/'+data.alias;
						editContent=data.alias;
						$('.alias').html(links);
						//$('.alias').attr('href',links);
						$('.alias').css('text-decoration','none');
						$('.alias').css('cursor','text');
						$('.aliasField').val(data.alias);
						$('.alias-group').show();
				},
				error: function() {
						console.log('error!');
				}                                 
		});
}

$('.editAlias').click(function()
{   
	var updated_alias = $('.aliasField').val();
	controller=$(this).data('url');
	var links =  site_url+'/'+controller+'/'+updated_alias;
	$('.alias-group').html('<div class="form-group"><label for="site_name">Url :</label> <a href="javascript:void;" class="alias">'+ links +'</a><a href="javascript:void(0);" class="editAlias"> <i class="ri-pencil-line"></i></a></div><div class="editAliasForm"><div class="form-group form-md-line-input form-md-floating-label"><input placeholder="Alias" class="form-control input-sm edited" name="new-alias" id="new-alias" value="'+ updated_alias +'" type="text" maxlength="150"/><label for="site_name">Alias</label></div><a class="btn btn-primary btn-xs update-alias" href="javascript:void(0);">Update</a>&nbsp;<a class="btn btn-primary btn-xs cancle-alias" href="javascript:void(0);">Cancel</a></div></div>');
});

$(document).on('click','.update-alias',function()
{
	var newAlias = $('#new-alias').val();   
	if(newAlias.length > 0)
	{
		
		aliasGenerate(newAlias);
		$('.editAliasForm').hide(); 

		$('.editAlias').click(function()
		{   
			var updated_alias = $('.aliasField').val();
			controller=$(this).data('url');
			var links =  site_url+'/'+controller+'/'+updated_alias;
			$('.alias-group').html('<div class="form-group"><label for="site_name">Url :</label> <a href="javascript:void;" class="alias">'+ links +'</a><a href="javascript:void(0);" class="editAlias"> <i class="ri-pencil-line"></i></a></div><div class="editAliasForm"><div class="form-group form-md-line-input form-md-floating-label"><input placeholder="Alias" class="form-control input-sm edited" name="new-alias" id="new-alias" value="'+ updated_alias +'" type="text" maxlength="150"/><label for="site_name">Alias</label></div><a class="btn btn-primary btn-xs update-alias" href="javascript:void(0);">Update</a>&nbsp;<a class="btn btn-primary btn-xs cancle-alias" href="javascript:void(0);">Cancel</a></div></div>');
		});

	}
});

$(document).on('click','.cancle-alias',function()
{       

	$('.editAlias').click(function()
	{   
		var updated_alias = $('.aliasField').val();
		controller=$(this).data('url');
		var links =  site_url+'/'+controller+'/'+updated_alias;
		$('.alias-group').html('<div class="form-group"><label for="site_name">Url :</label> <a href="javascript:void;" class="alias">'+ links +'</a><a href="javascript:void(0);" class="editAlias"> <i class="ri-pencil-line"></i></a></div><div class="editAliasForm"><div class="form-group form-md-line-input form-md-floating-label"><input placeholder="Alias" class="form-control input-sm edited" name="new-alias" id="new-alias" value="'+ updated_alias +'" type="text" maxlength="150"/><label for="site_name">Alias</label></div><a class="btn btn-primary btn-xs update-alias" href="javascript:void(0);">Update</a>&nbsp;<a class="btn btn-primary btn-xs cancle-alias" href="javascript:void(0);">Cancel</a></div></div>');
	});

	$('.editAliasForm').hide();
	
});
// code for alias