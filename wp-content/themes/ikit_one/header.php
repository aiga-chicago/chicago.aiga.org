<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Internet_Kit
 * @since Internet Kit One 1.0.0
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

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

$g_theme_options = get_option(IKIT_ONE_THEME_OPTION_GROUP_GENERAL);

?>

<head>

<?php

$page_title = null;

global $page, $paged;

$page_title = ikit_get_blog_title();
$page_title .= wp_title('|', false);

// Add the blog description for the home/front page.
$site_description = get_bloginfo('description', 'display');
if($site_description && (is_home() || is_front_page())) {
    $page_title .= " | $site_description";
}

// If post or event, add open graph tags for sharing
if(is_singular(array('post', IKIT_POST_TYPE_IKIT_EVENT))) {

    $feed_image_url = null;
    if($post->post_type == 'post') {
        $feed_image_url = ikit_post_get_image_url($post->ID, IKIT_IMAGE_SIZE_MEDIUM, ikit_one_get_post_image_default());
    }
    else if($post->post_type == IKIT_POST_TYPE_IKIT_EVENT) {
        $ikit_event_meta = ikit_event_get_meta($post->ID);
        $feed_image_url = ikit_event_get_image_url($post->ID, $ikit_event_meta, ikit_one_get_event_image_default());
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
    <meta property="og:image" content="<?php echo $feed_image_url;?>"></meta>

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
<link rel="shortcut icon" href="<?php echo $g_theme_options[IKIT_ONE_THEME_OPTION_FAV_ICON]['url']; ?>">

<!-- iOS devices should not auto-scale the website, as resolutions are perfectly matched -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0;">

<!-- Add theme stylesheet -->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/style.css"/>

<!-- Add child theme stylesheet -->
<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

<?php

wp_enqueue_script('js_jquery', get_bloginfo('template_url') . '/js/jquery-1.10.2.min.js');
wp_enqueue_script('js_jquery_flexslider', get_bloginfo('template_url') . '/js/jquery.flexslider-min.js');
wp_enqueue_script('js_ikit_one', get_bloginfo('template_url') . '/js/ikit_one.js');
wp_enqueue_script('js_respond', get_bloginfo('template_url') . '/js/respond.min.js');
wp_enqueue_script('js_plugins', get_bloginfo('template_url') . '/js/plugins.js');

wp_enqueue_style('css_jquery_flexslider', get_bloginfo('template_url') . '/css/flexslider.css');
wp_enqueue_style('css_editor', get_bloginfo('template_url') . '/css/editor.css');

wp_head();

?>

<!-- Custom font -->
<?php

$custom_font_embed_code = $g_theme_options[IKIT_ONE_THEME_OPTION_CUSTOM_FONT_EMBED_CODE];
if(empty($custom_font_embed_code) == false) {
    echo $custom_font_embed_code;
}
else {
    ?>
    <script type="text/javascript" src="//use.typekit.net/car7wwi.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
    <?php
}
?>

<!-- Global Javascript variables -->
<script type="text/javascript">
    g_templateUrl = "<?php echo get_bloginfo('template_url'); ?>";
</script>

<?php echo $g_options[IKIT_PLUGIN_OPTION_EMBED_CODES]; ?>

</head>



<body <?php body_class('ikit-one ikit-body ikit'); ?>>

<div class="layout">

<!-- Header -->
<div class="layout-header-container">

    <div class="layout-header">

        <table>
        <tr>
        <td class="layout-header-col0">
            <a class="layout-header-logo" href="<?php echo get_home_url(); ?>"><img src="<?php echo $g_theme_options[IKIT_ONE_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE]['url']; ?>" /> </a>
        </td>

        <td onclick="window.location.href = '<?php echo get_home_url(); ?>'" class="layout-header-col1" style="background:url('<?php header_image(); ?>');">

            <?php if('blank' != get_header_textcolor()) { ?>
                <div style="color:#<?php echo get_header_textcolor(); ?>" class="layout-header-text emphasis"><?php $blog_name = str_replace("AIGA ", "", get_bloginfo('name')); echo $blog_name; ?></div>
            <?php } else { echo '&nbsp;'; } ?>

        </td>
        </tr>
        </table>

    </div>

</div>

<!-- Navigation is activated via Javascript, see js file -->
<div class="layout-nav-container">

    <div class="layout-nav">

        <!-- Breakpoint 300 -->
        <div class="layout-nav-menu layout-nav-menu-breakpoint-300 breakpoint-300">
            <table>
                <tr>
                <td class="layout-nav-menu-col0">
                    <div class="layout-nav-menu-button-container">
                        <div class="layout-nav-menu-button">
                            menu
                            <div class="layout-nav-menu-submenu">

                                <?php foreach($g_main_nav_menu_items as $main_nav_menu_item) { ?>
                                    <div class="submenu-item"><a target="<?php echo $main_nav_menu_item->target; ?>" href="<?php echo $main_nav_menu_item->url; ?>"><?php echo $main_nav_menu_item->title; ?></a></div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </td>
                <td class="layout-nav-menu-col1">
                    <div class="layout-nav-menu-search-button">
                        <img class="layout-nav-menu-search-icon" src="<?php bloginfo('template_url'); ?>/images/button_search.png" />

                        <div class="layout-nav-menu-search-form">

                            <div class="layout-nav-menu-search-form-spacer"></div>

                            <div class="layout-nav-menu-search-form-content">
                            <table style="height:100%;">
                                <tr>
                                <td>
                                <?php get_search_form(); ?>
                                </td>
                                </tr>
                            </table>
                            </div>

                        </div>

                    </div>
                </td>
                </tr>
            </table>
        </div>

        <!-- Breakpoint 520 -->
        <div class="layout-nav-menu layout-nav-menu-breakpoint-520 breakpoint-520">
            <table>
                <tr>
                <td class="layout-nav-menu-col0">
                    <div class="layout-nav-menu-button-container">
                        <div class="layout-nav-menu-button">
                            menu
                            <div class="layout-nav-menu-submenu">

                                <?php
                                // Arrange the menu items into a bucket for each column
                                $menu_items_by_col = array();
                                foreach($g_main_nav_menu_items as $main_nav_menu_item) {

                                    $column = 0;
                                    preg_match('/breakpoint-520-col([0-9])/', implode(" ", $main_nav_menu_item->classes), $matches, PREG_OFFSET_CAPTURE);
                                    if(isset($matches[1][0])) {
                                        $column = $matches[1][0];
                                    }

                                    if(isset($menu_items_by_col[$column]) == false) {
                                            $menu_items_by_col[$column] = array();
                                    }

                                    array_push($menu_items_by_col[$column], $main_nav_menu_item);

                                }
                                ksort($menu_items_by_col);
                                foreach($menu_items_by_col as $menu_items_for_col) { ?>

                                    <div class="breakpoint-520-submenu-col">

                                        <?php foreach($menu_items_for_col as $menu_item_for_col) { ?>
                                            <div class="submenu-item">
                                                <a target="<?php echo $menu_item_for_col->target; ?>" href="<?php echo $menu_item_for_col->url; ?>"><?php echo $menu_item_for_col->title; ?></a>
                                            </div>

                                            <?php $main_nav_menu_items_for_parent_id = $g_main_nav_menu_items_by_parent_id[$menu_item_for_col->ID];
                                            foreach($main_nav_menu_items_for_parent_id as $main_nav_menu_item_for_parent_id) { ?>
                                                <div class="submenu-subitem"><a target="<?php echo $main_nav_menu_item_for_parent_id->target; ?>" href="<?php echo $main_nav_menu_item_for_parent_id->url; ?>"><?php echo $main_nav_menu_item_for_parent_id->title; ?></a></div>
                                            <?php } ?>

                                        <?php } ?>

                                    </div>


                                <?php } ?>

                            </div>
                        </div>
                     </div>
                </td>
                <td class="layout-nav-menu-col1">

                    <div class="layout-nav-menu-search-button">
                        <img class="layout-nav-menu-search-icon" src="<?php bloginfo('template_url'); ?>/images/button_search.png" />

                        <div class="layout-nav-menu-search-form">

                            <div class="layout-nav-menu-search-form-spacer"></div>

                            <div class="layout-nav-menu-search-form-content">
                            <table style="height:100%;">
                                <tr>
                                <td>
                                <?php get_search_form(); ?>
                                </td>
                                </tr>
                            </table>
                            </div>

                        </div>

                    </div>

                </td>
                </tr>
            </table>
        </div>

        <!-- Breakpoint 1020 -->
        <div class="layout-nav-menu layout-nav-menu-breakpoint-1020 breakpoint-1020">

            <table>
                <tr>
                <td class="layout-nav-menu-col0">
                    <div class="layout-nav-menu-button-container">
                    <?php foreach($g_main_nav_menu_items as $main_nav_menu_item) { ?>

                            <div class="layout-nav-menu-button">

                                <a target="<?php echo $main_nav_menu_item->target; ?>" href="<?php echo $main_nav_menu_item->url; ?>"><?php echo $main_nav_menu_item->title; ?></a>

                                <div class="layout-nav-menu-submenu">

                                    <?php $main_nav_menu_items_for_parent_id = $g_main_nav_menu_items_by_parent_id[$main_nav_menu_item->ID];
                                    foreach($main_nav_menu_items_for_parent_id as $main_nav_menu_item_for_parent_id) { ?>
                                        <div class="submenu-subitem"><a target="<?php echo $main_nav_menu_item_for_parent_id->target; ?>" href="<?php echo $main_nav_menu_item_for_parent_id->url; ?>"><?php echo $main_nav_menu_item_for_parent_id->title; ?></a></div>
                                    <?php } ?>

                                </div>

                            </div>

                    <?php } ?>
                    </div>

                </td>
                <td class="layout-nav-menu-col1">

                    <div class="layout-nav-menu-search-button">
                        <img class="layout-nav-menu-search-icon" src="<?php bloginfo('template_url'); ?>/images/button_search.png" />

                        <div class="layout-nav-menu-search-form">

                            <div class="layout-nav-menu-search-form-spacer"></div>

                            <div class="layout-nav-menu-search-form-content">
                            <table style="height:100%;">
                                <tr>
                                <td>
                                <?php get_search_form(); ?>
                                </td>
                                </tr>
                            </table>
                            </div>

                        </div>

                    </div>

                </td>
                </tr>
            </table>

        </div>

    </div>

</div>


<?php if(is_home() || is_front_page()) { ?>


<div class="layout-feature-container">

    <div class="layout-feature">

        <div class="featured">

            <?php

            $image_gallery_featured = ikit_get_post_by_slug(IKIT_SLUG_IKIT_IMAGE_GALLERY_FEATURED, IKIT_POST_TYPE_IKIT_IMAGE_GALLERY);
            $image_gallery = get_field(IKIT_CUSTOM_FIELD_IKIT_IMAGE_GALLERY_IMAGE_GALLERY, $image_gallery_featured->ID);
            if($image_gallery_featured->post_status == 'publish') {
            ?>

            <div class="box-section-image-gallery flex-container" image_gallery_type="featured">
            <div class="flexslider">
            <ul class="slides">
            <?php

                foreach($image_gallery as $image_gallery_row) {
                    $image = $image_gallery_row;
                    $item_image_tuple = wp_get_attachment_image_src($image['image'], 'full', false);
                    if($item_image_tuple) {
                    ?>
                        <li title="<?php echo $image['title']; ?>" link_target="<?php echo $image['link_target']; ?>" link_url="<?php echo $image['link_url']; ?>"><img src="<?php echo $item_image_tuple[0]; ?>"/></li>
                    <?php
                    }
                }
            ?>
            </ul>
            </div>


            <div class="box-section-image-gallery-controls">
                <table>
                <tr>
                <td class="box-section-image-gallery-controls-col0">
                <div class="box-section-image-gallery-controls-title">FEATURED</div>
                </td>
                <td class="box-section-image-gallery-controls-col1">
                    <div class="box-section-image-gallery-controls-line"></div>
                </td>
                <td class="box-section-image-gallery-controls-col2">
                    <table>
                    <tr>
                    <td>
                    <div class="box-section-image-gallery-controls-prev-button"><img class="highlightable" src="<?php bloginfo('template_url'); ?>/images/slideshow_arrow_left.png"/></div>
                    </td>
                    <td>
                    <div class="box-section-image-gallery-controls-pause-play-button">
                        <div class="box-section-image-gallery-controls-pause-button"><img class="highlightable" src="<?php bloginfo('template_url'); ?>/images/slideshow_pause.png"/></div>
                        <div class="box-section-image-gallery-controls-play-button"><img class="highlightable" src="<?php bloginfo('template_url'); ?>/images/slideshow_play.png"/></div>
                    </div>
                    </td>
                    <td>
                    <div class="box-section-image-gallery-controls-next-button"><img class="highlightable" src="<?php bloginfo('template_url'); ?>/images/slideshow_arrow_right.png"/></div>
                    </td>
                    </tr>
                    </table>
                </td>
                </tr>
                </table>
            </div>



            <div class="box-section-image-gallery-title"></div>

            </div>
            <?php } ?>

        </div>

    </div>

</div>

<?php } ?>

<div class="layout-main-container">

    <div class="layout-main">

        <div class="layout-content">



            <div class="content">