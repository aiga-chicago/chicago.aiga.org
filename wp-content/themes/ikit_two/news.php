<?php
/**
 * Template Name: News
 */
?>
<?php get_header();?>

<script type="text/javascript">

jQuery.news = function() {

};

jQuery.news.onDomReady = function() {

    $('.category-filter').click(function() {
        var urlParams = {};
        urlParams['category'] = $(this).attr('value');
        window.location.href = '?' + jQuery.param(urlParams);
    });

};

jQuery.news.onStartDomReady = function() {

    jQuery.ikit_two.infinityFetcher.contentContainer = $('.infinity-fetcher-grid');
    jQuery.ikit_two.infinityFetcher.fetchButton = $('.infinity-fetcher-fetch-button');
    jQuery.ikit_two.infinityFetcher.fetchingUrl = "<?php echo IKIT_AJAX_URL; ?>";
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['action'] = 'ikit_two_ajax_render_news';
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['category'] = "<?php echo $_GET['category']; ?>";

    // Hide fetch button if no more pages
    if(parseInt($('.infinity-fetcher-grid .data').attr('num_pages')) <= parseInt($('.infinity-fetcher-grid .data').attr('page'))) {
        $('.infinity-fetcher-fetch-button').hide();
    }

};

jQuery.news.afterFetchFinished = function(response, page, numPages) {

    jQuery.ikit_two.infinityFetcher.afterFetchGrid('.infinity-fetcher-grid', response, page, numPages, true);
    jQuery.cat.plugin.anystretchImage.layout();
};

$(document).ready(function() {
    jQuery.news.onDomReady();
});

jQuery.ikit_two.infinityFetcher.afterFetchFinishedFunctions.push(jQuery.news.afterFetchFinished);
jQuery.ikit_two.onStartDomReadyFunctions.push(jQuery.news.onStartDomReady);

</script>

<div class="page-header-3">
    <table>
    <tr>
        <td class="page-header-3-section-title">
            <a href="<?php echo ikit_get_request_url(false); ?>">News</a>
        </td>
        <td class="page-header-3-title"></td>
    </tr>
    </table>
</div>

<table class="page-layout-4">
<tr>

    <td class="page-layout-4-tools">
        <div class="cat-plugin-fluid-grid grid"
            cat_plugin_fluid_grid_layout_mode="fitRows"
            cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_TOOLS_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
            cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
            cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
        >

            <?php if(ikit_two_has_toc($post)) {?>
            <div class="cat-plugin-fluid-grid-item grid-item">
                <div class="grid-item-inner">
                    <div class="page-layout-4-tool">
                        <?php ikit_two_render_toc($post, 'page-layout-4-tool'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="cat-plugin-fluid-grid-item grid-item category-filters">
                <div class="grid-item-inner">
                    <div class="page-layout-4-tool">
                        <div class="page-layout-4-tool-header">Categories</div>
                        <div class="page-layout-4-tool-filters">
                        <div class="page-layout-4-tool-filter category-filter <?php if($_GET['category'] == null) { ?>active<?php } ?>" value="">All</div>
                        <?php
                        $args = array();
                        $args['type'] = 'post';
                        $categories = ikit_get_categories_for_post_type('post', $args);
                        foreach($categories as $category) {
                            ?>
                            <div class="page-layout-4-tool-filter category-filter <?php if($_GET['category'] == $category->slug) { ?>active<?php } ?>" value="<?php echo $category->slug; ?>"><?php echo $category->name; ?></div>
                            <?php
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-layout-4-tools-footer"></div>

    </td>

    <td class="page-layout-4-content">

        <table>
        <tr>
        <td class="page-layout-4-body">

            <?php

            $data = array();
            $data['category'] = $_GET['category'];
            $rendered = ikit_two_ajax('ikit_two_ajax_render_news', $data);
            ?>

            <?php if(empty($rendered) == false) { ?>

                    <div class="cat-plugin-fluid-grid grid infinity-fetcher-grid"
                        cat_plugin_fluid_grid_layout_mode="fitRows"
                        cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_BODY_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                        cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                        cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
                    >
                    <?php echo $rendered; ?>
                </div>

            <?php } ?>

        </td>
        <td class="page-layout-4-sidebar">
            <?php dynamic_sidebar('news-sidebar');?>
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