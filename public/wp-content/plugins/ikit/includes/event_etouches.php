<?php
/**
 * @package Internet_Kit
 */


/**
 * Main file for events feed from etouches
 */

define('IKIT_EVENT_ETOUCHES_DB_VERSION', '3.24');
define('IKIT_EVENT_ETOUCHES_TABLE_NAME', 'ikit_event');

define('IKIT_EVENT_ETOUCHES_API_AUTHORIZE_URL', 'https://www.eiseverywhere.com/api/v2/global/authorize.xml');
define('IKIT_EVENT_ETOUCHES_API_LIST_URL', 'https://www.eiseverywhere.com/api/v2/global/listEvents.xml');
define('IKIT_EVENT_ETOUCHES_API_GET_URL', 'https://www.eiseverywhere.com/api/v2/ereg/getEvent.xml');
define('IKIT_EVENT_ETOUCHES_API_SEARCH_URL', 'https://www.eiseverywhere.com/api/v2/global/searchEvents.xml');
define('IKIT_EVENT_ETOUCHES_API_SEARCH_ATTENDEES_URL', 'https://www.eiseverywhere.com/api/v2/ereg/searchAttendees.xml');
define('IKIT_EVENT_ETOUCHES_API_GET_ATTENDEE_URL', 'https://www.eiseverywhere.com/api/v2/ereg/getAttendee.xml');
define('IKIT_EVENT_ETOUCHES_ACCOUNT_KEY', 'ce846f73d92db230cc889490c072b96679c1ef60');
define('IKIT_EVENT_ETOUCHES_ACCOUNT_ID', '2824');


global $ikit_event_etouches_db_version;
$ikit_event_etouches_db_version = IKIT_EVENT_ETOUCHES_DB_VERSION;

/**
 * Install or update plugin by creating database tables
 */
function ikit_event_etouches_install() {

    global $wpdb;
    global $ikit_event_etouches_db_version;

    $event_table_name = $wpdb->prefix . IKIT_EVENT_ETOUCHES_TABLE_NAME;
    $installed_ver = get_option( "ikit_event_etouches_db_version" );

    if($installed_ver != $ikit_event_etouches_db_version) {

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE " . $event_table_name . " (
            id mediumint(9) NOT NULL,
            timezone_id mediumint(9),
            name tinytext NOT NULL,
            code tinytext,
            start_date datetime DEFAULT '0000-00-00 00:00:00',
            end_date datetime DEFAULT '0000-00-00 00:00:00',
            status tinytext,
            description text,
            program_manager tinytext,
            default_language tinytext,
            creation_date datetime DEFAULT '0000-00-00 00:00:00',
            last_modified_date datetime DEFAULT '0000-00-00 00:00:00',
            location_name tinytext,
            location_address1 tinytext,
            location_address2 tinytext,
            location_city tinytext,
            location_state tinytext,
            location_postcode mediumint(9),
            location_country tinytext,
            location_phone tinytext,
            location_email tinytext,
            location_map tinytext,
            url tinytext,
            start_time time DEFAULT '00:00:00',
            end_time time DEFAULT '00:00:00',
            close_date date DEFAULT '0000-00-00',
            close_time time DEFAULT '00:00:00',
            homepage_url tinytext,
            standard_currency tinytext,
            timezone_description tinytext,
            folder_id mediumint(9),
            folder_name tinytext,
            folder_parent_id mediumint(9),
            event_type tinytext,
            department tinytext,
            division tinytext,
            business_unit tinytext,
            client_contact tinytext,
            service tinytext,

            PRIMARY KEY (id),
            UNIQUE KEY id (id)

        ) CHARACTER SET utf8;";
        dbDelta($sql);

        update_option("ikit_event_etouches_db_version", $ikit_event_etouches_db_version);
    }

}

function ikit_event_etouches_update_db_check() {
    global $ikit_event_etouches_db_version;
    if (get_site_option('ikit_event_etouches_db_version') != $ikit_event_etouches_db_version) {
        ikit_event_etouches_install();
    }
}
add_action('wp_loaded', 'ikit_event_etouches_update_db_check');



/**
 * Pull etouches events and import into database
 */
function ikit_event_etouches_remote_fetch() {

    ini_set('max_execution_time', 300); // XXX Temporarily extend timeout to 5 minutes

    global $wpdb;
    $event_table_name = $wpdb->prefix . IKIT_EVENT_ETOUCHES_TABLE_NAME;

    global $g_options;
    $filter_folder_id = $g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_EVENT_FOLDER_ID];

    // Authorize with etouches
    $curl_auth_request = curl_init();
    curl_setopt($curl_auth_request, CURLOPT_URL, IKIT_EVENT_ETOUCHES_API_AUTHORIZE_URL . "?accountid=" . IKIT_EVENT_ETOUCHES_ACCOUNT_ID . "&key=" . IKIT_EVENT_ETOUCHES_ACCOUNT_KEY);
    curl_setopt($curl_auth_request, CURLOPT_RETURNTRANSFER, true);
    $curl_auth_response = curl_exec($curl_auth_request);
    curl_close($curl_auth_request);

    $auth_xml = simplexml_load_string($curl_auth_response);
    $session_key = (string)$auth_xml->accesstoken;

    $event_ids = array();

    // Get chapter events
    $curl_list_request = curl_init();
    curl_setopt($curl_list_request, CURLOPT_URL, IKIT_EVENT_ETOUCHES_API_SEARCH_URL . "?accesstoken=" . urlencode($session_key) . '&folderid=' . $filter_folder_id . '&startdate=1-1-1111');
    curl_setopt($curl_list_request, CURLOPT_RETURNTRANSFER, true);
    $curl_list_response = curl_exec($curl_list_request);
    curl_close($curl_list_request);

    $list_xml = simplexml_load_string($curl_list_response);
    $event_xmls = ($list_xml->item);

    foreach($event_xmls as $event_xml) {
        $event_id = $event_xml->eventid;
        array_push($event_ids, $event_id);
    }

    // Get national events
    $curl_list_request = curl_init();
    curl_setopt($curl_list_request, CURLOPT_URL, IKIT_EVENT_ETOUCHES_API_SEARCH_URL . "?accesstoken=" . urlencode($session_key) . '&folderid=' . IKIT_EVENT_FOLDER_ID_NATIONAL . '&startdate=1-1-1111');
    curl_setopt($curl_list_request, CURLOPT_RETURNTRANSFER, true);
    $curl_list_response = curl_exec($curl_list_request);
    curl_close($curl_list_request);

    $list_xml = simplexml_load_string($curl_list_response);
    $event_xmls = ($list_xml->item);

    foreach($event_xmls as $event_xml) {
        $event_id = $event_xml->eventid;
        array_push($event_ids, $event_id);
    }

    // Get national registration type disabled events
    $curl_list_request = curl_init();
    curl_setopt($curl_list_request, CURLOPT_URL, IKIT_EVENT_ETOUCHES_API_SEARCH_URL . "?accesstoken=" . urlencode($session_key) . '&folderid=' . IKIT_EVENT_FOLDER_ID_NATIONAL_REGISTRATION_TYPE_DISABLED . '&startdate=1-1-1111');
    curl_setopt($curl_list_request, CURLOPT_RETURNTRANSFER, true);
    $curl_list_response = curl_exec($curl_list_request);
    curl_close($curl_list_request);

    $list_xml = simplexml_load_string($curl_list_response);
    $event_xmls = ($list_xml->item);

    foreach($event_xmls as $event_xml) {
        $event_id = $event_xml->eventid;
        array_push($event_ids, $event_id);
    }

    // Get each event
    $events = array();
    foreach($event_ids as $event_id) {

        // Get event detail from etouches
        $curl_get_request = curl_init();
        curl_setopt($curl_get_request, CURLOPT_URL, IKIT_EVENT_ETOUCHES_API_GET_URL . "?accesstoken=" . urlencode($session_key) . '&eventid=' . $event_id . '&customfields=true');
        curl_setopt($curl_get_request, CURLOPT_RETURNTRANSFER, true);
        $curl_get_response = curl_exec($curl_get_request);
        curl_close($curl_get_request);

        $event = simplexml_load_string($curl_get_response);
        array_push($events, $event);

    }

    ikit_event_etouches_remote_fetch_save($events);

}

/**
 * Save event feed, saves or updates events in the database
 */
function ikit_event_etouches_remote_fetch_save($events) {

    global $wpdb;
    $event_table_name = $wpdb->prefix . IKIT_EVENT_ETOUCHES_TABLE_NAME;

    foreach($events as $event) {

        $event_id = (string)$event->eventid;

        // Do not include error conditioned events or those without live status
        if($event_id <= 0) {
            continue;
        }

        $event_data = array(
            'id' => $event_id,
            'timezone_id' => $event->timezoneid,
            'name' => $event->name,
            'code' => $event->code,
            'start_date' => $event->startdate,
            'end_date' => $event->enddate,
            'status' => $event->status,
            'description' => $event->description,
            'program_manager' => $event->programmanager,
            'default_language' => $event->defaultlanguage,
            'creation_date' => $event->createddatetime,
            'last_modified_date' => $event->modifieddatetime,
            'location_name' => $event->location->name,
            'location_address1' => $event->location->address1,
            'location_address2' => $event->location->address2,
            'location_city' => $event->location->city,
            'location_state' => $event->location->state,
            //'location_postcode' => $postcode,
            'location_country' => $event->location->country,
            'location_phone' => $event->location->phone,
            'location_email' => $event->location->email,
            //'location_map' => $event->location->map, XXX Disable as tinytext may be too small to fit and unused
            'url' => $event->url,
            'start_time' => $event->starttime,
            'end_time' => $event->endtime,
            'close_date' => $event->closedate,
            'close_time' => $event->closetime,
            'homepage_url' => $event->homepage,
            // 'standard_currency' => $event->standardcurrency,
            'timezone_description' => $event->timezone,
            'folder_id' => $event->folderid,
            'folder_name' => $event->foldername,
            // 'folder_parent_id' => $event->folderparent,
            //'event_type' => $event->eventtype,
            //'department' => $event->department,
            //'division' => $event->division,
            //'business_unit' => $event->businessunit,
            'client_contact' => $event->clientcontact,
            'service' => IKIT_EVENT_SERVICE_ETOUCHES
        );

        // Allow for null postcodes
        if(empty($event->location->postcode) == false) {
            $event_data['location_postcode'] = $event->location->postcode;
        }

        $rows_affected = $wpdb->query("select id from $event_table_name where id = $event_id");

        if($rows_affected == 0) {
            $rows_affected = $wpdb->insert($event_table_name, $event_data);
        }
        else {
            $rows_affected = $wpdb->update($event_table_name, $event_data, array('id' => $event_id));
        }

        $post_status = 'publish';
        if($event->status != 'Live' && $event->status != 'Sold Out' && $event->status != 'Closed') {
          $post_status = 'draft';
        }

        // Each event has an associated WordPress post to aid with custom fields
        // searching and an auto-generated admin interface for the event
        $yesterday_gmt = date("Y-m-d H:i:s", time() - 60 * 60 * 24);
        $yesterday_local = date("Y-m-d H:i:s", time() - (60 * 60 * 24) + (get_option( 'gmt_offset' ) * 3600));

        $associated_post = array(
            'post_title' => (string)$event->name,
            'post_type' => IKIT_POST_TYPE_IKIT_EVENT,
            'post_status' => $post_status,
            'post_content' => (string)$event->description,

            // XXX Set to yesterday, WordPress 3.5 no longer allows us to publish posts with a future post date
            // we rely instead on custom fields for the event date
            'post_date_gmt' => $yesterday_gmt,
            'post_date' => $yesterday_local,
            'edit_date' => true
        );

        $existing_associated_post = ikit_get_post_by_meta(IKIT_CUSTOM_FIELD_IKIT_EVENT_ID, $event_id, IKIT_POST_TYPE_IKIT_EVENT);
        if($existing_associated_post == null) {

            $new_associated_post_id = wp_insert_post($associated_post);

            if($new_associated_post_id > 0) { // Add the event id to link this post with the event
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_ID, $event_id, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, (string)$event->enddate, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, (string)$event->startdate, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, (string)$event->status, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, IKIT_EVENT_SERVICE_ETOUCHES, true);

                // If in the no reg national folder, auto set to disabled reg
                if($event_data['folder_id'] == IKIT_EVENT_FOLDER_ID_NATIONAL_REGISTRATION_TYPE_DISABLED) {
                    add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_REGISTRATION_TYPE, IKIT_CUSTOM_FIELD_IKIT_EVENT_REGISTRATION_TYPE_DISABLED, true);
                }


            }
        }
        else {

            $associated_post['ID'] = $existing_associated_post->ID;

            // If admin explicity sent event to trash, leave it there
            if($existing_associated_post->post_status == 'trash') {
                $associated_post['post_status'] = 'trash';
            }

            $updated_associated_post_id = wp_update_post($associated_post);
            if($updated_associated_post_id > 0) {
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, (string)$event->enddate, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, (string)$event->startdate, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, (string)$event->status, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, IKIT_EVENT_SERVICE_ETOUCHES, true);

                // If in the no reg national folder, auto set to disabled reg
                if($event_data['folder_id'] == IKIT_EVENT_FOLDER_ID_NATIONAL_REGISTRATION_TYPE_DISABLED) {
                    ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_REGISTRATION_TYPE, IKIT_CUSTOM_FIELD_IKIT_EVENT_REGISTRATION_TYPE_DISABLED, true);
                }

            }
        }

    }

}

/**
 * Fetch the attendees for an event
 */
function ikit_event_etouches_remote_fetch_attendees($event_id) {

    $attendees = null;

    // Authorize with etouches
    $curl_auth_request = curl_init();
    curl_setopt($curl_auth_request, CURLOPT_URL, IKIT_EVENT_ETOUCHES_API_AUTHORIZE_URL . "?accountid=" . IKIT_EVENT_ETOUCHES_ACCOUNT_ID . "&key=" . IKIT_EVENT_ETOUCHES_ACCOUNT_KEY);
    curl_setopt($curl_auth_request, CURLOPT_RETURNTRANSFER, true);
    $curl_auth_response = curl_exec($curl_auth_request);
    curl_close($curl_auth_request);

    $auth_xml = simplexml_load_string($curl_auth_response);
    $session_key = (string)$auth_xml->accesstoken;

    // Get attendees for event
    $curl_list_request = curl_init();

    // Event start date is required as a dummy search critera, otherwise API complains, we set a year before any possible event to include all
    curl_setopt($curl_list_request, CURLOPT_URL, IKIT_EVENT_ETOUCHES_API_SEARCH_ATTENDEES_URL . "?accesstoken=" . urlencode($session_key) . '&eventid=' . $event_id . '&eventstartdate=1-1-1111');
    curl_setopt($curl_list_request, CURLOPT_RETURNTRANSFER, true);
    $curl_list_response = curl_exec($curl_list_request);
    curl_close($curl_list_request);

    $attendees = simplexml_load_string($curl_list_response);

    return $attendees;

}


// add_action('wp_loaded', 'ikit_event_save_post');

?>