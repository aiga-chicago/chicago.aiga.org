/**
 * Global constants
 */
var G_BREAKPOINT_300 = 300;
var G_BREAKPOINT_520 = 520;
var G_BREAKPOINT_1020 = 1020;
var G_BREAKPOINT_1100 = 1100;

var G_IMAGE_GALLERY_TYPE_FEATURED = 'featured';

/**
 * Global on dom ready
 */
$(document).ready(function() {


    // Initialize any image galleries
    $('.box-section-image-gallery').ikitOneImageGallery({

    });

    // Initialize navigation menus
    $('.layout-nav-menu-breakpoint-' + G_BREAKPOINT_300).ikitOneNavMenu({
        breakpoint: G_BREAKPOINT_300
    });

    $('.layout-nav-menu-breakpoint-' + G_BREAKPOINT_520).ikitOneNavMenu({
        breakpoint: G_BREAKPOINT_520
    });

    $('.layout-nav-menu-breakpoint-' + G_BREAKPOINT_1020).ikitOneNavMenu({
        breakpoint: G_BREAKPOINT_1020
    });

    // Initialize hover states
    $('.highlightable').each(function() {
        decorateHighlightableImage($(this));
    });

    $('.box-bottom-button').hover(
        function() {
            $(this).parent().find('.box-bottom-button-bar').addClass('hovering');
        },
        function() {
            $(this).parent().find('.box-bottom-button-bar').removeClass('hovering');
        }
    );

    // Initialize external link styles
    $("a[target='_blank']").each(function() {
        if($(this).children('img').length == 0 && $(this).hasClass('external-link-unstyled') == false) {
            $(this).addClass('external-link');
        }
    });

    // Initialize custom select
    $('.box-form-container select, .custom-select').each(function() {
      $(this).customSelect();
    });

    onWindowResize();

});

$(window).resize(onWindowResize);

/**
 * Global on window resize
 */
function onWindowResize() {

    // Handle the feature control padding at 1100, we need to use Javascript as the padding
    // becomes dynamic beyond this breakpoint
    var windowWidth = $(window).width();
    if(windowWidth > G_BREAKPOINT_1100) {
        var extraSpace = windowWidth - G_BREAKPOINT_1100;
        $('.layout-feature .box-section-image-gallery-controls-next-button').css('paddingRight', 60 + extraSpace/2);
        $('.layout-feature .box-section-image-gallery-controls-title, .layout-feature .box-section-image-gallery-title').css('paddingLeft', 60 + extraSpace/2);
    }
    else {
        $('.layout-feature .box-section-image-gallery-controls-next-button').attr('style', '');
        $('.layout-feature .box-section-image-gallery-controls-title, .layout-feature .box-section-image-gallery-title').attr('style', '');
    }

}

/**
 * Global on window load, window load is after the entire page is loaded and heights and widths
 * etc. have all been determined. This is run after dom ready.
 */
$(window).load(function() {

    // Fix for iPad bug where box titles would have glitch artifacts, some conflict with typekit
    $('.box-section-image-gallery').css('visibility', 'visible');
    $('.box-section-image-gallery').css('height', 'auto');

    // Preload images
    preloadImage(g_templateUrl + '/images/sidebar_twitter_hl.png');
    preloadImage(g_templateUrl + '/images/sidebar_facebook_hl.png');
    preloadImage(g_templateUrl + '/images/sidebar_flickr_hl.png');
    preloadImage(g_templateUrl + '/images/sidebar_vimeo_hl.png');
    preloadImage(g_templateUrl + '/images/sidebar_rss_hl.png');
    preloadImage(g_templateUrl + '/images/footer_twitter_hl.png');
    preloadImage(g_templateUrl + '/images/footer_facebook_hl.png');
    preloadImage(g_templateUrl + '/images/footer_flickr_hl.png');
    preloadImage(g_templateUrl + '/images/footer_vimeo_hl.png');
    preloadImage(g_templateUrl + '/images/footer_rss_hl.png');

});

/**
 * Image Gallery
 *
 * Slideshow image gallery generated from existing markup
 */
(function($) {

    $.fn.ikitOneImageGallery = function(options) {

        var settings = $.extend({

        }, options);

        // ---------------------------------------
        // Variables
        // ---------------------------------------

        var self = $(this);

        // ---------------------------------------
        // Functions
        // ---------------------------------------

        function onStart(slider) {

            // Init controls

            if(slider != null) {

                if(slider.slides.length == 0) {
                    self.controlsEl.hide();
                    self.titleEl.hide();
                }

                // Activate the urls if defined
                for(var i=0;i<slider.slides.length;i++) {

                    var slide = $(slider.slides[i]);

                    if(slide.attr('link_url') != '') {
                        slide.find('img').css('cursor', 'pointer');
                        slide.click(function() {

                            var linkUrl = $(this).attr('link_url');
                            var linkTarget = $(this).attr('link_target');

                            if(linkTarget == '_blank') {
                                window.open(linkUrl);
                            }
                            else {
                                window.location = linkUrl;
                            }
                        });
                    }

                    if(slide.attr('title')) {
                        self.titleEnabled = true;
                    }

                }

                // Hide title element if no titles
                if(self.titleEnabled == false) {
                    self.titleEl.addClass('inactive');
                }

                // Add appropriate next and previous button events
                self.nextButtonEl.click(function() {
                    var target = slider.getTarget("next");
                    slider.flexAnimate(target);
                });

                self.prevButtonEl.click(function() {
                    var target = slider.getTarget("prev");
                    slider.flexAnimate(target);
                });

                // Add pause play button events
                self.pausePlayButtonEl.click(function() {

                  var pauseButtonEl = $('.box-section-image-gallery-controls-pause-button');
                  var playButtonEl = $('.box-section-image-gallery-controls-play-button');

                  if(playButtonEl.css('display') == 'none') {
                      playButtonEl.show();
                      pauseButtonEl.hide();
                      slider.pause();
                  }
                  else {
                      playButtonEl.hide();
                      pauseButtonEl.show();
                      slider.resume();
                  }
                });

                decorateHighlightableImage(self.nextButtonEl.find('img'));
                decorateHighlightableImage(self.prevButtonEl.find('img'));

                // Trigger the on after event which does some initialization
                onAfter(slider);



            }
            else {

                if(self.imageGalleryType != G_IMAGE_GALLERY_TYPE_FEATURED) {
                    self.controlsEl.hide();
                }
                else {
                    self.nextButtonEl.css('visibility', 'hidden');
                    self.prevButtonEl.css('visibility', 'hidden');
                    self.pausePlayButtonEl.css('visibility', 'hidden');
                }

                var title = self.find('li').attr('title');
                var linkUrl = self.find('li').attr('link_url');
                var linkTarget = self.find('li').attr('link_target');

                // Hide the title if no title
                if(!title) {
                    self.titleEl.addClass('inactive');
                }

                // If has link, add click action to image
                if(linkUrl != '') {
                    self.css('cursor', 'pointer');
                    self.click(function() {
                        if(linkTarget == '_blank') {
                            window.open(linkUrl);
                        }
                        else {
                            window.location = linkUrl;
                        }
                    });
                }

                updateTitle(title, linkUrl, linkTarget);

            }

        }

        function onAfter(slider) {

            var slide = $(slider.slides[slider.currentSlide]);
            var title = slide.attr('title');
            var linkUrl = slide.attr('link_url');
            var linkTarget = slide.attr('link_target');

            updateTitle(title, linkUrl, linkTarget);

        }

        function updateTitle(title, linkUrl, linkTarget) {
            self.titleEl.html(title);

            // Activate link if exists
            if(linkUrl != '') {
                self.titleEl.addClass('link');
                self.titleEl.unbind('click');
                self.titleEl.click(function() {
                    if(linkTarget == '_blank') {
                        window.open(linkUrl);
                    }
                    else {
                        window.location = linkUrl;
                    }
                });
            }
            else {
                self.titleEl.unbind('click');
                self.titleEl.css('cursor', 'default');
            }

        }

        // ---------------------------------------
        // Main
        // ---------------------------------------

        // Reference the controls and caption elements
        self.controlsEl = $('.box-section-image-gallery-controls');
        self.titleEl = $('.box-section-image-gallery-title');
        self.nextButtonEl = $('.box-section-image-gallery-controls-next-button');
        self.prevButtonEl = $('.box-section-image-gallery-controls-prev-button');
        self.pausePlayButtonEl = $('.box-section-image-gallery-controls-pause-play-button');
        self.imageGalleryType = self.attr('image_gallery_type');
        self.titleEnabled = false;

        // Create the slider
        self.flexslider({
            animation: "slide",
            start:onStart,
            after:onAfter
        });

        // Need atleast 1 image for a carousel!
        if(self.find('li').length == 1) {
            onStart(null);
        }


    };
})(jQuery);

/**
 * Navigation Menu
 *
 * Auto-generates the navigation menus given the layout type and the
 * list element that contains the actual menu structure
 */
(function($) {

    $.fn.ikitOneNavMenu = function(options) {

        var settings = $.extend({
            'breakpoint' : null
        }, options);

        // ---------------------------------------
        // Variables
        // ---------------------------------------

        var self = $(this);

        // ---------------------------------------
        // Functions
        // ---------------------------------------

        // ---------------------------------------
        // Main
        // ---------------------------------------

        //
        // Activate menus
        //

        // Breakpoint 300
        if(settings.breakpoint == G_BREAKPOINT_300) {

            var menuButton = self.find('.layout-nav-menu-button');
            var menuSubmenu = self.find('.layout-nav-menu-submenu');

            menuButton.hover(
                function() {
                    menuSubmenu.width($(window).width()); // Expand to 100% of the window

                    // Set the vertical position just below the nav menu
                    var menuSubmenuTop = menuButton.parent().parent().height() - safeParseInt(menuButton.css('paddingTop'));
                    menuSubmenu.css('top',  menuSubmenuTop);

                    menuSubmenu.show();
                    menuButton.addClass('active');
                },
                function() {
                    menuSubmenu.hide();
                    menuButton.removeClass('active');
                }
            );

        }

        // Breakpoint 520
        if(settings.breakpoint == G_BREAKPOINT_520) {

            var menuButton = self.find('.layout-nav-menu-button');
            var menuSubmenu = self.find('.layout-nav-menu-submenu');

            menuButton.hover(
                function() {
                    menuSubmenu.width($(window).width()); // Expand to 100% of the window

                    // Set the vertical position just below the nav menu
                    var menuSubmenuTop = menuButton.parent().parent().height() - safeParseInt(menuButton.css('paddingTop'));
                    menuSubmenu.css('top',  menuSubmenuTop);

                    menuSubmenu.show();
                    menuButton.addClass('active');
                },
                function() {
                    menuSubmenu.hide();
                    menuButton.removeClass('active');
                }
            );
        }

        // Breakpoint 1020
        if(settings.breakpoint == G_BREAKPOINT_1020) {

            var menuButtons = self.find('.layout-nav-menu-button');

            for(var i=0;i<menuButtons.length;i++) {

                var menuButton = $(menuButtons[i]);

                // Make the menu button area itself clickable, not just the link
                menuButton.click(function(evt) {

                    var barMetricEl = $(this).parent().parent();
                    var y = evt.pageY - barMetricEl.offset().top;
                    if(y < barMetricEl.height()) {
                        var url = $(this).find('a').attr('href');
                        location.href = url;
                    }

                });

                menuButton.hover(
                    function() {

                        var menuButton = $(this);
                        var menuSubmenu = $(this).find('.layout-nav-menu-submenu');

                        // We need to use find to get the actual version
                        // using menuButton straight off won't work because it
                        // is scoped to the last run of the loop

                        menuSubmenu.css('left', menuButton.position().left);

                        // Set the vertical position just below the nav menu
                        var menuSubmenuTop = menuButton.parent().parent().parent().height() - safeParseInt(menuButton.css('paddingTop'));
                        menuSubmenu.css('top',  menuSubmenuTop);

                        menuSubmenu.show();
                        menuButton.addClass('active');

                    },
                    function() {

                        var menuButton = $(this);
                        var menuSubmenu = $(this).find('.layout-nav-menu-submenu');

                        menuSubmenu.hide();

                        menuButton.removeClass('active');
                    }
                    );

            }

        }



        //
        // Active search
        //


        // Breakpoint 300, 520, 1020
        if(settings.breakpoint == G_BREAKPOINT_300 || settings.breakpoint == G_BREAKPOINT_520 || settings.breakpoint == G_BREAKPOINT_1020) {

            var searchButton = self.find('.layout-nav-menu-search-button');
            var searchForm = self.find('.layout-nav-menu-search-form');
            var searchFormSpacer = self.find('.layout-nav-menu-search-form-spacer');
            var searchFormContent = self.find('.layout-nav-menu-search-form-content');
            var searchInput = searchForm.find('#s');
            var searchIcon = self.find('.layout-nav-menu-search-icon');

            searchIcon.click(function() {
                searchForm.find('form').submit();
            });

            searchButton.hover(
                function() {

                    var barMetricEl = searchButton.parent().parent();

                    // Create a spacer so that the form appears
                    // right below the nav bar, it's important not just
                    // set the position, because we need the hovering to
                    // work as well, so we use this padded element

                    var spacerTopPadding = 5; // Add some padding so that there is no chance for a gap between the button and the drop down menu that would cause the drop down to hide.
                    var spacerHeight = barMetricEl.height() - (searchButton.position().top + searchButton.height()) + spacerTopPadding;

                    searchFormSpacer.height(spacerHeight);

                    // Set the height of the search form to be the same as
                    // the nav bar for visual consistency

                    searchFormContent.css('height', barMetricEl.height());

                    // Set the vertical position just below the nav menu
                    var searchFormTop = barMetricEl.height() - safeParseInt(searchButton.css('paddingTop')) - spacerHeight;
                    searchForm.css('top',  searchFormTop);

                    searchForm.show();
                    searchButton.addClass('active');

                    searchInput.focus();

                },
                function() {
                    searchForm.hide();
                    searchButton.removeClass('active');

                    // update all search forms with the same text
                    $('.s').val(searchInput.val());
                }
            );
        }

    };
})(jQuery);

/**
 * Some helper functions
 */

// parse an int, typically used when it returns px, will still return the int
function safeParseInt(value) {
    return parseInt(value, 10);
}

// preloads an image for things such as rollovers and such where the image isn't loaded in the page
function preloadImage(src) {
    var image = new Image();
    image.src = src;
}

// Make an image highlightable, that is, replaces the src with the _hl version on mouseover.
function decorateHighlightableImage(imageEl) {

    var imageSrc = $(imageEl).attr('src');

    var extension = imageSrc.substring(imageSrc.lastIndexOf('.'));
    var basename = imageSrc.substring(0, imageSrc.lastIndexOf('.'));

    var highlightedImageSrc = basename + '_hl' + extension;
    preloadImage(highlightedImageSrc);

    imageEl.hover(
        function() {
            $(this).attr('src', highlightedImageSrc);
        },
        function() {
            $(this).attr('src', imageSrc);
        }
    );

}

