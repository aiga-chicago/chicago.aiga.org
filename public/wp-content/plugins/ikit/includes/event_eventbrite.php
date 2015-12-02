<?php
/**
 * @package Internet_Kit
 */

/**
 * Main file for events feed from Eventbrite, separated out for clarity only
 */

define('IKIT_EVENT_EVENTBRITE_DB_VERSION', '3.29');
define('IKIT_EVENT_EVENTBRITE_TABLE_NAME', 'ikit_event_eventbrite');
define("IKIT_EVENT_EVENTBRITE_MAX_EVENTS", 40);
define("IKIT_EVENT_EVENTBRITE_API_KEYS", serialize(array("TY5ZK5F6PKP3TJZABZ", "D373FX7APJZFZNBCXD", "KNG6ANZ5M4XHIXPCT4", "WVFQ4DFB2KWBMZQ3U3", "3PXZEMUPLDNVWQP2FQ"))); // Uses Eventbrite apps for user support@winfieldco.com

global $ikit_event_eventbrite_db_version;
$ikit_event_eventbrite_db_version = IKIT_EVENT_EVENTBRITE_DB_VERSION;

/**
 * Install or update plugin by creating database tables
 */
function ikit_event_eventbrite_install() {

    global $wpdb;
    global $ikit_event_eventbrite_db_version;

    $event_eventbrite_table_name = $wpdb->prefix . IKIT_EVENT_EVENTBRITE_TABLE_NAME;
    $installed_ver = get_option( "ikit_event_eventbrite_db_version" );

    if($installed_ver != $ikit_event_eventbrite_db_version) {

       require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

       $sql = "CREATE TABLE " . $event_eventbrite_table_name . " (
            id bigint(20) NOT NULL,
            url tinytext,
            start_date datetime DEFAULT '0000-00-00 00:00:00',
            end_date datetime DEFAULT '0000-00-00 00:00:00',
            description text,
            status tinytext,
            title tinytext NOT NULL,
            venue_name tinytext,
            venue_address tinytext,
            venue_address2 tinytext,
            venue_city tinytext,
            venue_region tinytext,
            venue_postal_code tinytext,
            venue_country tinytext,
            venue_country_code tinytext,
            venue_longitude tinytext,
            venue_latitude tinytext,
            logo tinytext,
            service tinytext,
            start_time time DEFAULT '00:00:00',
            end_time time DEFAULT '00:00:00',

            PRIMARY KEY (id),
            UNIQUE KEY id (id)

        ) CHARACTER SET utf8;";
        dbDelta($sql);

        update_option("ikit_event_eventbrite_db_version", $ikit_event_eventbrite_db_version);
    }

}

function ikit_event_eventbrite_update_db_check() {
    global $ikit_event_eventbrite_db_version;
    if (get_site_option('ikit_event_eventbrite_db_version') != $ikit_event_eventbrite_db_version) {
        ikit_event_eventbrite_install();
    }
}
add_action('wp_loaded', 'ikit_event_eventbrite_update_db_check');

/**
 * Pull eventbrite events and import into database
 */
function ikit_event_eventbrite_remote_fetch() {

    ini_set('max_execution_time', 300); // XXX Temporarily extend timeout to 5 minutes

    global $wpdb;
    $event_eventbrite_table_name = $wpdb->prefix . IKIT_EVENT_EVENTBRITE_TABLE_NAME;

    $eb_client = ikit_event_eventbrite_get_client();
    if($eb_client != null) {

        $response = $eb_client->user_list_events(array('do_not_display' => 'tickets', 'asc_or_desc' => 'desc'));
        $events = $response->events;

        // Only grab the last 30 events
        if(count($events) > IKIT_EVENT_EVENTBRITE_MAX_EVENTS) {
          $events = array_slice($events, 0, IKIT_EVENT_EVENTBRITE_MAX_EVENTS);
        }

        ikit_event_eventbrite_remote_fetch_save($events);

    }

}

function ikit_event_eventbrite_remote_fetch_save($events) {

    global $wpdb;
    $event_eventbrite_table_name = $wpdb->prefix . IKIT_EVENT_EVENTBRITE_TABLE_NAME;

    foreach($events as $event) {

        $event = $event->event;

        $event_id = (string)$event->id;

        $event_start_date = ikit_date_without_time($event->start_date);
        $event_end_date = ikit_date_without_time($event->end_date);

        $event_start_time = ikit_time_without_date($event->start_date);
        $event_end_time = ikit_time_without_date($event->end_date);

        $event_data = array(
            'id' => $event_id,
            'url' => $event->url,
            'start_date' => $event_start_date,
            'end_date' => $event_end_date,
            'description' => $event->description,
            'status' => $event->status,
            'title' => $event->title,
            'venue_name' => $event->venue->name,
            'venue_address' => $event->venue->address,
            'venue_address2' => $event->venue->address2,
            'venue_city' => $event->venue->city,
            'venue_region' => $event->venue->region,
            'venue_postal_code' => $event->venue->postal_code,
            'venue_country' => $event->venue->country,
            'venue_country_code' => $event->venue->country_code,
            'venue_longitude' => $event->venue->longitude,
            'venue_latitude' => $event->venue->latitude,
            'logo' => $event->logo,
            'service' => IKIT_EVENT_SERVICE_EVENTBRITE,
            'start_time' => $event_start_time,
            'end_time' => $event_end_time,
        );

        $rows_affected = $wpdb->query("select id from $event_eventbrite_table_name where id = $event_id");

        if($rows_affected == 0) {
            $rows_affected = $wpdb->insert($event_eventbrite_table_name, $event_data);
        }
        else {
            $rows_affected = $wpdb->update($event_eventbrite_table_name, $event_data, array('id' => $event_id));
        }

        $post_status = 'publish';
        if($event->status != 'Live' && $event->status != 'Completed' && $event->status != 'Started') {
          $post_status = 'draft';
        }

        // Each event has an associated WordPress post to aid with custom fields
        // searching and an auto-generated admin interface for the event
        $yesterday_gmt = date("Y-m-d H:i:s", time() - 60 * 60 * 24);
        $yesterday_local = date("Y-m-d H:i:s", time() - (60 * 60 * 24) + (get_option( 'gmt_offset' ) * 3600));

        $associated_post = array(
            'post_title' => (string)$event->title,
            'post_type' => IKIT_POST_TYPE_IKIT_EVENT,
            'post_status' => $post_status,
            'post_content' => (string)$event->description,

            // XXX Set to yesterday, WordPress 3.5 no longer allows us to publish posts with a future post date
            // we rely instead on custom fields for the event date
            'post_date_gmt' => $yesterday_gmt,
            'post_date' => $yesterday_local,
            'edit_date' => true
        );

        $event_end_date = ikit_date_without_time($event->end_date);
        $event_start_date = ikit_date_without_time($event->start_date);

        $existing_associated_post = ikit_get_post_by_meta(IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_ID, $event_id, IKIT_POST_TYPE_IKIT_EVENT);
        if($existing_associated_post == null) {

            $new_associated_post_id = wp_insert_post($associated_post);

            if($new_associated_post_id > 0) { // Add the event id to link this post with the event
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_ID, $event_id, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, IKIT_EVENT_SERVICE_EVENTBRITE, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, (string)$event->status, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, $event_end_date, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, $event_start_date, true);
            }
        }
        else {

            $associated_post['ID'] = $existing_associated_post->ID;

            $updated_associated_post_id = wp_update_post($associated_post);
            if($updated_associated_post_id > 0) {
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, IKIT_EVENT_SERVICE_EVENTBRITE, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, (string)$event->status, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, $event_end_date, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, $event_start_date, true);


            }
        }

    }

}



/**
 * Custom save of the post, syncs with Eventbrite if checked
 */
add_action('save_post', 'ikit_event_eventbrite_save_post', 10, 2);

function ikit_event_eventbrite_save_post($post_id) {

    if($_POST['post_type'] == IKIT_POST_TYPE_IKIT_EVENT) {

        $event_service = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, true);

        if($event_service == IKIT_EVENT_SERVICE_EVENTBRITE) {

            if(isset($_POST[IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_ENABLED])) {

                // Update setting
                update_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_ENABLED, true);
                $eb_client = ikit_event_eventbrite_get_client();

                $eventbrite_id = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_ID, true);
                $eventbrite_sync_data = unserialize(get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_DATA, true));
                if($eventbrite_sync_data) {

                }
                // Otherwise we haven't synced, create ticket types and access codes
                else {

                    $eventbrite_event = ikit_event_eventbrite_get_event($eb_client, $eventbrite_id);
                    $eventbrite_existing_tickets = $eventbrite_event->tickets;

                    $validate = true;

                    global $g_options;
                    if($g_options[IKIT_PLUGIN_OPTION_EVENTBRITE_SYNC_VALIDATION_DISABLED] == 1) {
                        $validate = false;
                    }

                    if($validate && count($eventbrite_existing_tickets) > 0) {
                        // Do nothing if tickets already exist, an error will appear because the event brite sync data is empty
                    }
                    else if($validate && time() > strtotime($eventbrite_event->start_date)) {
                        // Do nothing can't create tickets if the event is in the past
                    }
                    else {

                        $sync_data = array();

                        try {

                            ikit_event_eventbrite_add_ticket($eb_client, $eventbrite_id, 'Trustee', IKIT_MEMBER_TYPE_TRUSTEE, $sync_data, true);
                            ikit_event_eventbrite_add_ticket($eb_client, $eventbrite_id, 'Design Leader', IKIT_MEMBER_TYPE_DESIGN_LEADER, $sync_data, true);
                            ikit_event_eventbrite_add_ticket($eb_client, $eventbrite_id, 'Sustaining Member', IKIT_MEMBER_TYPE_SUSTAINING_MEMBER, $sync_data, true);
                            ikit_event_eventbrite_add_ticket($eb_client, $eventbrite_id, 'Supporter', IKIT_MEMBER_TYPE_DESIGN_SUPPORTER, $sync_data, true);
                            ikit_event_eventbrite_add_ticket($eb_client, $eventbrite_id, 'Contributor', IKIT_MEMBER_TYPE_CONTRIBUTOR, $sync_data, true);
                            ikit_event_eventbrite_add_ticket($eb_client, $eventbrite_id, 'Student', IKIT_MEMBER_TYPE_STUDENT, $sync_data, true);
                            ikit_event_eventbrite_add_ticket($eb_client, $eventbrite_id, 'Non-Member', IKIT_MEMBER_TYPE_NONE, $sync_data, false);

                        } catch (Exception $e) {

                            // Show the error from eventbrite if the sync failed (https://wcoaiga.atlassian.net/browse/AIGAIKIT-207)
                            echo('An error occurred attempting to sync this event with Eventbrite, please correct the error before continuing:<BR/><BR/>' . $e->getMessage());

                        }

                        update_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_DATA, serialize($sync_data));

                    }

                }

            }
            else {

                // We cannot delete the tickets from Eventbrite via the API so there is nothing we can do here for now...
                update_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_ENABLED, false);
                delete_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_DATA);

            }

        }
    }
}

function ikit_event_eventbrite_get_event($eb_client, $eventbrite_event_id) {

    $response = $eb_client->event_get(array('id'=>$eventbrite_event_id));

    return $response->event;

}

/**
 * Helper function to generate tickets and access codes
 */
function ikit_event_eventbrite_add_ticket($eb_client, $eventbrite_event_id, $ticket_name, $member_type, &$sync_data, $hidden) {

    $args = array();
    $args['event_id'] = $eventbrite_event_id;
    $args['quantity_available'] = 100;
    $args['name'] = $ticket_name;
    $args['price'] = 1;

    // Create the ticket
    $response = $eb_client->ticket_new($args);

    $ticket_id = $response->process->id;
    $ticket = array();

    // Hide the ticket
    if($hidden) {
        $response = $eb_client->ticket_update(array('id'=>$ticket_id, 'hide'=>'y'));
    }

    // Create access codes
    $code = uniqid();
    $response = $eb_client->access_code_new(array('event_id'=>$eventbrite_event_id, 'tickets'=>$ticket_id, 'code'=>$code));

    $ticket['id'] = $ticket_id;
    $ticket['access_code'] = array();
    $ticket['access_code']['code'] = $code;

    $sync_data[$member_type] = $ticket;

}

/**
 * Get eventbrite client
 */
 function ikit_event_eventbrite_get_client() {

    include_once dirname( __FILE__ ) . '/library/eventbrite.php/Eventbrite.php';

    global $g_options;
    $filter_api_user_key = $g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_EVENTBRITE_API_USER_KEY];

    if(empty($filter_api_user_key) == false) {

        $eventbrite_api_keys = unserialize(IKIT_EVENT_EVENTBRITE_API_KEYS);
        $option_key = "ikit_event_eventbrite_api_key_round_robin_index"; // Only used here and can change it without issue, so no need for a constant

        if(!get_option($option_key)) {
            update_option($option_key, 0);
        }

        $round_robin_index = get_option($option_key);
        if($round_robin_index >= count($eventbrite_api_keys)) {
            $round_robin_index = 0;
        }

        $eventbrite_api_key = $eventbrite_api_keys[$round_robin_index];
        update_option($option_key, $round_robin_index+1);

        $authentication_tokens = array('app_key'  => $eventbrite_api_key, 'user_key' => $filter_api_user_key);
        $eb_client = new Eventbrite($authentication_tokens);

        return $eb_client;

    }
    else {
        return null;
    }

}

/**
 * Redirect to Eventbrite given the SSO key, used for auto-generated tickets
 */
function ikit_event_eventbrite_sso_redirect($ikit_event_id, $sso_key) {

    if(empty($sso_key) == false) {

        // Get event meta
        $ikit_event_meta = ikit_event_get_meta($ikit_event_id);

        // Pull member type
        $xml = ikit_sso_get_user($sso_key);

        // Use access code
        $sync_data = unserialize(get_post_meta($ikit_event_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_DATA, true));

        // Determine member type
        $member_type = trim((string)$xml->Name->MEMBER_TYPE);

        $attribute1 = ((string)$xml->Name->ATTRIBUTE1); // Students use a special attribute
        if($attribute1 == '1STUDENT' && empty($sync_data[IKIT_MEMBER_TYPE_STUDENT]) == false) {

            // Determine if student ticket still exists
            $student_eventbrite_ticket_id = $sync_data[IKIT_MEMBER_TYPE_STUDENT]['id'];
            $student_eventbrite_ticket_exists = false;

            $eb_client = ikit_event_eventbrite_get_client();
            if($eb_client != null) {

                $ikit_event_meta = ikit_event_get_meta($ikit_event_id);
                $event_id = $ikit_event_meta->id;

                $eventbrite_event = ikit_event_eventbrite_get_event($eb_client, $ikit_event_meta->id);
                $eventbrite_tickets = $eventbrite_event->tickets;

                foreach($eventbrite_tickets as $eventbrite_ticket) {
                    $eventbrite_ticket_id = $eventbrite_ticket->ticket->id;

                    if($student_eventbrite_ticket_id == $eventbrite_ticket_id) {
                        $student_eventbrite_ticket_exists = true;
                        break;
                    }

                }
            }

            // If the student ticket still exists, we show the student the student ticket
            // otherwise just show their member ticket
            if($student_eventbrite_ticket_exists) {
                $member_type = 'STUDENT';
            }
        }

        $ticket = $sync_data[$member_type];

        // XXX Legacy events pre-20131125 used the missing constant name, TODO fix via update
        if(empty($ticket) && $member_type == 'SUP') {
            $ticket = $sync_data['IKIT_MEMBER_TYPE_SUPPORTER'];
        }

        $code = "";
        if(empty($ticket) == false) {
            $code = $ticket['access_code']['code'];
        }

        $eventbrite_registration_url = $ikit_event_meta->url . '&discount=' . $code;

        // Do a server redirect so users do not see a page load
        wp_redirect($eventbrite_registration_url);
        exit;

    }

}

/**
 * Fetch the attendees for an event
 */
function ikit_event_eventbrite_remote_fetch_attendees($eb_client, $eventbrite_event_id) {

    $attendees = array();

    try {
        $response = $eb_client->event_list_attendees(array('id'=>$eventbrite_event_id, 'status'=>'attending', 'count'=>999));
        $attendees = $response->attendees;
    } catch(Exception $e) {

    }

    return $attendees;

}

// add_action('wp_loaded', 'ikit_event_eventbrite_remote_fetch');



?>