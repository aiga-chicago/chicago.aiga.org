<?php
/**
 * Template Name: Jobs
 */
?>
<?php get_header();?>

<script type="text/javascript">

jQuery.jobs = function() {

};

jQuery.jobs.onStartDomReady = function() {

    jQuery.ikit_two.infinityFetcher.fetchingUrl = "<?php echo IKIT_AJAX_URL; ?>";
    jQuery.ikit_two.infinityFetcher.fetchButton = $('.infinity-fetcher-fetch-button')
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['action'] = 'ikit_two_ajax_render_jobs';

    // Hide fetch button if no more pages
    if(parseInt($('.infinity-fetcher-grid .data').attr('num_pages')) <= parseInt($('.infinity-fetcher-grid .data').attr('page'))) {
        $('.infinity-fetcher-fetch-button').hide();
    }

};

jQuery.jobs.onDomReady = function() {

    $('.expertise-area-filter').click(function() {
        $('.expertise-area-filter').removeClass('active');
        $(this).addClass('active');
        jQuery.ikit_two.infinityFetcher.filter('expertise_area', $(this).attr('value'));
    });

    $('.job-level-filter').click(function() {
        $('.job-level-filter').removeClass('active');
        $(this).addClass('active');
        jQuery.ikit_two.infinityFetcher.filter('job_level', $(this).attr('value'));
    });

};

jQuery.jobs.afterFetch = function(response, page, numPages) {

    jQuery.ikit_two.infinityFetcher.afterFetchGrid('.infinity-fetcher-grid', response, page, numPages, false);

};

$(document).ready(function() {
    jQuery.jobs.onDomReady();
});

jQuery.ikit_two.infinityFetcher.afterFetchFunctions.push(jQuery.jobs.afterFetch);
jQuery.ikit_two.onStartDomReadyFunctions.push(jQuery.jobs.onStartDomReady);

</script>

<div class="page-header-3">
    <table>
    <tr>
        <td class="page-header-3-section-title">
            <a href="<?php echo ikit_get_request_url(false); ?>">Job Board</a>
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
                        <div class="page-layout-4-tool-header">Categories</div>
                        <div class="page-layout-4-tool-filters">
                        <div class="page-layout-4-tool-filter expertise-area-filter active" value="">All</div>
                        <?php

                            global $wpdb;
                            $job_table_name = $wpdb->prefix . IKIT_JOB_TABLE_NAME;
                            $job_expertise_areas = $wpdb->get_results("SELECT DISTINCT(expertise_area) FROM $job_table_name GROUP BY expertise_area order by expertise_area ASC");
                            foreach($job_expertise_areas as $job_expertise_area) {

                                ?>
                                <div class="page-layout-4-tool-filter expertise-area-filter" value="<?php echo $job_expertise_area->expertise_area; ?>"><?php echo $job_expertise_area->expertise_area; ?></div>
                                <?php
                            }

                        ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cat-plugin-fluid-grid-item grid-item">
                <div class="grid-item-inner">
                    <div class="page-layout-4-tool">
                        <div class="page-layout-4-tool-header">Type</div>
                        <div class="page-layout-4-tool-filters">
                        <div class="page-layout-4-tool-filter job-level-filter active" value="">All</div>
                        <?php

                            global $wpdb;
                            $job_table_name = $wpdb->prefix . IKIT_JOB_TABLE_NAME;
                            $job_levels = $wpdb->get_results("SELECT DISTINCT(job_level) FROM $job_table_name GROUP BY expertise_area order by job_level ASC");
                            foreach($job_levels as $job_level) {

                                ?>
                                <div class="page-layout-4-tool-filter job-level-filter" value="<?php echo $job_level->job_level; ?>"><?php echo $job_level->job_level; ?></div>
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
                    To post a job, visit the <a target="_blank" href="http://designjobs.aiga.org">Design Jobs site</a>; one of the <a target="_blank" href="http://www.aiga.org/benefits/">benefits</a> of AIGA membership at the Sustaining level and above is discounted job posting rates.
                    <BR/><BR/>
                    Only AIGA members can see the full details of the positions listed below (you must be logged in to your AIGA account). And you can see listings from other geographical areas by visiting the main <a target="_blank" href="http://designjobs.aiga.org">Design Jobs site</a>. AIGA Friends and nonmembers, consider <a target="_blank" href="http://www.aiga.org/belong">joining</a> to reap the full benefits of this service.
                </div>
            </div>

            <div class="cat-plugin-fluid-grid grid infinity-fetcher-grid"
                cat_plugin_fluid_grid_layout_mode="fitRows"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_BODY_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
            >

                <?php
                $rendered = ikit_two_ajax('ikit_two_ajax_render_jobs');
                ?>

                <?php if(empty($rendered) == false) { ?>

                    <?php echo $rendered; ?>

                <?php } ?>

            </div>

        </td>
        <td class="page-layout-4-sidebar">
            <?php dynamic_sidebar('jobs-sidebar');?>
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