<?php
/**
 * Internet Kit Events Template Functions specific to Eventbrite
 *
 * @package Internet Kit
 * @subpackage Template
 */

/**
 * Retrieve the eventbrite meta data given the post ID for the event
 */
function ikit_event_eventbrite_get_meta($ikit_event_id) {

    $event_id = get_post_meta($ikit_event_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_ID, true);

    global $wpdb;
    $event_table_name = $wpdb->prefix . IKIT_EVENT_EVENTBRITE_TABLE_NAME;

    $sql = "select * from $event_table_name where id = $event_id ";
    return $wpdb->get_row($sql);

}

?>