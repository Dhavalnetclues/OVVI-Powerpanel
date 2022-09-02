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
            // if($('#banner').length>0){
            //  builderObj.push({
            //    type:'banner',
            //    val: $('#banner').val()
            //  });
            // }
            var columnsclass = '';
            $('.section-item').each(function() {
                if ($(this).parents('.row').closest('.ThreeColumns').length == 1) {
                    var columnsclass = 'ThreeColumns';
                } else if ($(this).parents('.row').closest('.TwoColumns').length == 1) {
                    var columnsclass = 'TwoColumns';
                } else if ($(this).parents('.row').closest('.FourColumns').length == 1) {
                    var columnsclass = 'FourColumns';
                } else if ($(this).parents('.row').closest('.OneThreeColumns').length == 1) {
                    var columnsclass = 'OneThreeColumns';
                } else if ($(this).parents('.row').closest('.ThreeOneColumns').length == 1) {
                    var columnsclass = 'ThreeOneColumns';
                }
                var editior = $(this).data("editor");
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
                    var align = $(this).find('.imgip').data('type');
                    var obj = {
                        type: 'image',
                        val: {
                            title: caption,
                            image: img,
                            alignment: align,
                            src: source
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

                    var obj = {
                        type: 'document',
                        val: {
                            title: caption,
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
                } else if ($(this).hasClass('two_col_1')) {
                    //TwoColumns

                    if ($(this).closest('.two_col_1').find('.onlytitleclass').length == 1) {
                        var txt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.onlytitleclass').val();
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'onlytitle',
                            partitionclass: columnsclass,
                            val: {
                                content: txt
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.textareaclass').length == 1) {
                        var txt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.textareaclass').val();
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'textarea',
                            partitionclass: columnsclass,
                            val: {
                                content: txt
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.imageclass').length == 1) {
                        var img = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.imageclass').val();
                        var source = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var caption = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.imageclass').data('caption');
                        var align = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.imageclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'onlyimage',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                alignment: align,
                                src: source
                            }
                        };

                    } else if ($(this).closest('.two_col_1').find('.imgcontentclass').length == 1) {
                        var img = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('id');
                        var caption = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('caption');
                        var txt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').val();
                        var align = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('type');
                        var source = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'imgcontent',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                content: txt,
                                alignment: align,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.videocontentclass').length == 1) {
                        var txt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('caption');
                        var content = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.videocontentclass').val();
                        var videoIdSec = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('id');
                        var vidType = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('type');
                        var align = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('aligntype');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'videocontent',
                            partitionclass: columnsclass,
                            val: {
                                title: txt,
                                videoType: vidType,
                                vidId: videoIdSec,
                                content: content,
                                alignment: align
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.homeimagecontclass').length == 1) {
                        var img = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('id');
                        var caption = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('caption');
                        var txt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').val();
                        var align = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('type');
                        var source = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'homeimgcontent',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                content: txt,
                                alignment: align,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.formclass').length == 1) {
                        //Only Content              
                        var txt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.formclass').val();
                        var eclass = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.formclass').data('class');
                        var eid = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.formclass').data('id');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'formdata',
                            partitionclass: columnsclass,
                            val: {
                                id: eid,
                                content: txt,
                                extclass: eclass
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.twotextareaclass').length == 1) {
                        //Only Content              
                        var lefttxt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.twotextareaclass').val();
                        var righttxt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.twotextareaclass').data('content');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'twotextarea',
                            partitionclass: columnsclass,
                            val: {
                                leftcontent: lefttxt,
                                rightcontent: righttxt
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.mapclass').length == 1) {
                        //Only Image      
                        var latitude = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.mapclass').data('latitude');
                        var longitude = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.mapclass').data('longitude');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'mapdata',
                            partitionclass: columnsclass,
                            val: {
                                latitude: latitude,
                                longitude: longitude
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.documentclass').length == 1) {
                        //Only Image    
                        var img = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.documentclass').val();
                        var source = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var caption = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.documentclass').data('caption');

                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'onlydocument',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                document: img,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.videoclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.videoclass').data('caption');
                        var videoIdSec = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.videoclass').val();
                        var vidType = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.videoclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'onlyvideo',
                            partitionclass: columnsclass,
                            val: {
                                title: txt,
                                videoType: vidType,
                                vidId: videoIdSec
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.contactinfoclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').val();
                        var section_address = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('address');
                        var section_email = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('email');
                        var section_phone = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('phone');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'contactinfodata',
                            partitionclass: columnsclass,
                            val: {
                                content: txt,
                                section_address: section_address,
                                section_email: section_email,
                                section_phone: section_phone
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.buttonclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.buttonclass').val();
                        var caption = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('caption');
                        var target = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('linktarget');
                        var align = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'buttondata',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                content: txt,
                                alignment: align,
                                target: target,
                            }
                        };
                    } else if ($(this).closest('.two_col_1').find('.spacerclass').length == 1) {
                        //Only Title
                        var conf = $('.two_col_1').closest('div[data-editor=' + editior + ']').find('.spacerclass').data('config');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_1',
                            gentype: 'spacerdata',
                            partitionclass: columnsclass,
                            val: {
                                config: conf
                            }
                        };
                    }
                } else if ($(this).hasClass('two_col_2')) {
                    //TwoColumns

                    if ($(this).closest('.two_col_2').find('.onlytitleclass').length == 1) {
                        //                        var txt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.onlytitleclass').val();
                        var txt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.onlytitleclass').val();
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'onlytitle',
                            partitionclass: columnsclass,
                            val: {
                                content: txt
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.textareaclass').length == 1) {
                        var txt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.textareaclass').val();
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'textarea',
                            partitionclass: columnsclass,
                            val: {
                                content: txt
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.imageclass').length == 1) {
                        var img = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.imageclass').val();
                        var source = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var caption = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.imageclass').data('caption');
                        var align = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.imageclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'onlyimage',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                alignment: align,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.imgcontentclass').length == 1) {
                        var img = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('id');
                        var caption = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('caption');
                        var txt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').val();
                        var align = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('type');
                        var source = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'imgcontent',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                content: txt,
                                alignment: align,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.videocontentclass').length == 1) {
                        var txt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('caption');
                        var content = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.videocontentclass').val();
                        var videoIdSec = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('id');
                        var vidType = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('type');
                        var align = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('aligntype');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'videocontent',
                            partitionclass: columnsclass,
                            val: {
                                title: txt,
                                videoType: vidType,
                                vidId: videoIdSec,
                                content: content,
                                alignment: align
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.homeimagecontclass').length == 1) {
                        var img = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('id');
                        var caption = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('caption');
                        var txt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').val();
                        var align = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('type');
                        var source = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'homeimgcontent',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                content: txt,
                                alignment: align,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.formclass').length == 1) {
                        //Only Content              
                        var txt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.formclass').val();
                        var eclass = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.formclass').data('class');
                        var eid = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.formclass').data('id');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'formdata',
                            partitionclass: columnsclass,
                            val: {
                                id: eid,
                                content: txt,
                                extclass: eclass
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.twotextareaclass').length == 1) {
                        //Only Content              
                        var lefttxt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.twotextareaclass').val();
                        var righttxt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.twotextareaclass').data('content');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'twotextarea',
                            partitionclass: columnsclass,
                            val: {
                                leftcontent: lefttxt,
                                rightcontent: righttxt
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.mapclass').length == 1) {
                        //Only Image      
                        var latitude = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.mapclass').data('latitude');
                        var longitude = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.mapclass').data('longitude');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'mapdata',
                            partitionclass: columnsclass,
                            val: {
                                latitude: latitude,
                                longitude: longitude
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.documentclass').length == 1) {
                        //Only Image    
                        var img = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.documentclass').val();
                        var source = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var caption = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.documentclass').data('caption');

                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'onlydocument',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                document: img,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.videoclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.videoclass').data('caption');
                        var videoIdSec = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.videoclass').val();
                        var vidType = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.videoclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'onlyvideo',
                            partitionclass: columnsclass,
                            val: {
                                title: txt,
                                videoType: vidType,
                                vidId: videoIdSec
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.contactinfoclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').val();
                        var section_address = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('address');
                        var section_email = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('email');
                        var section_phone = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('phone');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'contactinfodata',
                            partitionclass: columnsclass,
                            val: {
                                content: txt,
                                section_address: section_address,
                                section_email: section_email,
                                section_phone: section_phone
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.buttonclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.buttonclass').val();
                        var caption = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('caption');
                        var target = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('linktarget');
                        var align = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'buttondata',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                content: txt,
                                alignment: align,
                                target: target,
                            }
                        };
                    } else if ($(this).closest('.two_col_2').find('.spacerclass').length == 1) {
                        //Only Title
                        var conf = $('.two_col_2').closest('div[data-editor=' + editior + ']').find('.spacerclass').data('config');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_2',
                            gentype: 'spacerdata',
                            partitionclass: columnsclass,
                            val: {
                                config: conf
                            }
                        };
                    }
                } else if ($(this).hasClass('two_col_3')) {
                    //TwoColumns
                    if ($(this).closest('.two_col_3').find('.onlytitleclass').length == 1) {
                        var txt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.onlytitleclass').val();
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'onlytitle',
                            partitionclass: columnsclass,
                            val: {
                                content: txt
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.textareaclass').length == 1) {
                        var txt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.textareaclass').val();
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'textarea',
                            partitionclass: columnsclass,
                            val: {
                                content: txt
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.imageclass').length == 1) {
                        var img = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.imageclass').val();
                        var source = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var caption = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.imageclass').data('caption');
                        var align = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.imageclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'onlyimage',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                alignment: align,
                                src: source
                            }
                        };

                    } else if ($(this).closest('.two_col_3').find('.imgcontentclass').length == 1) {
                        var img = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('id');
                        var caption = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('caption');
                        var txt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').val();
                        var align = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('type');
                        var source = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'imgcontent',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                content: txt,
                                alignment: align,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.videocontentclass').length == 1) {
                        var txt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('caption');
                        var content = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.videocontentclass').val();
                        var videoIdSec = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('id');
                        var vidType = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('type');
                        var align = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('aligntype');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'videocontent',
                            partitionclass: columnsclass,
                            val: {
                                title: txt,
                                videoType: vidType,
                                vidId: videoIdSec,
                                content: content,
                                alignment: align
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.homeimagecontclass').length == 1) {
                        var img = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('id');
                        var caption = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('caption');
                        var txt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').val();
                        var align = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('type');
                        var source = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'homeimgcontent',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                content: txt,
                                alignment: align,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.formclass').length == 1) {
                        //Only Content              
                        var txt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.formclass').val();
                        var eclass = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.formclass').data('class');
                        var eid = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.formclass').data('id');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'formdata',
                            partitionclass: columnsclass,
                            val: {
                                id: eid,
                                content: txt,
                                extclass: eclass
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.twotextareaclass').length == 1) {
                        //Only Content              
                        var lefttxt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.twotextareaclass').val();
                        var righttxt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.twotextareaclass').data('content');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'twotextarea',
                            partitionclass: columnsclass,
                            val: {
                                leftcontent: lefttxt,
                                rightcontent: righttxt
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.mapclass').length == 1) {
                        //Only Image      
                        var latitude = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.mapclass').data('latitude');
                        var longitude = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.mapclass').data('longitude');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'mapdata',
                            partitionclass: columnsclass,
                            val: {
                                latitude: latitude,
                                longitude: longitude
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.documentclass').length == 1) {
                        //Only Image    
                        var img = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.documentclass').val();
                        var source = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var caption = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.documentclass').data('caption');

                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'onlydocument',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                document: img,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.videoclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.videoclass').data('caption');
                        var videoIdSec = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.videoclass').val();
                        var vidType = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.videoclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'onlyvideo',
                            partitionclass: columnsclass,
                            val: {
                                title: txt,
                                videoType: vidType,
                                vidId: videoIdSec
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.contactinfoclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').val();
                        var section_address = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('address');
                        var section_email = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('email');
                        var section_phone = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('phone');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'contactinfodata',
                            partitionclass: columnsclass,
                            val: {
                                content: txt,
                                section_address: section_address,
                                section_email: section_email,
                                section_phone: section_phone
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.buttonclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.buttonclass').val();
                        var caption = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('caption');
                        var target = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('linktarget');
                        var align = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'buttondata',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                content: txt,
                                alignment: align,
                                target: target,
                            }
                        };
                    } else if ($(this).closest('.two_col_3').find('.spacerclass').length == 1) {
                        //Only Title
                        var conf = $('.two_col_3').closest('div[data-editor=' + editior + ']').find('.spacerclass').data('config');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_3',
                            gentype: 'spacerdata',
                            partitionclass: columnsclass,
                            val: {
                                config: conf
                            }
                        };
                    }
                } else if ($(this).hasClass('two_col_4')) {
                    //TwoColumns
                    if ($(this).closest('.two_col_4').find('.onlytitleclass').length == 1) {
                        var txt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.onlytitleclass').val();
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'onlytitle',
                            partitionclass: columnsclass,
                            val: {
                                content: txt
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.textareaclass').length == 1) {
                        var txt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.textareaclass').val();
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'textarea',
                            partitionclass: columnsclass,
                            val: {
                                content: txt
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.imageclass').length == 1) {
                        var img = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.imageclass').val();
                        var source = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var caption = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.imageclass').data('caption');
                        var align = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.imageclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'onlyimage',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                alignment: align,
                                src: source
                            }
                        };

                    } else if ($(this).closest('.two_col_4').find('.imgcontentclass').length == 1) {
                        var img = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('id');
                        var caption = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('caption');
                        var txt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').val();
                        var align = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.imgcontentclass').data('type');
                        var source = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'imgcontent',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                content: txt,
                                alignment: align,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.videocontentclass').length == 1) {
                        var txt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('caption');
                        var content = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.videocontentclass').val();
                        var videoIdSec = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('id');
                        var vidType = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('type');
                        var align = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.videocontentclass').data('aligntype');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'videocontent',
                            partitionclass: columnsclass,
                            val: {
                                title: txt,
                                videoType: vidType,
                                vidId: videoIdSec,
                                content: content,
                                alignment: align
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.homeimagecontclass').length == 1) {
                        var img = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('id');
                        var caption = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('caption');
                        var txt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').val();
                        var align = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.homeimagecontclass').data('type');
                        var source = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'homeimgcontent',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                image: img,
                                content: txt,
                                alignment: align,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.formclass').length == 1) {
                        //Only Content              
                        var txt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.formclass').val();
                        var eclass = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.formclass').data('class');
                        var eid = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.formclass').data('id');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'formdata',
                            partitionclass: columnsclass,
                            val: {
                                id: eid,
                                content: txt,
                                extclass: eclass
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.twotextareaclass').length == 1) {
                        //Only Content              
                        var lefttxt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.twotextareaclass').val();
                        var righttxt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.twotextareaclass').data('content');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'twotextarea',
                            partitionclass: columnsclass,
                            val: {
                                leftcontent: lefttxt,
                                rightcontent: righttxt
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.mapclass').length == 1) {
                        //Only Image      
                        var latitude = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.mapclass').data('latitude');
                        var longitude = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.mapclass').data('longitude');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'mapdata',
                            partitionclass: columnsclass,
                            val: {
                                latitude: latitude,
                                longitude: longitude
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.documentclass').length == 1) {
                        //Only Image    
                        var img = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.documentclass').val();
                        var source = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('img').attr('src');
                        var caption = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.documentclass').data('caption');

                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'onlydocument',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                document: img,
                                src: source
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.videoclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.videoclass').data('caption');
                        var videoIdSec = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.videoclass').val();
                        var vidType = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.videoclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'onlyvideo',
                            partitionclass: columnsclass,
                            val: {
                                title: txt,
                                videoType: vidType,
                                vidId: videoIdSec
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.contactinfoclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').val();
                        var section_address = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('address');
                        var section_email = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('email');
                        var section_phone = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.contactinfoclass').data('phone');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'contactinfodata',
                            partitionclass: columnsclass,
                            val: {
                                content: txt,
                                section_address: section_address,
                                section_email: section_email,
                                section_phone: section_phone
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.buttonclass').length == 1) {
                        //Only Title
                        var txt = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.buttonclass').val();
                        var caption = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('caption');
                        var target = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('linktarget');
                        var align = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.buttonclass').data('type');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'buttondata',
                            partitionclass: columnsclass,
                            val: {
                                title: caption,
                                content: txt,
                                alignment: align,
                                target: target,
                            }
                        };
                    } else if ($(this).closest('.two_col_4').find('.spacerclass').length == 1) {
                        //Only Title
                        var conf = $('.two_col_4').closest('div[data-editor=' + editior + ']').find('.spacerclass').data('config');
                        var obj = {
                            type: 'partitondata',
                            subtype: 'TwoColumns_4',
                            gentype: 'spacerdata',
                            partitionclass: columnsclass,
                            val: {
                                config: conf
                            }
                        };
                    }
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
                } else if ($(this).hasClass('publicationTemplate')) {
                    //Only Title
                    var caption = $(this).find('.txtip').val();
                    var slimit = $(this).find('.txtip').data('slimit');
                    var sdesc = $(this).find('.txtip').data('sdesc');
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var extra_class = $(this).find('.txtip').data('class');
                    var sdate = $(this).find('.txtip').data('sdate');
                    var edate = $(this).find('.txtip').data('edate');
                    var publicationscat = $(this).find('.txtip').data('publicationscat');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var obj = {
                        type: 'publication_template',
                        val: {
                            title: caption,
                            limit: slimit,
                            desc: sdesc,
                            class: extra_class,
                            sdate: sdate,
                            edate: edate,
                            publicationscat: publicationscat,
                            config: conf,
                            layout: layoutType,
                            template: type
                        }
                    };
                } else if ($(this).hasClass('serviceTemplate')) {
                    var caption = $(this).find('.txtip').val();
                    var conf = $(this).find('.txtip').data('config');
                    var type = $(this).find('.txtip').data('type');
                    var layoutType = $(this).find('input[type=hidden]').data('layout');
                    var eclass = $(this).find('input[type=hidden]').data('extclass');
                    var obj = {
                        type: 'service_template',
                        val: {
                            title: caption,
                            config: conf,
                            layout: layoutType,
                            extclass: eclass,
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
                }
                builderObj.push(obj);
            });
            var json = JSON.stringify(builderObj);
            $('#builderObj').val(json);
        },
        businessTemplate: function(val, config, template, edit, configTxt, layout) {
            var section = '';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);
                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="business-template">';
                section += '<i class="action-icon edit ri-pencil-line"></i>';
                section += '</a>';
                section += '<div class="clearfix"></div>';
                section += '<div class="defoult-module section-item businessTemplate" data-editor="' + iCount + '">';
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
        spacerTemplate: function(config, edit, configTxt) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                section += '<div class="col-md-12"><b>Spacer Class</b><label></label></div>';
                section += '<input id="' + iCount + '" class="txtip colvalue spacerclass" data-config="' + config + '"  type="hidden"/>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('only-spacer');
            } else {
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
        publicationTemplate: function(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, publicationscat) {
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
                section += '<input id="' + iCount + '" data-layout="' + layout + '" data-class="' + extra_class + '" data-type="' + template + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" data-publicationscat="' + publicationscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
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
                section += '<input id="' + edit + '" data-layout="' + layout + '" data-class="' + extra_class + '" data-type="' + template + '" data-config="' + config + '" data-slimit="' + sectionlimit + '" data-sdesc="' + sectiondesc + '" data-publicationscat="' + publicationscat + '" data-sdate="' + startdate + '" data-edate="' + enddate + '" type="hidden" class="txtip" value="' + val + '"/>';
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
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                var val1 = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                section += '<div class="col-md-12">' + val + '</div>';
                section += '<input id="' + iCount + '" data-class="' + extClass + '" type="hidden" class="txtip colvalue onlytitleclass" value="' + val1 + '"/>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('only-title');
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
        onlySectionContactInfo: function(section_address, section_email, section_phone, section_otherinfo, edit) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                var val1 = section_otherinfo.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                section += '<div class="col-md-12"><b>Contact Information</b></div><br/><br/>';
                section += '<div class="col-md-12"><b>Address:</b>' + section_address + '</div>';
                section += '<div class="col-md-12"><b>Email:</b>' + section_email + '</div>';
                section += '<div class="col-md-12"><b>Phone #:</b>' + section_phone + '</div>';
                section += '<div class="col-md-12"><b>Other Info:</b>' + section_otherinfo + '</div>';
                section += '<input id="' + iCount + '" data-address="' + section_address + '" data-email="' + section_email + '" data-phone="' + section_phone + '" type="hidden" class="txtip colvalue contactinfoclass" value="' + val1 + '"/>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('contact-info');
            } else {
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
            }
        },
        onlySectionButton: function(val, linktarget, buttonlink, type, edit) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
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
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/left-button.png" alt=""></i>';
                } else if (type == 'button-rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/right-button.png" alt=""></i>';
                } else if (type == 'button-center-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/center-button.png" alt=""></i>';
                }
                section += '</div>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-caption="' + val + '" data-linktarget="' + linktarget + '" data-type="' + type + '" type="hidden" class="txtip buttonclass" value="' + buttonlink + '"/>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('section-button');
            } else {
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
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/left-button.png" alt=""></i>';
                    } else if (type == 'button-rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/right-button.png" alt=""></i>';
                    } else if (type == 'button-center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/center-button.png" alt=""></i>';
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
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/left-button.png" alt=""></i>';
                    } else if (type == 'button-rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/right-button.png" alt=""></i>';
                    } else if (type == 'button-center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/center-button.png" alt=""></i>';
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
        onlyVideo: function(val, title, videoType, edit) {
            var section = '';

            var vidIco = videoType == 'Vimeo' ?
                '<a title="' + val + '" target="_blank" href="' + val + '"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' :
                '<a title="' + val + '" target="_blank" href="' + val + '"><i class="fa fa-youtube" aria-hidden="true"></i></a>';

            val = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                section += '<div class="col-md-12"><strong>' + title + '</strong> - ' + vidIco + '</div>';
                section += '<input id="' + iCount + '" data-caption="' + title + '" data-type="' + videoType + '" type="hidden" class="vidip videoclass" value="' + val + '"/>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('only-video');
            } else {
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
            }
        },
        addTextArea: function(val, extClass, edit) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                var val1 = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                section += '<div class="col-md-12">' + val + '</div>';
                section += '<input id="' + iCount + '" data-class="' + extClass + '" type="hidden" class="txtip colvalue textareaclass" value="' + val1 + '"/>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('text-block');
            } else {
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
        addTwoTextArea: function(leftval, rightval, edit) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                var leftval1 = leftval.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                var rightval1 = rightval.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                section += '<div class="col-md-12"><div class="col-md-6"><strong> Left Side Content </strong>' + leftval + '</div>';
                section += '<div class="col-md-6"><strong> Right Side Content </strong>' + rightval + '</div></div>';
                section += '<input id="' + iCount + '" data-content="' + rightval1 + '" type="hidden" class="txtip colvalue twotextareaclass" value="' + leftval1 + '"/>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('two-part-content');
            } else {
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
            }
        },
        addImage: function(imgsrc, val, imgCaption, type, edit, folderid) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                val = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');

                section += '<div class="col-md-9 col-xs-small">';
                section += '<div class="team_box">';
                section += '<div class="thumbnail_container">';
                section += '<div class="thumbnail">';
                section += '<img src="' + imgsrc + '">';
                section += '<input id="' + iCount + '" data-caption="' + imgCaption + '" data-type="' + type + '" data-folderid="' + folderid + '" type="hidden" class="imgip imageclass" value="' + val + '"/>';
                section += '</div>';
                section += '</div>';
                section += '</div>';
                section += '<div class="title-img">';
                section += '<h3>' + imgCaption + '</h3>';
                section += '</div>';
                section += '</div>';
                section += '<div class="col-md-3 text-right">';
                section += '<div class="image-align-box">';
                section += '<h5 class="title">Preview</h5>';
                if (type == 'image-lft-txt') {
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/image-left.png" alt=""></i>';
                } else if (type == 'image-rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/image-right.png" alt=""></i>';
                } else if (type == 'image-center-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/image-center.png" alt=""></i>';
                }

                section += '</div>';
                section += '</div>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('only-image');
            } else {
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
                    section += '<input id="' + iCount + '" data-caption="' + imgCaption + '" data-type="' + type + '" data-folderid="' + folderid + '" type="hidden" class="imgip" value="' + val + '"/>';
                    section += '</div>';
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="title-img">';
                    section += '<h3>' + imgCaption + '</h3>';
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="col-md-2 text-right">';
                    section += '<div class="image-align-box">';
                    section += '<h5 class="title">Preview</h5>';
                    if (type == 'image-lft-txt') {
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/image-left.png" alt=""></i>';
                    } else if (type == 'image-rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/image-right.png" alt=""></i>';
                    } else if (type == 'image-center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/image-center.png" alt=""></i>';
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
                    section += '<div class="col-md-12 col-xs-small">';
                    section += '<div class="team_box">';
                    section += '<div class="thumbnail_container">';
                    section += '<div class="thumbnail">';
                    section += '<img src="' + imgsrc + '">';
                    section += '<input id="' + edit + '" data-caption="' + imgCaption + '" data-type="' + type + '"  data-folderid="' + folderid + '" type="hidden" class="imgip" value="' + val + '"/>';
                    section += '</div>';
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="title-img">';
                    section += '<h3>' + imgCaption + '</h3>';
                    section += '</div>';
                    section += '</div>';
                    section += '<div class="col-md-2 text-right">';
                    section += '<div class="image-align-box">';
                    section += '<h5 class="title">Preview</h5>';
                    if (type == 'image-lft-txt') {
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/image-left.png" alt=""></i>';
                    } else if (type == 'image-rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/image-right.png" alt=""></i>';
                    } else if (type == 'image-center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/image-center.png" alt=""></i>';
                    }

                    section += '</div>';
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    $('#section-container').find('div[data-editor=' + edit + ']').html(section);
                }
            }
        },

        addMap: function(latitude, longitude, edit) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                section += '<div class="col-md-12 col-xs-small">';
                section += '<div class="team_box">';
                section += '<input id="' + iCount + '" data-latitude="' + latitude + '" data-longitude="' + longitude + '" type="hidden" class="imgip mapclass"/>';
                section += '</div>';
                section += '<div class="title-img">';
                section += '<h3>Latitude: ' + latitude + '</h3><br/>';
                section += '<h3>Longitude: ' + longitude + '</h3>';
                section += '</div>';
                section += '</div>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('google-map');
            } else {
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
            }
        },
        addDocument: function(imgsrc, val, edit, folderid) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                val = val.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                section += '<input id="' + edit + '"  type="hidden" class="imgip documentclass" data-folderid="' + folderid + '" value="' + val + '"/>';
                section += '<div class="docdatahtml"></div>';
                section += '<div class="clearfix"></div>';
                //                $('#section-container').find('div[data-editor=' + edit + ']').html(section);

                var doccopid = val;
                var DOC_URL = site_url + "/powerpanel/media/ComposerDocDatajs";
                $.ajax({
                    type: 'POST',
                    url: DOC_URL,
                    data: 'id=' + doccopid + '',
                    success: function(html) {
                        $('.add-element.clicked').parents('.section-item').find(".docdatahtml").html(html);
                    }
                });
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('only-document');
            } else {
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
                    section += '<input id="' + iCount + '"  type="hidden" class="imgip" data-folderid="' + folderid + '" value="' + val + '"/>';
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
                    section += '<input id="' + edit + '"  type="hidden" class="imgip" value="' + val + '"/>';
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

        addImageWithContent: function(imgsrc, val, imgCaption, content, type, edit, folderid) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                var content1 = content.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                section += '<div class="col-md-9 col-xs-small">';
                section += '<div class="team_box">';
                section += '<div class="thumbnail_container">';
                section += '<div class="thumbnail">';
                section += '<img src="' + imgsrc + '">';
                section += '<input id="' + edit + '" data-id="' + val + '" data-type="' + type + '" data-folderid="' + folderid + '" data-caption="' + imgCaption + '" type="hidden" class="imgip imgcontentclass" value="' + content1 + '"/>';
                section += '</div>';
                section += '</div>';
                section += '</div>';
                section += '<div class="title-img">';
                section += '<h3>' + imgCaption + '</h3>';
                section += content;
                section += '</div>';
                section += '</div>';
                section += '<div class="col-md-3 text-right">';
                section += '<div class="image-align-box">';
                section += '<h5 class="title">Preview</h5>';
                if (type == 'lft-txt') {
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/left-image.png" alt=""></i>';
                } else if (type == 'rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/right-image.png" alt=""></i>';
                } else if (type == 'top-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/top-image.png" alt=""></i>';
                } else if (type == 'bot-txt') {
                    section += '<i class="icon"><img height="45" title="Align Bottom" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/bottom-image.png" alt=""></i>';
                } else if (type == 'center-txt') {
                    section += '<i class="icon"><img height="45" title="Align Center" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/center-image.png" alt=""></i>';
                }

                section += '</div>';
                section += '</div>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('image-with-information');
            } else {
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
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/left-image.png" alt=""></i>';
                    } else if (type == 'rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/right-image.png" alt=""></i>';
                    } else if (type == 'top-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/top-image.png" alt=""></i>';
                    } else if (type == 'bot-txt') {
                        section += '<i class="icon"><img height="45" title="Align Bottom" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/bottom-image.png" alt=""></i>';
                    } else if (type == 'center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Center" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/center-image.png" alt=""></i>';
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
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/left-image.png" alt=""></i>';
                    } else if (type == 'rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/right-image.png" alt=""></i>';
                    } else if (type == 'top-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/top-image.png" alt=""></i>';
                    } else if (type == 'bot-txt') {
                        section += '<i class="icon"><img height="45" title="Align Bottom" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/bottom-image.png" alt=""></i>';
                    } else if (type == 'center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Center" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/center-image.png" alt=""></i>';
                    }

                    section += '</div>';
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    $('#section-container').find('div[data-editor=' + edit + ']').html(section);
                }
            }
        },

        addVideoWithContent: function(title, val, videoType, content, type, edit) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                var vidIco = videoType == 'Vimeo' ?
                    '<a title="' + val + '" target="_blank" href="' + val + '"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>' :
                    '<a title="' + val + '" target="_blank" href="' + val + '"><i class="fa fa-youtube" aria-hidden="true"></i></a>';
                var content1 = content.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                section += '<div class="col-md-9 col-xs-small">';
                section += '' + vidIco + ' <strong>' + title + '</strong>';
                section += '<input id="' + iCount + '" data-caption="' + title + '" data-id="' + val + '" data-type="' + videoType + '" data-aligntype="' + type + '" type="hidden" class="vidip videocontentclass" value="' + content1 + '"/>';
                section += '<div class="title-img">';
                section += content;
                section += '</div></div>';
                section += '<div class="col-md-3 text-right">';
                section += '<div class="image-align-box">';
                section += '<h5 class="title">Preview</h5>';
                if (type == 'lft-txt') {
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/left-video.png" alt=""></i>';
                } else if (type == 'rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/right-video.png" alt=""></i>';
                } else if (type == 'top-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/top-video.png" alt=""></i>';
                } else if (type == 'bot-txt') {
                    section += '<i class="icon"><img height="45" title="Align Bottom" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/bottom-video.png" alt=""></i>';
                } else if (type == 'center-txt') {
                    section += '<i class="icon"><img height="45" title="Align Center" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/center-video.png" alt=""></i>';
                }

                section += '</div>';
                section += '</div>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('video-with-information');
            } else {
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
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/left-video.png" alt=""></i>';
                    } else if (type == 'rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/right-video.png" alt=""></i>';
                    } else if (type == 'top-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/top-video.png" alt=""></i>';
                    } else if (type == 'bot-txt') {
                        section += '<i class="icon"><img height="45" title="Align Bottom" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/bottom-video.png" alt=""></i>';
                    } else if (type == 'center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Center" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/center-video.png" alt=""></i>';
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
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/left-video.png" alt=""></i>';
                    } else if (type == 'rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/right-video.png" alt=""></i>';
                    } else if (type == 'top-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/top-video.png" alt=""></i>';
                    } else if (type == 'bot-txt') {
                        section += '<i class="icon"><img height="45" title="Align Bottom" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/bottom-video.png" alt=""></i>';
                    } else if (type == 'center-txt') {
                        section += '<i class="icon"><img height="45" title="Align Center" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/center-video.png" alt=""></i>';
                    }

                    section += '</div>';
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    $('#section-container').find('div[data-editor=' + edit + ']').html(section);
                }
            }
        },

        addGalleryImage: function(images, imgPosition, edit) {
            if ($('.add-element').hasClass('clicked')) {
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(val);
                $('.add-element.clicked').parents('.maintwocol').find('.colvalue').val(val);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
            } else {
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
            }
        },

        addHomeImageWithContent: function(imgsrc, val, imgCaption, content, type, edit, folderid) {
            var iCount = 'item-' + ($('.ui-state-default').length + 1);
            if ($('.add-element').hasClass('clicked')) {
                var section = '';
                var content1 = content.replace(new RegExp(String.fromCharCode(34), 'g'), '&#34;');
                section += '<div class="col-md-9 col-xs-small">';
                section += '<div class="team_box">';
                section += '<div class="thumbnail_container">';
                section += '<div class="thumbnail">';
                section += '<img src="' + imgsrc + '">';
                section += '<input id="' + edit + '" data-id="' + val + '" data-type="' + type + '" data-caption="' + imgCaption + '" data-folderid="' + folderid + '" type="hidden" class="imgip homeimagecontclass" value="' + content1 + '"/>';
                section += '</div>';
                section += '</div>';
                section += '</div>';
                section += '<div class="title-img">';
                section += '<h3>' + imgCaption + '</h3>';
                section += content;
                section += '</div>';
                section += '</div>';
                section += '<div class="col-md-3 text-right">';
                section += '<div class="image-align-box">';
                section += '<h5 class="title">Preview</h5>';
                if (type == 'home-lft-txt') {
                    section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/home-left-image.png" alt=""></i>';
                } else if (type == 'home-rt-txt') {
                    section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/home-right-image.png" alt=""></i>';
                } else if (type == 'home-top-txt') {
                    section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/home-top-image.png" alt=""></i>';
                }

                section += '</div>';
                section += '</div>';
                $('.add-element.clicked').parents('.maintwocol').find('.twocol1').html(section);
                $('.add-element.clicked').parents('.maintwocol').find('.hidecol').hide();
                $('.add-element.clicked').parents('.col_1').find('.columnstwo').addClass('home-information');
            } else {
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
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/home-left-image.png" alt=""></i>';
                    } else if (type == 'home-rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/home-right-image.png" alt=""></i>';
                    } else if (type == 'home-top-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/home-top-image.png" alt=""></i>';
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
                        section += '<i class="icon"><img height="45" title="Align Left" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/home-left-image.png" alt=""></i>';
                    } else if (type == 'home-rt-txt') {
                        section += '<i class="icon"><img height="45" title="Align Right" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/home-right-image.png" alt=""></i>';
                    } else if (type == 'home-top-txt') {
                        section += '<i class="icon"><img height="45" title="Align Top" width="50" src="' + site_url + '/assets/images/packages/visualcomposer/home-top-image.png" alt=""></i>';
                    }

                    section += '</div>';
                    section += '</div>';
                    section += '<div class="clearfix"></div>';
                    $('#section-container').find('div[data-editor=' + edit + ']').html(section);
                }
            }
        },

        moduleSectionsBusiness: function(caption, config, configTxt, recids, recTitle, edit, template, layoutType) {
            var section = '';
            var customize = '<a href="javascript:;" data-module="business" class="customize-icon" title="Customize"> <i class="fa fa-wrench" aria-hidden="true"></i> </a>';
            if (edit == 'N') {
                var iCount = 'item-' + ($('.ui-state-default').length + 1);

                section += '<div class="ui-state-default">';
                section += '<i title="Drag" class="action-icon move fa fa-arrows-alt"></i>';
                section += '<a href="javascript:;" class="close-btn" title="Delete">';
                section += '<i class="action-icon delete fa fa-trash"></i>';
                section += '</a>';
                section += '<a href="javascript:;" data-filter="' + template + '" title="Edit" data-id="' + iCount + '" class="business-module">';
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
                section += '<a data-id="' + iCount + '" data-filter="' + template + '" title="Add more" class="add-link business-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="business" />';
                section += '<div class="clearfix"></div>';
                section += '</div>';

                if ($('#section-container .ui-state-default').length > 0) {
                    $(section).insertAfter($('#section-container .ui-state-default:last'));
                } else {
                    $('#section-container').append(section);
                }
            } else {
                var businessIds = [];
                var businessTitles = [];
                var businessCustomized = [];
                var businessImg = [];
                var businessImgSrc = [];
                var businessImgheight = [];
                var businessImgwidth = [];
                var businessImgpoint = [];
                var businessPhone = [];
                var businessEmail = [];
                var businessWebsite = [];
                var businessAddress = [];
                var businessDescription = [];


                $('.section-item[data-editor=' + edit + '] li').each(function(key, val) {
                    var iId = $(this).data('id');
                    businessIds.push(iId);

                    var iTitle = $(this).text();
                    businessTitles.push(iTitle);

                    var Icustomized = $(this).data('customized');
                    if (typeof Icustomized != 'undefined') {
                        businessCustomized.push(Icustomized);
                    }

                    var Iimg = $(this).data('img');
                    if (typeof Iimg != 'undefined') {
                        businessImg.push(Iimg);
                    }

                    var Iimgsrc = $(this).data('imgsrc');
                    if (typeof Iimgsrc != 'undefined') {
                        businessImgSrc.push(Iimgsrc);
                    }

                    var Iimgheight = $(this).data('imgheight');
                    if (typeof Iimgheight != 'undefined') {
                        businessImgheight.push(Iimgheight);
                    }

                    var Iimgwidth = $(this).data('imgwidth');
                    if (typeof Iimgwidth != 'undefined') {
                        businessImgwidth.push(Iimgwidth);
                    }

                    var Iimgpoint = $(this).data('imgpoint');
                    if (typeof Iimgpoint != 'undefined') {
                        businessImgpoint.push(Iimgpoint);
                    }

                    var Iphone = $(this).data('phone');
                    if (typeof Iphone != 'undefined') {
                        businessPhone.push(Iphone);
                    }

                    var Iemail = $(this).data('email');
                    if (typeof Iemail != 'undefined') {
                        businessEmail.push(Iemail);
                    }

                    var Iwebsite = $(this).data('website');
                    if (typeof Iwebsite != 'undefined') {
                        businessWebsite.push(Iwebsite);
                    }

                    var Iaddress = $(this).data('address');
                    if (typeof Iaddress != 'undefined') {
                        businessAddress.push(Iaddress);
                    }

                    var Idescription = $(this).data('description');
                    if (typeof Idescription != 'undefined') {
                        businessDescription.push(Idescription);
                    }

                });

                $.each(recids, function(index, value) {
                    businessIds.push(value);
                    businessTitles.push(recTitle[index]);
                    businessCustomized.push(false);
                    businessImg.push('');
                    businessImgSrc.push('');
                    businessImgheight.push('');
                    businessImgwidth.push('');
                    businessImgpoint.push('');
                    businessPhone.push('');
                    businessEmail.push('');
                    businessWebsite.push('');
                    businessAddress.push('');
                    businessDescription.push('');
                });

                section += '<div class="col-md-12">';
                section += '<label><b>' + caption + '</b>&nbsp;</label>';
                section += '<ul class="record-list">';
                $.each(businessIds, function(index, value) {
                    section += '<li data-customized="' + businessCustomized[index] + '" data-img="' + businessImg[index] + '" data-imgsrc="' + businessImgSrc[index] + '" data-imgheight="' + businessImgheight[index] + '" data-imgwidth="' + businessImgwidth[index] + '" data-imgpoint="' + businessImgpoint[index] + '" data-phone="' + businessPhone[index] + '" data-email="' + businessEmail[index] + '" data-website="' + businessWebsite[index] + '" data-address="' + businessAddress[index] + '" data-description="' + businessDescription[index] + '" data-id="' + value + '" id="' + value + '-item-' + edit + '">' + businessTitles[index] + '<a href="javascript:;" class="close-icon" title="Delete"><i class="ri-delete-bin-line" aria-hidden="true"></i></a></li>';
                });
                section += '</ul>';
                section += '<a data-id="' + edit + '" data-filter="' + template + '" title="Add more" class="add-link business-module" href="javascript:;">';
                section += '<i class="fa fa-plus"></i>&nbsp;Add more';
                section += '</a>';
                section += '</div>';
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-layout="' + layoutType + '" data-caption="' + caption + '" data-type="module" value="business" />';
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

        moduleSectionsPublication: function(caption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass) {
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
                section += '<input id="' + iCount + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-extraclass="' + extraclass + '" data-layout="' + layoutType + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="publication" />';
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
                section += '<input id="' + edit + '" data-filter="' + template + '" type="hidden" data-config="' + config + '" data-extraclass="' + extraclass + '" data-layout="' + layoutType + '" data-desc="' + desc + '" data-caption="' + caption + '" data-type="module" value="publication" />';
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
        submitFrmSectionBusinessModuleTemplate: function() {
            if ($('#frmSectionBusinessModuleTemplate').validate().form()) {
                var edit = $('#frmSectionBusinessModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionBusinessModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionBusinessModuleTemplate input[name=section_title]').val();
                var template = $('#frmSectionBusinessModuleTemplate input[name=template]').val();
                var config = $('#frmSectionBusinessModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionBusinessModuleTemplate #config option:selected').text();
                var layout = $('#frmSectionBusinessModuleTemplate select[name=layoutType]').val();

                builder.businessTemplate(val, config, template, edit, configTxt, layout);
                $('#sectionBusinessModuleTemplate').modal('hide');
            }
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

        submitFrmSectionPublicationModuleTemplate: function() {
            if ($('#frmSectionPublicationModuleTemplate').validate().form()) {
                var edit = $('#frmSectionPublicationModuleTemplate input[name=editing]').val() != '' ? $('#frmSectionPublicationModuleTemplate input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var val = $('#frmSectionPublicationModuleTemplate input[name=section_title]').val();
                var sectionlimit = $('#frmSectionPublicationModuleTemplate input[name=section_limit]').val();
                var sectiondesc = $('#frmSectionPublicationModuleTemplate textarea[name=section_description]').val();
                var template = $('#frmSectionPublicationModuleTemplate input[name=template]').val();
                var config = $('#frmSectionPublicationModuleTemplate select[name=section_config]').val();
                var configTxt = $('#frmSectionPublicationModuleTemplate #config option:selected').text();
                var extra_class = $('#frmSectionPublicationModuleTemplate input[name=extra_class]').val();
                var startdate = $('#frmSectionPublicationModuleTemplate input[name=publications_start_date_time]').val();
                var enddate = $('#frmSectionPublicationModuleTemplate input[name=publications_end_date_time]').val();
                var publicationscat = $('#frmSectionPublicationModuleTemplate select[name=publicationscat]').val();
                var layout = $('#frmSectionPublicationModuleTemplate select[name=layoutType]').val();
                builder.publicationTemplate(val, sectionlimit, sectiondesc, config, template, edit, configTxt, layout, extra_class, startdate, enddate, publicationscat);
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
                var val = sectionTitleCk.getData();
                var extClass = $('#frmSectionTitle #extraClass').val();
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
                var imgsrc = $('#frmSectionOnlyImage').find('.thumbnail img').attr('src');
                var type = $('#frmSectionOnlyImage input[name=selector]:checked').val();
                builder.addImage(imgsrc, val, imgCaption, type, edit, folderid);
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
                var imgsrc = $('#frmSectionOnlyDocument').find('.thumbnail img').attr('src');
                builder.addDocument(imgsrc, val, edit, folderid);
                builder.reInitSortable();
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
        submitFrmSectionBusinessModule: function() {
            if ($('#frmSectionBusinessModule').validate().form()) {
                var edit = $('#frmSectionBusinessModule input[name=editing]').val() != '' ? $('#frmSectionBusinessModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionBusinessModule input[name=template]').val();
                var imgCaption = $('#frmSectionBusinessModule #section_title').val();
                var config = $('#frmSectionBusinessModule #config').val();
                var layoutType = $('#frmSectionBusinessModule select[name=layoutType]').val();
                var configTxt = $('#frmSectionBusinessModule #config option:selected').text();
                var recids = [];
                var recTitle = [];
                $('#sectionBusinessModule #datatable_business_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsBusiness(imgCaption, config, configTxt, recids, recTitle, edit, template, layoutType);
                builder.reInitSortable();
                $('#sectionBusinessModule').modal('hide');
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
        submitFrmSectionPublicationModule: function() {
            if ($('#frmSectionPublicationModule').validate().form()) {
                var edit = $('#frmSectionPublicationModule input[name=editing]').val() != '' ? $('#frmSectionPublicationModule input[name=editing]').val() : 'N';
                $('#no-content').addClass('d-none');
                $('#has-content').removeClass('d-none');
                var template = $('#frmSectionPublicationModule input[name=template]').val();
                var imgCaption = $('#frmSectionPublicationModule #section_title').val();
                var desc = $('#frmSectionPublicationModule #section_description').val();
                var extraclass = $('#frmSectionPublicationModule #extra_class').val();
                var config = $('#frmSectionPublicationModule #config').val();
                var configTxt = $('#frmSectionPublicationModule #config option:selected').text();
                var layoutType = $('#frmSectionPublicationModule select[name=layoutType]').val();
                var recids = [];
                var recTitle = [];
                $('#sectionPublicationModule #datatable_publication_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
                builder.moduleSectionsPublication(imgCaption, desc, config, configTxt, recids, recTitle, edit, template, layoutType, extraclass);
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
                $('#sectionNewsModule #datatable_news_ajax .chkChoose:checked').each(function() {
                    recids.push($(this).val());
                    recTitle.push($(this).data('title'));
                });
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
        }
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

//$(document).on('click', '.ck-editor__editable', function (event) {
//    $(this).focus();
//});

var submitted = false;
$('#' + seoFormId).submit(function(event) {
    builder.fillFormObj();
    submitted = true;
});

$(document).on('change', '#cuisine_filter', function() {
    if ($(this).prop('checked')) {
        $('#sectionBusinessModuleTemplate #cuisine_filter_select').closest('.form-group').removeClass('hide');
    } else {
        $('#sectionBusinessModuleTemplate #cuisine_filter_select').closest('.form-group').addClass('hide');
    }
});

$(document).on('change', '#area_filter', function() {
    if ($(this).prop('checked')) {
        $('#sectionBusinessModuleTemplate #area_filter_select').closest('.form-group').removeClass('hide');
    } else {
        $('#sectionBusinessModuleTemplate #area_filter_select').closest('.form-group').addClass('hide');
    }
});

$(document).on('change', '#meal_times_filter', function() {
    if ($(this).prop('checked')) {
        $('#sectionBusinessModuleTemplate #meal_served_filter_select').closest('.form-group').removeClass('hide');
    } else {
        $('#sectionBusinessModuleTemplate #meal_served_filter_select').closest('.form-group').addClass('hide');
    }
});

$(document).on('change', '#price_range', function() {
    if ($(this).prop('checked')) {
        $('#sectionBusinessModuleTemplate #price_range_select').closest('.form-group').removeClass('hide');
    } else {
        $('#sectionBusinessModuleTemplate #price_range_select').closest('.form-group').addClass('hide');
    }
});

$(document).on('change', '#price_range_filter', function() {
    if ($(this).prop('checked')) {
        $('#sectionBusinessModuleTemplate #price_range_filter_select').closest('.form-group').removeClass('hide');
    } else {
        $('#sectionBusinessModuleTemplate #price_range_filter_select').closest('.form-group').addClass('hide');
    }
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
    $("#partition_tab").show();
    $("#templates_tab").show();
    $("#publications_tab").show();
    $("#events_tab").show();
    $("#blogs_tab").show();
    $("#news_tab").show();
    $("#alerts_tab").show();
    $("#organizations_tab").show();
    $("#department_tab").show();
    $("#links_tab").show();
    $("#faqs_tab").show();
    $("#photoalbum_tab").show();
    $("#videogallery_tab").show();
    $(".photo-gallery").show();
    $(".photoalbum").show();
    $(".photoalbum-template").show();
    $(".videogallery").show();
    $(".videogallery-template").show();
    $(".video-gallery").show();
    $(".alerts").show();
    $(".alerts-template").show();
    $(".organizations").show();
    $(".department").show();
    $(".department-template").show();
    $(".links").show();
    $(".links-template").show();
    $(".faqs").show();
    $(".faqs-template").show();
    $(".news").show();
    $(".news-template").show();
    $(".blogs").show();
    $(".blogs-template").show();
    $(".events").show();
    $(".events-template").show();
    $(".publication").show();
    $(".publication-template").show();
    $(".only-spacer").show();
    $(".home-information").show();
    $(".two-columns").show();
    $(".three-columns").show();
    $(".four-columns").show();
    $(".one-three-columns").show();
    $(".three-one-columns").show();
    $("#services_tab").show();
    $("#products_tab").show();
    $("#careers_tab").show();
    $("#testimonials_tab").show();
    $("#team_tab").show();
    $("#shows_tab").show();
    $("#gallery_tab").show();
    $(".services").show();
    $(".services").show();
    $(".service-template").show();
    $(".service-template").show();
    $(".products").show();
    $(".product-template").show();
    $(".careers").show();
    $(".career-template").show();
    $(".testimonials").show
    $(".testimonial-template").show();
    $(".team").show();
    $(".team-template").show();
    $(".shows").show();
    $(".show-template").show();
    $(".gallery-list").show();
    $(".gallery-template").show();
    $(".iframeonly").show();
    var innerplus = false;
    var innerplusid = '';
    var innerplusdada = $(this).attr("data-innerplus");
    if (typeof innerplusdada != 'undefined') {
        var innerplus = true;
        $(this).addClass('clicked');
        var innerplusid = $(this).attr("data-innerplus");
        // $( "#partition_tab" ).removeClass( "active" )
        // $( "#all_tab" ).removeClass( "active" )
        // var alltab =document.getElementById("all_tab"); 
        // alltab.className += 'active';
        $("#templates_tab").hide();
        $("#alerts_tab").hide();
        $("#organizations_tab").hide();
        $("#department_tab").hide();
        $("#links_tab").hide();
        $("#faqs_tab").hide();
        $("#photoalbum_tab").hide();
        $("#videogallery_tab").hide();
        $("#publications_tab").hide();
        $("#events_tab").hide();
        $("#blogs_tab").hide();
        $("#news_tab").hide();
        $(".photoalbum").hide();
        $(".photoalbum-template").hide();
        $(".videogallery").hide();
        $(".videogallery-template").hide();
        $(".photo-gallery").hide();
        $(".video-gallery").hide();
        $(".alerts").hide();
        $(".alerts-template").hide();
        $(".organizations").hide();
        $(".department").hide();
        $(".department-template").hide();
        $(".links").hide();
        $(".links-template").hide();
        $(".faqs").hide();
        $(".faqs-template").hide();
        $(".news").hide();
        $(".news-template").hide();
        $(".blogs").hide();
        $(".blogs-template").hide();
        $(".events").hide();
        $(".events-template").hide();
        $(".publication").hide();
        $(".publication-template").hide();
        $(".only-spacer").hide();
        $(".home-information").hide();
        $("#partition_tab").hide();
        $(".two-columns").hide();
        $(".three-columns").hide();
        $(".four-columns").hide();
        $(".one-three-columns").hide();
        $(".three-one-columns").hide();
        $("#services_tab").hide();
        $("#products_tab").hide();
        $("#careers_tab").hide();
        $("#testimonials_tab").hide();
        $("#team_tab").hide();
        $("#shows_tab").hide();
        $("#gallery_tab").hide();
        $(".services").hide();
        $(".services").hide();
        $(".service-template").hide();
        $(".service-template").hide();
        $(".products").hide();
        $(".product-template").hide();
        $(".careers").hide();
        $(".career-template").hide();
        $(".testimonials").hide();
        $(".testimonial-template").hide();
        $(".team").hide();
        $(".team-template").hide();
        $(".shows").hide();
        $(".show-template").hide();
        $(".gallery-list").hide();
        $(".gallery-template").hide();
        $(".iframeonly").hide();
        $(".all_tab").trigger("click");
    }
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

// $(document).on('click','#add-text-block',function(event){
//  $('#sectionContent').modal({
//        backdrop: 'static',
//        keyboard: false
//    });    
// });
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
    $('#businessCustomize .photo_gallery_1 img').attr('src', site_url + '/assets/images/packages/visualcomposer/plus-no-image.png');
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
    $('#sectionTitle').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .onlytitleclass').length == 1) {
        $('#sectionTitle textarea').val($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .onlytitleclass').val());
        $('#sectionTitle #addSection').text('Update');
        $('#sectionTitle #exampleModalLabel b').text('Edit Section Title');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .onlytitleclass').length == 1) {
        $('#sectionTitle textarea').val($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .onlytitleclass').val());
        $('#sectionTitle #addSection').text('Update');
        $('#sectionTitle #exampleModalLabel b').text('Edit Section Title');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .onlytitleclass').length == 1) {

        $('#sectionTitle textarea').val($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .onlytitleclass').val());
        $('#sectionTitle #addSection').text('Update');
        $('#sectionTitle #exampleModalLabel b').text('Edit Section Title');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .onlytitleclass').length == 1) {
        $('#sectionTitle textarea').val($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .onlytitleclass').val());
        $('#sectionTitle #addSection').text('Update');
        $('#sectionTitle #exampleModalLabel b').text('Edit Section Title');
    } else if (typeof id != 'undefined') {
        var extclass = $('.titleOnly #' + id).data('class');
        var value = $('.titleOnly #' + id).val();
        $('#sectionTitle input[name=editing]').val(id);
        $('#sectionTitle #extraClass').val(extclass);
        $('#sectionTitle textarea').val(value);
        $('#sectionTitle #addSection').text('Update');
        $('#sectionTitle #exampleModalLabel b').text('Edit Section Title');
    } else {
        $('#sectionTitle input[name=editing]').val('');
        $('#sectionTitle #extraClass').val('');
        $('#sectionTitle textarea').val('');
        $('#sectionTitle #addSection').text('Add');
        $('#sectionTitle #exampleModalLabel b').text('Section Title');
    }

    $('#sectionTitle .ck-editor').remove();
    ClassicEditor.create(document.querySelector('#sectionTitle #ck-area'), cmsConfig)
        .then(editor => {
            window.sectionTitleCk = editor;
        })
        .catch(error => {
            console.error(error);
        });
});
$('#sectionTitle').on('shown.bs.modal', function() {
    builder.resizeOnlyTitleModal();
    validateSectionOnlyTitle.init();
}).on('hidden.bs.modal', function() {
    validateSectionOnlyTitle.reset();
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
    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .buttonclass').length == 1) {
        var target = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .buttonclass').data('linktarget');
        var align = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .buttonclass').data('type');
        $('#sectionButton input[name=section_title]').val($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .buttonclass').data('caption'));
        $('#sectionButton #section_link').val($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .buttonclass').val());
        $('#sectionButton select[name=section_button_target] option[value=' + target + ']').prop('selected', true);
        $('#sectionButton input[name=selector][value="' + align + '"]').prop('checked', true);
        $('#sectionButton #addSection').text('Update');
        $('#sectionButton #exampleModalLabel b').text('Edit Button Info');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .buttonclass').length == 1) {
        var target = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .buttonclass').data('linktarget');
        var align = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .buttonclass').data('type');
        $('#sectionButton input[name=section_title]').val($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .buttonclass').data('caption'));
        $('#sectionButton #section_link').val($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .buttonclass').val());
        $('#sectionButton select[name=section_button_target] option[value=' + target + ']').prop('selected', true);
        $('#sectionButton input[name=selector][value="' + align + '"]').prop('checked', true);
        $('#sectionButton #addSection').text('Update');
        $('#sectionButton #exampleModalLabel b').text('Edit Button Info');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .buttonclass').length == 1) {
        var target = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .buttonclass').data('linktarget');
        var align = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .buttonclass').data('type');
        $('#sectionButton input[name=section_title]').val($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .buttonclass').data('caption'));
        $('#sectionButton #section_link').val($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .buttonclass').val());
        $('#sectionButton select[name=section_button_target] option[value=' + target + ']').prop('selected', true);
        $('#sectionButton input[name=selector][value="' + align + '"]').prop('checked', true);
        $('#sectionButton #addSection').text('Update');
        $('#sectionButton #exampleModalLabel b').text('Edit Button Info');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .buttonclass').length == 1) {
        var target = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .buttonclass').data('linktarget');
        var align = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .buttonclass').data('type');
        $('#sectionButton input[name=section_title]').val($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .buttonclass').data('caption'));
        $('#sectionButton #section_link').val($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .buttonclass').val());
        $('#sectionButton select[name=section_button_target] option[value=' + target + ']').prop('selected', true);
        $('#sectionButton input[name=selector][value="' + align + '"]').prop('checked', true);
        $('#sectionButton #addSection').text('Update');
        $('#sectionButton #exampleModalLabel b').text('Edit Button Info');
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


$(document).on('click', '.text-block', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionContent').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .textareaclass').length == 1) {
        var extclass = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .textareaclass').data('class');
        var value = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .textareaclass').val();
        $('#sectionContent #extraClass').val(extclass);
        $('#sectionContent textarea').val(value);
        $('#sectionContent #addSection').text('Update');
        $('#sectionContent #exampleModalLabel b').text('Edit Text Block');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .textareaclass').length == 1) {
        var extclass = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .textareaclass').data('class');
        var value = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .textareaclass').val();
        $('#sectionContent #extraClass').val(extclass);
        $('#sectionContent textarea').val(value);
        $('#sectionContent #addSection').text('Update');
        $('#sectionContent #exampleModalLabel b').text('Edit Text Block');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .textareaclass').length == 1) {
        var extclass = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .textareaclass').data('class');
        var value = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .textareaclass').val();
        $('#sectionContent #extraClass').val(extclass);
        $('#sectionContent textarea').val(value);
        $('#sectionContent #addSection').text('Update');
        $('#sectionContent #exampleModalLabel b').text('Edit Text Block');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .textareaclass').length == 1) {
        var extclass = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .textareaclass').data('class');
        var value = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .textareaclass').val();
        $('#sectionContent #extraClass').val(extclass);
        $('#sectionContent textarea').val(value);
        $('#sectionContent #addSection').text('Update');
        $('#sectionContent #exampleModalLabel b').text('Edit Text Block');
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
$('#sectionContent').on('shown.bs.modal', function() {
    builder.resizeOnlyContentModal();
    validateSectionOnlyContent.init();
}).on('hidden.bs.modal', function() {
    validateSectionOnlyContent.reset();
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


$(document).on('click', '.only-image', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionOnlyImage').modal({
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).data('id');
    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .imageclass').length == 1) {
        var colData = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .imageclass');
        var caption = colData.data('caption');
        var value = colData.val();
        var src = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 img').attr('src');
        var align = colData.data('type');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionOnlyImage .imgip').val(value);
        $('#sectionOnlyImage #img_title').val(caption);
        $('#sectionOnlyImage #vfolder_id').val(folderid);
        $('#sectionOnlyImage img:first').attr('src', src);
        $('#sectionOnlyImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionOnlyImage #addSection').text('Update');
        $('#sectionOnlyImage #exampleModalLabel b').text('Edit Image Block');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .imageclass').length == 1) {
        var colData = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .imageclass');
        var caption = colData.data('caption');
        var value = colData.val();
        var src = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 img').attr('src');
        var align = colData.data('type');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionOnlyImage .imgip').val(value);
        $('#sectionOnlyImage #img_title').val(caption);
        $('#sectionOnlyImage #vfolder_id').val(folderid);
        $('#sectionOnlyImage img:first').attr('src', src);
        $('#sectionOnlyImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionOnlyImage #addSection').text('Update');
        $('#sectionOnlyImage #exampleModalLabel b').text('Edit Image Block');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .imageclass').length == 1) {
        var colData = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .imageclass');
        var caption = colData.data('caption');
        var value = colData.val();
        var src = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 img').attr('src');
        var align = colData.data('type');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionOnlyImage .imgip').val(value);
        $('#sectionOnlyImage #img_title').val(caption);
        $('#sectionOnlyImage #vfolder_id').val(folderid);
        $('#sectionOnlyImage img:first').attr('src', src);
        $('#sectionOnlyImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionOnlyImage #addSection').text('Update');
        $('#sectionOnlyImage #exampleModalLabel b').text('Edit Image Block');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .imageclass').length == 1) {
        var colData = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .imageclass');
        var caption = colData.data('caption');
        var value = colData.val();
        var src = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 img').attr('src');
        var align = colData.data('type');
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        $('#sectionOnlyImage .imgip').val(value);
        $('#sectionOnlyImage #img_title').val(caption);
        $('#sectionOnlyImage #vfolder_id').val(folderid);
        $('#sectionOnlyImage img:first').attr('src', src);
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
        var value = $('.img-area #' + id).val();
        var src = $('.img-area .' + id).attr('src');
        var align = $('.img-area #' + id).data('type');
        $('#sectionOnlyImage input[name=editing]').val(id);
        $('#sectionOnlyImage .imgip').val(value);
        $('#sectionOnlyImage #img_title').val(caption);
        $('#sectionOnlyImage #vfolder_id').val(folderid);
        $('#sectionOnlyImage img:first').attr('src', src);
        $('#sectionOnlyImage input[value="' + align + '"]').prop('checked', true);
        $('#sectionOnlyImage #addSection').text('Update');
        $('#sectionOnlyImage #exampleModalLabel b').text('Edit Image Block');
    } else {
        $('#sectionOnlyImage input[name=editing]').val('');
        $('#sectionOnlyImage .imgip').val('');
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
        //console.log(event.latLng);
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
            //console.log(pointposition);
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


$(document).on('click', '.only-document', function(event) {

    $('#pgBuiderSections').modal('hide');
    $('#sectionOnlyDocument').modal({
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).data('id');
    if ($('.columns_1.clicked').parents('.col_1').find('.two_col_1 .documentclass').length == 1) {
        var colData = $('.columns_1.clicked').parents('.col_1').find('.two_col_1 .documentclass');
        var value = colData.val();
        var doccopid = colData.parents('.section-item').find("#dochiddenid").val();
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        /*alert(doccopid);*/
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

        var src = colData.attr('src');
        $('#sectionOnlyDocument .imgip1').val(doccopid);
        $('#sectionOnlyDocument #vfolder_id').val(folderid);
        $('#sectionOnlyDocument img:first').attr('src', site_url + 'assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionOnlyDocument #addSection').text('Update');
        $('#sectionOnlyDocument #exampleModalLabel b').text('Edit Document Block');
    } else if ($('.columns_2.clicked').parents('.col_1').find('.two_col_2 .documentclass').length == 1) {
        var colData = $('.columns_2.clicked').parents('.col_1').find('.two_col_2 .documentclass');
        var value = colData.val();
        var doccopid = colData.parents('.section-item').find("#dochiddenid").val();
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        /*alert(doccopid);*/
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

        var src = colData.attr('src');
        $('#sectionOnlyDocument .imgip1').val(doccopid);
        $('#sectionOnlyDocument #vfolder_id').val(folderid);
        $('#sectionOnlyDocument img:first').attr('src', site_url + '/assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionOnlyDocument #addSection').text('Update');
        $('#sectionOnlyDocument #exampleModalLabel b').text('Edit Document Block');
    } else if ($('.columns_3.clicked').parents('.col_1').find('.two_col_3 .documentclass').length == 1) {
        var colData = $('.columns_3.clicked').parents('.col_1').find('.two_col_3 .documentclass');
        var value = colData.val();
        var doccopid = colData.parents('.section-item').find("#dochiddenid").val();
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        /*alert(doccopid);*/
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

        var src = colData.attr('src');
        $('#sectionOnlyDocument .imgip1').val(doccopid);
        $('#sectionOnlyDocument #vfolder_id').val(folderid);
        $('#sectionOnlyDocument img:first').attr('src', site_url + '/assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionOnlyDocument #addSection').text('Update');
        $('#sectionOnlyDocument #exampleModalLabel b').text('Edit Document Block');
    } else if ($('.columns_4.clicked').parents('.col_1').find('.two_col_4 .documentclass').length == 1) {
        var colData = $('.columns_4.clicked').parents('.col_1').find('.two_col_4 .documentclass');
        var value = colData.val();
        var doccopid = colData.parents('.section-item').find("#dochiddenid").val();
        var strfid = id.split("-");
        if (colData.data('folderid') != '' && typeof colData.data('folderid') != 'undefined') {
            var folderid = colData.data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        /*alert(doccopid);*/
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

        var src = colData.attr('src');
        $('#sectionOnlyDocument .imgip1').val(doccopid);
        $('#sectionOnlyDocument #vfolder_id').val(folderid);
        $('#sectionOnlyDocument img:first').attr('src', site_url + '/assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionOnlyDocument #addSection').text('Update');
        $('#sectionOnlyDocument #exampleModalLabel b').text('Edit Document Block');
    } else if (typeof id != 'undefined') {
        var value = $('.img-document #' + id).val();
        var doccopid = $('.img-document #' + id).parents('.section-item').find("#dochiddenid").val();
        var strfid = id.split("-");
        if ($('.img-document #' + id).data('folderid') != '' && typeof $('.img-document #' + id).data('folderid') != 'undefined') {
            var folderid = $('.img-document #' + id).data('folderid');
        } else {
            var folderid = $('.folder_' + strfid[1]).val();
        }
        /*alert(doccopid);*/
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

        var src = $('.' + id).attr('src');
        $('#sectionOnlyDocument input[name=editing]').val(id);
        $('#sectionOnlyDocument #vfolder_id').val(folderid);
        $('#sectionOnlyDocument .imgip1').val(doccopid);
        $('#sectionOnlyDocument img:first').attr('src', site_url + '/assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionOnlyDocument #addSection').text('Update');
        $('#sectionOnlyDocument #exampleModalLabel b').text('Edit Document Block');
    } else {
        $('#sectionOnlyDocument input[name=editing]').val('');
        $('#sectionOnlyDocument .imgip1').val('');
        $("#sectionOnlyDocument .dochtml").html('');
        $('#sectionOnlyDocument #img_title1').val('');
        $('#sectionOnlyDocument img').attr('src', site_url + '/assets/images/packages/visualcomposer/plus-no-image.png');
        $('#sectionOnlyDocument #addSection').text('Add');
        $('#sectionOnlyDocument #exampleModalLabel b').text('Document Block');
    }
});
$('#sectionOnlyDocument').on('shown.bs.modal', function() {
    builder.resizeOnlyImageModal();
    validateSectionOnlyDocument.init();
}).on('hidden.bs.modal', function() {
    validateSectionOnlyDocument.reset();
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
        $('#sectionImage img:first').attr('src', site_url + '/assets/images/packages/visualcomposer/plus-no-image.png');
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
        $('#sectionHomeImage img:first').attr('src', site_url + '/assets/images/packages/visualcomposer/plus-no-image.png');
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


$(document).on('click', '.business-template', function(event) {
    $('#pgBuiderSections').modal('hide');
    $('#sectionBusinessModuleTemplate').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).data('id');
    var layout = '';
    if (typeof id != 'undefined') {
        var extclass = $('#' + id).data('class');
        var value = $('#' + id).val();
        layout = $('#' + id).data('layout');
        $('#sectionBusinessModuleTemplate input[name=editing]').val(id);
        $('#sectionBusinessModuleTemplate #section_title').val($.trim(value));
        $('#sectionBusinessModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionBusinessModuleTemplate #addSection').text('Update');
        $('#sectionBusinessModuleTemplate #exampleModalLabel b').text('Edit Business');
    } else {
        var value = $(this).text();
        $('#sectionBusinessModuleTemplate input[name=editing]').val('');
        $('#sectionBusinessModuleTemplate #section_title').val($.trim(value));
        $('#sectionBusinessModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionBusinessModuleTemplate #addSection').text('Add');
        $('#sectionBusinessModuleTemplate #exampleModalLabel b').text('Add Business');
    }
    var currentFilter = $(this).data('filter');
    $('#sectionBusinessModuleTemplate input[name=template]').val(currentFilter);
    if (
        currentFilter == 'restaurant-by-cuisine' ||
        currentFilter == 'restaurant-by-area' ||
        currentFilter == 'restaurant-by-meal-times' ||
        currentFilter == 'restaurant-by-price-range') {
        $('#sectionBusinessModuleTemplate #filters').removeClass('hide');

        if (currentFilter == 'restaurant-by-cuisine') {
            $('#sectionBusinessModuleTemplate #cuisine_filter,#cuisine_filter_show').prop('checked', true);
            $('#sectionBusinessModuleTemplate #cuisine_filter,#cuisine_filter_show').trigger('change');
        }
        if (currentFilter == 'restaurant-by-area') {
            $('#sectionBusinessModuleTemplate #area_filter,#area_filter_show').prop('checked', true);
            $('#sectionBusinessModuleTemplate #area_filter,#area_filter_show').trigger('change');
        }
        if (currentFilter == 'restaurant-by-meal-times') {
            $('#sectionBusinessModuleTemplate #meal_times_filter,#meal_times_filter_show').prop('checked', true);
            $('#sectionBusinessModuleTemplate #meal_times_filter,#meal_times_filter_show').trigger('change');
        }
        if (currentFilter == 'restaurant-by-price-range') {
            $('#sectionBusinessModuleTemplate #price_range_filter,#price_range_filter_show').prop('checked', true);
            $('#sectionBusinessModuleTemplate #price_range_filter,#price_range_filter_show').trigger('change');
        }


    }
});
$('#sectionBusinessModuleTemplate').on('shown.bs.modal', function() {
    $('#sectionBusinessModuleTemplate select').selectpicker();
    validateBusinessTemplate.init();
}).on('hidden.bs.modal', function() {
    $('#sectionBusinessModuleTemplate select').selectpicker('destroy');
    $('#sectionBusinessModuleTemplate #filters').addClass('hide');
    $('#sectionBusinessModuleTemplate #cuisine_filter_select').closest('.form-group').addClass('hide');
    $('#sectionBusinessModuleTemplate #area_filter_select').closest('.form-group').addClass('hide');
    $('#sectionBusinessModuleTemplate #meal_served_filter_select').closest('.form-group').addClass('hide');
    $('#sectionBusinessModuleTemplate #price_range_filter_select').closest('.form-group').addClass('hide');
    $('#sectionBusinessModuleTemplate #cuisine_filter,#cuisine_filter_show').prop('checked', false);
    $('#sectionBusinessModuleTemplate #area_filter,#area_filter_show').prop('checked', false);
    $('#sectionBusinessModuleTemplate #meal_times_filter,#meal_times_filter_show').prop('checked', false);
    $('#sectionBusinessModuleTemplate #price_range_filter,#price_range_filter_show').prop('checked', false);
    validateBusinessTemplate.reset();
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
    var layout = '';
    var slimit = '';
    var sdesc = '';
    var config = '';
    var extclass = '';
    var startdate = '';
    var enddate = '';
    var publicationscat = '';
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
        publicationscat = $('#' + id).data('publicationscat');

        $('#sectionPublicationModuleTemplate input[name=editing]').val(id);
        $('#sectionPublicationModuleTemplate #section_title').val($.trim(value));
        $('#sectionPublicationModuleTemplate #section_limit').val($.trim(slimit));
        $('#sectionPublicationModuleTemplate #section_description').val(sdesc);
        $('#sectionPublicationModuleTemplate #extra_class').val(extclass);
        if (publicationscat != '') {
            $('#sectionPublicationModuleTemplate select[name=publicationscat] option[value=' + publicationscat + ']').prop('selected', true);
        }
        $('#sectionPublicationModuleTemplate #publications_start_date_time').val(startdate);
        $('#sectionPublicationModuleTemplate #publications_end_date_time').val(enddate);
        $('#sectionPublicationModuleTemplate select[name=layoutType] option[value=' + layout + ']').prop('selected', true);
        $('#sectionPublicationModuleTemplate select[name=section_config] option[value=' + config + ']').prop('selected', true);
        $('#sectionPublicationModuleTemplate #addSection').text('Update');
        $('#sectionPublicationModuleTemplate #exampleModalLabel b').text('Edit Publication');
    } else {
        var value = $(this).text();
        $('#sectionPublicationModuleTemplate input[name=editing]').val('');
        $('#sectionPublicationModuleTemplate #section_title').val($.trim(value));
        $('#sectionPublicationModuleTemplate #extra_class').val(extclass);
        $('#sectionPublicationModuleTemplate select[name=publicationscat] option:first').prop('selected', true);
        $('#sectionPublicationModuleTemplate #publications_start_date_time').val(startdate);
        $('#sectionPublicationModuleTemplate #publications_end_date_time').val(enddate);
        $('#sectionPublicationModuleTemplate select[name=layoutType] option:first').prop('selected', true);
        $('#sectionPublicationModuleTemplate #addSection').text('Add');
        $('#sectionPublicationModuleTemplate #exampleModalLabel b').text('Add Publication');
    }
    $('#sectionPublicationModuleTemplate #publications_start_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        minDate: 0,
        onShow: function(ct) {
            this.setOptions({})
        },
        timepicker: true
    });

    $('#sectionPublicationModuleTemplate #publications_end_date_time').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        onShow: function(ct) {
            this.setOptions({
                minDate: jQuery('#sectionPublicationModuleTemplate #publications_start_date_time').val() ? jQuery('#sectionPublicationModuleTemplate #publications_start_date_time').val() : false
            })
        },
        timepicker: true
    });
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
        $('#sectionLinksModuleTemplate #exampleModalLabel b').text('Edit Links');
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
        $('#sectionLinksModuleTemplate #exampleModalLabel b').text('Add Links');
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

                } else {
                    //                    console.log(data.message)
                }
            },
            complete: function() {}
        });
    }
});
/*../Preview opener code=====================================*/


//Validations code=====================================

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
                }
            },
            messages: {
                img1: "Document is required"
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

        $('#frmSectionTitle #addSection').click(function() {
            sectionTitleCk.updateSourceElement();
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
                            return $('#frmSectionPublicationModule input[name="editing"]').val().length == '';
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
var validateBusinessTemplate = function() {
    var handleBusinessTemplate = function() {
        $("#frmSectionBusinessModuleTemplate").validate({
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
                $('.alert-danger', $('#frmSectionBusinessModuleTemplate')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                builder.submitFrmSectionBusinessModuleTemplate();
                return false;
            }
        });
        $('#frmSectionBusinessModuleTemplate input').keypress(function(e) {
            if (e.which == 13) {
                builder.submitFrmSectionBusinessModuleTemplate();
                return false;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function() {
            handleBusinessTemplate();
        },
        reset: function() {
            var validator = $("#frmSectionBusinessModuleTemplate").validate();
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
/*../Validations code=====================================*/