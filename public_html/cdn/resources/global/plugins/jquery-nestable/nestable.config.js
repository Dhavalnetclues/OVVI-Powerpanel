"use strict";
/*
 * nestable.config - jQuery Plugin to manage menu list
 * Author: NetQuick
 * since : 2016-10-01
 */

var menuUpdated = false;
var Menu = function() {
    // private functions & variables
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var myFunc = function(text) {
            alert(text);
        }
        // public functions
    return {
        //main function
        init: function() {
            //initialize here something.
            $('.menuBody').loader(loaderConfig);
        },
        recycleRecords: function(ajaxUrl, tableRef, modalId) {
            var matches = [];
            $(".chkDelete:checked").each(function() {
                matches.push(this.value);
            });

            jQuery.ajax({
                type: "POST",
                url: ajaxUrl,
                data: {
                    "ids": matches,
                    "module": tableRef
                },
                async: false,
                success: function(result) {
                    $('#confirm').modal('hide');
                    $('#restore-item').modal('hide');
                    var $lmTable = $(".dataTable").dataTable({
                        bRetrieve: true
                    });
                    $lmTable.fnDraw();
                    return false;
                }
            });
        },
        addMenuType: function() {
            $('#newMenuTitle').trigger('change');
            var label = $('#newMenuTitle').val();
            var slug = $('.aliasField').val();
            var ajaxurl = site_url + '/powerpanel/menu/addMenuType';
            $.ajax({
                url: ajaxurl,
                data: {
                    title: label,
                    alias: slug,
                    _token: CSRF_TOKEN
                },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    if (data.length > 0) {
                        var obj = jQuery.parseJSON(data);
                        if (obj.title != undefined) {
                            $('#menuTitleErrT').html(obj.title);
                        } else {
                            $('#menuTitleErrT').html('');
                            $('#new-menu-add input').val(null);
                            $('#new-menu-add').modal('hide');
                        }
                    } else {
                        $('#new-menu-add').modal('hide');
                        $('#menuTitleErrT').html('');
                    }
                    Menu.reload_menu_types(obj.menu_id);
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        reload_menu_types: function(id = null) {
            var ajaxurl = site_url + '/powerpanel/menu/getMenuType';
            $.ajax({
                url: ajaxurl,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    $('#menuPosition').html(data.html);
                    if (id != null) {
                        $('#menuPosition option').each(function() {
                            if ($(this).val() == id) {
                                $(this).attr('selected', true);
                            }
                        });
                        toastr.success('Menu added successfully.', {
                            timeOut: 5000
                        });
                    }
                    $("#menuPosition").trigger('change');
                    var size = $('#menuPosition option').length;
                    $('#menuPosition').attr('size', (size < 2 ? 2 : size));
                },
                complete: function() {
                    $.loader.close(true);
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        reorder: function() {

            var jsonData = $('.dd').nestable('serialize');
            var pos = $('#menuPosition option[class=active]').val();
            var state = $('input[name=active]').bootstrapSwitch('state') == true ? 'Y' : 'N';

            jsonData = JSON.stringify(jsonData);

            var matches = [];
            $('.activeItem:checked').each(function() {
                matches.push($(this).val());
            });

            var notMatches = [];
            $('.activeItem:not(:checked)').each(function() {
                notMatches.push($(this).val());
            });

            var inMobile = [];
            $('.inMobileMenu:checked').each(function() {
                inMobile.push($(this).val());
            });

            var inWeb = [];
            $('.inWebMenu:checked').each(function() {
                inWeb.push($(this).val());
            });

            var notInMobile = [];
            $('.inMobileMenu:not(:checked)').each(function() {
                notInMobile.push($(this).val());
            });

            var notInWeb = [];
            $('.inWebMenu:not(:checked)').each(function() {
                notInWeb.push($(this).val());
            });

            $('.menuBody').loader(loaderConfig);

            var ajaxurl = site_url + '/powerpanel/menu/saveMenu';
            $.ajax({
                url: ajaxurl,
                data: {
                    menuList: jsonData,
                    active: state,
                    activeItem: JSON.stringify(matches),
                    inActiveItem: JSON.stringify(notMatches),
                    inMobile: JSON.stringify(inMobile),
                    inWeb: JSON.stringify(inWeb),
                    notInMobile: JSON.stringify(notInMobile),
                    notInWeb: JSON.stringify(notInWeb),
                    position: pos,
                    _token: CSRF_TOKEN
                },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    Menu.reload_menu();
                },
                complete: function() {
                    $.loader.close(true);
                    toastr.success('Changes saved', {
                        timeOut: 5000
                    });
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        add_menu_item: function() {
            var label = $('#menuTitle').val();
            var link = $('#menuLink').val();
            var pos = $('#menuPosition option:selected').val();
            var state = 'Y'; //$('input[name=active]').bootstrapSwitch('state')==true?'Y':'N';       

            var ajaxurl = site_url + '/powerpanel/menu/addMenuItem';
            $.ajax({
                url: ajaxurl,
                data: {
                    title: label,
                    page_url: link,
                    active: state,
                    position: pos,
                    _token: CSRF_TOKEN
                },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    if (data.length > 0) {
                        var obj = jQuery.parseJSON(data);
                        if (obj.title != 'undefined') {
                            $('#menuTitleErr').html(obj.title);
                        } else {
                            if (obj.titleI != 'undefined') {
                                $('#menuTitle').val(data.titleI)
                            }
                            $('#menuTitleErr').html('');
                        }

                        if (obj.page_url != 'undefined') {
                            $('#menuLinkErr').html(obj.page_url);
                        } else {
                            if (obj.page_urlI != 'undefined') {
                                $('#menuLink').val(data.page_urlI)
                            }
                            $('#menuLinkErr').html('');
                        }
                        $.loader.close(true);
                    } else {
                        Menu.reload_menu();
                        $('#menuTitle').val('');
                        $('#menuLink').val('');
                        $('#menuTitleErr').html('');
                        $('#menuLinkErr').html('');
                        toastr.success('Page added to menu at the end, please arrange / move it as per your requirement.', {
                            timeOut: 20000
                        });
                    }
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        add_menu_items: function(Items) {
            $('.menuBody').loader(loaderConfig);
            var pos = $('#menuPosition option:selected').val();
            var state = 'Y'; //$('input[name=active]').bootstrapSwitch('state')==true?'Y':'N';						
            var ajaxurl = site_url + '/powerpanel/menu/addMenuItems';
            $.ajax({
                url: ajaxurl,
                data: {
                    'items': Items,
                    'active': state,
                    'position': pos,
                    '_token': CSRF_TOKEN
                },
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    $('.frontPage').prop('checked', false);
                    $(".checkbox-list .checked").removeClass('checked');
                    if (data.length > 0) {
                        //toastr.error('Item exists in menu.',{timeOut: 5000});
                        $('#frontPageExists').show();
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                $('.menu_pages_list input[data-title="' + data[key] + '"]').parent().parent().parent().parent().find('label').css('color', 'red');
                                //$('.menu_pages_list input[data-title="Terms & Conditions"]').parent().parent().parent().parent().find('label').contents().last().replaceWith(data[key]+'*');
                            }
                        }
                    } else {
                        $('.menu_pages_list li label').removeAttr('style');
                        $('#frontPageExists').hide();
                        toastr.success('Page added to menu at the end, please arrange / move it as per your requirement.', {
                            timeOut: 20000
                        });
                    }
                    Menu.reload_menu();
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        reload_menu: function() {
            var pos = $('#menuPosition option:selected').val();
            var ajaxurl = site_url + '/powerpanel/menu/reload';
            $.ajax({
                url: ajaxurl,
                data: {
                    _token: CSRF_TOKEN,
                    position: pos
                },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    if (data.length > 0) {

                        $('#nestable_list_1').html('');
                        $('#nestable_list_1').html(data);

                        if ($('#menuActive').val() == 'Y') {
                            $('#active').bootstrapSwitch('state', true);
                        } else {
                            $('#active').bootstrapSwitch('state', false);
                        }

                        var active = $('#active').bootstrapSwitch('state');
                        if (active) {
                            $('#in-active').prop('checked', false);
                            $('#uniform-in-active').closest('div').find('span').removeClass('checked');
                            $('#active').bootstrapSwitch('state', true);
                            $('#uniform-active').closest('div').find('span').addClass('checked');
                        } else {
                            $('#active').bootstrapSwitch('state', false);
                            $('#uniform-active').closest('div').find('span').removeClass('checked');
                            $('#in-active').attr('checked', true);
                            $('#uniform-in-active').closest('div').find('span').addClass('checked');
                        }
                    } else {
                        $('#active').bootstrapSwitch('state', false);
                        $('#nestable_list_1').html('');
                        $('#nestable_list_1').html('No menu items');
                    }
                },
                complete: function() {
                    $.loader.close(true);
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
                    menuUpdated = false;
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        delete_menu_item: function(iId) {
            $('.menuBody').loader(loaderConfig);
            var ajaxurl = site_url + '/powerpanel/menu/deleteMenuItem';
            $.ajax({
                url: ajaxurl,
                data: {
                    id: iId,
                    _token: CSRF_TOKEN
                },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    Menu.reload_menu();
                    $('#confirm').modal('hide');
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        get_menu_item: function(iId) {
            $('.menuBody').loader(loaderConfig);
            var ajaxurl = site_url + '/powerpanel/menu/getMenuItem';
            $.ajax({
                url: ajaxurl,
                data: {
                    id: iId,
                    _token: CSRF_TOKEN
                },
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    $('#menu-item-edit #menuItemId').val(data.id);
                    $('#menu-item-edit #menuTitleEdit').val(data.varTitle);
                    $('#menu-item-edit #menuLinkEdit').val(data.txtPageUrl);
                    if (data.intPageId != null) {
                        $('#menu-item-edit #menuLinkEdit').attr('disabled', 'disabled');
                    } else {
                        $('#menu-item-edit #menuLinkEdit').removeAttr('disabled');
                    }
                },
                complete: function() {
                    $.loader.close(true);
                    $('#menu-item-edit').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        deleteWholeMenu: function() {
            $('.menuBody').loader(loaderConfig);
            var pos = $('#menuPosition option:selected').val();
            var ajaxurl = site_url + '/powerpanel/menu/deleteMenu';
            $.ajax({
                url: ajaxurl,
                data: {
                    position: pos,
                    _token: CSRF_TOKEN
                },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    $('#confirm').modal('hide');
                    Menu.reload_menu();
                    Menu.reload_menu_types();
                    $('#menuPosition option').each(function() {
                        if ($(this).val() == 1) {
                            $(this).attr('selected', true);
                            $('#menuPosition').trigger('change')
                        }
                    });
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        makeMegaMenu: function(id, status) {
            var pos = $('#menuPosition option:selected').val();
            var ajaxurl = site_url + '/powerpanel/menu/megaMenu';
            $.ajax({
                url: ajaxurl,
                data: {
                    id: id,
                    status: status,
                    _token: CSRF_TOKEN
                },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    Menu.reload_menu();
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        inMobile: function(id, status) {
            var pos = $('#menuPosition option:selected').val();
            var ajaxurl = site_url + '/powerpanel/menu/inMobile';
            $.ajax({
                url: ajaxurl,
                data: {
                    id: id,
                    status: status,
                    _token: CSRF_TOKEN
                },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    Menu.reload_menu();
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        inWeb: function(id, status) {
            var pos = $('#menuPosition option:selected').val();
            var ajaxurl = site_url + '/powerpanel/menu/inWeb';
            $.ajax({
                url: ajaxurl,
                data: {
                    id: id,
                    status: status,
                    _token: CSRF_TOKEN
                },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    Menu.reload_menu();
                },
                error: function() {
                    console.log('error!');
                }
            });
        },
        hideCatDelete: function() {
            var pos = $('#menuPosition option:selected').val();
            if (pos == 1) {
                //$('#deleteMenu').hide();
                $('.activation').show(); // $('.activation').hide();
                $('#active').bootstrapSwitch('state', true);
            } else {
                $('#deleteMenu').show();
                $('.activation').show();
            }
        },
        update_menu_item: function() {
            $('body').loader(loaderConfig);
            var iId = $('#menu-item-edit #menuItemId').val();
            var label = $('#menu-item-edit #menuTitleEdit').val();
            var link = $('#menu-item-edit #menuLinkEdit').val();
            var pos = $('#menuPosition option:selected').val();
            var state = $('input[name=active]').bootstrapSwitch('state') == true ? 'Y' : 'N';
            var ajaxurl = site_url + '/powerpanel/menu/updateMenuItem';
            $.ajax({
                url: ajaxurl,
                data: {
                    id: iId,
                    title: label,
                    page_url: link,
                    active: state,
                    position: pos,
                    _token: CSRF_TOKEN
                },
                type: "POST",
                dataType: "HTML",
                success: function(data) {
                    if (data.length > 0) {
                        var obj = jQuery.parseJSON(data);
                        if (obj.title != undefined) {
                            $('#menuTitleErrE').html(obj.title);
                        } else {
                            $('#menuTitleErrE').html('');
                        }

                        if (obj.page_url != undefined) {
                            $('#menuLinkErrE').html(obj.page_url);
                        } else {
                            $('#menuLinkErrE').html('');
                        }
                    } else {
                        Menu.reload_menu();
                        $('#menuTitleErrE').html('');
                        $('#menuLinkErrE').html('');
                        toastr.success('Changes saved', {
                            timeOut: 5000
                        });
                    }
                },
                complete: function() {
                    $.loader.close(true);
                },
                error: function() {
                    console.log('error!');
                }
            });
        }
    };
}();

jQuery(document).ready(function() {
    Menu.init();
    Menu.reload_menu(); //remove this to load bootstrap check boxes
    Menu.hideCatDelete();

    $('#menuPosition').find('option:first').prop('selected', true).addClass('active');

    $('#menuPosition').on('change', function(e) {
        e.preventDefault();
        if (!menuUpdated) {
            $('.menuBody').loader(loaderConfig);
            $('.caption-subject').text($('#menuPosition option:selected').text());
            $('#menuPosition option').removeAttr('class');
            // $('#menuPosition option').removeAttr('selected');
            $('#menuPosition option:selected').addClass('active');
            Menu.reload_menu();
            Menu.hideCatDelete();
        } else {
            if ($('.toast').length <= 0) {
                toastr.error('Please save the menu.', {
                    timeOut: 5000
                });
            }
        }
    });

    /* Uncoment these lines to turn on auto update on drag and drop*/
    // $('.dd').on('change', function() {
    //    //Menu.reorder();
    // });

    $('#add-menu-item').on('click', function() {
        if (!menuUpdated) {
            $('.manualMenu').loader(loaderConfig);
            Menu.add_menu_item();
        } else {
            if ($('.toast').length <= 0) {
                toastr.error('Please save the menu.', {
                    timeOut: 5000
                });
            }
        }
    });

    $(document).on('click', '.megaMenu', function() {
        var status = $(this).is(":checked") ? 'Y' : 'N';
        Menu.makeMegaMenu($(this).val(), status);
    });

    //##Ajax function
    // $(document).on('click','.inMobileMenu', function() {
    // 		var status=$(this).is( ":checked" )?'Y':'N';        
    // 		Menu.inMobile($(this).val(),status);
    // });

    // $(document).on('click','.inWebMenu', function() {
    // 		var status=$(this).is( ":checked" )?'Y':'N';        
    // 		Menu.inWeb($(this).val(),status);
    // });

    $(document).on('click', '.inMobileMenu', function() {
        if ($(this).is(":checked")) {
            $(this).parent().parent().find('.activeItem').prop('checked', true)
        } else if (!$(this).parent().parent().find('.inWebMenu').is(":checked")) {
            $(this).parent().parent().find('.activeItem').prop('checked', false);
        }
    });

    $(document).on('click', '.inWebMenu', function() {
        if ($(this).is(":checked")) {
            $(this).parent().parent().find('.activeItem').prop('checked', true)
        } else if (!$(this).parent().parent().find('.inMobileMenu').is(":checked")) {
            $(this).parent().parent().find('.activeItem').prop('checked', false);
        }
    });

    $(document).on('click', '.activeItem', function() {
        if ($(this).is(":checked")) {
            $(this).parent().parent().find('.inWebMenu').prop('checked', true);
            $(this).parent().parent().find('.inMobileMenu').prop('checked', true);
        } else {
            $(this).parent().parent().find('.inWebMenu').prop('checked', false);
            $(this).parent().parent().find('.inMobileMenu').prop('checked', false);
        }
    });

    //Menu Item Delete Code
    $(document).on('click', '.deleteItem', function(e) {
        modalMenuItemId = $(this).data('id');
        deleteItemModal();
        $('#confirm .delMsg').html(' this menu item');
    });

    function deleteItemModal() {
        $('#confirm').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    $(document).on('click', '#delete', function() {
        Menu.delete_menu_item(modalMenuItemId);
    });
    //Menu Item Delete Code

    $(document).on('click', '.editItem', function() {
        var id = $(this).data('id');
        Menu.get_menu_item(id);
    });

    $(document).on('click', '#saveMenuItem', function() {
        Menu.update_menu_item();
        $('#menu-item-edit').modal('hide');
    });

    $(document).on('click', '#saveMenu', function() {
        Menu.reorder();
        menuUpdated = false;
    });

    $(document).on('click', '#addAllMenuItem', function() {
        var formId = $(this).parents('form').attr('id');
        var formIdwithHas = '#' + formId;
        if (!menuUpdated) {
            var matches = {};
            var title = '';
            var value = '';
            var moduleSlug = '';
            if ($(formIdwithHas + ' .menu_pages_list input:checked').length > 0) {
                $(formIdwithHas + ' .cmsPages').loader(loaderConfig);
                $(formIdwithHas + ' #frontPageSelect').hide();
                $(formIdwithHas + " .frontPage:checked").each(function() {
                    title = $(this).data('title');
                    value = $(this).val();
                    matches[title] = value;
                });
                Menu.add_menu_items(JSON.stringify(matches));
            } else {

                $(formIdwithHas + ' #frontPageSelect').show();
                $(formIdwithHas + ' .menu_pages_list li label').removeAttr('style');
                $(formIdwithHas + ' #frontPageExists').hide();
            }
        } else {
            if ($('.toast').length <= 0) {
                toastr.error('Please save the menu.', {
                    timeOut: 5000
                });
            }
        }
    });

    $(document).on('click', '#deleteMenu', function(e) {
        e.preventDefault();
        $('#confirm .delMsg').text($('#menuPosition option:selected').text());
        $('#confirm').modal({
                backdrop: 'static',
                keyboard: false
            })
            .one('click', '#delete', function() {
                Menu.deleteWholeMenu();
            });
    });

    $(document).on('click', '#saveNewMenu', function(e) {
        Menu.addMenuType();
    });

    $(document).on('change', '.activeItem', function(e) {
        if (this.checked) {
            $($(this).parent().parent().parent().find('ol').find('input')).each(function() {
                $(this).prop('checked', true);
            });
        } else {
            $($(this).parent().parent().parent().find('ol').find('input')).each(function() {
                $(this).removeAttr("checked");
            });
        }
    });

    $(document).ajaxStop(function() {
        if (!menuUpdated) {
            $('input[name=active]').on('switchChange.bootstrapSwitch', function(event, state) {
                menuUpdated = true;
            });
        }
    });
});

$('.dd').on('change', function() {
    menuUpdated = true;
});
$('#new-menu-add').on('hidden.bs.modal', function() {
    $('#menuTitleErrT').html('');
});
// $(window).mousemove(function (e) 
// {
// 		var x = $(window).innerHeight(),
// 				y = $(window).scrollTop();


// 	 if( typeof $('.dd-dragel').offset()!= 'undefined')
// 	 {
// 		if ($('.dd-dragel').offset().top > (x - 70)) 
// 		{
// 				//Down
// 				$('html, body').animate({
// 						scrollTop: 5000 // adjust number of px to scroll down
// 				}, 600);
// 		}

// 		if ($('.dd-dragel').offset().top < (y + 50)) {
// 				//Up
// 				$('html, body').animate({
// 						scrollTop: 0
// 				}, 600);
// 		} else {
// 				$('html, body').animate({

// 				});
// 		}
// 	}

// });