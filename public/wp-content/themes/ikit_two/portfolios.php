<?php
/**
 * Template Name: Portfolios
 */
?>
<?php get_header();?>

<script type="text/javascript">

jQuery.portfolios = function() {

};

jQuery.portfolios.onStartDomReady = function() {

    jQuery.ikit_two.infinityFetcher.fetchingUrl = "<?php echo IKIT_AJAX_URL; ?>";
    jQuery.ikit_two.infinityFetcher.fetchButton = $('.infinity-fetcher-fetch-button');
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['action'] = 'ikit_two_ajax_render_portfolios';

    // Hide fetch button if no more pages
    if(parseInt($('.infinity-fetcher-grid .data').attr('num_pages')) <= parseInt($('.infinity-fetcher-grid .data').attr('page'))) {
        $('.infinity-fetcher-fetch-button').hide();
    }

};

jQuery.portfolios.onDomReady = function() {

};

jQuery.portfolios.afterFetch = function(response, page, numPages) {

    jQuery.ikit_two.infinityFetcher.afterFetchGrid('.infinity-fetcher-grid', response, page, numPages, true);
    jQuery.cat.plugin.anystretchImage.layout();

};

$(document).ready(function() {
    jQuery.portfolios.onDomReady();
});

jQuery.ikit_two.infinityFetcher.afterFetchFunctions.push(jQuery.portfolios.afterFetch)
jQuery.ikit_two.onStartDomReadyFunctions.push(jQuery.portfolios.onStartDomReady);

</script>

<div class="page-header-3">
    <table>
    <tr>
        <td class="page-header-3-section-title">
            <a href="<?php echo ikit_get_request_url(false); ?>">Portfolios</a>
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

            <div class="page-layout-4-body-description">
                <div class="page-layout-4-body-description-inner">
                    Check out the work of other chapter members! These projects are from the <a target="_blank" href="http://portfolios.aiga.org/">AIGA Member Portfolios Gallery</a>. Participation requires an <a target="_blank" href="http://www.aiga.org/belong">active AIGA membership</a>.
                </div>
            </div>

            <div class="cat-plugin-fluid-grid grid infinity-fetcher-grid"
                cat_plugin_fluid_grid_layout_mode="fitRows"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_BODY_FULL_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"

            >

                <?php
                $rendered = ikit_two_ajax('ikit_two_ajax_render_portfolios');
                ?>

                <?php if(empty($rendered) == false) { ?>

                    <?php echo $rendered; ?>



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