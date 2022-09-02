/* Back to Top Scroll S */
    $(window).scroll(function() {
        if ($(this).scrollTop() > 80) {
            $('#back-top').show();
        } else {
            $('#back-top').hide();
        }
    });
    $('#back-top').click(function() {
        $('body,html').animate({
            scrollTop: 0
        }, 2500);
        return false;
    });
/* Back to Top Scroll E */