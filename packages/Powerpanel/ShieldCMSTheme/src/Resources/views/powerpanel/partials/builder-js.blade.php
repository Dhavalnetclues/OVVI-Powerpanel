<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-cookie-master/src/jquery.cookie.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-ui.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/mcscroll/jquery.mCustomScrollbar.concat.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/pgbuilder.config.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/events-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/news-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/blogs-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/photo-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/video-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/publication-datatables-builder-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/numbervalidation.js' }}" type="text/javascript"></script>

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