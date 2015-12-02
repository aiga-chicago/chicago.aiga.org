/* ********************************************
 * Markup plugins
 *
 * These plugins are initialized using HTML
 * attributes on the elements themselves.
 *
 * ********************************************/

jQuery.ikit_two = function() {

};

jQuery.ikit_two.onStartDomReadyFunctions = []; // Do setup and initialization here
jQuery.ikit_two.onEndDomReadyFunctions = [];

jQuery.ikit_two.onWindowLoad = function() {

    jQuery.cat.plugin.fluidGrid.grid.onStartWindowLoad();

    jQuery.cat.plugin.breakpointBody.layout();
    jQuery.cat.plugin.anystretchImage.layout();

    jQuery.cat.plugin.fluidGrid.grid.onEndWindowLoad();

};

jQuery.ikit_two.onDomReady = function() {

    jQuery.ikit_two.pageLoader.onStartDomReady();

    for ( var i = 0; i < jQuery.ikit_two.onStartDomReadyFunctions.length; i++) {
        var onStartDomReadyFunction = jQuery.ikit_two.onStartDomReadyFunctions[i];
        onStartDomReadyFunction();
    }

    // Initialize any image galleries
    $('.image-gallery').ikitTwoImageGallery({});

    jQuery.cat.plugin.breakpointBody.layout();
    jQuery.ikit_two.widgets.onDomReady();
    jQuery.ikit_two.grid.onDomReady();
    jQuery.cat.plugin.clickRedirect.layout();
    jQuery.ikit_two.navMenu.onDomReady();
    jQuery.ikit_two.infinityFetcher.onDomReady();
    jQuery.cat.plugin.videoSwap.layout();
    jQuery.ikit_two.pageLayout4.onDomReady();
    jQuery.ikit_two.pageHeader3.onDomReady();
    jQuery.ikit_two.pageHeader4.onDomReady();
    jQuery.ikit_two.pageHeader5.onDomReady();
    jQuery.ikit_two.heroImage.onDomReady();
    jQuery.ikit_two.customSelect.onDomReady();
    jQuery.ikit_two.customSubmit.onDomReady();
    jQuery.ikit_two.paragraphHelper.onDomReady();

    $(this).inputPlaceholder();

    // Initialize hover states
    $('.rollover-image').cat().ui().rolloverImage('rollover_src', '_hl', 'rollover');

    for( var i = 0; i < jQuery.ikit_two.onEndDomReadyFunctions.length; i++) {
        var onEndDomReadyFunction = jQuery.ikit_two.onEndDomReadyFunctions[i];
        onEndDomReadyFunction();
    }

    jQuery.cat.plugin.anystretchImage.layout();

    // Initially hide all the isotope images, show them once they are loaded, and relayout the grid
    $('.cat-plugin-fluid-grid img').hide();
    $('.cat-plugin-fluid-grid img').each(function() {
      $(this).imagesLoaded(function(instance) {
        instance.fadeIn();
        jQuery.cat.plugin.fluidGrid.isotope.relayout(jQuery.cat.plugin.className('fluid-grid', true));
      });
    });

    // We run once more as layouts may have changed
    jQuery.ikit_two.grid.layout();

    jQuery.ikit_two.pageLoader.onEndDomReady();

};

jQuery.ikit_two.windowResizeWidth = 0;
jQuery.ikit_two.windowResizeHeight = 0;
jQuery.ikit_two.onWindowResize = function() {

    $('.cat-plugin-fluid-grid').removeClass('animated'); // Animations are always disabled when resizing the window

    if($(window).width() != jQuery.ikit_two.windowResizeWidth || $(window).height() != jQuery.ikit_two.windowResizeHeight) {

        jQuery.cat.plugin.breakpointBody.layout();
        jQuery.ikit_two.grid.layout();
        jQuery.cat.plugin.videoSwap.onWindowResize();
        jQuery.ikit_two.pageLayout4.onWindowResize();
        jQuery.ikit_two.pageHeader3.onWindowResize();
        jQuery.ikit_two.pageHeader4.onWindowResize();
        jQuery.ikit_two.pageHeader5.onWindowResize();
        jQuery.ikit_two.heroImage.onWindowResize();

        // We run once more as layouts may have changed
        jQuery.ikit_two.grid.layout();

    }


    jQuery.ikit_two.windowResizeWidth = $(window).width();
    jQuery.ikit_two.windowResizeHeight = $(window).height();

};

jQuery.ikit_two.onWindowScroll = function() {

    jQuery.ikit_two.navMenu.onWindowScroll();
    jQuery.ikit_two.infinityFetcher.onWindowScroll();

};


/**
 * Util
 */
jQuery.ikit_two.util = function() {

};

jQuery.ikit_two.util.lpad = function(str, padStr, length) {
    while (str.length < length)
        str = padStr + str;
    return str;
};

jQuery.ikit_two.util.hasAttr = function(el, name) {
    return $(el).cat().dom().hasAttr(name);
};

/**
 * Fonts
 */

jQuery.ikit_two.fonts = function() {

};

jQuery.ikit_two.fonts.onLoaded = function() {

    // The font may have loaded after all the dom ready and loaded calls have been made
    // so we may need to do another grid layout
    $(document).ready(function() {
       jQuery.ikit_two.grid.layout();
    });

};

/**
 * Paragraph helper
 */
jQuery.ikit_two.paragraphHelper = function() {

};

jQuery.ikit_two.paragraphHelper.layout = function() {

    // Add class to last paragraph for certain elements
    $('.wp-editor').each(function() {
        $(this).find('p').last().addClass('p-last');
    });

};

jQuery.ikit_two.paragraphHelper.onDomReady = function() {
    jQuery.ikit_two.paragraphHelper.layout();
};

/**
 * Custom select
 */
jQuery.ikit_two.customSelect = function() {

};

jQuery.ikit_two.customSelect.onDomReady = function() {

    jQuery.ikit_two.customSelect.layout();
};

jQuery.ikit_two.customSelect.layout = function() {

    $('.custom-select-input, .gform_wrapper select').selectbox({

        onOpen : function(inst) {
            var sbSelector = $("#sbSelector_" + inst.uid);
            var sbHolder = sbSelector.closest('.sbHolder');
            var sb = sbHolder.siblings('select');

            sbSelector.addClass('open');
            sbHolder.addClass('open');

        },

        onClose : function(inst) {
            var sbSelector = $("#sbSelector_" + inst.uid);
            var sbHolder = sbSelector.closest('.sbHolder');
            var sb = sbHolder.siblings('select');

            sbSelector.removeClass('open');
            sbHolder.removeClass('open');

        },

        onChange : function(value, inst, sbSelector) {

            sbSelector.removeClass('unselected');
            if (value == "") {
                sbSelector.addClass('unselected');
            }

            // XXX for whatever reason, the value isn't ready on the change
            // callback in Android, so we set this attribute instead to key off of
            $(inst.input).attr('selected_value', value);

        },
        onLoad : function(inst, input, sbSelector) {
            if ($(input).val() == "") {
                sbSelector.addClass('unselected');
            }

        }
    });

};

/**
 * Grid
 */
jQuery.ikit_two.grid = function() {

};

jQuery.ikit_two.grid.layout = function() {

    jQuery.cat.plugin.fluidGrid.grid.layout();

    // Append a "bottom" class to the widgets on the bottom row for any fitRows grids
    $('.grid[cat_plugin_fluid_grid_layout_mode="fitRows"]').each(function() {
       var gridEl = $(this);
       var gridItemEls = gridEl.find('.grid-item');

       gridItemEls.height('');
       gridItemEls.removeClass('first-row');
       gridItemEls.removeClass('last-row');
       gridItemEls.removeClass('first-col');
       gridItemEls.removeClass('last-col');

       var gridItemsByTop = [];
       var maxHeightByTop = [];
       var maxTop = -9999;
       var minTop = 9999;

       for(var i=0;i<gridItemEls.length;i++) {
           var gridItemEl = $(gridItemEls[i]);
           var top = gridItemEl.position().top;
           if(top in gridItemsByTop) {

           }
           else {
               maxHeightByTop[top] = 0;
               gridItemsByTop[top] = [];
           }

           gridItemsByTop[top].push(gridItemEl);
           maxHeightByTop[top] = Math.max(maxHeightByTop[top], gridItemEl.height());

           maxTop = Math.max(maxTop, top);
           minTop = Math.min(minTop, top);

       }

       // Set the height to the max height of the row
       for(var i=0;i<gridItemEls.length;i++) {
           var gridItemEl = $(gridItemEls[i]);
           var top = gridItemEl.position().top;
           gridItemEl.height(maxHeightByTop[top]);
       }

       // Add a class to the bottom row items
       if(maxTop in gridItemsByTop) {
           for(var i=0;i<gridItemsByTop[maxTop].length;i++) {
               $(gridItemsByTop[maxTop][i]).addClass('last-row');
           }
       }

       // Add a class to the top row items
       if(minTop in gridItemsByTop) {
           for(var i=0;i<gridItemsByTop[minTop].length;i++) {
               $(gridItemsByTop[minTop][i]).addClass('first-row');
           }
       }

       // Add a class to the last and first cols
       for(var top in gridItemsByTop) {
           var gridItems = gridItemsByTop[top];
           for(var i=0;i<gridItems.length;i++) {
               var gridItem = $(gridItems[i]);
               if(i == 0) {
                   gridItem.addClass('first-col');
               }
               if(i == gridItems.length-1) {
                   gridItem.addClass('last-col');
               }
           }
       }

    });

    jQuery.cat.plugin.fluidGrid.grid.layout();


};

jQuery.ikit_two.grid.onDomReady = function() {

    jQuery.cat.plugin.fluidGrid.grid.overrideItemWidthFunction = jQuery.ikit_two.grid.overrideItemWidthFunction;
    jQuery.ikit_two.grid.layout();

};

jQuery.ikit_two.grid.getItemWidth = function() {

    var firstFooterGridItemEl = $('.footer .grid-item').first();
    return firstFooterGridItemEl.width();

};

jQuery.ikit_two.grid.overrideItemWidthFunction = function(gridItemEl, columnWidth, numCols) {

    // Mobile everything collapses to single column regardless of grid item size
    if($('.breakpoint-body').hasClass('breakpoint-body-size-s')) {

        gridItemEl.width(columnWidth);
        return true;
    }

    // Anything that goes beyond the window width should just be set at the window width
    if(jQuery.ikit_two.util.hasAttr(gridItemEl, 'cat_plugin_fluid_grid_item_size')) {
        var gridItemWidth = columnWidth * parseInt(gridItemEl.attr('cat_plugin_fluid_grid_item_size'));
        if(gridItemWidth > columnWidth * numCols) {
            gridItemEl.width(columnWidth * numCols);
            return true;
        }
    }

    return false;

};

// Set the grid empty, useful for no results found etc.
jQuery.ikit_two.grid.empty = function(selector, html) {

    jQuery.cat.plugin.fluidGrid.isotope.destroy(selector);
    $(selector).empty();
    $(selector).append('<div class="cat-plugin-fluid-grid-item grid-item grid-item-empty"><div class="grid-item-inner">' + html + '</div></div>');
    jQuery.cat.plugin.fluidGrid.isotope.create(selector);

    // Isotope sizes based on the previous grid elements, so we need to force a layout here, as our dummy
    // grid item has no width yet
    jQuery.ikit_two.grid.layout();

};

// Prepare the ajax response for insertion into the grid
jQuery.ikit_two.grid.prepareAjaxResponseInsert = function(ajaxResponse, gridEl) {

    var gridItemWidth = gridEl.find('.grid-item:first').width();
    for(var i=0;i<ajaxResponse.length;i++) {
        var responseItem = $(ajaxResponse[i]);
        if(responseItem.hasClass('grid-item')) {
            responseItem.width(gridItemWidth);
            responseItem.addClass('appending');
        }
    }

};

/**
 * Widgets
 */
jQuery.ikit_two.widgets = function() {

};

jQuery.ikit_two.widgets.onDomReady = function() {

    $('.widget').addClass('cat-plugin-fluid-grid-item grid-item');

};

/**
 * Hero image
 */
jQuery.ikit_two.heroImage = function() {

};

jQuery.ikit_two.heroImage.onDomReady = function() {
    jQuery.ikit_two.heroImage.onWindowResize();
};

jQuery.ikit_two.heroImage.maxHeight = 900;
jQuery.ikit_two.heroImage.onWindowResize = function() {

    // Set the max height for the hero image
    $('.hero-image').each(function() {
        var windowWidth = $(window).width();
        var height = windowWidth * 0.6; // Hero images are always 1:0.6 ratio width to height
        if(height > jQuery.ikit_two.heroImage.maxHeight) {
            var paddingTopPerc = Math.ceil((jQuery.ikit_two.heroImage.maxHeight / windowWidth) * 100);
            $(this).css('paddingTop', paddingTopPerc + '%');
        }
        else {
            $(this).css('paddingTop', '');
        }
    });

};

/**
 * Page loader
 */
jQuery.ikit_two.pageLoader = function() {

};

jQuery.ikit_two.pageLoader.onStartDomReady = function() {

    // Attach loading indicator to page
    $('.page-loader-dialog').cat().ui().popupDialog(true, null,
      function(dialogEl, modalEl) {
        dialogEl.show();
        modalEl.addClass('page-loader-dialog-modal');
      }
    );

};

jQuery.ikit_two.pageLoader.onEndDomReady = function() {

    $('.page-loader-dialog, .page-loader-dialog-modal').fadeOut(function() {
        $('body').addClass('loaded');
        $('.breakpoint-body, .header').css('visibility', 'visible');
        $('.cycle-slideshow').css('opacity', 1);
    });

};

/**
 * Page header 3
 */
jQuery.ikit_two.pageHeader3 = function() {
};

jQuery.ikit_two.pageHeader3.onDomReady = function() {
    jQuery.ikit_two.pageHeader3.onWindowResize();
};

jQuery.ikit_two.pageHeader3.onWindowResize = function() {

    $('.page-header-3').each(function() {
        $(this).find('.page-header-3-section-title').width(jQuery.ikit_two.grid.getItemWidth());
    });
};

/**
 * Page header 4
 */
jQuery.ikit_two.pageHeader4 = function() {
};

jQuery.ikit_two.pageHeader4.onDomReady = function() {
    jQuery.ikit_two.pageHeader4.onWindowResize();
};

jQuery.ikit_two.pageHeader4.onWindowResize = function() {

    $('.page-header-4').each(function() {

        // Center the title
        $(this).find('.page-header-4-title').css('paddingLeft', jQuery.ikit_two.grid.getItemWidth());
        $(this).find('.page-header-4-title').css('paddingRight', jQuery.ikit_two.grid.getItemWidth());

    });


};


/**
 * Page header 5
 */
jQuery.ikit_two.pageHeader5 = function() {
};

jQuery.ikit_two.pageHeader5.onDomReady = function() {
    jQuery.ikit_two.pageHeader5.onWindowResize();
};

jQuery.ikit_two.pageHeader5.onWindowResize = function() {

    $('.page-header-5').each(function() {
        $(this).find('.page-header-5-title').css('paddingLeft', jQuery.ikit_two.grid.getItemWidth());
        $(this).find('.page-header-5-title').css('paddingRight', jQuery.ikit_two.grid.getItemWidth());
    });

};

/**
 * Page layout 4
 */
jQuery.ikit_two.pageLayout4 = function() {
};

jQuery.ikit_two.pageLayout4.onDomReady = function() {

    jQuery.ikit_two.pageLayout4.onWindowResize();
};

jQuery.ikit_two.pageLayout4.onWindowResize = function() {

    var gridItemWidth = jQuery.ikit_two.grid.getItemWidth();
    $('.page-layout-4').each(function() {
        $(this).find('.page-layout-4-tools, .page-layout-4-sidebar').width(gridItemWidth);
    });

    $('.page-layout-4-sub-content').each(function() {
        $(this).find('.page-layout-4-sub-content-title-col').width(gridItemWidth);
    });

    // If no tools, no need to display the footer
    $('.page-layout-4-tools').each(function() {

        var toolEls = $(this).find('.grid-item');
        var hasActiveTool = false;
        for(var i=0;i<toolEls.length;i++) {
            if($(toolEls[i]).css('visibility') != 'hidden') {
                hasActiveTool = true;
                break;
            }
        }
        if(hasActiveTool == false) {
            $(this).find('.page-layout-4-tools-footer').hide();
        }

    });

};


/**
 * Infinity fetcher
 */
jQuery.ikit_two.infinityFetcher = function() {

};

jQuery.ikit_two.infinityFetcher.beforeFilterFunctions = [];
jQuery.ikit_two.infinityFetcher.afterFetchFunctions = [];
jQuery.ikit_two.infinityFetcher.afterFetchFinishedFunctions = [];
jQuery.ikit_two.infinityFetcher.afterAppendFunctions = [];

jQuery.ikit_two.infinityFetcher.page = -1;
jQuery.ikit_two.infinityFetcher.maxPagesReached = false;
jQuery.ikit_two.infinityFetcher.contentContainer = null;
jQuery.ikit_two.infinityFetcher.fetchingIndicator = null;
jQuery.ikit_two.infinityFetcher.fetchButton = null;
jQuery.ikit_two.infinityFetcher.fetching = false;
jQuery.ikit_two.infinityFetcher.fetchingUrl = null;
jQuery.ikit_two.infinityFetcher.fetchingUrlData = {};

jQuery.ikit_two.infinityFetcher.infinityRunnerBottomOffsetThreshold = 200;

jQuery.ikit_two.infinityFetcher.infinityRunner = function() {

    if(jQuery.ikit_two.infinityFetcher.fetchButton == null) {
        if (jQuery.ikit_two.infinityFetcher.fetchingUrl != null && jQuery.ikit_two.infinityFetcher.maxPagesReached == false && jQuery.ikit_two.infinityFetcher.fetching == false && jQuery.ikit_two.infinityFetcher.infinityRunnerBottomOffsetThreshold >= ($(document).height() - ($(window).scrollTop() + $(window).height()))) {
            jQuery.ikit_two.infinityFetcher.fetch();
        }
    }

};

jQuery.ikit_two.infinityFetcher.filter = function(fetchingDataKey, fetchingDataValue) {

    if(jQuery.cat.string.isBlank(fetchingDataValue) == false) {

        // Reset to the first page and set different filter criteria
        jQuery.ikit_two.infinityFetcher.page = 1;
        jQuery.ikit_two.infinityFetcher.maxPagesReached = false;

        jQuery.ikit_two.infinityFetcher.fetchingUrlData[fetchingDataKey] = fetchingDataValue;
        jQuery.ikit_two.infinityFetcher.fetch();

    }
    else {

        jQuery.ikit_two.infinityFetcher.page = 1;
        jQuery.ikit_two.infinityFetcher.maxPagesReached = false;

        jQuery.ikit_two.infinityFetcher.fetchingUrlData[fetchingDataKey] = null;
        jQuery.ikit_two.infinityFetcher.fetch();
    }


};

jQuery.ikit_two.infinityFetcher.fetch = function() {

    if(jQuery.ikit_two.infinityFetcher.fetching != true) {

        jQuery.ikit_two.infinityFetcher.fetching = true;


        if(jQuery.ikit_two.infinityFetcher.fetchingIndicator != null) {
            jQuery.ikit_two.infinityFetcher.fetchingIndicator.show();
        }

        if(jQuery.ikit_two.infinityFetcher.fetchButton != null) {
            jQuery.ikit_two.infinityFetcher.fetchButton.addClass('loading');
        }

        jQuery.ikit_two.infinityFetcher.fetchingUrlData['page'] = jQuery.ikit_two.infinityFetcher.page;

        $.ajax({
            type : "POST",
            url : jQuery.ikit_two.infinityFetcher.fetchingUrl,
            data: jQuery.ikit_two.infinityFetcher.fetchingUrlData,
            success : function(response) {

                if (jQuery.cat.string.isBlank(response) == false) {

                    response = $(response);
                    var data = $(response[response.length-1]);

                    var page = parseInt(data.attr('page'));
                    var numPages = parseInt(data.attr('num_pages'));

                    if(numPages <= page) {

                        jQuery.ikit_two.infinityFetcher.maxPagesReached = true;

                        if(jQuery.ikit_two.infinityFetcher.fetchButton != null) {
                            if(page != 1) {
                                jQuery.ikit_two.infinityFetcher.fetchButton.fadeOut();
                            }
                            else {
                                jQuery.ikit_two.infinityFetcher.fetchButton.hide();
                            }
                        }
                    }

                    for(var i=0; i<jQuery.ikit_two.infinityFetcher.afterFetchFunctions.length; i++) {
                        var afterFetchFunction = jQuery.ikit_two.infinityFetcher.afterFetchFunctions[i];
                        afterFetchFunction(response, page, numPages);
                    }

                    if(jQuery.ikit_two.infinityFetcher.contentContainer != null) {
                        responseEl = $(response);
                        jQuery.ikit_two.infinityFetcher.contentContainer.append(responseEl);

                        for(var i=0; i<jQuery.ikit_two.infinityFetcher.afterAppendFunctions.length; i++) {
                            var afterAppendFunction = jQuery.ikit_two.infinityFetcher.afterAppendFunctions[i];
                            afterAppendFunction(responseEl);
                        }
                    }

                    jQuery.ikit_two.ajaxHelper.reload(); // Do any reloads neccessary on ajax appends

                    jQuery.ikit_two.infinityFetcher.page++;

                }

                jQuery.ikit_two.infinityFetcher.fetching = false;

                // If after fetch function are defined, allow the after fetch to define
                // when the loading has finished
                if(jQuery.ikit_two.infinityFetcher.afterFetchFunctions.length <= 0) {
                    if(jQuery.ikit_two.infinityFetcher.fetchingIndicator != null) {
                        jQuery.ikit_two.infinityFetcher.fetchingIndicator.hide();
                    }

                    if(jQuery.ikit_two.infinityFetcher.fetchButton != null) {
                        jQuery.ikit_two.infinityFetcher.fetchButton.removeClass('loading');
                    }
                }

                for(var i=0; i<jQuery.ikit_two.infinityFetcher.afterFetchFinishedFunctions.length; i++) {
                    var afterFetchFinishedFunction = jQuery.ikit_two.infinityFetcher.afterFetchFinishedFunctions[i];
                    afterFetchFinishedFunction(response, page, numPages);
                }

            }

        });

    }

};

jQuery.ikit_two.infinityFetcher.onDomReady = function() {

    if(jQuery.ikit_two.infinityFetcher.fetchButton != null) {
        jQuery.ikit_two.infinityFetcher.fetchButton.click(function() {
            jQuery.ikit_two.infinityFetcher.fetch();
        });
    }

    jQuery.ikit_two.infinityFetcher.page = 2;
};

jQuery.ikit_two.infinityFetcher.onWindowScroll = function() {
    jQuery.ikit_two.infinityFetcher.infinityRunner();
};

// Provided generic implementation of after fetch for grid layouts, to handle paging and filtering
jQuery.ikit_two.infinityFetcher.afterFetchGrid = function(selector, response, page, numPages, hasImages) {

    if(numPages > 0) {

        jQuery.ikit_two.grid.prepareAjaxResponseInsert(response, $(selector));

        if(page == 1) {

            if(page < numPages) {
                jQuery.ikit_two.infinityFetcher.fetchButton.show();
            }

            jQuery.cat.plugin.fluidGrid.isotope.destroy(selector);
            $(selector).empty();
            $(selector).append(response);

            if(hasImages) {
                response.imagesLoaded(function() {
                    jQuery.cat.plugin.fluidGrid.isotope.create(selector);
                    if(jQuery.ikit_two.infinityFetcher.fetchButton != null) {
                        jQuery.ikit_two.infinityFetcher.fetchButton.removeClass('loading');
                    }
                    jQuery.ikit_two.grid.layout();
                });
            }
            else {
                jQuery.cat.plugin.fluidGrid.isotope.create(selector);
                if(jQuery.ikit_two.infinityFetcher.fetchButton != null) {
                    jQuery.ikit_two.infinityFetcher.fetchButton.removeClass('loading');
                }
                jQuery.ikit_two.grid.layout();
            }


        }
        else {

            // Animate the adding of new elements
            $(selector).addClass('animated');
            $(selector).append(response);

            if(hasImages) {

                $(selector).find('.grid-item.appending').hide();

                response.imagesLoaded(function() {
                    $(selector).isotope('appended', response);
                    $(selector).find('.grid-item.appending').show().removeClass('appending');
                    jQuery.ikit_two.infinityFetcher.fetchButton.removeClass('loading');
                    jQuery.ikit_two.grid.layout();
                });
            }
            else {
                $(selector).isotope('appended', response);
                if(jQuery.ikit_two.infinityFetcher.fetchButton != null) {
                    jQuery.ikit_two.infinityFetcher.fetchButton.removeClass('loading');
                }
                jQuery.ikit_two.grid.layout();
            }

        }

    }
    else {

        jQuery.ikit_two.grid.empty(selector, 'No results found.');

    }

};

/**
 * Custom submit
 */
jQuery.ikit_two.customSubmit = function() {

};

jQuery.ikit_two.customSubmit.onDomReady = function() {
  jQuery.ikit_two.customSubmit.layout();
};

jQuery.ikit_two.customSubmit.layout = function() {

  // Styles submit
  $('.custom-submit').unbind('click');
  $('.custom-submit').click(function() {
    var submitEl = $(this);

    var formEl = null;
    if(jQuery.ikit_two.util.hasAttr(submitEl, 'custom_submit_form_dom_id')) {
      formEl = $('#' + submitEl.attr('custom_submit_form_dom_id'));
    }
    else {
      formEl = submitEl.closest('form');
    }

    formEl.submit();
  });

};

/**
 * Ajax helper
 */

jQuery.ikit_two.ajaxHelper = function() {

};

jQuery.ikit_two.ajaxHelper.reload = function() {
    jQuery.ikit_two.paragraphHelper.layout();
    jQuery.cat.plugin.clickRedirect.layout();
};


/**
 * Nav menu
 */
jQuery.ikit_two.navMenu = function() {

};

jQuery.ikit_two.navMenu.animationDuration = 300;

jQuery.ikit_two.navMenu.onWindowScroll = function() {

};

jQuery.ikit_two.navMenu.onDomReady = function() {

    $('.nav-menu').click(function(event) {

        // Close the nav menu if click outside
        if($(event.target).hasClass('nav-menu')) {
            $('.header-nav-menu-button').trigger('click');
        }

    });

    $('.header-nav-menu-button').click(function() {

        var headerNavMenu = $('.nav-menu');
        var headerNavMenuInner = $('.nav-menu-inner');

        if(headerNavMenu.css('display') == 'none') {

            // Shift the content to not shift as the scroll bar is now hidden
            $('body, .header').css('paddingRight', jQuery.cat.dom.windowScrollBarWidth());
            $('body').css('overflow', 'hidden');

            // We must show the nav menu first, otherwise the scrollbar will appear after the animation, which will cause a jitter
            headerNavMenu.show();

            // The nav menu is a grid, so we have to do a layout, then we animate just the inner element in for a smooth slide down
            jQuery.ikit_two.grid.layout();
            headerNavMenuInner.hide();
            headerNavMenuInner.slideDown(jQuery.ikit_two.navMenu.animationDuration);

            $('.nav-menu-background').css('opacity', 0.8);
            $('.nav-menu-background').fadeIn();
        }
        else {

            // Shift back to normal
            $('body').css('overflow', '');
            $('body, .header').css('paddingRight', '');

            headerNavMenu.slideUp(jQuery.ikit_two.navMenu.animationDuration);

            $('.nav-menu-background').fadeOut();
        }

    });

};



/* ********************************************
 * Javascript plugins
 *
 * These plugins are initialized in Javascript
 * using the typical option arguments.
 *
 * ********************************************/

/**
 * Image gallery
 *
 * Based off the original slideshow included in the iKit One Theme.
 */
(function($) {

    $.fn.ikitTwoImageGallery = function(options) {

        var settings = $.extend({

        }, options);

        // ---------------------------------------
        // Variables
        // ---------------------------------------

        var self = $(this);

        // ---------------------------------------
        // Konstants
        // ---------------------------------------
        self.konstants = Object();
        self.konstants.IMAGE_GALLERY_TYPE_FEATURED = "featured"

        // ---------------------------------------
        // Functions
        // ---------------------------------------

        function onStart(slider) {

            // Init controls

            if (slider != null) {

                if (slider.slides.length == 0) {
                    self.controlsEl.hide();
                    self.titleEl.hide();
                }

                // Activate the urls if defined
                for ( var i = 0; i < slider.slides.length; i++) {

                    var slide = $(slider.slides[i]);

                    // Fix for fade transition
                    if(i != 0) {
                        slide.hide();
                    }

                    if (slide.attr('link_url') != '') {
                        slide.addClass('link');
                        slide.find('img').css('cursor', 'pointer');
                        slide.click(function() {

                            var linkUrl = $(this).attr('link_url');
                            var linkTarget = $(this).attr('link_target');

                            if (linkTarget == '_blank') {
                                window.open(linkUrl);
                            } else {
                                window.location = linkUrl;
                            }
                        });
                    }

                    if (slide.attr('title')) {
                        self.titleEnabled = true;
                    }

                }

                // Hide title element if no titles
                if (self.titleEnabled == false) {
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

                    var pauseButtonEl = $('.image-gallery-controls-pause-button');
                    var playButtonEl = $('.image-gallery-controls-play-button');

                    if (playButtonEl.css('display') == 'none') {
                        playButtonEl.show();
                        pauseButtonEl.hide();
                        slider.pause();
                    } else {
                        playButtonEl.hide();
                        pauseButtonEl.show();
                        slider.resume();
                    }
                });

                // Trigger the on after event which does some initialization
                onAfter(slider);

            } else {

                if (self.imageGalleryType != self.konstants.IMAGE_GALLERY_TYPE_FEATURED) {
                    self.controlsEl.hide();
                } else {
                    self.nextButtonEl.css('visibility', 'hidden');
                    self.prevButtonEl.css('visibility', 'hidden');
                    self.pausePlayButtonEl.css('visibility', 'hidden');
                }

                var title = self.find('li').attr('title');
                var linkUrl = self.find('li').attr('link_url');
                var linkTarget = self.find('li').attr('link_target');

                // Hide the title if no title
                if (!title) {
                    self.titleEl.addClass('inactive');
                }

                // If has link, add click action to image
                if (linkUrl != '') {

                    self.css('cursor', 'pointer');
                    self.click(function() {
                        if (linkTarget == '_blank') {
                            window.open(linkUrl);
                        } else {
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
            if (linkUrl != '') {
                self.titleEl.addClass('link');
                self.titleEl.unbind('click');
                self.titleEl.click(function() {
                    if (linkTarget == '_blank') {
                        window.open(linkUrl);
                    } else {
                        window.location = linkUrl;
                    }
                });
                self.titleEl.css('cursor', 'pointer');
            } else {
                self.titleEl.unbind('click');
                self.titleEl.css('cursor', 'default');
            }

        }

        // ---------------------------------------
        // Main
        // ---------------------------------------

        // Reference the controls and caption elements
        self.controlsEl = $('.image-gallery-controls');
        self.titleEl = $('.image-gallery-title');
        self.nextButtonEl = $('.image-gallery-controls-next-button');
        self.prevButtonEl = $('.image-gallery-controls-prev-button');
        self.pausePlayButtonEl = $('.image-gallery-controls-pause-play-button');
        self.imageGalleryType = self.attr('image_gallery_type');
        self.imageGalleryAutoplay = self.attr('image_gallery_autoplay') ? (self.attr('image_gallery_autoplay') == "true" ? true : false) : false;
        self.titleEnabled = false;

        self.nextPrevButtonEls = $('.image-gallery-controls-button');
        self.nextPrevButtonEls.hover(
        function() {
            $(this).find('img').show();
        },
        function() {
            $(this).find('img').hide();
        });

        var animation = 'fade';
        if(g_browserSupportsFixedBackgroundImage) {
            animation = 'fade';
        }

        // Create the slider
        self.flexslider({
            animation : animation,
            start : onStart,
            after : onAfter,
            slideshow: self.imageGalleryAutoplay,
            slideshowSpeed: 5000
        });

        // Need atleast 1 image for a carousel!
        if (self.find('li').length == 1) {
            onStart(null);
        }

    };
})(jQuery);




/* ********************************************
 * Core events
 *
 * These are built in browser events.
 *
 * ********************************************/

$(window).resize(function() {
    jQuery.ikit_two.onWindowResize();
});

$(window).load(function() {
    jQuery.ikit_two.onWindowLoad();
});

$(document).ready(function() {
    jQuery.ikit_two.onDomReady();
});

$(window).scroll(function() {
    jQuery.ikit_two.onWindowScroll();
});
