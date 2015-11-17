<?php

/**
 * Theme constants
 */
define("IKIT_ONE_NEWS_POSTS_PER_PAGE", 5);
define("IKIT_ONE_EVENTS_POSTS_PER_PAGE", 5);
define("IKIT_ONE_PORTFOLIOS_POSTS_PER_PAGE", 10);
define("IKIT_ONE_CATEGORY_POSTS_PER_PAGE", 10);
define("IKIT_ONE_JOBS_POSTS_PER_PAGE", 10);
define("IKIT_ONE_MAX_SEARCH_RESULTS", 25);

define("IKIT_ONE_HOME_NEWS_POSTS", 3);
define("IKIT_ONE_HOME_EVENTS_POSTS", 3);

define("IKIT_ONE_HEADER_IMAGE_WIDTH", 1210);
define("IKIT_ONE_HEADER_IMAGE_HEIGHT", 70);

define("IKIT_ONE_PAGE_DESCRIPTION_MAX_LENGTH", 300);

define("IKIT_ONE_THEME_UPDATE_URL", "http://ikit.aiga.org//httpdocs/ikit_update/themes/ikit_one/update.chk");
define('IKIT_ONE_THEME_UPDATE_REMOTE_FETCH_FREQUENCY_SECS', 3600);
define('IKIT_ONE_THEME_UPDATE_REMOTE_FETCH_SCHEDULE_OFFSET_SECS', 3600);

define("IKIT_ONE_OPTION_KEY_THEME_UPDATE_INFO", "ikit_one_theme_update_info");

define("IKIT_ONE_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT", "comments_enabled_ikit_event");
define("IKIT_ONE_THEME_OPTION_COMMENTS_ENABLED_PAGE", "comments_enabled_page");
define("IKIT_ONE_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED", "event_attendee_list_enabled");
define("IKIT_ONE_THEME_OPTION_EVENTS_POSTS_PER_PAGE", "events_posts_per_page");
define("IKIT_ONE_THEME_OPTION_HOME_EVENTS_POSTS_PER_PAGE", "home_events_posts_per_page");
define("IKIT_ONE_THEME_OPTION_HOME_NEWS_POSTS_PER_PAGE", "home_news_posts_per_page");
define("IKIT_ONE_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE", "aiga_header_logo_image");
define("IKIT_ONE_THEME_OPTION_FAV_ICON", "fav_icon");
define("IKIT_ONE_THEME_OPTION_CUSTOM_FONT_EMBED_CODE", "custom_font_embed_code");
define("IKIT_ONE_THEME_OPTION_GROUP_GENERAL", "ikit_one_general");
define("IKIT_ONE_THEME_OPTION_HOME_EVENTS_NEWS_ORDER_TOGGLE", "home_events_news_order_toggle");

/**
 * Theme setup
 */
add_action('after_setup_theme', 'ikit_one_setup');

function ikit_one_setup() {

    // Custom header support
    add_custom_image_header('ikit_one_header_style', 'ikit_one_admin_header_style');

    define('HEADER_IMAGE_WIDTH', apply_filters('ikit_header_image_width', IKIT_ONE_HEADER_IMAGE_WIDTH));
    define('HEADER_IMAGE_HEIGHT', apply_filters('ikit_header_image_height', IKIT_ONE_HEADER_IMAGE_HEIGHT));
    define('HEADER_TEXTCOLOR', 'FFFFFF');

    // Custom background support
    add_custom_background();

    // Includes
    require(get_template_directory() . '/includes/theme_options.php');
    require(get_template_directory() . '/includes/widgets.php');
    require(get_template_directory() . '/includes/updates.php');


    // Set defaults for theme options
    $theme_options = get_option(IKIT_ONE_THEME_OPTION_GROUP_GENERAL);
    $theme_options_updated = false;
    if(!$theme_options[IKIT_ONE_THEME_OPTION_EVENTS_POSTS_PER_PAGE]) {
      $theme_options[IKIT_ONE_THEME_OPTION_EVENTS_POSTS_PER_PAGE] = IKIT_ONE_EVENTS_POSTS_PER_PAGE;
      $theme_options_updated = true;
    }
    if(!$theme_options[IKIT_ONE_THEME_OPTION_HOME_NEWS_POSTS_PER_PAGE]) {
      $theme_options[IKIT_ONE_THEME_OPTION_HOME_NEWS_POSTS_PER_PAGE] = IKIT_ONE_HOME_NEWS_POSTS;
      $theme_options_updated = true;
    }
    if(!$theme_options[IKIT_ONE_THEME_OPTION_HOME_EVENTS_POSTS_PER_PAGE]) {
      $theme_options[IKIT_ONE_THEME_OPTION_HOME_EVENTS_POSTS_PER_PAGE] = IKIT_ONE_HOME_EVENTS_POSTS;
      $theme_options_updated = true;
    }
    if(isset($theme_options[IKIT_ONE_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED]) == false) {
      $theme_options[IKIT_ONE_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED] = 1;
      $theme_options_updated = true;
    }
    if(isset($theme_options[IKIT_ONE_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT]) == false) {
      $theme_options[IKIT_ONE_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT] = 0;
      $theme_options_updated = true;
    }
    if(isset($theme_options[IKIT_ONE_THEME_OPTION_FEATURED_IMAGE_GALLERY_MAX_HEIGHT_DISABLED]) == false) {
      $theme_options[IKIT_ONE_THEME_OPTION_FEATURED_IMAGE_GALLERY_MAX_HEIGHT_DISABLED] = 0;
      $theme_options_updated = true;
    }
    if($theme_options_updated) {
        update_option(IKIT_ONE_THEME_OPTION_GROUP_GENERAL, $theme_options);
    }

}

function ikit_one_header_style() {

}

function ikit_one_admin_header_style() {

}

/**
 * Theme options
 */
function ikit_one_options_setup() {
    add_theme_page('Theme Options', 'Theme Options', 'manage_options', 'ikit-one-theme-options', 'ikit_one_theme_options_page_render');
}

add_action('admin_menu', 'ikit_one_options_setup');

/**
 * Theme support for widgets
 */
add_action('widgets_init', 'ikit_one_widgets_setup');

function ikit_one_widgets_setup() {

    $before_widget = '<div id="%1$s" class="widget ikit-widget %2$s"><div class="box-container"><div class="box"><div class="box-widget-begin"></div><div class="box-widget-content">';
    $after_widget = '</div><div class="box-widget-end"></div></div></div><div class="box-close"></div></div>';

    register_sidebar( array(
        'name' => __('Primary Sidebar'),
        'id' => 'primary-sidebar',
        'before_widget' => $before_widget,
        'after_widget' => $after_widget
    ));

    register_sidebar( array(
        'name' => __('Secondary Sidebar'),
        'id' => 'secondary-sidebar',
        'before_widget' => $before_widget,
        'after_widget' => $after_widget
    ));

    // Remove default widgets
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Nav_Menu_Widget');
    unregister_widget('Ikit_Facebook_Widget');
    unregister_widget('Ikit_Twitter_Widget');
    unregister_widget('Ikit_Vimeo_Widget');
    unregister_widget('Ikit_YouTube_Widget');
    unregister_widget('Ikit_Flickr_Widget');
    unregister_widget('Ikit_Instagram_Widget');
    unregister_widget('Ikit_News_Widget');
    unregister_widget('Ikit_Events_Widget');
    unregister_widget('Ikit_Quote_Widget');
    unregister_widget('Ikit_Events_Billboard_Widget');
    unregister_widget('Ikit_News_Billboard_Widget');
    unregister_widget('Ikit_Video_Billboard_Widget');

//	unregister_widget('WP_Widget_Pages');
//	unregister_widget('WP_Widget_Links');
//	unregister_widget('WP_Widget_Text');
//	unregister_widget('WP_Widget_Recent_Posts');

}

add_filter('widget_title', 'customize_widget_title', 11, 3); // 11 is one above the default priority of 10, meaning it will occur after any other default widget_title filters
function customize_widget_title($title, $instance, $id_base) {

    $classname = $id_base;
    if(empty($instance[IKIT_WIDGET_ATTRIBUTE_BANNER_CLASS]) == false) {
        $classname = $instance[IKIT_WIDGET_ATTRIBUTE_BANNER_CLASS];
    }

    return get_ikit_one_render_banner_header(null, $classname, $title, false);
}

/**
 * Theme support for navigation menus
 */
function ikit_one_nav_menus_setup() {

    // XXX Nasty hack, for some reason whenever we register custom nav menus in the wp 4.3.x it breaks the customizer
    // so we basically just don't register the custom menu when using the customizer, likely the issue is it can't find
    // custom menus so fails with a js error. Hopefully fixed in later versions of WP.
    if(strpos(ikit_get_request_url(), 'customize.php') !== false) {

    }
    else {
        register_nav_menus(
        array('nav-menu-main' => __('Main Menu'))
        );
    }

}

add_action( 'admin_init', 'ikit_one_nav_menus_setup' );

/**
 * Render a callout for a box image
 */
function get_ikit_one_render_image_callout($ikit_section_id, $title = null) {

    if($ikit_section_id != null) {
        $ikit_section = get_post($ikit_section_id);

        if($classname == null) {
            $classname = $ikit_section->post_name;
        }

    }

    return '<div class="box-section-image-callout background-color-' . $classname . '">' . $title . '</div>';

}

function ikit_one_render_image_callout($ikit_section_id, $title = null) {

    echo get_ikit_one_render_image_callout($ikit_section_id, $title);

}

/**
 * Render a banner for a box section
 */
function get_ikit_one_render_banner_header($ikit_section_id, $classname = null, $title = null, $align_left = true) {

    if($ikit_section_id != null) {
        $ikit_section = get_post($ikit_section_id);

        if($classname == null) {
            $classname = $ikit_section->post_name;
        }

        if($title == null) {
            $title = $ikit_section->post_title;
        }
    }

    $banner_header_html = '';
    $banner_header_html .= '<div class="box-banner box-widget-banner-' . sanitize_title($title) . '">';
    $banner_header_html .= '<table class="box-banner-title-container"><tr>';

    $title_html = '<td class="box-banner-title-container-col0"><div class="box-banner-title background-color-' . $classname . '">' . $title . '</div></td>';
    $title_line_html = '<td class="box-banner-title-container-col1"><div class="box-banner-title-line background-color-' . $classname . '"></div></td>';

    if($align_left) {
        $banner_header_html .= $title_html;
        $banner_header_html .= $title_line_html;
    }
    else {
        $banner_header_html .= $title_line_html;
        $banner_header_html .= $title_html;
    }

    $banner_header_html .= '</tr></table>';
    $banner_header_html .='</div>';
    return $banner_header_html;

}

function ikit_one_render_banner_header($ikit_section_id, $section_slug = null, $section_title = null) {

    echo get_ikit_one_render_banner_header($ikit_section_id, $section_slug, $section_title);

}

/**
 * Render a pager, if you don't give any arguments, it will default to using the built in
 * wordpress paging system urls, that is, appending page/1/ and so on. If you give a URL
 * it will use that instead. You can also customize the labels for the next/prev buttons.
 * You can also force the link to show.
 */
function ikit_one_render_pager($wp_query, $next_label = 'OLDER &gt;', $next_url = null, $next_force_show = false, $prev_label = '&lt; NEWER', $prev_url = null, $prev_force_show = false) {

    if(get_previous_posts_link() || get_next_posts_link() || $next_force_show || $prev_force_show) {

        ?>
        <div class="box-bottom-button-bar"></div>
        <?php

        if(get_previous_posts_link() || $prev_force_show) {
        ?>

            <div class="box-bottom-button box-bottom-left-button">
                <?php
                if($prev_url != null) {
                    ?>
                    <a href="<?php echo $prev_url; ?>"><?php echo $prev_label; ?></a>
                    <?php
                } else {
                    previous_posts_link($prev_label);
                }
                ?>

            </div>

        <?php
        }

        if(get_next_posts_link() || $next_force_show) {
        ?>
            <div class="box-bottom-button box-bottom-right-button">
                <?php
                if($next_url != null) {
                    ?>
                    <a href="<?php echo $next_url; ?>"><?php echo $next_label; ?></a>
                    <?php
                } else {
                    next_posts_link($next_label);
                }
                ?>

            </div>
        <?php
        }

    }

}

/**
 * Get the default event image URL
 */
function ikit_one_get_event_image_default() {
    return get_bloginfo('template_url') . '/images/default_image.png';
}

/**
 * Get the default post image URL
 */
function ikit_one_get_post_image_default() {
    return get_bloginfo('template_url') . '/images/default_image.png';
}

/**
 * Get the image URL for an event, note this does not work for the later ability to set a custom image on the event and should not be used going forward
 * @deprecated
 */
function ikit_one_get_event_image($ikit_event_meta) {
    return ikit_event_get_image_url(-1, $ikit_event_meta, ikit_one_get_event_image_default());
}

/**
 * Get the image URL for usage in a feed given a post
 * @deprecated
 */
function ikit_one_get_post_feed_image_url($post) {

    if($post->post_type == 'post') {
        $feed_image_url = ikit_post_get_image_url($post->ID, IKIT_IMAGE_SIZE_MEDIUM, ikit_one_get_post_image_default());
    }
    else if($post->post_type == IKIT_POST_TYPE_IKIT_EVENT) {
        $ikit_event_meta = ikit_event_get_meta($post->ID);
        $feed_image_url = ikit_event_get_image_url($post->ID, $ikit_event_meta, ikit_one_get_event_image_default());
    }
}

/**
 * Custom editor styles
 */
add_editor_style('css/editor.css'); // Adds support for editor-style.css so you can see the style in editor

/**
 * Append post slug to body class
 */
function ikit_one_body_class($classes) {
    global $post;

    // Posts add the slug and post type for CSS customization
    if(isset($post)) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}

add_filter('body_class', 'ikit_one_body_class');


/**
 * AJAX methods
 */

function ikit_one_ajax_get_event_attendees() {

    $event_id = $_POST['event_id'];
    $event_service = $_POST['event_service'];

    $attendees = ikit_event_get_attendees($event_id, $event_service);
    if($attendees == null) {
        ikit_render_500_error();
    }
    else {
        print json_encode($attendees);
    }

    die();
}

add_action('wp_ajax_nopriv_ikit_one_ajax_get_event_attendees', 'ikit_one_ajax_get_event_attendees' );
add_action('wp_ajax_ikit_one_ajax_get_event_attendees', 'ikit_one_ajax_get_event_attendees' );


/**
 * Custom editor styles
 */
add_filter('tiny_mce_before_init', 'ikit_one_editor_custom_styles');
function ikit_one_editor_custom_styles($settings) {

    // Show style select dropdown
    $settings["theme_advanced_buttons2"] = "styleselect," . $settings["theme_advanced_buttons2"];

    // Custom styles that will be used throughout all ikit themes
    $settings['style_formats'] = json_encode(array (
        array (
            "title" => "Header 1",
            "block" => "div",
            "classes" => "editor-header1 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 2",
            "block" => "div",
            "classes" => "editor-header2 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 3",
            "block" => "div",
            "classes" => "editor-header3 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 4",
            "block" => "div",
            "classes" => "editor-header4 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 5",
            "block" => "div",
            "classes" => "editor-header5 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 6",
            "block" => "div",
            "classes" => "editor-header6 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 7",
            "block" => "div",
            "classes" => "editor-header7 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 8",
            "block" => "div",
            "classes" => "editor-header8 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 9",
            "block" => "div",
            "classes" => "editor-header9 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 10",
            "block" => "div",
            "classes" => "editor-header10 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 11",
            "block" => "div",
            "classes" => "editor-header11 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "Header 12",
            "block" => "div",
            "classes" => "editor-header12 editor-header editor-style",
            "exact" => true,
            "wrapper" => false
        )
    ));

    return $settings;
}

function ikit_one_editor_add_buttons($buttons) {
    // WordPress 3.9 introduced TinyMCE 4.0, which requires specifically adding the styleselect as below
    $wp_version = get_bloginfo('version');
    if($wp_version >= 3.9) {
        array_unshift($buttons, 'styleselect');
    }
    return $buttons;
}
add_filter('mce_buttons_2', 'ikit_one_editor_add_buttons');

?>