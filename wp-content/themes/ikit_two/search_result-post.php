<?php

    $news_image = ikit_post_get_image_url($post->ID, 'full', null);

?>

<div class="cat-plugin-fluid-grid-item grid-item">
    <div class="grid-item-inner">

        <div class="search-result">
            <?php if($news_image != null) { ?>
                <div class="search-result-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $news_image; ?>"></div>
            <?php } ?>
            <div class="search-result-type">NEWS</div>

            <a class="link-block" href="<?php the_permalink(); ?>">
                <span class="search-result-title"><?php echo $post->post_title; ?></span>
                <span class="search-result-date"><?php echo mysql2date('F j, Y', get_gmt_from_date($post->post_date), false);?></span>
            </a>
        </div>

    </div>
</div>