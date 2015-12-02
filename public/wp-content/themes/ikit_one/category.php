<?php get_header();?>

<div class="box-container">
<div class="box">

<?php

$cat = $wp_query->get_queried_object();

print(get_ikit_one_render_banner_header(null, IKIT_SLUG_IKIT_SECTION_NEWS, $cat->name));

?>


<?php

// Get the posts
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array();
$args['posts_per_page'] = IKIT_ONE_CATEGORY_POSTS_PER_PAGE;
$args['paged'] = $paged;
$args['category__in'] = array($cat->cat_ID);
$args['post_type'] = array('post', IKIT_POST_TYPE_IKIT_POST_EXTERNAL);
$args['orderby'] = 'meta_value_num';
$args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;

query_posts($args);
while (have_posts()) : the_post();

    include 'news-item.php';

endwhile;

?>

</div>

<?php

ikit_one_render_pager($wp_query);
wp_reset_query();

?>

</div>

<div class="box-close"></div>

<?php get_sidebar();?>
<?php get_footer();?>