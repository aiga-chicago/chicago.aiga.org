<?php
/**
 * The Template for displaying all single events
 *
 * @package WordPress
 * @subpackage Internet_Kit
 * @since Internet Kit One 1.0.0
 *
 */

// If returning from SSO, forward to Eventbrite
$sso_key = $_REQUEST['key'];

if ( have_posts() ) while ( have_posts() ) : the_post();

    global $id;
    ikit_event_eventbrite_sso_redirect($id, $sso_key);

endwhile;

?>

<?php get_header();?>

<div class="box-container">
<div class="box">

<?php

// The single page is a wordpress built-in, therefore we can't get
// the page custom field section like normal, so use the constants since
// we already know for sure it's part of news

$ikit_section_events = ikit_get_post_by_slug(IKIT_SLUG_IKIT_SECTION_EVENT, IKIT_POST_TYPE_IKIT_SECTION);
ikit_one_render_banner_header($ikit_section_events);

?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post();

    global $id;

    $ikit_event_meta = ikit_event_get_meta($id);
    $event = ikit_event_get_meta_normalized($id, $ikit_event_meta, ikit_one_get_event_image_default());

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

    $additional_information = get_post_meta($id, IKIT_CUSTOM_FIELD_IKIT_EVENT_ADDITIONAL_INFORMATION, true);

    ?>

    <div class="box-section">

        <div class="box-top-empty-spacer"></div>

        <div class="box-section-title"><h1><?php the_title(); ?></h1></div>
        <div class="box-section-title-detail"><?php echo $event['start_date'];?> <?php if($event['end_date'] != $event['start_date']) { echo ' - ' . $event['end_date']; } ?> <?php if(!empty($event['location_city'])) { echo '/ ' . $event['location_city']; } ?></div>

        <div class="box-section-image"><img onerror="this.src='<?php echo ikit_one_get_event_image_default(); ?>'" src="<?php if($event['image'] != null) { echo $event['image']; } ?>"></img></div>

        <div class="box-section-body">

        <table class="box-section-actions">
        <tr>
        <td class="box-section-actions-col0">

            <!-- Do not show a register link if it's a past event -->
            <?php if($is_upcoming_event && $display_register_link) { ?>

                <?php if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EVENTBRITE && $event['eventbrite_sync_data']) { ?>

                    <table class="event-single-eventbrite-registration">
                        <tr>
                        <td>
                        <select class="custom-select event-single-eventbrite-registration-select">
                            <option value="">SELECT REGISTRATION</option>
                            <option value="member">Member</option>
                            <option value="non_member">Non-Member</option>
                        </select>
                        </td>
                        </tr>
                        <tr>
                        <td>
                        <a class="event-single-eventbrite-registration-link" href="javascript:void(0);" non_member_registration_url="<?php echo $ikit_event_meta->url; ?>" member_registration_url="<?php echo $event['url'];?>"><?php echo $event_link_title; ?></a></h5>
                        </td>
                        </tr>
                    </table>


                <?php } else { ?>

                    <h5><a class="event-single-registration-link emphasis external-link-unstyled" target="<?php echo $event['url_target'];?>" href="<?php echo $event['url'];?>"><?php echo $event_link_title; ?></a></h5>
                <?php } ?>


            <?php } ?>

        </td>
        <td class="box-section-actions-col1">

            <!-- Share this -->
            <?php

            $twitter_username = $g_options[IKIT_PLUGIN_OPTION_TWITTER_USERNAME];
            $share_title = htmlspecialchars(strip_tags($post->post_title));
            $share_description = htmlspecialchars(strip_tags($post->post_content));

            ?>

            <script type="text/javascript">var switchTo5x=true;</script>
            <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
            <script type="text/javascript">stLight.options({publisher: "ur-db07c463-a921-51b-5402-47e2c83c7d8d",onhover: false}); </script>

            <span class="st_twitter_custom share-button" st_via="<?php echo $twitter_username; ?>" st_url="<?php the_permalink(); ?>" st_title="<?php echo $share_title; ?>" st_image="<?php echo $event['image']; ?>"><img class="highlightable" id="st_twitter_custom" src="<?php echo get_bloginfo('template_url') . '/images/share_twitter.png'; ?>"></img></span>
            <span class="st_facebook_custom share-button" st_via="<?php echo $twitter_username; ?>" st_url="<?php the_permalink(); ?>" st_title="<?php echo $share_title; ?>" st_image="<?php echo $event['image']; ?>"><img class="highlightable" id="st_facebook_custom" src="<?php echo get_bloginfo('template_url') . '/images/share_facebook.png'; ?>"></img></span>
            <span class="st_email_custom share-button" st_via="<?php echo $twitter_username; ?>" st_url="<?php the_permalink(); ?>" st_title="<?php echo $share_title; ?>" st_image="<?php echo $event['image']; ?>"><img class="highlightable" id="st_email_custom" src="<?php echo get_bloginfo('template_url') . '/images/share_more.png'; ?>"></img></span>

        </td>
        </tr>
        </table>

        </div>
        <div class="box-section-divider"></div>

        <div class="box-section-body"><?php echo $event['description']; ?></div>



        <div class="box-section-spacer"></div>

        <div class="box-section-inset">

            <div class="box-section-heading">DETAILS</div>

            <div class="box-section-body">

                <div class="event-single-date"><?php echo $event['start_date'];?> <?php if($event['end_date'] != $event['start_date']) { echo ' - ' . $event['end_date']; } ?></div>
                <div class="event-single-time">
                    <?php if($event['start_time_raw'] != '00:00:00') { ?>
                        <?php echo date('g:i A', $event['start_time']); ?> - <?php echo date('g:i A', $event['end_time']); ?>
                    <?php } ?>
                </div>

                <div class="event-single-location-name"><?php echo $event['location_name']; ?></div>
                <div class="event-single-location-address1"><?php echo $event['location_address1']; ?></div>
                <div class="event-single-location-address2"><?php echo $event['location_address2']; ?></div>
                <div class="event-single-location-city-state-zip"><?php echo $event['location_city']; ?>, <?php echo $event['location_state']; ?> <?php if($event['location_postal_code'] != '0') { echo $event['location_postal_code']; } ?></div>

            </div>

            <?php if($is_upcoming_event && $display_register_link) { ?>

            <div class="box-section-divider"></div>
            <div class="box-section-body">

                <?php if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EVENTBRITE && $event['eventbrite_sync_data']) { ?>

                    <table class="event-single-eventbrite-registration">
                        <tr>
                        <td>
                        <select class="custom-select event-single-eventbrite-registration-select">
                            <option value="">SELECT REGISTRATION</option>
                            <option value="member">Member</option>
                            <option value="non_member">Non-Member</option>
                        </select>
                        </td>
                        </tr>
                        <tr>
                        <td>
                        <a class="event-single-eventbrite-registration-link" href="javascript:void(0);"  non_member_registration_url="<?php echo $ikit_event_meta->url; ?>" member_registration_url="<?php echo $event['url'];?>"><?php echo $event_link_title; ?></a></h5>
                        </td>
                        </tr>
                    </table>

                <?php } else { ?>

                    <h5><a class="event-single-registration-link emphasis external-link-unstyled" target="<?php echo $event['url_target'];?>" href="<?php echo $event['url'];?>"><?php echo $event_link_title; ?></a></h5>

                <?php } ?>

            </div>

            <?php } ?>

            <?php if(empty($additional_information) == false) { ?>
                <div class="box-section-divider">
                    <div class="wp-editor">
                        <?php echo wpautop(do_shortcode($additional_information)); ?>
                    </div>
                </div>
            <?php } ?>

        </div>

        <?php if($g_theme_options[IKIT_ONE_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED]) { ?>
            <div class="box-section-inset2 event-attendees">

                <div class="box-section-heading"><?php echo $event_attendees_title; ?></div>

                <div class="wp-editor">
                    <div id="ajax-hook-event-attendees"><div class="box-section-body"><img src="<?php bloginfo('template_url'); ?>/images/activity_indicator_event_attendees.gif"/></div></div>
                </div>
            </div>
        <?php } ?>

        <?php if($g_theme_options[IKIT_ONE_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT]) { ?>
            <?php include(TEMPLATEPATH . '/post_comments.php'); ?>
        <?php } ?>

    </div>

    <script type="text/javascript">

    $(document).ready(function() {

        $('.event-single-eventbrite-registration-link').click(function() {

            var containerEl = $(this).closest('.event-single-eventbrite-registration');
            var registrationType = containerEl.find('select').val();
            if(registrationType == '') {
                alert('Please select a registration type before continuing.');
            }
            else if(registrationType == 'member') {
                window.location.href = $(this).attr('member_registration_url');
            }
            else if(registrationType == 'non_member') {
                window.location.href = $(this).attr('non_member_registration_url');
            }

        });

        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: { 'action': 'ikit_one_ajax_get_event_attendees', event_id: '<?php echo $event['id']; ?>', event_service: '<?php echo $event['service']; ?>' },
            context: document.body,
            success: function(data) {

                var attendees = $.parseJSON(data);
                console.log(attendees);

                $('#ajax-hook-event-attendees').html(''); // Clear loading

                if(attendees.length > 0) {

                    var buf = [];
                    buf.push('<table class="box-section-data-table">');

                    for(var i=0;i<attendees.length;i++) {
                        var className = '';
                        if(i==attendees.length-1) {
                            className = 'last-child';
                        }
                        buf.push('<tr class="' + className + '"><td>' + attendees[i] + '</td></tr>');
                    }

                    buf.push('</table>');

                    $('#ajax-hook-event-attendees').append(buf.join(""));

                }
                else {
                    $('#ajax-hook-event-attendees').append('<div class="box-section-body"><?php echo $event_attendees_error_no_attendees; ?></div>');
                }

            },
            error: function() {

                $('#ajax-hook-event-attendees').html(''); // Clear loading
                $('#ajax-hook-event-attendees').append('<div class="box-section-body"><?php echo $event_attendees_error_no_attendees; ?></div>');
            }

          });

    });

    </script>

    <?php
endwhile;?>

</div>
</div>

<div class="box-close"></div>



<?php get_sidebar();?>
<?php get_footer();?>