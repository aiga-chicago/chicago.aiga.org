<?php
/**
 * The Header for our theme.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class="html">

<?php

// Define global variables here
global $g_main_nav_menu_items; // Only the parent menu items
global $g_main_nav_menu_items_by_parent_id; // Sub menu items by parent
global $g_main_nav_menu_items_all; // Flat listing of all nav menu items
global $g_theme_options; // Theme options
global $g_options; // Plugin options, set within the ikit plugin

// Get the main navigation menu
$nav_menu_locations = get_nav_menu_locations();
$nav_menu = wp_get_nav_menu_object( $nav_menu_locations[ 'nav-menu-main' ] );
$nav_menu_items = wp_get_nav_menu_items($nav_menu->term_id);
$g_main_nav_menu_items_all = $nav_menu_items;

// Store in hierachy, supports two levels of hiearchy only
$g_main_nav_menu_items = array(); // Only the top level
$g_main_nav_menu_items_by_parent_id = array(); // Secondary level by top level id
foreach((array)$nav_menu_items as $key => $nav_menu_item ) {
  if($nav_menu_item->menu_item_parent == 0) {
    array_push($g_main_nav_menu_items, $nav_menu_item);
    $g_main_nav_menu_items_by_parent_id[$nav_menu_item->ID] = array();
  }
  else {
    array_push($g_main_nav_menu_items_by_parent_id[$nav_menu_item->menu_item_parent], $nav_menu_item);
  }
}

$g_theme_options = get_option(IKIT_TWO_THEME_OPTION_GROUP_GENERAL);

?>

<head>

<?php

$page_title = null;

global $page, $paged;

$page_title = ikit_get_blog_title();
$page_title .= wp_title('|', false);
$page_slug = null;

// Add the blog description for the home/front page.
$site_description = get_bloginfo('description', 'display');
if($site_description && (is_home() || is_front_page())) {
    $page_title .= " | $site_description";
}

// If page, set the page slug
if(is_singular(array('page'))) {
    $page_slug = $post->post_name;
}

// If post or event, add open graph tags for sharing
if(is_singular(array('post', IKIT_POST_TYPE_IKIT_EVENT))) {

    $page_slug = $post->post_name;

    $feed_image_url = null;
    if($post->post_type == 'post') {
        $feed_image_url = ikit_post_get_image_url($post->ID, IKIT_IMAGE_SIZE_MEDIUM, null);
    }
    else if($post->post_type == IKIT_POST_TYPE_IKIT_EVENT) {
        $ikit_event_meta = ikit_event_get_meta($post->ID);
        $feed_image_url = ikit_event_get_image_url($post->ID, $ikit_event_meta, null);
    }

    $page_description = htmlspecialchars(strip_tags($post->post_content));
    if (strlen($page_description) > IKIT_ONE_PAGE_DESCRIPTION_MAX_LENGTH) { // Truncate description
      $page_description = substr($page_description, 0, IKIT_ONE_PAGE_DESCRIPTION_MAX_LENGTH-3) . '...';
    }

    ?>

    <title><?php echo $page_title; ?></title>
    <meta property="og:title" content="<?php echo $page_title; ?>"></meta>
    <meta property="og:description" content="<?php echo $page_description; ?>"></meta>
    <meta property="og:url" content="<?php echo get_permalink($post->ID); ?>"></meta>

    <?php if(empty($feed_image_url) == false) { ?>
        <meta property="og:image" content="<?php echo $feed_image_url;?>"></meta>
    <?php } ?>

    <?php

}
// Otherwise sitewide meta
else {

    ?>
    <title><?php echo $page_title; ?></title>
    <?php
}

?>

<meta name="description" content="<?php echo $site_description; ?>"/>

<?php $fav_icon_url = $g_theme_options[IKIT_TWO_THEME_OPTION_FAV_ICON]['url']; ?>
<link rel="shortcut icon" href="<?php if(empty($fav_icon_url)) { echo bloginfo('template_url') . '/images/default_favicon.png'; } else { echo $fav_icon_url; }; ?>">

<!-- iOS devices should not auto-scale the website, as resolutions are perfectly matched -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0;">

<!-- Add theme stylesheet -->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/style.css"/>

<!-- Add child theme stylesheet -->
<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

<?php

wp_enqueue_script('jquery');
wp_enqueue_script('js_jquery_init', get_bloginfo('template_url') . '/js/jquery.init.js');
wp_enqueue_script('js_jquery_ui', get_bloginfo('template_url') . '/js/jquery-ui-1.10.4.custom.min.js');
wp_enqueue_script('js_jquery_flexslider', get_bloginfo('template_url') . '/js/jquery.flexslider-min.js');
wp_enqueue_script('js_respond', get_bloginfo('template_url') . '/js/respond.min.js');
wp_enqueue_script('js_isotope', get_bloginfo('template_url') . '/js/jquery.isotope.js');

wp_enqueue_script('js_cat', get_bloginfo('template_url') . '/js/cat.js');
wp_enqueue_script('js_fluid_grid', get_bloginfo('template_url') . '/js/fluid_grid.js');
wp_enqueue_script('js_plugins', get_bloginfo('template_url') . '/js/plugins.js');
wp_enqueue_script('js_ikit_two', get_bloginfo('template_url') . '/js/ikit_two.js');

wp_enqueue_style('css_sass', get_bloginfo('template_url') . '/css/stylesheets/sass.css');
wp_enqueue_style('css_sass_templates', get_bloginfo('template_url') . '/css/stylesheets/sass_templates.css');
wp_enqueue_style('css_plugins', get_bloginfo('template_url') . '/css/plugins.css');
wp_enqueue_style('css_editor', get_bloginfo('template_url') . '/css/editor.css');

wp_head();

?>

<!-- Add IE stylesheet -->
<?php if(preg_match('/msie/i',$_SERVER['HTTP_USER_AGENT'])) { ?>
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/ie.css"/>
<?php } ?>


<?php if(preg_match('/msie [4|5|6|7|8]/i',$_SERVER['HTTP_USER_AGENT'])) { ?>
    <script type="text/javascript">
    $(document).ready(function() {

        // Attach loading indicator to page
        $('.unsupported-browser-dialog').cat().ui().popupDialog(true, null,
          function(dialogEl, modalEl) {
            dialogEl.show();
            modalEl.addClass('unsupported-browser-dialog-modal');
            modalEl.css('opacity', 0.8);
          }
        );

    });
    </script>
<?php } ?>

<!-- Custom font -->
<script src="//ajax.googleapis.com/ajax/libs/webfont/1.5.10/webfont.js"></script>
<script>

    // Font face fonts need to run the onLoaded function
    WebFont.load({
        custom: {
            families: ['Tiempos'],
            urls: ['<?php echo get_bloginfo('template_url') . '/style.css'; ?>']
        },
        active: function() {
            jQuery.ikit_two.fonts.onLoaded();
        },
        inactive: function() {
            jQuery.ikit_two.fonts.onLoaded();
        }
    });
</script>

<?php

$custom_font_embed_code = $g_theme_options[IKIT_TWO_THEME_OPTION_CUSTOM_FONT_EMBED_CODE];
if(empty($custom_font_embed_code) == false) {
    echo $custom_font_embed_code;
}
else {
    ?>

    <script type="text/javascript" src="//use.typekit.net/evv0pua.js"></script>
    <script type="text/javascript">
    try{

        // Typekit Loaded fonts need to run the onloaded function
        Typekit.load({
            active: jQuery.ikit_two.fonts.onLoaded,
            inactive: jQuery.ikit_two.fonts.onLoaded
        });
    }
    catch(e) {}
    </script>
    <?php
}
?>

<!-- Global Javascript variables -->
<script type="text/javascript">
    g_templateUrl = "<?php echo get_bloginfo('template_url'); ?>";

    <?php if(ikit_two_browser_supports_fixed_background_image($g_theme_options)) { ?>
        g_browserSupportsFixedBackgroundImage = true;
    <?php } else { ?>
        g_browserSupportsFixedBackgroundImage = false;
    <?php } ?>

</script>

<?php echo $g_options[IKIT_PLUGIN_OPTION_EMBED_CODES]; ?>

<style type="text/css">

    .cat-plugin-breakpoint-body {
        background-color:#<?php echo get_background_color(); ?>;
    }

</style>

</head>


<body <?php body_class('ikit-two ikit-body ikit post-name-' . $page_slug); ?>>

<div class="cat-plugin-breakpoint-body"
    cat_plugin_breakpoint_body_class="breakpoint-body"
    cat_plugin_breakpoint_body_size_class_prefix="breakpoint-body-size"
    cat_plugin_breakpoint_body_names="s,m,l,xl"
    cat_plugin_breakpoint_body_widths="600,780,1280,1560">

<div class="nav-menu">
    <div class="nav-menu-inner">

    <div class="nav-search">
        <div class="nav-search-inner">
            <div class="nav-search-form">
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>

    <div class="nav-menu-grid">
        <h1>Hey, a more calm test.</h1>

        <div class="cat-plugin-fluid-grid grid"
            cat_plugin_fluid_grid_layout_mode="fitRows"
            cat_plugin_fluid_grid_breakpoint_body_size_num_cols="5,3,2,1"
            cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
            cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
        >

        <?php foreach($g_main_nav_menu_items as $main_nav_menu_item) { ?>

            <div class="nav-menu-item cat-plugin-fluid-grid-item grid-item">
                <div class="grid-item-inner">
                <?php
                $main_nav_menu_item_active = false;
                if($main_nav_menu_item->post_name == 'home' && (is_home() || is_front_page())) { $main_nav_menu_item_active = true; }
                if($main_nav_menu_item->object == 'page' && is_page($main_nav_menu_item->object_id)) { $main_nav_menu_item_active = true; }
                ?>

                <div class="nav-menu-item-link"><a class="<?php if($main_nav_menu_item_active) { echo 'active'; } ?>" target="<?php echo $main_nav_menu_item->target; ?>" href="<?php echo $main_nav_menu_item->url; ?>"><?php echo $main_nav_menu_item->title; ?></a></div>

                <div class="nav-menu-submenu">

                    <?php $main_nav_menu_items_for_parent_id = $g_main_nav_menu_items_by_parent_id[$main_nav_menu_item->ID];
                    foreach($main_nav_menu_items_for_parent_id as $main_nav_menu_item_for_parent_id) { ?>

                        <?php
                        $main_nav_menu_item_for_parent_id_active = false;
                        if($main_nav_menu_item_for_parent_id->object == 'page' && is_page($main_nav_menu_item_for_parent_id->object_id)) { $main_nav_menu_item_for_parent_id_active = true; }
                        ?>

                        <div class="nav-menu-submenu-item <?php if($main_nav_menu_item_for_parent_id->target == '_blank') { ?>nav-menu-submenu-item-external<?php } ?>"><a class="nav-menu-submenu-item-link <?php if($main_nav_menu_item_for_parent_id_active) { echo 'active'; } ?>" target="<?php echo $main_nav_menu_item_for_parent_id->target; ?>" href="<?php echo $main_nav_menu_item_for_parent_id->url; ?>"><?php echo $main_nav_menu_item_for_parent_id->title; ?></a></div>

                    <?php } ?>

                </div>
                </div>
            </div>

        <?php } ?>

        </div>
    </div>

    </div>

</div>

<!-- Nav menu background is needed to appear behind the menu when the user is scrolling, otherwise the background will show through -->
<div class="nav-menu-background"></div>

<div class="layout">

<div class="header">

    <table>
    <tr>
    <td class="header-col0">
        <?php $aiga_header_logo_image_url = $g_theme_options[IKIT_TWO_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE]['url']; ?>
        <a class="header-logo" href="<?php echo get_home_url(); ?>"><img src="<?php if(empty($aiga_header_logo_image_url)) { echo bloginfo('template_url') . '/images/default_aiga_header_logo.png'; } else { echo $aiga_header_logo_image_url; } ?>" /> </a>
    </td>
    <td class="header-col1" onclick="window.location.href = '<?php echo get_home_url(); ?>'">

        <?php if('blank' != get_header_textcolor()) { ?>
            <div style="color:#<?php echo get_header_textcolor(); ?>" class="header-text"><?php $blog_name = str_replace("AIGA ", "", get_bloginfo('name')); echo $blog_name; ?></div>
        <?php } else { echo '&nbsp;'; } ?>

    </td>
    <td class="header-col2">
        <a class="header-nav-menu-button" href="javascript:void(0);">Menu</a>
    </td>
    </tr>
    </table>

</div>

<?php if(is_home() || is_front_page()) { ?>

<?php

$image_gallery_featured = ikit_get_post_by_slug(IKIT_SLUG_IKIT_IMAGE_GALLERY_FEATURED, IKIT_POST_TYPE_IKIT_IMAGE_GALLERY);
$image_gallery = get_field(IKIT_CUSTOM_FIELD_IKIT_IMAGE_GALLERY_IMAGE_GALLERY, $image_gallery_featured->ID);
if($image_gallery_featured->post_status == 'publish' && count($image_gallery) > 0) {

?>

<div class="feature">

    <div class="image-gallery flex-container hero-image" image_gallery_type="featured" image_gallery_autoplay="<?php if($g_theme_options[IKIT_TWO_THEME_OPTION_FEATURED_IMAGE_GALLERY_AUTOPLAY]) { echo "true"; } else { echo "false"; } ?>">

        <div class="flexslider">
        <ul class="slides">
        <?php
        foreach($image_gallery as $image_gallery_row) {
            $image = $image_gallery_row;
            $item_image_tuple = wp_get_attachment_image_src($image['image'], 'full', false);
            if($item_image_tuple) {
            ?>
                <?php if(ikit_two_browser_supports_fixed_background_image($g_theme_options) == false) { ?>
                    <li class="cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $item_image_tuple[0]; ?>" title="<?php echo $image['title']; ?>" link_target="<?php echo $image['link_target']; ?>" link_url="<?php echo $image['link_url']; ?>"></li>
                <?php } else { ?>
                    <li class="fixed-background-image" style="background-image:url('<?php echo $item_image_tuple[0]; ?>');" title="<?php echo $image['title']; ?>" link_target="<?php echo $image['link_target']; ?>" link_url="<?php echo $image['link_url']; ?>"></li>
                <?php } ?>
            <?php
            }
        }
        ?>
        </ul>
        </div>

        <div class="image-gallery-controls-button image-gallery-controls-prev-button"><img class="rollover-image" src="<?php bloginfo('template_url'); ?>/images/button_arrow_left@2x.png"/></div>
        <div class="image-gallery-controls-button image-gallery-controls-next-button"><img class="rollover-image" src="<?php bloginfo('template_url'); ?>/images/button_arrow_right@2x.png"/></div>


        <div class="image-gallery-title-container">
            <div class="image-gallery-title-container-inner">
                <div class="image-gallery-title"></div>
            </div>
        </div>

    </div>

</div>

<?php } ?>

<?php } ?>

<?php
$background_image = get_background_image();
$background_color = get_background_color();
$has_background = false;
if(empty($background_image) == false || empty($background_color) == false) {
    $has_background = true;
}
?>

<div class="layout-content" style="<?php if($has_background) { echo 'background-color:transparent;'; } ?>">