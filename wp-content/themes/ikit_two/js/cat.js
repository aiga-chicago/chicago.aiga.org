(function($){

// Namespace
jQuery.cat = function() {

};

/*
 * Element extensions
 *
 */

jQuery.fn.cat = function() {

  /*
   * Animation
   */

  this.anim = function() {

    // Smoothly slide down fix for height issue
    //
    // Usage: myDomElement.cat().anim().smoothSlideDown('slow', function() {});
    this.smoothSlideDown = function() {

      var args = arguments[0] || {};

      $(this).each(function() {

        $(this).show();
        $(this).css('height', $(this).height());
        $(this).hide();

        $(this).slideDown(args);

      });

    };

    return this;

  };

  this.ui = function() {

    /*
     * Applies rollover fade to elements, takes arguments of fade duration in milliseconds
     * and the fade opacity as a decimal. The following attributes apply:
     *
     * rollover_fade_disable="true"
     *  disables the rollover functionality
     *
     * rollover_fade_opacity="0.3"
     *  custom opacity
     *
     * rollover_target_selector="#container"
     *  target something else to fade on hover over this element, rather than this element itself
     *
     * Usage:
     *
     * $('.rollover-fade').cat().ui().rolloverFade('rollover_fade', 200, 0.68);
     *
     * Warning! For IE to recognize opacity on elements, you must have some sort
     * of layout defined, the easiest way to do this is add a display:inline-block
     * or display:block to the element itself.
     *
     * http://stackoverflow.com/questions/1948176/opacity-css-not-working-in-ie8
     *
     */
    this.rolloverFade = function() {

      var attrPrefix = arguments[0];
      var fadeDuration = arguments[1];
      var fadeOpacity = arguments[2];

      $(this).each(function() {

        if($(this).cat().dom().hasAttr(attrPrefix + '_disable') == false) {

          var opacity = fadeOpacity;
          if($(this).cat().dom().hasAttr(attrPrefix + '_opacity')) {
            opacity = $(this).attr(attrPrefix + '_opacity');
          }

          var targetEl = $(this);
          if($(this).cat().dom().hasAttr(attrPrefix + '_target_selector')) {
            targetEl = $($(this).attr(attrPrefix + '_target_selector'));
          }

          $(this).hover(
              function() {
                targetEl.stop().animate({
                  opacity: opacity
                }, fadeDuration);
              },
              function() {
                targetEl.stop().animate({
                  opacity: 1
                }, fadeDuration, function() {
                  targetEl.css('filter', 'none'); // IE, otherwise text may appear distorted on rollout
                });
              }
          );
        }


      });

    };

    /*
     * Applies rollover images to elements, and preloads the rollover image, if given
     * a selected class, if the image has that specified class, it will always show the
     * rollover image. The following attributes apply:
     *
     * rollover_transition="fade"
     *  instead of a direct swap, fades into the rollover, requires a parent div container
     *
     * Usage: $('.rollover-image').cat().ui().rolloverImage('rollover_src', '_hl', 'rollover');
     *
     *
     */
    this.rolloverImage = function() {

      var rolloverSrcAttrName = arguments[0];
      var rolloverSuffix = arguments[1];
      var rolloverAttrPrefix = arguments[2];

      $(this).each(function() {

        var src = $(this).attr('src');

        var ext = src.substr(src.lastIndexOf('.'));
        var name = src.substr(0, src.lastIndexOf('.'));
        var rolloverSrc = $(this).attr(rolloverSrcAttrName);

        if($.cat.string.isEmpty(rolloverSrc)) {
          rolloverSrc = name + rolloverSuffix + ext;

          if(name.indexOf('@2x') != -1) {
            rolloverSrc = name.replace('@2x', '') + rolloverSuffix + '@2x' + ext;
          }
        }

        $.cat.dom.preloadImage(rolloverSrc);

        if($(this).data('src')) {
        }
        else {
          $(this).data('src', src);
          $(this).data('rolloversrc', rolloverSrc);
        }

        if($(this).cat().dom().hasAttr(rolloverAttrPrefix + '_transition')) {
          var transition = $(this).attr(rolloverAttrPrefix + '_transition');
        }

        if(transition == 'fade') {

          var containerEl = $(this).parent();
          containerEl.css('position', 'relative');

          var rolloverSrcImageEl = $('<img src="' + rolloverSrc + '"/>');
          $(this).after(rolloverSrcImageEl);
          rolloverSrcImageEl.css('position', 'absolute');
          rolloverSrcImageEl.css('top', 0);
          rolloverSrcImageEl.css('left', 0);
          rolloverSrcImageEl.hide();

          containerEl.hover(
            function() {
              rolloverSrcImageEl.stop(true, true).fadeIn();
            },
            function() {
              rolloverSrcImageEl.stop(true, true).fadeOut();
            });

        }
        else {

          $(this).hover(function() {
            $(this).attr('src', $(this).data('rolloversrc'));
          },
          function() {
            $(this).attr('src', $(this).data('src'));
          });

        }

      });

    };

    /*
     * Expands element to the size of the window
     *
     * Usage: $('.fullwindow').cat().ui().fullWindow(true, true, 0, 0, true, false);
     */
    this.fullWindow = function() {

      var expandWidth = arguments[0];
      var expandHeight = arguments[1];
      var widthOffset = arguments[2];
      var heightOffset = arguments[3];
      var positionAbsolute = arguments[4];
      var expandFull = arguments[5];
      if(jQuery.cat.dom.isUndefined(positionAbsolute)) {
        positionAbsolute = true;
      }

      $(this).each(function() {

        if(positionAbsolute) {
          $(this).css('position', 'absolute');
        }
        $(this).css('top', '0');
        $(this).css('left', '0');

        if(expandWidth) {
          $(this).width($(window).width() - widthOffset);
        }
        if(expandHeight) {

          var windowHeight = $(window).height();
          if(jQuery.cat.dom.isUndefined(window.innerHeight) == false) { // Fix for iOS Safari
            height = window.innerHeight;
          }

          $(this).height($(window).height() - heightOffset);
        }
        if(expandFull) {
          $(this).height('100%');
          $(this).width('100%');
        }

      });

      return this;

    };


    /*
     * Expands element to size of the container
     *
     * Usage: $('.fullcontainer').cat().ui().fullContainer($('.container'), true, true, 0, 0);
     */
    this.fullContainer = function() {

      var container = arguments[0];
      var expandWidth = arguments[1];
      var expandHeight = arguments[2];
      var widthOffset = arguments[3];
      var heightOffset = arguments[4];

      $(this).each(function() {

        $(this).css('position', 'absolute');
        $(this).css('top', '0');
        $(this).css('left', '0');

        if(expandWidth) {
          $(this).width($(container).width() - widthOffset);
        }
        if(expandHeight) {
          $(this).height($(container).height() - heightOffset);
        }

      });

      return this;

    };

    /*
     * Expands element to size of the document
     *
     * Usage: $('.fulldocument').cat().ui().fullDocument(true, true, 0, 0);
     */
    this.fullDocument = function() {

      var expandWidth = arguments[0];
      var expandHeight = arguments[1];
      var widthOffset = arguments[2];
      var heightOffset = arguments[3];

      $(this).each(function() {

        $(this).css('position', 'absolute');
        $(this).css('top', '0');
        $(this).css('left', '0');

        if(expandWidth) {
          $(this).width($(window).width() - widthOffset);
        }

        if(expandHeight) {

          $(this).css('top', -999999); // Reposition, so self is not included in document height
          $(this).height($(document).height() - heightOffset);
          $(this).css('top', 0);
        }

      });

      return this;

    };

    /*
     * Positions the popup dialog in the center of the page above all other elements
     *
     * Usage:
     *
     * $('.popup-dialog').cat().ui().popupDialog(true, null, function(dialogEl, modalEl) { el.css('backgroundColor', '#FF0000'); });
     * $('.popup-dialog, .popup-dialog-overlay').show();
     *
     * Caller responsible for showing and hiding popup and overlay, this allows more flexibility
     * on if the caller wants to fade in, slide in etc. The second argument is a window resize callback,
     * at this point you may add styles to the modal overlay etc.
     *
     */
    this.popupDialog = function() {

      var modal = arguments[0];
      var onWindowResizeCallback = arguments[1];
      var onLoadCallback = arguments[2];

      $(this).each(function() {

        var el = $(this);

        el.addClass('popup-dialog-loaded');
        el.css('position', 'fixed');
        el.css('top', '50%');
        el.css('left', '50%');
        el.css('zIndex', 9999);

        if(modal) {
          $('.popup-dialog-modal-overlay').remove();
          var modalOverlay = $('<div class="popup-dialog-modal-overlay"></div>');
          $('body').append(modalOverlay);
        }

        if(onLoadCallback) {
          var modalOverlay = $('.popup-dialog-modal-overlay');
          onLoadCallback(el, modalOverlay);
        }

        // Events

        $(el).bind('center', function() { // Center the dialog
          var width = el.width();
          var height = el.height();
          el.css('marginLeft', -(width/2));
          el.css('marginTop', -(height/2));
        });


        $(el).bind('relayout', function() { // Resize the dialog, including the modal dialog

          el.trigger('center');

          var modalOverlay = $('.popup-dialog-modal-overlay');
          if(modalOverlay.length > 0) {

            modalOverlay.css('position', 'fixed');

            modalOverlay.css('height', '100%');
            modalOverlay.css('width', '100%');

            modalOverlay.css('zIndex', 9998);
            modalOverlay.css('top', 0);
            modalOverlay.css('left', 0);

          }

          if(onWindowResizeCallback) {
            onWindowResizeCallback(el, modalOverlay);
          }

        });


        $(el).bind('close', function() { // Close the dialog including the modal
          el.hide();
          el.unbind('relayout');
          modalOverlay.remove();
        });


        el.trigger('relayout');

        $(window).resize(function() {
          el.trigger('relayout');
        });

      });

    };

    return this;

  };



  /*
   * DOM manipulation
   */

  this.dom = function() {

    // Fixed height method that accounts for issues with the hidden elements
    //
    // Usage: myDomElement.cat().dom().height();
    this.height = function() {
      var first = $(this).first();
      first.show();
      var height = first.height();
      first.hide();
      return height;
    };

    // Fixed width method that accounts for issues with the hidden elements
    //
    // Usage: myDomElement.cat().dom().width();
    this.width = function() {
      var first = $(this).first();
      first.show();
      var height = first.width();
      first.hide();
      return height;
    };

    // Same as hasClass but for attributes
    //
    // Usage: myDomElement.cat().dom().hasAttr(attr);
    this.hasAttr = function(attr) {

      if($.cat.string.isEmpty($(this).attr(attr)) == false) {
        return true;
      }
      return false;

    };

    /*
     * Center the element in the given container
     *
     * Usage: $('.section-content').cat().dom().center($(window), 'absolute', true, true);
     */
    this.center = function() {

      var container = arguments[0];
      var position = arguments[1];
      var centerHorizontally = arguments[2];
      var centerVertically = arguments[3];

      $(this).each(function() {

        $(this).css({
          position: position
        });

        if(centerHorizontally) {

          $(this).css({
            left : (container.width() - $(this).outerWidth()) / 2
          });

        }

        if(centerVertically) {

          $(this).css({
            top : (container.height() - $(this).outerHeight()) / 2
          });

        }

      });

    };

    /*
     * Determine if element has children or not
     */
    this.hasChildren = function() {

      if($(this).children().length > 0) {
        return true;
      }

      return false;

    };

    return this;

  };


  return this;

};

/*
 * JS
 */

jQuery.cat.js = function() {

};

/* Call using:
 * var myFunction = $.cat.js.functionFromString('myFunction')
 * myFunction(param);
 */
jQuery.cat.js.functionFromString = function(str) {
  return window[str];
};

jQuery.cat.js.objectHasKey = function(object, key) {
  if(key in object) {
    return true;
  }
  return false;
};

jQuery.cat.js.objectLength = function(obj) {

  if(obj != null && jQuery.cat.dom.isUndefined(obj) == false) {

    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;

  }
  else {
    return 0;
  }

};


/*
/*
 * String Format
 *
 */
jQuery.cat.stringFormat = function() {

};

jQuery.cat.stringFormat.numberWithCommas = function(number) {
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

/*
/*
 * String
 * Call using $.cat.string.isEmpty('test')
 */

jQuery.cat.string = function() {

};

jQuery.cat.string.isEmpty = function(str) {
  return (!str || 0 === str.length);
};

jQuery.cat.string.isBlank = function(str) {
  return (!str || /^\s*$/.test(str));
};

jQuery.cat.string.trim = function(str) {
  return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
};

jQuery.cat.string.ltrim = function(str) {
  return str.replace(/^\s+/,'');
};

jQuery.cat.string.rtrim = function(str) {
  return str.replace(/\s+$/,'');
};

jQuery.cat.string.fulltrim=function(str) {
  return str.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');
};

jQuery.cat.string.booleanValue = function(str) {
  return (str === 'true');
};

jQuery.cat.string.startsWith = function(str, needle) {
  return (str.indexOf(needle) == 0);
};

jQuery.cat.string.fromCamel = function(str, delimiter){
  return str.replace(/\W+/g, '-').replace(/([a-z\d])([A-Z])/g, '$1' + delimiter + '$2').toLowerCase();
};

jQuery.cat.string.pxToFloat = function(value) {
  return parseFloat(value, 10) || 0;
};

/*
 * Request
 */

jQuery.cat.request = function() {

};

jQuery.cat.request.redirect = function(url) {
  window.location.href = url;
}

// Given a dictionary, returns a query string of the form key1=value1&key2=value2
jQuery.cat.request.queryStrFromDict = function(dict) {

  var query = null;

  if(dict != null && jQuery.cat.js.objectLength(dict) > 0) {

    query = "";
    var count = 0;
    for(var key in dict) {

      if(count != 0) {
        query += '&';
      }

      query += key + '=' + dict[key];

      count++;
    }
  }

  return query;

};

// Given a URL query such as key1=value1&key2=value2, returns a dictionary
jQuery.cat.request.queryStrToDict = function(query, dict) {

  if(dict == null) {
    dict = {};
  }

  if(query != null) {
    var querySplit = query.split('&');
    for(var i=0;i<querySplit.length;i++) {
      var queryItem = querySplit[i].split('=')
      dict[queryItem[0]] = queryItem[1];
    }
  }

  return dict;

};

/*
 * Browser Extended
 */

jQuery.cat.browser = function() {

};

jQuery.cat.browser.ie = function() {
  if($.browser.msie) {
    return true;
  }
  return false;
};

// Check if the browser is IE and a version less than
jQuery.cat.browser.versionGt = function(version) {

  var browserVersion = parseInt(jQuery.browser.version, 10);

  if(browserVersion > version) {
    return true;
  }
  else {
    return false;
  }

};

//Check if the browser is IE and a version less than
jQuery.cat.browser.versionLt = function(version) {

  var browserVersion = parseInt(jQuery.browser.version, 10);

  if(browserVersion < version) {
    return true;
  }
  else {
    return false;
  }

};

jQuery.cat.browser.supportsCanvas = function() {

  var elem = document.createElement('canvas');
  return !!(elem.getContext && elem.getContext('2d'));

};

/*
 * UI
 */

jQuery.cat.ui = function() {


};

// Evenly spaces the items in the container
jQuery.cat.ui.evenlySpaceList = function(containerEl) {

  var itemEls = containerEl.find('li');
  var totalWidth = containerEl.width();
  var totalItemsWidth = 0;

  for(var i=0;i<itemEls.length;i++) {
    var itemEl = $(itemEls[i]);
    totalItemsWidth += itemEl.width();
  }

  if(totalItemsWidth < totalWidth) {
    var paddingRight = (totalWidth - totalItemsWidth)/(itemEls.length);
    for(var i=0;i<itemEls.length;i++) {
      var itemEl = $(itemEls[i]);
      if(i!=itemEls.length-1) {
        itemEl.css('paddingRight', paddingRight);
      }
    }
  }

};

/*
 * DOM
 */

jQuery.cat.dom = function() {

};

// Redraw an element, useful for ghosting issues and artifacts caused by browser issues that are otherwise unsolvable
jQuery.cat.dom.repaint = function(el) {
  var t = el[0].ownerDocument.createTextNode('');
  el[0].appendChild(t);
  setTimeout(function() { el[0].removeChild(t); }, 0);
};

// Returns whether or not element has an attribute
jQuery.cat.dom.hasAttr = function(el, attr) {
  return el.is("[" + attr + "]");
};

// Returns attr, if does not exist returns default value
jQuery.cat.dom.getAttr = function(el, attr, defaultValue) {
  if(jQuery.cat.dom.hasAttr(el, attr)) {
    return el.attr(attr);
  }
  return defaultValue;
};

// Returns the height of the window cross-device compatible
jQuery.cat.dom.windowHeight = function() {

  var windowHeight = $(window).height();

  if(jQuery.cat.dom.isUndefined(window.innerHeight) == false) {
    windowHeight = window.innerHeight;
  }

  return windowHeight;

};

// Returns width of the scrollbar
// http://stackoverflow.com/questions/986937/how-can-i-get-the-browsers-scrollbar-sizes
jQuery.cat.dom.windowScrollBarWidth = function() {

  var inner = document.createElement('p');
  inner.style.width = "100%";
  inner.style.height = "200px";

  var outer = document.createElement('div');
  outer.style.position = "absolute";
  outer.style.top = "0px";
  outer.style.left = "0px";
  outer.style.visibility = "hidden";
  outer.style.width = "200px";
  outer.style.height = "150px";
  outer.style.overflow = "hidden";
  outer.appendChild (inner);

  document.body.appendChild (outer);
  var w1 = inner.offsetWidth;
  outer.style.overflow = 'scroll';
  var w2 = inner.offsetWidth;
  if (w1 == w2) w2 = outer.clientWidth;

  document.body.removeChild (outer);

  return (w1 - w2);

};

jQuery.cat.dom.isObject = function(obj) {
  return obj === Object(obj);
};

jQuery.cat.dom.isUndefined = function(obj) {
  if (typeof obj === 'undefined') {
    return true;
  }
  return false;
};

jQuery.cat.dom.equals = function(el1, el2) {
  if(el1[0] === el2[0]) {
    return true;
  }
  return false;
};

// Returns cross-browser scrollTop for the entire page
jQuery.cat.dom.windowScrollTop = function() {
  return $(window).scrollTop();
};

jQuery.cat.dom.preloadImage = function(src) {
  var image = new Image();
  image.src = src;
};

/*
 * Scrolls to part of page.
 *
 * Usage: $.cat.dom.bodyScrollTopAnimate(100, 'fast', 'linear', function() {});
 *
 */
jQuery.cat.dom.bodyScrollTopAnimate = function(scrollTop, duration, easing, callback) {

  $('html,body').animate(
      {scrollTop: scrollTop},
      duration,
      easing,
      callback
  );
};


/*
 * Datastructure
 */

jQuery.cat.datastructure = function() {

};

// Return the keys of an array sorted numerically
jQuery.cat.datastructure.getNumericSortedKeys = function(obj) {
  var keys = []; for(var key in obj) keys.push(key);
  return keys.sort(function(a,b){
    return a-b;
  });
};

// Extends the given array by another array
jQuery.cat.datastructure.extendArray = function(a, b) {
  Array.prototype.push.apply(a, b);
};

/*
 * Math
 */

jQuery.cat.math = function() {

};

jQuery.cat.math.uuid = function() {

  var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
    return v.toString(16);
  });

  return uuid;

};

// Return a random number from 1 to max
jQuery.cat.math.randomInt = function(max) {
  return 1 + Math.floor(Math.random() * max);
};


jQuery.cat.plugin = function() {

};

jQuery.cat.plugin.attrPrefix = "cat_plugin_";
jQuery.cat.plugin.classPrefix = "cat-plugin-";

jQuery.cat.plugin.attrName = function(name) {
  return jQuery.cat.plugin.attrPrefix + name;
};

jQuery.cat.plugin.className = function(name, includeSelector) {
  return (includeSelector ? '.' : '') + jQuery.cat.plugin.classPrefix + name;
};

/*
 * Anystretch image
 *
 * Usage: add class anystretch-image to any div, then add an attribute
 * anystretch_image_url="http://pathtoimage". Anystretch will then be applied
 * using the specified image.
 */

jQuery.cat.plugin.anystretchImage = function() {

};

jQuery.cat.plugin.anystretchImage.layout = function() {

  $(jQuery.cat.plugin.className('anystretch-image', true)).each(function() {
    var el = $(this);

    if(el.cat().dom().hasAttr(jQuery.cat.plugin.attrName('anystretch_image_url'))) {

      var uuid = jQuery.cat.math.uuid();
      el.attr(jQuery.cat.plugin.attrName('anystretch_id'), uuid);
      var imageUrl = el.attr(jQuery.cat.plugin.attrName('anystretch_image_url'));
      var imageEl = $('[' + jQuery.cat.plugin.attrName('anystretch_id') + '="' + uuid + '"]');

      if(jQuery.cat.dom.isUndefined($.anystretch) == false) {
          imageEl.anystretch(imageUrl);
      }
      else if(jQuery.cat.dom.isUndefined($('body').imgLiquid) == false) {
          if(imageEl.find('img').length == 0) {
              imageEl.append('<img src="' + imageUrl + '"/>');
          }
          imageEl.imgLiquid();
      }

    }
  });

};

/*
 * Breakpoint body
 *
 * This plugin allows you to define breakpoints for responsive layouts, the plugin
 * will add classes based on defined widths. This is an alternative to using media queries.
 *
 * Usage: add class cat-plugin-breakpoint-body to your body or a class right within
 * the body. Add the following attributes, these are the customizable settings.
 *
 * cat_plugin_breakpoint_body_names="s,m,l,xl"
 * cat_plugin_breakpoint_body_widths="600,780,1280,1560"
 * cat_plugin_breakpoint_body_size_class_prefix="breakpoint-body-size"
 * cat_plugin_breakpoint_body_class="breakpoint-body"
 *
 * This means small is 600 and below, medium is 600-780 and so on.
 * Intermediary classes are also added to target ranges such as s-m-l.
 */

jQuery.cat.plugin.breakpointBody = function() {

};

jQuery.cat.plugin.breakpointBody.layout = function() {

  var windowWidth = $(window).width();
  var breakpointBodyEl = $(jQuery.cat.plugin.className('breakpoint-body', true));

  if(breakpointBodyEl.cat().dom().hasAttr(jQuery.cat.plugin.attrName('breakpoint_body_names')) == false) {
    return;
  }

  var breakpointBodyNames = breakpointBodyEl.attr(jQuery.cat.plugin.attrName('breakpoint_body_names')).split(',');
  var breakpointBodyWidths = breakpointBodyEl.attr(jQuery.cat.plugin.attrName('breakpoint_body_widths')).split(',');
  var breakpointBodyClassPrefix = jQuery.cat.plugin.className('size-', false);
  var breakpointBodyClass = '';

  if(breakpointBodyEl.cat().dom().hasAttr(jQuery.cat.plugin.attrName('breakpoint_body_class'))) {
    breakpointBodyClass = breakpointBodyEl.attr(jQuery.cat.plugin.attrName('breakpoint_body_class'));
  }

  if(breakpointBodyEl.cat().dom().hasAttr(jQuery.cat.plugin.attrName('breakpoint_body_size_class_prefix'))) {
    breakpointBodyClassPrefix = breakpointBodyEl.attr(jQuery.cat.plugin.attrName('breakpoint_body_size_class_prefix')) + '-';
  }

  breakpointBodyEl.attr('class', '');
  breakpointBodyEl.addClass(jQuery.cat.plugin.className('breakpoint-body', false));
  breakpointBodyEl.addClass(breakpointBodyClass);

  var breakpointBodyComboNames = [];
  var breakpointBodyComboName = '';
  for(var i=0;i<breakpointBodyNames.length;i++) {
    var breakpointBodyName = breakpointBodyNames[i];
    if(i!=0) {
      breakpointBodyComboName += '-';
    }
    breakpointBodyComboName += breakpointBodyName;
    if(i!=0) {
      breakpointBodyComboNames.push(breakpointBodyComboName + ''); // Forces a copy of the string
    }
  }

  for(var i=breakpointBodyWidths.length-1;i>=0;i--) {

    var breakpointBodyWidth = breakpointBodyWidths[i];
    var breakpointBodyName = breakpointBodyNames[i];

    var nextBreakpointBodyName = '';
    if(i+1 < breakpointBodyWidths.length) {
      nextBreakpointBodyName = breakpointBodyNames[i+1];
    }

    if(i==0 && windowWidth <= breakpointBodyWidth) {
      breakpointBodyEl.addClass(breakpointBodyClassPrefix + breakpointBodyName);
      for(idx in breakpointBodyComboNames) {
        breakpointBodyEl.addClass(breakpointBodyClassPrefix + breakpointBodyComboNames[idx]);
      }

    }
    else if(windowWidth > breakpointBodyWidth) {

      if(nextBreakpointBodyName != '') {
        breakpointBodyEl.addClass(breakpointBodyClassPrefix + nextBreakpointBodyName);
        for(idx in breakpointBodyComboNames) {
          var breakpointBodyComboName = breakpointBodyComboNames[idx];
          if(breakpointBodyComboName.indexOf(nextBreakpointBodyName) >= 0) {
            breakpointBodyEl.addClass(breakpointBodyClassPrefix + breakpointBodyComboName);
          }

        }
      }

      break;
    }

  }

};

/*
 * Video swap
 *
 * Usage: add class video-swap to any div, then add an attribute
 * video_swap_url="http://pathtovideo". Video swap will then replace
 * with an iframe video on click of the div.
 */

jQuery.cat.plugin.videoSwap = function() {

};



jQuery.cat.plugin.videoSwap.layout = function() {

  $(jQuery.cat.plugin.className('video-swap', true)).each(function() {

    var containerEl = $(this);
    var buttonEl = containerEl;
    var videoPlayerUrl = containerEl.attr(jQuery.cat.plugin.attrName('video_swap_video_player_url'));

    if(containerEl.cat().dom().hasAttr(jQuery.cat.plugin.attrName('video_swap_button_selector'))) {
      buttonEl = $(containerEl.attr(jQuery.cat.plugin.attrName('video_swap_button_selector')));
    }

    buttonEl.click(function() {
      if(containerEl.cat().dom().hasAttr(jQuery.cat.plugin.attrName('video_swap_custom_embed'))) {
        containerEl.append($(containerEl.attr(jQuery.cat.plugin.attrName('video_swap_custom_embed'))));
      }
      else {
        containerEl.append('<iframe src="' + videoPlayerUrl + '" frameborder="0" style="width:' + '100%' + '; height:' + '100%' + ';"></iframe>');
      }

      jQuery.cat.plugin.videoSwap.onWindowResize();

    });

  });

};

jQuery.cat.plugin.videoSwap.onWindowResize = function() {

  // XXX Old Vimeo embeds need to be sized each time, as they don't support 100% width and heights
  $(jQuery.cat.plugin.className('video-swap object', true)).each(function() {
    var objectEl = $(this);
    var videoSwapEl = objectEl.closest(jQuery.cat.plugin.className('video-swap', true));
    objectEl.width(videoSwapEl.width());
    objectEl.height(videoSwapEl.height());
  });

};

/*
 * Click redirect
 *
 * Usage: add class click-redirect to any div, then add an attribute
 * click-redirect_url="http://url". Clicking on the div will redirect to the specified URL.
 * Also supports click_redirect_target.
 */

jQuery.cat.plugin.clickRedirect = function() {

};

jQuery.cat.plugin.clickRedirect.layout = function() {

  $(jQuery.cat.plugin.className('click-redirect', true)).each(function() {

    var clickRedirectEl = $(this);
    if(clickRedirectEl.hasClass(jQuery.cat.plugin.className('click-redirect-loaded', false)) == false) {
      clickRedirectEl.addClass(jQuery.cat.plugin.className('click-redirect-loaded', false));

      // Click redirect
      clickRedirectEl.click(function() {

        var redirectUrl = clickRedirectEl.attr(jQuery.cat.plugin.attrName('click_redirect_url'));
        var redirectTarget = clickRedirectEl.attr(jQuery.cat.plugin.attrName('click_redirect_target'));
        if(redirectTarget == '_blank') {
          window.open(redirectUrl);
        }
        else {
          document.location.href = redirectUrl;
        }

      });

    }

  });

};

/*
 * Scroll popup
 *
 * Scrollable popup, uses the window scroll bar to scroll. It is functional
 * on IE8+, Safari, Firefox, Chrome, and iOS devices.
 */
jQuery.cat.plugin.scrollPopup = function() {

};

jQuery.cat.plugin.scrollPopup.close = function(callback) {

  $('body').css('overflow', '');

  var scrollPopupEl = $(jQuery.cat.plugin.className('scroll-popup', true));
  scrollPopupEl.hide();

  callback(scrollPopupEl);

};

jQuery.cat.plugin.scrollPopup.open = function(callback, modalOverlayEnabled) {

  $('body').css('overflow', 'hidden');

  var scrollPopupEl = $(jQuery.cat.plugin.className('scroll-popup', true));
  scrollPopupEl.show();
  scrollPopupEl.css('position', 'fixed');
  scrollPopupEl.css('width', '100%');
  scrollPopupEl.css('height', '100%');
  scrollPopupEl.css('top', 0);
  scrollPopupEl.css('left', 0);
  scrollPopupEl.css('overflowY', 'scroll');

  var modalOverlayEl = null;

  if(modalOverlayEnabled) {

    $(jQuery.cat.plugin.className('scroll-popup-modal-overlay', true)).remove();

    modalOverlayEl = $('<div class="' + jQuery.cat.plugin.className('scroll-popup-modal-overlay', false) + '"></div>');

    modalOverlayEl.css('position', 'fixed');

    modalOverlayEl.css('height', '100%');
    modalOverlayEl.css('width', '100%');

    modalOverlayEl.css('zIndex', 9998);
    modalOverlayEl.css('top', 0);
    modalOverlayEl.css('left', 0);

    $('body').append(modalOverlayEl);
  }

  if(callback) {
    callback(scrollPopupEl, modalOverlayEl);
  }

};

/*
 * Custom select
 *
 * !Requires the selectbox plugin
 * http://www.bulgaria-web-developers.com/projects/javascript/selectbox/
 *
 * Usage, use the following markup
 *
 * <select class="cat-plugin-custom-select">
 *  <option>All</option>
 *  <option value="1">1</option>
 * </select>
 *
 */
jQuery.cat.plugin.customSelect = function() {

};

 // Updates the selected value for a given selector, note this requires a cat-plugin-custom-select-container wrapping div. Should be called within an onChange event.
jQuery.cat.plugin.customSelect.updateSelectValue = function(event, selector) {

  var selectContainerEl = $(event.currentTarget).closest(jQuery.cat.plugin.className('custom-select-container', true));
  var selectText = selectContainerEl.find('.sbSelector').text();

  $(selector).closest(jQuery.cat.plugin.className('custom-select-container', true)).each(function() {
    var containerEl = $(this);
    containerEl.find('.sbSelector').text(selectText);
  });

};

// Return the select value, should be used in the onChange event
jQuery.cat.plugin.customSelect.getSelectValueFromEvent = function(event) {
  return $(event.currentTarget).attr('selected_value');
};

jQuery.cat.plugin.customSelect.getSelectValue = function(selectEl) {
  return $(selectEl).attr('selected_value');
};

jQuery.cat.plugin.customSelect.layout = function(callback) {

  if($(jQuery.cat.plugin.className('custom-select', true)).length > 0) {

    $(jQuery.cat.plugin.className('custom-select', true)).selectbox({

      onOpen : function(inst) {
        var sbSelector = $("#sbSelector_" + inst.uid);
        var sbHolder = sbSelector.closest('.sbHolder');
        var sb = sbHolder.siblings('select');

        sbSelector.addClass(jQuery.cat.plugin.className('custom-select-open', false));
        sbHolder.addClass(jQuery.cat.plugin.className('custom-select-open', false));

        if(callback) {
          callback('open', inst);
        }

      },

      onClose : function(inst) {
        var sbSelector = $("#sbSelector_" + inst.uid);
        var sbHolder = sbSelector.closest('.sbHolder');
        var sb = sbHolder.siblings('select');

        sbSelector.removeClass(jQuery.cat.plugin.className('custom-select-open', false));
        sbHolder.removeClass(jQuery.cat.plugin.className('custom-select-open', false));

        if(callback) {
          callback('close', inst);
        }

      },

      onChange : function(value, inst, sbSelector) {

        sbSelector.removeClass(jQuery.cat.plugin.className('custom-select-unselected', false));
        if (value == "") {
          sbSelector.addClass(jQuery.cat.plugin.className('custom-select-unselected', false));
        }

        // XXX for whatever reason, the value isn't ready on the change
        // callback in Android, so we set this attribute instead to key off of
        $(inst.input).attr('selected_value', value);

        if(callback) {
          callback('change', inst);
        }

      },
      onLoad : function(inst, input, sbSelector) {
        if ($(input).val() == "") {
          sbSelector.addClass(jQuery.cat.plugin.className('custom-select-unselected', false));
        }
        else {
          // Sync the selected value if the input contains it on startup
          $(inst.input).attr('selected_value', $(input).val());
        }

        if(callback) {
          callback('load', inst);
        }

      }
    });

  }

};

/*
 * Content expander
 *
 * Usage: Add the following markup
 *  <div class="cat-plugin-content-expander"
 *     cat_plugin_content_expander_expand_button_selector=".mybutton"
 *     cat_plugin_content_expander_expand_selector=".myexpansion"
 *  >
 *    expand!
 *  </div>
 *
 */
jQuery.cat.plugin.contentExpander = function() {


};

jQuery.cat.plugin.contentExpander.layout = function() {

  $(jQuery.cat.plugin.className('content-expander', true)).each(function() {

    var contentExpanderEl = $(this);
    var contentExpandButtonEl = $(contentExpanderEl.attr(jQuery.cat.plugin.attrName('content_expander_expand_button_selector')));
    var contentExpandEl = $(contentExpanderEl.attr(jQuery.cat.plugin.attrName('content_expander_expand_selector')));

    if(contentExpanderEl.hasClass(jQuery.cat.plugin.className('content-expander-loaded', false)) == false) {

      contentExpanderEl.addClass(jQuery.cat.plugin.className('content-expander-loaded', false));

      contentExpandButtonEl.unbind('click');
      contentExpandButtonEl.click(function() {

        if(contentExpanderEl.hasClass(jQuery.cat.plugin.className('content-expander-expanded', false))) {
          contentExpanderEl.removeClass(jQuery.cat.plugin.className('content-expander-expanded', false));
          contentExpandEl.slideUp();
        }
        else {
          contentExpanderEl.addClass(jQuery.cat.plugin.className('content-expander-expanded', false));
          contentExpandEl.slideDown();
        }

      });

    }

  });

};

/*
 * Force fluid element
 *
 * Certain elements will not keep their aspect ratio when set to width 100%, the height
 * will remain the same, for such things as iframes and object embeds.
 *
 * Using force fluid element we can force to 100% width, while maintaining the aspect ratio
 * for the height. Just set the width and height as below.
 *
 * <iframe class="cat-plugin-force-fluid-element" width="200" height="200">...</iframe>
 *
 */
jQuery.cat.plugin.forceFluidElement = function() {
};

jQuery.cat.plugin.forceFluidElement.onWindowResize = function() {
  jQuery.cat.plugin.forceFluidElement.layout();
};

jQuery.cat.plugin.forceFluidElement.layout = function() {

  $(jQuery.cat.plugin.className('force-fluid-element', true)).each(function() {

    var iframeEl = $(this);

    if(iframeEl.cat().dom().hasAttr(jQuery.cat.plugin.attrName('force_fluid_element_aspect_ratio')) == false) {

      // We need to use css values because the height() and width() functions make the iframe hide for vimeo embeds
      var height = jQuery.cat.string.pxToFloat(iframeEl.attr('height'));
      var width = jQuery.cat.string.pxToFloat(iframeEl.attr('width'));

      iframeEl.attr(jQuery.cat.plugin.attrName('force_fluid_element_aspect_ratio'), (height/width));
    }

    var aspectRatio = parseFloat(iframeEl.attr(jQuery.cat.plugin.attrName('force_fluid_element_aspect_ratio')));
    iframeEl.css('height', parseInt(iframeEl.width() * aspectRatio) + 'px');

  });
};

/*
 * Responsive table
 *
 * Turns a set of divs with float:left to act just like tables.
 * You can then set the number of columns by assigning percentages
 * to the divs for widths at different breakpoints.
 *
 * Supports IE9+, Chrome, Firefox, Safari, iOS
 *
 * Usage: add the following markup
 *
 * <div class="cat-plugin-responsive-table">
 *  <div class="cat-plugin-responsive-table-item">1</div>
 *  <div class="cat-plugin-responsive-table-item">2</div>
 *  <div class="cat-plugin-responsive-table-item">3</div>
 *  <div class="cat-plugin-responsive-table-item">4</div>
 *  <div class="cat-plugin-responsive-table-item">5</div>
 *  <div class="cat-plugin-responsive-table-item">6</div>
 * </div>
 *
 * With the following css
 *
 * float:left;
 * width:33%;
 *
 * And at a smaller breakpoint for example you can change the number of columns
 *
 * width:50%
 *
 */
jQuery.cat.plugin.responsiveTable = function() {

};

jQuery.cat.plugin.responsiveTable.layout = function() {

  $(jQuery.cat.plugin.className('responsive-table', true)).each(function() {

    var responsiveTableEl = $(this);
    var responsiveTableItemEls = responsiveTableEl.find(jQuery.cat.plugin.className('responsive-table-item', true));
    var maxItemHeight = 0;

    // Arrange into perfect rows
    responsiveTableItemEls.height('auto');
    for(var i=0;i<responsiveTableItemEls.length;i++) {

      var responsiveTableItemEl = $(responsiveTableItemEls[i]);
      maxItemHeight = Math.max(maxItemHeight, responsiveTableItemEl.height());

    }

    responsiveTableItemEls.height(maxItemHeight);

    // Place into buckets by their position since we are now arranged into rows
    var itemsByPosition = {};
    for(var i=0;i<responsiveTableItemEls.length;i++) {

      var responsiveTableItemEl = $(responsiveTableItemEls[i]);
      var top = responsiveTableItemEl.position().top;

      if(top in itemsByPosition) {

      }
      else {
        itemsByPosition[top] = [];
      }

      itemsByPosition[top].push(responsiveTableItemEl);

    }

    // Finally assign heights to the items based on their max in their row
    responsiveTableItemEls.height('auto');
    var keyIndex = 0;
    for(var key in itemsByPosition) {

      var itemsForPosition = itemsByPosition[key];
      var maxItemHeight = 0;
      for(var i=0;i<itemsForPosition.length;i++) {
        var itemForPosition = $(itemsForPosition[i]);
        maxItemHeight = Math.max(maxItemHeight, itemForPosition.height());
      }

      for(var i=0;i<itemsForPosition.length;i++) {
        var itemForPosition = $(itemsForPosition[i]);
        itemForPosition.height(maxItemHeight);
        itemForPosition.attr('data-row-index', keyIndex);
      }

      keyIndex++;

    }

    responsiveTableEl.attr('data-count', responsiveTableItemEls.length);
    responsiveTableEl.attr('data-row-count', keyIndex);


  });

};


/*
 * Page loader
 *
 * Usage: Add the following html markup to your layout
 *
 * <div class="cat-plugin-page-loader-dialog" cat_plugin_page_loader_fade_out_duration="200">
 *  <img src="path_to_my_loading_image"/>
 * </div>
 *
 * Now add a function to call after loading, it would typically set the
 * content to visible, and then fade it in, e.g.
 *
 * function onPageLoaded() {
 *   $('.layout').hide();
 *   $('.layout').css('visibility', 'visible');
 *   $('.layout').fadeIn();
 * }
 *
 * jQuery.cat.plugin.pageLoader.onLoadedFunction = onPageLoaded;
 *
 * Finally add the neccessary CSS
 *
 * .cat-plugin-page-loader-dialog {
 *   display:none;
 * }
 *
 * .layout {
 *  visibility:hidden;
 * }
 *
 */
jQuery.cat.plugin.pageLoader = function() {

};

jQuery.cat.plugin.pageLoader.onLoadedFunction = null;
jQuery.cat.plugin.pageLoader.onStartDomReady = function() {

  // Attach loading indicator to page
  $(jQuery.cat.plugin.className('page-loader-dialog', true)).cat().ui().popupDialog(true, null,
    function(dialogEl, modalEl) {
      dialogEl.show();
      modalEl.addClass(jQuery.cat.plugin.className('page-loader-dialog-modal', false));
    }
  );

};

jQuery.cat.plugin.pageLoader.onEndDomReady = function() {

  var fadeOutDuration = parseInt($(jQuery.cat.plugin.className('page-loader-dialog', true)).attr(jQuery.cat.plugin.attrName('page_loader_fade_out_duration')));

  $(jQuery.cat.plugin.className('page-loader-dialog-modal', true)).fadeOut(fadeOutDuration);
  $(jQuery.cat.plugin.className('page-loader-dialog', true)).fadeOut(fadeOutDuration, function() {
    jQuery.cat.plugin.pageLoader.onLoadedFunction();
  });

};

/*
 * Document height change
 *
 * Easy plugin to register and detect document height changes, you should
 * trigger document height changes in things such as slideDown callbacks
 * etc. using jQuery.cat.plugin.documentHeightChange.trigger();
 *
 * Then register your functions in
 *
 * jQuery.cat.plugin.documentHeightChange.onChange(function() {
 *   alert('changed!');
 * });
 *
 */
jQuery.cat.plugin.documentHeightChange = function() {

};

jQuery.cat.plugin.documentHeightChange.trigger = function() {
  $('body').trigger(jQuery.cat.plugin.attrName("document_height_resize"));
};

jQuery.cat.plugin.documentHeightChange.onChange = function(callback) {

  $(document).ready(function() {
    $('body').on(jQuery.cat.plugin.attrName("document_height_resize"), function() {
      callback();
    });
  });
};

/*
 * Stitch Animate
 *
 * Usage: Add class stitch-animate to a div, assumes a horizontal stitch,
 * the reason stitch is needed is to support devices that have maximum
 * sizes for background images, so this animates "stitched" background
 * images, that is a div with multiple background images stiched together
 * to look like one seamless one.
 *
 * This animation routine depends on multiple backgrounds supported in the browser:
 * http://caniuse.com/multibackgrounds
 *
 * Essentially for IE8 and lower, a placeholder should be used.
 */

jQuery.cat.plugin.stitchAnimate = function() {

};

jQuery.cat.plugin.stitchAnimate.layout = function() {

  $(jQuery.cat.plugin.className('stitch-animate', true)).each(function() {

    var containerEl = $(this);
    var seams = containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_seams'));
    var increment = parseInt(containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_increment')));
    var numFrames = parseInt(containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_frames'))) - 1;
    var animationDelay = parseInt(containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_delay')));

    var seamsSplit = seams.split(",");
    var numSeams = seamsSplit.length;

    for(var i=0;i<seamsSplit.length;i++) {
      containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_seam_' + i), seamsSplit[i]);
    }

    containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_frame'), 0);

    window.setInterval(function() {

      var newBackgroundPosition = "";
      for(var i=0;i<numSeams;i++) {
        var seam = parseInt(containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_seam_' + i)));
        seam -= increment;

        if(newBackgroundPosition != "") {
          newBackgroundPosition += ', ';
        }
        newBackgroundPosition += seam + 'px 0px';

        containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_seam_' + i), seam);
      }

      containerEl.css('backgroundPosition', newBackgroundPosition);

      var currentFrame = parseInt(containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_frame')));
      containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_frame'), currentFrame+1);

      // Loop back to start
      if(currentFrame + 1 > numFrames) {
        for(var i=0;i<seamsSplit.length;i++) {
          containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_seam_' + i), seamsSplit[i]);
          containerEl.attr(jQuery.cat.plugin.attrName('stitch_animate_frame'), 0);
        }
      }

    }, animationDelay);




  });

};



})(jQuery);
