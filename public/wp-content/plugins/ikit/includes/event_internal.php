<?php

/**
 * Main file for internal events, these are exposed to the admin facing as Community Events.
 */

define('IKIT_EVENT_INTERNAL_DB_VERSION', '1.02');
define('IKIT_EVENT_INTERNAL_TABLE_NAME', 'ikit_event_internal');

global $ikit_event_internal_db_version;
$ikit_event_internal_db_version = IKIT_EVENT_INTERNAL_DB_VERSION;


/**
 * Install or update plugin by creating database tables
 */
function ikit_event_internal_install() {

    global $wpdb;
    global $ikit_event_internal_db_version;

    $event_internal_table_name = $wpdb->prefix . IKIT_EVENT_INTERNAL_TABLE_NAME;
    $installed_ver = get_option( "ikit_event_internal_db_version" );

    if($installed_ver != $ikit_event_internal_db_version) {

       require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

       $sql = "CREATE TABLE " . $event_internal_table_name . " (
            id varchar(255) NOT NULL,
            start_date datetime DEFAULT '0000-00-00 00:00:00',
            end_date datetime DEFAULT '0000-00-00 00:00:00',
            service tinytext,
            start_time time DEFAULT '00:00:00',
            end_time time DEFAULT '00:00:00',
            url tinytext,
            url_name tinytext,
            location_name tinytext,
            location_address_1 tinytext,
            location_address_2 tinytext,
            location_city tinytext,
            location_state_province tinytext,
            location_postal_code tinytext,
            location_country tinytext,

            PRIMARY KEY (id),
            UNIQUE KEY id (id)

        ) CHARACTER SET utf8;";
        dbDelta($sql);

        update_option("ikit_event_internal_db_version", $ikit_event_internal_db_version);
    }

}

function ikit_event_internal_update_db_check() {
    global $ikit_event_internal_db_version;
    if (get_site_option('ikit_event_internal_db_version') != $ikit_event_internal_db_version) {
        ikit_event_internal_install();
    }
}
add_action('wp_loaded', 'ikit_event_internal_update_db_check');

/**
 * Upon saving, update the database entry for this event where the details are stored,
 * this is performed to match with the storage method for all other events,
 * even though we could simply have used WordPress meta data.
 */
add_action( 'save_post', 'ikit_event_internal_save_post');
add_action( 'wp_insert_post', 'ikit_event_internal_save_post') ;
function ikit_event_internal_save_post($post_id) {

    $post = get_post($post_id);
    if($post->post_type == IKIT_POST_TYPE_IKIT_EVENT_INTERNAL) {

        ikit_add_or_update_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, IKIT_EVENT_SERVICE_INTERNAL, true);
        ikit_add_or_update_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, 'Live', true);

        global $wpdb;
        $event_internal_table_name = $wpdb->prefix . IKIT_EVENT_INTERNAL_TABLE_NAME;

        $start_date = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, true);
        $end_date = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, true);
        $location_name = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_NAME, true);
        $location_address_1 = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_ADDRESS_1, true);
        $location_address_2 = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_ADDRESS_2, true);
        $location_city = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_CITY, true);
        $location_state_province = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_STATE_PROVINCE, true);
        $location_postal_code = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_POSTAL_CODE, true);
        $location_country = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_COUNTRY, true);
        $url = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_URL, true);
        $url_name = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_URL_NAME, true);
        $start_time_hour = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_START_TIME_HOUR, true);
        $start_time_minute = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_START_TIME_MINUTE, true);
        $end_time_hour = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_END_TIME_HOUR, true);
        $end_time_minute = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_END_TIME_MINUTE, true);

        // Create the start and end times based on the repeater fields
        $start_time = null;
        if($start_time_hour != 'null' && $start_time_minute != 'null' && empty($start_time_hour) == false && empty($start_time_minute) == false) {
            $start_time = $start_time_hour . ':' . $start_time_minute . ':00';
        }
        $end_time = null;
        if($end_time_hour != 'null' && $end_time_minute != 'null' && empty($end_time_hour) == false && empty($end_time_minute) == false) {
            $end_time = $end_time_hour . ':' . $end_time_minute . ':00';
        }

        if(empty($start_date) == false && empty($end_date) == false) {

            // Create or update the associated database record
            $event_data = array(
                'id' => $post_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'service' => IKIT_EVENT_SERVICE_INTERNAL,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'location_name' => $location_name,
                'location_address_1' => $location_address_1,
                'location_address_2' => $location_address_2,
                'location_city' => $location_city,
                'location_state_province' => $location_state_province,
                'location_postal_code' => $location_postal_code,
                'location_country' => $location_country,
                'url' => $url,
                'url_name' => $url_name,
            );

            $rows_affected = $wpdb->query("select id from $event_internal_table_name where id = '$post_id'");

            if($rows_affected == 0) {
                $rows_affected = $wpdb->insert($event_internal_table_name, $event_data);
            }
            else {
                $rows_affected = $wpdb->update($event_internal_table_name, $event_data, array('id' => $post_id));
            }

        }

    }

}

?>