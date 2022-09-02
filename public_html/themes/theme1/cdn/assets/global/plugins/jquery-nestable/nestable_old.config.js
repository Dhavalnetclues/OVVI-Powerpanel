		$('#menuPosition').on('change', function() {

		    $('.caption-subject').text($('#menuPosition option:selected').text());

		    reload_menu();

		});



		/* Uncoment these lines to turn on auto update on drag and drop*/

		// $('.dd').on('change', function() {

		// 		//reorder();

		// });		



		$('#add-menu-item').on('click', function() {

		    add_menu_item();

		});



		$(document).on('click', '.deleteItem', function() {

		    var id = $(this).data('id');

		    delete_menu_item(id);

		});



		$(document).on('click', '.editItem', function() {

		    var id = $(this).data('id');

		    get_menu_item(id);

		});



		$(document).on('click', '#saveMenuItem', function() {

		    update_menu_item();

		});



		$(document).on('click', '#saveMenu', function() {

		    reorder();

		    toastr.info('changes saved!');

		});



		$(document).on('click', '#addAllMenuItem', function() {

		    var matches = {};

		    var title = '';

		    var value = '';

		    $(".frontPage:checked").each(function() {

		        title = $(this).data('title');

		        value = $(this).val();

		        matches[title] = value;

		    });



		    add_menu_items(JSON.stringify(matches));

		});



		$(document).on('click', '#deleteMenu', function(e) {

		    e.preventDefault();

		    $('#confirm .delMsg').text($('#menuPosition option:selected').text());

		    $('#confirm').modal({ backdrop: 'static', keyboard: false })

		    .one('click', '#delete', function() {

		        deleteWholeMenu();

		    });

		});



		$(document).on('click', '#saveNewMenu', function(e) {

		    addMenuType();

		});



		function addMenuType()

		{

		    $('#newMenuTitle').trigger('change');

		    var label = $('#newMenuTitle').val();

		    var slug = $('.aliasField').val();

		    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		    var ajaxurl = rootUrl + '/powerpanel/menu/addMenuType';

		    $.ajax({

		        url: ajaxurl,

		        data: { title: label, alias: slug, _token: CSRF_TOKEN },

		        type: "POST",

		        dataType: "HTML",

		        success: function(data) {

		            reload_menu_types();

		        },

		        error: function() {

		            console.log('error!');

		        }

		    });

		}



		function reload_menu_types() {

		    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		    var ajaxurl = rootUrl + '/powerpanel/menu/getMenuType';

		    $.ajax({

		        url: ajaxurl,

		        type: "POST",

		        dataType: "JSON",

		        success: function(data) {

		            $('#menuPosition').html(data.html);

		        },

		        error: function() {

		            console.log('error!');

		        }

		    });

		}



		function reorder()

		{

		    var jsonData = $('.dd').nestable('serialize');

		    var pos = $('#menuPosition').val();

		    var state = $('[name=active]:checked').val();

		    jsonData = JSON.stringify(jsonData);

		    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		    var ajaxurl = rootUrl + '/powerpanel/menu/saveMenu';

		    $.ajax({

		        url: ajaxurl,

		        data: { menuList: jsonData, active: state, position: pos, _token: CSRF_TOKEN },

		        type: "POST",

		        dataType: "HTML",

		        success: function(data) {

		            reload_menu();

		        },

		        error: function() {

		            console.log('error!');

		        }

		    });

		}



		function add_menu_item()

		{

		    var label = $('#menuTitle').val();

		    var link = $('#menuLink').val();

		    var pos = $('#menuPosition').val();

		    var state = 'Y'; //$('[name=active]:checked').val();				

		    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		    var ajaxurl = rootUrl + '/powerpanel/menu/addMenuItem';

		    $.ajax({

		        url: ajaxurl,

		        data: { title: label, page_url: link, active: state, position: pos, _token: CSRF_TOKEN },

		        type: "POST",

		        dataType: "HTML",

		        success: function(data) {

		            reload_menu();

		        },

		        error: function() {

		            console.log('error!');

		        }

		    });

		}



		function add_menu_items(Items)

		{



		    var pos = $('#menuPosition').val();

		    var state = $('[name=active]:checked').val();

		    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		    var ajaxurl = rootUrl + '/powerpanel/menu/addMenuItems';

		    $.ajax({

		        url: ajaxurl,

		        data: { 'items': Items, 'active': state, 'position': pos, '_token': CSRF_TOKEN },

		        type: "POST",

		        dataType: "HTML",

		        success: function(data) {

		            reload_menu();

		            $('.frontPage').removeAttr('checked');

		            $(".checkbox-list .checked").removeClass('checked');

		        },

		        error: function() {

		            console.log('error!');

		        }

		    });

		}



		function reload_menu()

		{

		    var pos = $('#menuPosition').val();

		    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		    var ajaxurl = rootUrl + '/powerpanel/menu/reload';

		    $.ajax({

		        url: ajaxurl,

		        data: { _token: CSRF_TOKEN, position: pos },

		        type: "POST",

		        dataType: "HTML",

		        success: function(data) {

		            $('#menuTitle').val('');

		            $('#menuLink').val('');



		            $('#nestable_list_1').html('');

		            $('#nestable_list_1').html(data);

		            var active = $('#menuActive').val();

		            if (active == 'Y')

		            {

		                $('#in-active').removeAttr('checked');

		                $('#uniform-in-active').closest('div').find('span').removeClass('checked');

		                $('#active').attr('checked', true);

		                $('#uniform-active').closest('div').find('span').addClass('checked');

		            } else

		            {

		                $('#active').removeAttr('checked');

		                $('#uniform-active').closest('div').find('span').removeClass('checked');

		                $('#in-active').attr('checked', true);

		                $('#uniform-in-active').closest('div').find('span').addClass('checked');

		            }

		        },

		        error: function() {

		            console.log('error!');

		        }

		    });

		}



		function delete_menu_item(iId)

		{

		    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		    var ajaxurl = rootUrl + '/powerpanel/menu/deleteMenuItem';

		    $.ajax({

		        url: ajaxurl,

		        data: { id: iId, _token: CSRF_TOKEN },

		        type: "POST",

		        dataType: "HTML",

		        success: function(data) {

		            reload_menu();

		        },

		        error: function() {

		            console.log('error!');

		        }

		    });

		}



		function get_menu_item(iId)

		{

		    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		    var ajaxurl = rootUrl + '/powerpanel/menu/getMenuItem';

		    $.ajax({

		        url: ajaxurl,

		        data: { id: iId, _token: CSRF_TOKEN },

		        type: "POST",

		        dataType: "JSON",

		        success: function(data) {

		            $('#menu-item-edit #menuItemId').val(data[0].id);

		            $('#menu-item-edit #menuTitleEdit').val(data[0].title);

		            $('#menu-item-edit #menuLinkEdit').val(data[0].page_url);

		        },

		        error: function() {

		            console.log('error!');

		        }

		    });

		}



		function deleteWholeMenu()

		{

		    var pos = $('#menuPosition').val();

		    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		    var ajaxurl = rootUrl + '/powerpanel/menu/deleteMenu';

		    $.ajax({

		        url: ajaxurl,

		        data: { position: pos, _token: CSRF_TOKEN },

		        type: "POST",

		        dataType: "HTML",

		        success: function(data) {

		            $('#confirm').modal('hide');

		            reload_menu();

		        },

		        error: function() {

		            console.log('error!');

		        }

		    });

		}



		function update_menu_item()

		{

		    var iId = $('#menu-item-edit #menuItemId').val();

		    var label = $('#menu-item-edit #menuTitleEdit').val();

		    var link = $('#menu-item-edit #menuLinkEdit').val();

		    var pos = $('#menuPosition').val();

		    var state = $('[name=active]:checked').val();



		    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		    var ajaxurl = rootUrl + '/powerpanel/menu/updateMenuItem';

		    $.ajax({

		        url: ajaxurl,

		        data: { id: iId, title: label, page_url: link, active: state, position: pos, _token: CSRF_TOKEN },

		        type: "POST",

		        dataType: "HTML",

		        success: function(data) {

		            reload_menu();

		        },

		        error: function() {

		            console.log('error!');

		        }

		    });

		}