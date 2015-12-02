<?php
/**
 * Index
 */
?>
<?php get_header();?>

<script type="text/javascript">

jQuery.index = function() {

};

jQuery.index.onStartDomReady = function() {

  $('.ikit-widget-quote').attr('cat_plugin_fluid_grid_item_size', 5);

};

jQuery.ikit_two.onStartDomReadyFunctions.push(jQuery.index.onStartDomReady);

</script>

<div class="index-billboard-sidebar">
    <?php dynamic_sidebar('index-billboard-sidebar');?>
</div>

<div class="cat-plugin-fluid-grid grid widget-grid page-layout-3 index-sidebar"
    cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
    cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
    cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
>
    <?php dynamic_sidebar('index-sidebar');?>
</div>

<?php get_footer();?>