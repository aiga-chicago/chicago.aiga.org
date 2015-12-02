<?php
/**
 * @package Internet_Kit
 */


/**
 * Main file for portfolio feed
 */

define('IKIT_PORTFOLIO_THIRDWAVE_FEED_URL', 'http://newyork-staging.aiga.org/utils/behance/xml/');
define('IKIT_PORTFOLIO_VERSION', '1.0.0');
define('IKIT_PORTFOLIO_DB_VERSION', '2.6');

define('IKIT_PORTFOLIO_PROJECT_TABLE_NAME', 'ikit_portfolio_project');
define('IKIT_PORTFOLIO_OWNER_TABLE_NAME', 'ikit_portfolio_owner');
define('IKIT_PORTFOLIO_PROJECT_OWNER_TABLE_NAME', 'ikit_portfolio_project_owner');

global $ikit_portfolio_db_version;
$ikit_portfolio_db_version = IKIT_PORTFOLIO_DB_VERSION;

/**
 * Install or update plugin by creating database tables
 */
function ikit_portfolio_install() {
    global $wpdb;
    global $ikit_portfolio_db_version;

    $project_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_TABLE_NAME;
    $owner_table_name = $wpdb->prefix . IKIT_PORTFOLIO_OWNER_TABLE_NAME;
    $project_owner_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_OWNER_TABLE_NAME;

    $installed_ver = get_option( "ikit_portfolio_db_version" );

    if($installed_ver != $ikit_portfolio_db_version) {

        // Project table, id is the behance project id
        $project_sql = "CREATE TABLE " . $project_table_name . " (
            id int(11) NOT NULL,
            title tinytext NOT NULL,
            published_date datetime DEFAULT '0000-00-00 00:00:00',
            url tinytext,
            cover_image_url tinytext,
            mature_content boolean,

            PRIMARY KEY (id),
            UNIQUE KEY id (id)

        ) CHARACTER SET utf8;";

        // Owner table, id is the aiga id
        $owner_sql = "CREATE TABLE " . $owner_table_name . " (
            id int(11) NOT NULL,
            name tinytext NOT NULL,
            frame_url tinytext,
            url tinytext,
            aiga_chapter tinytext,

            PRIMARY KEY (id),
            UNIQUE KEY id (id)

        ) CHARACTER SET utf8;";

        // Relationship table - many to many owners to projects
        $project_owner_sql = "CREATE TABLE " . $project_owner_table_name . " (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            project_id int(11) NOT NULL,
            owner_id int(11) NOT NULL,

            PRIMARY KEY (id),
            UNIQUE KEY id (id),
            KEY (project_id),
            KEY (owner_id)

        ) CHARACTER SET utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($project_sql);
        dbDelta($owner_sql);
        dbDelta($project_owner_sql);

        update_option("ikit_portfolio_db_version", $ikit_portfolio_db_version);
    }

}

function ikit_portfolio_update_db_check() {
    global $ikit_portfolio_db_version;
    if (get_site_option('ikit_portfolio_db_version') != $ikit_portfolio_db_version) {
        ikit_portfolio_install();
    }
}
add_action('wp_loaded', 'ikit_portfolio_update_db_check');

/**
 * Pull portfolios from feed and import into database
 */
function ikit_portfolio_remote_fetch() {

    global $wpdb;
    $project_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_TABLE_NAME;
    $owner_table_name = $wpdb->prefix . IKIT_PORTFOLIO_OWNER_TABLE_NAME;
    $project_owner_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_OWNER_TABLE_NAME;

    // Get filter
    global $g_options;
    $filter_chapter = $g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_IMUS_CHAPTER];

    $curl_request = curl_init();

    // XXX User proxy server as production server cannot connect to chapters.aiga.org
    // curl_setopt($curl_request, CURLOPT_PROXY, IKIT_PROXY_SERVER_IP);
    // curl_setopt($curl_request, CURLOPT_PROXYPORT, IKIT_PROXY_SERVER_PORT);

    curl_setopt($curl_request, CURLOPT_URL, IKIT_PORTFOLIO_THIRDWAVE_FEED_URL . strtoupper(trim($filter_chapter)) . 'Portfolio.xml');
    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl_request);
    curl_close($curl_request);


    $xml = simplexml_load_string($curl_response);
    $portfolios = $xml->portfolio;

    ikit_portfolio_remote_fetch_save($portfolios);

}

/**
 * Save the projects returned from a portfolio fetch from behance
 */
function ikit_portfolio_remote_fetch_save($portfolios) {

    // Purge portfolios as we will start from scratch
    ikit_delete_ikit_portfolios();

    global $wpdb;
    $project_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_TABLE_NAME;
    $owner_table_name = $wpdb->prefix . IKIT_PORTFOLIO_OWNER_TABLE_NAME;
    $project_owner_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_OWNER_TABLE_NAME;

    foreach($portfolios as $portfolio) {

        $owner_id = intval($portfolio->aigaid);

        // Add owner
        $owner_data = array(
            'id' => $owner_id,
            'name' => $portfolio->name,
            'url' => $portfolio->url,
            'frame_url' => $portfolio->frameurl,
            'aiga_chapter' => $portfolio->aigachapter
        );

        $rows_affected = $wpdb->insert($owner_table_name, $owner_data);

        // Each owner has an associated WordPress post
        $associated_post = array(
            'post_title' => (string)$portfolio->name,
            'post_type' => IKIT_POST_TYPE_IKIT_PORTFOLIO,
            'post_status' => 'publish',
            'post_date' => current_time('mysql') // No date information so just use the time pulled
        );

        $new_associated_post_id = wp_insert_post($associated_post);
        if($new_associated_post_id > 0) { // Add the event id to link this post with the project
            add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_PORTFOLIO_ID, $owner_id, true);
            add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_PORTFOLIO_CHAPTER, (string)$portfolio->aigachapter, true);
        }

        $projects = $portfolio->projects->project;
        foreach($projects as $project) {

            // Add project
            $project_id = intval($project->id);

            $project_data = array(
                'id' => $project_id,
                'title' => $project->title,
                'url' => $project->url,
                'cover_image_url' => $project->coverimage,
                'published_date' => date('Y-m-d H:i:s', strtotime($project->pubDate))
            );

            $rows_affected = $wpdb->insert($project_table_name, $project_data);

            // Add project owner relationship
            $project_owner_data = array(
                'project_id' => $project_id,
                'owner_id' => $owner_id
            );

            $rows_affected = $wpdb->insert($project_owner_table_name, $project_owner_data);

        }

    }

}

// add_action('wp_loaded', 'ikit_portfolio_remote_fetch');


?>