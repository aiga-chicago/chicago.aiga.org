<?php
/**
 * Template Name: Category
 */
?>
<?php get_header();?>

<?php

    $category = $wp_query->get_queried_object();

?>

<script type="text/javascript">

jQuery.category = function() {

};

jQuery.category.onStartDomReady = function() {

    jQuery.ikit_two.infinityFetcher.contentContainer = $('.infinity-fetcher-grid');
    jQuery.ikit_two.infinityFetcher.fetchButton = $('.infinity-fetcher-fetch-button');
    jQuery.ikit_two.infinityFetcher.fetchingUrl = "<?php echo IKIT_AJAX_URL; ?>";
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['action'] = 'ikit_two_ajax_render_news';
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['category'] = '<?php echo $category->slug; ?>';
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['disable_featured'] = false;

    // Hide fetch button if no more pages
    if(parseInt($('.infinity-fetcher-grid .data').attr('num_pages')) <= parseInt($('.infinity-fetcher-grid .data').attr('page'))) {
        $('.infinity-fetcher-fetch-button').hide();
    }

};

jQuery.category.afterFetchFinished = function(response, page, numPages) {

    jQuery.ikit_two.infinityFetcher.afterFetchGrid('.infinity-fetcher-grid', response, page, numPages, true);
    jQuery.cat.plugin.anystretchImage.layout();
};

jQuery.ikit_two.infinityFetcher.afterFetchFinishedFunctions.push(jQuery.category.afterFetchFinished);
jQuery.ikit_two.onStartDomReadyFunctions.push(jQuery.category.onStartDomReady);

</script>

<div class="page-header-3">
    <table>
    <tr>
        <td class="page-header-3-section-title">
            <a href="<?php echo ikit_get_request_url(false); ?>">News / <span class="header-category-name"><?php echo $category->name; ?></span></a>
        </td>
        <td class="page-header-3-title"></td>
    </tr>
    </table>
</div>


<table class="page-layout-4">
<tr>

    <td class="page-layout-4-content">

        <table>
        <tr>
        <td class="page-layout-4-body">

            <?php

            $data = array();
            $data['category'] = $category->slug;
            $data['disable_featured'] = true;
            $rendered = ikit_two_ajax('ikit_two_ajax_render_news', $data);
            ?>

            <?php if(empty($rendered) == false) { ?>

                    <div class="cat-plugin-fluid-grid grid infinity-fetcher-grid"
                        cat_plugin_fluid_grid_layout_mode="fitRows"
                        cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_BODY_FULL_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                        cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                        cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
                    >
                    <?php echo $rendered; ?>
                </div>

            <?php } ?>

        </td>
        </tr>
        </table>

    </td>

</tr>
</table>

<div class="box-button-container box-button-1-container page-layout-4-loader">
    <a href="javascript:void(0);" class="box-button-1 infinity-fetcher-fetch-button">
        <span>Load More</span>
        <div class="css-loader">
            <span class="css-loader-fallback">LOADING...</span>
        </div>
    </a>
</div>



<?php get_footer();?>