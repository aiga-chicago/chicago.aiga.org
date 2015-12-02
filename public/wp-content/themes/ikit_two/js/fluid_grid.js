/*
 * Fluid grid
 *
 * Usage:
 * Add the class 'cat-plugin-fluid-grid' to your div. Inside the div
 * add grid items with class 'cat-plugin-fluid-grid-item'.
 *
 * Now configure the grid with the following:
 *
 * cat_plugin_fluid_grid_breakpoint_body_class="breakpoint-body"
 * cat_plugin_fluid_grid_breakpoint_body_size_classes="breakpoint-body-size-xl,breakpoint-body-size-l,breakpoint-body-size-m,breakpoint-body-size-s"
 * cat_plugin_fluid_grid_breakpoint_body_size_num_cols="5,4,3,2"
 * cat_plugin_fluid_grid_layout_mode="fitRows"
 *
 */
jQuery.cat.plugin.fluidGrid = function() {

};

jQuery.cat.plugin.fluidGrid.grid = function() {

};

jQuery.cat.plugin.fluidGrid.grid.overrideItemWidthFunction = null;
jQuery.cat.plugin.fluidGrid.grid.overrideItemHeightFunction = null;
jQuery.cat.plugin.fluidGrid.grid.onDomReady = function() {

  jQuery.cat.plugin.fluidGrid.grid.layout();

};

jQuery.cat.plugin.fluidGrid.grid.onWindowResize = function() {
  jQuery.cat.plugin.fluidGrid.grid.layout();
};

jQuery.cat.plugin.fluidGrid.grid.onStartWindowLoad = function() {
  jQuery.cat.plugin.fluidGrid.isotope.relayout(jQuery.cat.plugin.className('fluid-grid', true));
};

jQuery.cat.plugin.fluidGrid.grid.onEndWindowLoad = function() {
    jQuery.cat.plugin.fluidGrid.grid.layout();
};

jQuery.cat.plugin.fluidGrid.grid.layout = function() {

  $(jQuery.cat.plugin.className('fluid-grid', true)).each(function() {

    var gridEl = $(this);
    var gridLayoutMode = null;

    // Allow for grids to define custom number of columns per breakpoint

    var customNumColsByBreakpoint = gridEl.attr(jQuery.cat.plugin.attrName('fluid_grid_breakpoint_body_size_num_cols')).split(',');
    var customClassByBreakpoint = gridEl.attr(jQuery.cat.plugin.attrName('fluid_grid_breakpoint_body_size_classes')).split(',');

    if(gridEl.cat().dom().hasAttr(jQuery.cat.plugin.attrName('fluid_grid_layout_mode'))) {
      gridLayoutMode = gridEl.attr(jQuery.cat.plugin.attrName('fluid_grid_layout_mode'));
    }

    var breakpointBodyEl = $('.' + gridEl.attr(jQuery.cat.plugin.attrName('fluid_grid_breakpoint_body_class')));

    var numCols = customNumColsByBreakpoint[0];
    for(var i=0;i<customNumColsByBreakpoint.length;i++) {
      if(breakpointBodyEl.hasClass(customClassByBreakpoint[i])) {
        numCols = customNumColsByBreakpoint[i];
        break;
      }
    }

    var gridWidth = gridEl.outerWidth();
    var columnWidth = Math.floor(gridWidth / numCols);

    gridEl.find(jQuery.cat.plugin.className('fluid-grid-item', true)).each(function() {

      var gridItemEl = $(this);

      var gridItemSize = $(this).attr(jQuery.cat.plugin.attrName('fluid_grid_item_size'));

      if(jQuery.cat.plugin.fluidGrid.grid.overrideItemWidthFunction == null || jQuery.cat.plugin.fluidGrid.grid.overrideItemWidthFunction($(this), columnWidth, numCols) == false) {
          if(gridItemEl.cat().dom().hasAttr(jQuery.cat.plugin.attrName('fluid_grid_item_size'))) {
            $(this).width(columnWidth * parseInt(gridItemSize));
          } else {
            $(this).width(columnWidth);
          }
      }

      if(jQuery.cat.plugin.fluidGrid.grid.overrideItemHeightFunction == null || jQuery.cat.plugin.fluidGrid.grid.overrideItemHeightFunction($(this), columnWidth, numCols) == false) {
        if(gridItemEl.cat().dom().hasAttr(jQuery.cat.plugin.attrName('fluid_grid_item_aspect_ratio'))) {
          $(this).height($(this).width() * parseFloat(gridItemEl.attr(jQuery.cat.plugin.attrName('fluid_grid_item_aspect_ratio'))));
        }
      }


    });

    jQuery.cat.plugin.fluidGrid.isotope.create(gridEl, columnWidth, gridLayoutMode);

  });

};

jQuery.cat.plugin.fluidGrid.grid.onWindowResize = function() {

  jQuery.cat.plugin.fluidGrid.grid.layout();

};

jQuery.cat.plugin.fluidGrid.grid.onWindowLoad = function() {
  jQuery.cat.plugin.fluidGrid.isotope.relayout(jQuery.cat.plugin.className('fluid-grid', true));
};

/**
 * Isotope
 */
jQuery.cat.plugin.fluidGrid.isotope = function() {

};

jQuery.cat.plugin.fluidGrid.isotope.exists = function(selector) {
  if ($(selector).hasClass('isotope')) {
    return true;
  }
  return false;
};

jQuery.cat.plugin.fluidGrid.isotope.append = function(selector, data) {
  var data = $(data); // Must be wrapped as a jQuery object to be used
  $(selector).append(data).isotope( 'appended', data);
};

jQuery.cat.plugin.fluidGrid.isotope.destroy = function(selector) {
  $(selector).isotope('destroy');
};

jQuery.cat.plugin.fluidGrid.isotope.relayout = function(selector) {
  $(selector).isotope('reLayout');
};

jQuery.cat.plugin.fluidGrid.isotope.create = function(selector, columnWidth, gridLayoutMode) {

  // Isotyope
  $(selector).isotope({
    transformsEnabled : false,
    itemSelector : jQuery.cat.plugin.className('fluid-grid-item', true),
    animationEngine : 'css',
    layoutMode : gridLayoutMode == null ? 'masonry' : gridLayoutMode,
    masonry : {
      columnWidth : columnWidth
    }
  });

};