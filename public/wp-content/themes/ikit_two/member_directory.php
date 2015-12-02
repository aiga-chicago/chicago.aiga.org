<?php
/**
 * Template Name: Member Directory
 */
?>
<?php get_header();?>

<script type="text/javascript">

jQuery.member_directory = function() {

};

jQuery.member_directory.onStartDomReady = function() {

    jQuery.ikit_two.infinityFetcher.contentContainer = $('.infinity-fetcher-grid');
    jQuery.ikit_two.infinityFetcher.fetchButton = $('.infinity-fetcher-fetch-button');
    jQuery.ikit_two.infinityFetcher.fetchingUrl = "<?php echo IKIT_AJAX_URL; ?>";
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['action'] = 'ikit_two_ajax_render_member_directory';

    // Hide fetch button if no more pages
    if(parseInt($('.infinity-fetcher-grid .data').attr('num_pages')) <= parseInt($('.infinity-fetcher-grid .data').attr('page'))) {
        $('.infinity-fetcher-fetch-button').hide();
    }

};

jQuery.member_directory.onDomReady = function() {

    $('.member-type-filter').click(function() {

        $('.member-type-filter').removeClass('active');
        $(this).addClass('active');

        jQuery.ikit_two.infinityFetcher.filter('member_type', $(this).attr('value'));

    });

};

jQuery.member_directory.afterFetchFinished = function(response, page, numPages) {

    jQuery.ikit_two.infinityFetcher.afterFetchGrid('.infinity-fetcher-grid', response, page, numPages, true);
    jQuery.cat.plugin.anystretchImage.layout();

};

jQuery.member_directory.onWindowResize = function() {

};

jQuery.ikit_two.infinityFetcher.afterFetchFinishedFunctions.push(jQuery.member_directory.afterFetchFinished);
jQuery.ikit_two.onStartDomReadyFunctions.push(jQuery.member_directory.onStartDomReady);

$(document).ready(function() {
    jQuery.member_directory.onDomReady();
});

$(window).resize(function() {
    jQuery.member_directory.onWindowResize();
});

</script>

<div class="page-header-3">
    <table>
    <tr>
        <td class="page-header-3-section-title">
            <?php ikit_two_render_toc_title($post); ?>
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
                        <div class="page-layout-4-tool-header">Member Type</div>
                        <div class="page-layout-4-tool-filters">
                        <div class="page-layout-4-tool-filter member-type-filter active" value="">All</div>
                        <?php
                        $member_types = ikit_member_types();
                        foreach($member_types as $member_type) {

                            ?>
                            <div class="page-layout-4-tool-filter member-type-filter" value="<?php echo $member_type ?>" href="javascript:void(0);"><?php echo ikit_member_type_display_name($member_type); ?></div>
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

            <div class="page-layout-4-body-description">
                <div class="page-layout-4-body-description-inner">
                    The membership directory is available for individual and personal uses only. Accessing address information acknowledges the user's acceptance of the <a target="_blank" href="http://www.aiga.org/designer-directory/">conditions of use</a> prohibiting commercial use.
                </div>
            </div>

            <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

                <?php
                $rendered = ikit_two_ajax('ikit_two_ajax_render_member_directory');
                ?>

                <?php if(empty($rendered) == false) { ?>

                    <div class="cat-plugin-fluid-grid grid infinity-fetcher-grid"
                        cat_plugin_fluid_grid_layout_mode="fitRows"
                        cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_BODY_NO_SIDEBAR_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                        cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                        cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
                    >
                        <?php echo $rendered; ?>
                    </div>

                <?php } ?>

            <?php endwhile; ?>

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