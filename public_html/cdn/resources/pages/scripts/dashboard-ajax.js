$(document).ready(function() {
    // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))

    $(".cmsPages").click(function() {
        var cmspage_id = this.id;
        $.ajax({
            url: site_url + '/powerpanel/dashboard/ajax',
            data: { type: 'cms', id: cmspage_id },
            type: "POST",
            dataType: "json",
            success: function(data) {
                var html = '';
                if (data != null && data != '') {
                    html += '<div class="modal-dialog">';
                    html += '<div class="modal-content">';
                    html += '<div class="modal-header">';
                    html += '<h5 class="modal-title">' + data.varTitle + '</h5>';
                    html += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                    html += '</div>';
                    html += '<div class="modal-body">';
                    html += '<p>' + data.varTitle + '</p>';
                    html += '<p>' + data.txtDescription + '</p>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    $('.detailsCmsPage').html(html);
                    $('.detailsCmsPage').modal('show');
                }
            },
            error: function() {
                console.log('error!');
            }
        });
    });
    $(".contactUsLead").click(function() {
        var contactuslead_id = this.id;
        $.ajax({
            url: site_url + '/powerpanel/dashboard/ajax',
            data: { type: 'contactuslead', id: contactuslead_id },
            type: "POST",
            dataType: "json",
            success: function(data) {
                var html = '';
                if (data != null && data != '') {
                    html += '<div class="offcanvas-header border-bottom">';
                    html += '<h5 class="offcanvas-title" id="detailsContactUsLead">' + data.varTitle + '</h5>';
                    html += '<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>';
                    html += '</div>';
                    html += '<div class="offcanvas-body p-4 overflow-hidden">';
                    html += '<div data-simplebar style="height: calc(100vh);">';
                    html += '<p><strong>Email:</strong> ' + data.varEmail + '</p>';
                    if (data.DepartmentName == null || data.DepartmentName == '') {
                        html += '<p><strong>Department:</strong> ' + '-' + '</p>';
                    } else {
                        html += '<p><strong>Department:</strong> ' + data.DepartmentName + '</p>';
                    }
                    
                    if (data.varPhoneNo == null || data.varPhoneNo == '') {
                        html += '<p><strong>Phone No:</strong> ' + '-' + '</p>';
                    } else {
                        html += '<p><strong>Phone No:</strong> ' + data.varPhoneNo + '</p>';
                    }

                    if (data.varContactingAbout == null || data.varContactingAbout == '') {
                        html += '<p><strong>Connecting About:</strong> ' + '-' + '</p>';
                    } else {
                        html += '<p><strong>Connecting About:</strong> ' + data.varContactingAbout + '</p>';
                    }

                    
                    if (data.txtUserMessage == null || data.txtUserMessage == '') {
                        html += '<p><strong>Message:</strong> ' + '-' + '</p>';
                    } else {
                        html += '<p><strong>Message:</strong> ' + data.txtUserMessage + '</p>';
                    }
                    html += '</div></div></div></div>';
                    
                    $('#detailsContactUsLead').html(html);
                    var myOffcanvas = document.getElementById('detailsContactUsLead');
                    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
                    bsOffcanvas.show();
                }
            },
            error: function() {
                console.log('error!');
            }
        });
    });
    $(".FaqRecord").click(function() {
        var faq_id = this.id;
        $.ajax({
            url: site_url + '/powerpanel/dashboard/ajax',
            data: { type: 'faq', id: faq_id },
            type: "POST",
            dataType: "json",
            success: function(data) {
                var html = '';
                if (data != null && data != '') {
                    html += '<div class="modal-dialog">';
                    html += '<div class="modal-content">';
                    html += '<div class="modal-header">';
                    html += '<h5 class="modal-title">FAQ</h5>';
                    html += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                    html += '</div>';
                    html += '<div class="modal-body">';
                    html += '<p>' + data.question + '</p>';
                    html += '<p>' + data.answer + '</p>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    $('.FAQDetails').html(html);
                    $('.FAQDetails').modal('show');
                }
            },
            error: function() {
                console.log('error!');
            }
        });
    });
    $(".BlogRecord").click(function() {
        var blog_id = this.id;
        $.ajax({
            url: site_url + '/powerpanel/dashboard/ajax',
            data: { type: 'blog', blog_alias: blog_id },
            type: "POST",
            dataType: "json",
            success: function(data) {
                var html = '';
                if (data != null && data != '') {
                    html += '<div class="modal-dialog">';
                    html += '<div class="modal-content">';
                    html += '<div class="modal-header">';
                    html += '<h5 class="modal-title">Blog</h5>';
                    html += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                    html += '</div>';
                    html += '<div class="modal-body">';
                    html += '<p>' + data.title + '</p>';
                    html += '<p>' + data.description + '</p>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    $('.BlogDetails').html(html);
                    $('.BlogDetails').modal('show');
                }
            },
            error: function() {
                console.log('error!');
            }
        });
    });
});