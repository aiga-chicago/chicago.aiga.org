<?php


    global $id;
    $news_image = ikit_post_get_image_url($id, 'full', ikit_one_get_post_image_default());
    $news_url = get_permalink($id);
    $news_url_target = '_self';

    $external = false;
    if($post->post_type == IKIT_POST_TYPE_IKIT_POST_EXTERNAL) {
        $external = true;
        $news_url = get_post_meta($id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_LINK_URL, true);
        $news_url_target = '_blank';
    }

    ?>
    <div class="box-section post-<? echo $post->post_name; ?>">
        <div class="box-section-image"><a target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>"><img src="<?php if($news_image) { echo $news_image; } ?>"></img></a></div>

        <?php if($external) { ?>
            <div class="box-section-title-source"><a class="text-color-ikit-section-news external-link-unstyled" target="_blank" href="<?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_LINK_URL, true); ?>">NEWS FROM <?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, true); ?></a></div>
        <?php } ?>

        <div class="box-section-title-detail"><?php echo mysql2date('F j, Y', get_gmt_from_date($post->post_date), false);?> / <?php echo ikit_post_get_author($id, 'By ' . get_the_author()); ?></div>
        <div class="box-section-title"><a class="external-link-unstyled" target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>"><?php the_title(); ?></a></div>
        <div class="box-section-body"><?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_POST_PREVIEW_DESCRIPTION, true);?></div>
        <div class="box-section-button"><a class="external-link-unstyled" target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>"><?php if($external) { echo 'go to article'; } else { echo 'details'; } ?></a></div>

    </div>
    <?php


?>