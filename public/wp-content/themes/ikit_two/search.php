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

<div class="page-header-3">
    <table>
    <tr>
        <td class="page-header-3-section-title">
            <a href="<?php echo ikit_get_request_url(true); ?>">Search</a>
        </td>
        <td class="page-header-3-title"></td>
    </tr>
    </table>
</div>


<table class="page-layout-4">
<tr>

    <td class="page-layout-4-content">

        <table>
        <tr>
        <td class="page-layout-4-body">

            <div class="page-layout-4-body-description">
                <div class="page-layout-4-body-description-inner">
                    Results for &ldquo;<?php echo $_GET['s']; ?>&rdquo;
                </div>
            </div>

            <?php
            if($search->have_posts()) {

                ?>

                <div class="cat-plugin-fluid-grid grid search-results"
                    cat_plugin_fluid_grid_layout_mode="fitRows"
                    cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_BODY_FULL_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                    cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                    cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"

                >
                <?php

                while($search->have_posts() ) : $search->the_post(); ?>

                    <?php

                    // Skip pages that are not within the navigation menu
                    // this removes from the search results national pages, and pages
                    // just made for display in widgets etc.
                    if($post->post_type == 'page' && empty($nav_menu_pages_by_id[$post->ID])) {
                        continue;
                    }

                    ?>

                    <?php get_template_part('search_result', $post->post_type); ?>


                <?php

                $post_count++;
                endwhile;

                ?>
                </div>

                <?php

            }

            if($post_count <= 0) {

                ?>

                <div class="cat-plugin-fluid-grid-item grid-item grid-item-empty">
                    <div class="grid-item-inner">

                        No results found.

                    </div>
                </div>

                <?php
            }
            ?>

        </td>
        </tr>
        </table>

    </td>

</tr>
</table>

<?php

wp_reset_query();

?>

</div>

<?php get_footer();?>