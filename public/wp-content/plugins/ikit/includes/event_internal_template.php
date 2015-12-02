<?php
/**
 * Internet Kit Events Template Functions specific to internal events.
 *
 * @package Internet Kit
 * @subpackage Template
 */

/**
 * Retrieve the internal meta data given the post ID for the event
 */
function ikit_event_internal_get_meta($ikit_event_id) {

    global $wpdb;
    $event_table_name = $wpdb->prefix . IKIT_EVENT_INTERNAL_TABLE_NAME;

    $sql = "select * from $event_table_name where id = '$ikit_event_id' ";

    return $wpdb->get_row($sql);


}

?>