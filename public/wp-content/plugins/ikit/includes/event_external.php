<?php

/**
 * Main file for external events and posts, externals are feeds from outside,
 * external defines a standard way of consuming posts and events into the
 * iKit from external sources. These are exposed in the admin as other chapters events.
 */

define('IKIT_EVENT_EXTERNAL_DB_VERSION', '1.08');
define('IKIT_EVENT_EXTERNAL_TABLE_NAME', 'ikit_event_external');
define('IKIT_EVENT_EXTERNAL_ID_FORMAT', 'event_external_id_%s_%s'); // Unique ID comprised of a section, then ID
define('IKIT_EVENT_EXTERNAL_SOURCE_FORMAT', 'event_external_source_%s');

global $ikit_event_external_db_version;
$ikit_event_external_db_version = IKIT_EVENT_EXTERNAL_DB_VERSION;

/**
 * Install or update plugin by creating database tables
 */
function ikit_event_external_install() {

    global $wpdb;
    global $ikit_event_external_db_version;

    $event_external_table_name = $wpdb->prefix . IKIT_EVENT_EXTERNAL_TABLE_NAME;
    $installed_ver = get_option( "ikit_event_external_db_version" );

    if($installed_ver != $ikit_event_external_db_version) {

       require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

       $sql = "CREATE TABLE " . $event_external_table_name . " (
            id varchar(255) NOT NULL,
            url tinytext,
            image_url tinytext,
            start_date datetime DEFAULT '0000-00-00 00:00:00',
            end_date datetime DEFAULT '0000-00-00 00:00:00',
            service tinytext,
            start_time time DEFAULT '00:00:00',
            end_time time DEFAULT '00:00:00',
            location varchar(512),

            PRIMARY KEY (id),
            UNIQUE KEY id (id)

        ) CHARACTER SET utf8;";
        dbDelta($sql);

        update_option("ikit_event_external_db_version", $ikit_event_external_db_version);
    }

}

function ikit_event_external_update_db_check() {
    global $ikit_event_external_db_version;
    if (get_site_option('ikit_event_external_db_version') != $ikit_event_external_db_version) {
        ikit_event_external_install();
    }
}
add_action('wp_loaded', 'ikit_event_external_update_db_check');

/**
 * Add custom post edit widget to sync with eventbrite
 */
add_action('add_meta_boxes', 'ikit_event_external_add_meta_boxes');

function ikit_event_external_add_meta_boxes() {
    $screens = array(IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL);
    foreach ($screens as $screen) {
        add_meta_box(
            'ikit_event_external',
            __( 'Event External Information', 'ikit_event_external' ),
            'ikit_event_external_add_meta_box_integration',
            $screen
        );
    }
}

function ikit_event_external_add_meta_box_integration($post) {

    $external_source_display_name = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, true);
    $event_meta = ikit_event_external_get_meta($post->ID);

    ?>

    <div class="wp-box">
    <div class="inner">

    <table class="widefat">
    <tr>
        <td>
        External Source
        </td>
        <td>
        <?php echo $external_source_display_name; ?>
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

    <?php

}

/**
 * Saves the posts from a remote fetch, assumes posts objects are of a particular format
 */
function ikit_event_external_remote_fetch_save($external_source, $external_source_display_name, $external_source_link_url, $items) {

    global $wpdb;
    $event_external_table_name = $wpdb->prefix . IKIT_EVENT_EXTERNAL_TABLE_NAME;

    // Find all existing external events for this chapter, delete any that are no longer in the feed
    $existing_posts = ikit_get_posts_by_meta(IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, $external_source, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL, 999999);
    $existing_post_exists_by_id = array();
    foreach($existing_posts as $existing_post) {
        $existing_post_exists_by_id[get_post_meta($existing_post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_EXTERNAL_ID, true)] = 0;
    }

    foreach($items as $item) {

        $title = $item->title;
        $link_url = $item->link_url;
        $image_url = $item->image_url;
        $description = $item->description;
        $start_date = $item->start_date;
        $end_date = $item->end_date;
        $start_time = $item->start_time;
        $end_time = $item->end_time;
        $image_url = $item->image_url;
        $location = $item->location;
        $post_id = $item->post_id;

        // Create an event entry
        $event_data = array(
            'id' => $post_id,
            'url' => $link_url,
            'image_url' => $image_url,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'service' => IKIT_EVENT_SERVICE_EXTERNAL,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'location' => $location,
        );

        $existing_post_exists_by_id[$post_id] = 1;

        $rows_affected = $wpdb->query("select id from $event_external_table_name where id = '$post_id'");

        if($rows_affected == 0) {
            $rows_affected = $wpdb->insert($event_external_table_name, $event_data);
        }
        else {
            $rows_affected = $wpdb->update($event_external_table_name, $event_data, array('id' => $post_id));
        }

        // Each event has an associated WordPress post to aid with custom fields
        // searching and an auto-generated admin interface for the event
        $yesterday_gmt = date("Y-m-d H:i:s", time() - 60 * 60 * 24);
        $yesterday_local = date("Y-m-d H:i:s", time() - (60 * 60 * 24) + (get_option( 'gmt_offset' ) * 3600));

        $post = array(
            'post_title' => $title,
            'post_type' => IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL,
            'post_content' => $description,

            // XXX Set to yesterday, WordPress 3.5 no longer allows us to publish posts with a future post date
            // we rely instead on custom fields for the event date
            'post_date_gmt' => $yesterday_gmt,
            'post_date' => $yesterday_local,
            'edit_date' => true
        );

        $existing_post = ikit_get_post_by_meta(IKIT_CUSTOM_FIELD_IKIT_EVENT_EXTERNAL_ID, $post_id, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL);
        if($existing_post == null) {

            $post['post_status'] = 'draft';

            $new_post_id = wp_insert_post($post);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_EXTERNAL_ID, $post_id, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, $end_date, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, $start_date, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, 'Live', true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, IKIT_EVENT_SERVICE_EXTERNAL, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, $external_source, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, $external_source_display_name, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_LINK_URL, $external_source_link_url, true);

        }
        else {

            $post['ID'] = $existing_post->ID;

            $updated_post_id = wp_update_post($post);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, $end_date, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, $start_date, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, 'Live', true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, IKIT_EVENT_SERVICE_EXTERNAL, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, $external_source, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, $external_source_display_name, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_LINK_URL, $external_source_link_url, true);

        }

    }

    foreach($existing_posts as $existing_post) {
        $external_id = get_post_meta($existing_post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_EXTERNAL_ID, true);
        $exists = $existing_post_exists_by_id[$external_id];
        if($exists == 0) {
            wp_delete_post($existing_post->ID, true);

            // Delete associated database entry
            global $wpdb;
            $table_name = $wpdb->prefix . IKIT_EVENT_EXTERNAL_TABLE_NAME;
            $wpdb->query(sprintf("delete from $table_name where id = '%s'", $external_id));

        }
    }

}

?>