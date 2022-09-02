<!-- CSS Files For Visual Composer -->
<link href="{{ $CDN_PATH.'resources/css/packages/visualcomposer/visualcomposercss.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/css/packages/visualcomposer/jquery-ui.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/css/packages/visualcomposer/datatables.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/css/packages/visualcomposer/datatables.bootstrap.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/css/packages/visualcomposer/jquery.mCustomScrollbar.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/css/packages/visualcomposer/pgbuilder.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/css/packages/visualcomposer/jquerysctipttop.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/css/packages/visualcomposer/bootstrap-select.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/ckeditor/content-style.css' }}" rel="stylesheet" type="text/css" />


<!-- JS Files and script for Visual Composer -->
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/jquery-ui.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/jquery.mCustomScrollbar.concat.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/pgbuilder.config.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/numbervalidation.js' }}" type="text/javascript"></script>

<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/events-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/news-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/alerts-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/links-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/faqs-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/department-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/blogs-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/consultations-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/complaintservices-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/fmbroadcasting-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/boardofdirectors-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/registerapplication-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/licenceregister-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/formsandfees-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/photo-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/video-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/publication-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/netquick-careers-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/service-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/candwservice-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/team-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/loading.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/bootstrap-select.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/visualcomposer/common_form_validation.js' }}" type="text/javascript"></script>

<script>
    function GetSetTemplateData(id) {
        
        var Template_URL = '{!! url("/powerpanel/pages/Template_Listing") !!}';
        
        if ($("#no-content").hasClass('hide')) {
            var temp = 'Y';
        } else if ($(".builder-append-data .section-item").length > 0) {
            var temp = 'Y';
        } else {
            var temp = 'N';
        }
        $.ajax({
            type: 'POST',
            url: Template_URL,
            data: 'id=' + id + '&temp=' + temp,
            success: function (msg) {
               
                if ($("#no-content").hasClass('hide')) {
                    $('#builder-control .builder-append-data').append(msg);
                } else if ($(".builder-append-data .section-item").length > 0) {
                    $('#builder-control .builder-append-data').append(msg);
                } else {
                    $('#builder-control').html(msg);
                }
                $("#pgBuiderSections .modal-header .close").trigger("click");
                builder.init();
                $(".record-list").sortable().disableSelection();
                $("#pgBuiderSections").hide();
            }
        });
    }


    function GetSetFormBuilderData(id) {
        var FormBuilder_URL = '{!! url("/powerpanel/pages/FormBuilder_Listing") !!}';
        if ($('.add-element').hasClass('clicked')) {
             var temp = 'F';
        }else if ($("#no-content").hasClass('hide')) {
            var temp = 'Y';
        } else if ($(".builder-append-data .section-item").length > 0) {
            var temp = 'Y';
        } else {
            var temp = 'N';
        }
        $.ajax({
            type: 'POST',
            url: FormBuilder_URL,
            data: 'id=' + id + '&temp=' + temp,
            success: function (msg) {
                
                if ($('.add-element').hasClass('clicked')) {
                    $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(msg);
                    $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                    $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('only_title');
                }else if ($("#no-content").hasClass('hide')) {
                    $('#builder-control .builder-append-data').append(msg);
                } else if ($(".builder-append-data .section-item").length > 0) {
                    $('#builder-control .builder-append-data').append(msg);
                } else {
                    $('#builder-control').html(msg);
                }
                
                $("#pgBuiderSections .modal-header .close").trigger("click");
                builder.init();
                $(".record-list").sortable().disableSelection();
                $("#pgBuiderSections").hide();
            }
        });
    }

    $(document).on('click', '.config-class .dropdown-toggle', function (event) {
        $(".config-class").addClass("open");
    });
    $(document).on('click', '.layout-class .dropdown-toggle', function (event) {
        $(".layout-class").addClass("open");
    });
    $(document).on('click', '.cat-class .dropdown-toggle', function (event) {
        $(".cat-class").addClass("open");
    });
    $(document).on('click', '.sort-class .dropdown-toggle', function (event) {
        $(".sort-class").addClass("open");
    });
    $(document).on('click', '.buttonsec-class .dropdown-toggle', function (event) {
        $(".buttonsec-class").addClass("open");
    });
    
           
//..End Open while add or edit section
</script>
