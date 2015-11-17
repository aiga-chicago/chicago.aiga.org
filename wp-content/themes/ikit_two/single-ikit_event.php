<?php
/**
 * Template Name: Single Event
 */

// If returning from SSO, forward to Eventbrite
$sso_key = $_REQUEST['key'];

if ( have_posts() ) while ( have_posts() ) : the_post();

    global $id;
    ikit_event_eventbrite_sso_redirect($id, $sso_key);

endwhile;

?>
<?php get_header();?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post();

    global $id;

    $ikit_event_meta = ikit_event_get_meta($id);
    $event = ikit_event_get_meta_normalized($id, $ikit_event_meta, null);

    $event_attendees_title = null;
    $event_attendees_error_no_attendees = null;
    $event_link_title = null;

    if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EVENTBRITE) {

        $event_link_title = 'REGISTER NOW';
        $event_attendees_title = 'Attendees';
        $event_attendees_error_no_attendees = 'No attendees at this time.';

    }
    else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_ETOUCHES) {

        if($ikit_event_meta->status == 'Sold Out') {
            $event_link_title = 'JOIN THE WAIT LIST';
        }
        else {
            $event_link_title = 'REGISTER NOW';
        }

        $event_attendees_title = 'Member Attendees';
        $event_attendees_error_no_attendees = 'No registered members at this time.';

    }

    // Determine whether is an upcoming or past event based on the event's end date
    $is_upcoming_event = false;
    $post_date = strtotime( mysql2date('Y-m-d', $event['end_date_raw']) );
    $current_date = strtotime( date( 'Y-m-d' ) );
    if($post_date >= $current_date) {
        $is_upcoming_event = true;
    }

    $display_register_link = true;
    $registration_type = get_post_meta($id, IKIT_CUSTOM_FIELD_IKIT_EVENT_REGISTRATION_TYPE, true);
    if($registration_type == IKIT_CUSTOM_FIELD_IKIT_EVENT_REGISTRATION_TYPE_DISABLED) {
        $display_register_link = false;
    }

    $start_date_abbr = mysql2date('D, M j, Y', get_gmt_from_date($event['start_date_raw']), false);
    $end_date_abbr = mysql2date('D, M j, Y', get_gmt_from_date($event['end_date_raw']), false);

    $additional_information = get_post_meta($id, IKIT_CUSTOM_FIELD_IKIT_EVENT_ADDITIONAL_INFORMATION, true);

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

                <?php if($is_upcoming_event && $display_register_link) { ?>

                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <?php if($event['service'] == IKIT_EVENT_SERVICE_EVENTBRITE && $event['eventbrite_sync_data']) { ?>
                                <div class="page-layout-4-tool-actions">
                                    <div class="box-button-container box-button-1-container">
                                        <a class="eventbrite-member-registration-link box-button-1" target="<?php echo $event['url_target'];?>" href="<?php echo $event['url'];?>">MEMBER</a>
                                    </div>
                                    <div class="box-button-container box-button-1-container">
                                        <a class="eventbrite-non-member-registration-link box-button-1" target="<?php echo $event['url_target'];?>" href="<?php echo $ikit_event_meta->url; ?>">NON MEMBER</a>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="page-layout-4-tool-actions">
                                    <div class="box-button-container box-button-1-container">
                                        <a class="etouches-registration-link box-button-1" target="<?php echo $event['url_target'];?>" href="<?php echo $event['url'];?>"><?php echo $event_link_title; ?></a>
                                    </div>
                                </div>
                            <?php } ?>
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

                        <div class="event-description">
                            <?php echo $event['description']; ?>
                        </div>

                        <?php if(empty($additional_information) == false) { ?>
                            <div class="additional-information"><?php echo wpautop(do_shortcode($additional_information)); ?></div>
                        <?php } ?>
                    </div>
                </div>
            </td>
            <td class="page-layout-4-sidebar">
                <?php dynamic_sidebar('single-ikit-event-sidebar');?>
            </td>
            </tr>
            </table>

        </td>

    </tr>
    </table>

    <div class="page-layout-4-sub-content attendees clearfix">
        <div class="page-layout-4-sub-content-inner">

            <table>
            <tr>
            <td class="page-layout-4-sub-content-title-col">

            <div class="cat-plugin-fluid-grid-item grid-item page-layout-4-sub-content-title">
                <div class="grid-item-inner">
                    Attendees
                </div>
            </div>

            </td>
            <td class="page-layout-4-sub-content-content-col">

            <div id="ajax-hook-event-attendees" class="cat-plugin-fluid-grid grid"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="4,3,3,1"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
            >

                <div class="attendees-loading cat-plugin-fluid-grid-item grid-item"><div class="grid-item-inner">Loading...</div></div>

            </div>

            </td>
            </tr>
            </table>

        </div>
    </div>

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

<script type="text/javascript">

jQuery.single_ikit_event = function() {

};

jQuery.single_ikit_event.onDomReady = function() {

    $.ajax({
        type: 'POST',
        url: '<?php echo IKIT_AJAX_URL; ?>',
        data: { 'action': 'ikit_two_ajax_get_event_attendees', event_id: '<?php echo $event['id']; ?>', event_service: '<?php echo $event['service']; ?>' },
        context: document.body,
        success: function(data) {

            $('.attendees-loading').addClass('grid-item-hidden');

            var attendees = $.parseJSON(data);

            // Destroy grid
            $('#ajax-hook-event-attendees').find('.grid-item').not('.page-layout-2-sub-content-title').remove(); // Do no remove the title
            jQuery.cat.plugin.fluidGrid.isotope.destroy('#ajax-hook-event-attendees');

            if(attendees.length > 0) {

                var buf = [];
                for(var i=0;i<attendees.length;i++) {

                    if(i % 10 == 0) {
                        if(i != 0) {
                            buf.push('</div></div>');
                        }
                        buf.push('<div class="cat-plugin-fluid-grid-item grid-item"><div class="grid-item-inner">');
                    }

                    buf.push('<div>' + attendees[i] + '</div>');
                }

                $('#ajax-hook-event-attendees').append(buf.join(""));

            }
            else {
                $('#ajax-hook-event-attendees').append('<div class="cat-plugin-fluid-grid-item grid-item"><div class="grid-item-inner"><?php echo $event_attendees_error_no_attendees; ?></div></div>');
            }

            // Create grid
            jQuery.ikit_two.grid.layout();
            jQuery.cat.plugin.fluidGrid.isotope.create('#ajax-hook-event-attendees');

        },
        error: function() {

            $('.attendees-loading').addClass('grid-item-hidden');

            // Destroy grid
            $('#ajax-hook-event-attendees').html('');
            jQuery.cat.plugin.fluidGrid.isotope.destroy('#ajax-hook-event-attendees');

            $('#ajax-hook-event-attendees').append('<div class="cat-plugin-fluid-grid-item grid-item"><div class="grid-item-inner"><?php echo $event_attendees_error_no_attendees; ?></div></div>');

            // Create grid
            jQuery.ikit_two.grid.layout();
            jQuery.cat.plugin.fluidGrid.isotope.create('#ajax-hook-event-attendees');

        }

      });

};

$(document).ready(function() {
    jQuery.single_ikit_event.onDomReady();
});

</script>

<?php get_footer();?>