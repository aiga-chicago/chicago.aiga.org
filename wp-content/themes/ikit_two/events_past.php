<?php
/**
 * Template Name: Events Past
 */
?>
<?php get_header();?>

<script type="text/javascript">

jQuery.events_past = function() {

};

jQuery.events_past.onStartDomReady = function() {

    jQuery.ikit_two.infinityFetcher.fetchingUrl = "<?php echo IKIT_AJAX_URL; ?>";
    jQuery.ikit_two.infinityFetcher.fetchButton = $('.infinity-fetcher-fetch-button');
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['action'] = 'ikit_two_ajax_render_events_past';
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['y'] = "<?php echo $_GET['y']; ?>";
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['category'] = "<?php echo $_GET['category']; ?>";

    // Hide fetch button if no more pages
    if(parseInt($('.infinity-fetcher-grid .data').attr('num_pages')) <= parseInt($('.infinity-fetcher-grid .data').attr('page'))) {
        $('.infinity-fetcher-fetch-button').hide();
    }

};

jQuery.events_past.onDomReady = function() {

    $('.year-filter').click(function() {
        var urlParams = {};
        jQuery.events_past.updateUrlParams(urlParams);
        urlParams['y'] = $(this).attr('value');
        window.location.href = '?' + jQuery.param(urlParams);
    });

    $('.category-filter').click(function() {
        var urlParams = {};
        jQuery.events_past.updateUrlParams(urlParams);
        urlParams['category'] = $(this).attr('value');
        window.location.href = '?' + jQuery.param(urlParams);
    });

};

jQuery.events_past.updateUrlParams = function(urlParams) {

    <?php if(empty($_GET['y']) == false) { ?>
        urlParams['y'] = '<?php echo $_GET['y']; ?>';
    <?php } ?>

    <?php if(empty($_GET['category']) == false) { ?>
        urlParams['category'] = '<?php echo $_GET['category']; ?>';
    <?php } ?>

};

jQuery.events_past.afterFetch = function(response, page, numPages) {

    jQuery.ikit_two.infinityFetcher.afterFetchGrid('.infinity-fetcher-grid', response, page, numPages, true);
    jQuery.cat.plugin.anystretchImage.layout();

};

$(document).ready(function() {
    jQuery.events_past.onDomReady();
});

jQuery.ikit_two.infinityFetcher.afterFetchFunctions.push(jQuery.events_past.afterFetch);
jQuery.ikit_two.onStartDomReadyFunctions.push(jQuery.events_past.onStartDomReady);

</script>

<div class="page-header-3">
    <table>
    <tr>
        <td class="page-header-3-section-title">
            <a href="<?php echo ikit_get_request_url(false); ?>">Past Events</a>
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

            <div class="cat-plugin-fluid-grid-item grid-item">
                <div class="grid-item-inner">

                    <div class="page-layout-4-tool">
                        <div class="page-layout-4-tool-header">Year</div>
                        <div class="page-layout-4-tool-filters">
                        <div class="page-layout-4-tool-filter year-filter <?php if($_GET['y'] == null) { ?>active<?php } ?>" value="">All</div>
                        <?php
                        for($i=0;$i<5;$i++) {
                            $year = date("Y",strtotime("-" . $i . " year"));
                            ?>
                            <div class="page-layout-4-tool-filter year-filter <?php if($_GET['y'] == $year) { ?>active<?php } ?>" value="<?php echo $year ?>" href="javascript:void(0);"><?php echo $year ?></div>
                            <?php
                        }
                        ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="cat-plugin-fluid-grid-item grid-item category-filters">
                <div class="grid-item-inner">
                    <div class="page-layout-4-tool">
                        <div class="page-layout-4-tool-header">Categories</div>
                        <div class="page-layout-4-tool-filters">
                        <div class="page-layout-4-tool-filter category-filter <?php if($_GET['category'] == null) { ?>active<?php } ?>" value="">All</div>
                        <?php
                        $args = array();
                        $args['type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL, IKIT_POST_TYPE_IKIT_EVENT_INTERNAL);
                        $categories = ikit_get_categories_for_post_type(array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL, IKIT_POST_TYPE_IKIT_EVENT_INTERNAL), $args);
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

            <div class="cat-plugin-fluid-grid grid infinity-fetcher-grid"
                cat_plugin_fluid_grid_layout_mode="fitRows"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_BODY_NO_SIDEBAR_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
            >

                <?php
                $data = array();
                $data['category'] = $_GET['category'];
                $data['y'] = $_GET['y'];
                $rendered = ikit_two_ajax('ikit_two_ajax_render_events_past', $data);

                $no_results = false;
                preg_match_all('/num_pages="([0-9]+)"/i', $rendered, $matches);
                if(intval($matches[1][0]) <= 0) {
                    $no_results = true;
                }
                ?>

                <?php if(empty($rendered) == false && $no_results == false) { ?>
                    <?php echo $rendered; ?>
                <?php } else { ?>
                    <div class="cat-plugin-fluid-grid-item grid-item grid-item-empty">
                        <div class="grid-item-inner">
                            No events found.
                        </div>
                    </div>
                <?php } ?>
            </div>

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