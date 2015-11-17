jQuery.ikit_admin = function() {

};

jQuery.ikit_admin.widgets = function() {

};

jQuery.ikit_admin.widgets.quoteWidget = function() {

};

jQuery.ikit_admin.widgets.quoteWidget.addQuote = function(buttonEl) {

  var buttonEl = jQuery(buttonEl);
  var widgetEl = buttonEl.closest('.widget');

  var quotesEl = widgetEl.find('.quotes');
  var quoteEl = quotesEl.find('.quote').last();

  var quotePrefix = quoteEl.attr('quote_prefix');
  var quoteIndex = parseInt(quoteEl.attr('quote_index'));

  // Copy the HTML directly, replacing the index values
  var newQuoteIndex = quoteIndex+1;
  var newQuoteHtml = quoteEl.html().replace(/-idx-([0-9]+)/g,'-idx-' + newQuoteIndex);

  var buf = [];
  buf.push('<div class="wp-box quote" quote_prefix="' + quotePrefix + '" quote_index="' + newQuoteIndex + '">');
  buf.push(newQuoteHtml);
  buf.push('</div>');

  widgetEl.find('#' + quotePrefix + 'quote-max').val(newQuoteIndex+1);

  // Append the new inputs to the container
  quotesEl.append(buf.join(""));

  // Clear out the inputs
  var newQuoteEl = quotesEl.find('.quote').last();
  newQuoteEl.find('input').val('');
  newQuoteEl.find('textarea').val('');


};

jQuery.ikit_admin.widgets.quoteWidget.removeQuote = function(buttonEl) {

  var buttonEl = jQuery(buttonEl);
  var quoteEl = buttonEl.closest('.quote');
  var widgetEl = buttonEl.closest('.widget');
  var quotesEl = widgetEl.find('.quotes');

  var numQuotes = quotesEl.find('.quote').length;

  if(numQuotes > 1) {

    quoteEl.slideUp(function() {
      quoteEl.remove();
    });

  }
  else {

    quoteEl.find('input').val('');
    quoteEl.find('textarea').val('');

  }


};