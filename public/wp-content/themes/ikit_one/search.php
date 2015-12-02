<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

<?php get_header();?>
<div class="box-container">
<div class="box">
<div class="box-top-empty-spacer"></div>

<?php ikit_one_render_banner_header(null, 'search', 'Search Results');?>
<?php
global $query_string;

// Generate a hash of the menu items that are pages by their page id
$nav_menu_pages_by_id = array();
foreach($g_main_nav_menu_items_all as $nav_menu_item) {
    if($nav_menu_item->object == 'page') {
        $nav_menu_pages_by_id[$nav_menu_item->object_id] = $nav_menu_item;
    }
}

$query_args = explode("&", $query_string);
$search_query = array();

foreach($query_args as $key => $string) {
    $query_split = explode("=", $string);
    $search_query[$query_split[0]] = urldecode($query_split[1]);
} // foreach

$post_count = 0;
$search_query['post_type'] = array('page', 'post', IKIT_POST_TYPE_IKIT_EVENT);
$search_query['posts_per_page'] = IKIT_ONE_MAX_SEARCH_RESULTS;

$search = new WP_Query($search_query);
?>

<?php
if($search->have_posts()) {

    while ( $search->have_posts() ) : $search->the_post(); ?>

        <?php

        // Skip pages that are not within the navigation menu
        // this removes from the search results national pages, and pages
        // just made for display in widgets etc.
        if($post->post_type == 'page' && empty($nav_menu_pages_by_id[$post->ID])) {
            continue;
        }

        ?>

        <?php if($post_count != 0) { ?>
            <div class="box-section-divider"></div>
        <?php } ?>


        <?php get_template_part('search_result', $post->post_type); ?>


    <?php

    $post_count++;
    endwhile;

}

if($post_count <= 0) {

    ?>

    <div class="box-section">
        <div class="box-section-title">No results...</div>
    </div>

    <?php
}
?>

<div class="box-section-spacer"></div>

</div>

<?php

wp_reset_query();

?>

</div>

<div class="box-close"></div>

<?php get_sidebar();?>
<?php get_footer();?>