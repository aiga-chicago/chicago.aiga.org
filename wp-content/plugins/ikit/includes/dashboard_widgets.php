<?php
/**
 * @package Internet_Kit
 */

/**
 * Dashboard widgets available for the iKit, dashboard widgets
 * appear when you first login and are viewing
 * the administrative dashboard.
 *
 * http://codex.wordpress.org/Dashboard_Widgets_API
 *
 */

/**
 * Resources widget
 *
 * @package Internet Kit
 * @subpackage Dashboard Widgets
 */
function ikit_dashboard_widget_resources_function() {
    echo do_shortcode('[ikit_page_content slug="national-dashboard-widget-resources"]');
}

function ikit_dashboard_widget_chapter_resources_function() {

    $dashboard_settings = get_option(IKIT_PLUGIN_OPTION_GROUP_GENERAL_SETTINGS);
    $content = $dashboard_settings[IKIT_PLUGIN_OPTION_DASHBOARD_WIDGET_CHAPTER_RESOURCES_CONTENT];

    if(strlen($content) == 0) {
        if(current_user_can('level_10')) {
            $content = "You can customize this widget's content by going to the <a href='admin.php?page=ikit-menu'>iKit Settings</a> page under Dashboard Widgets.";
        }
    }
    echo wpautop($content);
}

function ikit_dashboard_widget_setup() {
    wp_add_dashboard_widget('ikit_dashboard_widget_resources', 'Resources', 'ikit_dashboard_widget_resources_function');
    wp_add_dashboard_widget('ikit_dashboard_widget_chapter_resources', 'Chapter Resources', 'ikit_dashboard_widget_chapter_resources_function');

    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);

}

add_action('wp_dashboard_setup', 'ikit_dashboard_widget_setup' );

?>