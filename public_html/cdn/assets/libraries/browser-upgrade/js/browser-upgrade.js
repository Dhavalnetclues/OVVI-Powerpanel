/* Browser Upgrade Font API S */
    var ie = /MSIE (\d+)/.exec(navigator.userAgent);
    ie = ie? ie[1] : null;
    if(ie && ie <= 9) {
        var script = document.createElement( 'script' );script.type = 'text/javascript';
        script.src = 'assets/js/html5.min.js';$("head").append("<style type='text/css'>.buorg{display:block}</style>");
    }
    if(ie && ie == 8) {  
        $('head').append("<style type='text/css'>.buorg{display:block}</style>");
    }
    else if(ie && ie == 7) {
        $('head').append("<style type='text/css'>.buorg{display:block;}</style>");
    }
/* Browser Upgrade Font API E */