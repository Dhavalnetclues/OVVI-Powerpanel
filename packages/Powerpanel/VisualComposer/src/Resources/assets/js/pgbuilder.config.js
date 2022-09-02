"use strict";
/*
 * nestable.config - jQuery Plugin to manage builder section list
 * Author: NetQuick
 * since : 2019-02-27
 */
var builder = function() {
    // private functions & variables
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    // public functions
    return {
        //main function
        init: function() {
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt',
                cancel: 'input,button,.ck-editor__editable'
            });
        },
        reInitSortable: function() {
            $("#section-container").sortable('destroy');
            $("#section-container").sortable({
                placeholder: "ui-state-highlight",
                handle: '.fa-arrows-alt'
            });
        },
        resizeImg: function(img, cwidth, point) {
            var orginalWidth = img.width();
            var poinDefault = 0;
            if (cwidth > 0 && (point > 0 || point < 0)) {
                img.width(cwidth);
                poinDefault = point;
            }

            $("#slider").slider({
                value: poinDefault,
                min: -50,
                max: 50,
                step: 5,
                slide: function(event, ui) {
                    var fraction = (1 + ui.value / 100),
                        newWidth = orginalWidth * fraction;
                    img.width(newWidth);
                }
            });
        },
        getRecord: function(recordId, module) {
            var response = false;
            var modelName = '';
            var ajaxRoute = site_url + '/powerpanel/get-customize-record';
            if (module == 'business') {
                modelName = 'Business';
            }
            if (module == 'promotion') {
                modelName = 'Promotion';
            }
            if (module == 'events') {
                modelName = 'Events';
            }
            if (module == 'articles') {
                modelName = 'Articles';
            }
            if (module == 'news') {
                modelName = 'News';
            }
            if (module == 'blogs') {
                modelName = 'Blogs';
            }
            jQuery.ajax({
                type: "POST",
                url: ajaxRoute,
                dataType: 'JSON',
                data: {
                    id: recordId,
                    model: modelName
                },
                async: false,
                success: function(result) {
                    response = result;
                },
                complete: function() {}
            });
            return response;
        },
        fillFormObj: function() {

            var builderObj = [];
            $('.section-item').each(function() {

                //Image with content
                if ($(this).hasClass('img-rt-area')) {
                    var img = $(this).find('.imgip').data('id');
                    var caption = $(this).find('.imgip').data('caption');
                    var txt = $(this).find('.imgip').val();
                    var align = $(this).find('.imgip').data('type');
                    var source = $(this).find('img').attr('src');
                    var obj = {
                        type: 'img_content',
                        val: {
                            title: caption,
                            image: img,
                            content: txt,
                            alignment: align,
                            src: source
                        }
                    };
                } else if ($(this).hasClass('videoContent')) {
                    var txt = $(this).find('.vidip').data('caption');
                    var content = $(this).find('.vidip').val();
                    var videoIdSec = $(this).find('.vidip').data('id');
                    var vidType = $(this).find('.vidip').data('type');
                    var align = $(this).find('.vidip').data('aligntype');
                    var obj = {
                        type: 'video_content',
                        val: {
                            title: txt,
                            videoType: vidType,
                            vidId: videoIdSec,
                            content: content,
                            alignment: align
                        }
                    };
                } else if ($(this).hasClass('home-img-rt-area')) {
                    var img = $(this).find('.imgip').data('id');
                    var caption = $(this).find('.imgip').data('caption');
                    var txt = $(this).find('.imgip').val();
                    var align = $(this).find('.imgip').data('type');
                    var source = $(this).find('img').attr('src');
                    var obj = {
                        type: 'home-img_content',
                        val: {
                            title: caption,
                            image: img,
                            content: txt,
                            alignment: align,
                            src: source
                        }
                    };
                } else if ($(this).hasClass('text-area')) {
                    //Only Content              
                    var txt = $(this).find('.txtip').val();
                    var eclass = $(this).find('.txtip').data('class');
                    var obj = {
                        type: 'textarea',
                        val: {
                            content: txt,
                            extclass: eclass
                        }
                    };
                } else if ($(this).hasClass('text-accordian')) {
                    //Only Content              
                    var txt = $(this).find('.txtip').val();
                    var caption = $(this).find('.txtip').data('title');
                    var obj = {
                        type: 'accordianblock',
                        val: {
                            content: txt,
                            title: caption
                        }
                    };
                } else if ($(this).hasClass('form-area')) {
                    //Only Content              
                    var txt = $(this).find('.txtip').val();
                    var eclass = $(this).find('.txtip').data('class');
                    var eid = $(this).find('.txtip').data('id');
                    var obj = {
                        type: 'formarea',
                        val: {
                            id: eid,
                            content: txt,
                            extclass: eclass
                        }
                    };
                } else if ($(this).hasClass('two-part')) {
                    //Only Content              
                    var lefttxt = $(this).find('.txtip').val();
                    var righttxt = $(this).find('.txtip').data('content');
                    var obj = {
                        type: 'twocontent',
                        val: {
                            leftcontent: lefttxt,
                            rightcontent: righttxt
                        }
                    };
                } else if ($(this).hasClass('img-area')) {

                    //Only Image              
                    var img = $(this).find('.imgip').val();
                    var source = $(this).find('img').attr('src');
                    var caption = $(this).find('.imgip').data('caption');
                    var extra_class = $(this).find('.imgip').data('extra_class');
                    var data_width = $(this).find('.imgip').data('width');
                    var align = $(this).find('.imgip').data('type');
                    var obj = {
                        type: 'image',
                        val: {
                            title: caption,
                            image: img,
                            extra_class: extra_class,
                            data_width: data_width,
                            alignment: align,
                            src: source,
                        }
                    };
                } else if ($(this).hasClass('img-map')) {
                    //Only Image      
                    var latitude = $(this).find('.imgip').data('latitude');
                    var longitude = $(this).find('.imgip').data('longitude');
                    var obj = {
                        type: 'map',
                        val: {
                            latitude: latitude,
                            longitude: longitude
                        }
                    };
                } else if ($(this).hasClass('img-document')) {
                    //Only Image    
                    var img = $(this).find('.imgip').val();
                    var source = $(this).find('img').attr('src');
                    var caption = $(this).find('.imgip').data('caption');
                    var doc_date_time = $(this).find('.imgip').data('doc_date_time');

                    var obj = {
                        type: 'document',
                        val: {
                            caption: caption,
                            doc_date_time: doc_date_time,
                            document: img,
                            src: source
                        }
                    };

                } else if ($(this).hasClass('videoOnly')) {
                    //Only Title
                    var txt = $(this).find('.vidip').data('caption');
                    var videoIdSec = $(this).find('.vidip').val();
                    var vidType = $(this).find('.vidip').data('type');
                    var obj = {
                        type: 'only_video',
                        val: {
                            title: txt,
                            videoType: vidType,
                            vidId: videoIdSec
                        }
                    };
                } else if ($(this).hasClass('titleOnly')) {
                    //Only Title
                    var txt = $(this).find('.txtip').val();
                    var eclass = $(this).find('.txtip').data('class');
                    var obj = {
                        type: 'only_title',
                        val: {
                            content: txt,
                            extclass: eclass
                        }
                    };
                } else if ($(this).hasClass('img-gallery-section')) {
                    // Image Gallery              
                    var items = {};
                    $(this).find('.record-list li').each(function(key, val) {
                        var iId = $(this).data('id');
                        items[key] = {
                            id: iId,
                            src: $(this).find('img').attr('src')
                        };
                    });

                    var caption = $(this).find('.imgip').data('caption');
                    var imgLayout = $(this).find('.imgip').data('layout');
                    var obj = {
                        type: 'image_gallery',
                        val: {
                            title: caption,
                            layout: imgLayout,
                            images: items
                        }
                    };
                } else if ($(this).hasClass('contactInfoOnly')) {
                    //Only Title
                    var txt = $(this).find('.txtip').val();
                    var section_address = $(this).find('.txtip').data('address');
                    var section_email = $(this).find('.txtip').data('email');
                    var section_phone = $(this).find('.txtip').data('phone');
                    var obj = {
                        type: 'conatct_info',
                        val: {
                            content: txt,
                            section_address: section_address,
                            section_email: section_email,
                            section_phone: section_phone
                        }
                    };
                } else if ($(this).hasClass('buttonInfoOnly')) {
                    //Only Title
                    var txt = $(this).find('.txtip').val();
                    var caption = $(this).find('.txtip').data('caption');
                    var target = $(this).find('.txtip').data('linktarget');
                    var align = $(this).find('.txtip').data('type');
                    var obj = {
                        type: 'button_info',
                        val: {
                            title: caption,
                            content: txt,
                            alignment: align,
                            target: target,
                        }
                    };
                } else if ($(this).hasClass('custom-section-module')) {

                    var caption = $(this).find('.txtip').val();
                    var subtitle = $(this).find('.txtip').data('subtitle');
                    var eclass = $(this).find('.txtip').attr('data-extclass');
                    var layoutType = $(this).find('.txtip').attr('data-layout');

                    var items = {};
                    $(this).find('.record-list li').each(function(key, val) {
                        items[key] = {
                            title: $.trim($(this).find('a').data('title')),
                            imgid: $(this).find('a').data('img'),
                            imgsrc: $(this).find('a').data('imgsrc'),
                            link: $(this).find('a').data('link'),
                            desc: $(this).find('a').data('desc')
                        };
                    });
                    var obj = {
                        type: 'custom_section',
                        val: {
                            title: caption,
                            SubTitle: subtitle,
                            layout: layoutType,
                            records: items,
                            extclass: eclass
                        }
                    };

                } else if ($(this).hasClass('iframe')) {
                    //Iframe							
                    var txt = $(this).find('.txtip').val();
                    var eclass = $(this).find('.txtip').data('class');
                    var obj = {
                        type: 'iframe',
                        val: {
                            content: txt,
                            extclass: eclass
                        }
                    };
                } else if ($(this).hasClass('module')) {
                    //module
                    var items = {};
                    $(this).find('.record-list li').each(function(key, val) {
                        var iId = $(this).data('id');
                        var cusom_content = {
                            img: $(this).data('img'),
                            imgsrc: $(this).data('imgsrc'),
                            imgheight: $(this).data('imgheight'),
                            imgwidth: $(this).data('imgwidth'),
                            imgpoint: $(this).data('imgpoint'),
                            phone: $(this).data('phone'),
                            email: $(this).data('email'),
                            website: $(this).data('website'),
                            address: $(this).data('address'),
                            extraclass: $(this).data('extraclass'),
                            description: $(this).data('description')
                        };
                        key++;
                        items[key] = { id: iId, title: $.trim($(this).text()), custom_fields: cusom_content };
                    });

                    var iModule = $(this).find('input[type=hidden]').val();
                    var caption = $(this).find('input[type=hidden]').data('caption');
                    var desc = $(this).find('input[type=hidden]').data('desc');
                    var extraclass = $(this).find('input[type=hidden]').data('extraclass');
                    var settings = $(this).find('input[type=hidden]').data('config');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var filter = $(this).find('input[type=hidden]').data('filter');
                    var hasFeaturedRes = $(this).find('input[type=hidden]').data('frest');
                    var obj = {
                        type: 'module',
                        val: {
                            title: caption,
                            desc: desc,
                            extraclass: extraclass,
                            module: iModule,
                            config: settings,
                            layout: $.trim(layoutType),
                            records: items,
                            template: filter,
                            featured_restaurant_section: hasFeaturedRes
                        }
                    };
                } else if ($(this).hasClass('businessTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'business_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('spacerTemplate')) {
                    //Only Title
                    var conf = $(this).find('.txtip').data('config');

                    var obj = {
                        type: 'spacer_template',
                        val: {
                            config: conf
                        }
                    };
                } else if ($(this).hasClass('eventsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var eventscat = $(this).find('.txtip').data('eventscat');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'events_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            class: extra_class,
                            sdate: sdate,
                            edate: edate,
                            eventscat: eventscat,
                            desc: sdesc,
                            config: conf,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('blogsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var blogscat = $(this).find('.txtip').data('blogscat');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'blogs_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            desc: sdesc,
                            config: conf,
                            class: extra_class,
                            sdate: sdate,
                            edate: edate,
                            blogscat: blogscat,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('numberAllocationsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var obj = {
                        type: 'numberAllocations_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            class: extra_class,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('serviceTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');

                    var servicecat = $(this).find('.txtip').data('servicecat');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'service_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            desc: sdesc,
                            config: conf,
                            class: extra_class,

                            servicecat: servicecat,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('candwserviceTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');

                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'candwservice_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            desc: sdesc,
                            config: conf,
                            class: extra_class,
                            sdate: sdate,
                            edate: edate,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('consultationsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var blogscat = $(this).find('.txtip').data('blogscat');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'consultations_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            desc: sdesc,
                            config: conf,
                            class: extra_class,
                            sdate: sdate,
                            edate: edate,
                            blogscat: blogscat,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('complaintservicesTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'complaintservices_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            desc: sdesc,
                            config: conf,
                            class: extra_class,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('fmbroadcastingTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'fmbroadcasting_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            desc: sdesc,
                            config: conf,
                            class: extra_class,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('boardofdirectorsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'boardofdirectors_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            desc: sdesc,
                            config: conf,
                            class: extra_class,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('registerapplicationTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var obj = {
                        type: 'registerapplication_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            class: extra_class,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('licenceregisterTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var sect = $(this).find('.txtip').data('sector');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var obj = {
                        type: 'licenceregister_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            sector: sect,
                            class: extra_class,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('formsandfeesTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'formsandfees_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            desc: sdesc,
                            config: conf,
                            class: extra_class,
                            layout: layoutType,
                            template: type
                        }
                    };

                } else if ($(this).hasClass('publicationTemplate')) {

                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var publicationscat = $(this).find('.txtip').data('publicationscat');
                    var sectorType = $(this).find('input[type=hidden]').data('sector');
                    var obj = {
                        type: 'publication_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            class: extra_class,
                            publicationscat: publicationscat,
                            config: conf,
                            sector: sectorType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('productTemplate')) {
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var eclass = $(this).find('input[type=hidden]').data('extclass');
                    var obj = {
                        type: 'product_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            extclass: eclass,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('projectTemplate')) {
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var eclass = $(this).find('input[type=hidden]').data('extclass');
                    var obj = {
                        type: 'project_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            extclass: eclass,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('clientTemplate')) {
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var eclass = $(this).find('input[type=hidden]').data('extclass');
                    var obj = {
                        type: 'client_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            extclass: eclass,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('careerTemplate')) {
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var eclass = $(this).find('input[type=hidden]').data('extclass');
                    var obj = {
                        type: 'career_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            extclass: eclass,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('testimonialTemplate')) {
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var eclass = $(this).find('input[type=hidden]').data('extclass');
                    var obj = {
                        type: 'testimonial_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            extclass: eclass,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('teamTemplate')) {
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var eclass = $(this).find('input[type=hidden]').data('extclass');
                    var obj = {
                        type: 'team_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            extclass: eclass,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('showTemplate')) {
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var eclass = $(this).find('input[type=hidden]').data('extclass');
                    var obj = {
                        type: 'show_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            extclass: eclass,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('galleryTemplate')) {
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var eclass = $(this).find('input[type=hidden]').data('extclass');
                    var obj = {
                        type: 'gallery_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            extclass: eclass,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('newsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var newscat = $(this).find('.txtip').data('newscat');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'news_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            class: extra_class,
                            sdate: sdate,
                            edate: edate,
                            newscat: newscat,
                            desc: sdesc,
                            config: conf,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('publicRecordTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var newscat = $(this).find('.txtip').data('newscat');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'publicRecord_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            class: extra_class,
                            sdate: sdate,
                            edate: edate,
                            newscat: newscat,
                            desc: sdesc,
                            config: conf,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('latestNewsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var extra_class = $(this).find('.txtip').data('class');
                    var type = $(this).find('.txtip').data('type');
                    var obj = {
                        type: 'latest_news_template',
                        val: {
                            title: caption,
                            class: extra_class,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('QuickLinkTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var type = $(this).find('.txtip').data('type');
                    var obj = {
                        type: 'quick_link_template',
                        val: {
                            title: caption,
                            class: extra_class,
                            sdate: sdate,
                            edate: edate,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('photoalbumTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'photoalbum_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            class: extra_class,
                            sdate: sdate,
                            edate: edate,
                            desc: sdesc,
                            config: conf,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('videoalbumTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'videoalbum_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            class: extra_class,
                            sdate: sdate,
                            edate: edate,
                            desc: sdesc,
                            config: conf,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('organizationsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var parentorg = $(this).find('.txtip').data('parentorg');
                    var orgclass = $(this).find('.txtip').data('orgclass');
                    var type = $(this).find('.txtip').data('type');
                    var obj = {
                        type: 'organizations_template',
                        val: {
                            title: caption,
                            parentorg: parentorg,
                            orgclass: orgclass,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('interconnectionsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var parentorg = $(this).find('.txtip').data('parentorg');
                    var orgclass = $(this).find('.txtip').data('orgclass');
                    var type = $(this).find('.txtip').data('type');
                    var obj = {
                        type: 'interconnections_template',
                        val: {
                            title: caption,
                            parentorg: parentorg,
                            orgclass: orgclass,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('alertsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var extra_class = $(this).find('.txtip').data('class');
                    var alerttype = $(this).find('.txtip').data('alerttype');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var type = $(this).find('.txtip').data('type');
                    var obj = {
                        type: 'alerts_template',
                        val: {
                            title: caption,
                            class: extra_class,
                            limit: slimit,
                            alerttype: alerttype,
                            sdate: sdate,
                            edate: edate,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('departmentTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var type = $(this).find('.txtip').data('type');
                    var obj = {
                        type: 'department_template',
                        val: {
                            title: caption,
                            class: extra_class,
                            limit: slimit,
                            sdate: sdate,
                            edate: edate,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('linkTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var linkcat = $(this).find('.txtip').data('linkcat');
                    var type = $(this).find('.txtip').data('type');
                    var obj = {
                        type: 'link_template',
                        val: {
                            title: caption,
                            class: extra_class,
                            limit: slimit,
                            sdate: sdate,
                            edate: edate,
                            linkcat: linkcat,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('faqTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var faqcat = $(this).find('.txtip').data('faqcat');
                    var type = $(this).find('.txtip').data('type');
                    var obj = {
                        type: 'faq_template',
                        val: {
                            title: caption,
                            class: extra_class,
                            limit: slimit,
                            sdate: sdate,
                            edate: edate,
                            faqcat: faqcat,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('promotionsTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'promotions_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            template: type
                        }
                    };

                } else if ($(this).hasClass('row-template')) {

                    //var r = 1;
                    //$('.row-template').each(function(index) {

                    var obj = {};

                    var item_id = $(this).parent('div').attr('id');
                    var row_class = $(this).find('#' + item_id + '-row').attr('data-extclass');
                    var row_animation = $(this).find('#' + item_id + '-row').attr('data-animation');

                    obj['type'] = 'row_template';
                    obj['row_class'] = row_class;
                    obj['row_animation'] = row_animation;
                    obj['val'] = [];
                    $(this).find('.column-list .col-row').each(function(col_row_index) {

                        var col_row_id = $(this).attr('id');
                        var no_of_col = $(this).find('.ui-new-section-add .columns').length;
                        var col_row_class = $('input[data-id="' + col_row_id + '"]').attr('data-extclass');
                        var col_row_animation = $('input[data-id="' + col_row_id + '"]').attr('data-animation');

                        obj['val'][col_row_index] = {
                            id: col_row_id,
                            no_of_col: no_of_col,
                            col_row_class: col_row_class,
                            col_row_animation: col_row_animation
                        };

                        obj['val'][col_row_index]['columns'] = [];
                        $(this).find('.ui-new-section-add .columns').each(function(col_index) {

                            var col_id = $(this).attr('id');
                            var extclass = $('input[data-id="' + col_id + '"]').attr('data-extclass');
                            var animation = $('input[data-id="' + col_id + '"]').attr('data-animation');

                            var elementObj = {};

                            $('.' + col_id + ' .cms-element').each(function(element_index) {

                                var ele_id = $(this).data('id');
                                if ($('div[data-editor="' + ele_id + '"]').hasClass('titleOnly')) {

                                    var title = $('#' + ele_id).val();
                                    var eclass = $('#' + ele_id).attr('data-class');
                                    elementObj[element_index] = {
                                        type: 'only_title',
                                        val: {
                                            content: title,
                                            extclass: eclass
                                        }
                                    };

                                } else if ($('div[data-editor="' + ele_id + '"]').hasClass('text-area')) {

                                    var content = $('#' + ele_id).val();
                                    var eclass = $('#' + ele_id).attr('data-class');

                                    elementObj[element_index] = {
                                        type: 'textarea',
                                        val: {
                                            content: content,
                                            extclass: eclass
                                        }
                                    };

                                } else if ($('div[data-editor="' + ele_id + '"]').hasClass('text-accordian')) {

                                    var content = $('#' + ele_id).val();
                                    var caption = $('#' + ele_id).attr('data-title');

                                    elementObj[element_index] = {
                                        type: 'accordianblock',
                                        val: {
                                            content: content,
                                            title: caption
                                        }
                                    };

                                } else if ($('div[data-editor="' + ele_id + '"]').hasClass('img-area')) {

                                    var img = $('#' + ele_id).val();
                                    var source = $('#' + ele_id).prev().attr('src');
                                    var caption = $('#' + ele_id).data('caption');
                                    var extra_class = $('#' + ele_id).data('extra_class');
                                    var data_width = $('#' + ele_id).data('width');
                                    var align = $('#' + ele_id).data('type');

                                    elementObj[element_index] = {
                                        type: 'image',
                                        val: {
                                            title: caption,
                                            image: img,
                                            extra_class: extra_class,
                                            data_width: data_width,
                                            alignment: align,
                                            src: source,
                                        }
                                    };

                                } else if ($('div[data-editor="' + ele_id + '"]').hasClass('img-document')) {

                                    var img = $('#' + ele_id).val();
                                    var source = $('#' + ele_id).prev().attr('src');
                                    var caption = $('#' + ele_id).data('caption');
                                    var doc_date_time = $('#' + ele_id).data('doc_date_time');

                                    elementObj[element_index] = {
                                        type: 'document',
                                        val: {
                                            caption: caption,
                                            doc_date_time: doc_date_time,
                                            document: img,
                                            src: source
                                        }
                                    };

                                } else if ($('div[data-editor="' + ele_id + '"]').hasClass('buttonInfoOnly')) {

                                    var txt = $('#' + ele_id).val();
                                    var caption = $('#' + ele_id).data('caption');
                                    var target = $('#' + ele_id).data('linktarget');
                                    var align = $('#' + ele_id).data('type');

                                    elementObj[element_index] = {
                                        type: 'button_info',
                                        val: {
                                            title: caption,
                                            content: txt,
                                            alignment: align,
                                            target: target,
                                        }
                                    };
                                }

                            });

                            obj['val'][col_row_index]['columns'][col_index] = {
                                id: col_id,
                                column_class: extclass,
                                animation: animation,
                                elementObj: elementObj
                            }
                        });

                    });
                }
                if (obj != undefined && obj != null) {
                    builderObj.push(obj);
                }
            });

            var json = JSON.stringify(builderObj);
            $('#builderObj').val(json);
        },
        spacerTemplate: function(config, edit, configTxt) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);

            var section = '';
            if (edit == 'N') {
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="only-spacer">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item spacerTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><b>Spacer Class</b><label></label></div>';
                section += '<input id="' + iCount + '" data-config="' + config + '"  type="hidden" class="txtip"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><b>Spacer Class</b></div>';
                section += '<input id="' + edit + '"  data-config="' + config + '" type="hidden" class="txtip"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }

        },
        eventsTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, eventscat) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="events-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item eventsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-config="' + config + '" data-class="' + extra_class + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" data-eventscat="' + eventscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-config="' + config + '" data-class="' + extra_class + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" data-eventscat="' + eventscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        newsTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, newscat) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="news-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item newsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" data-newscat="' + newscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-newscat="' + newscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        publicRecordTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, newscat) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="public-record-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item publicRecordTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" data-newscat="' + newscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-newscat="' + newscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        latestNewsTemplate: function(val, template, edit, extra_class) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="latest-news-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item latestNewsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                section += '<input id="' + iCount + '" data-type="' + template + '" data-class="' + extra_class + '"   type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                section += '<input id="' + edit + '" data-type="' + template + '" data-class="' + extra_class + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        QuickLinkTemplate: function(val, template, edit, extra_class, startdate, enddate) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="quick-links-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item QuickLinkTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                section += '<input id="' + iCount + '" data-type="' + template + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" data-class="' + extra_class + '"   type="hidden" class="txtip" value="' + val + '"/>';

                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                section += '<input id="' + edit + '" data-type="' + template + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" data-class="' + extra_class + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        photoalbumTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="photoalbum-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item photoalbumTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '"  data-sdate="' + startdate + '" data-edate="' + enddate + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        videoalbumTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="videogallery-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item videoalbumTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '"  data-sdate="' + startdate + '" data-edate="' + enddate + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        organizationsTemplate: function(val, parentorg, template, extraclass, edit) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="organizations">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item organizationsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                section += '<input id="' + iCount + '" data-type="' + template + '" data-parentorg="' + parentorg + '" data-orgclass="' + extraclass + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></div>';
                section += '<input id="' + edit + '" data-type="' + template + '" data-parentorg="' + parentorg + '" data-orgclass="' + extraclass + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        interconnectionsTemplate: function(val, parentorg, template, extraclass, edit) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="interconnections">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item interconnectionsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                section += '<input id="' + iCount + '" data-type="' + template + '" data-parentorg="' + parentorg + '" data-orgclass="' + extraclass + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></div>';
                section += '<input id="' + edit + '" data-type="' + template + '" data-parentorg="' + parentorg + '" data-orgclass="' + extraclass + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        alertsTemplate: function(val, sectionlimit, extra_class, alertType, template, edit, configTxt, startdate, enddate) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="alerts-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item alertsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (alertType == 1) {
                    var type = 'High';
                } else if (alertType == 2) {
                    var type = 'Medium';
                } else if (alertType == 3) {
                    var type = 'Low';
                } else {
                    var type = '';
                }
                if (alertType != '') {
                    section += '<div class="col-md-12"><b>Alert Type:</b>' + type + '</div>';
                }
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }

                section += '<input id="' + iCount + '" data-class="' + extra_class + '" data-type="' + template + '" data-alerttype="' + alertType + '" data-slimit="' + sectionlimit + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (alertType == 1) {
                    var type = 'High';
                } else if (alertType == 2) {
                    var type = 'Medium';
                } else if (alertType == 3) {
                    var type = 'Low';
                } else {
                    var type = '';
                }
                if (alertType != '') {
                    section += '<div class="col-md-12"><b>Alert Type:</b>' + type + '</div>';
                }
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + edit + '" data-class="' + extra_class + '" data-type="' + template + '" data-alerttype="' + alertType + '" data-slimit="' + sectionlimit + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        departmentTemplate: function(val, sectionlimit, extra_class, template, edit, startdate, enddate) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="department-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item departmentTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + iCount + '" data-class="' + extra_class + '" data-type="' + template + '"  data-slimit="' + sectionlimit + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + edit + '" data-class="' + extra_class + '" data-type="' + template + '"  data-slimit="' + sectionlimit + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        linkTemplate: function(val, sectionlimit, extra_class, template, edit, startdate, enddate, linkcat) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="links-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item linkTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + iCount + '" data-class="' + extra_class + '" data-type="' + template + '"  data-slimit="' + sectionlimit + '" data-linkcat="' + linkcat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + edit + '" data-class="' + extra_class + '" data-type="' + template + '"  data-slimit="' + sectionlimit + '" data-linkcat="' + linkcat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        faqTemplate: function(val, sectionlimit, extra_class, template, edit, startdate, enddate, faqcat) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="faqs-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item faqTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + iCount + '" data-class="' + extra_class + '" data-type="' + template + '"  data-slimit="' + sectionlimit + '" data-faqcat="' + faqcat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + edit + '" data-class="' + extra_class + '" data-type="' + template + '"  data-slimit="' + sectionlimit + '" data-faqcat="' + faqcat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        blogsTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, blogscat) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="blogs-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item blogsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-blogscat="' + blogscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-blogscat="' + blogscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        numberAllocationsTemplate: function(val, sectionlimit, template, edit, extra_class) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="number-allocations">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item numberAllocationsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + iCount + '" data-type="' + template + '" data-class="' + extra_class + '" data-slimit="' + sectionlimit + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + edit + '" data-type="' + template + '" data-class="' + extra_class + '" data-slimit="' + sectionlimit + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        serviceTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, servicecat) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="service-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item serviceTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-servicecat="' + servicecat + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {

                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-servicecat="' + servicecat + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        candwserviceTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="candwservice-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item candwserviceTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        consultationsTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, blogscat) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="consultations-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item consultationsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-blogscat="' + blogscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';
                if (startdate != '' && typeof startdate != 'undefined') {
                    section += '<div class="col-md-12"><b>Start Date:</b>' + startdate + '</div>';
                }
                if (enddate != '' && typeof enddate != 'undefined') {
                    section += '<div class="col-md-12"><b>End Date:</b>' + enddate + '</div>';
                }
                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-blogscat="' + blogscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        complaintservicesTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="complaint-services-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item complaintservicesTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        fmbroadcastingTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="fmbroadcasting-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item fmbroadcastingTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        boardofdirectorsTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="boardofdirectors-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item boardofdirectorsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        registerapplicationTemplate: function(val, sectionlimit, template, edit, extra_class) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="register-application-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item registerapplicationTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }

                section += '<input id="' + iCount + '" data-type="' + template + '" data-class="' + extra_class + '" data-slimit="' + sectionlimit + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + edit + '" data-class="' + extra_class + '" data-slimit="' + sectionlimit + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        licenceregisterTemplate: function(val, sectionlimit, sector, template, edit, sectorTxt, extra_class) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="licence-register-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item licenceregisterTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + iCount + '" data-type="' + template + '" data-class="' + extra_class + '" data-sector="' + sector + '" data-slimit="' + sectionlimit + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                section += '<input id="' + edit + '" data-type="' + template + '" data-class="' + extra_class + '" data-sector="' + sector + '" data-slimit="' + sectionlimit + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        formsandfeesTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="forms-and-fees-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item formsandfeesTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }
                if (sectiondesc != '' && typeof sectiondesc != 'undefined') {
                    section += '<div class="col-md-12"><b>Description:</b>' + sectiondesc + '</div>';
                }
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-class="' + extra_class + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        publicationTemplate: function(val, sectionlimit, config, template, edit, configTxt, sector, extra_class, publicationscat) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="publication-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item publicationTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }

                section += '<input id="' + iCount + '" data-sector="' + sector + '" data-class="' + extra_class + '" data-type="' + template + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-publicationscat="' + publicationscat + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label></div>';

                if (sectionlimit != '' && typeof sectionlimit != 'undefined') {
                    section += '<div class="col-md-12"><b>Limit:</b>' + sectionlimit + '</div>';
                }

                section += '<input id="' + edit + '" data-sector="' + sector + '" data-class="' + extra_class + '" data-type="' + template + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-publicationscat="' + publicationscat + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        promotionsTemplate: function(val, config, template, edit, configTxt, layout) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="promotions-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item promotionsTemplate" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>' + val + '</b></label><ul><li>Template: ' + template + '</li></ul></div>';
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-type="' + template + '" data-config="' + config + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>' + val + '</b></label><ul><li>Template: ' + template + '</li></ul></div>';
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-type="' + template + '" data-config="' + config + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        TwoColumns: function() {
            var section = '';
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            section += '<div class="ui-state-default"><div class="row TwoColumns"><div class="col-sm-12">';
            section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
            section += '<a href="javascript:;" class="close-btn" title="Delete">';
            section += '<i class="action-icon delete fa fa-trash"></i>';
            section += '</a>';
            section += '<div class="ui-new-section-add col-sm-12">';

            section += '<div class="col-sm-6 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_1">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item TwoColumns maintwocol two_col_1" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_1"><a href="javascript:;"  data-innerplus="two_col_1" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '<div class="col-sm-6 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_2">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item TwoColumns maintwocol two_col_2" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_2"><a href="javascript:;" data-innerplus="two_col_2" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div></div>';
            section += '</div>';
            section += '</div></div>';
            if ($('#section-container .ui-state-default').length > 0) {
                $(section).insertAfter($('#section-container .ui-state-default:last'));
            } else {
                $('#section-container').append(section);
            }

        },
        ThreeColumns: function() {
            var section = '';
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            section += '<div class="ui-state-default"><div class="row ThreeColumns"><div class="col-sm-12">';
            section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
            section += '<a href="javascript:;" class="close-btn" title="Delete">';
            section += '<i class="action-icon delete fa fa-trash"></i>';
            section += '</a>';
            section += '<div class="ui-new-section-add col-sm-12">';

            section += '<div class="col-sm-4 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_1">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item ThreeColumns maintwocol two_col_1" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_1"><a href="javascript:;"  data-innerplus="two_col_1" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '<div class="col-sm-4 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_2">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item ThreeColumns maintwocol two_col_2" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_2"><a href="javascript:;" data-innerplus="two_col_2" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '<div class="col-sm-4 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_3">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item ThreeColumns maintwocol two_col_3" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_3"><a href="javascript:;" data-innerplus="two_col_3" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '</div></div>';
            section += '</div></div>';
            if ($('#section-container .ui-state-default').length > 0) {
                $(section).insertAfter($('#section-container .ui-state-default:last'));
            } else {
                $('#section-container').append(section);
            }

        },
        OneThreeColumns: function() {
            var section = '';
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            section += '<div class="ui-state-default"><div class="row OneThreeColumns"><div class="col-sm-12">';
            section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
            section += '<a href="javascript:;" class="close-btn" title="Delete">';
            section += '<i class="action-icon delete fa fa-trash"></i>';
            section += '</a>';
            section += '<div class="ui-new-section-add col-sm-12">';

            section += '<div class="col-sm-3 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_1">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item OneThreeColumns maintwocol two_col_1" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_1"><a href="javascript:;"  data-innerplus="two_col_1" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '<div class="col-sm-9 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_2">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item OneThreeColumns maintwocol two_col_2" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_2"><a href="javascript:;" data-innerplus="two_col_2" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '</div></div>';
            section += '</div></div>';
            if ($('#section-container .ui-state-default').length > 0) {
                $(section).insertAfter($('#section-container .ui-state-default:last'));
            } else {
                $('#section-container').append(section);
            }

        },
        ThreeOneColumns: function() {
            var section = '';
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            section += '<div class="ui-state-default"><div class="row ThreeOneColumns"><div class="col-sm-12">';
            section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
            section += '<a href="javascript:;" class="close-btn" title="Delete">';
            section += '<i class="action-icon delete fa fa-trash"></i>';
            section += '</a>';
            section += '<div class="ui-new-section-add col-sm-12">';

            section += '<div class="col-sm-9 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_1">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item ThreeOneColumns maintwocol two_col_1" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_1"><a href="javascript:;"  data-innerplus="two_col_1" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '<div class="col-sm-3 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_2">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item ThreeOneColumns maintwocol two_col_2" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_2"><a href="javascript:;" data-innerplus="two_col_2" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '</div></div>';
            section += '</div></div>';
            if ($('#section-container .ui-state-default').length > 0) {
                $(section).insertAfter($('#section-container .ui-state-default:last'));
            } else {
                $('#section-container').append(section);
            }

        },
        FourColumns: function() {

            var section = '';
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            section += '<div class="ui-state-default"><div class="row FourColumns"><div class="col-sm-12">';
            section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
            section += '<a href="javascript:;" class="close-btn" title="Delete">';
            section += '<i class="action-icon delete fa fa-trash"></i>';
            section += '</a>';
            section += '<div class="ui-new-section-add col-sm-12">';

            section += '<div class="col-sm-3 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_1">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item FourColumns maintwocol two_col_1" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_1"><a href="javascript:;"  data-innerplus="two_col_1" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '<div class="col-sm-3 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_2">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item FourColumns maintwocol two_col_2" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_2"><a href="javascript:;" data-innerplus="two_col_2" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '<div class="col-sm-3 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_3">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item FourColumns maintwocol two_col_3" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_3"><a href="javascript:;" data-innerplus="two_col_3" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '<div class="col-sm-3 col_1">';
            section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="columnstwo columns_4">';
            section += '<i class="action-icon edit ri-pencil-line"></i>';
            section += '</a>';
            section += '<div class="clearfix"></div>';
            section += '<div class="defoult-module module section-item FourColumns maintwocol two_col_4" data-editor="' + iCount + '">';
            section += '<div class="ui-new-section-add add-element hidecol" data-innerplus="two_col_4"><a href="javascript:;" data-innerplus="two_col_4" class="add-icon add-element"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            section += '<div class="twocol1"></div>';
            section += '</div>';
            section += '</div>';

            section += '</div></div>';
            section += '</div></div>';
            if ($('#section-container .ui-state-default').length > 0) {
                $(section).insertAfter($('#section-container .ui-state-default:last'));
            } else {
                $('#section-container').append(section);
            }

        },
        onlyTitle: function(val, extClass, edit) {

            var iCount = 'item-' + ($('.ui-state-default').length + 1);

            if ($('.add-cms-block').hasClass('clicked')) {

                var section = '';
                var val1 = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                var col_id = $('.add-cms-block.clicked').data('id');
                var ele_count = $('.' + col_id + ' .cms-element').length + 1;

                if (edit != 'N') {


                    section += '<div class="col-md-12">' + val + '</div>';
                    section += '<input id="' + edit + '" data-class="' + extClass + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';

                    $('div[data-editor=' + edit + ']').html(section);

                } else {

                    section += '<div class="cms-element" data-id="' + col_id + '-element-' + ele_count + '">';
                    section += '<a href="javascript:;" title="Delete" data-id="' + col_id + '-element-' + ele_count + '" class="delete-element">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + col_id + '-element-' + ele_count + '" class="only-title">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="ui-new-section-add titleOnly" data-editor="' + col_id + '-element-' + ele_count + '">';
                    section += '<div class="col-md-12">' + val + '</div>';
                    section += '<input id="' + col_id + '-element-' + ele_count + '" data-class="' + extClass + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    section += '</div>';
                    $('.' + col_id).append(section);

                }

            } else {

                var section = '';
                var val1 = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                if (edit == 'N') {
                    section += '<div class="ui-state-default">';
                    section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                    section += '<a href="javascript:;" class="close-btn" title="Delete">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="only-title hideclass">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="defoult-module module section-item titleOnly" data-editor="' + iCount + '">';
                    section += '<div class="col-md-12">' + val + '</div>';
                    section += '<input id="' + iCount + '" data-class="' + extClass + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    section += '</div>';

                    if ($('#section-container .ui-state-default').length > 0) {
                        $(section).insertAfter($('#section-container .ui-state-default:last'));
                    } else {
                        $('#section-container').append(section);
                    }
                } else {
                    section += '<div class="col-md-12">' + val + '</div>';
                    section += '<input id="' + edit + '" data-class="' + extClass + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    $('#section-container').find('div[data-editor=' + edit + ']').html(section);
                }
            }
        },
        addTextArea: function(val, extClass, edit) {

            if ($('.add-cms-block').hasClass('clicked')) {

                var section = '';
                var val1 = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                var col_id = $('.add-cms-block.clicked').data('id');
                var ele_count = $('.' + col_id + ' .cms-element').length + 1;

                if (edit != 'N') {

                    section += '<div class="col-md-12">' + val + '</div>';
                    section += '<input id="' + edit + '" data-class="' + extClass + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    $('div[data-editor=' + edit + ']').html(section);

                } else {
                    section += '<div class="cms-element" data-id="' + col_id + '-element-' + ele_count + '">';
                    section += '<a href="javascript:;" title="Delete" data-id="' + col_id + '-element-' + ele_count + '" class="delete-element">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + col_id + '-element-' + ele_count + '" class="text-block">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="ui-new-section-add text-area" data-editor="' + col_id + '-element-' + ele_count + '">';
                    section += '<div class="col-md-12">' + val + '</div>';
                    section += '<input id="' + col_id + '-element-' + ele_count + '" data-class="' + extClass + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    section += '</div>';
                    $('.' + col_id).append(section);
                }

            } else {

                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                var section = '';
                var val1 = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                if (edit == 'N') {

                    section += '<div class="ui-state-default">';
                    section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                    section += '<a href="javascript:;" class="close-btn" title="Delete">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="text-block hideclass">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="defoult-module module section-item text-area" data-editor="' + iCount + '">';
                    section += '<div class="col-md-12">' + val + '</div>';
                    section += '<input id="' + iCount + '" data-class="' + extClass + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    section += '</div>';

                    if ($('#section-container .ui-state-default').length > 0) {
                        $(section).insertAfter($('#section-container .ui-state-default:last'));
                    } else {
                        $('#section-container').append(section);
                    }
                } else {
                    section += '<div class="col-md-12">' + val + '</div>';
                    section += '<input id="' + edit + '" data-class="' + extClass + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    $('#section-container').find('div[data-editor=' + edit + ']').html(section);
                }
            }
        },
        addAccordianblock: function(val, title, edit) {

            if ($('.add-cms-block').hasClass('clicked')) {

                var section = '';
                var val1 = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                var col_id = $('.add-cms-block.clicked').data('id');
                var ele_count = $('.' + col_id + ' .cms-element').length + 1;

                if (edit != 'N') {

                    section += '<div class="col-md-12"><b>Title:</b>' + title + '</div>';
                    section += '<div class="col-md-12"><b>Content:</b>' + val + '</div>';
                    section += '<input id="' + edit + '" data-title="' + title + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    $('div[data-editor=' + edit + ']').html(section);

                } else {

                    section += '<div class="cms-element" data-id="' + col_id + '-element-' + ele_count + '">';
                    section += '<a href="javascript:;" title="Delete" data-id="' + col_id + '-element-' + ele_count + '" class="delete-element">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + col_id + '-element-' + ele_count + '" class="accordian-block">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="ui-new-section-add text-accordian" data-editor="' + col_id + '-element-' + ele_count + '">';
                    section += '<div class="col-md-12"><b>Title:</b>' + title + '</div>';
                    section += '<div class="col-md-12"><b>Content:</b>' + val + '</div>';
                    section += '<input id="' + col_id + '-element-' + ele_count + '" data-title="' + title + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    section += '</div>';

                    $('.' + col_id).append(section);
                }

            } else {

                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                var section = '';
                var val1 = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                if (edit == 'N') {

                    section += '<div class="ui-state-default">';
                    section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                    section += '<a href="javascript:;" class="close-btn" title="Delete">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="accordian-block hideclass">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="defoult-module module section-item text-accordian" data-editor="' + iCount + '">';
                    section += '<div class="col-md-12"><b>Title:</b>' + title + '</div>';
                    section += '<div class="col-md-12"><b>Content:</b>' + val + '</div>';
                    section += '<input id="' + iCount + '" data-title="' + title + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    section += '</div>';

                    if ($('#section-container .ui-state-default').length > 0) {
                        $(section).insertAfter($('#section-container .ui-state-default:last'));
                    } else {
                        $('#section-container').append(section);
                    }
                } else {
                    section += '<div class="col-md-12"><b>Title:</b>' + title + '</div>';
                    section += '<div class="col-md-12"><b>Content:</b>' + val + '</div>';
                    section += '<input id="' + edit + '" data-title="' + title + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                    section += '<div class="clearfix"></div>';
                    $('#section-container').find('div[data-editor=' + edit + ']').html(section);
                }
            }

        },
        addImage: function(imgsrc, val, imgCaption, extraClass, type, edit, folderid, data_width) {

            if ($('.add-cms-block').hasClass('clicked')) {

                var section = '';
                val = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                var col_id = $('.add-cms-block.clicked').data('id');
                var ele_count = $('.' + col_id + ' .cms-element').length + 1;

                if (edit != 'N') {

                    section += '<div class="team_box">';
                    section += '<div class="thumbnail_container">';
                    section += '<div class="thumbnail">';
                    section += '<img src="' + imgsrc + '">';
                    section += '<input id="' + edit + '" data-caption="' + imgCaption + '" data-extra_class="' + extraClass + '" data-type="' + type + '"  data-folderid="' + folderid + '" data-width="' + data_width + '" type="hidden" class="imgip" value="' + val + '"/>';
                    section += '</div>';
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="title-img">';
                    section += '<p>' + imgCaption + '</p>';
                    section += '</div>';
                    if (extraClass != null || extraClass != '') {
                        section += '<div class="extraClass-img">';
                        section += '<h5>Extra Class: ' + extraClass + '</h5>';
                        section += '</div>';
                    }
                    section += '<div class="clearfix"></div>';

                    $('div[data-editor=' + edit + ']').html(section);

                } else {

                    section += '<div class="cms-element" data-id="' + col_id + '-element-' + ele_count + '">';
                    section += '<a href="javascript:;" title="Delete" data-id="' + col_id + '-element-' + ele_count + '" class="delete-element">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + col_id + '-element-' + ele_count + '" class="only-image">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="ui-new-section-add img-area" data-editor="' + col_id + '-element-' + ele_count + '">';
                    section += '<div class="team_box">';
                    section += '<div class="thumbnail_container">';
                    section += '<div class="thumbnail">';
                    section += '<img src="' + imgsrc + '">';
                    section += '<input id="' + col_id + '-element-' + ele_count + '" data-caption="' + imgCaption + '" data-extra_class="' + extraClass + '" data-type="' + type + '" data-folderid="' + folderid + '" data-width="' + data_width + '" type="hidden" class="imgip" value="' + val + '"/>';
                    section += '</div>';
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="title-img">';
                    section += '<p>' + imgCaption + '</p>';
                    section += '</div>';
                    if (extraClass != null || extraClass != '') {
                        section += '<div class="extraClass-img">';
                        section += '<h5>Extra Class: ' + extraClass + '</h5>';
                        section += '</div>';
                    }
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    $('.' + col_id).append(section);
                }

            } else {

                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                var section = '';
                val = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');

                if (edit == 'N') {
                    section += '<div class="ui-state-default">';
                    section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                    section += '<a href="javascript:;" class="close-btn" title="Delete">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="only-image hideclass">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="section-item img-area" data-editor="' + iCount + '">';
                    section += '<div class="col-md-10 col-xs-small">';
                    section += '<div class="team_box">';
                    section += '<div class="thumbnail_container">';
                    section += '<div class="thumbnail">';
                    section += '<img src="' + imgsrc + '">';
                    section += '<input id="' + iCount + '" data-caption="' + imgCaption + '" data-extra_class="' + extraClass + '" data-type="' + type + '" data-folderid="' + folderid + '" data-width="' + data_width + '" type="hidden" class="imgip" value="' + val + '"/>';
                    section += '</div>';
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="title-img">';
                    section += '<h3>' + imgCaption + '</h3>';
                    section += '</div>';
                    if (extraClass != null || extraClass != '') {
                        section += '<div class="extraClass-img">';
                        section += '<h5>Extra Class: ' + extraClass + '</h5>';
                        section += '</div>';
                    }
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    section += '</div>';
                    if ($('#section-container .ui-state-default').length > 0) {
                        $(section).insertAfter($('#section-container .ui-state-default:last'));
                    } else {
                        $('#section-container').append(section);
                    }
                } else {

                    section += '<div class="col-md-12 col-xs-small">';
                    section += '<div class="team_box">';
                    section += '<div class="thumbnail_container">';
                    section += '<div class="thumbnail">';
                    section += '<img src="' + imgsrc + '">';
                    section += '<input id="' + edit + '" data-caption="' + imgCaption + '" data-extra_class="' + extraClass + '" data-type="' + type + '"  data-folderid="' + folderid + '" data-width="' + data_width + '" type="hidden" class="imgip" value="' + val + '"/>';
                    section += '</div>';
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="title-img">';
                    section += '<h3>' + imgCaption + '</h3>';
                    section += '</div>';
                    if (extraClass != null || extraClass != '') {
                        section += '<div class="extraClass-img">';
                        section += '<h5>Extra Class: ' + extraClass + '</h5>';
                        section += '</div>';
                    }
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    $('#section-container').find('div[data-editor=' + edit + ']').html(section);
                }
            }
        },
        addDocument: function(val, edit, folderid, caption, doc_date_time) {

            if ($('.add-cms-block').hasClass('clicked')) {

                var section = '';
                var col_id = $('.add-cms-block.clicked').data('id');
                var ele_count = $('.' + col_id + ' .cms-element').length + 1;

                if (edit != 'N') {

                    section += '<div class="docdatahtml"></div>';
                    section += '<input id="' + edit + '"  type="hidden" class="imgip" data-caption="' + caption + '" data-doc_date_time="' + doc_date_time + '" value="' + val + '"/>';
                    section += '<div class="clearfix"></div>';
                    var doccopid = val;
                    var DOC_URL = site_url + "/powerpanel/media/ComposerDocDatajs";
                    $.ajax({
                        type: 'POST',
                        url: DOC_URL,
                        data: 'id=' + doccopid + '',
                        success: function(html) {
                            $('#' + edit).parents('div[data-editor="' + edit + '"]').find(".docdatahtml").html(html);
                            $.loader.close(true);
                        }
                    });
                    $('div[data-editor=' + edit + ']').html(section);

                } else {

                    section += '<div class="cms-element" data-id="' + col_id + '-element-' + ele_count + '">';
                    section += '<a href="javascript:;" title="Delete" data-id="' + col_id + '-element-' + ele_count + '" class="delete-element">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + col_id + '-element-' + ele_count + '"   class="only-document">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="ui-new-section-add img-document" data-editor="' + col_id + '-element-' + ele_count + '">';
                    section += '<div class="docdatahtml"></div>';
                    section += '<input id="' + col_id + '-element-' + ele_count + '"  type="hidden" class="imgip" data-caption="' + caption + '" data-doc_date_time="' + doc_date_time + '" data-folderid="' + folderid + '" value="' + val + '"/>';
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';

                    var doccopid = val;
                    var DOC_URL = site_url + "/powerpanel/media/ComposerDocDatajs";

                    $.ajax({
                        type: 'POST',
                        url: DOC_URL,
                        data: 'id=' + doccopid + '',
                        success: function(html) {
                            $('#' + col_id + '-element-' + ele_count).parents('div[data-editor="' + col_id + '-element-' + ele_count + '"]').find(".docdatahtml").html(html);
                            $.loader.close(true);
                        }
                    });
                    $('.' + col_id).append(section);

                }

            } else {

                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                var section = '';
                val = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                if (edit == 'N') {

                    section += '<div class="ui-state-default">';
                    section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                    section += '<a href="javascript:;" class="close-btn" title="Delete">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '"   class="only-document">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="section-item img-document" data-editor="' + iCount + '">';
                    section += '<div class="docdatahtml"></div>';
                    section += '<input id="' + iCount + '"  type="hidden" class="imgip" data-caption="' + caption + '" data-doc_date_time="' + doc_date_time + '" data-folderid="' + folderid + '" value="' + val + '"/>';
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    section += '</div>';
                    if ($('#section-container .ui-state-default').length > 0) {
                        $(section).insertAfter($('#section-container .ui-state-default:last'));
                    } else {
                        $('#section-container').append(section);
                    }

                    var doccopid = val;
                    var DOC_URL = site_url + "/powerpanel/media/ComposerDocDatajs";
                    $.ajax({
                        type: 'POST',
                        url: DOC_URL,
                        data: 'id=' + doccopid + '',
                        success: function(html) {
                            $('#' + iCount).parents('.section-item').find(".docdatahtml").html(html);
                        }
                    });
                } else {
                    section += '<input id="' + edit + '"  type="hidden" class="imgip" data-caption="' + caption + '" data-doc_date_time="' + doc_date_time + '" value="' + val + '"/>';
                    section += '<div class="docdatahtml"></div>';
                    section += '<div class="clearfix"></div>';
                    $('#section-container').find('div[data-editor=' + edit + ']').html(section);

                    var doccopid = val;
                    var DOC_URL = site_url + "/powerpanel/media/ComposerDocDatajs";
                    $.ajax({
                        type: 'POST',
                        url: DOC_URL,
                        data: 'id=' + doccopid + '',
                        success: function(html) {
                            $('#' + edit).parents('.section-item').find(".docdatahtml").html(html);
                        }
                    });
                }
            }
        },
        onlySectionButton: function(val, linktarget, buttonlink, type, edit) {

            if ($('.add-cms-block').hasClass('clicked')) {

                var section = '';
                var col_id = $('.add-cms-block.clicked').data('id');
                var ele_count = $('.' + col_id + ' .cms-element').length + 1;

                if (edit != 'N') {

                    section += '<div class="col-md-12"><b>Button Information</b></div><br/><br/>';
                    section += '<div class="col-md-10"><b>Caption:</b>' + val + '<br/>';
                    section += '<b>Link:</b>' + buttonlink + '<br/>';
                    if (linktarget == "_blank") {
                        section += '<b>Link Target:</b>New Window' + '<br/>';
                    } else {
                        section += '<b>Link Target:</b>Same Window' + '<br/>';
                    }
                    section += '</div>';
                    section += '<div class="col-md-2 text-right">';
                    section += '<div class="image-align-box">';
                    section += '<h5 class="title">Preview</h5>';
                    if (type == 'button-lft-txt') {
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/left-button.png" alt=""></i>';
                    } else if (type == 'button-rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/right-button.png" alt=""></i>';
                    } else if (type == 'button-center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/center-button.png" alt=""></i>';
                    }
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    section += '<input id="' + edit + '" data-caption="' + val + '" data-linktarget="' + linktarget + '" data-type="' + type + '" type="hidden" class="txtip" value="' + buttonlink + '"/>';
                    section += '<div class="clearfix"></div>';

                    $('div[data-editor=' + edit + ']').html(section);

                } else {

                    section += '<div class="cms-element" data-id="' + col_id + '-element-' + ele_count + '">';
                    section += '<a href="javascript:;" title="Delete" data-id="' + col_id + '-element-' + ele_count + '" class="delete-element">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + col_id + '-element-' + ele_count + '" class="section-button">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="ui-new-section-add buttonInfoOnly" data-editor="' + col_id + '-element-' + ele_count + '">';
                    section += '<div class="col-md-12"><b>Button Information</b></div><br/><br/>';
                    section += '<div class="col-md-10"><b>Title:</b>' + val + '<br/>';
                    section += '<b>Link:</b>' + buttonlink + '<br/>';

                    if (linktarget == "_blank") {
                        section += '<b>Target:</b>New Window' + '<br/>';
                    } else {
                        section += '<b>Link Target:</b>Same Window' + '<br/>';
                    }
                    section += '</div>';
                    section += '<div class="col-md-2 text-right">';
                    section += '<div class="image-align-box">';
                    section += '<h5 class="title">Preview</h5>';
                    if (type == 'button-lft-txt') {
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/left-button.png" alt=""></i>';
                    } else if (type == 'button-rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/right-button.png" alt=""></i>';
                    } else if (type == 'button-center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/center-button.png" alt=""></i>';
                    }
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    section += '<input id="' + col_id + '-element-' + ele_count + '" data-caption="' + val + '" data-linktarget="' + linktarget + '" data-type="' + type + '" type="hidden" class="txtip" value="' + buttonlink + '"/>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    section += '</div>';

                    $('.' + col_id).append(section);

                }

            } else {

                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                var section = '';
                if (edit == 'N') {

                    section += '<div class="ui-state-default">';
                    section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                    section += '<a href="javascript:;" class="close-btn" title="Delete">';
                    section += '<i class="action-icon delete fa fa-trash"></i>';
                    section += '</a>';
                    section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="section-button hideclass">';
                    section += '<i class="action-icon edit ri-pencil-line"></i>';
                    section += '</a>';
                    section += '<div class="clearfix"></div>';
                    section += '<div class="defoult-module module section-item buttonInfoOnly" data-editor="' + iCount + '">';
                    section += '<div class="col-md-12"><b>Button Information</b></div><br/><br/>';
                    section += '<div class="col-md-10"><b>Title:</b>' + val + '<br/>';
                    section += '<b>Link:</b>' + buttonlink + '<br/>';

                    if (linktarget == "_blank") {
                        section += '<b>Target:</b>New Window' + '<br/>';
                    } else {
                        section += '<b>Link Target:</b>Same Window' + '<br/>';
                    }
                    section += '</div>';
                    section += '<div class="col-md-2 text-right">';
                    section += '<div class="image-align-box">';
                    section += '<h5 class="title">Preview</h5>';
                    if (type == 'button-lft-txt') {
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/left-button.png" alt=""></i>';
                    } else if (type == 'button-rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/right-button.png" alt=""></i>';
                    } else if (type == 'button-center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/center-button.png" alt=""></i>';
                    }
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="clearfix"></div>';

                    section += '<input id="' + iCount + '" data-caption="' + val + '" data-linktarget="' + linktarget + '" data-type="' + type + '" type="hidden" class="txtip" value="' + buttonlink + '"/>';
                    section += '<div class="clearfix"></div>';
                    section += '</div>';
                    section += '</div>';

                    if ($('#section-container .ui-state-default').length > 0) {
                        $(section).insertAfter($('#section-container .ui-state-default:last'));
                    } else {
                        $('#section-container').append(section);
                    }
                } else {
                    section += '<div class="col-md-12"><b>Button Information</b></div><br/><br/>';
                    section += '<div class="col-md-10"><b>Caption:</b>' + val + '<br/>';
                    section += '<b>Link:</b>' + buttonlink + '<br/>';
                    if (linktarget == "_blank") {
                        section += '<b>Link Target:</b>New Window' + '<br/>';
                    } else {
                        section += '<b>Link Target:</b>Same Window' + '<br/>';
                    }
                    section += '</div>';
                    section += '<div class="col-md-2 text-right">';
                    section += '<div class="image-align-box">';
                    section += '<h5 class="title">Preview</h5>';
                    if (type == 'button-lft-txt') {
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/left-button.png" alt=""></i>';
                    } else if (type == 'button-rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/right-button.png" alt=""></i>';
                    } else if (type == 'button-center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/center-button.png" alt=""></i>';
                    }
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="clearfix"></div>';

                    section += '<input id="' + edit + '" data-caption="' + val + '" data-linktarget="' + linktarget + '" data-type="' + type + '" type="hidden" class="txtip" value="' + buttonlink + '"/>';
                    section += '<div class="clearfix"></div>';
                    $('#section-container').find('div[data-editor=' + edit + ']').html(section);
                }
            }
        },
        addCustomSection: function(title, template, extclass, layoutType, subtitle) {

            var edit = $('#frmCustomSectionBase input[name=editing]').val() != '' ? $('#frmCustomSectionBase input[name=editing]').val() : 'N';
            var iCount = 'item-' + ($('.ui-state-default').length + 1);

            var section = '';

            if (edit == 'N') {
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="custom-section">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module module section-item custom-section-module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label class="section-head"><b>' + title + '</b></label>';
                section += '<ul class="record-list" id="record-list-' + iCount + '">';
                section += '</ul>';
                section += '<a data-id="' + iCount + '" title="Add custom record" class="add-custom-record" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add custom record';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-config="' + configuration + '" data-layout="' + layoutType + '" data-subtitle="' + subtitle + '" data-extclass="' + extclass + '" type="hidden" class="txtip" value="' + title + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {

                $('.custom-section-module[data-editor=' + edit + '] div label').html('<b>' + title + '</b>');
                $('#' + edit).val(title);
                $('#' + edit).val(subtitle);
                $('#' + edit).attr('data-extclass', extclass);
                $('#' + edit).attr('data-layout', layoutType);

            }

            $('#no-content').addClass('d-none');
            $('#has-content').removeClass('d-none');
            $('#customSectionBase').modal('hide');


        },
        onlySectionContactInfo: function(section_address, section_email, section_phone, section_otherinfo, edit) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);

            var section = '';
            var val1 = section_otherinfo.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
            if (edit == 'N') {

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="contact-info hideclass">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module module section-item contactInfoOnly" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><b>Contact Information</b></div><br/><br/>';
                section += '<div class="col-md-12"><b>Address:</b>' + section_address + '</div>';
                section += '<div class="col-md-12"><b>Email:</b>' + section_email + '</div>';
                section += '<div class="col-md-12"><b>Phone #:</b>' + section_phone + '</div>';
                section += '<div class="col-md-12"><b>Other Info:</b>' + section_otherinfo + '</div>';
                section += '<input id="' + iCount + '" data-address="' + section_address + '" data-email="' + section_email + '" data-phone="' + section_phone + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><b>Contact Information</b></div><br/><br/>';
                section += '<div class="col-md-12"><b>Address:</b>' + section_address + '</div>';
                section += '<div class="col-md-12"><b>Email:</b>' + section_email + '</div>';
                section += '<div class="col-md-12"><b>Phone #:</b>' + section_phone + '</div>';
                section += '<div class="col-md-12"><b>Other Info:</b>' + section_otherinfo + '</div>';
                section += '<input id="' + edit + '" data-address="' + section_address + '" data-email="' + section_email + '" data-phone="' + section_phone + '" type="hidden" class="txtip" value="' + val1 + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }

        },
        addCustomRecord: function(sectionid, imgid, imgsrc, title, link, desc, edit) {

            if (typeof edit == 'undefined' || edit == '') {
                var iCount = $('.ui-state-default').length;
                var recCount = $('#record-list-' + sectionid + ' li').length + 1;
                var crecid = 'crec-item-' + iCount + '-' + recCount;
                var section = '<li id="' + crecid + '">';
                section += title;
                section += '<a href="javascript:;" data-id="' + sectionid + '" data-img="' + imgid + '" data-imgsrc="' + imgsrc + '" data-title="' + title + '" data-link="' + link + '" data-desc="' + desc + '" data-mode="edit" class="add-custom-record" title="edit"> <i class="ri-pencil-line" aria-hidden="true"></i> </a>';
                section += '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a>';
                section += '</li>';
                $('#record-list-' + sectionid).append(section);
            } else {
                section = title;
                section += '<a href="javascript:;" data-id="' + sectionid + '" data-img="' + imgid + '" data-imgsrc="' + imgsrc + '" data-title="' + title + '" data-link="' + link + '" data-desc="' + desc + '" data-mode="edit" class="add-custom-record" title="edit"> <i class="ri-pencil-line" aria-hidden="true"></i> </a>';
                section += '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a>';
                $('#' + edit).html(section);
            }

            $('.record-list').each(function() {
                if ($(this).hasClass('ui-sortable')) {
                    $(this).sortable('destroy');
                }
            });
            $(".record-list").sortable().disableSelection();
            $('#customSection').modal('hide');

        },

        onlyVideo: function(val, title, videoType, edit) {
            var section = '';

            var vidIco = videoType == 'Vimeo' ?
                '<a title="' + val + '" target="_blank" href="' + val + '"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' :
                '<a title="' + val + '" target="_blank" href="' + val + '"><i class="fa fa-youtube" aria-hidden="true"></i></a>';

            val = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
            var iCount = 'item-' + ($('.ui-state-default').length + 1);

            if (edit == 'N') {

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="only-video hideclass">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module module section-item videoOnly" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><strong>' + title + '</strong> - ' + vidIco + '</div>';
                section += '<input id="' + iCount + '" data-caption="' + title + '" data-type="' + videoType + '" type="hidden" class="vidip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><strong>' + title + '</strong> - ' + vidIco + '</div>';
                section += '<input id="' + edit + '" data-caption="' + title + '" data-type="' + videoType + '" type="hidden" class="vidip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }

        },
        addTwoTextArea: function(leftval, rightval, edit) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);

            var section = '';
            var leftval1 = leftval.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
            var rightval1 = rightval.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
            if (edit == 'N') {

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="two-part-content hideclass">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module module section-item two-part" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><div class="col-md-6"><strong> Left Side Content </strong>' + leftval + '</div>';
                section += '<div class="col-md-6"><strong> Right Side Content </strong>' + rightval + '</div></div>';
                section += '<input id="' + iCount + '" data-content="' + rightval1 + '" type="hidden" class="txtip" value="' + leftval1 + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><div class="col-md-6"><strong> Left Side Content </strong>' + leftval + '</div>';
                section += '<div class="col-md-6"><strong> Right Side Content </strong>' + rightval + '</div></div>';
                section += '<input id="' + edit + '" data-content="' + rightval1 + '" type="hidden" class="txtip" value="' + leftval1 + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }

        },
        addMap: function(latitude, longitude, edit) {

            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            var section = '';
            if (edit == 'N') {

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="google-map hideclass">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item img-map" data-editor="' + iCount + '">';
                section += '<div class="col-md-12 col-xs-small">';
                section += '<div class="team_box">';
                section += '<input id="' + iCount + '" data-latitude="' + latitude + '" data-longitude="' + longitude + '" type="hidden" class="imgip"/>';
                section += '</div>';
                section += '<div class="title-img">';
                section += '<h3>Latitude: ' + latitude + '</h3><br/>';
                section += '<h3>Longitude: ' + longitude + '</h3>';
                section += '</div>';
                section += '</div>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';
                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12 col-xs-small">';
                section += '<div class="team_box">';
                section += '<input id="' + edit + '" data-latitude="' + latitude + '" data-longitude="' + longitude + '" type="hidden" class="imgip"/>';
                section += '</div>';
                section += '<div class="title-img">';
                section += '<h3>Latitude: ' + latitude + '</h3><br/>';
                section += '<h3>Longitude: ' + longitude + '</h3>';
                section += '</div>';
                section += '</div>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }

        },
        addImageWithContent: function(imgsrc, val, imgCaption, content, type, edit, folderid) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);

            var section = '';
            var content1 = content.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
            if (edit == 'N') {

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="image-with-information hideclass">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item  img-rt-area" data-editor="' + iCount + '">';
                section += '<div class="col-md-10 col-xs-small">';
                section += '<div class="team_box">';
                section += '<div class="thumbnail_container">';
                section += '<div class="thumbnail">';
                section += '<img src="' + imgsrc + '">';
                section += '<input id="' + iCount + '" data-id="' + val + '" data-type="' + type + '" data-folderid="' + folderid + '" data-caption="' + imgCaption + '" type="hidden" class="imgip" value="' + content1 + '"/>';
                section += '</div>';
                section += '</div>';
                section += '</div>';
                section += '<div class="title-img">';
                section += '<h3>' + imgCaption + '</h3>';
                section += content;
                section += '</div>';
                section += '</div>';
                section += '<div class="col-md-2 text-right">';
                section += '<div class="image-align-box">';
                section += '<h5 class="title">Preview</h5>';
                if (type == 'lft-txt') {
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/left-image.png" alt=""></i>';
                } else if (type == 'rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/right-image.png" alt=""></i>';
                } else if (type == 'top-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/top-image.png" alt=""></i>';
                } else if (type == 'bot-txt') {
                    section += '<i class="icon"><img height="45" title="Align Bottom" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/bottom-image.png" alt=""></i>';
                } else if (type == 'center-txt') {
                    section += '<i class="icon"><img height="45" title="Align Center" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/center-image.png" alt=""></i>';
                }

                section += '</div>';
                section += '</div>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-10 col-xs-small">';
                section += '<div class="team_box">';
                section += '<div class="thumbnail_container">';
                section += '<div class="thumbnail">';
                section += '<img src="' + imgsrc + '">';
                section += '<input id="' + edit + '" data-id="' + val + '" data-type="' + type + '" data-folderid="' + folderid + '" data-caption="' + imgCaption + '" type="hidden" class="imgip" value="' + content1 + '"/>';
                section += '</div>';
                section += '</div>';
                section += '</div>';
                section += '<div class="title-img">';
                section += '<h3>' + imgCaption + '</h3>';
                section += content;
                section += '</div>';
                section += '</div>';
                section += '<div class="col-md-2 text-right">';
                section += '<div class="image-align-box">';
                section += '<h5 class="title">Preview</h5>';
                if (type == 'lft-txt') {
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/left-image.png" alt=""></i>';
                } else if (type == 'rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/right-image.png" alt=""></i>';
                } else if (type == 'top-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/top-image.png" alt=""></i>';
                } else if (type == 'bot-txt') {
                    section += '<i class="icon"><img height="45" title="Align Bottom" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/bottom-image.png" alt=""></i>';
                } else if (type == 'center-txt') {
                    section += '<i class="icon"><img height="45" title="Align Center" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/center-image.png" alt=""></i>';
                }

                section += '</div>';
                section += '</div>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }

        },

        addVideoWithContent: function(title, val, videoType, content, type, edit) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);

            var section = '';
            var vidIco = videoType == 'Vimeo' ?
                '<a title="' + val + '" target="_blank" href="' + val + '"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' :
                '<a title="' + val + '" target="_blank" href="' + val + '"><i class="fa fa-youtube" aria-hidden="true"></i></a>';
            var content1 = content.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
            if (edit == 'N') {

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="video-with-information hideclass">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module module section-item videoContent" data-editor="' + iCount + '">';
                section += '<div class="col-md-10 col-xs-small">';
                section += '' + vidIco + ' <strong>' + title + '</strong>';
                section += '<input id="' + iCount + '" data-caption="' + title + '" data-id="' + val + '" data-type="' + videoType + '" data-aligntype="' + type + '" type="hidden" class="vidip" value="' + content1 + '"/>';
                section += '<div class="title-img">';
                section += content;
                section += '</div></div>';
                section += '<div class="col-md-2 text-right">';
                section += '<div class="image-align-box">';
                section += '<h5 class="title">Preview</h5>';
                if (type == 'lft-txt') {
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/left-video.png" alt=""></i>';
                } else if (type == 'rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/right-video.png" alt=""></i>';
                } else if (type == 'top-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/top-video.png" alt=""></i>';
                } else if (type == 'bot-txt') {
                    section += '<i class="icon"><img height="45" title="Align Bottom" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/bottom-video.png" alt=""></i>';
                } else if (type == 'center-txt') {
                    section += '<i class="icon"><img height="45" title="Align Center" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/center-video.png" alt=""></i>';
                }
                section += '</div>';
                section += '</div></div>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-10 col-xs-small">';
                section += '' + vidIco + ' <strong>' + title + '</strong>';
                section += '<input id="' + iCount + '" data-caption="' + title + '" data-id="' + val + '" data-type="' + videoType + '" data-aligntype="' + type + '" type="hidden" class="vidip" value="' + content1 + '"/>';
                section += '<div class="title-img">';
                section += content;
                section += '</div></div>';
                section += '<div class="col-md-2 text-right">';
                section += '<div class="image-align-box">';
                section += '<h5 class="title">Preview</h5>';
                if (type == 'lft-txt') {
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/left-video.png" alt=""></i>';
                } else if (type == 'rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/right-video.png" alt=""></i>';
                } else if (type == 'top-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/top-video.png" alt=""></i>';
                } else if (type == 'bot-txt') {
                    section += '<i class="icon"><img height="45" title="Align Bottom" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/bottom-video.png" alt=""></i>';
                } else if (type == 'center-txt') {
                    section += '<i class="icon"><img height="45" title="Align Center" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/center-video.png" alt=""></i>';
                }

                section += '</div>';
                section += '</div>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }

        },

        addGalleryImage: function(images, imgPosition, edit) {

            var section = '';
            var caption = 'Image Gallery';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="image-gallery">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module img-gallery-section" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list img-gallery">';
                $.each(images, function(index, value) {
                    section += '<li data-id="' + index + '" id="' + index + '-item-' + iCount + '"><img height="50" width="50" src="' + value + '"/><a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '</div>';
                section += '<input class="imgip" id="' + iCount + '" type="hidden" data-layout="' + imgPosition + '" data-caption="' + caption + '" data-type="gallery" value="gallery" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list img-gallery">';
                $.each(images, function(index, value) {
                    section += '<li data-id="' + index + '" id="' + index + '-item-' + iCount + '"><img height="50" width="50" src="' + value + '"/><a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '</div>';

                section += '<input class="imgip" id="' + iCount + '" type="hidden" data-layout="' + imgPosition + '" data-caption="' + caption + '" data-type="gallery" value="gallery" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }

        },

        addHomeImageWithContent: function(imgsrc, val, imgCaption, content, type, edit, folderid) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);

            var section = '';
            var content1 = content.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
            if (edit == 'N') {

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="home-information hideclass">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item  home-img-rt-area" data-editor="' + iCount + '">';
                section += '<div class="col-md-10 col-xs-small">';
                section += '<div class="team_box">';
                section += '<div class="thumbnail_container">';
                section += '<div class="thumbnail">';
                section += '<img src="' + imgsrc + '">';
                section += '<input id="' + iCount + '" data-id="' + val + '" data-type="' + type + '" data-caption="' + imgCaption + '" data-folderid="' + folderid + '" type="hidden" class="imgip" value="' + content1 + '"/>';
                section += '</div>';
                section += '</div>';
                section += '</div>';
                section += '<div class="title-img">';
                section += '<h3>' + imgCaption + '</h3>';
                section += content;
                section += '</div>';
                section += '</div>';
                section += '<div class="col-md-2 text-right">';
                section += '<div class="image-align-box">';
                section += '<h5 class="title">Preview</h5>';
                if (type == 'home-lft-txt') {
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/home-left-image.png" alt=""></i>';
                } else if (type == 'home-rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/home-right-image.png" alt=""></i>';
                } else if (type == 'home-top-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/home-top-image.png" alt=""></i>';
                }

                section += '</div>';
                section += '</div>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-10 col-xs-small">';
                section += '<div class="team_box">';
                section += '<div class="thumbnail_container">';
                section += '<div class="thumbnail">';
                section += '<img src="' + imgsrc + '">';
                section += '<input id="' + edit + '" data-id="' + val + '" data-type="' + type + '" data-caption="' + imgCaption + '" data-folderid="' + folderid + '" type="hidden" class="imgip" value="' + content1 + '"/>';
                section += '</div>';
                section += '</div>';
                section += '</div>';
                section += '<div class="title-img">';
                section += '<h3>' + imgCaption + '</h3>';
                section += content;
                section += '</div>';
                section += '</div>';
                section += '<div class="col-md-2 text-right">';
                section += '<div class="image-align-box">';
                section += '<h5 class="title">Preview</h5>';
                if (type == 'home-lft-txt') {
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/home-left-image.png" alt=""></i>';
                } else if (type == 'home-rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/home-right-image.png" alt=""></i>';
                } else if (type == 'home-top-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + CDN_PATH + 'assets/images/packages/visualcomposer/home-top-image.png" alt=""></i>';
                }

                section += '</div>';
                section += '</div>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }

        },
        moduleSectionsEvents: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="events" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="events-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link events-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-extraclass="' + extraclass + '" data-layout="' + layoutType + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="events" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link events-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-extraclass="' + extraclass + '" data-layout="' + layoutType + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="events" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsPhotoAlbum: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="photoalbum" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="photoalbum-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link photoalbum-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-extraclass="' + extraclass + '" data-layout="' + layoutType + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="photoalbum" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link photoalbum-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-extraclass="' + extraclass + '" data-layout="' + layoutType + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="photoalbum" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsVideoAlbum: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="videoalbum" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="videoalbum-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link videoalbum-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-extraclass="' + extraclass + '" data-layout="' + layoutType + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="videoalbum" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link videoalbum-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-extraclass="' + extraclass + '" data-layout="' + layoutType + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="videoalbum" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsBlogs: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="blogs" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="blogs-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link blogs-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="blogs" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link blogs-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="blogs" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsService: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="service" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="service-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link service-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="service" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link service-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="service" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },


        moduleSectionsCandWService: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="candwservice" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="candwservice-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link candwservice-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="candwservice" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link candwservice-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="candwservice" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        moduleSectionsConsultations: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="consultations" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="consultations-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link consultations-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="consultations" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link consultations-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="consultations" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsComplaintServices: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="complaint-services" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="complaint-services-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link complaint-services-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="complaint-services" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link complaint-services-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="complaint-services" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsFMBroadcasting: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="fmbroadcasting" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="fmbroadcasting-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link fmbroadcasting-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="fmbroadcasting" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link fmbroadcasting-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="fmbroadcasting" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsBoardofDirectors: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="boardofdirectors" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="boardofdirectors-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link boardofdirectors-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="boardofdirectors" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link boardofdirectors-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="boardofdirectors" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsRegisterofApplications: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="register-application" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="register-application-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link register-application-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="register-application" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link register-application-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="register-application" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsLicenceRegister: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="licence-register" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="licence-register-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link register-application-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="licence-register" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link licence-register-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="licence-register" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsFormsandFees: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="forms-and-fees" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="forms-and-fees-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link forms-and-fees-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="forms-and-fees" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link forms-and-fees-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="forms-and-fees" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsPublication: function(caption, desc, recids, recTitle, edit, template, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="publication" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="publication-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link publication-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden"  data-extraclass="' + extraclass + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="publication" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var eventsIds = [];
                var eventsTitles = [];
                var eventsCustomized = [];
                var eventsImg = [];
                var eventsImgSrc = [];
                var eventsImgheight = [];
                var eventsImgwidth = [];
                var eventsImgpoint = [];
                var eventsPhone = [];
                var eventsEmail = [];
                var eventsWebsite = [];
                var eventsAddress = [];
                var eventsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    eventsIds.push(iId);

                    var iTitle = $(this).text();
                    eventsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    eventsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    eventsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    eventsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    eventsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    eventsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    eventsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    eventsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    eventsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    eventsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    eventsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    eventsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    eventsIds.push(value);
                    eventsTitles.push(recTitle[index]);
                    eventsCustomized.push(false);
                    eventsImg.push('');
                    eventsImgSrc.push('');
                    eventsImgheight.push('');
                    eventsImgwidth.push('');
                    eventsImgpoint.push('');
                    eventsPhone.push('');
                    eventsEmail.push('');
                    eventsWebsite.push('');
                    eventsAddress.push('');
                    eventsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(eventsIds, function(index, value) {
                    section += '<li data-customized="' + eventsCustomized[index] + '" data-img="' + eventsImg[index] + '" data-imgsrc="' + eventsImgSrc[index] + '" data-imgheight="' + eventsImgheight[index] + '" data-imgwidth="' + eventsImgwidth[index] + '" data-imgpoint="' + eventsImgpoint[index] + '" data-phone="' + eventsPhone[index] + '" data-email="' + eventsEmail[index] + '" data-website="' + eventsWebsite[index] + '" data-address="' + eventsAddress[index] + '" data-description="' + eventsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + eventsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link publication-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-extraclass="' + extraclass + '"  data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="publication" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        moduleSectionsPromotions: function(caption, config, configTxt, recids, recTitle, edit, template, layoutType) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="promotion" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="promotions-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" title="Add more" class="add-link promotions-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="promotions" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var promotionsIds = [];
                var promotionsTitles = [];
                var promotionsCustomized = [];
                var promotionsImg = [];
                var promotionsImgSrc = [];
                var promotionsImgheight = [];
                var promotionsImgwidth = [];
                var promotionsImgpoint = [];
                var promotionsPhone = [];
                var promotionsEmail = [];
                var promotionsWebsite = [];
                var promotionsAddress = [];
                var promotionsDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    promotionsIds.push(iId);

                    var iTitle = $(this).text();
                    promotionsTitles.push(iTitle);

                    var Icustomized = $(this).data('customized')
                    promotionsCustomized.push(Icustomized);

                    var Iimg = $(this).data('img')
                    promotionsImg.push(Iimg);

                    var Iimgsrc = $(this).data('imgsrc')
                    promotionsImgSrc.push(Iimgsrc);

                    var Iimgheight = $(this).data('imgheight')
                    promotionsImgheight.push(Iimgheight);

                    var Iimgwidth = $(this).data('imgwidth')
                    promotionsImgwidth.push(Iimgwidth);

                    var Iimgpoint = $(this).data('imgpoint')
                    promotionsImgpoint.push(Iimgpoint);

                    var Iphone = $(this).data('phone')
                    promotionsPhone.push(Iphone);

                    var Iemail = $(this).data('email')
                    promotionsEmail.push(Iemail);

                    var Iwebsite = $(this).data('website')
                    promotionsWebsite.push(Iwebsite);

                    var Iaddress = $(this).data('address')
                    promotionsAddress.push(Iaddress);

                    var Idescription = $(this).data('description')
                    promotionsDescription.push(Idescription);

                });

                $.each(recids, function(index, value) {
                    promotionsIds.push(value);
                    promotionsTitles.push(recTitle[index]);
                    promotionsCustomized.push(false);
                    promotionsImg.push('');
                    promotionsImgSrc.push('');
                    promotionsImgheight.push('');
                    promotionsImgwidth.push('');
                    promotionsImgpoint.push('');
                    promotionsPhone.push('');
                    promotionsEmail.push('');
                    promotionsWebsite.push('');
                    promotionsAddress.push('');
                    promotionsDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(promotionsIds, function(index, value) {
                    section += '<li data-customized="' + promotionsCustomized[index] + '" data-img="' + promotionsImg[index] + '" data-imgsrc="' + promotionsImgSrc[index] + '" data-imgheight="' + promotionsImgheight[index] + '" data-imgwidth="' + promotionsImgwidth[index] + '" data-imgpoint="' + promotionsImgpoint[index] + '" data-phone="' + promotionsPhone[index] + '" data-email="' + promotionsEmail[index] + '" data-website="' + promotionsWebsite[index] + '" data-address="' + promotionsAddress[index] + '" data-description="' + promotionsDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + promotionsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link promotions-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="promotions" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsNews: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="news" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-filter="' + template + '" data-id="' + iCount + '" class="news-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link news-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" type="hidden" data-filter="' + template + '" data-config="' + config + '" data-extraclass="' + extraclass + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-desc="' + desc + '" data-type="module" value="news" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var newsIds = [];
                var newsTitles = [];
                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    newsIds.push(iId);

                    var iTitle = $(this).text();
                    newsTitles.push(iTitle);
                });

                $.each(recids, function(index, value) {
                    newsIds.push(value);
                    newsTitles.push(recTitle[index]);
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(newsIds, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + edit + '">' + newsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link news-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" type="hidden" data-filter="' + template + '" data-config="' + config + '" data-extraclass="' + extraclass + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-desc="' + desc + '" data-type="module" value="news" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        moduleSectionsAlerts: function(caption, recids, recTitle, edit, template, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="alerts" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-id="' + iCount + '" class="alerts-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" data-extraclass="' + extraclass + '" title="Add more" class="add-link alerts-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" type="hidden" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-caption="' + caption + '"  data-type="module" value="alerts" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var alertsIds = [];
                var alertsTitles = [];
                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    alertsIds.push(iId);

                    var iTitle = $(this).text();
                    alertsTitles.push(iTitle);
                });

                $.each(recids, function(index, value) {
                    alertsIds.push(value);
                    alertsTitles.push(recTitle[index]);
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(alertsIds, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + edit + '">' + alertsTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" data-extraclass="' + extraclass + '" title="Add more" class="add-link alerts-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" type="hidden" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-caption="' + caption + '" data-type="module" value="alerts" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        moduleSectionsLinks: function(caption, recids, recTitle, edit, template, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="links" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-id="' + iCount + '" class="links-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" data-extraclass="' + extraclass + '" title="Add more" class="add-link links-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" type="hidden" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-caption="' + caption + '"  data-type="module" value="links" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var linksIds = [];
                var linksTitles = [];
                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    linksIds.push(iId);

                    var iTitle = $(this).text();
                    linksTitles.push(iTitle);
                });

                $.each(recids, function(index, value) {
                    linksIds.push(value);
                    linksTitles.push(recTitle[index]);
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(linksIds, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + edit + '">' + linksTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" data-extraclass="' + extraclass + '" title="Add more" class="add-link links-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" type="hidden" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-caption="' + caption + '" data-type="module" value="links" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        moduleSectionsFaqs: function(caption, recids, recTitle, edit, template, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="faqs" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-id="' + iCount + '" class="faqs-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" data-extraclass="' + extraclass + '" title="Add more" class="add-link faqs-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" type="hidden" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-caption="' + caption + '"  data-type="module" value="faqs" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var linksIds = [];
                var linksTitles = [];
                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    linksIds.push(iId);

                    var iTitle = $(this).text();
                    linksTitles.push(iTitle);
                });

                $.each(recids, function(index, value) {
                    linksIds.push(value);
                    linksTitles.push(recTitle[index]);
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(linksIds, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + edit + '">' + linksTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" data-extraclass="' + extraclass + '" title="Add more" class="add-link faqs-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" type="hidden" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-caption="' + caption + '" data-type="module" value="faqs" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        addIframe: function(val, extClass, edit) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-id="' + iCount + '" class="iframeonly">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module module section-item iframe" data-editor="' + iCount + '">';
                section += '<div class="col-md-12"><label><b>Iframe</b></label><br/><iframe src="' + val + '" width="600" height="100" frameborder="0" style="border:0;"></iframe></div>';
                section += '<input id="' + iCount + '" data-class="' + extClass + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                section += '</div>';
                section += '</div>';
                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                section += '<div class="col-md-12"><label><b>Iframe</b></label><br/><iframe src="' + val + '" width="600" height="100" frameborder="0" style="border:0;"></iframe></div>';
                section += '<input id="' + edit + '" data-class="' + extClass + '" type="hidden" class="txtip" value="' + val + '"/>';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        moduleSectionsDepartment: function(caption, recids, recTitle, edit, template, extraclass) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="department" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-filter="' + template + '" data-id="' + iCount + '" class="department-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link department-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" type="hidden" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-caption="' + caption + '"  data-type="module" value="department" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var departmentIds = [];
                var departmentTitles = [];
                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    departmentIds.push(iId);

                    var iTitle = $(this).text();
                    departmentTitles.push(iTitle);
                });

                $.each(recids, function(index, value) {
                    departmentIds.push(value);
                    departmentTitles.push(recTitle[index]);
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b></label>';
                section += '<ul class="record-list">';
                $.each(departmentIds, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + edit + '">' + departmentTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link department-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" type="hidden" data-filter="' + template + '" data-extraclass="' + extraclass + '" data-caption="' + caption + '" data-type="module" value="department" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },

        moduleSectionsArticles: function(caption, config, configTxt, recids, recTitle, edit, template, layoutType, featuredRestaurant) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="articles" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" title="Edit" data-filter="' + template + '" data-id="' + iCount + '" class="articles-module">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="section-item defoult-module module" data-editor="' + iCount + '">';
                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(recids, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + iCount + '">' + recTitle[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + iCount + '" title="Add more" class="add-link articles-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" type="hidden" data-filter="' + template + '" data-config="' + config + '" data-layout="' + layoutType + '" data-frest="' + featuredRestaurant + '" data-caption="' + caption + '" data-type="module" value="articles" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var articlesIds = [];
                var articlesTitles = [];
                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    articlesIds.push(iId);

                    var iTitle = $(this).text();
                    articlesTitles.push(iTitle);
                });

                $.each(recids, function(index, value) {
                    articlesIds.push(value);
                    articlesTitles.push(recTitle[index]);
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(articlesIds, function(index, value) {
                    section += '<li data-id="' + value + '" id="' + value + '-item-' + edit + '">' + articlesTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link articles-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" type="hidden" data-filter="' + template + '" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-frest="' + featuredRestaurant + '" data-type="module" value="articles" />';
                section += '<div class="clearfix"></div>';
                $('#section-container').find('div[data-editor=' + edit + ']').html(section);
            }
        },
        resizeImageContentModal: function() {
            if ($.cookie('imageModalH')) {
                var imageModalH = $.cookie('imageModalH');
                $('#sectionImage .modal-dialog').css('height', imageModalH);
                $('#sectionImage .modal-content').css('height', imageModalH);
            }
            if ($.cookie('imageModalW')) {
                var imageModalW = $.cookie('imageModalW');
                $('#sectionImage .modal-dialog').css('width', imageModalW);
                $('#sectionImage .modal-content').css('width', imageModalW);
            }

            $('#sectionImage .modal-content').resizable({
                alsoResize: ".modal-dialog",
                minHeight: 758,
                minWidth: 650,
                stop: function(e, u) {
                    imageModalH = $(this).height();
                    imageModalW = $(this).width();
                    $.cookie('imageModalH', imageModalH);
                    $.cookie('imageModalW', imageModalW);
                }
            });
        },
        resizeOnlyImageModal: function() {
            if ($.cookie('onlyImageModalH')) {
                var onlyImageModalH = $.cookie('onlyImageModalH');
                $('#sectionOnlyImage .modal-dialog').css('height', onlyImageModalH);
                $('#sectionOnlyImage .modal-content').css('height', onlyImageModalH);
            }
            if ($.cookie('onlyImageModalW')) {
                var onlyImageModalW = $.cookie('onlyImageModalW');
                $('#sectionOnlyImage .modal-dialog').css('width', onlyImageModalW);
                $('#sectionOnlyImage .modal-content').css('width', onlyImageModalW);
            }

            $('#sectionOnlyImage .modal-content').resizable({
                alsoResize: ".modal-dialog",
                minHeight: 387,
                minWidth: 650,
                stop: function(e, u) {
                    onlyImageModalH = $(this).height();
                    onlyImageModalW = $(this).width();
                    $.cookie('onlyImageModalH', onlyImageModalH);
                    $.cookie('onlyImageModalW', onlyImageModalW);
                }
            });
        },
        resizeOnlyTitleModal: function() {
            if ($.cookie('onlyTitleModalH')) {
                var onlyTitleModalH = $.cookie('onlyTitleModalH');
                $('#sectionTitle .modal-dialog').css('height', onlyTitleModalH);
                $('#sectionTitle .modal-content').css('height', onlyTitleModalH);
            }
            if ($.cookie('onlyTitleModalW')) {
                var onlyTitleModalW = $.cookie('onlyTitleModalW');
                $('#sectionTitle .modal-dialog').css('width', onlyTitleModalW);
                $('#sectionTitle .modal-content').css('width', onlyTitleModalW);
            }

            $('#sectionTitle .modal-content').resizable({
                alsoResize: ".modal-dialog",
                minHeight: 455,
                minWidth: 650,
                stop: function(e, u) {
                    onlyTitleModalH = $(this).height();
                    onlyTitleModalW = $(this).width();
                    $.cookie('onlyTitleModalH', onlyTitleModalH);
                    $.cookie('onlyTitleModalW', onlyTitleModalW);
                }
            });
        },
        resizeOnlyContentModal: function() {
            if ($.cookie('onlyContentModalH')) {
                var onlyContentModalH = $.cookie('onlyContentModalH');
                $('#sectionContent .modal-dialog').css('height', onlyContentModalH);
                $('#sectionContent .modal-content').css('height', onlyContentModalH);
            }
            if ($.cookie('onlyContentModalW')) {
                var onlyContentModalW = $.cookie('onlyContentModalW');
                $('#sectionContent .modal-dialog').css('width', onlyContentModalW);
                $('#sectionContent .modal-content').css('width', onlyContentModalW);
            }

            $('#sectionContent .modal-content').resizable({
                alsoResize: ".modal-dialog",
                minHeight: 455,
                minWidth: 650,
                stop: function(e, u) {
                    onlyContentModalH = $(this).height();
                    onlyContentModalW = $(this).width();
                    $.cookie('onlyContentModalH', onlyContentModalH);
                    $.cookie('onlyContentModalW', onlyContentModalW);
                }
            });
        },
        resizePgBuiderSectionsModal: function() {
            if ($.cookie('pgBuiderSectionsModalH')) {
                var pgBuiderSectionsModalH = $.cookie('pgBuiderSectionsModalH');
                $('#pgBuiderSections .modal-dialog').css('height', pgBuiderSectionsModalH);
                $('#pgBuiderSections .modal-content').css('height', pgBuiderSectionsModalH);
            }
            if ($.cookie('pgBuiderSectionsModalW')) {
                var pgBuiderSectionsModalW = $.cookie('pgBuiderSectionsModalW');
                $('#pgBuiderSections .modal-dialog').css('width', pgBuiderSectionsModalW);
                $('#pgBuiderSections .modal-content').css('width', pgBuiderSectionsModalW);
            }

            $('#pgBuiderSections .modal-content').resizable({
                alsoResize: ".modal-dialog, .mcscroll",
                minHeight: 509,
                minWidth: 1125,
                stop: function(e, u) {
                    pgBuiderSectionsModalH = $(this).height();
                    pgBuiderSectionsModalW = $(this).width();
                    $.cookie('pgBuiderSectionsModalH', pgBuiderSectionsModalH);
                    $.cookie('pgBuiderSectionsModalW', pgBuiderSectionsModalW);
                }
            });
        },
        submitFrmSectionSpacerTemplate: function() {
            if ($('#frmSectionSpacerTemplate').validate().form()) {
                var edit = $('#frmSectionSpacerTemplate input[name=editing]').val() != '' ? $('#frmSectionSpacerTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var config = $('#frmSectionSpacerTemplate select[name=section_spacer]').val();
                var configTxt = $('#frmSectionSpacerTemplate #spacerid option:selected').text();
                builder.spacerTemplate(config, edit, configTxt);
                $('#sectionSpacerTemplate').modal('hide');
            }
        },
        submitFrmSectionEventsModuleTemplate: function() {
            if ($('#frmSectionEventsModuleTemplate').validate().form()) {
                var edit = $('#frmSectionEventsModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionEventsModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionEventsModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionEventsModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionEventsModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionEventsModuleTemplate input[name=template]').val();
                var config = $('#frmSectionEventsModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionEventsModuleTemplate #config option:selected').text();
                var layout = $('#frmSectionEventsModuleTemplate select[name=layoutType]').val();
                var extra_class = $('#frmSectionEventsModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionEventsModuleTemplate input[name=events_start_date_time]').val();
                var enddate = $('#frmSectionEventsModuleTemplate input[name=events_end_date_time]').val();
                var eventscat = $('#frmSectionEventsModuleTemplate select[name=eventscat]').val();
                builder.eventsTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, eventscat);
                $('#sectionEventsModuleTemplate').modal('hide');
            }
        },

        submitFrmSectionBlogsModuleTemplate: function() {
            if ($('#frmSectionBlogsModuleTemplate').validate().form()) {
                var edit = $('#frmSectionBlogsModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionBlogsModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionBlogsModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionBlogsModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionBlogsModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionBlogsModuleTemplate input[name=template]').val();
                var config = $('#frmSectionBlogsModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionBlogsModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionBlogsModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionBlogsModuleTemplate input[name=blogs_start_date_time]').val();
                var enddate = $('#frmSectionBlogsModuleTemplate input[name=blogs_end_date_time]').val();
                var blogscat = $('#frmSectionBlogsModuleTemplate select[name=blogscat]').val();
                var layout = $('#frmSectionBlogsModuleTemplate select[name=layoutType]').val();
                builder.blogsTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, blogscat);
                $('#sectionBlogsModuleTemplate').modal('hide');
            }
        },

        submitFrmSectionNumberAllocationsModuleTemplate: function() {
            if ($('#frmSectionNumberAllocationsModuleTemplate').validate().form()) {
                var edit = $('#frmSectionNumberAllocationsModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionNumberAllocationsModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionNumberAllocationsModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionNumberAllocationsModuleTemplate input[name=section_limit]').val();
                var template = $('#frmSectionNumberAllocationsModuleTemplate input[name=template]').val();
                var extra_class = $('#frmSectionNumberAllocationsModuleTemplate input[name=extra_class]').val();
                builder.numberAllocationsTemplate(val, sectionlimit, template, edit, extra_class);
                $('#sectionNumberAllocationsModuleTemplate').modal('hide');
            }
        },

        submitFrmSectionServiceModuleTemplate: function() {
            if ($('#frmSectionServiceModuleTemplate').validate().form()) {
                var edit = $('#frmSectionServiceModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionServiceModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionServiceModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionServiceModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionServiceModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionServiceModuleTemplate input[name=template]').val();
                var config = $('#frmSectionServiceModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionServiceModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionServiceModuleTemplate input[name=extra_class]').val();

                var servicecat = $('#frmSectionServiceModuleTemplate select[name=servicecat]').val();
                var layout = $('#frmSectionServiceModuleTemplate select[name=layoutType]').val();
                builder.serviceTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, servicecat);
                $('#sectionServiceModuleTemplate').modal('hide');
            }
        },




        submitFrmSectionCandWServiceModuleTemplate: function() {
            if ($('#frmSectioncandwserviceModuleTemplate').validate().form()) {
                var edit = $('#frmSectioncandwserviceModuleTemplate input[name=editing]').val() != '' ? $('#frmSectioncandwserviceModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectioncandwserviceModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectioncandwserviceModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectioncandwserviceModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectioncandwserviceModuleTemplate input[name=template]').val();
                var config = $('#frmSectioncandwserviceModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectioncandwserviceModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectioncandwserviceModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectioncandwserviceModuleTemplate input[name=candwservice_start_date_time]').val();
                var enddate = $('#frmSectioncandwserviceModuleTemplate input[name=candwservice_end_date_time]').val();
                var layout = $('#frmSectioncandwserviceModuleTemplate select[name=layoutType]').val();
                builder.candwserviceTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate);
                $('#sectioncandwserviceModuleTemplate').modal('hide');
            }
        },

        submitFrmSectionConsultationsModuleTemplate: function() {
            if ($('#frmSectionConsultationsModuleTemplate').validate().form()) {
                var edit = $('#frmSectionConsultationsModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionConsultationsModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionConsultationsModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionConsultationsModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionConsultationsModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionConsultationsModuleTemplate input[name=template]').val();
                var config = $('#frmSectionConsultationsModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionConsultationsModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionConsultationsModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionConsultationsModuleTemplate input[name=consultations_start_date_time]').val();
                var enddate = $('#frmSectionConsultationsModuleTemplate input[name=consultations_end_date_time]').val();
                var blogscat = $('#frmSectionConsultationsModuleTemplate select[name=blogscat]').val();
                var layout = $('#frmSectionConsultationsModuleTemplate select[name=layoutType]').val();
                builder.consultationsTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, blogscat);
                $('#sectionConsultationsModuleTemplate').modal('hide');
            }
        },

        submitFrmSectionComplaintServicesModuleTemplate: function() {
            if ($('#frmSectionComplaintServicesModuleTemplate').validate().form()) {
                var edit = $('#frmSectionComplaintServicesModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionComplaintServicesModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionComplaintServicesModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionComplaintServicesModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionComplaintServicesModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionComplaintServicesModuleTemplate input[name=template]').val();
                var config = $('#frmSectionComplaintServicesModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionComplaintServicesModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionComplaintServicesModuleTemplate input[name=extra_class]').val();

                var layout = $('#frmSectionComplaintServicesModuleTemplate select[name=layoutType]').val();
                builder.complaintservicesTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class);
                $('#sectionComplaintServicesModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionFMBroadcastingModuleTemplate: function() {
            if ($('#frmSectionFMBroadcastingModuleTemplate').validate().form()) {
                var edit = $('#frmSectionFMBroadcastingModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionFMBroadcastingModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionFMBroadcastingModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionFMBroadcastingModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionFMBroadcastingModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionFMBroadcastingModuleTemplate input[name=template]').val();
                var config = $('#frmSectionFMBroadcastingModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionFMBroadcastingModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionFMBroadcastingModuleTemplate input[name=extra_class]').val();

                var layout = $('#frmSectionFMBroadcastingModuleTemplate select[name=layoutType]').val();
                builder.fmbroadcastingTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class);
                $('#sectionFMBroadcastingModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionBoardofDirectorsModuleTemplate: function() {
            if ($('#frmSectionBoardofDirectorsTemplate').validate().form()) {
                var edit = $('#frmSectionBoardofDirectorsTemplate input[name=editing]').val() != '' ? $('#frmSectionBoardofDirectorsTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionBoardofDirectorsTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionBoardofDirectorsTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionBoardofDirectorsTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionBoardofDirectorsTemplate input[name=template]').val();
                var config = $('#frmSectionBoardofDirectorsTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionBoardofDirectorsTemplate #config option:selected').text();
                var extra_class = $('#frmSectionBoardofDirectorsTemplate input[name=extra_class]').val();

                var layout = $('#frmSectionBoardofDirectorsTemplate select[name=layoutType]').val();
                builder.boardofdirectorsTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class);
                $('#sectionBoardofDirectorsTemplate').modal('hide');
            }
        },

        submitFrmSectionRegisterofApplicationsModuleTemplate: function() {
            if ($('#frmSectionRegisterofApplicationsModuleTemplate').validate().form()) {
                var edit = $('#frmSectionRegisterofApplicationsModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionRegisterofApplicationsModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionRegisterofApplicationsModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionRegisterofApplicationsModuleTemplate input[name=section_limit]').val();
                var template = $('#frmSectionRegisterofApplicationsModuleTemplate input[name=template]').val();
                var extra_class = $('#frmSectionRegisterofApplicationsModuleTemplate input[name=extra_class]').val();

                builder.registerapplicationTemplate(val, sectionlimit, template, edit, extra_class);
                $('#sectionRegisterofApplicationsModuleTemplate').modal('hide');
            }
        },

        submitFrmSectionLicenceRegisterModuleTemplate: function() {
            if ($('#frmSectionLicenceRegisterModuleTemplate').validate().form()) {
                var edit = $('#frmSectionLicenceRegisterModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionLicenceRegisterModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionLicenceRegisterModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionLicenceRegisterModuleTemplate input[name=section_limit]').val();
                var template = $('#frmSectionLicenceRegisterModuleTemplate input[name=template]').val();
                var sector = $('#frmSectionLicenceRegisterModuleTemplate select[name=section_config]').val();
                var sectorTxt = $('#frmSectionLicenceRegisterModuleTemplate #sector option:selected').text();
                var extra_class = $('#frmSectionLicenceRegisterModuleTemplate input[name=extra_class]').val();

                builder.licenceregisterTemplate(val, sectionlimit, sector, template, edit, sectorTxt, extra_class);
                $('#sectionLicenceRegisterModuleTemplate').modal('hide');
            }
        },

        submitFrmSectionFormsandFeesModuleTemplate: function() {
            if ($('#frmSectionFormsAndFeesModuleTemplate').validate().form()) {
                var edit = $('#frmSectionFormsAndFeesModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionFormsAndFeesModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionFormsAndFeesModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionFormsAndFeesModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionFormsAndFeesModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionFormsAndFeesModuleTemplate input[name=template]').val();
                var config = $('#frmSectionFormsAndFeesModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionFormsAndFeesModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionFormsAndFeesModuleTemplate input[name=extra_class]').val();

                var layout = $('#frmSectionFormsAndFeesModuleTemplate select[name=layoutType]').val();
                builder.formsandfeesTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class);
                $('#sectionFormsAndFeesModuleTemplate').modal('hide');
            }
        },

        submitFrmSectionPublicationModuleTemplate: function() {
            if ($('#frmSectionPublicationModuleTemplate').validate().form()) {
                var edit = $('#frmSectionPublicationModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionPublicationModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionPublicationModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionPublicationModuleTemplate input[name=section_limit]').val();

                var template = $('#frmSectionPublicationModuleTemplate input[name=template]').val();
                var config = $('#frmSectionPublicationModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionPublicationModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionPublicationModuleTemplate input[name=extra_class]').val();

                var publicationscat = $('#frmSectionPublicationModuleTemplate select[name=publicationscat]').val();
                var sector = $('#frmSectionPublicationModuleTemplate select[name=sectortype]').val();
                builder.publicationTemplate(val, sectionlimit, config, template, edit, configTxt, sector, extra_class, publicationscat);
                $('#sectionPublicationModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionPhotoAlbumModule: function() {
            if ($('#frmSectionPhotoAlbumModule').validate().form()) {
                var edit = $('#frmSectionPhotoAlbumModule input[name=editing]').val() != '' ? $('#frmSectionPhotoAlbumModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionPhotoAlbumModule input[name=template]').val();
                var imgCaption = $('#frmSectionPhotoAlbumModule #section_title').val();
                var desc = $('#frmSectionPhotoAlbumModule #section_description').val();
                var config = $('#frmSectionPhotoAlbumModule #config').val();
                var configTxt = $('#frmSectionPhotoAlbumModule #config option:selected').text();
                var layoutType = $('#frmSectionPhotoAlbumModule select[name=layoutType]').val();
                var extraclass = $('#frmSectionPhotoAlbumModule input[name=extra_class]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionPhotoAlbumModule #datatable_photoalbum_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsPhotoAlbum(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionPhotoAlbumModule').modal('hide');
            }
        },
        submitFrmSectionVideoAlbumModule: function() {
            if ($('#frmSectionVideoAlbumModule').validate().form()) {
                var edit = $('#frmSectionVideoAlbumModule input[name=editing]').val() != '' ? $('#frmSectionVideoAlbumModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionVideoAlbumModule input[name=template]').val();
                var imgCaption = $('#frmSectionVideoAlbumModule #section_title').val();
                var desc = $('#frmSectionVideoAlbumModule #section_description').val();
                var config = $('#frmSectionVideoAlbumModule #config').val();
                var configTxt = $('#frmSectionVideoAlbumModule #config option:selected').text();
                var layoutType = $('#frmSectionVideoAlbumModule select[name=layoutType]').val();
                var extraclass = $('#frmSectionVideoAlbumModule input[name=extra_class]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionVideoAlbumModule #datatable_videoalbum_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsVideoAlbum(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionVideoAlbumModule').modal('hide');
            }
        },
        submitFrmSectionNewsModuleTemplate: function() {
            if ($('#frmSectionNewsModuleTemplate').validate().form()) {
                var edit = $('#frmSectionNewsModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionNewsModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionNewsModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionNewsModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionNewsModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionNewsModuleTemplate input[name=template]').val();
                var config = $('#frmSectionNewsModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionNewsModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionNewsModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionNewsModuleTemplate input[name=news_start_date_time]').val();
                var enddate = $('#frmSectionNewsModuleTemplate input[name=news_end_date_time]').val();
                var newscat = $('#frmSectionNewsModuleTemplate select[name=newscat]').val();
                var layout = $('#frmSectionNewsModuleTemplate select[name=layoutType]').val();
                builder.newsTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, newscat);
                $('#sectionNewsModuleTemplate').modal('hide');
            }
        },

        submitFrmSectionPublicRecordModuleTemplate: function() {
            if ($('#frmSectionPublicRecordModuleTemplate').validate().form()) {
                var edit = $('#frmSectionPublicRecordModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionPublicRecordModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionPublicRecordModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionPublicRecordModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionPublicRecordModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionPublicRecordModuleTemplate input[name=template]').val();
                var config = $('#frmSectionPublicRecordModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionPublicRecordModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionPublicRecordModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionPublicRecordModuleTemplate input[name=news_start_date_time]').val();
                var enddate = $('#frmSectionPublicRecordModuleTemplate input[name=news_end_date_time]').val();
                var newscat = $('#frmSectionPublicRecordModuleTemplate select[name=newscat]').val();
                var layout = $('#frmSectionPublicRecordModuleTemplate select[name=layoutType]').val();
                builder.publicRecordTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, newscat);
                $('#sectionPublicRecordModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionLatestNewsModuleTemplate: function() {
            if ($('#frmSectionLatestNewsTemplate').validate().form()) {
                var edit = $('#frmSectionLatestNewsTemplate input[name=editing]').val() != '' ? $('#frmSectionLatestNewsTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionLatestNewsTemplate input[name=section_title]').val();
                var template = $('#frmSectionLatestNewsTemplate input[name=template]').val();
                var extra_class = $('#frmSectionLatestNewsTemplate input[name=extra_class]').val();

                builder.latestNewsTemplate(val, template, edit, extra_class);
                $('#sectionLatestNewsTemplate').modal('hide');
            }
        },
        submitFrmSectionQuickLinkModuleTemplate: function() {
            if ($('#frmSectionQuickLinkTemplate').validate().form()) {
                var edit = $('#frmSectionQuickLinkTemplate input[name=editing]').val() != '' ? $('#frmSectionQuickLinkTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionQuickLinkTemplate input[name=section_title]').val();
                var template = $('#frmSectionQuickLinkTemplate input[name=template]').val();
                var extra_class = $('#frmSectionQuickLinkTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionQuickLinkTemplate input[name=qlink_start_date_time]').val();
                var enddate = $('#frmSectionQuickLinkTemplate input[name=qlink_end_date_time]').val();
                builder.QuickLinkTemplate(val, template, edit, extra_class, startdate, enddate);
                $('#sectionQuickLinkTemplate').modal('hide');
            }
        },
        submitFrmSectionPhotoAlbumModuleTemplate: function() {
            if ($('#frmSectionPhotoAlbumModuleTemplate').validate().form()) {
                var edit = $('#frmSectionPhotoAlbumModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionPhotoAlbumModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionPhotoAlbumModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionPhotoAlbumModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionPhotoAlbumModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionPhotoAlbumModuleTemplate input[name=template]').val();
                var config = $('#frmSectionPhotoAlbumModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionPhotoAlbumModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionPhotoAlbumModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionPhotoAlbumModuleTemplate input[name=photo_start_date_time]').val();
                var enddate = $('#frmSectionPhotoAlbumModuleTemplate input[name=photo_end_date_time]').val();
                var layout = $('#frmSectionPhotoAlbumModuleTemplate select[name=layoutType]').val();
                builder.photoalbumTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate);
                $('#sectionPhotoAlbumModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionVideoAlbumModuleTemplate: function() {
            if ($('#frmSectionVideoAlbumModuleTemplate').validate().form()) {
                var edit = $('#frmSectionVideoAlbumModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionVideoAlbumModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionVideoAlbumModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionVideoAlbumModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionVideoAlbumModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionVideoAlbumModuleTemplate input[name=template]').val();
                var config = $('#frmSectionVideoAlbumModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionVideoAlbumModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionVideoAlbumModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionVideoAlbumModuleTemplate input[name=photo_start_date_time]').val();
                var enddate = $('#frmSectionVideoAlbumModuleTemplate input[name=photo_end_date_time]').val();
                var layout = $('#frmSectionVideoAlbumModuleTemplate select[name=layoutType]').val();
                builder.videoalbumTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate);
                $('#sectionVideoAlbumModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionOrganizationsModuleTemplate: function() {
            if ($('#frmSectionOrganizationsModule').validate().form()) {
                var edit = $('#frmSectionOrganizationsModule input[name=editing]').val() != '' ? $('#frmSectionOrganizationsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionOrganizationsModule input[name=section_title]').val();
                var template = $('#frmSectionOrganizationsModule input[name=template]').val();
                var extraclass = $('#frmSectionOrganizationsModule input[name=extra_class]').val();
                var parentorg = $('#frmSectionOrganizationsModule select[name=parentorg]').val();
                builder.organizationsTemplate(val, parentorg, template, extraclass, edit);
                $('#sectionOrganizationsModule').modal('hide');
            }
        },
        submitFrmSectionInterconnectionsModuleTemplate: function() {
            if ($('#frmSectionInterconnectionsModule').validate().form()) {
                var edit = $('#frmSectionInterconnectionsModule input[name=editing]').val() != '' ? $('#frmSectionInterconnectionsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionInterconnectionsModule input[name=section_title]').val();
                var template = $('#frmSectionInterconnectionsModule input[name=template]').val();
                var extraclass = $('#frmSectionInterconnectionsModule input[name=extra_class]').val();
                var parentorg = $('#frmSectionInterconnectionsModule select[name=parentorg]').val();
                builder.interconnectionsTemplate(val, parentorg, template, extraclass, edit);
                $('#sectionInterconnectionsModule').modal('hide');
            }
        },
        submitFrmSectionAlertsModuleTemplate: function() {
            if ($('#frmSectionAlertsModuleTemplate').validate().form()) {
                var edit = $('#frmSectionAlertsModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionAlertsModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionAlertsModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionAlertsModuleTemplate input[name=section_limit]').val();
                var extra_class = $('#frmSectionAlertsModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionAlertsModuleTemplate input[name=alert_start_date_time]').val();
                var enddate = $('#frmSectionAlertsModuleTemplate input[name=alert_end_date_time]').val();
                var template = $('#frmSectionAlertsModuleTemplate input[name=template]').val();
                var alertType = $('#frmSectionAlertsModuleTemplate select[name=alertType]').val();
                var configTxt = $('#frmSectionAlertsModuleTemplate #alert-template-layout option:selected').text();
                builder.alertsTemplate(val, sectionlimit, extra_class, alertType, template, edit, configTxt, startdate, enddate);
                $('#sectionAlertsModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionDepartmentModuleTemplate: function() {
            if ($('#frmSectionDepartmentModuleTemplate').validate().form()) {
                var edit = $('#frmSectionDepartmentModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionDepartmentModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionDepartmentModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionDepartmentModuleTemplate input[name=section_limit]').val();
                var extra_class = $('#frmSectionDepartmentModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionDepartmentModuleTemplate input[name=department_start_date_time]').val();
                var enddate = $('#frmSectionDepartmentModuleTemplate input[name=department_end_date_time]').val();
                var template = $('#frmSectionDepartmentModuleTemplate input[name=template]').val();
                builder.departmentTemplate(val, sectionlimit, extra_class, template, edit, startdate, enddate);
                $('#sectionDepartmentModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionLinksModuleTemplate: function() {
            if ($('#frmSectionLinksModuleTemplate').validate().form()) {
                var edit = $('#frmSectionLinksModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionLinksModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionLinksModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionLinksModuleTemplate input[name=section_limit]').val();
                var extra_class = $('#frmSectionLinksModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionLinksModuleTemplate input[name=link_start_date_time]').val();
                var enddate = $('#frmSectionLinksModuleTemplate input[name=link_end_date_time]').val();
                var template = $('#frmSectionLinksModuleTemplate input[name=template]').val();
                var linkcat = $('#frmSectionLinksModuleTemplate select[name=linkcat]').val();
                builder.linkTemplate(val, sectionlimit, extra_class, template, edit, startdate, enddate, linkcat);
                $('#sectionLinksModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionFaqsModuleTemplate: function() {
            if ($('#frmSectionFaqsModuleTemplate').validate().form()) {
                var edit = $('#frmSectionFaqsModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionFaqsModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionFaqsModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionFaqsModuleTemplate input[name=section_limit]').val();
                var extra_class = $('#frmSectionFaqsModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionFaqsModuleTemplate input[name=faq_start_date_time]').val();
                var enddate = $('#frmSectionFaqsModuleTemplate input[name=faq_end_date_time]').val();
                var template = $('#frmSectionFaqsModuleTemplate input[name=template]').val();
                var faqcat = $('#frmSectionFaqsModuleTemplate select[name=faqcat]').val();
                builder.faqTemplate(val, sectionlimit, extra_class, template, edit, startdate, enddate, faqcat);
                $('#sectionFaqsModuleTemplate').modal('hide');
            }
        },
        submitFrmSectionPromotionsModuleTemplate: function() {
            if ($('#frmSectionPromotionsModuleTemplate').validate().form()) {
                var edit = $('#frmSectionPromotionsModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionPromotionsModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionPromotionsModuleTemplate input[name=section_title]').val();
                var template = $('#frmSectionPromotionsModuleTemplate input[name=template]').val();
                var config = $('#frmSectionPromotionsModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionPromotionsModuleTemplate #config option:selected').text();
                var layout = $('#frmSectionPromotionsModuleTemplate select[name=layoutType]').val();
                builder.promotionsTemplate(val, config, template, edit, configTxt, layout);
                $('#sectionPromotionsModuleTemplate').modal('hide');
            }
        },
        submitFrmBusinessCustomize: function() {
            if ($('#frmBusinessCustomize').validate().form()) {
                var edit = $('#frmBusinessCustomize input[name=editing]').val() != '' ? $('#frmBusinessCustomize input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var img = $('#frmBusinessCustomize input[name=img1]').val();
                var height = $('#frmBusinessCustomize input[name=height]').val();
                var width = $('#frmBusinessCustomize input[name=width]').val();
                var point = $('#frmBusinessCustomize input[name=point]').val();
                var imgsrc = $('#frmBusinessCustomize .photo_gallery_1 img').attr('src');
                var phone = $('#frmBusinessCustomize input[name=phone]').val();
                var email = $('#frmBusinessCustomize input[name=email]').val();
                var website = $('#frmBusinessCustomize input[name=website]').val();
                var address = $('#frmBusinessCustomize textarea[name=address]').val();
                var txtShortDescription = businessCustomizeCk.getData();

                $('#' + edit).data('img', img)
                    .data('customized', true)
                    .data('imgsrc', imgsrc)
                    .data('imgheight', height)
                    .data('imgwidth', width)
                    .data('imgpoint', point)
                    .data('phone', phone)
                    .data('email', email)
                    .data('website', website)
                    .data('address', address)
                    .data('description', txtShortDescription);

                $('#businessCustomize').modal('hide');
            }
        },
        submitFrmSectionTitle: function() {
            if ($('#frmSectionTitle').validate().form()) {
                var edit = $('#frmSectionTitle input[name=editing]').val() != '' ? $('#frmSectionTitle input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionTitle #only_title').val();
                var extClass = $('#frmSectionTitle #extra_class').val();
                builder.onlyTitle(val, extClass, edit);
                builder.reInitSortable();
                $('#sectionTitle').modal('hide');
            }
        },
        submitTwoColumnsTitle: function() {
            $('#no-content').addClass('d-none');
            $('#has-content').removeClass('d-none');
            builder.TwoColumns();
            builder.reInitSortable();
        },
        submitThreeColumnsTitle: function() {
            $('#no-content').addClass('d-none');
            $('#has-content').removeClass('d-none');
            builder.ThreeColumns();
            builder.reInitSortable();
        },
        submitOneThreeColumnsTitle: function() {
            $('#no-content').addClass('d-none');
            $('#has-content').removeClass('d-none');
            builder.OneThreeColumns();
            builder.reInitSortable();
        },
        submitThreeOneColumnsTitle: function() {
            $('#no-content').addClass('d-none');
            $('#has-content').removeClass('d-none');
            builder.ThreeOneColumns();
            builder.reInitSortable();
        },
        submitFourColumnsTitle: function() {
            $('#no-content').addClass('d-none');
            $('#has-content').removeClass('d-none');
            // builder.addColumnParentClass('Four Column', 4);
            builder.FourColumns();
            builder.reInitSortable();
        },
        submitFrmSectionContactInfo: function() {
            if ($('#frmSectionContactInfo').validate().form()) {
                var edit = $('#frmSectionContactInfo input[name=editing]').val() != '' ? $('#frmSectionContactInfo input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var section_otherinfo = sectionInfoCk.getData();
                var section_address = $('#frmSectionContactInfo #section_address').val();
                var section_email = $('#frmSectionContactInfo #section_email').val();
                var section_phone = $('#frmSectionContactInfo #section_phone').val();
                builder.onlySectionContactInfo(section_address, section_email, section_phone, section_otherinfo, edit);
                builder.reInitSortable();
                $('#sectionContactInfo').modal('hide');
            }
        },
        submitFrmCustomSectionBase: function() {
            if ($('#frmCustomSectionBase').validate().form()) {

                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');

                var title = $('#frmCustomSectionBase input[name=title]').val();
                var subtitle = $('#frmCustomSectionBase input[name=subtitle]').val();

                var template = $('#frmCustomSectionBase input[name=template]').val();
                var extclass = $('#frmCustomSectionBase input[name=extra_class]').val();
                var layoutType = $('#frmCustomSectionBase #business-layout').val();

                builder.addCustomSection(title, template, extclass, layoutType, subtitle);
                $('#customSectionBase').modal('hide');
            }
        },
        submitFrmSectionButton: function() {
            if ($('#frmSectionButton').validate().form()) {
                var edit = $('#frmSectionButton input[name=editing]').val() != '' ? $('#frmSectionButton input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionButton input[name=section_title]').val();
                var linktarget = $('#frmSectionButton #section_button_target').val();
                var buttonlink = $('#frmSectionButton #section_link').val();
                var type = $('#frmSectionButton input[name=selector]:checked').val();
                builder.onlySectionButton(val, linktarget, buttonlink, type, edit);
                builder.reInitSortable();
                $('#sectionButton').modal('hide');
            }
        },
        submitFrmSectionVideo: function() {
            if ($('#frmSectionVideo').validate().form()) {
                var edit = $('#frmSectionVideo input[name=editing]').val() != '' ? $('#frmSectionVideo input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionVideo #videoId').val();
                var title = $('#frmSectionVideo #videoCaption').val();
                var videoType = $('#frmSectionVideo input[name=chrVideoType]:checked').val();
                builder.onlyVideo(val, title, videoType, edit);
                builder.reInitSortable();
                $('#sectionVideo').modal('hide');
            }
        },
        submitFrmSectionContent: function() {
            if ($('#frmSectionContent').validate().form()) {
                var edit = $('#frmSectionContent input[name=editing]').val() != '' ? $('#frmSectionContent input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = sectionContentCk.getData();
                var extClass = $('#frmSectionContent #extraClass').val();
                builder.addTextArea(val, extClass, edit);
                builder.reInitSortable();
                $('#sectionContent').modal('hide');
            }
        },
        submitFrmSectionAccordian: function() {
            if ($('#frmSectionAccordian').validate().form()) {
                var edit = $('#frmSectionAccordian input[name=editing]').val() != '' ? $('#frmSectionAccordian input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = sectionContentCk.getData();
                var title = $('#frmSectionAccordian #only_title').val();
                builder.addAccordianblock(val, title, edit);
                builder.reInitSortable();
                $('#sectionAccordian').modal('hide');
            }
        },
        submitFrmSectionTwoContent: function() {
            if ($('#frmSectionTwoContent').validate().form()) {
                var edit = $('#frmSectionTwoContent input[name=editing]').val() != '' ? $('#frmSectionTwoContent input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var leftval = sectionleftContentCk.getData();
                var rightval = sectionrightContentCk.getData();
                builder.addTwoTextArea(leftval, rightval, edit);
                builder.reInitSortable();
                $('#sectiontwoContent').modal('hide');
            }
        },
        submitFrmSectionOnlyImage: function() {
            if ($('#frmSectionOnlyImage').validate().form()) {
                var edit = $('#frmSectionOnlyImage input[name=editing]').val() != '' ? $('#frmSectionOnlyImage input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var folderid = $('#frmSectionOnlyImage').find('.folder_1').val();
                var val = $('#frmSectionOnlyImage').find('.imgip').val();
                var imgCaption = $('#frmSectionOnlyImage #img_title').val();
                var extraClass = $('#frmSectionOnlyImage #extra_class').val();
                var data_width = $('#frmSectionOnlyImage #data_width').val();
                var imgsrc = $('#frmSectionOnlyImage').find('.thumbnail img').attr('src');
                var type = $('#frmSectionOnlyImage input[name=selector]:checked').val();

                builder.addImage(imgsrc, val, imgCaption, extraClass, type, edit, folderid, data_width);
                builder.reInitSortable();
                $('#sectionOnlyImage').modal('hide');
            }
        },
        submitFrmSectionMap: function() {
            if ($('#frmSectionMap').validate().form()) {
                var edit = $('#frmSectionMap input[name=editing]').val() != '' ? $('#frmSectionMap input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var latitude = $('#frmSectionMap #img_latitude').val();
                var longitude = $('#frmSectionMap #img_longitude').val();
                builder.addMap(latitude, longitude, edit);
                builder.reInitSortable();
                $('#sectionMap').modal('hide');
            }
        },
        submitFrmSectionOnlyDocument: function() {
            if ($('#frmSectionOnlyDocument').validate().form()) {
                var edit = $('#frmSectionOnlyDocument input[name=editing]').val() != '' ? $('#frmSectionOnlyDocument input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var folderid = $('#frmSectionOnlyDocument').find('.folder_1').val();
                var val = $('#frmSectionOnlyDocument').find('.imgip1').val();
                var caption = $('#frmSectionOnlyDocument').find('#caption').val();
                var doc_date_time = $('#frmSectionOnlyDocument').find('#doc_date_time').val();
                builder.addDocument(val, edit, folderid, caption, doc_date_time);
                builder.reInitSortable();
                $('#frmSectionOnlyDocument').find('.folder_1').val('');
                $('#frmSectionOnlyDocument').find('.imgip1').val('');
                $('#frmSectionOnlyDocument').find('#caption').val('');
                $('#frmSectionOnlyDocument').find('#doc_date_time').val('');
                // $('#frmSectionOnlyDocument').find('.thumbnail img').attr('src').val('');
                $('#sectionOnlyDocument').modal('hide');
            }
        },
        submitFrmSectionImage: function() {
            if ($('#frmSectionImage').validate().form()) {
                var edit = $('#frmSectionImage input[name=editing]').val() != '' ? $('#frmSectionImage input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var folderid = $('#frmSectionImage').find('.folder_1').val();
                var val = $('#frmSectionImage').find('.imgip').val();
                var imgCaption = $('#frmSectionImage #img_title').val();
                var imgsrc = $('#frmSectionImage').find('.thumbnail img').attr('src');
                var content = sectionImageCk.getData();
                var type = $('#frmSectionImage input[name=selector]:checked').val();
                builder.addImageWithContent(imgsrc, val, imgCaption, content, type, edit, folderid);
                builder.reInitSortable();
                $('#sectionImage').modal('hide');
            }
        },
        submitFrmSectionGalleryImage: function() {
            if ($('#frmSectionGalleryImage').validate().form()) {
                var edit = $('#frmSectionGalleryImage input[name=editing]').val() != '' ? $('#frmSectionGalleryImage input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var images = {};
                $('#sectionGalleryImage .multi_image_list li').each(function() {
                    images[$(this).attr('id')] = $(this).find('img').attr('src');
                });
                var imgPosition = $('#sectionGalleryImage input[name=gallery_layout]:checked').val();
                builder.addGalleryImage(images, imgPosition, edit);
                builder.reInitSortable();
                $('#sectionGalleryImage').modal('hide');
            }
        },
        submitFrmsectionVideoContent: function() {
            if ($('#frmsectionVideoContent').validate().form()) {
                var edit = $('#frmsectionVideoContent input[name=editing]').val() != '' ? $('#frmsectionVideoContent input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmsectionVideoContent #videoId').val();
                var title = $('#frmsectionVideoContent #videoCaption').val();
                var videoType = $('#frmsectionVideoContent input[name=chrVideoType]:checked').val();
                var content = sectionImageCk.getData();
                var type = $('#frmsectionVideoContent input[name=selector]:checked').val();
                builder.addVideoWithContent(title, val, videoType, content, type, edit);
                builder.reInitSortable();
                $('#sectionVideoContent').modal('hide');
            }
        },
        submitFrmSectionHomeImage: function() {
            if ($('#frmSectionHomeImage').validate().form()) {
                var edit = $('#frmSectionHomeImage input[name=editing]').val() != '' ? $('#frmSectionHomeImage input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var folderid = $('#frmSectionHomeImage').find('.folder_1').val();
                var val = $('#frmSectionHomeImage').find('.imgip').val();
                var imgCaption = $('#frmSectionHomeImage #img_title').val();
                var imgsrc = $('#frmSectionHomeImage').find('.thumbnail img').attr('src');
                var content = sectionImageCk.getData();
                var type = $('#frmSectionHomeImage input[name=selector]:checked').val();
                builder.addHomeImageWithContent(imgsrc, val, imgCaption, content, type, edit, folderid);
                builder.reInitSortable();
                $('#sectionHomeImage').modal('hide');
            }
        },
        submitFrmSectionEventsModule: function() {
            if ($('#frmSectionEventsModule').validate().form()) {
                var edit = $('#frmSectionEventsModule input[name=editing]').val() != '' ? $('#frmSectionEventsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionEventsModule input[name=template]').val();
                var imgCaption = $('#frmSectionEventsModule #section_title').val();
                var desc = $('#frmSectionEventsModule #section_description').val();
                var extraclass = $('#frmSectionEventsModule #extra_class').val();
                var config = $('#frmSectionEventsModule #config').val();
                var configTxt = $('#frmSectionEventsModule #config option:selected').text();
                var layoutType = $('#frmSectionEventsModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionEventsModule #datatable_events_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsEvents(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionEventsModule').modal('hide');
            }
        },
        submitFrmSectionBlogsModule: function() {
            if ($('#frmSectionBlogsModule').validate().form()) {
                var edit = $('#frmSectionBlogsModule input[name=editing]').val() != '' ? $('#frmSectionBlogsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionBlogsModule input[name=template]').val();
                var imgCaption = $('#frmSectionBlogsModule #section_title').val();
                var desc = $('#frmSectionBlogsModule #section_description').val();
                var config = $('#frmSectionBlogsModule #config').val();
                var extraclass = $('#frmSectionBlogsModule #extra_class').val();
                var configTxt = $('#frmSectionBlogsModule #config option:selected').text();
                var layoutType = $('#frmSectionBlogsModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionBlogsModule #datatable_blogs_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsBlogs(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionBlogsModule').modal('hide');
            }
        },

        submitFrmSectionServiceModule: function() {
            if ($('#frmSectionServiceModule').validate().form()) {
                var edit = $('#frmSectionServiceModule input[name=editing]').val() != '' ? $('#frmSectionServiceModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionServiceModule input[name=template]').val();
                var imgCaption = $('#frmSectionServiceModule #section_title').val();
                var desc = $('#frmSectionServiceModule #section_description').val();
                var config = $('#frmSectionServiceModule #config').val();
                var extraclass = $('#frmSectionServiceModule #extra_class').val();
                var configTxt = $('#frmSectionServiceModule #config option:selected').text();
                var layoutType = $('#frmSectionServiceModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionServiceModule #datatable_service_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsService(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionServiceModule').modal('hide');
            }
        },



        submitFrmSectionComplaintServicesModule: function() {
            if ($('#frmSectionComplaintServicesModule').validate().form()) {
                var edit = $('#frmSectionComplaintServicesModule input[name=editing]').val() != '' ? $('#frmSectionComplaintServicesModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionComplaintServicesModule input[name=template]').val();
                var imgCaption = $('#frmSectionComplaintServicesModule #section_title').val();
                var desc = $('#frmSectionComplaintServicesModule #section_description').val();
                var config = $('#frmSectionComplaintServicesModule #config').val();
                var extraclass = $('#frmSectionComplaintServicesModule #extra_class').val();
                var configTxt = $('#frmSectionComplaintServicesModule #config option:selected').text();
                var layoutType = $('#frmSectionComplaintServicesModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionComplaintServicesModule #datatable_complaint-services_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsComplaintServices(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionComplaintServicesModule').modal('hide');
            }
        },

        submitFrmSectionFMBroadcastingModule: function() {
            if ($('#frmSectionFMBroadcastingModule').validate().form()) {
                var edit = $('#frmSectionFMBroadcastingModule input[name=editing]').val() != '' ? $('#frmSectionFMBroadcastingModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionFMBroadcastingModule input[name=template]').val();
                var imgCaption = $('#frmSectionFMBroadcastingModule #section_title').val();
                var desc = $('#frmSectionFMBroadcastingModule #section_description').val();
                var config = $('#frmSectionFMBroadcastingModule #config').val();
                var extraclass = $('#frmSectionFMBroadcastingModule #extra_class').val();
                var configTxt = $('#frmSectionFMBroadcastingModule #config option:selected').text();
                var layoutType = $('#frmSectionFMBroadcastingModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionFMBroadcastingModule #datatable_fmbroadcasting_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsFMBroadcasting(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionFMBroadcastingModule').modal('hide');
            }
        },

        submitFrmSectionBoardofDirectorsModule: function() {
            if ($('#frmSectionBoardofDirectorsModule').validate().form()) {
                var edit = $('#frmSectionBoardofDirectorsModule input[name=editing]').val() != '' ? $('#frmSectionBoardofDirectorsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionBoardofDirectorsModule input[name=template]').val();
                var imgCaption = $('#frmSectionBoardofDirectorsModule #section_title').val();
                var desc = $('#frmSectionBoardofDirectorsModule #section_description').val();
                var config = $('#frmSectionBoardofDirectorsModule #config').val();
                var extraclass = $('#frmSectionBoardofDirectorsModule #extra_class').val();
                var configTxt = $('#frmSectionBoardofDirectorsModule #config option:selected').text();
                var layoutType = $('#frmSectionBoardofDirectorsModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionBoardofDirectorsModule #datatable_boardofdirectors_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsBoardofDirectors(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionBoardofDirectorsModule').modal('hide');
            }
        },

        submitFrmSectionRegisterofApplicationsModule: function() {
            if ($('#frmSectionRegisterofApplicationsModule').validate().form()) {
                var edit = $('#frmSectionRegisterofApplicationsModule input[name=editing]').val() != '' ? $('#frmSectionRegisterofApplicationsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionRegisterofApplicationsModule input[name=template]').val();
                var imgCaption = $('#frmSectionRegisterofApplicationsModule #section_title').val();
                var desc = $('#frmSectionRegisterofApplicationsModule #section_description').val();
                var config = $('#frmSectionRegisterofApplicationsModule #config').val();
                var extraclass = $('#frmSectionRegisterofApplicationsModule #extra_class').val();
                var configTxt = $('#frmSectionRegisterofApplicationsModule #config option:selected').text();
                var layoutType = $('#frmSectionRegisterofApplicationsModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionRegisterofApplicationsModule #datatable_registerapplication_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsRegisterofApplications(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionRegisterofApplicationsModule').modal('hide');
            }
        },

        submitFrmSectionLicenceRegisterModule: function() {
            if ($('#frmSectionLicenceRegisterModule').validate().form()) {
                var edit = $('#frmSectionLicenceRegisterModule input[name=editing]').val() != '' ? $('#frmSectionLicenceRegisterModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionLicenceRegisterModule input[name=template]').val();
                var imgCaption = $('#frmSectionLicenceRegisterModule #section_title').val();
                var desc = $('#frmSectionLicenceRegisterModule #section_description').val();
                var config = $('#frmSectionLicenceRegisterModule #config').val();
                var extraclass = $('#frmSectionLicenceRegisterModule #extra_class').val();
                var configTxt = $('#frmSectionLicenceRegisterModule #config option:selected').text();
                var layoutType = $('#frmSectionLicenceRegisterModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionLicenceRegisterModule #datatable_licence-register_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsLicenceRegister(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionLicenceRegisterModule').modal('hide');
            }
        },

        submitFrmSectionFormsandFeesModule: function() {
            if ($('#frmSectionFormsandFeesModule').validate().form()) {
                var edit = $('#frmSectionFormsandFeesModule input[name=editing]').val() != '' ? $('#frmSectionFormsandFeesModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionFormsandFeesModule input[name=template]').val();
                var imgCaption = $('#frmSectionFormsandFeesModule #section_title').val();
                var desc = $('#frmSectionFormsandFeesModule #section_description').val();
                var config = $('#frmSectionFormsandFeesModule #config').val();
                var extraclass = $('#frmSectionFormsandFeesModule #extra_class').val();
                var configTxt = $('#frmSectionFormsandFeesModule #config option:selected').text();
                var layoutType = $('#frmSectionFormsandFeesModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionFormsandFeesModule #datatable_forms-and-fees_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsFormsandFees(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionFormsandFeesModule').modal('hide');
            }
        },
        submitFrmSectionConsultationsModule: function() {
            if ($('#frmSectionConsultationsModule').validate().form()) {
                var edit = $('#frmSectionConsultationsModule input[name=editing]').val() != '' ? $('#frmSectionConsultationsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionConsultationsModule input[name=template]').val();
                var imgCaption = $('#frmSectionConsultationsModule #section_title').val();
                var desc = $('#frmSectionConsultationsModule #section_description').val();
                var config = $('#frmSectionConsultationsModule #config').val();
                var extraclass = $('#frmSectionConsultationsModule #extra_class').val();
                var configTxt = $('#frmSectionConsultationsModule #config option:selected').text();
                var layoutType = $('#frmSectionConsultationsModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionConsultationsModule #datatable_consultations_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsConsultations(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionConsultationsModule').modal('hide');
            }
        },

        submitFrmSectionCandWServiceModule: function() {
            if ($('#frmSectionCandWServiceModule').validate().form()) {
                var edit = $('#frmSectionCandWServiceModule input[name=editing]').val() != '' ? $('#frmSectionCandWServiceModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionCandWServiceModule input[name=template]').val();
                var imgCaption = $('#frmSectionCandWServiceModule #section_title').val();
                var desc = $('#frmSectionCandWServiceModule #section_description').val();
                var config = $('#frmSectionCandWServiceModule #config').val();
                var extraclass = $('#frmSectionCandWServiceModule #extra_class').val();
                var configTxt = $('#frmSectionCandWServiceModule #config option:selected').text();
                var layoutType = $('#frmSectionCandWServiceModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionCandWServiceModule #datatable_candwservice_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsCandWService(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionCandWServiceModule').modal('hide');
            }
        },

        submitFrmSectionPublicationModule: function() {
            if ($('#frmSectionPublicationModule').validate().form()) {
                var edit = $('#frmSectionPublicationModule input[name=editing]').val() != '' ? $('#frmSectionPublicationModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionPublicationModule input[name=template]').val();
                var imgCaption = $('#frmSectionPublicationModule #section_title').val();
                var desc = $('#frmSectionPublicationModule #section_description').val();
                var extraclass = $('#frmSectionPublicationModule #extra_class').val();

                var recids = [];
                var recTitle = [];
                $('#sectionPublicationModule #datatable_publication_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsPublication(imgCaption, desc, recids, recTitle, edit, template, extraclass);
                builder.reInitSortable();
                $('#sectionPublicationModule').modal('hide');
            }
        },
        submitFrmSectionPromotionsModule: function() {
            if ($('#frmSectionPromotionsModule').validate().form()) {
                var edit = $('#frmSectionPromotionsModule input[name=editing]').val() != '' ? $('#frmSectionPromotionsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionPromotionsModule input[name=template]').val();
                var imgCaption = $('#frmSectionPromotionsModule #section_title').val();
                var config = $('#frmSectionPromotionsModule #config').val();
                var configTxt = $('#frmSectionPromotionsModule #config option:selected').text();
                var layoutType = $('#frmSectionPromotionsModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionPromotionsModule #datatable_promotions_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsPromotions(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType);
                builder.reInitSortable();
                $('#sectionPromotionsModule').modal('hide');
            }
        },
        submitFrmSectionNewsModule: function() {
            if ($('#frmSectionNewsModule').validate().form()) {
                var edit = $('#frmSectionNewsModule input[name=editing]').val() != '' ? $('#frmSectionNewsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');

                var imgCaption = $('#frmSectionNewsModule #section_title').val();
                var desc = $('#frmSectionNewsModule #section_description').val();
                var config = $('#frmSectionNewsModule #config').val();
                var extraclass = $('#frmSectionNewsModule #extra_class').val();
                var configTxt = $('#frmSectionNewsModule #config option:selected').text();
                var layoutType = $('#frmSectionNewsModule select[name=layoutType]').val();
                var template = $('#frmSectionNewsModule input[name=template]').val();
                var recids = [];
                var recTitle = [];
                builder.moduleSectionsNews(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
                builder.reInitSortable();
                $('#sectionNewsModule').modal('hide');
            }
        },
        submitFrmSectionAlertsModule: function() {
            if ($('#frmSectionAlertsModule').validate().form()) {
                var edit = $('#frmSectionAlertsModule input[name=editing]').val() != '' ? $('#frmSectionAlertsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');

                var imgCaption = $('#frmSectionAlertsModule #section_title').val();
                var extraclass = $('#frmSectionAlertsModule #extra_class').val();
                var template = $('#frmSectionAlertsModule input[name=template]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionAlertsModule #datatable_alerts_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsAlerts(imgCaption, recids, recTitle, edit, template, extraclass);
                builder.reInitSortable();
                $('#sectionAlertsModule').modal('hide');
            }
        },
        submitFrmSectionLinksModule: function() {
            if ($('#frmSectionLinksModule').validate().form()) {
                var edit = $('#frmSectionLinksModule input[name=editing]').val() != '' ? $('#frmSectionLinksModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');

                var imgCaption = $('#frmSectionLinksModule #section_title').val();
                var extraclass = $('#frmSectionLinksModule #extra_class').val();
                var template = $('#frmSectionLinksModule input[name=template]').val();
                var recids = [];
                var recTitle = [];
                $('#frmSectionLinksModule #datatable_links_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsLinks(imgCaption, recids, recTitle, edit, template, extraclass);
                builder.reInitSortable();
                $('#sectionLinksModule').modal('hide');
            }
        },
        submitFrmSectionFaqsModule: function() {
            if ($('#frmSectionFaqsModule').validate().form()) {
                var edit = $('#frmSectionFaqsModule input[name=editing]').val() != '' ? $('#frmSectionFaqsModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');

                var imgCaption = $('#frmSectionFaqsModule #section_title').val();
                var extraclass = $('#frmSectionFaqsModule #extra_class').val();
                var template = $('#frmSectionLinksModule input[name=template]').val();
                var recids = [];
                var recTitle = [];
                $('#frmSectionFaqsModule #datatable_faqs_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsFaqs(imgCaption, recids, recTitle, edit, template, extraclass);
                builder.reInitSortable();
                $('#sectionFaqsModule').modal('hide');
            }
        },
        submitFrmSectionDepartmentModule: function() {
            if ($('#frmSectionDepartmentModule').validate().form()) {
                var edit = $('#frmSectionDepartmentModule input[name=editing]').val() != '' ? $('#frmSectionDepartmentModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');

                var imgCaption = $('#frmSectionDepartmentModule #section_title').val();
                var extraclass = $('#frmSectionDepartmentModule #extra_class').val();
                var template = $('#frmSectionDepartmentModule input[name=template]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionDepartmentModule #datatable_department_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsDepartment(imgCaption, recids, recTitle, edit, template, extraclass);
                builder.reInitSortable();
                $('#sectionDepartmentModule').modal('hide');
            }
        },
        submitFrmSectionIframe: function() {
            if ($('#frmSectionIframe').validate().form()) {
                var edit = $('#frmSectionIframe input[name=editing]').val() != '' ? $('#frmSectionIframe input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionIframe input[name=content]').val();
                var extClass = $('#frmSectionIframe .extraClass').val();
                builder.addIframe(val, extClass, edit);
                builder.reInitSortable();
                $('#sectionIframe').modal('hide');
            }
        },
        submitFrmSectionArticlesModule: function() {
            if ($('#frmSectionArticlesModule').validate().form()) {
                var edit = $('#frmSectionArticlesModule input[name=editing]').val() != '' ? $('#frmSectionArticlesModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');

                var template = $('#frmSectionArticlesModule input[name=template]').val();
                var imgCaption = $('#frmSectionArticlesModule #section_title').val();
                var config = $('#frmSectionArticlesModule #config').val();
                var configTxt = $('#frmSectionArticlesModule #config option:selected').text();
                var layoutType = $('#frmSectionArticlesModule select[name=layoutType]').val();
                var featuredRestaurant = $('#frmSectionArticlesModule input[name=show_featured_res]').bootstrapSwitch('state');
                var recids = [];
                var recTitle = [];
                $('#sectionArticlesModule #datatable_articles_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsArticles(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType, featuredRestaurant);
                builder.reInitSortable();
                $('#sectionArticlesModule').modal('hide');
            }
        },
        submitFrmAddColumn: function() {
            if ($('#frmAddColumns').validate().form()) {
                var edit = $('#frmAddColumns input[name=editing]').val() != '' ? $('#frmAddColumns input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');

                if (edit == 'Y') {

                    var no_of_column = $('#frmAddColumns select[name=no_of_column]').val();
                    var column_class = $('#frmAddColumns input[name=column_class]').val();
                    var animation = $('#frmAddColumns select[name=animation]').val();
                    var sectionid = $('#frmAddColumns input[name=sectionid]').val();

                    builder.updateColumns(sectionid, no_of_column, column_class, animation);
                } else {
                    var no_of_column = $('#frmAddColumns select[name=no_of_column]').val();
                    var column_class = $('#frmAddColumns input[name=column_class]').val();
                    var animation = $('#frmAddColumns select[name=animation]').val();
                    var sectionid = $('#frmAddColumns input[name=sectionid]').val();
                    builder.addColumns(sectionid, no_of_column, column_class, animation);

                }
                $('#addColumns').modal('hide');
            }
        },
        submitFrmEditRow: function(item_id) {

            if ($('#frmEditRow').validate().form()) {

                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');

                var row_class = $('#frmEditRow input[name=row_class]').val();
                var animation = $('#frmEditRow select[name=animation]').val();

                $('#' + item_id + '-row').val(item_id + '-row');
                $('#' + item_id + '-row').attr('data-extclass', row_class);
                $('#' + item_id + '-row').attr('data-animation', animation);
                $('#editRow').modal('hide');
            }
        },
        submitFrmEditColRow: function(item_id) {

            if ($('#frmEditColRow').validate().form()) {

                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');

                var row_class = $('#frmEditColRow input[name=col_row_class]').val();
                var animation = $('#frmEditColRow select[name=animation]').val();

                $('input[data-id="' + item_id + '"]').val(item_id);
                $('input[data-id="' + item_id + '"]').attr('data-extclass', row_class);
                $('input[data-id="' + item_id + '"]').attr('data-animation', animation);
                $('#editColRow').modal('hide');
            }
        },
        addColumns: function(sectionid, no_of_column, column_class, animation) {

            var section = '';

            var editor = sectionid;
            var row_count = $('.col-row').length + 1;
            var class_divider = (12 / no_of_column);
            var i;

            section += '<div id="' + editor + '-row-' + row_count + '" class="col-row">';
            section += '<a href="javascript:;" data-id="' + editor + '-row-' + row_count + '"  class="delete-col-row" title="Delete"><i class="action-icon delete fa fa-trash"></i></a>';
            section += '<a href="javascript:;" data-id="' + editor + '-row-' + row_count + '"  class="edit-col-row" title="Delete"><i class="action-icon edit ri-pencil-line"></i></a>';
            section += '<div class="ui-new-section-add col-sm-12 row">';
            for (i = 1; i <= no_of_column; i++) {

                section += '<div class="columns col-sm-' + class_divider + '" id="' + editor + '-row-' + row_count + '-col-' + i + '">';
                section += '<a href="javascript:;" data-id="' + editor + '-row-' + row_count + '-col-' + i + '" class="delete-column" title="Delete"><i class="action-icon delete fa fa-trash"></i></a>';
                section += '<a href="javascript:;" data-editor="' + editor + '-row-' + row_count + '-col-' + i + '" class="edit-column" title="Edit"><i class="action-icon edit ri-pencil-line"></i></a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="ui-new-section-add ' + editor + '-row-' + row_count + '-col-' + i + '"><strong> Column ' + i + '</strong></div>';
                section += '<a href="javascript:;" data-id="' + editor + '-row-' + row_count + '-col-' + i + '" class="add-icon add-cms-block"><i class="fa fa-plus" aria-hidden="true"></i></a>';
                section += '<input data-id="' + editor + '-row-' + row_count + '-col-' + i + '" data-extclass="' + column_class + '" data-animation="' + animation + '" type="hidden" value="' + no_of_column + '"/>';
                section += '</div>';

            }
            section += '</div>';
            section += '<input data-id="' + editor + '-row-' + row_count + '" data-extclass="" data-animation="" type="hidden" value=""/>';
            section += '</div>';

            $('div[data-id="' + sectionid + '-column-list"]').append(section);
        },
        updateColumns: function(sectionid, no_of_column, column_class, animation) {

            $('input[data-id="' + sectionid + '"]').val(no_of_column);
            $('input[data-id="' + sectionid + '"]').attr('data-extclass', column_class);
            $('input[data-id="' + sectionid + '"]').attr('data-animation', animation);

        },
    };
}();

jQuery(document).ready(function() {
    builder.init();
    $(".record-list").sortable().disableSelection();
});


$(document).on('click', '.close-btn', function() {
    if (confirm('Are you sure you want to remove this section?')) {
        $(this).parent().remove();
    }
});

$(document).on('click', '.record-list .close-icon', function() {
    if (confirm('Are you sure you want to remove this record?')) {
        $(this).parent().remove();
    }
});

$(document).on('change', '.imgip', function() {
    $('.gallary_manager').css('display', 'none');
});

$(document).on('click', '#insert_image', function() {
    $('.gallary_manager').css('display', 'none');
});



var submitted = false;
$('#' + seoFormId).submit(function(event) {
    builder.fillFormObj();
    submitted = true;
});

window.onbeforeunload = function() {
    /*if (!submitted) {
     return 'Do you really want to leave the page?';
     }*/
}


//Dialog opener code=====================================
$(document).on('click', '#resize-image', function(event) {
    $('#image-resizer').modal({
        backdrop: 'static',
        keyboard: false
    });
});
$('#image-resizer').on('shown.bs.modal', function() {
    var source = $('#businessCustomize .photo_gallery_1 img').attr('src');
    $('#image-resizer img').attr('src', source);
    var cheight = $('#businessCustomize #height').val();
    var cwidth = $('#businessCustomize #width').val();
    var point = $('#businessCustomize #point').val();
    builder.resizeImg($('#image-resizer img'), cwidth, point);
}).on('hidden.bs.modal', function() {
    $('#image-resizer img').css('width', 'auto');
});

$(document).on('click', '#save-adjusments', function(event) {
    var height = $('#image-resizer img').height();
    var width = $('#image-resizer img').width();
    var point = $('#image-resizer #slider').slider("value");
    var dimension = height + 'X' + width;
    $('#businessCustomize #current-dimension').removeClass('hide').text(dimension);
    $('#businessCustomize #height').val(height);
    $('#businessCustomize #width').val(width);
    $('#businessCustomize #point').val(point);
    $('#image-resizer').modal('hide');
});

$(document).on('click', '.add-element', function(event) {

    $('.add-element').removeClass('clicked');
    $('.columnstwo').removeClass('clicked');

    $('.nav-tabs li').show();

    //$('.only-title').hide();
    //$('.text-block').hide();

    $('.only-document').hide();
    $('.only-image').hide();
    $('.section-button').hide();
    $('.accordian-block').hide();
    $('.only-video').hide();
    //$('.iframeonly').hide();
    $('.google-map').hide();
    $('.only-spacer').hide();
    $('.home-information').hide();
    $('.two-part-content').hide();
    $('.image-with-information').hide();
    $('.video-with-information').hide();
    $('.contact-info').hide();
    $('.custom-section').hide();


    $('#pgBuiderSections').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$('#pgBuiderSections').on('shown.bs.modal', function() {
    builder.resizePgBuiderSectionsModal();
    $(".mcscroll").mCustomScrollbar({
        axis: "y",
        theme: "dark"
    });
});

var recordId = '';
var module = '';
var cutomid = '';
var img = '';
var imgsrc = '';
var imgHeight = '';
var imgWidth = '';
var imgpoint = '';
var phone = '';
var email = '';
var website = '';
var address = '';
var description = '';
var config = '';
var customized = false;
$(document).on('click', '.record-list .customize-icon', function() {
    cutomid = $(this).parent().attr('id');
    recordId = $(this).parent().data('id');

    customized = $(this).parent().data('customized');
    img = $(this).parent().data('img');
    imgHeight = $(this).parent().data('imgheight');
    imgWidth = $(this).parent().data('imgwidth');
    imgpoint = $(this).parent().data('imgpoint');
    imgsrc = $(this).parent().data('imgsrc');
    phone = $(this).parent().data('phone');
    email = $(this).parent().data('email');
    website = $(this).parent().data('website');
    address = $(this).parent().data('address');
    description = $(this).parent().data('description');
    config = $(this).closest('.section-item').find('input[type=hidden]').data('config');
    module = $(this).data('module');
    $('#businessCustomize').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$('#businessCustomize').on('shown.bs.modal', function() {
    $('#frmBusinessCustomize .modal-body').loading('start');
    $('#businessCustomize #current-dimension').addClass('hide');
    setTimeout(function() {
        if (!customized || typeof customized == 'undefined' || customized == 'undefined') {
            var data = builder.getRecord(recordId, module);
            description = data.txtShortDescription;
            img = data.intLogoId;
            imgsrc = data.img;
            if (data.business_contact != null) {
                var address2 = data.business_contact.txtAddress2 == null ? '' : ("\n" + data.business_contact.txtAddress2);
                phone = data.business_contact.varPhone;
                email = data.business_contact.varEmail;
                website = data.business_contact.varWebsite;
                address = data.business_contact.txtAddress + address2;
            }
        }

        $('#businessCustomize input[name=editing]').val(cutomid);
        $('#businessCustomize .photo_gallery_1 img').attr('src', imgsrc);
        $('#businessCustomize #photo_gallery1').val(img);
        $('#businessCustomize #height').val(imgHeight);
        $('#businessCustomize #width').val(imgWidth);
        $('#businessCustomize #point').val(imgpoint);
        var txt = (imgHeight > 0 && imgWidth > 0) ? imgHeight + 'X' + imgWidth : '';
        $('#businessCustomize #current-dimension').text(txt);
        // $('#businessCustomize #img_title').val(data.varTitle);
        $('#businessCustomize #ck-area').val(description);
        $('#businessCustomize #phone').val(phone);
        $('#businessCustomize #email').val(email);
        $('#businessCustomize #website').val(website);
        $('#businessCustomize #address').val(address);

        if (imgHeight > 0 && imgWidth > 0) {
            $('#businessCustomize #current-dimension').removeClass('hide');
        }

        ClassicEditor.create(document.querySelector('#businessCustomize #ck-area'), cmsConfig)
            .then(editor => {
                window.businessCustomizeCk = editor;
            })
            .catch(error => {
                console.error(error);
            });

        if (config == 1) { //(Image & Title)     
            $('#businessCustomize #img1').removeClass('hide');
        } else if (config == 2 || config == 7 || config == 13 || config == 18) { //(Image & Short Description)
            $('#businessCustomize #img1').removeClass('hide');
            $('#businessCustomize #ck-area').closest('.form-group').removeClass('hide');
        } else if (config == 3) { //Image & Phone Number
            $('#businessCustomize #img1').removeClass('hide');
            $('#businessCustomize #phone').closest('.form-group').removeClass('hide');
        } else if (config == 4) { //Image & Email
            $('#businessCustomize #img1').removeClass('hide');
            $('#businessCustomize #email').closest('.form-group').removeClass('hide');
        } else if (config == 5) { //Image & Address
            $('#businessCustomize #img1').removeClass('hide');
            $('#businessCustomize #address').closest('.form-group').removeClass('hide');
        } else if (config == 6) { //Image & Reviews
            $('#businessCustomize #img1').removeClass('hide');
        } else if (config == 8) { //Image, Title, Short Description, & Phone Number
            $('#businessCustomize #img1').removeClass('hide');
            $('#businessCustomize #ck-area').closest('.form-group').removeClass('hide');
            $('#businessCustomize #phone').closest('.form-group').removeClass('hide');
        } else if (config == 9) { //Image, Title, Short Description, Phone Number & Email
            $('#businessCustomize #img1').removeClass('hide');
            $('#businessCustomize #ck-area').closest('.form-group').removeClass('hide');
            $('#businessCustomize #phone').closest('.form-group').removeClass('hide');
            $('#businessCustomize #email').closest('.form-group').removeClass('hide');
        } else if (config == 10 || config == 11) { //Image, Title, Short Description, Phone Number, Email  &  Address
            $('#businessCustomize #img1').removeClass('hide');
            $('#businessCustomize #ck-area').closest('.form-group').removeClass('hide');
            $('#businessCustomize #phone').closest('.form-group').removeClass('hide');
            $('#businessCustomize #email').closest('.form-group').removeClass('hide');
            $('#businessCustomize #address').closest('.form-group').removeClass('hide');
        } else if (config == 14) { //Title & Description
            $('#businessCustomize #ck-area').closest('.form-group').removeClass('hide');
        }
        validateBusinessCustomize.init();
        $('#frmBusinessCustomize .modal-body').loading('stop');
    }, 1000);
}).on('hidden.bs.modal', function() {
    $('#businessCustomize .ck-editor').remove();
    $('#businessCustomize .photo_gallery_1 img').attr('src', CDN_PATH + 'assets/images/packages/visualcomposer/plus-no-image.png');
    $('#businessCustomize #photo_gallery1').val(null);
    $('#businessCustomize #img_title').val(null);
    $('#businessCustomize #phone').val(null);
    $('#businessCustomize #email').val(null);
    $('#businessCustomize #website').val(null);
    $('#businessCustomize #address').val(null);
    $('#businessCustomize #ck-area').val(null);
    $('#businessCustomize #height').val(null);
    $('#businessCustomize #width').val(null);
    $('#businessCustomize #point').val(null);

    $('#businessCustomize #img1').addClass('hide');
    $('#businessCustomize #ck-area').closest('.form-group').addClass('hide');
    $('#businessCustomize #phone').closest('.form-group').addClass('hide');
    $('#businessCustomize #email').closest('.form-group').addClass('hide');
    $('#businessCustomize #address').closest('.form-group').addClass('hide');

    validateBusinessCustomize.reset();
});
$(document).on('click', '.two-columns', function(event) {
    $('#pgBuiderSections').modal('hide');
    builder.submitTwoColumnsTitle();
    return false;
});

$(document).on('click', '.three-columns', function(event) {
    $('#pgBuiderSections').modal('hide');
    builder.submitThreeColumnsTitle();
    return false;
});

$(document).on('click', '.one-three-columns', function(event) {
    $('#pgBuiderSections').modal('hide');
    builder.submitOneThreeColumnsTitle();
    return false;
});

$(document).on('click', '.three-one-columns', function(event) {
    $('#pgBuiderSections').modal('hide');
    builder.submitThreeOneColumnsTitle();
    return false;
});

$(document).on('click', '.four-columns', function(event) {
    $('#pgBuiderSections').modal('hide');
    builder.submitFourColumnsTitle();
    return false;
});

$(document).on('click', '.columnstwo', function(event) {
    $('.add-element').removeClass('clicked');
    $('.columnstwo').removeClass('clicked');
    $(this).addClass('clicked');
    $(this).parents('.col_1').find('.add-element').addClass('clicked');
});

$(document).on('click', '.hideclass', function(event) {
    $('.add-element').removeClass('clicked');
    $('.columnstwo').removeClass('clicked');
});


$(document).on('click', '.only-title', function(event) {
    $('#pgBuiderSections').modal('hide');
    // $('#sectionTitle').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    $('#sectionTitle').modal('show');

    var id = $(this).data('id');

    if ($('.add-cms-block').hasClass('clicked')) {

        var extclass = $('.titleOnly #' + id).data('class');
        var value = $('.titleOnly #' + id).val();

        $('#sectionTitle input[name=editing]').val(id);
        $('#sectionTitle #only_title').val(value);
        $('#sectionTitle #extra_class').val(extclass);

        if (typeof id != undefined) {
            $('#sectionTitle #addSection').text('Update');
            $('#sectionTitle #exampleModalLabel b').text('Update Section Title');
        } else {
            $('#sectionTitle #addSection').text('Add');
            $('#sectionTitle #exampleModalLabel b').text('Add Section Title');
        }


    } else if (typeof id != 'undefined') {
        var extclass = $('.titleOnly #' + id).data('class');
        var value = $('.titleOnly #' + id).val();
        $('#sectionTitle input[name=editing]').val(id);
        $('#sectionTitle #extra_class').val(extclass);
        $('#sectionTitle #only_title').val(value);
        $('#sectionTitle #addSection').text('Update');
        $('#sectionTitle #exampleModalLabel b').text('Edit Section Title');
    } else {
        $('#sectionTitle input[name=editing]').val('');
        $('#sectionTitle #extra_class').val('');
        $('#sectionTitle #only_title').val('');
        $('#sectionTitle #addSection').text('Add');
        $('#sectionTitle #exampleModalLabel b').text('Section Title');
    }

});

$('#sectionTitle').on('shown.bs.modal', function() {
    builder.resizeOnlyTitleModal();
    validateSectionOnlyTitle.init();
}).on('hidden.bs.modal', function() {
    validateSectionOnlyTitle.reset();
});

if ($('.composerbody div:first').removeClass('text-block')) {
    $(document).on('click', '.text-block', function(event) {
        $('#pgBuiderSections').modal('hide');
        $('#sectionContent').modal({
            backdrop: 'static',
            keyboard: false
        });

        var id = $(this).data('id');

        if ($('.add-cms-block').hasClass('clicked')) {

            var extclass = $('.text-area #' + id).data('class');
            var value = $('.text-area #' + id).val();

            $('#sectionContent input[name=editing]').val(id);
            $('#sectionContent #extraClass').val(extclass);
            $('#sectionContent textarea').val(value);

            if (typeof id != undefined) {
                $('#sectionContent #addSection').text('Update');
                $('#sectionContent #exampleModalLabel b').text('Update Text Block');
            } else {
                $('#sectionContent #addSection').text('Add');
                $('#sectionContent #exampleModalLabel b').text('Add Text Block');
            }

        } else if (typeof id != 'undefined') {

            var extclass = $('.text-area #' + id).data('class');
            var value = $('.text-area #' + id).val();
            $('#sectionContent input[name=editing]').val(id);
            $('#sectionContent #extraClass').val(extclass);
            $('#sectionContent textarea').val(value);
            $('#sectionContent #addSection').text('Update');
            $('#sectionContent #exampleModalLabel b').text('Edit Text Block');

        } else {
            $('#sectionContent input[name=editing]').val('');
            $('#sectionContent #extraClass').val('');
            $('#sectionContent textarea').val('');
            $('#sectionContent #addSection').text('Add');
            $('#sectionContent #exampleModalLabel b').text('Text Block');
        }
        $('#sectionContent .ck-editor').remove();
        ClassicEditor.create(document.querySelector('#sectionContent #ck-area'), cmsConfig)
            .then(editor => {
                window.sectionContentCk = editor;
            })
            .catch(error => {
                console.error(error);
            });
    });
}
$('#sectionContent').on('shown.bs.modal', function() {
    builder.resizeOnlyContentModal();
    validateSectionOnlyContent.init();
}).on('hidden.bs.modal', function() {
    validateSectionOnlyContent.reset();
});


$(document).on('click', '.accordian-block', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionAccordian').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');

    if ($('.add-cms-block').hasClass('clicked')) {

        var title = $('.text-accordian #' + id).data('title');
        var value = $('.text-accordian #' + id).val();

        $('#sectionAccordian input[name=editing]').val(id);
        $('#sectionAccordian #only_title').val(title);
        $('#sectionAccordian textarea').val(value);
        $('#sectionAccordian #addSection').text('Update');
        $('#sectionAccordian #exampleModalLabel b').text('Edit Accordian Block');


    } else if (typeof id != 'undefined') {

        var title = $('.text-accordian #' + id).data('title');
        var value = $('.text-accordian #' + id).val();
        $('#sectionAccordian input[name=editing]').val(id);
        $('#sectionAccordian #only_title').val(title);
        $('#sectionAccordian textarea').val(value);
        $('#sectionAccordian #addSection').text('Update');
        $('#sectionAccordian #exampleModalLabel b').text('Edit Accordian Block');

    } else {

        $('#sectionAccordian input[name=editing]').val('');
        $('#sectionAccordian #only_title').val('');
        $('#sectionAccordian textarea').val('');
        $('#sectionAccordian #addSection').text('Add');
        $('#sectionAccordian #exampleModalLabel b').text('Accordian Block');

    }
    $('#sectionAccordian .ck-editor').remove();
    ClassicEditor.create(document.querySelector('#sectionAccordian #ck-area'), cmsConfig)
        .then(editor => {
            window.sectionContentCk = editor;
        })
        .catch(error => {
            console.error(error);
        });
});
$('#sectionAccordian').on('shown.bs.modal', function() {
    builder.resizeOnlyContentModal();
    validateAccordianblock.init();
}).on('hidden.bs.modal', function() {
    validateAccordianblock.reset();
});

$(document).on('click', '.only-image', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionOnlyImage').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    if ($('.add-cms-block').hasClass('clicked')) {

        var strfid = id.split("-");
        if ($('.img-area #' + id).data('folderid') != '' && typeof $('.img-area #' + id).data('folderid') != 'undefined') {
            var folderid = $('.img-area #' + id).data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        var caption = $('.img-area #' + id).data('caption');
        var extra_class = $('.img-area #' + id).data('extra_class');
        var data_width = $('.img-area #' + id).data('width');
        var value = $('.img-area #' + id).val();
        var src = $('.img-area #' + id).prev().attr('src');
        var align = $('.img-area #' + id).data('type');

        $('#sectionOnlyImage input[name=editing]').val(id);
        $('#sectionOnlyImage .imgip').val(value);
        $('#sectionOnlyImage #img_title').val(caption);
        $('#sectionOnlyImage #extra_class').val(extra_class);
        $('#sectionOnlyImage #data_width').val(data_width);
        $('#sectionOnlyImage #vfolder_id').val(folderid);
        $('#frmSectionOnlyImage img:first').attr('src', src);
        $('#sectionOnlyImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionOnlyImage #addSection').text('Update');
        $('#sectionOnlyImage #exampleModalLabel b').text('Edit Image Block');

    } else if (typeof id != 'undefined') {

        var strfid = id.split("-");
        if ($('.img-area #' + id).data('folderid') != '' && typeof $('.img-area #' + id).data('folderid') != 'undefined') {
            var folderid = $('.img-area #' + id).data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        var caption = $('.img-area #' + id).data('caption');
        var extra_class = $('.img-area #' + id).data('extra_class');
        var data_width = $('.img-area #' + id).data('width');
        var value = $('.img-area #' + id).val();
        var src = $('.img-area #' + id).prev().attr('src');
        var align = $('.img-area #' + id).data('type');

        $('#sectionOnlyImage input[name=editing]').val(id);
        $('#sectionOnlyImage .imgip').val(value);
        $('#sectionOnlyImage #img_title').val(caption);
        $('#sectionOnlyImage #extra_class').val(extra_class);
        $('#sectionOnlyImage #data_width').val(data_width);
        $('#sectionOnlyImage #vfolder_id').val(folderid);
        $('#frmSectionOnlyImage img:first').attr('src', src);
        $('#sectionOnlyImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionOnlyImage #addSection').text('Update');
        $('#sectionOnlyImage #exampleModalLabel b').text('Edit Image Block');

    } else {
        $('#sectionOnlyImage input[name=editing]').val('');
        $('#sectionOnlyImage .imgip').val('');
        $('#sectionOnlyImage #extra_class').val('');
        $('#sectionOnlyImage #data_width').val('');
        $('#sectionOnlyImage #img_title').val('');
        $('#sectionOnlyImage #addSection').text('Add');
        $('#sectionOnlyImage #exampleModalLabel b').text('Image Block');
    }

});
$('#sectionOnlyImage').on('shown.bs.modal', function() {
    builder.resizeOnlyImageModal();
    validateSectionOnlyImage.init();
}).on('hidden.bs.modal', function() {
    validateSectionOnlyImage.reset();
});

$(document).on('click', '.only-document', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionOnlyDocument').modal({
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).data('id');

    if ($('.add-cms-block').hasClass('clicked')) {

        var value = $('.img-document #' + id).val();
        var doccopid = $('.img-document #' + id).parents('.ui-new-section-add').find("#dochiddenid").val();

        if (id != undefined) {
            var strfid = id.split("-");
        } else {
            strfid = ''
        }

        if ($('.img-document #' + id).data('folderid') != '' && typeof $('.img-document #' + id).data('folderid') != 'undefined') {
            var folderid = $('.img-document #' + id).data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }


        $("#sectionOnlyDocument .dochtml").html('');
        if (typeof doccopid != 'undefined') {
            var DOC_URL = site_url + "/powerpanel/media/ComposerDocData";
            $.ajax({
                type: 'POST',
                url: DOC_URL,
                data: 'id=' + doccopid + '',
                success: function(html) {
                    $("#sectionOnlyDocument .dochtml").html(html);
                }
            });
        }

        if ($('.img-document #' + id).data('caption') != '' && typeof $('.img-document #' + id).data('caption') != 'undefined') {
            var caption = $('.img-document #' + id).data('caption')
        } else {
            var caption = ''
        }

        if ($('.img-document #' + id).data('doc_date_time') != '' && typeof $('.img-document #' + id).data('doc_date_time') != 'undefined') {
            var doc_date_time = $('.img-document #' + id).data('doc_date_time')
        } else {
            var doc_date_time = ''
        }

        $('#sectionOnlyDocument input[name=editing]').val(id);
        $('#sectionOnlyDocument #vfolder_id').val(folderid);
        $('#sectionOnlyDocument .imgip1').val(doccopid);
        $('#sectionOnlyDocument #caption').val(caption);
        $('#sectionOnlyDocument #doc_date_time').val(doc_date_time);
        $('#sectionOnlyDocument img:first').attr('src', CDN_PATH + 'assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionOnlyDocument #addSection').text('Update');
        $('#sectionOnlyDocument #exampleModalLabel b').text('Edit Document Block');


    } else if (typeof id != 'undefined') {

        var doccopid = $('#' + id).val();
        var strfid = id.split("-");
        if ($('.img-document #' + id).data('folderid') != '' && typeof $('.img-document #' + id).data('folderid') != 'undefined') {
            var folderid = $('.img-document #' + id).data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }

        $("#sectionOnlyDocument .dochtml").html('');
        if (typeof doccopid != 'undefined') {
            var DOC_URL = site_url + "/powerpanel/media/ComposerDocData";
            $.ajax({
                type: 'POST',
                url: DOC_URL,
                data: 'id=' + doccopid + '',
                success: function(html) {
                    $("#sectionOnlyDocument .dochtml").html(html);
                }
            });
        }

        if ($('.img-document #' + id).data('caption') != '' && typeof $('.img-document #' + id).data('caption') != 'undefined') {
            var caption = $('.img-document #' + id).data('caption')
        } else {
            var caption = ''
        }

        if ($('.img-document #' + id).data('doc_date_time') != '' && typeof $('.img-document #' + id).data('doc_date_time') != 'undefined') {
            var doc_date_time = $('.img-document #' + id).data('doc_date_time')
        } else {
            var doc_date_time = ''
        }

        var src = $('.' + id).attr('src');
        $('#sectionOnlyDocument input[name=editing]').val(id);
        $('#sectionOnlyDocument #vfolder_id').val(folderid);
        $('#sectionOnlyDocument .imgip1').val(doccopid);
        $('#sectionOnlyDocument #caption').val(caption);
        $('#sectionOnlyDocument #doc_date_time').val(doc_date_time);
        $('#sectionOnlyDocument img:first').attr('src', CDN_PATH + 'assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionOnlyDocument #addSection').text('Update');
        $('#sectionOnlyDocument #exampleModalLabel b').text('Edit Document Block');

    } else {
        $('#sectionOnlyDocument input[name=editing]').val('');
        $('#sectionOnlyDocument .imgip1').val('');
        $("#sectionOnlyDocument .dochtml").html('');
        $('#sectionOnlyDocument #img_title1').val('');
        $('#sectionOnlyDocument #caption').val('');
        $('#sectionOnlyDocument img').attr('src', CDN_PATH + 'assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionOnlyDocument #addSection').text('Add');
        $('#sectionOnlyDocument #exampleModalLabel b').text('Document Block');
    }
});
$('#sectionOnlyDocument').on('shown.bs.modal', function() {
    builder.resizeOnlyImageModal();
    validateSectionOnlyDocument.init();
    $('#sectionOnlyDocument #doc_date_time').datetimepicker({
        format: 'd-m-Y',
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: false
    });
}).on('hidden.bs.modal', function() {
    validateSectionOnlyDocument.reset();
});

$(document).on('click', '.only-video', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionVideo').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .videoclass').length == 1) {
        var caption = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .videoclass').data('caption');
        var type = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .videoclass').data('type');
        var value = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .videoclass').val();
        $('#sectionVideo #videoCaption').val(caption);
        $('#sectionVideo #videoId').val(value);
        $('#frmSectionVideo .md-radio-inline input[value=' + type + ']').prop('checked', true);
        $('#sectionVideo #addSection').text('Update');
        $('#sectionVideo #exampleModalLabel b').text('Edit Promo Video');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .videoclass').length == 1) {
        var caption = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .videoclass').data('caption');
        var type = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .videoclass').data('type');
        var value = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .videoclass').val();
        $('#sectionVideo #videoCaption').val(caption);
        $('#sectionVideo #videoId').val(value);
        $('#frmSectionVideo .md-radio-inline input[value=' + type + ']').prop('checked', true);
        $('#sectionVideo #addSection').text('Update');
        $('#sectionVideo #exampleModalLabel b').text('Edit Promo Video');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .videoclass').length == 1) {
        var caption = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .videoclass').data('caption');
        var type = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .videoclass').data('type');
        var value = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .videoclass').val();
        $('#sectionVideo #videoCaption').val(caption);
        $('#sectionVideo #videoId').val(value);
        $('#frmSectionVideo .md-radio-inline input[value=' + type + ']').prop('checked', true);
        $('#sectionVideo #addSection').text('Update');
        $('#sectionVideo #exampleModalLabel b').text('Edit Promo Video');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .videoclass').length == 1) {
        var caption = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .videoclass').data('caption');
        var type = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .videoclass').data('type');
        var value = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .videoclass').val();
        $('#sectionVideo input[name=editing]').val(id);
        $('#sectionVideo #videoCaption').val(caption);
        $('#sectionVideo #videoId').val(value);
        $('#frmSectionVideo .md-radio-inline input[value=' + type + ']').prop('checked', true);
        $('#sectionVideo #addSection').text('Update');
        $('#sectionVideo #exampleModalLabel b').text('Edit Promo Video');
    } else if (typeof id != 'undefined') {
        var caption = $('.videoOnly #' + id).data('caption');
        var type = $('.videoOnly #' + id).data('type');
        var value = $('.videoOnly #' + id).val();
        $('#sectionVideo input[name=editing]').val(id);
        $('#sectionVideo #videoCaption').val(caption);
        $('#sectionVideo #videoId').val(value);
        $('#frmSectionVideo .md-radio-inline input[value=' + type + ']').prop('checked', true);
        $('#sectionVideo #addSection').text('Update');
        $('#sectionVideo #exampleModalLabel b').text('Edit Promo Video');
    } else {
        $('#sectionVideo input[name=editing]').val('');
        $('#frmSectionVideo .md-radio-inline input[value=YouTube]').prop('checked', true);
        $('#sectionVideo #videoCaption').val('');
        $('#sectionVideo #videoId').val('');
        $('#sectionVideo #addSection').text('Add');
        $('#sectionVideo #exampleModalLabel b').text('Promo Video');
    }

});
$('#sectionVideo').on('shown.bs.modal', function() {
    //builder.resizeOnlyVideoModal();
    validateSectionOnlyVideo.init();
}).on('hidden.bs.modal', function() {
    validateSectionOnlyVideo.reset();
});

$(document).on('click', '.iframeonly', function(event) {
    if (!$(this).hasClass('section-item')) {
        $('#sectionIframe [data-dismiss="modal"]').attr("data-toggle", "");
        $('#sectionIframe [data-dismiss="modal"]').attr("data-target", "");
        $('#pgBuiderSections').modal('hide');
        $('#sectionIframe').modal({
            backdrop: 'static',
            keyboard: false
        });
        var id = $(this).data('id');
        if (typeof id != 'undefined') {
            var extclass = $('#' + id).data('class');
            var value = $('#' + id).val();
            $('#sectionIframe input[name=editing]').val(id);
            $('#sectionIframe .extraClass').val(extclass);
            $('#sectionIframe input[name=content]').val(value);
            $('#sectionIframe .addSection').text('Update');
            $('#sectionIframe #exampleModalLabel b').text('Edit Iframe');
        } else {
            $('#sectionIframe input[name=editing]').val('');
            $('#sectionIframe .extraClass').val('');
            $('#sectionIframe input[name=content]').val('');
            $('#sectionIframe .addSection').text('Add');
            $('#sectionIframe #exampleModalLabel b').text('Iframe');
            $('#sectionIframe [data-dismiss="modal"]').attr("data-toggle", "modal");
            $('#sectionIframe [data-dismiss="modal"]').attr("data-target", "#pgBuiderSections");
        }
    }
});
$('#sectionIframe').on('shown.bs.modal', function() {
    validateSectionOnlyIframe.init();
}).on('hidden.bs.modal', function() {
    validateSectionOnlyIframe.reset();
});

$(document).on('click', '.image-with-information', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionImage').modal({
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).data('id');

    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .imgcontentclass').length == 1) {
        var colData = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .imgcontentclass');
        var img = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('type');
        var content = colData.val();
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        var src = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 img').attr('src');
        $('#sectionImage .imgip').val(img);
        $('#sectionImage img:first').attr('src', src);
        $('#sectionImage #img_title').val(caption);
        $('#sectionImage #vfolder_id').val(folderid);
        $('#sectionImage textarea').val(content);
        $('#sectionImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionImage #addSection').text('Update');
        $('#sectionImage #exampleModalLabel b').text('Edit Image Block With Text');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .imgcontentclass').length == 1) {
        var colData = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .imgcontentclass');
        var img = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('type');
        var content = colData.val();
        var src = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 img').attr('src');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionImage .imgip').val(img);
        $('#sectionImage img:first').attr('src', src);
        $('#sectionImage #img_title').val(caption);
        $('#sectionImage #vfolder_id').val(folderid);
        $('#sectionImage textarea').val(content);
        $('#sectionImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionImage #addSection').text('Update');
        $('#sectionImage #exampleModalLabel b').text('Edit Image Block With Text');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .imgcontentclass').length == 1) {
        var colData = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .imgcontentclass');
        var img = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('type');
        var content = colData.val();
        var src = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 img').attr('src');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionImage .imgip').val(img);
        $('#sectionImage img:first').attr('src', src);
        $('#sectionImage #img_title').val(caption);
        $('#sectionImage #vfolder_id').val(folderid);
        $('#sectionImage textarea').val(content);
        $('#sectionImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionImage #addSection').text('Update');
        $('#sectionImage #exampleModalLabel b').text('Edit Image Block With Text');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .imgcontentclass').length == 1) {
        var colData = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .imgcontentclass');
        var img = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('type');
        var content = colData.val();
        var src = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 img').attr('src');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionImage .imgip').val(img);
        $('#sectionImage img:first').attr('src', src);
        $('#sectionImage #img_title').val(caption);
        $('#sectionImage #vfolder_id').val(folderid);
        $('#sectionImage textarea').val(content);
        $('#sectionImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionImage #addSection').text('Update');
        $('#sectionImage #exampleModalLabel b').text('Edit Image Block With Text');
    } else if (typeof id != 'undefined') {
        var strfid = id.split("-");
        if ($('.img-rt-area #' + id).data('folderid') != '' && typeof $('.img-rt-area #' + id).data('folderid') != 'undefined') {
            var folderid = $('.img-rt-area #' + id).data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        var img = $('.img-rt-area #' + id).data('id');
        var caption = $('.img-rt-area #' + id).data('caption');
        var align = $('.img-rt-area #' + id).data('type');
        var content = $('.img-rt-area #' + id).val();
        var src = $('.img-rt-area .' + id).attr('src');

        $('#sectionImage input[name=editing]').val(id);
        $('#sectionImage .imgip').val(img);
        $('#sectionImage img:first').attr('src', src);
        $('#sectionImage #img_title').val(caption);
        $('#sectionImage #vfolder_id').val(folderid);
        $('#sectionImage textarea').val(content);
        $('#sectionImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionImage #addSection').text('Update');
        $('#sectionImage #exampleModalLabel b').text('Edit Image Block With Text');
    } else {
        $('#sectionImage input[name=editing]').val('');
        $('#sectionImage .imgip').val('');
        $('#sectionImage img:first').attr('src', CDN_PATH + 'assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionImage #img_title').val('');
        $('#sectionImage textarea').val('');
        $('#sectionImage input:checked').prop('checked', false);
        $('#sectionImage #addSection').text('Add');
        $('#sectionImage #exampleModalLabel b').text('Image Block With Text');
    }

    $('#sectionImage .ck-editor').remove();

    ClassicEditor.create(document.querySelector('#sectionImage #ck-area'), cmsConfig)
        .then(editor => {
            window.sectionImageCk = editor;
        })
        .catch(error => {
            console.error(error);
        });

});
$('#sectionImage').on('shown.bs.modal', function() {
    builder.resizeImageContentModal();
    validateSectionImage.init();
}).on('hidden.bs.modal', function() {
    validateSectionImage.reset();
});


$(document).on('click', '.video-with-information', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionVideoContent').modal({
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).data('id');
    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .videocontentclass').length == 1) {
        var colData = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .videocontentclass');
        var videoid = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('aligntype');
        var content = colData.val();
        var type = colData.data('type');

        $('#sectionVideoContent #videoCaption').val(caption);
        $('#sectionVideoContent #videoId').val(videoid);
        $('#sectionVideoContent textarea').val(content);
        $('#sectionVideoContent input[value="' + align + '"]').prop('checked', true);
        $('#sectionVideoContent .md-radio-inline input[value=' + type + ']').prop('checked', true);
        $('#sectionVideoContent #addSection').text('Update');
        $('#sectionVideoContent #exampleModalLabel b').text('Edit Video Block With Text');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .videocontentclass').length == 1) {
        var colData = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .videocontentclass');
        var videoid = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('aligntype');
        var content = colData.val();
        var type = colData.data('type');

        $('#sectionVideoContent #videoCaption').val(caption);
        $('#sectionVideoContent #videoId').val(videoid);
        $('#sectionVideoContent textarea').val(content);
        $('#sectionVideoContent input[value="' + align + '"]').prop('checked', true);
        $('#sectionVideoContent .md-radio-inline input[value=' + type + ']').prop('checked', true);
        $('#sectionVideoContent #addSection').text('Update');
        $('#sectionVideoContent #exampleModalLabel b').text('Edit Video Block With Text');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .videocontentclass').length == 1) {
        var colData = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .videocontentclass');
        var videoid = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('aligntype');
        var content = colData.val();
        var type = colData.data('type');

        $('#sectionVideoContent #videoCaption').val(caption);
        $('#sectionVideoContent #videoId').val(videoid);
        $('#sectionVideoContent textarea').val(content);
        $('#sectionVideoContent input[value="' + align + '"]').prop('checked', true);
        $('#sectionVideoContent .md-radio-inline input[value=' + type + ']').prop('checked', true);
        $('#sectionVideoContent #addSection').text('Update');
        $('#sectionVideoContent #exampleModalLabel b').text('Edit Video Block With Text');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .videocontentclass').length == 1) {
        var colData = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .videocontentclass');
        var videoid = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('aligntype');
        var content = colData.val();
        var type = colData.data('type');

        $('#sectionVideoContent #videoCaption').val(caption);
        $('#sectionVideoContent #videoId').val(videoid);
        $('#sectionVideoContent textarea').val(content);
        $('#sectionVideoContent input[value="' + align + '"]').prop('checked', true);
        $('#sectionVideoContent .md-radio-inline input[value=' + type + ']').prop('checked', true);
        $('#sectionVideoContent #addSection').text('Update');
        $('#sectionVideoContent #exampleModalLabel b').text('Edit Video Block With Text');
    } else if (typeof id != 'undefined') {
        var videoid = $('.videoContent #' + id).data('id');
        var caption = $('.videoContent #' + id).data('caption');
        var align = $('.videoContent #' + id).data('aligntype');
        var content = $('.videoContent #' + id).val();
        var type = $('.videoContent #' + id).data('type');

        $('#sectionVideoContent input[name=editing]').val(id);
        $('#sectionVideoContent #videoCaption').val(caption);
        $('#sectionVideoContent #videoId').val(videoid);
        $('#sectionVideoContent textarea').val(content);
        $('#sectionVideoContent input[value="' + align + '"]').prop('checked', true);
        $('#sectionVideoContent .md-radio-inline input[value=' + type + ']').prop('checked', true);
        $('#sectionVideoContent #addSection').text('Update');
        $('#sectionVideoContent #exampleModalLabel b').text('Edit Video Block With Text');
    } else {

        $('#sectionVideoContent input[name=editing]').val('');
        $('#sectionVideoContent #videoCaption').val('');
        $('#sectionVideoContent #videoId').val('');
        $('#sectionVideoContent textarea').val('');
        $('#sectionVideoContent input:checked').prop('checked', false);
        $('#sectionVideoContent .md-radio-inline input[value=YouTube]').prop('checked', true);
        $('#sectionVideoContent #addSection').text('Add');
        $('#sectionVideoContent #exampleModalLabel b').text('Video Block With Text');
    }

    $('#sectionVideoContent .ck-editor').remove();

    ClassicEditor.create(document.querySelector('#sectionVideoContent #ck-area'), cmsConfig)
        .then(editor => {
            window.sectionImageCk = editor;
        })
        .catch(error => {
            console.error(error);
        });

});
$('#sectionVideoContent').on('shown.bs.modal', function() {
    builder.resizeImageContentModal();
    validateSectionVideoContent.init();
}).on('hidden.bs.modal', function() {
    validateSectionVideoContent.reset();
});

$(document).on('click', '.google-map', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionMap').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .mapclass').length == 1) {
        var colData = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .mapclass');
        var latitude = colData.data('latitude');
        var longitude = colData.data('longitude');
        var align = colData.data('type');
        $('#sectionMap #img_latitude').val(latitude);
        $('#sectionMap #img_longitude').val(longitude);
        $('#sectionMap input[value="' + align + '"]').prop('checked', true);
        $('#sectionMap #addSection').text('Update');
        $('#sectionMap #exampleModalLabel b').text('Edit Google Map');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .mapclass').length == 1) {
        var colData = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .mapclass');
        var latitude = colData.data('latitude');
        var longitude = colData.data('longitude');
        var align = colData.data('type');
        $('#sectionMap #img_latitude').val(latitude);
        $('#sectionMap #img_longitude').val(longitude);
        $('#sectionMap input[value="' + align + '"]').prop('checked', true);
        $('#sectionMap #addSection').text('Update');
        $('#sectionMap #exampleModalLabel b').text('Edit Google Map');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .mapclass').length == 1) {
        var colData = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .mapclass');
        var latitude = colData.data('latitude');
        var longitude = colData.data('longitude');
        var align = colData.data('type');
        $('#sectionMap #img_latitude').val(latitude);
        $('#sectionMap #img_longitude').val(longitude);
        $('#sectionMap input[value="' + align + '"]').prop('checked', true);
        $('#sectionMap #addSection').text('Update');
        $('#sectionMap #exampleModalLabel b').text('Edit Google Map');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .mapclass').length == 1) {
        var colData = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .mapclass');
        var latitude = colData.data('latitude');
        var longitude = colData.data('longitude');
        var align = colData.data('type');
        $('#sectionMap #img_latitude').val(latitude);
        $('#sectionMap #img_longitude').val(longitude);
        $('#sectionMap input[value="' + align + '"]').prop('checked', true);
        $('#sectionMap #addSection').text('Update');
        $('#sectionMap #exampleModalLabel b').text('Edit Google Map');
    } else if (typeof id != 'undefined') {
        var latitude = $('.img-map #' + id).data('latitude');
        var longitude = $('.img-map #' + id).data('longitude');
        var align = $('.img-map #' + id).data('type');
        $('#sectionMap input[name=editing]').val(id);
        $('#sectionMap #img_latitude').val(latitude);
        $('#sectionMap #img_longitude').val(longitude);
        $('#sectionMap input[value="' + align + '"]').prop('checked', true);
        $('#sectionMap #addSection').text('Update');
        $('#sectionMap #exampleModalLabel b').text('Edit Google Map');
    } else {
        $('#sectionMap input[name=editing]').val('');
        $('#sectionMap #img_latitude').val('');
        $('#sectionMap #img_longitude').val('');
        $('#sectionMap #addSection').text('Add');
        $('#sectionMap #exampleModalLabel b').text('Google Map');
    }
});
$('#sectionMap').on('shown.bs.modal', function() {
    builder.resizeOnlyImageModal();
    validatesectionMap.init();
    var latval = document.getElementById("img_latitude").value;
    var longval = document.getElementById("img_longitude").value;
    //                                alert(longval);
    if (latval == '' && longval == '') {
        latval = '19.3133333';
        longval = '-81.2566667';
        //                latval = 19.2833333;
        //                longval = -81.2566667;
    }
    var markers = [];
    var defaultposition;
    var mapOptions = {
        zoom: 11,
        streetViewControl: false,
        center: new google.maps.LatLng(latval, longval),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById('map'), mapOptions);
    defaultposition = new google.maps.LatLng(latval, longval);
    addMarker(defaultposition);
    google.maps.event.addListener(map, 'click', function(event) {
        clearMarkers();
        addMarker(event.latLng);

        document.getElementById("img_latitude").value = event.latLng.lat();
        document.getElementById("img_longitude").value = event.latLng.lng();
    });

    function addMarker(location) {
        var marker = new google.maps.Marker({
            animation: google.maps.Animation.DROP,
            position: location,
            draggable: true,
            map: map
        });
        markers.push(marker);
        google.maps.event.addListener(marker, 'dragend', function() {
            var pointposition = marker.position;
            document.getElementById("img_latitude").value = pointposition.lat();
            document.getElementById("img_longitude").value = pointposition.lng();
        });
    }

    function clearMarkers() {
        setAllMap(null);
    }

    function setAllMap(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }
}).on('hidden.bs.modal', function() {
    validatesectionMap.reset();
});


$(document).on('click', '.contact-info', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionContactInfo').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .contactinfoclass').length == 1) {
        $('#sectionContactInfo #section_address').val($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .contactinfoclass').data('address'));
        $('#sectionContactInfo #section_email').val($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .contactinfoclass').data('email'));
        $('#sectionContactInfo #section_phone').val($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .contactinfoclass').data('phone'));
        $('#sectionContactInfo  #ck-area').val($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .contactinfoclass').val());
        $('#sectionContactInfo #addSection').text('Update');
        $('#sectionContactInfo #exampleModalLabel b').text('Edit Contact Info');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .contactinfoclass').length == 1) {
        $('#sectionContactInfo #section_address').val($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .contactinfoclass').data('address'));
        $('#sectionContactInfo #section_email').val($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .contactinfoclass').data('email'));
        $('#sectionContactInfo #section_phone').val($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .contactinfoclass').data('phone'));
        $('#sectionContactInfo  #ck-area').val($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .contactinfoclass').val());
        $('#sectionContactInfo #addSection').text('Update');
        $('#sectionContactInfo #exampleModalLabel b').text('Edit Contact Info');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .contactinfoclass').length == 1) {
        $('#sectionContactInfo #section_address').val($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .contactinfoclass').data('address'));
        $('#sectionContactInfo #section_email').val($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .contactinfoclass').data('email'));
        $('#sectionContactInfo #section_phone').val($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .contactinfoclass').data('phone'));
        $('#sectionContactInfo  #ck-area').val($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .contactinfoclass').val());
        $('#sectionContactInfo #addSection').text('Update');
        $('#sectionContactInfo #exampleModalLabel b').text('Edit Contact Info');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .contactinfoclass').length == 1) {
        $('#sectionContactInfo #section_address').val($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .contactinfoclass').data('address'));
        $('#sectionContactInfo #section_email').val($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .contactinfoclass').data('email'));
        $('#sectionContactInfo #section_phone').val($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .contactinfoclass').data('phone'));
        $('#sectionContactInfo  #ck-area').val($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .contactinfoclass').val());
        $('#sectionContactInfo #addSection').text('Update');
        $('#sectionContactInfo #exampleModalLabel b').text('Edit Contact Info');
    } else if (typeof id != 'undefined') {
        var section_address = $('.contactInfoOnly #' + id).data('address');
        var section_email = $('.contactInfoOnly #' + id).data('email');
        var section_phone = $('.contactInfoOnly #' + id).data('phone');
        var value = $('.contactInfoOnly #' + id).val();
        $('#sectionContactInfo input[name=editing]').val(id);
        $('#sectionContactInfo #section_address').val(section_address);
        $('#sectionContactInfo #section_email').val(section_email);
        $('#sectionContactInfo #section_phone').val(section_phone);
        $('#sectionContactInfo  #ck-area').val(value);
        $('#sectionContactInfo #addSection').text('Update');
        $('#sectionContactInfo #exampleModalLabel b').text('Edit Contact Info');
    } else {
        $('#sectionContactInfo input[name=editing]').val('');
        $('#sectionContactInfo #section_address').val('');
        $('#sectionContactInfo #section_email').val('');
        $('#sectionContactInfo #section_phone').val('');
        $('#sectionContactInfo #ck-area').val('');
        $('#sectionContactInfo #addSection').text('Add');
        $('#sectionContactInfo #exampleModalLabel b').text('Add Contact Info');
    }

    $('#sectionContactInfo .ck-editor').remove();
    ClassicEditor.create(document.querySelector('#sectionContactInfo #ck-area'), cmsConfig)
        .then(editor => {
            window.sectionInfoCk = editor;
        })
        .catch(error => {
            console.error(error);
        });
});
$('#sectionContactInfo').on('shown.bs.modal', function() {
    builder.resizeOnlyTitleModal();
    validateSectionContactInfo.init();
}).on('hidden.bs.modal', function() {
    validateSectionContactInfo.reset();
});

$(document).on('click', '.section-button', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionButton').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');

    if ($('.add-cms-block').hasClass('clicked')) {

        if (id != '' && id != undefined) {

            var caption = $('.buttonInfoOnly #' + id).data('caption');
            var target = $('.buttonInfoOnly #' + id).data('linktarget');
            var align = $('.buttonInfoOnly #' + id).data('type');
            var value = $('.buttonInfoOnly #' + id).val();

            $('#sectionButton input[name=editing]').val(id);
            $('#sectionButton input[name=section_title]').val(caption);
            $('#sectionButton #section_link').val(value);
            $('#sectionButton select[name=section_button_target] option[value=' + target + ']').prop('selected', true);
            $('#sectionButton input[name=selector][value="' + align + '"]').prop('checked', true);
            $('#sectionButton #addSection').text('Update');
            $('#sectionButton #exampleModalLabel b').text('Edit Button Info');

        } else {

            $('#sectionButton input[name=editing]').val('');
            $('#sectionButton input[name=section_title]').val('');
            $('#sectionButton #section_link').val('');
            $('#sectionButton #section_button_target option').removeAttr("selected");
            $('#sectionButton input[name=selector]:checked').prop('checked', false);
            $('#sectionButton #addSection').text('Add');
            $('#sectionButton #exampleModalLabel b').text('Add Button');

        }


    } else if (typeof id != 'undefined') {

        var caption = $('.buttonInfoOnly #' + id).data('caption');
        var target = $('.buttonInfoOnly #' + id).data('linktarget');
        var align = $('.buttonInfoOnly #' + id).data('type');
        var value = $('.buttonInfoOnly #' + id).val();
        $('#sectionButton input[name=editing]').val(id);
        $('#sectionButton input[name=section_title]').val(caption);
        $('#sectionButton #section_link').val(value);
        $('#sectionButton select[name=section_button_target] option[value=' + target + ']').prop('selected', true);
        $('#sectionButton input[name=selector][value="' + align + '"]').prop('checked', true);
        $('#sectionButton #addSection').text('Update');
        $('#sectionButton #exampleModalLabel b').text('Edit Button Info');

    } else {

        $('#sectionButton input[name=editing]').val('');
        $('#sectionButton input[name=section_title]').val('');
        $('#sectionButton #section_link').val('');
        $('#sectionButton #section_button_target option').removeAttr("selected");
        $('#sectionButton input[name=selector]:checked').prop('checked', false);
        $('#sectionButton #addSection').text('Add');
        $('#sectionButton #exampleModalLabel b').text('Add Button');
    }

});
$('#sectionButton').on('shown.bs.modal', function() {
    $('#sectionButton select').selectpicker();
    builder.resizeOnlyTitleModal();
    validateSectionButton.init();
}).on('hidden.bs.modal', function() {
    $('#sectionButton select').selectpicker('destroy');
    validateSectionButton.reset();
});

$(document).on('click', '.two-part-content', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectiontwoContent').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .twotextareaclass').length == 1) {
        var extclass = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .twotextareaclass').data('class');
        var value = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .twotextareaclass').val();
        var right = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .twotextareaclass').data('content');
        $('#sectiontwoContent #extraClass').val(extclass);
        $('#sectiontwoContent #leftck-area').val(value);
        $('#sectiontwoContent #rightck-area').val(right);
        $('#sectiontwoContent #addSection').text('Update');
        $('#sectiontwoContent #exampleModalLabel b').text('Edit 2 Part Contents');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .twotextareaclass').length == 1) {
        var extclass = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .twotextareaclass').data('class');
        var value = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .twotextareaclass').val();
        var right = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .twotextareaclass').data('content');
        $('#sectiontwoContent #extraClass').val(extclass);
        $('#sectiontwoContent #leftck-area').val(value);
        $('#sectiontwoContent #rightck-area').val(right);
        $('#sectiontwoContent #addSection').text('Update');
        $('#sectiontwoContent #exampleModalLabel b').text('Edit 2 Part Contents');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .twotextareaclass').length == 1) {
        var extclass = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .twotextareaclass').data('class');
        var value = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .twotextareaclass').val();
        var right = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .twotextareaclass').data('content');
        $('#sectiontwoContent #extraClass').val(extclass);
        $('#sectiontwoContent #leftck-area').val(value);
        $('#sectiontwoContent #rightck-area').val(right);
        $('#sectiontwoContent #addSection').text('Update');
        $('#sectiontwoContent #exampleModalLabel b').text('Edit 2 Part Contents');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .twotextareaclass').length == 1) {
        var extclass = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .twotextareaclass').data('class');
        var value = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .twotextareaclass').val();
        var right = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .twotextareaclass').data('content');
        $('#sectiontwoContent #extraClass').val(extclass);
        $('#sectiontwoContent #leftck-area').val(value);
        $('#sectiontwoContent #rightck-area').val(right);
        $('#sectiontwoContent #addSection').text('Update');
        $('#sectiontwoContent #exampleModalLabel b').text('Edit 2 Part Contents');
    } else if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var right = $('.two-part #' + id).data('content');
        var value = $('.two-part #' + id).val();
        $('#sectiontwoContent input[name=editing]').val(id);
        $('#sectiontwoContent #extraClass').val(extclass);
        $('#sectiontwoContent #leftck-area').val(value);
        $('#sectiontwoContent #rightck-area').val(right);
        $('#sectiontwoContent #addSection').text('Update');
        $('#sectiontwoContent #exampleModalLabel b').text('Edit 2 Part Contents');
    } else {
        $('#sectiontwoContent input[name=editing]').val('');
        $('#sectiontwoContent #extraClass').val('');
        $('#sectiontwoContent textarea').val('');
        $('#sectiontwoContent #addSection').text('Add');
        $('#sectiontwoContent #exampleModalLabel b').text('2 Part Contents');
    }
    $('#sectiontwoContent .ck-editor').remove();
    ClassicEditor.create(document.querySelector('#sectiontwoContent #leftck-area'), cmsConfig)
        .then(editor => {
            window.sectionleftContentCk = editor;
        })
        .catch(error => {
            console.error(error);
        });

    ClassicEditor.create(document.querySelector('#sectiontwoContent #rightck-area'), cmsConfig)
        .then(editor => {
            window.sectionrightContentCk = editor;
        })
        .catch(error => {
            console.error(error);
        });
});
$('#sectiontwoContent').on('shown.bs.modal', function() {
    builder.resizeOnlyContentModal();
    validateSectionTwoContent.init();
}).on('hidden.bs.modal', function() {
    validateSectionTwoContent.reset();
});

$(document).on('click', '.only-spacer', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionSpacerTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var config = '';
    if (typeof id != 'undefined') {
        config = $('#' + id).data('config');
        $('#sectionSpacerTemplate input[name=editing]').val(id);
        $('#sectionSpacerTemplate select[name=section_spacer] option[value=' + config + ']').prop('selected', true);

        $('#sectionSpacerTemplate #addSection').text('Update');
        $('#sectionSpacerTemplate #exampleModalLabel b').text('Edit Content Spacer');
    } else {
        $('#sectionSpacerTemplate input[name=editing]').val('');
        $('#sectionSpacerTemplate #addSection').text('Add');
        $('#sectionSpacerTemplate #exampleModalLabel b').text('Add Content Spacer');
    }
    $('#sectionSpacerTemplate input[name=template]').val($(this).data('filter'));
});
$('#sectionSpacerTemplate').on('shown.bs.modal', function() {
    $('#sectionSpacerTemplate select').selectpicker();
    validateSpacerTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionSpacerTemplate select').selectpicker('destroy');
    validateSpacerTemplate.reset();
});

$(document).on('click', '.home-information', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionHomeImage').modal({
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).data('id');

    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .homeimagecontclass').length == 1) {
        var colData = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .homeimagecontclass');
        var img = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('type');
        var content = colData.val();
        var src = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 img').attr('src');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionHomeImage .imgip').val(img);
        $('#sectionHomeImage img:first').attr('src', src);
        $('#sectionHomeImage #img_title').val(caption);
        $('#sectionHomeImage #vfolder_id').val(folderid);
        $('#sectionHomeImage textarea').val(content);
        $('#sectionHomeImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionHomeImage #addSection').text('Update');
        $('#sectionHomeImage #exampleModalLabel b').text('Edit Home Page Welcome Section');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .homeimagecontclass').length == 1) {
        var colData = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .homeimagecontclass');
        var img = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('type');
        var content = colData.val();
        var src = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 img').attr('src');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionHomeImage .imgip').val(img);
        $('#sectionHomeImage img:first').attr('src', src);
        $('#sectionHomeImage #img_title').val(caption);
        $('#sectionHomeImage #vfolder_id').val(folderid);
        $('#sectionHomeImage textarea').val(content);
        $('#sectionHomeImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionHomeImage #addSection').text('Update');
        $('#sectionHomeImage #exampleModalLabel b').text('Edit Home Page Welcome Section');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .homeimagecontclass').length == 1) {
        var colData = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .homeimagecontclass');
        var img = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('type');
        var content = colData.val();
        var src = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 img').attr('src');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionHomeImage .imgip').val(img);
        $('#sectionHomeImage img:first').attr('src', src);
        $('#sectionHomeImage #img_title').val(caption);
        $('#sectionHomeImage #vfolder_id').val(folderid);
        $('#sectionHomeImage textarea').val(content);
        $('#sectionHomeImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionHomeImage #addSection').text('Update');
        $('#sectionHomeImage #exampleModalLabel b').text('Edit Home Page Welcome Section');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .homeimagecontclass').length == 1) {
        var colData = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .homeimagecontclass');
        var img = colData.data('id');
        var caption = colData.data('caption');
        var align = colData.data('type');
        var content = colData.val();
        var src = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 img').attr('src');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionHomeImage .imgip').val(img);
        $('#sectionHomeImage img:first').attr('src', src);
        $('#sectionHomeImage #img_title').val(caption);
        $('#sectionHomeImage #vfolder_id').val(folderid);
        $('#sectionHomeImage textarea').val(content);
        $('#sectionHomeImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionHomeImage #addSection').text('Update');
        $('#sectionHomeImage #exampleModalLabel b').text('Edit Home Page Welcome Section');
    } else if (typeof id != 'undefined') {
        var strfid = id.split("-");
        if ($('.home-img-rt-area #' + id).data('folderid') != '' && typeof $('.home-img-rt-area #' + id).data('folderid') != 'undefined') {
            var folderid = $('.home-img-rt-area #' + id).data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        var img = $('.home-img-rt-area #' + id).data('id');
        var caption = $('.home-img-rt-area #' + id).data('caption');
        var align = $('.home-img-rt-area #' + id).data('type');
        var content = $('.home-img-rt-area #' + id).val();
        var src = $('.home-img-rt-area .' + id).attr('src');

        $('#sectionHomeImage input[name=editing]').val(id);
        $('#sectionHomeImage .imgip').val(img);
        $('#sectionHomeImage img:first').attr('src', src);
        $('#sectionHomeImage #img_title').val(caption);
        $('#sectionHomeImage #vfolder_id').val(folderid);
        $('#sectionHomeImage textarea').val(content);
        $('#sectionHomeImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionHomeImage #addSection').text('Update');
        $('#sectionHomeImage #exampleModalLabel b').text('Edit Home Page Welcome Section');
    } else {
        $('#sectionHomeImage input[name=editing]').val('');
        $('#sectionHomeImage .imgip').val('');
        $('#sectionHomeImage img:first').attr('src', CDN_PATH + 'assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionHomeImage #img_title').val('');
        $('#sectionHomeImage textarea').val('');
        $('#sectionHomeImage input:checked').prop('checked', false);
        $('#sectionHomeImage #addSection').text('Add');
        $('#sectionHomeImage #exampleModalLabel b').text('Home Page Welcome Section');
    }

    $('#sectionHomeImage .ck-editor').remove();

    ClassicEditor.create(document.querySelector('#sectionHomeImage #ck-area'), cmsConfig)
        .then(editor => {
            window.sectionImageCk = editor;
        })
        .catch(error => {
            console.error(error);
        });

});
$('#sectionHomeImage').on('shown.bs.modal', function() {
    builder.resizeImageContentModal();
    validateSectionHomeImage.init();
}).on('hidden.bs.modal', function() {
    validateSectionHomeImage.reset();
});


$(document).on('click', '.custom-section', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#customSectionBase').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var baseTitle = $('#' + id).val();
    var subtitle = $('#' + id).val();
    var extclass = $('#' + id).attr('data-extclass');
    var layoutType = $('#' + id).attr('data-layout');


    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .customSection').length == 1) {

        var colData = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .customSection');

        $('#customSectionBase input[name="title"]').val(colData.val());
        $('#customSectionBase input[name="subtitle"]').val(colData.val());
        $('#customSectionBase select[name="layoutType"]').val(colData.attr('data-layout'));
        $('#customSectionBase input[name="extra_class"]').val(colData.attr('data-extclass'));

        $('#customSectionBase #addSection').text('Update');
        $('#customSectionBase #exampleModalLabel b').text('Edit Custom Block');


    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .customSection').length == 1) {


        var colData = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .customSection');

        $('#customSectionBase input[name="title"]').val(colData.val());
        $('#customSectionBase input[name="subtitle"]').val(colData.val());
        $('#customSectionBase select[name="layoutType"]').val(colData.attr('data-layout'));
        $('#customSectionBase input[name="extra_class"]').val(colData.attr('data-extclass'));

        $('#customSectionBase #addSection').text('Update');
        $('#customSectionBase #exampleModalLabel b').text('Edit Custom Block');

    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .customSection').length == 1) {

        var colData = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .customSection');

        $('#customSectionBase input[name="title"]').val(colData.val());
        $('#customSectionBase input[name="subtitle"]').val(colData.val());
        $('#customSectionBase select[name="layoutType"]').val(colData.attr('data-layout'));
        $('#customSectionBase input[name="extra_class"]').val(colData.attr('data-extclass'));

        $('#customSectionBase #addSection').text('Update');
        $('#customSectionBase #exampleModalLabel b').text('Edit Custom Block');

    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .customSection').length == 1) {

        var colData = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .customSection');

        $('#customSectionBase input[name="title"]').val(colData.val());
        $('#customSectionBase input[name="subtitle"]').val(colData.val());
        $('#customSectionBase select[name="layoutType"]').val(colData.attr('data-layout'));
        $('#customSectionBase input[name="extra_class"]').val(colData.attr('data-extclass'));

        $('#customSectionBase #addSection').text('Update');
        $('#customSectionBase #exampleModalLabel b').text('Edit Custom Block');

    } else if (typeof id != 'undefined') {

        $('#frmCustomSectionBase input[name=editing]').val(id);
        $('#frmCustomSectionBase input[name=subtitle]').val(subtitle);
        $('#frmCustomSectionBase input[name=title]').val(baseTitle);
        $('#frmCustomSectionBase input[name=extra_class]').val(extclass);
        $('#frmCustomSectionBase select[name=layoutType]').val(layoutType);

        $('#frmCustomSectionBase #addSection').text('Update');
        $('#frmCustomSectionBase #exampleModalLabel b').text('Edit Custom Block');

    } else {

        $('#frmCustomSectionBase input[name=editing]').val(null);
        $('#frmCustomSectionBase input[name=title]').val(null);
        $('#frmCustomSectionBase input[name=subtitle]').val(null);
        $('#frmCustomSectionBase select[name=layoutType]').val(null);
        $('#frmCustomSectionBase input[name=extra_class]').val(null);
        $('#frmCustomSectionBase #addSection').text('Add');
        $('#frmCustomSectionBase #exampleModalLabel b').text('Custom Block');
    }
});

$('#customSectionBase').on('shown.bs.modal', function() {
    $('#customSectionBase select').selectpicker();
    validateCustomSectionBase.init();
}).on('hidden.bs.modal', function() {
    $('#customSectionBase select').selectpicker('destroy');
    validateCustomSectionBase.reset();
});

$(document).on('click', '.add-custom-record', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#customSection').modal({
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).data('id');
    var mode = $(this).data('mode');

    if (typeof id != 'undefined') {
        $('#customSection input[name=sectionid]').val(id);
        $('#customSection #addSection').text('Add');
        $('#customSection #exampleModalLabel b').text('Custom Cards');
    }

    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .add-custom-record').length == 1) {

        var colData = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .add-custom-record');

        $('#customSection img').attr('src', colData.data('imgsrc'));
        $('#customSection #custom_section_image').val(colData.data('img'));
        $('#customSection #title').val(colData.data('title'));
        $('#customSection #link').val(colData.data('link'));
        $('#customSection #ck-area').val(colData.data('desc'));
        id = colData.parent().attr('id');
        $('#customSection input[name=editing]').val(id);

        $('#customSection #addSection').text('Update');
        $('#customSection #exampleModalLabel b').text('Edit Custom Record');


    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .add-custom-record').length == 1) {


        var colData = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .add-custom-record');

        $('#customSection img').attr('src', colData.data('imgsrc'));
        $('#customSection #custom_section_image').val(colData.data('img'));
        $('#customSection #title').val(colData.data('title'));
        $('#customSection #link').val(colData.data('link'));
        $('#customSection #ck-area').val(colData.data('desc'));
        id = colData.parent().attr('id');
        $('#customSection input[name=editing]').val(id);

        $('#customSection #addSection').text('Update');
        $('#customSection #exampleModalLabel b').text('Edit Custom Record');

    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .add-custom-record').length == 1) {

        var colData = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .add-custom-record');

        $('#customSection img').attr('src', colData.data('imgsrc'));
        $('#customSection #custom_section_image').val(colData.data('img'));
        $('#customSection #title').val(colData.data('title'));
        $('#customSection #link').val(colData.data('link'));
        $('#customSection #ck-area').val(colData.data('desc'));
        id = colData.parent().attr('id');
        $('#customSection input[name=editing]').val(id);

        $('#customSection #addSection').text('Update');
        $('#customSection #exampleModalLabel b').text('Edit Custom Record');

    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .add-custom-record').length == 1) {

        var colData = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .add-custom-record');

        $('#customSection img').attr('src', colData.data('imgsrc'));
        $('#customSection #custom_section_image').val(colData.data('img'));
        $('#customSection #title').val(colData.data('title'));
        $('#customSection #link').val(colData.data('link'));
        $('#customSection #ck-area').val(colData.data('desc'));
        id = colData.parent().attr('id');
        $('#customSection input[name=editing]').val(id);

        $('#customSection #addSection').text('Update');
        $('#customSection #exampleModalLabel b').text('Edit Custom Record');

    } else if (typeof mode != 'undefined') {
        $('#customSection img').attr('src', colData.data('imgsrc'));
        $('#customSection #custom_section_image').val(colData.data('img'));
        $('#customSection #title').val(colData.data('title'));
        $('#customSection #link').val(colData.data('link'));
        $('#customSection #ck-area').val(colData.data('desc'));
        id = colData.parent().attr('id');
        $('#customSection input[name=editing]').val(id);
        $('#customSection #addSection').text('Update');
        $('#customSection #exampleModalLabel b').text('Edit Custom Record');
    }

    ClassicEditor.create(document.querySelector('#customSection #ck-area'), cmsConfig)
        .then(editor => {
            window.customRecordCk = editor;
        })
        .catch(error => {
            console.error(error);
        });

});

$('#customSection').on('shown.bs.modal', function() {

}).on('hidden.bs.modal', function() {
    customRecordCk.setData('');
    customRecordCk.destroy();
    $('#customSection img').attr('src', CDN_PATH + 'assets/images/packages/visualcomposer/plus-no-image.png');
    $('#customSection #custom_section_image').val(null);
    $('#customSection #title').val(null);
    $('#customSection #link').val(null);
    $('#customSection #ck-area').val(null);
    $('#customSection input[name=sectionid]').val(null);
    $('#customSection input[name=editing]').val(null);
});


$('#frmCustomSection').on('submit', function(e) {
    e.preventDefault();
    var imgid = $('#customSection #custom_section_image').val();
    var imgsrc = $('#customSection img').attr('src');
    var title = $('#customSection #title').val();
    var link = $('#customSection #link').val();
    var desc = customRecordCk.getData();
    var sectionid = $('#customSection input[name=sectionid]').val();
    var edit = $('#customSection input[name=editing]').val();
    builder.addCustomRecord(sectionid, imgid, imgsrc, title, link, desc, edit);
});


$(document).on('click', '.image-gallery', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionGalleryImage').modal({
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).data('id');
    if (typeof id != 'undefined') {
        var layout = $('#' + id).data('layout');
        $('#sectionGalleryImage input[name=editing]').val(id);
        $('#sectionGalleryImage input[name=position][value=' + layout + ']').prop('checked', true).trigger('change');
        var images = [];
        $('.section-item[data-editor=' + id + '] li').each(function(key, val) {
            images.push($(this).attr('id').match(/\d+/));
        });
        $('#sectionGalleryImage input[name=img_id]').val(images);
        $('#sectionGalleryImage #addSection').text('Update');
        $('#sectionGalleryImage #exampleModalLabel b').text('Edit Image Gallery');
    } else {
        $('#sectionGalleryImage input[name=editing]').val(null);
        $('#sectionGalleryImage input[name=position][value=lightbox]').prop('checked', true).trigger('change');
        $('#sectionGalleryImage #exampleModalLabel b').text('Image Gallery');
        $('#sectionGalleryImage #addSection').text('Add');
    }
});
$('#sectionGalleryImage').on('shown.bs.modal', function() {
    //builder.resizeOnlyImageModal();
    validateSectionGalleryImage.init();
}).on('hidden.bs.modal', function() {
    $('#sectionGalleryImage #photo_gallery_image_img').html('');
    $('#sectionGalleryImage input[name=img_id]').val(null);
    validateSectionGalleryImage.reset();
});


$(document).on('click', '.events-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionEventsModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var ajaxUrl = site_url + '/powerpanel/event-category/getAllCategory';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        async: false,
        success: function(result) {
            $('#sectionEventsModuleTemplate #cat-template-layout').html(result);
        },
        complete: function() {

        }
    });

    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    var eventscat = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');
        eventscat = $('#' + id).data('eventscat');

        $('#sectionEventsModuleTemplate input[name=editing]').val(id);
        $('#sectionEventsModuleTemplate #section_title').val($.trim(value));
        $('#sectionEventsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionEventsModuleTemplate #section_description').val(sdesc);
        $('#sectionEventsModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionEventsModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionEventsModuleTemplate #extra_class').val(extclass);
        if (eventscat != '') {
            $('#sectionEventsModuleTemplate select[name=eventscat] option[value=' + eventscat + ']').prop('selected', true);
        }
        $('#sectionEventsModuleTemplate #events_start_date_time').val(startdate);
        $('#sectionEventsModuleTemplate #events_end_date_time').val(enddate);
        $('#sectionEventsModuleTemplate #addSection').text('Update');
        $('#sectionEventsModuleTemplate #exampleModalLabel b').text('Edit Events');
    } else {
        var value = $(this).text();
        $('#sectionEventsModuleTemplate input[name=editing]').val('');
        $('#sectionEventsModuleTemplate #section_title').val($.trim(value));
        $('#sectionEventsModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionEventsModuleTemplate #extra_class').val(extclass);
        $('#sectionEventsModuleTemplate select[name=eventscat] option:first').prop('selected', true);
        $('#sectionEventsModuleTemplate #events_start_date_time').val(startdate);
        $('#sectionEventsModuleTemplate #events_end_date_time').val(enddate);
        $('#sectionEventsModuleTemplate #addSection').text('Add');
        $('#sectionEventsModuleTemplate #exampleModalLabel b').text('Add Events');
    }
    $('#sectionEventsModuleTemplate #events_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionEventsModuleTemplate #events_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionEventsModuleTemplate #events_start_date_time').val() ? jQuery('#sectionEventsModuleTemplate #events_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionEventsModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionEventsModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionEventsModuleTemplate select').selectpicker();
    validateEventsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionEventsModuleTemplate select').selectpicker('destroy');
    validateEventsTemplate.reset();
});


$(document).on('click', '.blogs-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionBlogsModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var ajaxUrl = site_url + '/powerpanel/blog-category/getAllCategory';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        async: false,
        success: function(result) {
            $('#sectionBlogsModuleTemplate #cat-template-layout').html(result);
        },
        complete: function() {

        }
    });

    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    var blogscat = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');
        blogscat = $('#' + id).data('blogscat');

        $('#sectionBlogsModuleTemplate input[name=editing]').val(id);
        $('#sectionBlogsModuleTemplate #section_title').val($.trim(value));
        $('#sectionBlogsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionBlogsModuleTemplate #section_description').val(sdesc);
        $('#sectionBlogsModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionBlogsModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionBlogsModuleTemplate #extra_class').val(extclass);
        if (blogscat != '') {
            $('#sectionBlogsModuleTemplate select[name=blogscat] option[value=' + blogscat + ']').prop('selected', true);
        }
        $('#sectionBlogsModuleTemplate #blogs_start_date_time').val(startdate);
        $('#sectionBlogsModuleTemplate #blogs_end_date_time').val(enddate);
        $('#sectionBlogsModuleTemplate #addSection').text('Update');
        $('#sectionBlogsModuleTemplate #exampleModalLabel b').text('Edit Blogs');
    } else {
        var value = $(this).text();
        $('#sectionBlogsModuleTemplate input[name=editing]').val('');
        $('#sectionBlogsModuleTemplate #section_title').val($.trim(value));
        $('#sectionBlogsModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionBlogsModuleTemplate #extra_class').val(extclass);
        $('#sectionBlogsModuleTemplate select[name=blogscat] option:first').prop('selected', true);
        $('#sectionBlogsModuleTemplate #blogs_start_date_time').val(startdate);
        $('#sectionBlogsModuleTemplate #blogs_end_date_time').val(enddate);
        $('#sectionBlogsModuleTemplate #addSection').text('Add');
        $('#sectionBlogsModuleTemplate #exampleModalLabel b').text('Add Blogs');
    }
    $('#sectionBlogsModuleTemplate #blogs_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionBlogsModuleTemplate #blogs_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionBlogsModuleTemplate #blogs_start_date_time').val() ? jQuery('#sectionBlogsModuleTemplate #blogs_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionBlogsModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionBlogsModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionBlogsModuleTemplate select').selectpicker();
    validateBlogsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionBlogsModuleTemplate select').selectpicker('destroy');
    validateBlogsTemplate.reset();
});

$(document).on('click', '.number-allocations', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionNumberAllocationsModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');

        $('#sectionNumberAllocationsModuleTemplate input[name=editing]').val(id);
        $('#sectionNumberAllocationsModuleTemplate #section_title').val($.trim(value));
        $('#sectionNumberAllocationsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionNumberAllocationsModuleTemplate #section_description').val(sdesc);
        $('#sectionNumberAllocationsModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionNumberAllocationsModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionNumberAllocationsModuleTemplate #extra_class').val(extclass);
        $('#sectionNumberAllocationsModuleTemplate #addSection').text('Update');
        $('#sectionNumberAllocationsModuleTemplate #exampleModalLabel b').text('Edit Number Allocations');
    } else {
        var value = $(this).text();
        $('#sectionNumberAllocationsModuleTemplate input[name=editing]').val('');
        $('#sectionNumberAllocationsModuleTemplate #section_title').val($.trim(value));
        $('#sectionNumberAllocationsModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionNumberAllocationsModuleTemplate #extra_class').val(extclass);
        $('#sectionNumberAllocationsModuleTemplate #addSection').text('Add');
        $('#sectionNumberAllocationsModuleTemplate #exampleModalLabel b').text('Add Number Allocations');
    }

    $('#sectionNumberAllocationsModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionNumberAllocationsModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionNumberAllocationsModuleTemplate select').selectpicker();
    validateNumberAllocationsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionNumberAllocationsModuleTemplate select').selectpicker('destroy');
    validateNumberAllocationsTemplate.reset();
});


$(document).on('click', '.service-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionServiceModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var ajaxUrl = site_url + '/powerpanel/service-category/getAllCategory';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        async: false,
        success: function(result) {

            $('#sectionServiceModuleTemplate #cat-template-layout').html(result);
        },
        complete: function() {

        }
    });

    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';

    var servicecat = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');

        servicecat = $('#' + id).data('servicecat');

        $('#sectionServiceModuleTemplate input[name=editing]').val(id);
        $('#sectionServiceModuleTemplate #section_title').val($.trim(value));
        $('#sectionServiceModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionServiceModuleTemplate #section_description').val(sdesc);
        $('#sectionServiceModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionServiceModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionServiceModuleTemplate #extra_class').val(extclass);
        if (servicecat != '') {
            $('#sectionServiceModuleTemplate select[name=servicecat] option[value=' + servicecat + ']').prop('selected', true);
        }

        $('#sectionServiceModuleTemplate #addSection').text('Update');
        $('#sectionServiceModuleTemplate #exampleModalLabel b').text('Edit Service');
    } else {
        var value = $(this).text();
        $('#sectionServiceModuleTemplate input[name=editing]').val('');
        $('#sectionServiceModuleTemplate #section_title').val($.trim(value));
        $('#sectionServiceModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionServiceModuleTemplate #extra_class').val(extclass);
        $('#sectionServiceModuleTemplate select[name=servicecat] option:first').prop('selected', true);

        $('#sectionServiceModuleTemplate #addSection').text('Add');
        $('#sectionServiceModuleTemplate #exampleModalLabel b').text('Add Service');
    }
    $('#sectionServiceModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionServiceModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionServiceModuleTemplate select').selectpicker();
    validateServiceTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionServiceModuleTemplate select').selectpicker('destroy');
    validateServiceTemplate.reset();
});





$(document).on('click', '.candwservice-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectioncandwserviceModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });



    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';

    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');

        $('#sectioncandwserviceModuleTemplate input[name=editing]').val(id);
        $('#sectioncandwserviceModuleTemplate #section_title').val($.trim(value));
        $('#sectioncandwserviceModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectioncandwserviceModuleTemplate #section_description').val(sdesc);
        $('#sectioncandwserviceModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectioncandwserviceModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectioncandwserviceModuleTemplate #extra_class').val(extclass);

        $('#sectioncandwserviceModuleTemplate #candwservice_start_date_time').val(startdate);
        $('#sectioncandwserviceModuleTemplate #candwservice_end_date_time').val(enddate);
        $('#sectioncandwserviceModuleTemplate #addSection').text('Update');
        $('#sectioncandwserviceModuleTemplate #exampleModalLabel b').text('Edit CandWService');
    } else {
        var value = $(this).text();
        $('#sectioncandwserviceModuleTemplate input[name=editing]').val('');
        $('#sectioncandwserviceModuleTemplate #section_title').val($.trim(value));
        $('#sectioncandwserviceModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectioncandwserviceModuleTemplate #extra_class').val(extclass);

        $('#sectioncandwserviceModuleTemplate #candwservice_start_date_time').val(startdate);
        $('#sectioncandwserviceModuleTemplate #candwservice_end_date_time').val(enddate);
        $('#sectioncandwserviceModuleTemplate #addSection').text('Add');
        $('#sectioncandwserviceModuleTemplate #exampleModalLabel b').text('Add CandWService');
    }
    $('#sectioncandwserviceModuleTemplate #candwservice_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectioncandwserviceModuleTemplate #candwservice_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectioncandwserviceModuleTemplate #candwservice_start_date_time').val() ? jQuery('#sectioncandwserviceModuleTemplate #candwservice_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectioncandwserviceModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectioncandwserviceModuleTemplate').on('shown.bs.modal', function() {
    $('#sectioncandwserviceModuleTemplate select').selectpicker();
    validateCandWServiceTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectioncandwserviceModuleTemplate select').selectpicker('destroy');
    validateCandWServiceTemplate.reset();
});





$(document).on('click', '.consultations-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionConsultationsModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });



    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    var blogscat = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');
        blogscat = $('#' + id).data('blogscat');

        $('#sectionConsultationsModuleTemplate input[name=editing]').val(id);
        $('#sectionConsultationsModuleTemplate #section_title').val($.trim(value));
        $('#sectionConsultationsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionConsultationsModuleTemplate #section_description').val(sdesc);
        $('#sectionConsultationsModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionConsultationsModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionConsultationsModuleTemplate #extra_class').val(extclass);
        if (blogscat != '') {
            $('#sectionConsultationsModuleTemplate select[name=blogscat] option[value=' + blogscat + ']').prop('selected', true);
        }
        $('#sectionConsultationsModuleTemplate #consultations_start_date_time').val(startdate);
        $('#sectionConsultationsModuleTemplate #consultations_end_date_time').val(enddate);
        $('#sectionConsultationsModuleTemplate #addSection').text('Update');
        $('#sectionConsultationsModuleTemplate #exampleModalLabel b').text('Edit Consultations');
    } else {
        var value = $(this).text();
        $('#sectionConsultationsModuleTemplate input[name=editing]').val('');
        $('#sectionConsultationsModuleTemplate #section_title').val($.trim(value));
        $('#sectionConsultationsModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionConsultationsModuleTemplate #extra_class').val(extclass);
        $('#sectionConsultationsModuleTemplate select[name=blogscat] option:first').prop('selected', true);
        $('#sectionConsultationsModuleTemplate #consultations_start_date_time').val(startdate);
        $('#sectionConsultationsModuleTemplate #consultations_end_date_time').val(enddate);
        $('#sectionConsultationsModuleTemplate #addSection').text('Add');
        $('#sectionConsultationsModuleTemplate #exampleModalLabel b').text('Add Consultations');
    }
    $('#sectionConsultationsModuleTemplate #consultations_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionConsultationsModuleTemplate #consultations_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionConsultationsModuleTemplate #consultations_start_date_time').val() ? jQuery('#sectionConsultationsModuleTemplate #consultations_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionConsultationsModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionConsultationsModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionConsultationsModuleTemplate select').selectpicker();
    validateConsultationsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionConsultationsModuleTemplate select').selectpicker('destroy');
    validateConsultationsTemplate.reset();
});


$(document).on('click', '.complaint-services-template', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionComplaintServicesModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });



    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    var blogscat = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');
        blogscat = $('#' + id).data('blogscat');

        $('#sectionComplaintServicesModuleTemplate input[name=editing]').val(id);
        $('#sectionComplaintServicesModuleTemplate #section_title').val($.trim(value));
        $('#sectionComplaintServicesModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionComplaintServicesModuleTemplate #section_description').val(sdesc);
        $('#sectionComplaintServicesModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionComplaintServicesModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionComplaintServicesModuleTemplate #extra_class').val(extclass);

        $('#sectionComplaintServicesModuleTemplate #addSection').text('Update');
        $('#sectionComplaintServicesModuleTemplate #exampleModalLabel b').text('Edit Complaint Services');
    } else {
        var value = $(this).text();
        $('#sectionComplaintServicesModuleTemplate input[name=editing]').val('');
        $('#sectionComplaintServicesModuleTemplate #section_title').val($.trim(value));
        $('#sectionComplaintServicesModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionComplaintServicesModuleTemplate #extra_class').val(extclass);
        $('#sectionComplaintServicesModuleTemplate select[name=blogscat] option:first').prop('selected', true);

        $('#sectionComplaintServicesModuleTemplate #addSection').text('Add');
        $('#sectionComplaintServicesModuleTemplate #exampleModalLabel b').text('Add Complaint Services');
    }

    $('#sectionComplaintServicesModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionComplaintServicesModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionComplaintServicesModuleTemplate select').selectpicker();
    validateComplaintServicesTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionComplaintServicesModuleTemplate select').selectpicker('destroy');
    validateComplaintServicesTemplate.reset();
});


$(document).on('click', '.fmbroadcasting-template', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionFMBroadcastingModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });



    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';

    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');


        $('#sectionFMBroadcastingModuleTemplate input[name=editing]').val(id);
        $('#sectionFMBroadcastingModuleTemplate #section_title').val($.trim(value));
        $('#sectionFMBroadcastingModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionFMBroadcastingModuleTemplate #section_description').val(sdesc);
        $('#sectionFMBroadcastingModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionFMBroadcastingModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionFMBroadcastingModuleTemplate #extra_class').val(extclass);

        $('#sectionFMBroadcastingModuleTemplate #addSection').text('Update');
        $('#sectionFMBroadcastingModuleTemplate #exampleModalLabel b').text('Edit FM Broadcasting');
    } else {
        var value = $(this).text();
        $('#sectionFMBroadcastingModuleTemplate input[name=editing]').val('');
        $('#sectionFMBroadcastingModuleTemplate #section_title').val($.trim(value));
        $('#sectionFMBroadcastingModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionFMBroadcastingModuleTemplate #extra_class').val(extclass);
        $('#sectionFMBroadcastingModuleTemplate select[name=blogscat] option:first').prop('selected', true);

        $('#sectionFMBroadcastingModuleTemplate #addSection').text('Add');
        $('#sectionFMBroadcastingModuleTemplate #exampleModalLabel b').text('Add FM Broadcasting');
    }

    $('#sectionFMBroadcastingModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionFMBroadcastingModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionFMBroadcastingModuleTemplate select').selectpicker();
    validateFMBroadcastingTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionFMBroadcastingModuleTemplate select').selectpicker('destroy');
    validateFMBroadcastingTemplate.reset();
});





$(document).on('click', '.boardofdirectors-template', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionBoardofDirectorsTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });



    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';

    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');


        $('#sectionBoardofDirectorsTemplate input[name=editing]').val(id);
        $('#sectionBoardofDirectorsTemplate #section_title').val($.trim(value));
        $('#sectionBoardofDirectorsTemplate #section_limit').val($.trim(slimit));
        $('#sectionBoardofDirectorsTemplate #section_description').val(sdesc);
        $('#sectionBoardofDirectorsTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionBoardofDirectorsTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionBoardofDirectorsTemplate #extra_class').val(extclass);
        $('#sectionBoardofDirectorsTemplate #addSection').text('Update');
        $('#sectionBoardofDirectorsTemplate #exampleModalLabel b').text('Edit Board of Directors');
    } else {
        var value = $(this).text();
        $('#sectionBoardofDirectorsTemplate input[name=editing]').val('');
        $('#sectionBoardofDirectorsTemplate #section_title').val($.trim(value));
        $('#sectionBoardofDirectorsTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionBoardofDirectorsTemplate #extra_class').val(extclass);
        $('#sectionBoardofDirectorsTemplate #addSection').text('Add');
        $('#sectionBoardofDirectorsTemplate #exampleModalLabel b').text('Add Board of Directors');
    }

    $('#sectionBoardofDirectorsTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionBoardofDirectorsTemplate').on('shown.bs.modal', function() {
    $('#sectionBoardofDirectorsTemplate select').selectpicker();
    validateBoardofDirectorsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionBoardofDirectorsTemplate select').selectpicker('destroy');
    validateBoardofDirectorsTemplate.reset();
});


$(document).on('click', '.register-application-template', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionRegisterofApplicationsModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });



    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';

    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');


        $('#sectionRegisterofApplicationsModuleTemplate input[name=editing]').val(id);
        $('#sectionRegisterofApplicationsModuleTemplate #section_title').val($.trim(value));
        $('#sectionRegisterofApplicationsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionRegisterofApplicationsModuleTemplate #section_description').val(sdesc);
        $('#sectionRegisterofApplicationsModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionRegisterofApplicationsModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionRegisterofApplicationsModuleTemplate #extra_class').val(extclass);
        $('#sectionRegisterofApplicationsModuleTemplate #addSection').text('Update');
        $('#sectionRegisterofApplicationsModuleTemplate #exampleModalLabel b').text('Edit Register of Applications');
    } else {
        var value = $(this).text();
        $('#sectionRegisterofApplicationsModuleTemplate input[name=editing]').val('');
        $('#sectionRegisterofApplicationsModuleTemplate #section_title').val($.trim(value));
        $('#sectionRegisterofApplicationsModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionRegisterofApplicationsModuleTemplate #extra_class').val(extclass);
        $('#sectionRegisterofApplicationsModuleTemplate #addSection').text('Add');
        $('#sectionRegisterofApplicationsModuleTemplate #exampleModalLabel b').text('Add Register of Applications');
    }

    $('#sectionRegisterofApplicationsModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionRegisterofApplicationsModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionRegisterofApplicationsModuleTemplate select').selectpicker();
    validateRegisterofApplicationsTemplate.init();
}).on('hidden.bs.modalvalidateRegisterofApplicationsTemplate', function() {
    $('#sectionRegisterofApplicationsModuleTemplate select').selectpicker('destroy');
    validateRegisterofApplicationsTemplate.reset();
});


$(document).on('click', '.licence-register-template', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionLicenceRegisterModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });



    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';

    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');


        $('#sectionLicenceRegisterModuleTemplate input[name=editing]').val(id);
        $('#sectionLicenceRegisterModuleTemplate #section_title').val($.trim(value));
        $('#sectionLicenceRegisterModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionLicenceRegisterModuleTemplate #section_description').val(sdesc);
        $('#sectionLicenceRegisterModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionLicenceRegisterModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionLicenceRegisterModuleTemplate #extra_class').val(extclass);
        $('#sectionLicenceRegisterModuleTemplate #addSection').text('Update');
        $('#sectionLicenceRegisterModuleTemplate #exampleModalLabel b').text('Edit Licence Register');
    } else {
        var value = $(this).text();
        $('#sectionLicenceRegisterModuleTemplate input[name=editing]').val('');
        $('#sectionLicenceRegisterModuleTemplate #section_title').val($.trim(value));
        $('#sectionLicenceRegisterModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionLicenceRegisterModuleTemplate #extra_class').val(extclass);
        $('#sectionLicenceRegisterModuleTemplate #addSection').text('Add');
        $('#sectionLicenceRegisterModuleTemplate #exampleModalLabel b').text('Add Licence Register');
    }

    $('#sectionLicenceRegisterModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionLicenceRegisterModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionLicenceRegisterModuleTemplate select').selectpicker();
    validateLicenceRegisterTemplate.init();
}).on('hidden.bs.modalvalidateRegisterofApplicationsTemplate', function() {
    $('#sectionLicenceRegisterModuleTemplate select').selectpicker('destroy');
    validateLicenceRegisterTemplate.reset();
});

$(document).on('click', '.forms-and-fees-template', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionFormsAndFeesModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });



    var id = $(this).data('id');

    var slimit = '';

    var extclass = '';

    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();

        slimit = $('#' + id).data('slimit');

        extclass = $('#' + id).data('class');


        $('#sectionFormsAndFeesModuleTemplate input[name=editing]').val(id);
        $('#sectionFormsAndFeesModuleTemplate #section_title').val($.trim(value));
        $('#sectionFormsAndFeesModuleTemplate #section_limit').val($.trim(slimit));

        $('#sectionFormsAndFeesModuleTemplate #extra_class').val(extclass);
        $('#sectionFormsAndFeesModuleTemplate #addSection').text('Update');
        $('#sectionFormsAndFeesModuleTemplate #exampleModalLabel b').text('Edit Forms and Fees');
    } else {
        var value = $(this).text();
        $('#sectionFormsAndFeesModuleTemplate input[name=editing]').val('');
        $('#sectionFormsAndFeesModuleTemplate #section_title').val($.trim(value));

        $('#sectionFormsAndFeesModuleTemplate #extra_class').val(extclass);
        $('#sectionFormsAndFeesModuleTemplate #addSection').text('Add');
        $('#sectionFormsAndFeesModuleTemplate #exampleModalLabel b').text('Add Forms and Fees');
    }

    $('#sectionFormsAndFeesModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionFormsAndFeesModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionFormsAndFeesModuleTemplate select').selectpicker();
    validateFormsAndFeesTemplate.init();
}).on('hidden.bs.modalvalidateRegisterofApplicationsTemplate', function() {
    $('#sectionFormsAndFeesModuleTemplate select').selectpicker('destroy');
    validateFormsAndFeesTemplate.reset();
});



$(document).on('click', '.publication-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionPublicationModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var ajaxUrl = site_url + '/powerpanel/publications-category/getAllCategory';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        async: false,
        success: function(result) {
            $('#sectionPublicationModuleTemplate #cat-template-layout').html(result);
        },
        complete: function() {

        }
    });

    var id = $(this).data('id');
    var sector = '';
    var slimit = '';

    var config = '';
    var extclass = '';

    var publicationscat = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        sector = $('#' + id).data('sector');

        slimit = $('#' + id).data('slimit');

        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');
        publicationscat = $('#' + id).data('publicationscat');

        $('#sectionPublicationModuleTemplate input[name=editing]').val(id);
        $('#sectionPublicationModuleTemplate #section_title').val($.trim(value));
        $('#sectionPublicationModuleTemplate #section_limit').val($.trim(slimit));

        $('#sectionPublicationModuleTemplate #extra_class').val(extclass);
        if (publicationscat != '') {
            $('#sectionPublicationModuleTemplate select[name=publicationscat] option[value=' + publicationscat + ']').prop('selected', true);
        }
        $('#sectionPublicationModuleTemplate select[name=sectortype] option[value=' + sector + ']').prop('selected', true);

        $('#sectionPublicationModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionPublicationModuleTemplate #addSection').text('Update');
        $('#sectionPublicationModuleTemplate #exampleModalLabel b').text('Edit Publication');
    } else {
        var value = $(this).text();
        $('#sectionPublicationModuleTemplate input[name=editing]').val('');
        $('#sectionPublicationModuleTemplate #section_title').val($.trim(value));
        $('#sectionPublicationModuleTemplate #extra_class').val(extclass);
        $('#sectionPublicationModuleTemplate select[name=publicationscat] option:first').prop('selected', true);
        $('#sectionPublicationModuleTemplate select[name=sectortype] option:first').prop('selected', true);

        $('#sectionPublicationModuleTemplate #addSection').text('Add');
        $('#sectionPublicationModuleTemplate #exampleModalLabel b').text('Add Publication');
    }

    $('#sectionPublicationModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionPublicationModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionPublicationModuleTemplate select').selectpicker();
    validatePublicationTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionPublicationModuleTemplate select').selectpicker('destroy');
    validatePublicationTemplate.reset();
});

$(document).on('click', '.photoalbum-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionPhotoAlbumModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');

        $('#sectionPhotoAlbumModuleTemplate input[name=editing]').val(id);
        $('#sectionPhotoAlbumModuleTemplate #section_title').val($.trim(value));
        $('#sectionPhotoAlbumModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionPhotoAlbumModuleTemplate #section_description').val(sdesc);
        $('#sectionPhotoAlbumModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionPhotoAlbumModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionPhotoAlbumModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionPhotoAlbumModuleTemplate #extra_class').val(extclass);
        $('#sectionPhotoAlbumModuleTemplate #photo_start_date_time').val(startdate);
        $('#sectionPhotoAlbumModuleTemplate #photo_end_date_time').val(enddate);
        $('#sectionPhotoAlbumModuleTemplate #addSection').text('Update');
        $('#sectionPhotoAlbumModuleTemplate #exampleModalLabel b').text('Edit Photo Album');
    } else {
        var value = $(this).text();
        $('#sectionPhotoAlbumModuleTemplate input[name=editing]').val('');
        $('#sectionPhotoAlbumModuleTemplate #section_title').val($.trim(value));
        $('#sectionPhotoAlbumModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionPhotoAlbumModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionPhotoAlbumModuleTemplate #extra_class').val(extclass);
        $('#sectionPhotoAlbumModuleTemplate #photo_start_date_time').val(startdate);
        $('#sectionPhotoAlbumModuleTemplate #photo_end_date_time').val(enddate);
        $('#sectionPhotoAlbumModuleTemplate #addSection').text('Add');
        $('#sectionPhotoAlbumModuleTemplate #exampleModalLabel b').text('Add Photo Album');
    }
    $('#sectionPhotoAlbumModuleTemplate #photo_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionPhotoAlbumModuleTemplate #photo_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionPhotoAlbumModuleTemplate #photo_start_date_time').val() ? jQuery('#sectionPhotoAlbumModuleTemplate #photo_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionPhotoAlbumModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionPhotoAlbumModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionPhotoAlbumModuleTemplate select').selectpicker();
    validatePhotoAlbumTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionPhotoAlbumModuleTemplate select').selectpicker('destroy');
    validatePhotoAlbumTemplate.reset();
});


$(document).on('click', '.videogallery-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionVideoAlbumModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');

        $('#sectionVideoAlbumModuleTemplate input[name=editing]').val(id);
        $('#sectionVideoAlbumModuleTemplate #section_title').val($.trim(value));
        $('#sectionVideoAlbumModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionVideoAlbumModuleTemplate #section_description').val(sdesc);
        $('#sectionVideoAlbumModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionVideoAlbumModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionVideoAlbumModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionVideoAlbumModuleTemplate #extra_class').val(extclass);
        $('#sectionVideoAlbumModuleTemplate #photo_start_date_time').val(startdate);
        $('#sectionVideoAlbumModuleTemplate #photo_end_date_time').val(enddate);
        $('#sectionVideoAlbumModuleTemplate #addSection').text('Update');
        $('#sectionVideoAlbumModuleTemplate #exampleModalLabel b').text('Edit Video Gallery');
    } else {
        var value = $(this).text();
        $('#sectionVideoAlbumModuleTemplate input[name=editing]').val('');
        $('#sectionVideoAlbumModuleTemplate #section_title').val($.trim(value));
        $('#sectionVideoAlbumModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionVideoAlbumModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionVideoAlbumModuleTemplate #extra_class').val(extclass);
        $('#sectionVideoAlbumModuleTemplate #photo_start_date_time').val(startdate);
        $('#sectionVideoAlbumModuleTemplate #photo_end_date_time').val(enddate);
        $('#sectionVideoAlbumModuleTemplate #addSection').text('Add');
        $('#sectionVideoAlbumModuleTemplate #exampleModalLabel b').text('Add Video Gallery');
    }
    $('#sectionVideoAlbumModuleTemplate #photo_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionVideoAlbumModuleTemplate #photo_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionVideoAlbumModuleTemplate #photo_start_date_time').val() ? jQuery('#sectionVideoAlbumModuleTemplate #photo_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionVideoAlbumModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionVideoAlbumModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionVideoAlbumModuleTemplate select').selectpicker();
    validateVideoAlbumTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionVideoAlbumModuleTemplate select').selectpicker('destroy');
    validateVideoAlbumTemplate.reset();
});


$(document).on('click', '.news-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionNewsModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var ajaxUrl = site_url + '/powerpanel/news-category/getAllCategory';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        async: false,
        success: function(result) {
            $('#sectionNewsModuleTemplate #cat-template-layout').html(result);
        },
        complete: function() {

        }
    });

    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var slimit = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    var newscat = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');
        slimit = $('#' + id).data('slimit');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');
        newscat = $('#' + id).data('newscat');

        $('#sectionNewsModuleTemplate input[name=editing]').val(id);
        $('#sectionNewsModuleTemplate #section_title').val($.trim(value));
        $('#sectionNewsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionNewsModuleTemplate #section_description').val(sdesc);
        $('#sectionNewsModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionNewsModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionNewsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionNewsModuleTemplate #extra_class').val(extclass);
        if (newscat != '') {
            $('#sectionNewsModuleTemplate select[name=newscat] option[value=' + newscat + ']').prop('selected', true);
        }
        $('#sectionNewsModuleTemplate #news_start_date_time').val(startdate);
        $('#sectionNewsModuleTemplate #news_end_date_time').val(enddate);
        $('#sectionNewsModuleTemplate #addSection').text('Update');
        $('#sectionNewsModuleTemplate #exampleModalLabel b').text('Edit News');
    } else {
        var value = $(this).text();
        $('#sectionNewsModuleTemplate input[name=editing]').val('');
        $('#sectionNewsModuleTemplate #section_title').val($.trim(value));
        $('#sectionNewsModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionNewsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionNewsModuleTemplate #extra_class').val(extclass);
        $('#sectionNewsModuleTemplate select[name=newscat] option:first').prop('selected', true);
        $('#sectionNewsModuleTemplate #news_start_date_time').val(startdate);
        $('#sectionNewsModuleTemplate #news_end_date_time').val(enddate);
        $('#sectionNewsModuleTemplate #addSection').text('Add');
        $('#sectionNewsModuleTemplate #exampleModalLabel b').text('Add News');
    }
    $('#sectionNewsModuleTemplate #news_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionNewsModuleTemplate #news_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionNewsModuleTemplate #news_start_date_time').val() ? jQuery('#sectionNewsModuleTemplate #news_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionNewsModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionNewsModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionNewsModuleTemplate select').selectpicker();
    validateNewsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionNewsModuleTemplate select').selectpicker('destroy');
    validateNewsTemplate.reset();
});

$(document).on('click', '.public-record-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionPublicRecordModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var ajaxUrl = site_url + '/powerpanel/public-record-category/getAllCategory';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        async: false,
        success: function(result) {
            $('#sectionPublicRecordModuleTemplate #cat-template-layout').html(result);
        },
        complete: function() {

        }
    });

    var id = $(this).data('id');
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var slimit = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    var newscat = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        slimit = $('#' + id).data('slimit');
        sdesc = $('#' + id).data('sdesc');
        config = $('#' + id).data('config');
        extclass = $('#' + id).data('class');
        slimit = $('#' + id).data('slimit');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');
        newscat = $('#' + id).data('newscat');

        $('#sectionPublicRecordModuleTemplate input[name=editing]').val(id);
        $('#sectionPublicRecordModuleTemplate #section_title').val($.trim(value));
        $('#sectionPublicRecordModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionPublicRecordModuleTemplate #section_description').val(sdesc);
        $('#sectionPublicRecordModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionPublicRecordModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionPublicRecordModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionPublicRecordModuleTemplate #extra_class').val(extclass);
        if (newscat != '') {
            $('#sectionPublicRecordModuleTemplate select[name=newscat] option[value=' + newscat + ']').prop('selected', true);
        }
        $('#sectionPublicRecordModuleTemplate #news_start_date_time').val(startdate);
        $('#sectionPublicRecordModuleTemplate #news_end_date_time').val(enddate);
        $('#sectionPublicRecordModuleTemplate #addSection').text('Update');
        $('#sectionPublicRecordModuleTemplate #exampleModalLabel b').text('Edit Public Records');
    } else {
        var value = $(this).text();
        $('#sectionPublicRecordModuleTemplate input[name=editing]').val('');
        $('#sectionPublicRecordModuleTemplate #section_title').val($.trim(value));
        $('#sectionPublicRecordModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionPublicRecordModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionPublicRecordModuleTemplate #extra_class').val(extclass);
        $('#sectionPublicRecordModuleTemplate select[name=newscat] option:first').prop('selected', true);
        $('#sectionPublicRecordModuleTemplate #news_start_date_time').val(startdate);
        $('#sectionPublicRecordModuleTemplate #news_end_date_time').val(enddate);
        $('#sectionPublicRecordModuleTemplate #addSection').text('Add');
        $('#sectionPublicRecordModuleTemplate #exampleModalLabel b').text('Add Public Records');
    }
    $('#sectionPublicRecordModuleTemplate #news_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionPublicRecordModuleTemplate #news_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionPublicRecordModuleTemplate #news_start_date_time').val() ? jQuery('#sectionPublicRecordModuleTemplate #news_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionPublicRecordModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionPublicRecordModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionPublicRecordModuleTemplate select').selectpicker();
    validatePublicRecordTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionPublicRecordModuleTemplate select').selectpicker('destroy');
    validatePublicRecordTemplate.reset();
});


$(document).on('click', '.quick-links-template', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionQuickLinkTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var extclass = '';
    var startdate = '';
    var enddate = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');
        $('#sectionQuickLinkTemplate input[name=editing]').val(id);
        $('#sectionQuickLinkTemplate #section_title').val($.trim(value));
        $('#sectionQuickLinkTemplate #extra_class').val(extclass);
        $('#sectionQuickLinkTemplate #addSection').text('Update');
        $('#sectionQuickLinkTemplate #exampleModalLabel b').text('Edit Quick Links');
        $('#sectionQuickLinkTemplate #qlink_start_date_time').val(startdate);
        $('#sectionQuickLinkTemplate #qlink_end_date_time').val(enddate);
    } else {

        var value = $(this).text();
        $('#sectionQuickLinkTemplate input[name=editing]').val('');
        $('#sectionQuickLinkTemplate #section_title').val($.trim(value));
        $('#sectionQuickLinkTemplate #extra_class').val(extclass);
        $('#sectionQuickLinkTemplate #qlink_start_date_time').val(startdate);
        $('#sectionQuickLinkTemplate #qlink_end_date_time').val(enddate);
        $('#sectionQuickLinkTemplate #addSection').text('Add');
        $('#sectionQuickLinkTemplate #exampleModalLabel b').text('Add Quick Links');
    }

    $('#sectionQuickLinkTemplate #qlink_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionQuickLinkTemplate #qlink_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionQuickLinkTemplate #qlink_start_date_time').val() ? jQuery('#sectionQuickLinkTemplate #qlink_start_date_time').val() : false
            })
        },
        timepicker: true
    });

    $('#sectionQuickLinkTemplate input[name=template]').val($(this).data('filter'));
});
$('#sectionQuickLinkTemplate').on('shown.bs.modal', function() {
    $('#sectionQuickLinkTemplate select').selectpicker();
    validateQuickLinkTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionQuickLinkTemplate select').selectpicker('destroy');
    validateQuickLinkTemplate.reset();
});

$(document).on('click', '.latest-news-template', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionLatestNewsTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var extclass = '';

    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();

        $('#sectionLatestNewsTemplate input[name=editing]').val(id);
        $('#sectionLatestNewsTemplate #section_title').val($.trim(value));
        $('#sectionLatestNewsTemplate #extra_class').val(extclass);
        $('#sectionLatestNewsTemplate #addSection').text('Update');
        $('#sectionLatestNewsTemplate #exampleModalLabel b').text('Edit News');

    } else {

        var value = $(this).text();
        $('#sectionLatestNewsTemplate input[name=editing]').val('');
        $('#sectionLatestNewsTemplate #section_title').val($.trim(value));
        $('#sectionLatestNewsTemplate #extra_class').val(extclass);

        $('#sectionLatestNewsTemplate #addSection').text('Add');
        $('#sectionLatestNewsTemplate #exampleModalLabel b').text('Add News');
    }
    $('#sectionLatestNewsTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionLatestNewsTemplate').on('shown.bs.modal', function() {
    $('#sectionLatestNewsTemplate select').selectpicker();
    validateLatestNewsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionLatestNewsTemplate select').selectpicker('destroy');
    validateLatestNewsTemplate.reset();
});

$(document).on('click', '.alerts-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionAlertsModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var type = '';
    var slimit = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    if (typeof id != 'undefined') {
        extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        type = $('#' + id).data('alerttype');
        slimit = $('#' + id).data('slimit');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');

        $('#sectionAlertsModuleTemplate input[name=editing]').val(id);
        $('#sectionAlertsModuleTemplate #section_title').val($.trim(value));
        $('#sectionAlertsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionAlertsModuleTemplate #extra_class').val(extclass);
        $('#sectionAlertsModuleTemplate #alert_start_date_time').val(startdate);
        $('#sectionAlertsModuleTemplate #alert_end_date_time').val(enddate);
        if (type != '') {
            $('#sectionAlertsModuleTemplate select[name=alertType] option[value=' + type + ']').prop('selected', true);
        }
        $('#sectionAlertsModuleTemplate #addSection').text('Update');
        $('#sectionAlertsModuleTemplate #exampleModalLabel b').text('Edit Alerts');
    } else {
        var value = $(this).text();
        $('#sectionAlertsModuleTemplate input[name=editing]').val('');
        $('#sectionAlertsModuleTemplate #section_title').val($.trim(value));
        $('#sectionAlertsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionAlertsModuleTemplate #extra_class').val(extclass);
        $('#sectionAlertsModuleTemplate #alert_start_date_time').val(startdate);
        $('#sectionAlertsModuleTemplate #alert_end_date_time').val(enddate);
        $('#sectionAlertsModuleTemplate select[name=alertType] option:first').prop('selected', true);
        $('#sectionAlertsModuleTemplate #addSection').text('Add');
        $('#sectionAlertsModuleTemplate #exampleModalLabel b').text('Add Alerts');
    }
    $('#sectionAlertsModuleTemplate #alert_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionAlertsModuleTemplate #alert_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionAlertsModuleTemplate #alert_start_date_time').val() ? jQuery('#sectionAlertsModuleTemplate #alert_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionAlertsModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionAlertsModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionAlertsModuleTemplate select').selectpicker();
    validateAlertsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionAlertsModuleTemplate select').selectpicker('destroy');
    validateAlertsTemplate.reset();
});


$(document).on('click', '.department-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionDepartmentModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var slimit = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    if (typeof id != 'undefined') {
        extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        slimit = $('#' + id).data('slimit');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');

        $('#sectionDepartmentModuleTemplate input[name=editing]').val(id);
        $('#sectionDepartmentModuleTemplate #section_title').val($.trim(value));
        $('#sectionDepartmentModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionDepartmentModuleTemplate #extra_class').val(extclass);
        $('#sectionDepartmentModuleTemplate #department_start_date_time').val(startdate);
        $('#sectionDepartmentModuleTemplate #department_end_date_time').val(enddate);
        $('#sectionDepartmentModuleTemplate #addSection').text('Update');
        $('#sectionDepartmentModuleTemplate #exampleModalLabel b').text('Edit Department');
    } else {
        var value = $(this).text();
        $('#sectionDepartmentModuleTemplate input[name=editing]').val('');
        $('#sectionDepartmentModuleTemplate #section_title').val($.trim(value));
        $('#sectionDepartmentModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionDepartmentModuleTemplate #extra_class').val(extclass);
        $('#sectionDepartmentModuleTemplate #department_start_date_time').val(startdate);
        $('#sectionDepartmentModuleTemplate #department_end_date_time').val(enddate);
        $('#sectionDepartmentModuleTemplate #addSection').text('Add');
        $('#sectionDepartmentModuleTemplate #exampleModalLabel b').text('Add Department');
    }
    $('#sectionDepartmentModuleTemplate #department_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionDepartmentModuleTemplate #department_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionDepartmentModuleTemplate #department_start_date_time').val() ? jQuery('#sectionDepartmentModuleTemplate #department_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionDepartmentModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionDepartmentModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionDepartmentModuleTemplate select').selectpicker();
    validateDepartmentTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionDepartmentModuleTemplate select').selectpicker('destroy');
    validateDepartmentTemplate.reset();
});





$(document).on('click', '.organizations', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionOrganizationsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
    var ajaxUrl = site_url + '/powerpanel/organizations/getAllParents';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        async: false,
        success: function(result) {
            $('#sectionOrganizationsModule #organization-template-layout').html(result);
        },
        complete: function() {

        }
    });
    var id = $(this).data('id');
    var parentorg = '';
    var extclass = '';
    if (typeof id != 'undefined') {
        extclass = $('#' + id).data('orgclass');
        var value = $('#' + id).val();
        parentorg = $('#' + id).data('parentorg');
        $('#sectionOrganizationsModule input[name=editing]').val(id);
        $('#sectionOrganizationsModule #section_title').val($.trim(value));
        $('#sectionOrganizationsModule #extra_class').val(extclass);
        $('#sectionOrganizationsModule select[name=parentorg] option[value=' + parentorg + ']').prop('selected', true);
        $('#sectionOrganizationsModule #addSection').text('Update');
        $('#sectionOrganizationsModule #exampleModalLabel b').text('Edit Organizations');
    } else {
        var value = $(this).text();
        $('#sectionOrganizationsModule input[name=editing]').val('');
        $('#sectionOrganizationsModule #section_title').val($.trim(value));
        $('#sectionOrganizationsModule #extra_class').val('');
        $('#sectionOrganizationsModule select[name=parentorg] option:first').prop('selected', true);
        $('#sectionOrganizationsModule #addSection').text('Add');
        $('#sectionOrganizationsModule #exampleModalLabel b').text('Add Organizations');
    }
    $('#sectionOrganizationsModule input[name=template]').val($(this).data('filter'));
});

$('#sectionOrganizationsModule').on('shown.bs.modal', function() {
    $('#sectionOrganizationsModule select').selectpicker();
    validateOrganizationsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionOrganizationsModule select').selectpicker('destroy');
    validateOrganizationsTemplate.reset();
});



$(document).on('click', '.interconnections', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionInterconnectionsModule').modal({
        backdrop: 'static',
        keyboard: false
    });
    var ajaxUrl = site_url + '/powerpanel/interconnections/getAllParents';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        async: false,
        success: function(result) {
            $('#sectionInterconnectionsModule #interconnections-template-layout').html(result);
        },
        complete: function() {

        }
    });
    var id = $(this).data('id');
    var parentorg = '';
    var extclass = '';
    if (typeof id != 'undefined') {
        extclass = $('#' + id).data('orgclass');
        var value = $('#' + id).val();
        parentorg = $('#' + id).data('parentorg');

        $('#sectionInterconnectionsModule input[name=editing]').val(id);
        $('#sectionInterconnectionsModule #section_title').val($.trim(value));
        $('#sectionInterconnectionsModule #extra_class').val(extclass);
        $('#sectionInterconnectionsModule #addSection').text('Update');
        if (parentorg != '' && parentorg != null) {
            $('#sectionInterconnectionsModule select[name=parentorg] option[value=' + parentorg + ']').prop('selected', true);
        }
        $('#sectionInterconnectionsModule #exampleModalLabel b').text('Edit Interconnections');
    } else {
        var value = $(this).text();
        $('#sectionInterconnectionsModule input[name=editing]').val('');
        $('#sectionInterconnectionsModule #section_title').val($.trim(value));
        $('#sectionInterconnectionsModule #extra_class').val('');
        $('#sectionInterconnectionsModule select[name=parentorg] option:first').prop('selected', true);
        $('#sectionInterconnectionsModule #addSection').text('Add');
        $('#sectionInterconnectionsModule #exampleModalLabel b').text('Add Interconnections');
    }
    $('#sectionInterconnectionsModule input[name=template]').val($(this).data('filter'));
});

$('#sectionInterconnectionsModule').on('shown.bs.modal', function() {
    $('#sectionInterconnectionsModule select').selectpicker();
    validateInterconnectionsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionInterconnectionsModule select').selectpicker('destroy');
    validateInterconnectionsTemplate.reset();
});



$(document).on('click', '.links-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionLinksModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var ajaxUrl = site_url + '/powerpanel/links-category/getAllCategory';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        async: false,
        success: function(result) {
            $('#sectionLinksModuleTemplate #link-template-layout').html(result);
        },
        complete: function() {

        }
    });

    var id = $(this).data('id');
    var slimit = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    var linkcat = '';
    if (typeof id != 'undefined') {
        extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        slimit = $('#' + id).data('slimit');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');
        linkcat = $('#' + id).data('linkcat');
        $('#sectionLinksModuleTemplate input[name=editing]').val(id);
        $('#sectionLinksModuleTemplate #section_title').val($.trim(value));
        $('#sectionLinksModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionLinksModuleTemplate #extra_class').val(extclass);
        if (linkcat != '') {
            $('#sectionLinksModuleTemplate select[name=linkcat] option[value=' + linkcat + ']').prop('selected', true);
        }
        $('#sectionLinksModuleTemplate #link_start_date_time').val(startdate);
        $('#sectionLinksModuleTemplate #link_end_date_time').val(enddate);
        $('#sectionLinksModuleTemplate #addSection').text('Update');
        $('#sectionLinksModuleTemplate #exampleModalLabel b').text('Edit Sector Links');
    } else {
        var value = $(this).text();
        $('#sectionLinksModuleTemplate input[name=editing]').val('');
        $('#sectionLinksModuleTemplate #section_title').val($.trim(value));
        $('#sectionLinksModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionLinksModuleTemplate #extra_class').val(extclass);
        $('#sectionLinksModuleTemplate select[name=linkcat] option:first').prop('selected', true);
        $('#sectionLinksModuleTemplate #link_start_date_time').val(startdate);
        $('#sectionLinksModuleTemplate #link_end_date_time').val(enddate);
        $('#sectionLinksModuleTemplate #addSection').text('Add');
        $('#sectionLinksModuleTemplate #exampleModalLabel b').text('Add Sector Links');
    }
    $('#sectionLinksModuleTemplate #link_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionLinksModuleTemplate #link_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionLinksModuleTemplate #link_start_date_time').val() ? jQuery('#sectionLinksModuleTemplate #link_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionLinksModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionLinksModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionLinksModuleTemplate select').selectpicker();
    validateLinksTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionLinksModuleTemplate select').selectpicker('destroy');
    validateLinksTemplate.reset();
});


$(document).on('click', '.faqs-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionFaqsModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var ajaxUrl = site_url + '/powerpanel/faq-category/getAllCategory';
    jQuery.ajax({
        type: "POST",
        url: ajaxUrl,
        dataType: 'HTML',
        async: false,
        success: function(result) {
            $('#sectionFaqsModuleTemplate #faq-template-layout').html(result);
        },
        complete: function() {

        }
    });

    var id = $(this).data('id');
    var slimit = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    var faqcat = '';
    if (typeof id != 'undefined') {
        extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        slimit = $('#' + id).data('slimit');
        startdate = $('#' + id).data('sdate');
        enddate = $('#' + id).data('edate');
        faqcat = $('#' + id).data('faqcat');
        $('#sectionFaqsModuleTemplate input[name=editing]').val(id);
        $('#sectionFaqsModuleTemplate #section_title').val($.trim(value));
        $('#sectionFaqsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionFaqsModuleTemplate #extra_class').val(extclass);
        if (faqcat != '') {
            $('#sectionFaqsModuleTemplate select[name=faqcat] option[value=' + faqcat + ']').prop('selected', true);
        }
        $('#sectionFaqsModuleTemplate #faq_start_date_time').val(startdate);
        $('#sectionFaqsModuleTemplate #faq_end_date_time').val(enddate);
        $('#sectionFaqsModuleTemplate #addSection').text('Update');
        $('#sectionFaqsModuleTemplate #exampleModalLabel b').text('Edit FAQS');
    } else {
        var value = $(this).text();
        $('#sectionFaqsModuleTemplate input[name=editing]').val('');
        $('#sectionFaqsModuleTemplate #section_title').val($.trim(value));
        $('#sectionFaqsModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionFaqsModuleTemplate #extra_class').val(extclass);
        $('#sectionFaqsModuleTemplate select[name=faqcat] option:first').prop('selected', true);
        $('#sectionFaqsModuleTemplate #faq_start_date_time').val(startdate);
        $('#sectionFaqsModuleTemplate #faq_end_date_time').val(enddate);
        $('#sectionFaqsModuleTemplate #addSection').text('Add');
        $('#sectionFaqsModuleTemplate #exampleModalLabel b').text('Add FAQS');
    }
    $('#sectionFaqsModuleTemplate #faq_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionFaqsModuleTemplate #faq_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionFaqsModuleTemplate #faq_start_date_time').val() ? jQuery('#sectionFaqsModuleTemplate #faq_start_date_time').val() : false
            })
        },
        timepicker: true
    });
    $('#sectionFaqsModuleTemplate input[name=template]').val($(this).data('filter'));
});

$('#sectionFaqsModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionFaqsModuleTemplate select').selectpicker();
    validateFaqsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionFaqsModuleTemplate select').selectpicker('destroy');
    validateFaqsTemplate.reset();
});


$(document).on('click', '.promotions-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionPromotionsModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var layout = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        $('#sectionPromotionsModuleTemplate input[name=editing]').val(id);
        $('#sectionPromotionsModuleTemplate #section_title').val($.trim(value));
        $('#sectionPromotionsModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionPromotionsModuleTemplate #addSection').text('Update');
        $('#sectionPromotionsModuleTemplate #exampleModalLabel b').text('Edit Promotions');
    } else {
        var value = $(this).text();
        $('#sectionPromotionsModuleTemplate input[name=editing]').val('');
        $('#sectionPromotionsModuleTemplate #section_title').val($.trim(value));
        $('#sectionPromotionsModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionPromotionsModuleTemplate #addSection').text('Add');
        $('#sectionPromotionsModuleTemplate #exampleModalLabel b').text('Add Promotions');
    }
    $('#sectionPromotionsModuleTemplate input[name=template]').val($(this).data('filter'));
});
$('#sectionPromotionsModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionPromotionsModuleTemplate select').selectpicker();
    validatePromotionsTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionPromotionsModuleTemplate select').selectpicker('destroy');
    validatePromotionsTemplate.reset();
});

$(document).on('click', '.add-row', function(event) {

    $('#pgBuiderSections').modal('hide');
    var editor = 'item-' + ($('.ui-state-default').length + 1);
    var section = '';

    section += '<div class="ui-state-default" id="' + editor + '">';
    section += '<div class="row section-item row-template">';
    section += '<div class="col-sm-12">';
    section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
    section += '<a href="javascript:;" data-id="' + editor + '" class="delete-row" title="Delete"><i class="action-icon delete fa fa-trash row-delete"></i></a>';
    section += '<a href="javascript:;" data-id="' + editor + '" class="edit-row" title="Delete"><i class="action-icon edit ri-pencil-line"></i></a>';
    section += '<div class="ui-new-section-add col-sm-12">';
    section += '<div class="column-list clearfix" data-id="' + editor + '-column-list"></div>';
    section += '<a href="javascript:;" data-id="' + editor + '"  title="Add Column(s)" class="add-icon add-columns"><i class="fa fa-columns" aria-hidden="true"></i></a>';
    section += '<input id="' + editor + '-row" data-extclass="" data-animation="" type="hidden" value="' + editor + '-row"/>';
    section += '</div>';
    section += '</div>';

    if ($('#section-container .ui-state-default').length > 0) {
        $(section).insertAfter($('#section-container .ui-state-default:last'));
    } else {
        $('#section-container').append(section);
    }

    $('#no-content').addClass('d-none');
    $('#has-content').removeClass('d-none');
});

$(document).on('click', '.edit-row', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#editRow').modal({
        backdrop: 'static',
        keyboard: false
    });
    var item_id = $(this).data('id');
    $('#frmEditRow').find('input[name="sectionid"]').val(item_id);
    $('#frmEditRow').find('input[name="editing"]').val('Y');

    var extclass = $('#' + item_id + '-row').attr('data-extclass');
    var animation = $('#' + item_id + '-row').attr('data-animation');

    $('#frmEditRow input[name="row_class"]').val(extclass);
    $('#frmEditRow select[name="animation"]').val(animation);

});

$('#editRow').on('shown.bs.modal', function() {
    $('#editRow select').selectpicker();
    validateEditRowForm.init();
}).on('hidden.bs.modal', function() {
    $('#editRow select').selectpicker('destroy');
    validateEditRowForm.reset();
});


$(document).on('click', '.delete-row', function(event) {
    if (confirm('Are you sure you want to remove this section?')) {
        var id = $(this).data('id');
        $('#' + id).remove();
    }
});

$(document).on('click', '.edit-col-row', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#editColRow').modal({
        backdrop: 'static',
        keyboard: false
    });

    var item_id = $(this).data('id');
    $('#frmEditColRow').find('input[name="sectionid"]').val(item_id);
    $('#frmEditColRow').find('input[name="editing"]').val('Y');

    var extclass = $('input[data-id="' + item_id + '"]').attr('data-extclass');
    var animation = $('input[data-id="' + item_id + '"]').attr('data-animation');

    $('#frmEditColRow input[name="col_row_class"]').val(extclass);
    $('#frmEditColRow select[name="animation"]').val(animation);

});

$('#editColRow').on('shown.bs.modal', function() {
    $('#editColRow select').selectpicker();
    validateEditColRowForm.init();
}).on('hidden.bs.modal', function() {
    $('#editColRow select').selectpicker('destroy');
    validateEditColRowForm.reset();
});


$(document).on('click', '.delete-col-row', function(event) {
    if (confirm('Are you sure you want to remove this row?')) {
        var id = $(this).data('id');
        $('#' + id).remove();
    }
});


$(document).on('click', '.add-columns', function(event) {
    $('#pgBuiderSections').modal('hide');

    var id = $(this).data('id');
    $(this).addClass('clicked');

    $('#addColumns').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('#frmAddColumns select[name=no_of_column]').removeAttr('disabled');
    $('#frmAddColumns input[name=editing]').val('N');
    $('#frmAddColumns input[name=sectionid]').val(id);
    $('#frmAddColumns select[name=no_of_column]').val(1);
    $('#frmAddColumns input[name=column_class]').val('');
    $('#frmAddColumns select[name=animation]').val('');
    $('#frmAddColumns #addSection').text('Add');
});

$(document).on('click', '.edit-column', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#addColumns').modal({
        backdrop: 'static',
        keyboard: false
    });

    var editor = $(this).attr('data-editor');
    var no_of_column = $('input[data-id="' + editor + '"]').val();
    var extclass = $('input[data-id="' + editor + '"]').attr('data-extclass');
    var animation = $('input[data-id="' + editor + '"]').attr('data-animation');


    $('#frmAddColumns input[name=editing]').val('Y');
    $('#frmAddColumns input[name=sectionid]').val(editor);
    $('#frmAddColumns select[name=no_of_column]').val(no_of_column);
    $('#frmAddColumns input[name=column_class]').val(extclass);
    $('#frmAddColumns select[name=animation]').val(animation);
    $('#frmAddColumns #addSection').text('Update');
    $('#frmAddColumns select[name=no_of_column]').attr('disabled', 'disabled');

});

$(document).on('click', '.delete-column', function(event) {
    if (confirm('Are you sure you want to remove this column?')) {
        var id = $(this).data('id');
        $('#' + id).remove();
    }
});


$(document).on('click', '.add-cms-block', function(event) {
    $('#pgBuiderSections').modal('show');

    $('.add-cms-block').removeClass('clicked');
    $(this).addClass('clicked');

    $('.nav-tabs li').each(function() {
        var id = $(this).attr('id');
        if (id == 'blocks_tab') {
            $(this).show();
            $('#' + id + ' a').trigger("click");
        } else {
            $(this).hide();
        }
    });

    $('.only-title').show();
    $('.text-block').show();
    $('.only-image').show();
    $('.only-document').show();
    $('.section-button').show();
    $('.accordian-block').show();

    $('.iframeonly').hide();
    $('.google-map').hide();
    $('.only-spacer').hide();
    $('.home-information').hide();
    $('.two-part-content').hide();
    $('.only-video').hide();
    $('.image-with-information').hide();
    $('.video-with-information').hide();
    $('.contact-info').hide();
    $('.custom-section').hide();

});

$('#addColumns').on('shown.bs.modal', function() {
    $('#addColumns select').selectpicker();
    validateAddColumnForm.init();
}).on('hidden.bs.modal', function() {
    $('#addColumns select').selectpicker('destroy');
    validateAddColumnForm.reset();
});

$(document).on('click', '.delete-element', function(event) {
    if (confirm('Are you sure you want to remove this element?')) {
        var id = $(this).data('id');
        $('div[data-id="' + id + '"]').remove();
    }
});


/*../Dialog opener code=====================================*/

//Preview opener code=====================================
$('#page-preview').click(function() {
    if ($('#' + seoFormId).validate().form()) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var ajaxUrl = site_url + '/powerpanel/' + moduleName + '/preview';
        var redirection = $('#preview-link').text() + '/preview';
        builder.fillFormObj();
        var previewForm = $('#' + seoFormId);
        $.ajax({
            type: "POST",
            url: ajaxUrl,
            dataType: 'JSON',
            data: previewForm.serialize(),
            async: false,
            success: function(data) {
                if (data.status != 'error') {
                    previewForm.find('input[name=previewId]').val(data.status);
                    previewForm.find('input[name=oldAlias]').val(data.alias);

                    if ($('select[name=category_id]').length > 0) {
                        dataCategoryAlias = $('select[name=category_id]').children('option:selected').data('categryalias');
                        redirection = redirection + '/' + dataCategoryAlias;
                    }

                    redirection = redirection + '/' + previewForm.find('input[name=previewId]').val();
                    if (isDetailPage) {
                        redirection = site_url + '/' + moduleName + '/preview/' + previewForm.find('input[name=previewId]').val() + '/detail';
                    }

                    $('#pp-body').addClass('hide');
                    $('#pp-header').addClass('hide');
                    $('#preview-panel').removeClass('hide');
                    var ifrm = document.createElement("iframe");
                    ifrm.setAttribute("id", "preview-iframe");
                    ifrm.setAttribute("src", redirection);
                    $(ifrm).css("height", '100vh');
                    $(ifrm).css("border", 'none');
                    ifrm.setAttribute("width", '100%');
                    $('#preview-panel').append(ifrm);

                }
            },
            complete: function() {}
        });
    }
});
/*../Preview opener code=====================================*/


//Validations code=====================================

var validateAddColumnForm = function() {
    var handleAddColumn = function() {
        $("#frmAddColumns").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                no_of_column: {
                    required: true
                },
                column_class: {
                    required: true
                }
            },
            messages: {
                no_of_column: {
                    required: "Please select no of column(s)"
                },
                column_class: {
                    required: "Class is required"
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmAddColumns')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmAddColumn();
                return false;
            }
        });
        $('#frmAddColumns input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmAddColumn();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleAddColumn();
        },
        reset: function() {
            var validator = $("#frmAddColumns").validate();
            validator.resetForm();
        }
    };
}();

var validateEditRowForm = function() {
    var handleEditRow = function() {
        $("#frmEditRow").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                column_class: {
                    required: true
                }
            },
            messages: {
                column_class: {
                    required: "Class is required"
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmEditRow')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                var item_id = $(form).find('input[name="sectionid"]').val();
                builder.submitFrmEditRow(item_id);
                return false;
            }
        });
        $('#frmEditRow input').keypress(function(e) {
            if (e.which == 13) {
                //builder.submitFrmEditRow();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleEditRow();
        },
        reset: function() {
            var validator = $("#frmEditRow").validate();
            validator.resetForm();
        }
    };
}();


var validateEditColRowForm = function() {
    var handleEditColRow = function() {
        $("#frmEditColRow").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                column_class: {
                    required: true
                }
            },
            messages: {
                column_class: {
                    required: "Class is required"
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmEditColRow')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                var item_id = $(form).find('input[name="sectionid"]').val();
                builder.submitFrmEditColRow(item_id);
                return false;
            }
        });
        $('#frmEditColRow input').keypress(function(e) {
            if (e.which == 13) {
                //builder.submitFrmEditRow();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleEditColRow();
        },
        reset: function() {
            var validator = $("#frmEditColRow").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionOnlyTitle = function() {
    var handleSectionOnlyTitle = function() {
        $("#frmSectionTitle").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    required: true,
                    noSpace: true
                },

            },
            messages: {
                title: "Title is required"
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionTitle')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionTitle();
                return false;
            }
        });

        $('#frmSectionTitle input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionTitle();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionOnlyTitle();
        },
        reset: function() {
            var validator = $("#frmSectionTitle").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionOnlyIframe = function() {
    var handleSectionOnlyIframe = function() {
        $("#frmSectionIframe").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                content: {
                    required: true,
                    url: true
                },
            },
            messages: {
                content: {
                    required: "Iframe URL is required"
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionIframe')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionIframe();
                return false;
            }
        });
        $('#frmSectionIframe input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionIframe();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionOnlyIframe();
        },
        reset: function() {
            var validator = $("#frmSectionIframe").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionOnlyImage = function() {
    var handleSectionOnlyImage = function() {
        $("#frmSectionOnlyImage").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                img_title: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                selector: {
                    required: true,
                    xssProtection: true,
                    no_url: true
                },
                img1: {
                    required: true,
                    noSpace: true
                },

            },
            messages: {
                img_title: {
                    required: "Caption is required",
                },
                img1: {
                    required: "Image is required",
                },
                selector: {
                    required: "Alignment is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionOnlyImage')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionOnlyImage();
                return false;
            }
        });
        $('#frmSectionOnlyImage input').keypress(function(e) {
            if (e.which == 13) {
                $Spelling.SpellCheckAsYouType('.sectiontitlespellingcheck');
                builder.submitFrmSectionOnlyImage();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionOnlyImage();
        },
        reset: function() {
            var validator = $("#frmSectionOnlyImage").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionOnlyDocument = function() {
    var handleSectionOnlyDocument = function() {
        $("#frmSectionOnlyDocument").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                img1: {
                    required: true,
                    noSpace: true
                },
                caption: {
                    required: true,
                }
            },
            messages: {
                img1: "Document is required",
                caption: "Caption is required"
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionOnlyDocument')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                $('body').loader(loaderConfig);
                builder.submitFrmSectionOnlyDocument();
                return false;
            }
        });
        $('#frmSectionOnlyDocument input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionOnlyDocument();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionOnlyDocument();
        },
        reset: function() {
            var validator = $("#frmSectionOnlyDocument").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionGalleryImage = function() {
    var handleSectionGalleryImage = function() {
        $("#frmSectionGalleryImage").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                img_id: {
                    required: {
                        //                                            if($('#frmSectionGalleryImage input[name="img_id"]').val() != '' || $('#frmSectionGalleryImage input[name="editing"]').val().length == 0){
                        depends: function() {
                            return $('#frmSectionGalleryImage input[name="editing"]').val().length == 0;
                        }
                    },
                    max6: true
                }
            },
            messages: {
                img_id: {
                    required: "Photo is required",
                    max6: "Please select six or less photos"
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionGalleryImage')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionGalleryImage();
                return false;
            }
        });
        $('#frmSectionGalleryImage input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionGalleryImage();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {

            $.validator.addMethod("max6", function(value) {
                var imgIDArr = value.split(',');
                if (imgIDArr.length > 6) {
                    return false;
                }
                return true;
            });

            handleSectionGalleryImage();
        },
        reset: function() {
            var validator = $("#frmSectionGalleryImage").validate();
            validator.resetForm();
        }
    };
}();



var validatesectionMap = function() {
    var handlesectionMap = function() {
        $("#frmSectionMap").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                img_latitude: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                img_longitude: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },

            },
            messages: {
                img_latitude: {
                    required: "Latitude is required",
                },
                img_longitude: {
                    required: "Longitude is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionMap')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionMap();
                return false;
            }
        });
        $('#frmSectionMap input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionMap();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handlesectionMap();
        },
        reset: function() {
            var validator = $("#frmSectionMap").validate();
            validator.resetForm();
        }
    };
}();




var validateSectionContactInfo = function() {
    var handleSectionContactInfo = function() {
        $("#frmSectionContactInfo").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_address: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                },
                section_email: {
                    required: true,
                    emailFormat: true,
                    noSpace: true,
                },
                section_phone: {
                    required: true,
                    languageTest: true,
                    no_url: true,
                    phonenumber: {
                        depends: function() {
                            if (($("#section_phone").val()) != '') {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    phonenumber_mobile: {
                        depends: function() {
                            if (($("#section_phone").val()) != '') {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                }
            },
            messages: {
                section_address: {
                    required: "Address is required",
                },
                section_email: {
                    required: "Email is required",
                    email: "Enter valid Email"
                },
                section_phone: {
                    required: "Phone is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionContactInfo')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionContactInfo();
                return false;
            }
        });

        $('#frmSectionContactInfo #addSection').click(function() {
            sectionInfoCk.updateSourceElement();
        });

        $('#frmSectionContactInfo input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionContactInfo();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionContactInfo();
        },
        reset: function() {
            var validator = $("#frmSectionContactInfo").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionButton = function() {
    var handleSectionButton = function() {
        $("#frmSectionButton").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_button_target: {
                    required: true,
                    xssProtection: true,
                },
                section_link: {
                    required: true,
                    xssProtection: true,
                },
                selector: {
                    required: true,
                    xssProtection: true,
                    no_url: true
                },
            },
            messages: {
                section_title: {
                    required: "Title is required",
                },
                section_button_target: {
                    required: "Link target is required",
                },
                section_link: {
                    required: "Link is required",
                    url: "Please enter valid Link"
                },
                selector: {
                    required: "Alignment is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionButton')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionButton();
                return false;
            }
        });

        $('#frmSectionButton input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionButton();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionButton();
        },
        reset: function() {
            var validator = $("#frmSectionButton").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionOnlyVideo = function() {
    var handleSectionOnlyVideo = function() {
        $("#frmSectionVideo").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    required: true,
                    noSpace: true
                },
                video_id: {
                    required: true,
                    noSpace: true
                },

            },
            messages: {
                title: "Caption is required",
                video_id: "Video Embed URL is required"
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionVideo')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionVideo();
                return false;
            }
        });

        $('#frmSectionVideo input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionVideo();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionOnlyVideo();
        },
        reset: function() {
            var validator = $("#frmSectionVideo").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionOnlyContent = function() {
    var handleSectionOnlyContent = function() {
        $("#frmSectionContent").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                content: {
                    required: true,
                    noSpace: true
                },

            },
            messages: {
                content: "Content is required"
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionContent')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionContent();
                return false;
            }
        });
        $('#frmSectionContent #addSection').click(function() {
            sectionContentCk.updateSourceElement();
        });
        $('#frmSectionContent input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionContent();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionOnlyContent();
        },
        reset: function() {
            var validator = $("#frmSectionContent").validate();
            validator.resetForm();
        }
    };
}();
var validateAccordianblock = function() {
    var handleAccordianblock = function() {
        $("#frmSectionAccordian").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    required: true,
                    noSpace: true
                },

            },
            messages: {
                title: "Title is required."
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionAccordian')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionAccordian();
                return false;
            }
        });
        $('#frmSectionAccordian #addSection').click(function() {
            sectionContentCk.updateSourceElement();
        });
        $('#frmSectionAccordian input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionAccordian();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleAccordianblock();
        },
        reset: function() {
            var validator = $("#frmSectionAccordian").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionTwoContent = function() {
    var handleSectionTwoContent = function() {
        $("#frmSectionTwoContent").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                leftcontent: {
                    required: true,
                    noSpace: true
                },
                rightcontent: {
                    required: true,
                    noSpace: true
                },
            },
            messages: {
                leftcontent: {
                    required: "Left side content is required",
                },
                rightcontent: {
                    required: "Right side content is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.attr('id') == 'leftck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('id') == 'rightck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionTwoContent')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionTwoContent();
                return false;
            }
        });
        $('#frmSectionTwoContent #addSection').click(function() {
            sectionleftContentCk.updateSourceElement();
            sectionrightContentCk.updateSourceElement();
        });
        $('#frmSectionContent input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionTwoContent();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionTwoContent();
        },
        reset: function() {
            var validator = $("#frmSectionTwoContent").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionImage = function() {
    var handleSectionImage = function() {
        $("#frmSectionImage").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                img_title: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                content: {
                    required: true,
                    noSpace: true
                },
                selector: {
                    required: true,
                    xssProtection: true,
                    no_url: true
                },
                img1: {
                    required: true
                }
            },
            messages: {
                img_title: {
                    required: "Caption is required",
                },
                content: {
                    required: "Content is required",
                },
                selector: {
                    required: "Alignment is required",
                },
                img1: {
                    required: "Image is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionImage')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionImage();
                return false;
            }
        });

        $('#frmSectionImage #addSection').click(function() {
            sectionImageCk.updateSourceElement();
        });

        $('#frmSectionImage input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionImage();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionImage();
        },
        reset: function() {
            var validator = $("#frmSectionImage").validate();
            validator.resetForm();
        }
    };
}();


var validateSectionVideoContent = function() {
    var handleSectionVideoContent = function() {
        $("#frmsectionVideoContent").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                video_id: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                content: {
                    required: true,
                    noSpace: true
                },
                selector: {
                    required: true,
                    xssProtection: true,
                    no_url: true
                }
            },
            messages: {
                title: {
                    required: "Caption is required",
                },
                video_id: {
                    required: "Video Embed Url is required",
                },
                content: {
                    required: "Content is required",
                },
                selector: {
                    required: "Alignment is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmsectionVideoContent')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmsectionVideoContent();
                return false;
            }
        });

        $('#frmsectionVideoContent #addSection').click(function() {
            sectionImageCk.updateSourceElement();
        });

        $('#frmsectionVideoContent input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmsectionVideoContent();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionVideoContent();
        },
        reset: function() {
            var validator = $("#frmsectionVideoContent").validate();
            validator.resetForm();
        }
    };
}();



var validateSectionHomeImage = function() {
    var handleSectionHomeImage = function() {
        $("#frmSectionHomeImage").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                img_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                content: {
                    required: true,
                    noSpace: true
                },
                selector: {
                    required: true,
                    xssProtection: true,
                    no_url: true
                },
                img1: {
                    required: true,
                    noSpace: true
                }
            },
            messages: {
                img_title: {
                    required: "Caption is required",
                },
                content: {
                    required: "Content is required",
                },
                selector: {
                    required: "Alignment is required",
                },
                img1: {
                    required: "Image is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionHomeImage')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionHomeImage();
                return false;
            }
        });

        $('#frmSectionHomeImage #addSection').click(function() {
            sectionImageCk.updateSourceElement();
        });

        $('#frmSectionHomeImage input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionHomeImage();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionHomeImage();
        },
        reset: function() {
            var validator = $("#frmSectionHomeImage").validate();
            validator.resetForm();
        }
    };
}();



var validateSectionBusiness = function() {
    var handleSectionBusiness = function() {
        $("#frmSectionBusinessModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true
                },
                section_config: {
                    required: true,
                    noSpace: true
                },
                layoutType: {
                    required: true
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionBusinessModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: "Caption is required",
                section_config: "Please select configurations.",
                layoutType: "Please select layout.",
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionBusinessModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionBusinessModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionBusinessModule();
                return false;
            }
        });

        $('#frmSectionBusinessModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionBusinessModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionBusiness();
        },
        reset: function() {
            var validator = $("#frmSectionBusinessModule").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionEvents = function() {
    var handleSectionEvents = function() {
        $("#frmSectionEventsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionEventsModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionEventsModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionEventsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionEventsModule();
                return false;
            }
        });

        $('#frmSectionEventsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionEventsModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionEvents();
        },
        reset: function() {
            var validator = $("#frmSectionEventsModule").validate();
            validator.resetForm();
        }
    };
}();


var validateSectionBlogs = function() {
    var handleSectionBlogs = function() {
        $("#frmSectionBlogsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionBlogsModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionBlogsModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionBlogsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionBlogsModule();
                return false;
            }
        });

        $('#frmSectionBlogsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionBlogsModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionBlogs();
        },
        reset: function() {
            var validator = $("#frmSectionBlogsModule").validate();
            validator.resetForm();
        }
    };
}();



var validateSectionService = function() {
    var handleSectionService = function() {
        $("#frmSectionServiceModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionServiceModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionServiceModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionServiceModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionServiceModule();
                return false;
            }
        });

        $('#frmSectionServiceModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionServiceModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionService();
        },
        reset: function() {
            var validator = $("#frmSectionServiceModule").validate();
            validator.resetForm();
        }
    };
}();



var validateSectionCandWService = function() {
    var handleSectionCandWService = function() {
        $("#frmSectionCandWServiceModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionBlogsModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionCandWServiceModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionCandWServiceModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },

            submitHandler: function(form) {
                builder.submitFrmSectionCandWServiceModule();
                return false;
            }
        });

        $('#frmSectionCandWServiceModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionCandWServiceModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionCandWService();
        },
        reset: function() {
            var validator = $("#frmSectionCandWServiceModule").validate();
            validator.resetForm();
        }
    };
}();


var validateSectionConsultations = function() {
    var handleSectionConsultations = function() {
        $("#frmSectionConsultationsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionConsultationsModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionConsultationsModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionConsultationsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionConsultationsModule();
                return false;
            }
        });

        $('#frmSectionConsultationsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionConsultationsModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionConsultations();
        },
        reset: function() {
            var validator = $("#frmSectionConsultationsModule").validate();
            validator.resetForm();
        }
    };
}();
var validateSectionComplaintServices = function() {
    var handleSectionComplaintServices = function() {
        $("#frmSectionComplaintServicesModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionComplaintServicesModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionComplaintServicesModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionComplaintServicesModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionComplaintServicesModule();
                return false;
            }
        });

        $('#frmSectionComplaintServicesModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionComplaintServicesModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionComplaintServices();
        },
        reset: function() {
            var validator = $("#frmSectionComplaintServicesModule").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionFMBroadcasting = function() {
    var handleSectionFMBroadcasting = function() {
        $("#frmSectionFMBroadcastingModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionFMBroadcastingModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionFMBroadcastingModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionFMBroadcastingModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionFMBroadcastingModule();
                return false;
            }
        });

        $('#frmSectionFMBroadcastingModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionFMBroadcastingModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionFMBroadcasting();
        },
        reset: function() {
            var validator = $("#frmSectionFMBroadcastingModule").validate();
            validator.resetForm();
        }
    };
}();


var validateSectionBoardofDirectors = function() {
    var handleSectionBoardofDirectors = function() {
        $("#frmSectionBoardofDirectorsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionBoardofDirectorsModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionBoardofDirectorsModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionBoardofDirectorsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionBoardofDirectorsModule();
                return false;
            }
        });

        $('#frmSectionBoardofDirectorsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionBoardofDirectorsModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionBoardofDirectors();
        },
        reset: function() {
            var validator = $("#frmSectionBoardofDirectorsModule").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionRegisterofApplications = function() {
    var handleSectionRegisterofApplications = function() {
        $("#frmSectionRegisterofApplicationsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionRegisterofApplicationsModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionRegisterofApplicationsModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionRegisterofApplicationsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionRegisterofApplicationsModule();
                return false;
            }
        });

        $('#frmSectionRegisterofApplicationsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionRegisterofApplicationsModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionRegisterofApplications();
        },
        reset: function() {
            var validator = $("#frmSectionRegisterofApplicationsModule").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionLicenceRegister = function() {
    var handleSectionLicenceRegister = function() {
        $("#frmSectionLicenceRegisterModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionLicenceRegisterModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionLicenceRegisterModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionLicenceRegisterModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionLicenceRegisterModule();
                return false;
            }
        });

        $('#frmSectionLicenceRegisterModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionLicenceRegisterModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionLicenceRegister();
        },
        reset: function() {
            var validator = $("#frmSectionLicenceRegisterModule").validate();
            validator.resetForm();
        }
    };
}();


var validateSectionFormsandFees = function() {
    var handleSectionFormsandFees = function() {
        $("#frmSectionFormsandFeesModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionFormsandFeesModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionFormsandFeesModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionFormsandFeesModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionFormsandFeesModule();
                return false;
            }
        });

        $('#frmSectionFormsandFeesModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionFormsandFeesModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionFormsandFees();
        },
        reset: function() {
            var validator = $("#frmSectionFormsandFeesModule").validate();
            validator.resetForm();
        }
    };
}();


var validateSectionPublication = function() {
    var handleSectionPublication = function() {
        $("#frmSectionPublicationModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },

                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionPublicationModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },

                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionPublicationModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionPublicationModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionPublicationModule();
                return false;
            }
        });

        $('#frmSectionPublicationModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionPublicationModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionPublication();
        },
        reset: function() {
            var validator = $("#frmSectionPublicationModule").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionNews = function() {
    var handleSectionNews = function() {
        $("#frmSectionNewsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                layoutType: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionNewsModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_config: {
                    required: "Please select configurations.",
                },
                layoutType: {
                    required: "Please select layout.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionNewsModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionNewsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionNewsModule();
                return false;
            }
        });

        $('#frmSectionNewsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionNewsModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionNews();
        },
        reset: function() {
            var validator = $("#frmSectionNewsModule").validate();
            validator.resetForm();
        }
    };
}();


var validateSectionAlerts = function() {
    var handleSectionAlerts = function() {
        $("#frmSectionAlertsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionAlertsModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Please enter caption.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionAlertsModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionAlertsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionAlertsModule();
                return false;
            }
        });

        $('#frmSectionAlertsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionAlertsModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionAlerts();
        },
        reset: function() {
            var validator = $("#frmSectionAlertsModule").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionLinks = function() {
    var handleSectionLinks = function() {
        $("#frmSectionLinksModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionLinksModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Please enter caption.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionLinksModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionLinksModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionLinksModule();
                return false;
            }
        });

        $('#frmSectionLinksModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionLinksModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionLinks();
        },
        reset: function() {
            var validator = $("#frmSectionLinksModule").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionFaqs = function() {
    var handleSectionFaqs = function() {
        $("#frmSectionFaqsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionFaqsModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Please enter caption.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionFaqsModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionFaqsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionFaqsModule();
                return false;
            }
        });

        $('#frmSectionFaqsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionFaqsModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionFaqs();
        },
        reset: function() {
            var validator = $("#frmSectionFaqsModule").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionDepartment = function() {
    var handleSectionDepartment = function() {
        $("#frmSectionDepartmentModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionDepartmentModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: {
                    required: "Please enter caption.",
                },
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionDepartmentModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionDepartmentModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionDepartmentModule();
                return false;
            }
        });

        $('#frmSectionDepartmentModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionDepartmentModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionDepartment();
        },
        reset: function() {
            var validator = $("#frmSectionDepartmentModule").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionArticles = function() {
    var handleSectionArticles = function() {
        $("#frmSectionArticlesModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true
                },
                section_config: {
                    required: true,
                    noSpace: true
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionArticlesModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: "Caption is required",
                section_config: "Please select configurations.",
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionArticlesModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionArticlesModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionArticlesModule();
                return false;
            }
        });

        $('#frmSectionArticlesModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionArticlesModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionArticles();
        },
        reset: function() {
            var validator = $("#frmSectionArticlesModule").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionPromotions = function() {
    var handleSectionPromotions = function() {
        $("#frmSectionPromotionsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true
                },
                section_config: {
                    required: true,
                    noSpace: true
                },
                'delete[]': {
                    required: {
                        depends: function() {
                            return $('#frmSectionPromotionsModule input[name="editing"]').val().length == '';
                        }
                    }
                }
            },
            messages: {
                section_title: "Caption is required",
                section_config: "Please select configurations.",
                'delete[]': "Please select at least one record",
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('chkChoose')) {
                    error.insertBefore($('#frmSectionPromotionsModule .table-container .table:first'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else if (element.attr('name') == 'selector') {
                    error.insertAfter(element.closest('ul'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionPromotionsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').addClass('has-error'); // set error class to the control group       
                } else {
                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('chkChoose')) {
                    $(element).closest('td').removeClass('has-error');
                } else {
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                }
            },
            submitHandler: function(form) {
                builder.submitFrmSectionPromotionsModule();
                return false;
            }
        });

        $('#frmSectionPromotionsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionPromotionsModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSectionPromotions();
        },
        reset: function() {
            var validator = $("#frmSectionPromotionsModule").validate();
            validator.resetForm();
        }
    };
}();

var validateBusinessCustomize = function() {
    var handleBusinessCustomize = function() {
        $("#frmBusinessCustomize").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                // img1: {
                //  required:true
                // }
                // ,
                // website:{
                //  url:true
                // }
            },
            messages: {
                section_title: {
                    required: "Image field is required"
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmBusinessCustomize')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmBusinessCustomize();
                return false;
            }
        });
        $('#frmBusinessCustomize input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmBusinessCustomize();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleBusinessCustomize();
        },
        reset: function() {
            var validator = $("#frmBusinessCustomize").validate();
            validator.resetForm();
        }
    };
}();
var validateEventsTemplate = function() {
    var handleEventsTemplate = function() {
        $("#frmSectionEventsModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionEventsModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionEventsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionEventsModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionEventsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleEventsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionEventsModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();


var validateSpacerTemplate = function() {
    var handleSpacerTemplate = function() {
        $("#frmSectionSpacerTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_spacer: {
                    required: true,
                    noSpace: true
                }
            },
            messages: {
                section_spacer: {
                    required: "Spacer Class is required"
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionSpacerTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionSpacerTemplate();
                return false;
            }
        });
        $('#frmSectionSpacerTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionSpacerTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleSpacerTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionSpacerTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validateBlogsTemplate = function() {
    var handleBlogsTemplate = function() {
        $("#frmSectionBlogsModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionBlogsModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionBlogsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionBlogsModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionBlogsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleBlogsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionBlogsModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validateNumberAllocationsTemplate = function() {
    var handleNumberAllocationTemplate = function() {
        $("#frmSectionNumberAllocationsModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionNumberAllocationsModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionNumberAllocationsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionNumberAllocationsModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionNumberAllocationsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleNumberAllocationTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionNumberAllocationsModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();


var validateServiceTemplate = function() {
    var handleServiceTemplate = function() {
        $("#frmSectionServiceModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionServiceModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionServiceModuleTemplate();
                return false;
            }
        });
        $('#frmSectionServiceModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionServiceModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleServiceTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionServiceModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();



var validateCandWServiceTemplate = function() {
    var handleCandWServiceTemplate = function() {
        $("#frmSectioncandwserviceModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectioncandwserviceModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionCandWServiceModuleTemplate();
                return false;
            }
        });
        $('#frmSectioncandwserviceModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionCandWServiceModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleCandWServiceTemplate();
        },
        reset: function() {
            var validator = $("#frmSectioncandwserviceModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validateConsultationsTemplate = function() {
    var handleConsultationsTemplate = function() {
        $("#frmSectionConsultationsModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionConsultationsModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionConsultationsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionConsultationsModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionConsultationsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleConsultationsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionConsultationsModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();





var validateComplaintServicesTemplate = function() {
    var handleComplaintServicesTemplate = function() {
        $("#frmSectionComplaintServicesModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionComplaintServicesModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionComplaintServicesModuleTemplate();
                return false;
            }
        });
        $('#frmSectionComplaintServicesModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionComplaintServicesModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleComplaintServicesTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionComplaintServicesModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();


var validateFMBroadcastingTemplate = function() {
    var handleFMBroadcastingTemplate = function() {
        $("#frmSectionFMBroadcastingModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionFMBroadcastingModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionFMBroadcastingModuleTemplate();
                return false;
            }
        });
        $('#frmSectionFMBroadcastingModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionFMBroadcastingModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleFMBroadcastingTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionFMBroadcastingModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();


var validateBoardofDirectorsTemplate = function() {
    var handleBoardofDirectorsTemplate = function() {
        $("#frmSectionBoardofDirectorsTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionBoardofDirectorsTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionBoardofDirectorsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionBoardofDirectorsTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionBoardofDirectorsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleBoardofDirectorsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionBoardofDirectorsTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validateRegisterofApplicationsTemplate = function() {
    var handleRegisterofApplicationsTemplate = function() {
        $("#frmSectionRegisterofApplicationsModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionRegisterofApplicationsModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionRegisterofApplicationsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionRegisterofApplicationsModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionRegisterofApplicationsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleRegisterofApplicationsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionRegisterofApplicationsModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validateLicenceRegisterTemplate = function() {
    var handleLicenceRegisterTemplate = function() {
        $("#frmSectionLicenceRegisterModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                sector: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                sector: {
                    required: "Please select sector."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionLicenceRegisterModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionLicenceRegisterModuleTemplate();
                return false;
            }
        });
        $('#frmSectionLicenceRegisterModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionLicenceRegisterModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleLicenceRegisterTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionLicenceRegisterModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();



var validateFormsAndFeesTemplate = function() {
    var handleFormsAndFeesTemplate = function() {
        $("#frmSectionFormsAndFeesModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },

            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },

            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionFormsAndFeesModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionFormsandFeesModuleTemplate();
                return false;
            }
        });
        $('#frmSectionFormsAndFeesModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionFormsandFeesModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleFormsAndFeesTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionLicenceRegisterModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();




var validatePublicationTemplate = function() {
    var handlePublicationTemplate = function() {
        $("#frmSectionPublicationModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },


            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },


            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionPublicationModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionPublicationModuleTemplate();
                return false;
            }
        });
        $('#frmSectionPublicationModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionPublicationModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handlePublicationTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionPublicationModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validateSectionPhotoAlbum = function() {
    var handlePhotoAlbum = function() {
        $("#frmSectionPhotoAlbumModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionPhotoAlbumModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionPhotoAlbumModule();
                return false;
            }
        });
        $('#frmSectionPhotoAlbumModule input').keypress(function(e) {
            if (e.which == 13) {

                builder.submitFrmSectionPhotoAlbumModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handlePhotoAlbum();
        },
        reset: function() {
            var validator = $("#frmSectionPhotoAlbumModule").validate();
            validator.resetForm();
        }
    };
}();


var validateSectionVideoAlbum = function() {
    var handleVideoAlbumAlbum = function() {
        $("#frmSectionVideoAlbumModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                layoutType: {
                    required: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionVideoAlbumModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionVideoAlbumModule();
                return false;
            }
        });
        $('#frmSectionVideoAlbumModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionVideoAlbumModule();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleVideoAlbumAlbum();
        },
        reset: function() {
            var validator = $("#frmSectionVideoAlbumModule").validate();
            validator.resetForm();
        }
    };
}();


var validateNewsTemplate = function() {
    var handleNewsTemplate = function() {
        $("#frmSectionNewsModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionNewsModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionNewsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionNewsModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionNewsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleNewsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionNewsModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validatePublicRecordTemplate = function() {
    var handlePublicRecordTemplate = function() {
        $("#frmSectionPublicRecordModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionPublicRecordModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionPublicRecordModuleTemplate();
                return false;
            }
        });
        $('#frmSectionPublicRecordModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionPublicRecordModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handlePublicRecordTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionPublicRecordModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();


var validateLatestNewsTemplate = function() {
    var handleLatestNewsTemplate = function() {
        $("#frmSectionLatestNewsTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionNewsModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionLatestNewsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionNewsModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionLatestNewsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleLatestNewsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionNewsModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validateQuickLinkTemplate = function() {
    var handleQuickLinkTemplate = function() {
        $("#frmSectionQuickLinkTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionQuickLinkTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionQuickLinkModuleTemplate();
                return false;
            }
        });
        $('#frmSectionQuickLinkTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionQuickLinkModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleQuickLinkTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionQuickLinkTemplate").validate();
            validator.resetForm();
        }
    };
}();


var validatePhotoAlbumTemplate = function() {
    var handlePhotoAlbumTemplate = function() {
        $("#frmSectionPhotoAlbumModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionPhotoAlbumModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionPhotoAlbumModuleTemplate();
                return false;
            }
        });
        $('#frmSectionPhotoAlbumModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionPhotoAlbumModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handlePhotoAlbumTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionPhotoAlbumModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();


var validateVideoAlbumTemplate = function() {
    var handleVideoAlbumTemplate = function() {
        $("#frmSectionVideoAlbumModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                },
                layoutType: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_config: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                },
                section_description: {
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionVideoAlbumModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionVideoAlbumModuleTemplate();
                return false;
            }
        });
        $('#frmSectionVideoAlbumModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionVideoAlbumModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleVideoAlbumTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionVideoAlbumModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();


var validateOrganizationsTemplate = function() {
    var handleOrganizationsTemplate = function() {
        $("#frmSectionOrganizationsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionOrganizationsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionOrganizationsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionOrganizationsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionOrganizationsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleOrganizationsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionOrganizationsModule").validate();
            validator.resetForm();
        }
    };
}();

var validateInterconnectionsTemplate = function() {
    var handleInterconnectionsTemplate = function() {
        $("#frmSectionInterconnectionsModule").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionInterconnectionsModule')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionInterconnectionsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionInterconnectionsModule input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionInterconnectionsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleInterconnectionsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionInterconnectionsModule").validate();
            validator.resetForm();
        }
    };
}();


var validateAlertsTemplate = function() {
    var handleAlertsTemplate = function() {
        $("#frmSectionAlertsModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionAlertsModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionAlertsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionAlertsModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionAlertsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleAlertsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionAlertsModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validateDepartmentTemplate = function() {
    var handleDepartmentTemplate = function() {
        $("#frmSectionDepartmentModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionDepartmentModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionDepartmentModuleTemplate();
                return false;
            }
        });
        $('#frmSectionDepartmentModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionDepartmentModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleDepartmentTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionDepartmentModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();


var validateLinksTemplate = function() {
    var handleLinksTemplate = function() {
        $("#frmSectionLinksModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionLinksModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionLinksModuleTemplate();
                return false;
            }
        });
        $('#frmSectionLinksModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionLinksModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleLinksTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionLinksModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validateFaqsTemplate = function() {
    var handleFaqsTemplate = function() {
        $("#frmSectionFaqsModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true,
                    xssProtection: true,
                    no_url: true,
                    badwordcheck: true,
                    languageTest: true,
                    check_special_char: true,
                }
            },
            messages: {
                section_title: {
                    required: "Caption is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionFaqsModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionFaqsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionFaqsModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionFaqsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleFaqsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionFaqsModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validatePromotionsTemplate = function() {
    var handlePromotionsTemplate = function() {
        $("#frmSectionPromotionsModuleTemplate").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                section_title: {
                    required: true,
                    noSpace: true
                },
                layoutType: {
                    required: true
                },
                section_config: {
                    required: true,
                    noSpace: true
                }
            },
            messages: {
                section_title: {
                    required: "Title is required"
                },
                layoutType: {
                    required: "Please select layout."
                },
                section_config: {
                    required: "Please select configurations."
                }

            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else if (element.attr('id') == 'ck-area') {
                    error.insertAfter(element.next('.ck-editor'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmSectionPromotionsModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionPromotionsModuleTemplate();
                return false;
            }
        });
        $('#frmSectionPromotionsModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionPromotionsModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handlePromotionsTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionPromotionsModuleTemplate").validate();
            validator.resetForm();
        }
    };
}();

var validateCustomSectionBase = function() {
    var handleCustomSectionBase = function() {
        $("#frmCustomSectionBase").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            ignore: [],
            rules: {
                title: {
                    required: true
                },
                layoutType: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "Title is required"
                },
                layoutType: {
                    required: "Layout is required"
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $.loader.close(true);
                }
                $('.alert-danger', $('#frmCustomSectionBase')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmCustomSectionBase();
                return false;
            }
        });
        $('#frmCustomSectionBase input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmCustomSectionBase();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleCustomSectionBase();
        },
        reset: function() {
            var validator = $("#frmCustomSectionBase").validate();
            validator.resetForm();
        }
    };
}();

/*../Validations code=====================================*/