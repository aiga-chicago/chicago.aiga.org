<?php


/**
 * Add custom post edit widget to sync with eventbrite
 */
add_action('add_meta_boxes', 'ikit_event_add_meta_boxes');

function ikit_event_add_meta_boxes() {
    $screens = array(IKIT_POST_TYPE_IKIT_EVENT);
    foreach ($screens as $screen) {
        add_meta_box(
            'ikit_event',
            __( 'Event Information', 'ikit_event' ),
            'ikit_event_add_meta_box_integration',
            $screen
        );
    }
}

/**
 * Delete the associated database record
 */
add_action('before_delete_post', 'ikit_event_before_delete_post');
function ikit_event_before_delete_post($post_id) {

    $post_type = get_post_type($post_id);

    if($post_type == IKIT_POST_TYPE_IKIT_EVENT || $post_type == IKIT_POST_TYPE_IKIT_EVENT_INTERNAL || $post_type == IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL) {
        $meta = ikit_event_get_meta($post_id);

        global $wpdb;

        if($meta->service == IKIT_EVENT_SERVICE_EVENTBRITE) {
            $table_name = $wpdb->prefix . IKIT_EVENT_EVENTBRITE_TABLE_NAME;
            $wpdb->query(sprintf("delete from $table_name where id = '%s'", $meta->id));
        }
        else if($meta->service == IKIT_EVENT_SERVICE_INTERNAL) {
            $table_name = $wpdb->prefix . IKIT_EVENT_INTERNAL_TABLE_NAME;
            $wpdb->query(sprintf("delete from $table_name where id = '%s'", $meta->id));
        }
        else if($meta->service == IKIT_EVENT_SERVICE_EXTERNAL) {
            $table_name = $wpdb->prefix . IKIT_EVENT_EXTERNAL_TABLE_NAME;
            $wpdb->query(sprintf("delete from $table_name where id = '%s'", $meta->id));
        }
        else if($meta->service == IKIT_EVENT_SERVICE_ETOUCHES) {
            $table_name = $wpdb->prefix . IKIT_EVENT_ETOUCHES_TABLE_NAME;
            $wpdb->query(sprintf("delete from $table_name where id = '%s'", $meta->id));
        }
    }
}

function ikit_event_add_meta_box_integration($post) {

    $event_service = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, true);

    if($event_service == IKIT_EVENT_SERVICE_EVENTBRITE) {

        $event_meta = ikit_event_eventbrite_get_meta($post->ID);
        $eventbrite_id = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_ID, true);
        $eventbrite_sync_data = unserialize(get_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_DATA, true));
        $eventbrite_sync_enabled = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_ENABLED, true);

        // Sync validation
        if($eventbrite_sync_enabled) {
            if($eventbrite_sync_data) {
                // Do nothing, correctly synced
            }
            else {
                ?>
                <div class="error"><p><strong>Automated member tickets are incorrectly configured with Eventbrite, if you would still like to automate tickets, please verify the following before saving:</strong><ul><li>- The event start date is not in the past</li><li>- There are no tickets currently existing on the event</li></ul></p></div>
                <?php
            }
        }

        ?>
        <div class="wp-box">
        <div class="inner">

        <table class="widefat">
        <tr>
            <td>
            Service
            </td>
            <td>
            Eventbrite
            </td>
        </tr>
        <tr>
            <td>
                Automate member tickets
                <div class="note">Automatically creates tickets for each member type on Eventbrite. Login will be required for all member type tickets. If you would like to manually create your tickets do not check this box.</div>
            </td>
            <td>
                <input type="checkbox" name="<?php echo IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_ENABLED; ?>" <?php if($eventbrite_sync_enabled) { echo 'checked'; } ?>/>
            </td>
        </tr>
        <tr>
            <td>
            Status
            </td>
            <td>
            <?php echo $event_meta->status; ?>
            </td>
        </tr>
        <tr>
            <td>
            Start Datetime
            </td>
            <td>
            <?php echo ikit_date_without_time($event_meta->start_date) . ' ' . $event_meta->start_time; ?>
            </td>
        </tr>
        <tr>
            <td>
            End Datetime
            </td>
            <td>
            <?php echo ikit_date_without_time($event_meta->end_date) . ' ' . $event_meta->end_time; ?>
            </td>
        </tr>
        </table>

        </div>
        </div>

        <?php

    }
    else if($event_service == IKIT_EVENT_SERVICE_ETOUCHES) {

        $event_meta = ikit_event_get_meta($post->ID);

        ?>

        <div class="wp-box">
        <div class="inner">
        <table class="widefat">
        <tr>
            <td>
            Service
            </td>
            <td>
            etouches
            </td>
        </tr>
        <tr>
            <td>
            Status
            </td>
            <td>
            <?php echo $event_meta->status; ?>
            </td>
        </tr>
        <tr>
            <td>
            Start Datetime
            </td>
            <td>
            <?php echo ikit_date_without_time($event_meta->start_date) . ' ' . $event_meta->start_time; ?>
            </td>
        </tr>
        <tr>
            <td>
            End Datetime
            </td>
            <td>
            <?php echo ikit_date_without_time($event_meta->end_date) . ' ' . $event_meta->end_time; ?>
            </td>
        </tr>
        </table>
        </div>
        </div>

        <?php

    }


}

?>