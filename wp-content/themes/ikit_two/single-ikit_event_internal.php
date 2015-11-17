<?php
/**
 * Template Name: Single Event Internal
 */

?>
<?php get_header();?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post();

    global $id;

    $ikit_event_meta = ikit_event_get_meta($id);
    $event = ikit_event_get_meta_normalized($id, $ikit_event_meta, null);
    $event_link_title = $ikit_event_meta->url_name;

    if(empty($event_link_title)) {
        $event_link_title = 'More Information';
    }

    $start_date_abbr = mysql2date('D, M j, Y', get_gmt_from_date($event['start_date_raw']), false);
    $end_date_abbr = mysql2date('D, M j, Y', get_gmt_from_date($event['end_date_raw']), false);

    ?>


    <?php if(empty($event['image'])) { ?>

        <div class="page-header-5">
            <div class="page-header-5-title">
                <?php the_title(); ?>
            </div>
        </div>

    <?php } else { ?>

        <div class="page-header-4">
            <div class="page-header-4-overlay"><img src="<?php bloginfo('template_url'); ?>/images/detail_background.png"/></div>
            <?php if(ikit_two_browser_supports_fixed_background_image($g_theme_options) == false) { ?>
                <div class="page-header-4-image hero-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $event['image']; ?>"></div>
            <?php } else { ?>
                <div class="page-header-4-image hero-image fixed-background-image" style="background-image:url('<?php echo $event['image']; ?>');"></div>
            <?php } ?>

            <div class="page-header-4-title-container">
                <div class="page-header-4-title">
                    <?php the_title(); ?>
                </div>
            </div>

        </div>

    <?php } ?>


    <table class="page-layout-4">
    <tr>

        <td class="page-layout-4-tools">

            <div class="cat-plugin-fluid-grid grid"
                cat_plugin_fluid_grid_layout_mode="fitRows"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_TOOLS_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
            >

                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool first">
                            <div class="page-layout-4-tool-header">When</div>
                            <div class="page-layout-4-tool-text">
                            <span class="event-date"><?php echo $start_date_abbr;?> <?php if($event['end_date'] != $event['start_date']) { echo ' - ' . $end_date_abbr; } ?></span>
                            <?php if($event['start_time_raw'] != '00:00:00') { ?>
                                <span class="event-time"><?php echo date('g:i A', $event['start_time']); ?> - <?php echo date('g:i A', $event['end_time']); ?></span>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(empty($event['location_name']) == false || empty($event['location_city']) == false) { ?>
                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-header">Where</div>
                            <div class="page-layout-4-tool-text">
                            <div class="event-location">
                                <div class="event-location-inner">
                                    <div class="event-location-name"><?php echo $event['location_name']; ?></div>
                                    <div class="event-location-address1"><?php echo $event['location_address1']; ?></div>
                                    <div class="event-location-address2"><?php echo $event['location_address2']; ?></div>
                                    <div class="event-location-city-state-zip"><?php echo $event['location_city']; ?><?php if(empty($event['location_city']) == false && empty($event['location_state']) == false) { echo ', '; } ?> <?php echo $event['location_state']; ?> <?php if($event['location_postal_code'] != '0') { echo $event['location_postal_code']; } ?></div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(empty($event['url']) == false) { ?>
                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-actions">
                                <div class="box-button-container box-button-1-container">
                                    <a class="event-link box-button-1" target="<?php echo $event['url_target'];?>" href="<?php echo $event['url']; ?>"><?php echo $event_link_title; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

            </div>

            <div class="page-layout-4-tools-footer"></div>

        </td>

        <td class="page-layout-4-content">

            <table>
            <tr>
            <td class="page-layout-4-body">
                <div class="page-layout-body-pad">
                    <div class="page-layout-body-paragraph-text wp-editor">
                        <?php the_content(); ?>
                    </div>
                </div>
            </td>
            <td class="page-layout-4-sidebar">
                <?php dynamic_sidebar('single-ikit-event-internal-sidebar');?>
            </td>
            </tr>
            </table>

        </td>

    </tr>
    </table>

    <div class="page-layout-4-sub-content comments">
        <div class="page-layout-4-sub-content-inner">
            <div class="cat-plugin-fluid-grid grid"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
            >

                <div class="cat-plugin-fluid-grid-item grid-item page-layout-4-sub-content-title">
                    <div class="grid-item-inner">
                        Comments
                    </div>
                </div>

                <div class="cat-plugin-fluid-grid-item grid-item" cat_plugin_fluid_grid_item_size="3">
                    <div class="grid-item-inner">
                        <?php include(TEMPLATEPATH . '/post_comments.php'); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php
endwhile;?>

<?php get_footer();?>