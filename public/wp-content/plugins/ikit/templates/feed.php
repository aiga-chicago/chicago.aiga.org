<?php
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

// Get the feed type
$type = $_GET['type'];

// Do not include disqus javascript
remove_action('loop_end', 'dsq_loop_end');

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<rss version="2.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:ikit="http://aiga.org/ikit/elements/1.0/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
    xmlns:media="http://search.yahoo.com/mrss/"
    <?php do_action('rss2_ns'); ?>
>

<channel>

    <title>
    <?php
        $feed_title = ikit_get_blog_title();
        echo $feed_title;
    ?>
    </title>
    <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
    <link><?php echo get_permalink($post->ID); if(empty($type) == false) { echo '?type=' . $type; } ?></link>
    <description><?php bloginfo_rss("description") ?></description>
    <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
    <language><?php echo get_option('rss_language'); ?></language>
    <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
    <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
    <?php do_action('rss2_head'); ?>

    <?php

    /**
     * Public feed items
     */
    if(empty($type)) {

    ?>

        <?php

        $args = array();
        $args['posts_per_page'] = 10;
        $args['post_type'] = array('post');

        query_posts($args);
        while (have_posts()) : the_post();

        ?>
        <item>

            <?php

            $item_image_url = ikit_post_get_image_url($post->ID, IKIT_IMAGE_SIZE_MEDIUM, null);
            $author = get_userdata($post->post_author);
            $author = ikit_post_get_author($post->ID, $author->nickname);

            ?>

            <title><?php the_title(); ?></title>
            <link><?php the_permalink_rss() ?></link>
            <comments><?php comments_link_feed(); ?></comments>
            <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_gmt_from_date($post->post_date), false);?></pubDate>
            <dc:creator><?php echo $author;?></dc:creator>

            <?php the_category_rss('rss2') ?>

            <guid isPermaLink="false"><?php the_guid(); ?></guid>

            <description><![CDATA[
            <?php if($item_image_url != null) { ?>
                <div class="item-image"><img src="<?php echo $item_image_url; ?>"/></div>
            <?php } ?>
            <?php the_content(); ?>
            ]]></description>

            <wfw:commentRss><?php echo esc_url( get_post_comments_feed_link(null, 'rss2') ); ?></wfw:commentRss>

            <?php rss_enclosure(); ?>
            <?php do_action('rss2_item'); ?>

        </item>
        <?php

        endwhile;
        wp_reset_query();

        ?>

<?php } ?>
<?php if($type=='national_aggregate') { ?>

    <?php

    /**
     * National feed aggregate
     *
     * Feed specifically made for aiga.org to consume. Is lightweight to allow for easy parsing.
     * If customizing the feed, do not edit this section or your site will not be pulled in by
     * national.
     */

    $args = array();
    $args['posts_per_page'] = 2;
    $args['post_type'] = array('post');

    query_posts($args);
    while (have_posts()) : the_post();

    // Only include posts that
    $post_days_age = ((date('U') - get_post_time('U')) / 86400);
    if($post_days_age < 31) {

        ?>

            <item>
                <title><![CDATA[<?php the_title();?>]]></title>
                <link><?php the_permalink_rss() ?></link>
                <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_gmt_from_date($post->post_date), false);?></pubDate>
                <media:content url="<?php echo ikit_post_get_image_url($post->ID, IKIT_IMAGE_SIZE_MEDIUM, null); ?>"/>
                <description><![CDATA[<?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_POST_PREVIEW_DESCRIPTION, true);?>]]></description>
            </item>

        <?php

    }

    endwhile;
    wp_reset_query();

    ?>


<?php } ?>

</channel>
</rss>