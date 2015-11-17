<!-- News -->
<?php

// Get the posts
$post_count = 0;
$args = array();
$args['posts_per_page'] = $g_theme_options[IKIT_ONE_THEME_OPTION_HOME_NEWS_POSTS_PER_PAGE];
$args['category_name'] = IKIT_SLUG_CATEGORY_FEATURED;
$args['order'] = 'DESC';
$args['post_type'] = array('post', IKIT_POST_TYPE_IKIT_POST_EXTERNAL);
$args['orderby'] = 'meta_value_num';
$args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;
query_posts($args);

// If no featured, show latest
if(have_posts() == false) {
    $args['category_name'] = null;
    query_posts($args);
}

if(have_posts()) {

?>

    <div class="box-container index-featured-news">
    <div class="box">

    <?php

    $ikit_section_news = ikit_get_post_by_slug(IKIT_SLUG_IKIT_SECTION_NEWS, IKIT_POST_TYPE_IKIT_SECTION);
    ikit_one_render_banner_header($ikit_section_news);

    while (have_posts()) : the_post();
        global $id;

        $news_image = null;
        if($post_count == 0) {
            $news_image = ikit_post_get_image_url($id, 'full', ikit_one_get_post_image_default());
        }
        else {
            $news_image = ikit_post_get_image_url($id, IKIT_IMAGE_SIZE_MEDIUM_Z, ikit_one_get_post_image_default());
        }

        $news_url_target = '_self';
        $news_url = get_permalink($id);

        $external = false;
        if($post->post_type == IKIT_POST_TYPE_IKIT_POST_EXTERNAL) {
            $external = true;
            $news_url = get_post_meta($id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_LINK_URL, true);
            $news_url_target = '_blank';
        }



        ?>
        <div class="box-section post-<? echo $post->post_name; ?> index-featured-news-item index-featured-news-item-<?php if($post_count == 0) { ?>primary<?php } else { ?>secondary<?php } ?>">

            <?php if($post_count == 0) { ?>

                <div class="box-section-image"><a target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>"><img src="<?php if($news_image) { echo $news_image; } ?>"></img></a></div>
                <?php if($external) { ?>
                    <div class="box-section-title-source"><a class="text-color-ikit-section-news external-link-unstyled" target="_blank" href="<?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_LINK_URL, true); ?>">NEWS FROM <?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, true); ?></a></div>
                <?php } ?>
                <div class="box-section-title-detail"><?php echo mysql2date('F j, Y', get_gmt_from_date($post->post_date), false);?> / <?php echo ikit_post_get_author($id, 'By ' . get_the_author()); ?></div>
                <div class="box-section-title"><h1><a class="external-link-unstyled" target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>"><?php the_title(); ?></a></h1></div>

            <?php } ?>

            <?php if($post_count != 0) { ?>
                <div class="box-section-divider"></div>

                <table class="box-section-split">
                <tr>
                <td class="box-section-split-col0">
                    <div class="box-section-image"><a target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>"><img src="<?php if($news_image) { echo $news_image; } ?>"></img></a></div>
                </td>
                <td class="box-section-split-col1">

                    <?php if($external) { ?>
                        <div class="box-section-title-source"><a class="text-color-ikit-section-news external-link-unstyled" target="_blank" href="<?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_LINK_URL, true); ?>">NEWS FROM <?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, true); ?></a></div>
                    <?php } ?>

                    <div class="box-section-title"><a class="external-link-unstyled" target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>"><?php the_title(); ?></a></div>
                    <div class="box-section-title-detail"><?php echo mysql2date('F j, Y', get_gmt_from_date($post->post_date), false);?> / <?php echo ikit_post_get_author($id, 'By ' . get_the_author()); ?></div>
                    <div class="box-section-body"><?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_POST_PREVIEW_DESCRIPTION, true);?></div>
                    <div class="box-section-button"><a class="external-link-unstyled" target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>"><?php if($external) { echo 'go to article'; } else { echo 'details'; } ?></a></div>

                </td>
                </tr>
                </table>


            <?php } ?>


        </div>


        <?php

        $post_count++;

    endwhile;

    ?>

    </div>

    <?php

    ikit_one_render_pager($wp_query, 'MORE', ikit_get_page_permalink_by_slug(IKIT_SLUG_PAGE_NEWS), true);
    wp_reset_query();

    ?>

    </div>

    <div class="box-close"></div>
    <div class="box-close"></div>

<?php

}

?>

<!-- News end -->