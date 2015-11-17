<?php

/**
 * Theme constants
 */

define("IKIT_TWO_THEME_NAME", 'ikit_two');
define("IKIT_TWO_JOBS_POSTS_PER_PAGE", 15);
define("IKIT_TWO_PORTFOLIOS_POSTS_PER_PAGE", 15);
define("IKIT_TWO_EVENTS_PAST_POSTS_PER_PAGE", 15);
define("IKIT_TWO_NEWS_POSTS_PER_PAGE", 12);
define("IKIT_TWO_EVENTS_POSTS_PER_PAGE", 9);
define("IKIT_TWO_MEMBER_DIRECTORY_POSTS_PER_PAGE", 20);

define("IKIT_TWO_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT", "comments_enabled_ikit_event");
define("IKIT_TWO_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED", "event_attendee_list_enabled");
define("IKIT_TWO_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE", "aiga_header_logo_image");
define("IKIT_TWO_THEME_OPTION_FAV_ICON", "fav_icon");
define("IKIT_TWO_THEME_OPTION_GROUP_GENERAL", "ikit_two_general");
define("IKIT_TWO_THEME_OPTION_CUSTOM_FONT_EMBED_CODE", "custom_font_embed_code");
define("IKIT_TWO_THEME_OPTION_FIXED_BACKGROUND_IMAGE_ENABLED", "fixed_background_image_enabled");
define("IKIT_TWO_THEME_OPTION_FEATURED_IMAGE_GALLERY_AUTOPLAY", "featured_image_gallery_autoplay");
define("IKIT_TWO_THEME_OPTION_SINGLE_DISABLED_IKIT_PERSON", "single_disabled_ikit_person");

define("IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS", "5,4,3,1");
define("IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES", "breakpoint-body-size-xl,breakpoint-body-size-l,breakpoint-body-size-m,breakpoint-body-size-s");
define("IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS", "breakpoint-body");

define("IKIT_TWO_PAGE_LAYOUT_4_TOOLS_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS", "1,1,3,1");
define("IKIT_TWO_PAGE_LAYOUT_4_BODY_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS", "3,3,2,1");
define("IKIT_TWO_PAGE_LAYOUT_4_BODY_NO_SIDEBAR_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS", "4,3,3,1");
define("IKIT_TWO_PAGE_LAYOUT_4_BODY_FULL_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS", "5,4,3,1");

define("IKIT_TWO_EVENTS_TYPE_AIGA", "aiga");
define("IKIT_TWO_EVENTS_TYPE_COMMUNITY", "community");

/**
 * Theme setup
 */
add_action('after_setup_theme', 'ikit_two_setup');

function ikit_two_setup() {

  // Custom header support
  add_theme_support('custom-header', array(
      'wp-head-callback' => 'ikit_two_header_style',
      'admin-head-callback' => 'ikit_two_admin_header_style'
      )
  );

  define('HEADER_TEXTCOLOR', 'FFFFFF');

  // Custom background support
  add_theme_support('custom-background');

  // Includes
  require(get_template_directory() . '/includes/theme_options.php');
  require(get_template_directory() . '/includes/widgets.php');
  require(get_template_directory() . '/includes/ajax.php');
  require(get_template_directory() . '/includes/updates.php');

  // Set defaults for theme options
  $theme_options = get_option(IKIT_TWO_THEME_OPTION_GROUP_GENERAL);
  $theme_options_updated = false;

  if(isset($theme_options[IKIT_TWO_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED]) == false) {
      $theme_options[IKIT_TWO_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED] = 1;
      $theme_options_updated = true;
  }
  if(isset($theme_options[IKIT_TWO_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT]) == false) {
      $theme_options[IKIT_TWO_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT] = 0;
      $theme_options_updated = true;
  }
  if(isset($theme_options[IKIT_TWO_THEME_OPTION_FIXED_BACKGROUND_IMAGE_ENABLED]) == false) {
      $theme_options[IKIT_TWO_THEME_OPTION_FIXED_BACKGROUND_IMAGE_ENABLED] = 1;
      $theme_options_updated = true;
  }
  if(isset($theme_options[IKIT_TWO_THEME_OPTION_FEATURED_IMAGE_GALLERY_AUTOPLAY]) == false) {
      $theme_options[IKIT_TWO_THEME_OPTION_FEATURED_IMAGE_GALLERY_AUTOPLAY] = 0;
      $theme_options_updated = true;
  }
  if($theme_options_updated) {
      update_option(IKIT_TWO_THEME_OPTION_GROUP_GENERAL, $theme_options);
  }

}

function ikit_two_header_style() {

}

function ikit_two_admin_header_style() {

}

/**
 * Theme options
 */
function ikit_two_options_setup() {
  add_theme_page('Theme Options', 'Theme Options', 'manage_options', 'ikit-two-theme-options', 'ikit_two_theme_options_page_render');
}

add_action('admin_menu', 'ikit_two_options_setup');


/**
 * Theme support for widgets
 */
add_action('widgets_init', 'ikit_two_widgets_setup');

function ikit_two_widgets_setup() {

  $before_widget = '<div id="%1$s" class="widget ikit-widget %2$s"><div class="widget-content">';
  $after_widget = '</div></div>';

  register_sidebar( array(
  'name' => __('Index Billboard'),
  'id' => 'index-billboard-sidebar',
  'before_widget' => $before_widget,
  'after_widget' => $after_widget
  ));

  register_sidebar( array(
  'name' => __('Index'),
  'id' => 'index-sidebar',
  'before_widget' => $before_widget,
  'after_widget' => $after_widget
  ));

  register_sidebar( array(
  'name' => __('Page'),
  'id' => 'page-sidebar',
  'before_widget' => $before_widget,
  'after_widget' => $after_widget
  ));

  register_sidebar( array(
  'name' => __('News'),
  'id' => 'news-sidebar',
  'before_widget' => $before_widget,
  'after_widget' => $after_widget
  ));

  register_sidebar( array(
  'name' => __('News Detail'),
  'id' => 'single-sidebar',
  'before_widget' => $before_widget,
  'after_widget' => $after_widget
  ));

  register_sidebar( array(
  'name' => __('Events'),
  'id' => 'events-sidebar',
  'before_widget' => $before_widget,
  'after_widget' => $after_widget
  ));

  register_sidebar( array(
  'name' => __('Event Detail'),
  'id' => 'single-ikit-event-sidebar',
  'before_widget' => $before_widget,
  'after_widget' => $after_widget
  ));

  register_sidebar( array(
  'name' => __('Community Event Detail'),
  'id' => 'single-ikit-event-internal-sidebar',
  'before_widget' => $before_widget,
  'after_widget' => $after_widget
  ));

  register_sidebar( array(
  'name' => __('Jobs'),
  'id' => 'jobs-sidebar',
  'before_widget' => $before_widget,
  'after_widget' => $after_widget
  ));

  // Remove default widgets
  unregister_widget('WP_Widget_Calendar');
  unregister_widget('WP_Widget_Archives');
  unregister_widget('WP_Widget_Meta');
  unregister_widget('WP_Widget_Search');
  unregister_widget('WP_Widget_Pages');
  unregister_widget('WP_Widget_Recent_Posts');
  unregister_widget('WP_Widget_Categories');
  unregister_widget('WP_Widget_Recent_Comments');
  unregister_widget('WP_Widget_RSS');
  unregister_widget('WP_Widget_Tag_Cloud');
  unregister_widget('WP_Nav_Menu_Widget');
  unregister_widget('Ikit_WidgetSocial');
  unregister_widget('Ikit_WidgetEventCalendar');
  unregister_widget('Ikit_WidgetMailingList');

}


/**
 * Theme support for navigation menus
 */
function ikit_two_nav_menus_setup() {

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

add_action( 'admin_init', 'ikit_two_nav_menus_setup' );

/**
 * Custom editor styles
 */
add_editor_style('css/editor.css'); // Adds support for editor-style.css so you can see the style in editor


/**
 * Render categories for a post
 */
function ikit_two_render_categories($post_id, $before, $after) {

    $category_ids = wp_get_post_categories($post_id);
    if(sizeof($category_ids) > 0) {

        echo $before;

        for($j=0;$j<sizeof($category_ids);$j++) {
            $category = get_category($category_ids[$j]);
            if($j != 0) {
                echo ', ';
            };
            echo '<a href="' . get_category_link($category->cat_ID) . '">' . $category->name . '</a>';
        }

        echo $after;

    }

}

/**
 * Render a single event calendar day, should only be used by the ajax function, ikit_two_ajax_render_events_calendar
 */
function ikit_two_render_events_calendar_day($post = null, $ikit_event_meta = null, $day = null, $key = null, $events_by_date = null, $current_date = null, $check_date  = null) {
    ?>

    <td class="events-calendar-day <?php if($post == null) { echo 'events-calendar-day-not-in-month'; } ?> <?php if($current_date == $check_date) { echo 'events-calendar-day-current'; } ?>">
        <div class="events-calendar-day-content">
            <table>
                <tr>
                <td>
                <div class="events-calendar-day-events">
                <?php
                if($post != null && array_key_exists($check_date, $events_by_date)) {

                    $events_for_date = $events_by_date[$check_date];
                    foreach($events_for_date as $key=>$event_for_date) {
                        $post = $event_for_date[0];
                        $ikit_event_meta = $event_for_date[1];
                        $event = ikit_event_get_meta_normalized($post->ID, $ikit_event_meta, null);
                        $event_location_city = $event['location_city'];
                        $event_start_date = $event['start_date'];
                        $event_end_date = $event['end_date'];
                        $event_url = $event['permalink'];
                        $event_url_target = $event['permalink_target'];
                        $external = false;
                        $internal = false;
                        if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) {
                            $external = true;
                        }
                        else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_INTERNAL) {
                            $internal = true;
                        }

                        ?>
                        <div class="events-calendar-day-event events-calendar-day-event-<?php echo $key; ?>">
                            <a class="events-calendar-day-event-image-link" target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><div class="events-calendar-day-event-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo ikit_event_get_image_url($post->ID, $ikit_event_meta, null, IKIT_IMAGE_SIZE_MEDIUM); ?>"></div></a>

                            <div class="events-calendar-day-event-info">
                                <div class="events-calendar-day-event-info-title">
                                    <?php echo $post->post_title;?>
                                </div>
                                <div class="events-calendar-day-event-info-attributes">
                                    <?php
                                    ikit_two_render_event_item_attributes($post, $external, $internal, $event_start_date, $event_end_date, $event_location_city);
                                    ?>
                                </div>
                            </div>

                        </div>
                        <?php
                    }

                }
                else {
                    ?>
                    <div class="events-calendar-day-event events-calendar-day-event-0">
                        <div class="events-calendar-day-event-empty">
                            <table>
                            <tr>
                            <td>
                            <?php if($day != null) { echo $day; } ?>
                            </td>
                            </tr>
                            </table>
                        </div>
                    </div>
                    <?php
                }

                ?>
                </div>
                </td>
                </tr>
            </table>
        </div>
    </td>
    <?php
}

/**
 * Render titles styles
 */
function ikit_two_render_banner_header($title = null, $classname = null, $style=1, $link_url = null, $link_target = null) {
    ?>
    <div class="banner-header <?php if(empty($link_url) == false) { ?>cat-plugin-click-redirect<?php } ?> <?php echo $classname; ?> banner-header-style-<?php echo $style; ?>"
        cat_plugin_click_redirect_url="<?php echo $link_url; ?>"
        cat_plugin_click_redirect_target="<?php echo $link_target; ?>"
    >
        <div class="banner-header-title">
            <?php echo $title ?>
        </div>
    </div>
    <?php
}

/**
 * Gets the parent nav menu item for a given post
 */
function ikit_two_get_parent_nav_menu_item_id($post) {

    global $g_main_nav_menu_items_all;

    $parent_main_nav_menu_item_id = null;
    foreach($g_main_nav_menu_items_all as $main_nav_menu_item) {
        if($main_nav_menu_item->object_id == $post->ID) {
            return $main_nav_menu_item->menu_item_parent;
        }
    }

    return null;
}

/**
 * Render the table of contents title, this is basically the parent nav menu items name
 */
function ikit_two_render_toc_title($post) {

    global $g_main_nav_menu_items;

    $found = false;
    $parent_main_nav_menu_item_id = ikit_two_get_parent_nav_menu_item_id($post);

    // Find the parent nav menu item and add a link to it
    if($parent_main_nav_menu_item_id != null) {

        foreach($g_main_nav_menu_items as $main_nav_menu_item) {
            if($main_nav_menu_item->ID == $parent_main_nav_menu_item_id) {
                $found = true;
                ?>
                <a href="<?php echo $main_nav_menu_item->url; ?>"><?php echo $main_nav_menu_item->title; ?></a>
                <?php
            }
        }

        // If there is no parent, show the post title itself
        if($found == false) {
            ?>
            <a href="<?php echo get_post_permalink($post); ?>"><?php echo get_the_title($post); ?></a>
            <?php
        }
    }

}

function ikit_two_has_toc($post) {

    global $g_main_nav_menu_items_by_parent_id;

    $parent_main_nav_menu_item_id = ikit_two_get_parent_nav_menu_item_id($post);

    if($parent_main_nav_menu_item_id == null || $parent_main_nav_menu_item_id == 0) {
        return false;
    }

    return true;

}

/**
 * Render table of contents for the post, this finds the nav menu item parent
 * and lists all the sub nav menu items as links, you can specify a class name
 * prefix to be prefixed to the rendered elements.
 */
function ikit_two_render_toc($post, $class_prefix) {

    global $g_main_nav_menu_items_by_parent_id;

    $parent_main_nav_menu_item_id = ikit_two_get_parent_nav_menu_item_id($post);

    if($parent_main_nav_menu_item_id != null) {
        $main_nav_menu_items_for_parent_id = $g_main_nav_menu_items_by_parent_id[$parent_main_nav_menu_item_id];

        ?>
        <div class="<?php echo $class_prefix; ?>-header">Sections</div>
        <div class="<?php echo $class_prefix; ?>-toc-links">
        <?php

        foreach($main_nav_menu_items_for_parent_id as $main_nav_menu_item_for_parent_id) {
            ?>
                <div class="<?php echo $class_prefix; ?>-link-container">
                    <a class="<?php echo $class_prefix; ?>-link <?php if($main_nav_menu_item_for_parent_id->target == '_blank') { ?>external<?php } ?> <?php if($main_nav_menu_item_for_parent_id->object_id == $post->ID) { echo 'active'; } ?>" target="<?php echo $main_nav_menu_item_for_parent_id->target; ?>" href="<?php echo $main_nav_menu_item_for_parent_id->url; ?>">
                        <?php echo $main_nav_menu_item_for_parent_id->title; ?>
                    </a>

                </div>
            <?php
        }

        ?>
        </div>
        <?php

    }

}

/**
 * Renders ancilliary attributes for an event, such as if its from the community, external, its location and datetime
 */
function ikit_two_render_event_item_attributes($post, $external, $internal, $event_start_date, $event_end_date, $event_location_city) {
    ?>

    <div class="event-item-attributes">

        <?php if(!empty($event_location_city)) {  ?>
            <span class="event-item-location">
                <?php echo $event_location_city; ?>
                <span> &middot; </span>
            </span>
        <?php } ?>

        <?php if($external) { ?>
            <span class="event-item-external-source">Event from <?php echo get_post_meta($post->ID, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, true); ?><span> &middot; </span></span>
        <?php } ?>

        <?php if($internal) { ?>
            <span class="event-item-internal-source">Event from the community <span> &middot; </span></span>
        <?php } ?>

        <span class="event-item-date">
            <?php echo $event_start_date;?> <?php if($event_end_date != $event_start_date) { echo ' - ' . $event_end_date; } ?>
        </span>

    </div>

    <?php
}

/**
 * Render news item attributes, these are ancilliary attributes such as date, categories, and external source
 */
function ikit_two_render_news_item_attributes($post, $external, $include_categories) {
    ?>
    <div class="news-item-attributes">

        <?php if($external) { ?>
        <span class="news-item-external-source">
            News from <?php echo get_post_meta($post->ID, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, true); ?>
            <span> &middot; </span>
        </span>
        <?php } else if($include_categories) { ?>
            <?php ikit_two_render_categories($post->ID, '<span class="news-item-categories">', '<span> &middot; </span></span>'); ?>
        <?php } ?>

        <span class="news-item-date"><?php echo mysql2date('F j, Y', get_gmt_from_date($post->post_date), false);?></span>

    </div>
    <?php
}

/**
 * Custom editor styles
 */
add_filter('tiny_mce_before_init', 'ikit_two_editor_custom_styles');
function ikit_two_editor_custom_styles($settings) {

    // Show style select dropdown
    $settings["theme_advanced_buttons2"] = "styleselect," . $settings["theme_advanced_buttons2"];

    // Custom styles that will be used throughout all ikit themes
    $settings['style_formats'] = json_encode(array (
        array (
            "title" => "SUBHEAD",
            "block" => "div",
            "classes" => "editor-header21 editor-header editor-format",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "QUOTE",
            "block" => "div",
            "classes" => "editor-header22 editor-header editor-format",
            "exact" => true,
            "wrapper" => false
        ),
        array (
            "title" => "CAPTION",
            "block" => "div",
            "classes" => "editor-header23 editor-header editor-format",
            "exact" => true,
            "wrapper" => false
        )
    ));

    return $settings;
}

function ikit_two_editor_add_buttons($buttons) {
    // WordPress 3.9 introduced TinyMCE 4.0, which requires specifically adding the styleselect as below
    $wp_version = get_bloginfo('version');
    if($wp_version >= 3.9) {
        array_unshift($buttons, 'styleselect');
    }
    return $buttons;
}
add_filter('mce_buttons_2', 'ikit_two_editor_add_buttons');

/**
 * Various theme setup
 */
function ikit_two_setup_theme() {
    update_option('image_default_link_type' , 'none');
}
add_action('after_setup_theme', 'ikit_two_setup_theme');

/**
 * Determine whether or not the browser supports fixed background images
 */
function ikit_two_browser_supports_fixed_background_image($theme_options) {

    $mobile_detect = new Mobile_Detect;
    if(preg_match('/msie [4|5|6|7|8]/i',$_SERVER['HTTP_USER_AGENT']) || $mobile_detect->isMobile() || $mobile_detect->isTablet()) {
        return false;
    }

    if($theme_options[IKIT_TWO_THEME_OPTION_FIXED_BACKGROUND_IMAGE_ENABLED]) {
        return true;
    }
    else {
        return false;
    }

}

/**
 * Add helpful admin notices for specific pages
 */
function ikit_two_admin_notices(){
    global $pagenow;
    $post_type = get_current_screen()->post_type;
    $base = get_current_screen()->base;

    if(($post_type == 'post' || $post_type == 'page') && $base == 'post') {
        ?>
        <div class="updated">
            <p>Please refer to the theme 2 documentation for tips on <a target="_blank" href="https://aiga-workroom1.pbworks.com/w/page/76976207/6%20Posts">formatting posts</a>.</p>
        </div>
        <?php
    }
    else if($post_type == IKIT_POST_TYPE_IKIT_PERSON) {
        ?>
        <!--
        <div class="updated">
            <p>Please refer to the theme 2 documentation for using <a target="_blank" href="https://aiga-workroom1.pbworks.com/w/page/76976207/6%20Posts">iKit Persons</a>.</p>
        </div>
        -->
        <?php
    }

}
add_action('admin_notices', 'ikit_two_admin_notices');

?>