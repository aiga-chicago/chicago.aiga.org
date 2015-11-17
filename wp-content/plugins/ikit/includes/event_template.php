<?php
/**
 * Internet Kit Events Template Functions. Template functions specific to Eventbrite see event_eventbrite_template.php,
 * this file contains functions for etouches as well as generic functions for all events.
 *
 * @package Internet Kit
 * @subpackage Template
 */

/**
 * Retrieve the meta for an event
 *
 * Because there are too many fields, it would be bad for performance
 * to store all as meta fields on the post, so instead
 * a meta field just stores the event id, then this function
 * is used to retrieve additional information, works for both
 * Eventbrite and etouches events, the caller should then
 * parse data based on the service.
 */
function ikit_event_get_meta($ikit_event_id) {

    $meta = null;
    $service = get_post_meta($ikit_event_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, true);

    if($service == IKIT_EVENT_SERVICE_EVENTBRITE) {
        $meta = ikit_event_eventbrite_get_meta($ikit_event_id);
    }
    else if($service == IKIT_EVENT_SERVICE_ETOUCHES) {
        $meta = ikit_event_etouches_get_meta($ikit_event_id);
    }
    else if($service == IKIT_EVENT_SERVICE_EXTERNAL) {
        $meta = ikit_event_external_get_meta($ikit_event_id);
    }
    else if($service == IKIT_EVENT_SERVICE_INTERNAL) {
        $meta = ikit_event_internal_get_meta($ikit_event_id);
    }

    return $meta;

}

/**
 * Get the event image URL
 */
function ikit_event_get_image_url($ikit_event_id, $ikit_event_meta, $default_image_url) {

    $image_gallery = get_field(IKIT_CUSTOM_FIELD_IKIT_EVENT_IMAGE_GALLERY, $ikit_event_id);
    foreach($image_gallery as $image_gallery_row) {
        $image = $image_gallery_row;
        if(empty($image['image']) == false) {
            $image = wp_get_attachment_image_src($image['image'], 'full');
            return $image[0];
        }
    }

    // Check if etouches or eventbrite
    if($ikit_event_meta->service == IKIT_EVENT_SERVICE_ETOUCHES) {

        // First check the client contact field for a value, it should be the large version of the image
        $event_image = trim($ikit_event_meta->client_contact);

        // If it does not exist, use the program manager, this is the small version of the image
        if(empty($event_image) || filter_var($event_image, FILTER_VALIDATE_URL) == false) {
            $event_image = trim($ikit_event_meta->program_manager);
        }
        // Otherwise fallback to a default image
        if(empty($event_image) || filter_var($event_image, FILTER_VALIDATE_URL) == false) {
            $event_image = $default_image_url;
        }

    }
    else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EVENTBRITE) {

        $event_image = $ikit_event_meta->logo;

        if(empty($event_image)) {
            $event_image = $default_image_url;
        }
    }
    else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) {

        $event_image = $ikit_event_meta->image_url;

        if(empty($event_image)) {
            $event_image = $default_image_url;
        }

    }

    return $event_image;

}

/**
 * Get event meta normalized to account for slight
 * differences in the eventbrite and etouches meta field names
 */
function ikit_event_get_meta_normalized($ikit_event_id, $ikit_event_meta, $default_image_url) {

    $event = array();

    $event['meta'] = $ikit_event_meta;
    $event['id'] = $ikit_event_meta->id;
    $event['permalink'] = get_permalink($ikit_event_id);
    $event['permalink_target'] = '_self';
    $event['url'] = null;
    $event['url_target'] = null;
    $event['image'] = ikit_event_get_image_url($ikit_event_id, $ikit_event_meta, $default_image_url);
    $event['start_date'] = mysql2date('l, F j, Y', get_gmt_from_date($ikit_event_meta->start_date), false);
    $event['end_date'] = mysql2date('l, F j, Y', get_gmt_from_date($ikit_event_meta->end_date), false);
    $event['end_date_raw'] = $ikit_event_meta->end_date;
    $event['start_date_raw'] = $ikit_event_meta->start_date;
    $event['status'] = $ikit_event_meta->status;
    $event['description'] = $ikit_event_meta->description;
    $event['location_name'] = null;
    $event['location_city'] = null;
    $event['location_address1'] = null;
    $event['location_address2'] = null;
    $event['location_state'] = null;
    $event['location_postal_code'] = null;
    $event['start_time_raw'] = $ikit_event_meta->start_time;
    $event['end_time_raw'] = $ikit_event_meta->end_time;
    $event['start_time'] = strtotime($ikit_event_meta->start_time);
    $event['end_time'] = strtotime($ikit_event_meta->end_time);
    $event['service'] = $ikit_event_meta->service;
    $event['eventbrite_sync_data'] = unserialize(get_post_meta($ikit_event_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_DATA, true));

    if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EVENTBRITE) {

        if($event['eventbrite_sync_data']) {
            $event['url'] = ikit_sso_get_login_url(get_permalink());
            $event['url_target'] = '_blank';
        }
        else {
            $event['url'] = $ikit_event_meta->url; // Just use the event URL if no sync data, assume they are doing tickets themselves
            $event['url_target'] = '_blank';
        }

        $event['location_city'] = $ikit_event_meta->venue_city;
        $event['location_name'] = $ikit_event_meta->venue_name;
        $event['location_address1'] = $ikit_event_meta->venue_address;
        $event['location_address2'] = $ikit_event_meta->venue_address2;
        $event['location_state'] = $ikit_event_meta->venue_region;
        $event['location_postal_code'] = $ikit_event_meta->venue_postal_code;
        $event['description'] = ikit_strip_style_attributes($event['description']);

        if($ikit_event_meta->venue_region != $ikit_event_meta->venue_postal_code) { // Eventbrite sets postal code to the same as region
            $event['location_postal_code'] = $ikit_event_meta->venue_postal_code;
        }

    }
    else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_ETOUCHES) {

        $event['url'] = $ikit_event_meta->url;
        $event['url_target'] = '_blank';

        $event['location_city'] = $ikit_event_meta->location_city;
        $event['location_name'] = $ikit_event_meta->location_name;
        $event['location_address1'] = $ikit_event_meta->location_address1;
        $event['location_address2'] = $ikit_event_meta->location_address2;
        $event['location_state'] = $ikit_event_meta->location_state;
        $event['location_postal_code'] = $ikit_event_meta->location_postcode;
        $event['description'] = wpautop(ikit_strip_style_attributes($event['description']));

    }
    else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_INTERNAL) {

        $event['location_name'] = $ikit_event_meta->location_name;
        $event['location_city'] = $ikit_event_meta->location_city;
        $event['location_address1'] = $ikit_event_meta->location_address_1;
        $event['location_address2'] = $ikit_event_meta->location_address_2;
        $event['location_state'] = $ikit_event_meta->location_state_province;
        $event['location_postal_code'] = $ikit_event_meta->location_postal_code;
        $event['url'] = $ikit_event_meta->url;
        $event['url_target'] = '_blank';

    }
    else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) {

        $event['location_name'] = $ikit_event_meta->location;
        $event['location_city'] = $ikit_event_meta->location;
        $event['registration_url'] = $ikit_event_meta->url;
        $event['registration_url_target'] = '_blank';
        $event['url'] = $ikit_event_meta->url;
        $event['url_target'] = '_blank';
        $event['permalink'] = $ikit_event_meta->url;
        $event['permalink_target'] = '_blank';

    }

    return $event;


}

/**
 * Get event attendees, returns null on error
 */
function ikit_event_get_attendees($event_id, $event_service) {

    if($event_service == IKIT_EVENT_SERVICE_ETOUCHES) {

        $attendees = ikit_event_etouches_remote_fetch_attendees($event_id);

        // XXX, etouches treats no attendees as an error, so in that specific case do not raise exception.
        if(isset($attendees->error) && $attendees->error->data != 'No attendees found.') {
            return null;
        }
        else {
            $attendees = $attendees->item;
        }

        $valid_attendees = array();

        // Remove guest and duplicate attendees from the list
        foreach($attendees as $attendee) {
            if(empty($attendee->name) == false) {

                $attendee_name = (string)$attendee->name;
                if(in_array($attendee_name, $valid_attendees) == false) {

                    // Only include confirmed attendees
                    if($attendee->status == 'Confirmed' || $attendee->status == 'Attended' || $attendee->status == 'No Show' || $attendee->status == 'Activated') {
                        array_push($valid_attendees, $attendee_name);
                    }
                }
            }
        }

        // Sort thte attendees based on last name, because etouches
        // just returns a name field, we need to parse the names
        $valid_attendees_for_sort = array();
        foreach($valid_attendees as $valid_attendee) {
            $valid_attendee_exploded = explode(" ", $valid_attendee);
            if(count($valid_attendee_exploded) >= 2) { // Valid names are space separated

                // Find last valid exploded item, this accounts for empty exploded items
                $attendee_last_name = "";
                foreach($valid_attendee_exploded as $valid_attendee_exploded_item) {
                    if(empty($valid_attendee_exploded_item) == false) {
                        $attendee_last_name = $valid_attendee_exploded_item;
                    }
                }

                $valid_attendees_for_sort[$attendee_last_name . ' ' . $valid_attendee] = $valid_attendee;
            }
        }

        ksort($valid_attendees_for_sort);

        $valid_attendees = array();
        foreach ($valid_attendees_for_sort as $key => $value) {
            array_push($valid_attendees ,$valid_attendees_for_sort[$key]);
        }

        return $valid_attendees;

    }
    else if($event_service == IKIT_EVENT_SERVICE_EVENTBRITE) {

        $attendees = ikit_event_eventbrite_remote_fetch_attendees(ikit_event_eventbrite_get_client(), $event_id);

        $valid_attendees = array();
        foreach ($attendees as $attendee) {
            array_push($valid_attendees, $attendee->attendee->first_name . ' ' . $attendee->attendee->last_name);
        }

        return $valid_attendees;

    }

}

?>