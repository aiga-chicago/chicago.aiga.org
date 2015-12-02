<?php
/**
 * @package Internet_Kit
 */

/**
 * Main file for jobs feed
 */

define('IKIT_NATIONAL_FEED_IKIT_JOB_URL', 'http://ikit.aiga.org/ikit_national_feed/ikit-national-feed-ikit-job/');

define('IKIT_JOB_VERSION', '1.0.0');
define('IKIT_JOB_DB_VERSION', '2.4');
define('IKIT_JOB_TABLE_NAME', 'ikit_job');

global $ikit_job_db_version;
$ikit_job_db_version = IKIT_JOB_DB_VERSION;

/**
 * Install or update plugin by creating database tables
 */
function ikit_job_install() {
    global $wpdb;
    global $ikit_job_db_version;

    $job_table_name = $wpdb->prefix . IKIT_JOB_TABLE_NAME;
    $installed_ver = get_option( "ikit_job_db_version" );

    if($installed_ver != $ikit_job_db_version) {

        $sql = "CREATE TABLE " . $job_table_name . " (
            id mediumint(9) NOT NULL,
            title tinytext NOT NULL,
            description text,
            company_name tinytext,
            contact_information text,
            expertise_area tinytext,
            experience_length text,
            city tinytext,
            state tinytext,
            country tinytext,
            other_skills text,
            apply_online_email tinytext,
            date_approved datetime DEFAULT '0000-00-00 00:00:00',
            expiration_date datetime DEFAULT '0000-00-00 00:00:00',
            job_level tinytext,
            application_url tinytext,
            application_url_text tinytext,
            submission_details text,
            job_functions text,

            PRIMARY KEY (id),
            UNIQUE KEY id (id)

        ) CHARACTER SET utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option("ikit_job_db_version", $ikit_job_db_version);
    }

}

function ikit_job_update_db_check() {
    global $ikit_job_db_version;
    if (get_site_option('ikit_job_db_version') != $ikit_job_db_version) {
        ikit_job_install();
    }
}
add_action('wp_loaded', 'ikit_job_update_db_check');

/**
 * Pull jobs from wsdl and import into database
 */
function ikit_job_remote_fetch() {

    // Get filter
    global $g_options;
    $filter_state = $g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_JOB_STATE];
    $filter_city = $g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_JOB_CITY];

    $curl_request = curl_init();
    curl_setopt($curl_request, CURLOPT_URL, IKIT_NATIONAL_FEED_IKIT_JOB_URL . "?states=" . urlencode(trim($filter_state)) . "&cities=" . urlencode(trim($filter_city)));
    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl_request);
    curl_close($curl_request);

    $xml = simplexml_load_string($curl_response);
    $jobs = $xml->job;

    ikit_job_remote_fetch_save($jobs);
    ikit_job_remove_expired();

}

function ikit_job_remote_fetch_save($jobs) {

    global $wpdb;
    $job_table_name = $wpdb->prefix . IKIT_JOB_TABLE_NAME;

    foreach($jobs as $active_job) {

        $active_job_id = (string)$active_job->id;

        $job_data = array(
            'id' => $active_job_id,
            'title' => $active_job->title,
            'description' => ikit_strip_cdata_tags($active_job->description),
            'company_name' => ikit_strip_cdata_tags($active_job->company_name),
            'contact_information' => ikit_strip_cdata_tags($active_job->contact_information),
            'expertise_area' => $active_job->expertise_area,
            'experience_length' => $active_job->experience_length,
            'city' => $active_job->city,
            'state' => $active_job->state,
            'country' => $active_job->country,
            'other_skills' => $active_job->other_skills,
            'apply_online_email' => $active_job->apply_online_email,
            'date_approved' => $active_job->date_approved,
            'expiration_date' => $active_job->expiration_date,
            'job_level' => $active_job->job_level,
            'application_url' => ikit_strip_cdata_tags($active_job->application_url),
            'application_url_text' => ikit_strip_cdata_tags($active_job->application_url_text),
            'submission_details' => ikit_strip_cdata_tags($active_job->submission_details),
            'job_functions' => ikit_strip_cdata_tags($active_job->job_functions)
        );

        $rows_affected = $wpdb->query("select id from $job_table_name where id = $active_job_id");

        if($rows_affected == 0) {
            $rows_affected = $wpdb->insert($job_table_name, $job_data);
        }
        else {
            $rows_affected = $wpdb->update($job_table_name, $job_data, array('id' => $active_job_id));
        }

        // Each job has an associated WordPress post to aid with custom fields
        $associated_post = array(
            'post_title' => (string)$active_job->title,
            'post_type' => IKIT_POST_TYPE_IKIT_JOB,
            'post_status' => 'publish',
            'post_content' => (string)ikit_strip_cdata_tags($active_job->description),
            'post_date' => (string)$active_job->date_approved // Assume local time
        );

        $existing_associated_post = ikit_get_post_by_meta(IKIT_CUSTOM_FIELD_IKIT_JOB_ID, $active_job_id, IKIT_POST_TYPE_IKIT_JOB);
        if($existing_associated_post == null) {
            $new_associated_post_id = wp_insert_post($associated_post);
            if($new_associated_post_id > 0) { // Add the job id to link this post with the job
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_JOB_ID, $active_job_id, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_JOB_CITY, (string)$active_job->city, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_JOB_STATE, (string)$active_job->state, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_JOB_LEVEL, (string)$active_job->job_level, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_JOB_EXPERTISE_AREA, (string)$active_job->expertise_area, true);
            }
        }
        else {
            $associated_post['ID'] = $existing_associated_post->ID;
            $updated_associated_post_id = wp_update_post($associated_post);
            if($updated_associated_post_id > 0) {
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_JOB_CITY, (string)$active_job->city, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_JOB_STATE, (string)$active_job->state, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_JOB_LEVEL, (string)$active_job->job_level, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_JOB_EXPERTISE_AREA, (string)$active_job->expertise_area, true);
            }
        }

    }

}

/**
 * Remove expired jobs
 */
function ikit_job_remove_expired() {

    global $wpdb;
    $job_table_name = $wpdb->prefix . IKIT_JOB_TABLE_NAME;

    $expired_jobs = $wpdb->get_results("SELECT * from $job_table_name where expiration_date < NOW()");

    if($expired_jobs != null && count($expired_jobs) > 0) {

        $expired_job_ids = '';
        foreach($expired_jobs as $expired_job) {
            $expired_job_ids .= $expired_job->id . ',';
        }
        $expired_job_ids = trim($expired_job_ids, ","); // Sanitize for trailing commas

        // Get all posts with the related meta id
        $args = array();
        $args['posts_per_page'] = 999;
        $args['post_type'] = IKIT_POST_TYPE_IKIT_JOB;

        $args['meta_query'] = array(array(
            'key' => IKIT_CUSTOM_FIELD_IKIT_JOB_ID,
            'compare' => 'IN',
            'value' => $expired_job_ids
        ));

        $posts = get_posts($args);

        // Delete associated posts
        foreach($posts as $post) {
            wp_delete_post($post->ID, true);
        }

        // Delete jobs
        $wpdb->query("delete from $job_table_name where id in ($expired_job_ids) ");

    }

}

/**
 * There is the possiblity a job may have a future date, since its pulled from a live feed,
 * so we disregard scheduling and just publish those.
 */
remove_action("future_" . IKIT_POST_TYPE_IKIT_JOB, '_future_post_hook');
add_action("future_" . IKIT_POST_TYPE_IKIT_JOB, 'publish_future_jobs_now', 2, 10);

function publish_future_jobs_now($post_id) {
    wp_publish_post($post_id);
}


// add_action('wp_loaded', 'ikit_job_remote_fetch');


?>