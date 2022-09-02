/* SVGConverter function S */
    function SVGConverter (e) {
        $(e + ' img.svg').each(function() {
            var $img = $(this),
                imgID = $img.attr('id'),
                imgClass = $img.attr('class'),
                imgURL = $img.attr('src');

            $.get(imgURL, function(data) {
                //var $svg = $(data).find('svg'), $svg = $svg.removeAttr('xmlns:a');
                var $svg = $(data).find('svg');
                if(typeof imgID !== 'undefined') { $svg = $svg.attr('id', imgID); }
                if(typeof imgClass !== 'undefined') { $svg = $svg.attr('class', imgClass+' replaced-svg'); }
                if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                    $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'));
                }
                $img.replaceWith($svg);    
            }, 'xml');    
        });
    }
    SVGConverter (".nq-svg");
/* SVGConverter function E */