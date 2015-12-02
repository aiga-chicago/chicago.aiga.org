<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Internet_Kit
 * @since Internet Kit One 1.0.0
 *
 */
?>

<?php get_header();?>

<div class="box-container">
<div class="box">

<?php

// The single page is a wordpress built-in, therefore we can't get
// the page custom field section like normal, so use the constants since
// we already know for sure it's part of news

$ikit_section_news = ikit_get_post_by_slug(IKIT_SLUG_IKIT_SECTION_NEWS, IKIT_POST_TYPE_IKIT_SECTION);
ikit_one_render_banner_header($ikit_section_news);

?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post();
    global $id;
    ?>

    <div class="box-section">

        <div class="box-top-empty-spacer"></div>

        <div class="box-section-title"><h1><?php the_title(); ?></h1></div>
        <div class="box-section-title-detail"><?php echo mysql2date('F j, Y', get_gmt_from_date($post->post_date), false);?> / <?php echo ikit_post_get_author($id, 'By ' . get_the_author()); ?></div>

        <div class="box-section-image-gallery flex-container">
        <div class="flexslider">

            <ul class="slides">
            <?php
                $image_gallery = get_field('image_gallery', $id);
                $post_image = null;
                foreach($image_gallery as $image_gallery_row) {
                    $image = $image_gallery_row;

                    if($post_image == null) {
                        if($image != null) {
                            $item_image_tuple = wp_get_attachment_image_src($image['image'], IKIT_IMAGE_SIZE_MEDIUM, false);
                            if($item_image_tuple != false) {
                                $post_image = $item_image_tuple[0];
                            }
                        }
                    }

                    ?>
                    <li title="<?php echo $image['title']; ?>"><img src="<?php echo wp_get_attachment_url($image['image'], 'image', true); ?>"/></li>
                    <?php
                }
                if(empty($image_gallery)) {
                    ?>
                    <li><img src="<?php echo get_bloginfo('template_url') . '/images/default_image.png'; ?>"/></li>
                    <?php
                }

            ?>
            </ul>

            </div>

                <div class="box-section-image-gallery-controls">

                <table>
                <tr>
                <td class="box-section-image-gallery-controls-col0">
                </td>
                <td class="box-section-image-gallery-controls-col1">

                </td>
                <td class="box-section-image-gallery-controls-col2">
                    <table>
                    <tr>
                    <td>
                    <div class="box-section-image-gallery-controls-prev-button"><img class="highlightable" src="<?php bloginfo('template_url'); ?>/images/slideshow_arrow_left.png"/></div>
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



        </div>

        <div class="box-section-image-gallery-title"></div>

        <div class="box-section-body">
        <table class="box-section-actions">
        <tr>
        <td class="box-section-actions-col0">

        <!-- Share this -->
        <?php

        $twitter_username = $g_options[IKIT_PLUGIN_OPTION_TWITTER_USERNAME];
        $share_title = htmlspecialchars(strip_tags($post->post_title));
        $share_description = htmlspecialchars(strip_tags($post->post_content));

        ?>

        <script type="text/javascript">var switchTo5x=true;</script>
        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "ur-db07c463-a921-51b-5402-47e2c83c7d8d",onhover: false}); </script>

        <span class="st_twitter_custom share-button" st_via="<?php echo $twitter_username; ?>" st_url="<?php the_permalink(); ?>" st_title="<?php echo $share_title; ?>" st_image="<?php echo $post_image; ?>"><img class="highlightable" id="st_twitter_custom" src="<?php echo get_bloginfo('template_url') . '/images/share_twitter.png'; ?>"></img></span>
        <span class="st_facebook_custom share-button" st_via="<?php echo $twitter_username; ?>" st_url="<?php the_permalink(); ?>" st_title="<?php echo $share_title; ?>" st_image="<?php echo $post_image; ?>"><img class="highlightable" id="st_facebook_custom" src="<?php echo get_bloginfo('template_url') . '/images/share_facebook.png'; ?>"></img></span>
        <span class="st_email_custom share-button" st_via="<?php echo $twitter_username; ?>" st_url="<?php the_permalink(); ?>" st_title="<?php echo $share_title; ?>" st_image="<?php echo $post_image; ?>"><img class="highlightable" id="st_email_custom" src="<?php echo get_bloginfo('template_url') . '/images/share_more.png'; ?>"></img></span>

        </td>
        </tr>
        </table>
        </div>

        <div class="box-section-divider"></div>

        <div class="wp-editor">
            <?php the_content();?>
        </div>

        <div class="box-section-spacer"></div>
        <div class="box-section-divider"></div>

        <?php include(TEMPLATEPATH . '/post_comments.php'); ?>


    </div>

    <?php
endwhile;?>

</div>
</div>

<div class="box-close"></div>

<?php get_sidebar();?>
<?php get_footer();?>