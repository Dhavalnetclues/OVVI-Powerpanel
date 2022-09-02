"use strict";

/* menuResize function S */
    function menuResize() {    
        $('.dropdown-toggle').on('mouseover', function() {
            if (false === $(this).parent('li').hasClass('multi-level')) {
                var off = $(this).closest('li').children('ul').offset(),
                    et = off.top,
                    el = off.left,
                    eh = $(this).closest('li').children('ul').height(),
                    ew = $(this).closest('li').children('ul').width(),
                    wh = window.innerHeight,
                    ww = window.innerWidth,
                    wx = window.pageXOffset,
                    wy = window.pageYOffset;
                off.visibility = "hidden";
                if ((el + ew) > $(document).width()) {
                    $(this).parent('li').children('ul').css('left', '-100%');
                    $(this).parent('li').children('ul').css('left', '-100%');
                    $(this).parent('li').children('ul').css('left', '-100%');
                }
            }
        });
    }
/* menuResize function E */


/* fixedHeader function S */
    function fixedHeader(className,scrollHeight,fixedHeaderClass,animateClassName) {
        $(document).scroll(function() {
            var scroll = $(window).scrollTop();
            if (scroll > $(className).height() + scrollHeight) {
                $(className).addClass(fixedHeaderClass + '  ' + animateClassName);
            } else {
                $(className).removeClass(fixedHeaderClass + '  ' + animateClassName);
            }
        });
    }
/* fixedHeader function E */


/* Menu JS S */
    var mobileMenuTitle = "Menu";
    var breakpoint_hide_show = 1024;
    var MNav;
    (function($) {
        $(document).ready(function() {
            $(window).resize(function(e) {
                if ($(document).width() > breakpoint_hide_show) {
                    $(".fa-close").trigger("click");
                };
            });
            MNav = {
                initialized: false,
                mobMenuFlag: false,
                mobileMenuTitle: mobileMenuTitle,
                init: function() {
                    var $tis = this;
                    if ($tis.initialized) {
                        return;
                    }
                    $tis.initialized = true;
                    $tis.build();
                    $tis.events();
                },
                build: function() {
                    var $tis = this;
                    $tis.createMobileMenu();
                    $('input, textarea').placeholder();
                },
                events: function() {
                    var $tis = this;
                    $tis.windowResize();
                },
                createMobileMenu: function(w) {
                    var $tis = this,
                        $wrapper = $('body'),
                        $navMobile,
                        etype = $.browser.mobile ? 'touchstart' : 'click';
                    if (w !== null) {
                        w = $(window).innerWidth();
                    }
                    if (w <= breakpoint_hide_show && !$tis.mobMenuFlag) {
                        /*$('body').prepend('<nav class="nav-mobile mob-nav"><span class="mob_menu_title">' + $tis.mobileMenuTitle + '</span><ul class="mobile_menu"></ul></nav>');*/
                        $('body').prepend('<nav class="nav-mobile mob-nav"><ul class="mobile_menu"></ul></nav>');
                        $('.nav-mobile > ul').html($('.main-nav').html());
                        $('.nav-mobile b').remove();
                        $('.nav-mobile ul.dropdown-menu').removeClass().addClass("dropdown-mobile");
                        //$('.nav-mobile').css({'min-height': ($('#wrapper').height() + 270) + 'px' });
                        $navMobile = $(".nav-mobile");
                        $("#nav-mobile-btn, .nav-mobile-btn").bind(etype, function(e) {
                            e.stopPropagation();
                            e.preventDefault();
                            setTimeout(function() {
                                $wrapper.addClass('open');
                                $navMobile.addClass('open');
                            }, 25);
                            $(document).bind(etype, function(e) {
                                if (!$(e.target).hasClass('nav-mobile') && !$(e.target).parents('.nav-mobile').length) {
                                    $wrapper.removeClass('open');
                                    $navMobile.removeClass('open');
                                    $(document).unbind(etype);
                                }
                            });
                            $('>i', $navMobile).bind(etype, function() {
                                $wrapper.removeClass('open');
                                $navMobile.removeClass('open');
                                $(document).unbind(etype);
                            });
                        });
                        $tis.mobMenuFlag = true;
                    }
                },
                windowResize: function() {
                    var $tis = this;
                    $(window).resize(function() {
                        var w = $(window).innerWidth();
                        $tis.createMobileMenu(w);
                    });
                }
            };
            MNav.init();
        });
    }(jQuery));
/* Menu JS E */


/* Center Logo JS S */
    function brandCenter() {
        var getNav = $("header");
        if (getNav.hasClass("fix_width")) {
            var postsArr = new Array(),
                index = $("header.fix_width"),
                $postsList = index.find('ul.navbar-nav');

            //Create array of all posts in lists
            index.find('ul.navbar-nav > li').each(function() {
                postsArr.push($(this).html());
            });

            //Split the array at this point. The original array is altered.
            var firstList = postsArr.splice(0, Math.round(postsArr.length / 2)),
                secondList = postsArr,
                ListHTML = '';

            var createHTML = function(list) {
                ListHTML = '';
                for (var i = 0; i < list.length; i++) {
                    ListHTML += '<li>' + list[i] + '</li>'
                }
            }

            //Generate HTML for first list
            createHTML(firstList);
            $postsList.html(ListHTML);
            index.find("ul.nav").first().addClass("navbar-left");

            //Generate HTML for second list
            createHTML(secondList);
            $postsList.after('<ul class="nav navbar-nav mega_menu main-nav nq_mob_hide"></ul>').next().html(ListHTML);
            index.find("ul.nav").last().addClass("navbar-right");

            //Wrap navigation menu
            index.find("ul.nav.navbar-left").wrap("<div class='col_half left'></div>");
            index.find("ul.nav.navbar-right").wrap("<div class='col_half right'></div>");

            //Selection Class
            index.find('ul.navbar-nav > li').each(function() {
                var dropDown = $("ul.dropdown-menu", this),
                    megaMenu = $("ul.megamenu-content", this);
                dropDown.closest("li").addClass("dropdown");
                megaMenu.closest("li").addClass("dropdown multi-level");
            });
        }
    }
/* Center Logo JS E */