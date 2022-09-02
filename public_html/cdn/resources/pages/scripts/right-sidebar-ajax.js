var loaderConfig = {
    autoCheck: false,
    size: 16,
    bgColor: 'rgba(255, 255, 255, 0.75)',
    bgOpacity: 1,
    fontColor: 'rgba(16, 128, 242, 90)',
    title: 'Loading...',
    css: {
        'top': 0,
        'bottom': 0,
        'position': 'fixed'
    },
    onShow: function() {
        $('.loading_wrp').css('z-index', 1045);
    }
};
$(document).ready(function() {
	setTimeout(function(){ 
     getRsideBar();
  }, 3000);
    //getRsideBar();
    //Dashboard
    $("#dashboard-sidebar").click(function() {
        $('#sidebar-html').html('');
        $('#offcanvasRightLabel').text('Dashboard');
        if ($('body.fixsidebar-open').length == 0) {
            $('body').addClass('fixsidebar-open');
            new bootstrap.Offcanvas('#RightSidebar').show();
        }

        $.ajax({
            url: site_url + '/powerpanel/dashboard/widget-settings',
            type: "GET",
            beforeSend: function(xhr) {
                $('#RightSidebar').loader(loaderConfig);
                xhr.setRequestHeader('X-CSRF-Token', $('input[name="_token"]').val());
            },
            success: function(data) {
                $('.first-menu .links').removeClass('active');

                data = JSON.parse(data)
                var html = '<div class="cm-checkbox-group">';
                $.each(data, function(key, value) {
                    var settingChecked = '';
                    if (value.widget_display == "Y") {
                        settingChecked = 'checked="checked"';
                    }
                    if (value.widget_id != 'widget_avl_workflow' && value.widget_id != 'widget_pending_workflow') {
                        let icon = '';
                        switch (value.widget_id) {
                            case 'widget_download':
                                icon = 'ri-dashboard-2-line';
                                break;
                            case 'widget_leadstatistics':
                                icon = 'ri-line-chart-line';
                                break;
                            case 'widget_liveusercountry':
                                icon = 'ri-global-line';
                                break;
                            case 'widget_conatctleads':
                                icon = 'ri-contacts-book-line';
                                break;
                            case 'widget_inapporval':
                                icon = 'ri-check-double-line';
                                break;
                            case 'widget_formbuilderleads':
                                icon = 'ri-file-text-line';
                                break;
                            default:
                                icon = '';
                        }

                        html += '<div class="checkbox">';
                        html += '<label class="checkbox-wrapper">';
                        //html += '<input type="checkbox" class="" checked />';
                        html += '<input type="checkbox" class="checkbox-input dashboard_checkbox" value="' + key + '" name="' + value.widget_id + '" id="' + value.widget_id + '" ' + settingChecked + '>';
                        html += '<span class="checkbox-tile">';
                        html += '<span class="checkbox-icon">';
                        html += '<i class="' + icon + '"></i>';
                        html += '</span>';
                        html += '<span class="checkbox-label">' + value.widget_name + '</span>';
                        html += '</span>';
                        html += '</label>';
                        html += '</div>';

                    }
                });
                html += '</div>';
                //html += '<div class="cm-checkbox-group"><div class="checkbox"><label class="checkbox-wrapper"><input type="checkbox" class="checkbox-input" checked /><span class="checkbox-tile"><span class="checkbox-icon"><i class="ri-dashboard-2-line"></i></span><span class="checkbox-label">Document Views & Downloads</span></span></label></div><div class="checkbox"><label class="checkbox-wrapper"><input type="checkbox" class="checkbox-input" /><span class="checkbox-tile"><span class="checkbox-icon"><i class="ri-line-chart-line"></i></span><span class="checkbox-label">Leads Statistics</span></span></label></div><div class="checkbox"><label class="checkbox-wrapper"><input type="checkbox" class="checkbox-input" /><span class="checkbox-tile"><span class="checkbox-icon"><i class="ri-global-line"></i></span><span class="checkbox-label">Live Users By Country</span></span></label></div><div class="checkbox"><label class="checkbox-wrapper"><input type="checkbox" class="checkbox-input" /><span class="checkbox-tile"><span class="checkbox-icon"><i class="ri-contacts-book-line"></i></span><span class="checkbox-label">Contact Leads</span></span></label></div><div class="checkbox"><label class="checkbox-wrapper"><input type="checkbox" class="checkbox-input" /><span class="checkbox-tile"><span class="checkbox-icon"><i class="ri-check-double-line"></i></span><span class="checkbox-label">In Approval</span></span></label></div><div class="checkbox"><label class="checkbox-wrapper"><input type="checkbox" class="checkbox-input" /><span class="checkbox-tile"><span class="checkbox-icon"><i class="ri-file-text-line"></i></span><span class="checkbox-label">Form Builder Leads</span></span></label></div></div>';

                $('#sidebar-html').html(html);

                $('#offcanvasRightLabel').text('Dashboard');
                $('#dashboard-sidebar').addClass('active');
            },
            complete: function() {
                $.loader.close(true);
            },
            error: function() {
                console.log('error!');
            }
        });
    });

    //Recent Activity
    $("#activity-sidebar").click(function(e) {
        $('#sidebar-html').html('');
        $('.first-menu .links').removeClass('active');
        $('#offcanvasRightLabel').text('Recent Activity');
        $('#activity-sidebar').addClass('active');

        if ($('body.fixsidebar-open').length == 0) {
            $('body').addClass('fixsidebar-open');
            new bootstrap.Offcanvas('#RightSidebar').show();
        }

        $.ajax({
            type: "POST",
            url: window.site_url + "/powerpanel/dashboard/ajax",
            data: {
                type: 'activity'
            },
            beforeSend: function(xhr) {
                $('#RightSidebar').loader(loaderConfig);
                xhr.setRequestHeader('X-CSRF-Token', $('input[name="_token"]').val());
            },
            dataType: "JSON",
            success: function(data) {
                let html = '<div data-simplebar class="p-3 pt-0 sidebar-activity">';
                html += '<div class="acitivity-timeline acitivity-main pt-2">';
                $.each(data, function(index, element) {
                    let icon = '';
                    let back = '';
                    let action = $.trim(element.varAction.toLowerCase());
                    switch (action) {
                        case 'add':
                            icon = 'ri-add-line';
                            back = 'success';
                            break;

                        case 'auto saved as a draft':
                            icon = 'ri-file-text-line';
                            back = 'info';
                            break;

                        case 'comment added':
                            icon = 'ri-message-2-line';
                            back = 'success';
                            break;

                        case 'delete':
                            icon = 'ri-delete-bin-line';
                            back = 'danger';
                            break;

                        case 'delete approved record':
                            icon = 'ri-checkbox-circle-line';
                            back = 'success';
                            break;

                        case 'delete record':
                            icon = 'ri-delete-bin-3-line';
                            back = 'danger';
                            break;

                        case 'delete trash record':
                            icon = 'ri-delete-bin-2-line';
                            back = 'danger';
                            break;

                        case 'draft record approved':
                            icon = 'ri-task-line';
                            back = 'warning';
                            break;

                        case 'draft record move to trash':
                            icon = 'ri-drag-move-2-fill';
                            back = 'danger';
                            break;

                        case 'edit':
                            icon = 'ri-edit-line';
                            back = 'secondary';
                            break;

                        case 'favorite record move':
                            icon = 'ri-arrow-go-forward-line';
                            back = 'warning';
                            break;

                        case 'form added':
                            icon = 'ri-file-add-line';
                            back = 'success';
                            break;

                        case 'form edit':
                            icon = 'ri-file-edit-line';
                            back = 'primary';
                            break;

                        case 'locked record':
                            icon = 'ri-lock-2-line';
                            back = 'warning';
                            break;

                        case 'publish':
                            icon = 'ri-cloud-line';
                            back = 'success';
                            break;

                        case 'quick edit to record':
                            icon = 'ri-edit-box-line';
                            back = 'success';
                            break;

                        case 'record approved':
                            icon = 'ri-checkbox-circle-line';
                            back = 'success';
                            break;

                        case 'record copy':
                            icon = 'ri-file-copy-line';
                            back = 'success';
                            break;

                        case 'record move to archive':
                            icon = 'ri-file-list-3-line';
                            back = 'secondary';
                            break;

                        case 'record move to favorite':
                            icon = 'ri-price-tag-3-line';
                            back = 'primary';
                            break;

                        case 'record move to trash':
                            icon = 'ri-arrow-go-back-line';
                            back = 'danger';
                            break;

                        case 'sent for approval':
                            icon = 'ri-send-plane-line';
                            back = 'info';
                            break;

                        case 'trash record restore':
                            icon = 'ri-history-line';
                            back = 'success';
                            break;

                        case 'unlocked record':
                            icon = 'ri-lock-unlock-line';
                            back = 'secondary';
                            break;

                        case 'unpublish':
                            icon = 'ri-cloud-off-line';
                            back = 'warning';
                            break;

                        case 'update draft':
                            icon = 'ri-sticky-note-line';
                            back = 'info';
                            break;

                        default:
                            icon = '';
                            back = 'success';
                    }

                    html += '<div class="acitivity-item d-flex pb-3">';
                    html += '<div class="flex-shrink-0 avatar-xs acitivity-avatar">';
                    html += '<div class="avatar-title bg-soft-' + back + ' text-' + back + ' rounded-circle"><i class="' + icon + '"></i></div>';
                    html += '</div>';
                    html += '<div class="flex-grow-1 ms-3">';
                    html += '<h6 class="mb-1 lh-base">' + element.varAction + ' by ' + element.user.name + '</h6>';
                    html += '<p class="text-muted mb-1"><b>' + element.module.varTitle + ': </b>' + element.varTitle + '</p>';
                    html += '<small class="mb-0 text-muted">' + element.created_at + '</small>';
                    html += '</div></div>';
                });
                html += '</div>';
                html += '</div>';
                html += '<div class="card sidebar-alert border-0 shadow-none text-center mb-0 mt-2"><div class="card-body"><a class="text-muted" href="'+site_url+'/powerpanel/log'+'" id="notification">View All <i class="ri-arrow-right-line"></i></a></div></div>';
                $('#sidebar-html').html(html);
            },
            complete: function() {
                $.loader.close(true);
            }
        });
    });

    //Dashboard
    $("#notification-sidebar").click(function() {
        $('#sidebar-html').html('');
        $('#offcanvasRightLabel').text('Notification');
        if ($('body.fixsidebar-open').length == 0) {
            $('body').addClass('fixsidebar-open');
            new bootstrap.Offcanvas('#RightSidebar').show();
        }

        $.ajax({
            url: window.site_url + "/powerpanel/Notification_View",
            type: "POST",
            beforeSend: function(xhr) {
                $('#RightSidebar').loader(loaderConfig);
                xhr.setRequestHeader('X-CSRF-Token', $('input[name="_token"]').val());
            },
            success: function(data) {
                $('.first-menu .links').removeClass('active');
                $('#sidebar-html').html(data);

                $('#offcanvasRightLabel').text('Notification');
                $('#notification-sidebar').addClass('active');
            },
            complete: function() {
                $.loader.close(true);
            },
            error: function() {
                console.log('error!');
            }
        });
    });

setTimeout(function(){ 
	$(".nav-sort").draggable({
        helper: "clone",
        connectToSortable: "#dropper",
        revert: "invalid",
        start: function(event, ui) {
            $('body').addClass('navdrag-start');
            $(ui.helper).addClass('menu-dragging');
            $('.pagemenu').css('background', 'aliceblue');
        },
        stop: function(event, ui) {
            $('body').removeClass('navdrag-start');
            $('.pagemenu').find('.nav-link').attr('data-bs-toggle', 'tooltip').attr('data-bs-placement', 'left');
            $('.pagemenu').css('background', 'transparent');
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
                //$(this).draggable( "option", "disabled", true );
        }
    }).disableSelection();
    makeSortable();
}, 3000);
    
});

function makeSortable() {
    $("#dropper").sortable({
        placeholder: "ui-state-highlight",
        start: function(event, ui) {
            $(ui.item).find('span').remove();
        },
        stop: function(event, ui) {
            $(ui.item).find('span').remove();
            $(ui.item).find('i').removeClass('d-none');
            $(ui.item).find('a').removeClass('active');
            $(ui.item).append('<a href="javascript:void(0)" class="close-menu" title="Remove"><i class="ri-close-circle-fill"></i></a>');
            saveRsideBar();
        }
    }).disableSelection();
}


$(document).on('click', '#dropper .close-menu', function() {
    $(this).parent().remove();
    saveRsideBar();
});

function saveRsideBar() {
    var ids = [];
    $('#dropper li').each(function(i, j) {
        // console.log(i,j)
        ids.push({
            id: $(this).data('id'),
            order: i
        });
    });
    let data = {
        ids: ids,
        user: user_id
    }
    $.ajax({
        url: window.site_url + "/powerpanel/save-order",
        data: data,
        type: "POST",
        success: function(data) {},
        complete: function() {},
        error: function() {
            console.log('error!');
        }
    });
}

function getRsideBar() {
    let data = {
        user: user_id
    }
    $.ajax({
        url: window.site_url + "/powerpanel/get-sidebar-order",
        data: data,
        type: "GET",
        success: function(data) {
            $(data).each(function(i, j) {
                // console.log(j.id);
                let shortcut = $('.nav-sort[data-id=' + j.id + ']').clone();
                shortcut.removeAttr('class');
                shortcut.find('span').remove();
                shortcut.find('i').removeClass('d-none');
                shortcut.find('a').removeAttr('class')
                    .addClass("links")
                    .attr("data-bs-toggle", "tooltip")
                    .attr("data-bs-placement", "left");
                $(shortcut).prepend('<a href="javascript:void(0)" class="close-menu" title="Remove"><i class="ri-close-circle-fill"></i></a>');
                $('#dropper').append(shortcut);
            });
        },
        complete: function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            makeSortable();
        },
        error: function() {
            console.log('error!');
        }
    });
}