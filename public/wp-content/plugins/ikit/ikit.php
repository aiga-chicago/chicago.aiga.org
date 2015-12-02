<?php
/**
 * @package Internet_Kit
 */
/*
 Plugin Name: Internet Kit
 Plugin URI: http://aiga.org/
 Description: Internet Kit plugin
 Version: 1.0.0
 Author: AIGA
 Author URI: http://aiga.org

 */

/**
 * Define constants
 */

// Custom fields
define("IKIT_CUSTOM_FIELD_POST_PREVIEW_DESCRIPTION", "preview_description");
define("IKIT_CUSTOM_FIELD_POST_IMAGE_GALLERY", "image_gallery");
define("IKIT_CUSTOM_FIELD_POST_DISPLAY_PRIORITY", "display_priority");
define("IKIT_CUSTOM_FIELD_POST_ATTRIBUTION", "attribution");
define("IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE", "primary_image");
define("IKIT_CUSTOM_FIELD_IKIT_SPONSOR_SECONDARY_IMAGE", "secondary_image");
define("IKIT_CUSTOM_FIELD_IKIT_SPONSOR_DISPLAY_ORDER", "display_order");
define("IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL", "url");
define("IKIT_CUSTOM_FIELD_IKIT_LINK_IMAGE", "image");
define("IKIT_CUSTOM_FIELD_IKIT_LINK_URL", "url");
define("IKIT_CUSTOM_FIELD_PAGE_IKIT_SECTION", "ikit_section");
define("IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_ID", "post_external_id");
define("IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_IMAGE", "post_external_image");
define("IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_LINK_URL", "post_external_link_url");
define("IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_DATE", "post_external_date");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_IMAGE_GALLERY", "image_gallery");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_PREVIEW_DESCRIPTION", "preview_description");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_START_TIME_HOUR", "start_time_0_hour");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_START_TIME_MINUTE", "start_time_0_minute");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_END_TIME_HOUR", "end_time_0_hour");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_END_TIME_MINUTE", "end_time_0_minute");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_NAME", "location_name");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_ADDRESS_1", "location_address_1");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_ADDRESS_2", "location_address_2");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_CITY", "location_city");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_STATE_PROVINCE", "location_state_province");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_POSTAL_CODE", "location_postal_code");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_LOCATION_COUNTRY", "location_country");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_URL", "url");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_INTERNAL_URL_NAME", "url_name");

define("IKIT_CUSTOM_FIELD_IKIT_EVENT_EXTERNAL_ID", "event_external_id");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_ID", "event_id");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_ADDITIONAL_INFORMATION", "additional_information");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE", "end_date");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE", "start_date");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS", "status");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_DISPLAY_PRIORITY", "display_priority");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_REGISTRATION_TYPE", "registration_type");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE", "service");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_ID", "event_eventbrite_id"); # Requires a separate custom field to prevent conflicting with etouches event ids, all other fields are shared
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_ENABLED", "eventbrite_sync_enabled");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_EVENTBRITE_SYNC_DATA", "eventbrite_sync_data");
define("IKIT_CUSTOM_FIELD_IKIT_PORTFOLIO_ID", "portfolio_id");
define("IKIT_CUSTOM_FIELD_IKIT_PORTFOLIO_CHAPTER", "chapter");
define("IKIT_CUSTOM_FIELD_IKIT_JOB_ID", "job_id");
define("IKIT_CUSTOM_FIELD_IKIT_JOB_CITY", "city");
define("IKIT_CUSTOM_FIELD_IKIT_JOB_STATE", "state");
define("IKIT_CUSTOM_FIELD_IKIT_JOB_LEVEL", "job_level");
define("IKIT_CUSTOM_FIELD_IKIT_JOB_EXPERTISE_AREA", "expertise_area");
define("IKIT_CUSTOM_FIELD_IKIT_IMAGE_GALLERY_IMAGE_GALLERY", "image_gallery");
define("IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE", "external_source");
define("IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_LINK_URL", "external_source_link_url");
define("IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME", "external_source_display_name");
define("IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY", "ordering_display_priority");
define("IKIT_CUSTOM_FIELD_IKIT_PERSON_DISPLAY_PRIORITY", "display_priority");
define("IKIT_CUSTOM_FIELD_IKIT_PERSON_IMAGE_GALLERY", "image_gallery");
define("IKIT_CUSTOM_FIELD_IKIT_PERSON_POSITIONS", "positions");

// Custom field values
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_REGISTRATION_TYPE_ENABLED", "0");
define("IKIT_CUSTOM_FIELD_IKIT_EVENT_REGISTRATION_TYPE_DISABLED", "1");

// Ikit sections slugs
define("IKIT_SLUG_IKIT_SECTION_NEWS", "ikit-section-news");
define("IKIT_SLUG_IKIT_SECTION_ABOUT_US", "ikit-section-about-us");
define("IKIT_SLUG_IKIT_SECTION_EVENT", "ikit-section-event");
define("IKIT_SLUG_IKIT_SECTION_PORTFOLIO", "ikit-section-portfolio");
define("IKIT_SLUG_IKIT_SECTION_JOB", "ikit-section-job");

// Page Slugs
define("IKIT_SLUG_PAGE_NEWS", "news");
define("IKIT_SLUG_PAGE_EVENTS", "events");
define("IKIT_SLUG_PAGE_EVENTS_PAST", "events-past");
define("IKIT_SLUG_PAGE_ABOUT_US", "about-us");
define("IKIT_SLUG_PAGE_JOBS", "jobs");
define("IKIT_SLUG_PAGE_PORTFOLIOS", "portfolios");
define("IKIT_SLUG_PAGE_CONTACT", "contact");
define("IKIT_SLUG_PAGE_GET_INVOLVED", "get-involved");
define("IKIT_SLUG_PAGE_MEMBERSHIP", "membership");
define("IKIT_SLUG_PAGE_RESOURCES", "resources");
define("IKIT_SLUG_PAGE_IKIT_FEED", "ikit-feed");
define("IKIT_SLUG_PAGE_MEMBER_DIRECTORY", "member-directory");
define("IKIT_SLUG_PAGE_MEMBERSHIP_RATES", "membership-rates");

// Category slugs
define("IKIT_SLUG_CATEGORY_FEATURED", "featured");

// Misc slugs
define("IKIT_SLUG_IKIT_IMAGE_GALLERY_FEATURED", "ikit-image-gallery-featured");

// Post types
define("IKIT_POST_TYPE_IKIT_LINK", "ikit_link");
define("IKIT_POST_TYPE_IKIT_SPONSOR", "ikit_sponsor");
define("IKIT_POST_TYPE_IKIT_SECTION", "ikit_section");
define("IKIT_POST_TYPE_IKIT_EVENT", "ikit_event");
define("IKIT_POST_TYPE_IKIT_PORTFOLIO", "ikit_portfolio");
define("IKIT_POST_TYPE_IKIT_JOB", "ikit_job");
define("IKIT_POST_TYPE_IKIT_IMAGE_GALLERY", "ikit_image_gallery");
define("IKIT_POST_TYPE_IKIT_POST_EXTERNAL", "ikit_post_external");
define("IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL", "ikit_event_external");
define("IKIT_POST_TYPE_IKIT_EVENT_INTERNAL", "ikit_event_internal");
define("IKIT_POST_TYPE_IKIT_PERSON", "ikit_person");

// Plugin options
define("IKIT_PLUGIN_OPTION_GROUP_GENERAL_SETTINGS", "ikit_general_settings");
define("IKIT_PLUGIN_OPTION_TWITTER_USERNAME", "twitter_username");
define("IKIT_PLUGIN_OPTION_FLICKR_USER_ID", "flickr_user_id");
define("IKIT_PLUGIN_OPTION_VIMEO_USER_ID", "vimeo_user_id");
define("IKIT_PLUGIN_OPTION_FACEBOOK_ID", "facebook_id");
define("IKIT_PLUGIN_OPTION_PINTEREST_USERNAME", "pinterest_username");
define("IKIT_PLUGIN_OPTION_LINKEDIN_GROUP_ID", "linkedin_group_id");
define("IKIT_PLUGIN_OPTION_YOUTUBE_USERNAME", "youtube_username");
define("IKIT_PLUGIN_OPTION_INSTAGRAM_USERNAME", "instagram_username");
define("IKIT_PLUGIN_OPTION_INSTAGRAM_USER_ID", "instagram_user_id");

define("IKIT_PLUGIN_OPTION_MAILCHIMP_SIGNUP_FORM_URL", "mailchimp_signup_form_url");
define("IKIT_PLUGIN_OPTION_EMBED_CODES", "embed_codes");
define("IKIT_PLUGIN_OPTION_LOCKING_DISABLED", "locking_disabled");
define("IKIT_PLUGIN_OPTION_EVENTBRITE_SYNC_VALIDATION_DISABLED", "eventbrite_sync_validation_disabled");
define("IKIT_PLUGIN_OPTION_FILTER_IKIT_EVENT_FOLDER_ID", "filter_ikit_event_folder_id");
define("IKIT_PLUGIN_OPTION_FILTER_IKIT_JOB_STATE", "filter_ikit_job_state");
define("IKIT_PLUGIN_OPTION_FILTER_IKIT_JOB_CITY", "filter_ikit_job_city");
define("IKIT_PLUGIN_OPTION_FILTER_IKIT_JOB_ZIP", "filter_ikit_job_zip");
define("IKIT_PLUGIN_OPTION_FILTER_IKIT_JOB_DISTANCE_IN_MILES", "filter_ikit_job_distance_in_miles");
define("IKIT_PLUGIN_OPTION_FILTER_IKIT_IMUS_CHAPTER", "filter_ikit_imus_chapter");
define("IKIT_PLUGIN_OPTION_FILTER_IKIT_EVENTBRITE_API_USER_KEY", "filter_ikit_eventbrite_api_user_key");
define("IKIT_PLUGIN_OPTION_FILTER_IKIT_EXTERNAL_IKIT_URLS", "filter_ikit_external_ikit_urls");
define("IKIT_PLUGIN_OPTION_DASHBOARD_WIDGET_CHAPTER_RESOURCES_CONTENT", "dashboard_widget_chapter_resources_content");

// External sources
define("IKIT_EXTERNAL_SOURCE_NATIONAL", "national");

// Misc
define("IKIT_MEMBER_TYPE_FRIEND", "FRND");
define("IKIT_MEMBER_TYPE_SUSTAINING_MEMBER", "SUS");
define("IKIT_MEMBER_TYPE_CONTRIBUTOR", "CONTR");
define("IKIT_MEMBER_TYPE_TRUSTEE", "TRUST");
define("IKIT_MEMBER_TYPE_DESIGN_LEADER", "LEAD");
define("IKIT_MEMBER_TYPE_DESIGN_SUPPORTER", "SUP");
define("IKIT_MEMBER_TYPE_STUDENT", "STUDENT");
define("IKIT_MEMBER_TYPE_NONE", "NONE");
define('IKIT_EVENT_SERVICE_INTERNAL', 'internal');
define('IKIT_EVENT_SERVICE_ETOUCHES', 'etouches');
define('IKIT_EVENT_SERVICE_EVENTBRITE', 'eventbrite');
define('IKIT_EVENT_SERVICE_EXTERNAL', 'external');
define("IKIT_FACEBOOK_PUBLIC_ACCESS_TOKEN", "181400691980726|Gxf_mKadYSZNCkVWyDT90BYb3Zo");
define("IKIT_DIR_CACHE", WP_CONTENT_DIR . "/cache/ikit/");
define("IKIT_DIR_FILE_CACHE", WP_CONTENT_DIR . "/cache/ikit/file/");
define("IKIT_EVENT_FOLDER_ID_NATIONAL", '6611');
define("IKIT_EVENT_FOLDER_ID_NATIONAL_REGISTRATION_TYPE_DISABLED", '48082');
define("IKIT_PLUGIN_UPDATE_URL", "http://ikit.aiga.org//httpdocs/ikit_update/plugins/ikit/update.chk");
define('IKIT_PLUGIN_UPDATE_REMOTE_FETCH_FREQUENCY_SECS', 3600);
define('IKIT_PLUGIN_UPDATE_REMOTE_FETCH_SCHEDULE_OFFSET_SECS', 3600);
define('IKIT_SPONSOR_LOCAL_NUM_SHORT', 3);
define('IKIT_PROXY_SERVER_IP', '199.15.248.179');
define('IKIT_PROXY_SERVER_PORT', '3128');
define("IKIT_AJAX_URL", get_bloginfo('url') . '/wp-admin/admin-ajax.php');

// Image sizes
define("IKIT_IMAGE_SIZE_MEDIUM_Z", "ikit_medium_z");
define("IKIT_IMAGE_SIZE_MEDIUM_C", "ikit_medium_c");
define("IKIT_IMAGE_SIZE_MEDIUM", "ikit_medium");

// Misc option keys (used with get_option)
define("IKIT_OPTION_KEY_PLUGIN_UPDATE_INFO", "ikit_plugin_update_info");
define('IKIT_XML_NAMESPACE', "ikit");
define('IKIT_XML_NAMESPACE_URI', "http://aiga.org/ikit/elements/1.0/");

// SSO
define("IKIT_LOGIN_BRIDGE_URL", "http://my.aiga.org/services/loginbridge/default.aspx");
define("IKIT_LOGIN_BRIDGE_REDIRECT_PARAM_NAME", "returnURL");

// Member API
define("IKIT_AIGA_MEMBER_API_BASE_URL", "http://www.aiga.org/services/vangoservice.asmx/");
define("IKIT_AIGA_MEMBER_API_TOKEN", "3F2504E0-4F89-11D3-9A0C-0305E82C33UD");
define("IKIT_AIGA_MEMBER_API_TOKEN_PARAM_NAME", "token");
define("IKIT_AIGA_MEMBER_API_KEY_PARAM_NAME", "key");

/**
 * Includes
 */
if (!class_exists('SimplePie')) {
    include_once dirname( __FILE__ ) . '/includes/library/simplepie.inc';
}
include_once dirname( __FILE__ ) . '/includes/library/filecache.php';
include_once dirname( __FILE__ ) . '/includes/library/mobiledetect/Mobile_Detect.php';

include_once dirname( __FILE__ ) . '/includes/utils.php';
include_once dirname( __FILE__ ) . '/includes/render_utils.php';
include_once dirname( __FILE__ ) . '/includes/locking.php';
include_once dirname( __FILE__ ) . '/includes/shortcodes.php';
include_once dirname( __FILE__ ) . '/includes/social.php';
include_once dirname( __FILE__ ) . '/includes/social_template.php';
include_once dirname( __FILE__ ) . '/includes/sso.php';
include_once dirname( __FILE__ ) . '/includes/national.php';
include_once dirname( __FILE__ ) . '/includes/national_template.php';
include_once dirname( __FILE__ ) . '/includes/portfolio.php';
include_once dirname( __FILE__ ) . '/includes/portfolio_template.php';
include_once dirname( __FILE__ ) . '/includes/job.php';
include_once dirname( __FILE__ ) . '/includes/job_template.php';
include_once dirname( __FILE__ ) . '/includes/event.php';
include_once dirname( __FILE__ ) . '/includes/event_internal.php';
include_once dirname( __FILE__ ) . '/includes/event_internal_template.php';
include_once dirname( __FILE__ ) . '/includes/event_etouches.php';
include_once dirname( __FILE__ ) . '/includes/event_etouches_template.php';
include_once dirname( __FILE__ ) . '/includes/event_eventbrite.php';
include_once dirname( __FILE__ ) . '/includes/event_eventbrite_template.php';
include_once dirname( __FILE__ ) . '/includes/event_template.php';
include_once dirname( __FILE__ ) . '/includes/post_template.php';
include_once dirname( __FILE__ ) . '/includes/member.php';
include_once dirname( __FILE__ ) . '/includes/member_template.php';
include_once dirname( __FILE__ ) . '/includes/person_template.php';
include_once dirname( __FILE__ ) . '/includes/widgets.php';
include_once dirname( __FILE__ ) . '/includes/menu_settings.php';
include_once dirname( __FILE__ ) . '/includes/dashboard_widgets.php';
include_once dirname( __FILE__ ) . '/includes/task.php';
include_once dirname( __FILE__ ) . '/includes/acf.php';
include_once dirname( __FILE__ ) . '/includes/event_external.php';
include_once dirname( __FILE__ ) . '/includes/event_external_template.php';
include_once dirname( __FILE__ ) . '/includes/post_external.php';
include_once dirname( __FILE__ ) . '/includes/post_external_template.php';
include_once dirname( __FILE__ ) . '/includes/external_ikit.php';
include_once dirname( __FILE__ ) . '/includes/menu_control_panel.php';
include_once dirname( __FILE__ ) . '/includes/menu_change_password.php';
include_once dirname( __FILE__ ) . '/includes/api.php';
include_once dirname( __FILE__ ) . '/includes/updates.php'; // Leave last

/**
 * Some unneccessary warning and info messages may print to the screen,
 * so just report the actual errors instead
 */
error_reporting(E_ERROR | E_COMPILE_ERROR | E_CORE_ERROR);

/**
 * Register custom post types
 */
add_action( 'init', 'ikit_create_post_types' );
function ikit_create_post_types() {

    $menu_position = 1;

    // Events
    register_post_type( IKIT_POST_TYPE_IKIT_EVENT,
        array(
            'labels' => array(
                'name' => __( 'iKit Events' ),
                'singular_name' => __( 'iKit Event' )
            ),
        'public' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'supports' => array('title', 'comments', 'revisions'),
        'menu_position' => $menu_position++,
        'show_in_menu' => 'ikit-menu',
        'taxonomies' => array('category'),
        'rewrite' => array('slug' => 'event')
        )
    );

    // Portfolios
    register_post_type( IKIT_POST_TYPE_IKIT_PORTFOLIO,
        array(
            'labels' => array(
                'name' => __( 'iKit Portfolios' ),
                'singular_name' => __( 'iKit Portfolio' )
            ),
        'public' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'supports' => array('title'),
        'menu_position' => $menu_position++,
        'show_in_menu' => 'ikit-menu',
        'taxonomies' => array('category')
        )
    );

    // Jobs
    register_post_type( IKIT_POST_TYPE_IKIT_JOB,
        array(
            'labels' => array(
                'name' => __( 'iKit Jobs' ),
                'singular_name' => __( 'iKit Job' )
            ),
        'public' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'supports' => array('title', 'editor'),
        'menu_position' => $menu_position++,
        'show_in_menu' => 'ikit-menu',
        'taxonomies' => array('category'),
        'rewrite' => array('slug' => 'job')
        )
    );

    // Sponsors are supporters of the chapter
    register_post_type( IKIT_POST_TYPE_IKIT_SPONSOR,
        array(
            'labels' => array(
                'name' => __( 'iKit Sponsors' ),
                'singular_name' => __( 'iKit Sponsor' )
            ),
        'public' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'supports' => array('title', 'editor'),
        'menu_position' => $menu_position++,
        'taxonomies' => array('category'),
        'show_in_menu' => 'ikit-menu'
        )
    );

    // Image galleries are collections of images
    register_post_type( IKIT_POST_TYPE_IKIT_IMAGE_GALLERY,
        array(
            'labels' => array(
                'name' => __( 'iKit Image Galleries' ),
                'singular_name' => __( 'iKit Image Gallery' )
            ),
        'public' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'supports' => array('title'),
           'menu_position' => $menu_position++,
        'show_in_menu' => 'ikit-menu'
        )
    );

    // Sections are the color coded groupings of content
    register_post_type( IKIT_POST_TYPE_IKIT_SECTION,
        array(
            'labels' => array(
                'name' => __( 'iKit Sections' ),
                'singular_name' => __( 'iKit Section' )
            ),
        'public' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'supports' => array('title'),
           'menu_position' => $menu_position++,
        'show_in_menu' => 'ikit-menu'
        )
    );

    // External Post
    register_post_type( IKIT_POST_TYPE_IKIT_POST_EXTERNAL,
        array(
            'labels' => array(
                'name' => __( 'iKit External Posts' ),
                'singular_name' => __( 'iKit External Post' )
            ),
        'public' => true,
        'supports' => array('title'),
        'menu_position' => $menu_position++,
        'show_in_menu' => 'ikit-menu',
        'taxonomies' => array('category'),
        )
    );

    // External Events
    register_post_type( IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL,
        array(
            'labels' => array(
                'name' => __( 'iKit External Events' ),
                'singular_name' => __( 'iKit External Event')
            ),
        'public' => true,
        'supports' => array('title'),
        'menu_position' => $menu_position++,
        'show_in_menu' => 'ikit-menu',
        'taxonomies' => array('category'),
        )
    );


    // Internal events
    register_post_type( IKIT_POST_TYPE_IKIT_EVENT_INTERNAL,
        array(
            'labels' => array(
                'name' => __( 'iKit Community Events' ),
                'singular_name' => __( 'iKit Community Event' )
            ),
        'public' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'supports' => array('title', 'editor'),
        'menu_position' => $menu_position++,
        'show_in_menu' => 'ikit-menu',
        'taxonomies' => array('category'),
        'rewrite' => array('slug' => 'event-internal')
        )
    );

    // Persons
    register_post_type( IKIT_POST_TYPE_IKIT_PERSON,
        array(
            'labels' => array(
                'name' => __( 'iKit Persons' ),
                'singular_name' => __( 'iKit Person' )
        ),
        'public' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'supports' => array('title', 'editor'),
        'menu_position' => $menu_position++,
        'show_in_menu' => 'ikit-menu',
        'taxonomies' => array('category'),
        'rewrite' => array('slug' => 'person')
        )
    );

    // 20150404, update the permalinks
    if(!get_option('ikit_update_flush_rewrite_rules_20150404')) {
        update_option('ikit_update_flush_rewrite_rules_20150404', 1);
        flush_rewrite_rules();
    }

    // 20140522, update the permalinks, must be placed here to be run directly after
    // the new post type is added
    if(!get_option('ikit_update_flush_rewrite_rules_20140522')) {
        update_option('ikit_update_flush_rewrite_rules_20140522', 1);
        flush_rewrite_rules();
    }

}

// Register custom taxonomies
add_action( 'init', 'ikit_create_taxonomies');
function ikit_create_taxonomies() {

  register_taxonomy('ikit_link_category',
     array('ikit_link'),
     array(
         'hierarchical' => true,
         'show_ui' => true,
         'labels' => array('name' => 'iKit Link Categories')
         )
     );

}

function ikit_admin_enqueue() {

    wp_enqueue_style('css_ikit_admin', ikit_get_plugin_url('css/ikit_admin.css'));
    wp_enqueue_script('js_ikit_admin', ikit_get_plugin_url('js/ikit_admin.js'));

    // Wordpress admin now already comes included with latest jQuery, so jQuery inclusion
    // is no longer needed

    // wp_enqueue_script('js_jquery', ikit_get_plugin_url('js/jquery-1.10.2.min.js'));

}
add_action('admin_init', 'ikit_admin_enqueue');

/**
 * Plugin Menu
 */
add_action('admin_menu', 'ikit_admin_menu_add', 5);

function ikit_admin_menu_add() {
    add_menu_page( 'iKit', 'iKit', 'manage_options', 'ikit-menu', null, null, 30 );
}


/**
 * Register additional image sizes, these are based on flickr image sizing for general purpose usage
 */
add_image_size(IKIT_IMAGE_SIZE_MEDIUM, 500, 500);
add_image_size(IKIT_IMAGE_SIZE_MEDIUM_Z, 640, 640);
add_image_size(IKIT_IMAGE_SIZE_MEDIUM_C, 800, 800);

/**
 * Generate cache directories
 */
if(!is_dir(IKIT_DIR_CACHE)) {
    mkdir(IKIT_DIR_CACHE, 0777, true);
}
if(!is_dir(IKIT_DIR_FILE_CACHE)) {
    mkdir(IKIT_DIR_FILE_CACHE, 0777, true);
}

/**
 * Log number of logins for usage statistics
 */
add_action('wp_login', 'ikit_login_stats');
function ikit_login_stats($login) {

    // Add the option if it doesn't exist
    add_option('ikit_stats_num_logins', 0);
    update_option('ikit_stats_num_logins', intval(get_option('ikit_stats_num_logins')) + 1);
}

/**
 * Disable update nags for certain plugins
 */
add_filter('site_transient_update_plugins', 'ikit_disable_update_plugin_warning');
function ikit_disable_update_plugin_warning($value) {
    unset($value->response['advanced-custom-fields/acf.php']);
    return $value;
}

function ikit_plugin_admin_notices(){
    global $pagenow;
    if($pagenow == 'plugins.php') {
        ?>

        <?php
    }

    global $g_admin_notices;
    foreach($g_admin_notices as $admin_notice) {
        print($admin_notice);
    }
}
add_action('admin_notices', 'ikit_plugin_admin_notices');

/**
 * Adds additional ways to order posts when posts are saved, stored in custom fields,
 * additional ordering schemes should be added here, the technique used here
 * is to do the ordering logic on post saves, then the query is simply to order by
 * that pre-calculated field
 */
add_action( 'save_post', 'ikit_save_post_orderings');
add_action( 'wp_insert_post', 'ikit_save_post_orderings') ;
function ikit_save_post_orderings() {

    $persons = get_posts(array('post_type'=> array(IKIT_POST_TYPE_IKIT_PERSON),'posts_per_page'=>9999));
    $posts = get_posts(array('post_type'=> array('post', IKIT_POST_TYPE_IKIT_POST_EXTERNAL),'posts_per_page'=>9999));
    $events = get_posts(array('post_type'=>array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL, IKIT_POST_TYPE_IKIT_EVENT_INTERNAL),'posts_per_page'=>9999));

    {

        // By display order, admins optionally set a display priority on events and news items, this value
        // is then multiplied by the post date as an integer value, that value then represents the priority field
        // which can then be used to order the events or news postings

        $impossible_future_date = strtotime('2200-01-01');
        $impossible_past_date = strtotime('1900-01-01');

        foreach($persons as $person) {
            $ordering_display_priority = null;
            $display_priority = get_post_meta($person->ID, IKIT_CUSTOM_FIELD_IKIT_PERSON_DISPLAY_PRIORITY, true);
            if(empty($display_priority)) {
                // Normally will order by the last name, which is whatever we find after splitting by spaces in the last index
                $post_title_split = explode(' ', $person->post_title);
                $ordering_display_priority = strtolower($post_title_split[count($post_title_split)-1]);
            } else {
                // Period to start will always appear before any letter, then we use a numeric value to generate a pseudo string that will order correctly...
                $ordering_display_priority = '.' . (($display_priority * 60 * 60 * 24) - $impossible_future_date);
            }
            ikit_add_or_update_post_meta($person->ID, IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY, $ordering_display_priority, true);

        }

        foreach($posts as $post) {

            $ordering_display_priority = null;
            $display_priority = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_POST_DISPLAY_PRIORITY, true);
            if(empty($display_priority)) {
                $ordering_display_priority = strtotime($post->post_date);
            } else {
                $ordering_display_priority = $impossible_future_date + ($display_priority * 60 * 60 * 24); // Take the impossible future date and append the number of days of display priority
            }
            ikit_add_or_update_post_meta($post->ID, IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY, $ordering_display_priority, true);
        }

        foreach($events as $event) {

            $event_start_date = get_post_meta($event->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, true);

            $ordering_display_priority = null;
            $display_priority = get_post_meta($event->ID, IKIT_CUSTOM_FIELD_POST_DISPLAY_PRIORITY, true);
            if(empty($display_priority)) {
                $ordering_display_priority = strtotime($event_start_date);
            } else {
                $ordering_display_priority = $impossible_past_date - ($display_priority * 60 * 60 * 24); // Take the impossible future date and append the number of days of display priority
            }
            ikit_add_or_update_post_meta($event->ID, IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY, $ordering_display_priority, true);
        }

    }

}

/**
 * Enable categories on pages
 */
add_action('init', 'ikit_enable_category_taxonomy_for_pages', 500);

function ikit_enable_category_taxonomy_for_pages() {
    register_taxonomy_for_object_type('category', 'page');
}

/**
 * Force SSL for admin pages
 */
if(WP_DEBUG == false) {
    force_ssl_admin(true);
}

/**
 * Force password reset
 */
add_filter('login_redirect', 'ikit_login_redirect',  10, 3);
function ikit_login_redirect($redirect_to, $request, $user) {

    // Redirect to reset password screen
    if(!is_a($user, WP_Error)) {

        $should_change_password = false;

        // If the user has not changed their password since a specified date
        $change_password_datetime = get_user_meta($user->ID, 'ikit_change_password_datetime', true);
        if(empty($change_password_datetime)) {
            $should_change_password = true;
        }
        else {

            $expiry = strtotime("2020-01-01 00:00:00"); // Any password set after this expiration date is invalid
            if(strtotime($change_password_datetime) > $expiry) {
                $should_change_password = true;
            }
        }

        if($should_change_password) {
            return admin_url() . 'admin.php?page=ikit-change-password';
        }
    }

    return $redirect_to;

}

/**
 * Add globals below
 */
global $g_national_sponsors; // Sponsors
global $g_local_sponsors; // Local sponsors
global $g_local_sponsors_short; // Local sponsors trimmed to max size
global $g_options; // Settings
global $g_admin_notices;

// Notices to appear in admin
$g_admin_notices = array();

// Get the sponsors
$args = array(
    'numberposts'     => 999,
    'offset'          => 0,
    'orderby'         => 'meta_value_num',
    'meta_key'        => 'display_order',
    'order'           => 'ASC',
    'post_type'       => 'ikit_sponsor',
    'post_status'     => 'publish',
    'meta_query'      => array(array('key' => IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, 'value' => IKIT_EXTERNAL_SOURCE_NATIONAL, 'compare' => '=')));

$g_national_sponsors = get_posts( $args );

// Get the local sponsors
$args['meta_query'] = null;
$national_sponsor_ids = array();
foreach($g_national_sponsors as $sponsor) {
    array_push($national_sponsor_ids, $sponsor->ID);
}
$args['post__not_in'] = $national_sponsor_ids;
$g_local_sponsors = get_posts( $args );

$g_local_sponsors_short = $g_local_sponsors;
if(count($g_local_sponsors_short) > IKIT_SPONSOR_LOCAL_NUM_SHORT) {
    shuffle($g_local_sponsors_short);
    $g_local_sponsors_short = array_slice($g_local_sponsors_short, 0, IKIT_SPONSOR_LOCAL_NUM_SHORT);
}

$g_options = get_option(IKIT_PLUGIN_OPTION_GROUP_GENERAL_SETTINGS);

?>