<?php

function ikit_action_widgets_render($class, $args, $instance, $context) {

    if($class == Ikit_WidgetSocial) {

        extract($args);

        $title = get_ikit_one_render_banner_header(null, 'social', 'Social', false);

        $is_first = true; // Determine if first section rendering

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }?>

        <!-- Social links -->

        <div class="social-links">
            <?php ikit_render_social_links(ikit_get_page_permalink_by_slug(IKIT_SLUG_PAGE_IKIT_FEED)); ?>
        </div>
            <div class="box-section-divider"></div>


        <!-- Twitter -->
        <?php if(count($context['twitter_messages']) > 0 && $context['show_twitter']) { ?>

            <?php if($is_first == false) { ?>
                <div class="box-section-divider"></div>
            <?php } $is_first = false; ?>

            <ul>
                <?php

                for($i=0;$i<count($context['twitter_messages']);$i++) {
                    $twitter_message = $context['twitter_messages'][$i];
                    if($i < $context['num_twitter_messages']) {
                        ?>
                        <li><?php echo ikit_social_twitter_url_converter(str_replace($context['twitter_username'] . ': ', '', html_entity_decode($twitter_message->text))); ?></li>

                        <?php if($i != $context['num_twitter_messages']-1 && i!= count($context['twitter_messages'])-1) { ?>
                        <li class="box-section-divider"></li>
                        <?php } ?>


                        <?php
                    }
                }
                ?>

                <li>
                <div class="ikit-widget-title2">
                    <a target="_blank" href="<?php echo $context['twitter_url']; ?>">VIA TWITTER</a>
                </div>
                </li>

            </ul>

        <?php } ?>

        <!-- Facebook -->
        <?php if(count($context['facebook_status_messages']) > 0 && $context['show_facebook']) { ?>

            <?php if($is_first == false) { ?>
                <div class="box-section-divider"></div>
            <?php } $is_first = false; ?>

            <ul>
            <?php foreach ($context['facebook_status_messages'] as $facebook_status_message) {?>

                <?php if(empty($facebook_status_message->message) == false) { ?>
                <li><?php echo ikit_social_url_converter(html_entity_decode(ikit_truncate($facebook_status_message->message, 256))); ?></li>
                <?php } elseif(empty($facebook_status_message->name) == false) { ?>
                <li><?php echo $facebook_status_message->name; ?></li>
                <?php } else { ?>
                  <?php continue; ?>
                <?php } ?>

                <?php if(empty($facebook_status_message->link) == false) { ?>
                <li><a target="_blank" href="<?php echo $facebook_status_message->link; ?>">Read more...</a></li>
                <?php } ?>

            <?php break; } ?>

                <li>
                <div class="ikit-widget-title2">
                    <a target="_blank" href="<?php echo $context['facebook_url']; ?>">VIA FACEBOOK</a>
                </div>
                </li>

            </ul>


        <?php } ?>

        <!-- Instagram -->

        <?php if(count($context['instagram_photos']) > 0 && $context['show_instagram']) { ?>

            <?php if($is_first == false) { ?>
                <div class="box-section-divider"></div>
            <?php } $is_first = false; ?>

            <?php

            $instagram_photo = $context['instagram_photos'][0];
            if($context['randomize_instagram']) {
                $instagram_photo = $context['instagram_photos'][array_rand($context['instagram_photos'], 1)];
            }

            $instagram_image_url = $instagram_photo->images->standard_resolution->url;

            ?>


            <a target="_blank" href="<?php echo $instagram_photo->link; ?>">
                <div class="image-crop instagram-image-container"><img class="instagram-image" src="<?php echo $instagram_image_url;?>"/></div>
            </a>


            <ul>
                <li>
                <div class="ikit-widget-title2">
                    <a target="_blank" href="<?php echo $context['instagram_url']; ?>">VIA INSTAGRAM</a>
                </div>
                </li>
            </ul>

        <?php } ?>


        <!-- Flickr -->
        <?php if(count($context['flickr_images']) > 0 && $context['show_flickr']) { ?>

            <?php if($is_first == false) { ?>
                <div class="box-section-divider"></div>
            <?php } $is_first = false; ?>

            <?php

            $flickr_image = $context['flickr_images'][0];
            if($context['randomize_flickr']) {
                $flickr_image = $context['flickr_images'][array_rand($context['flickr_images'], 1)];
            }
            $flickr_image_url = ikit_social_flickr_image_from_description($flickr_image->get_description());

            ?>

            <a target="_blank" href="<?php echo $flickr_image->get_link(); ?>">
                <div class="image-crop"><img class="flickr-image" src="<?php echo ikit_social_flickr_image_resize($flickr_image_url, 5);?>"/></div>
            </a>


            <ul>
                <li>
                <div class="ikit-widget-title2">
                    <a target="_blank" href="<?php echo $context['flickr_url']; ?>">VIA FLICKR</a>
                </div>
                </li>
            </ul>

        <?php } ?>


        <!-- Vimeo -->
        <?php if(count($context['vimeo_videos']) > 0 && $context['show_vimeo']) { ?>

            <?php if($is_first == false) { ?>
                <div class="box-section-divider"></div>
            <?php } $is_first = false; ?>

            <?php

            $vimeo_video = $context['vimeo_videos'][0];
            if($context['randomize_vimeo']) {
                $vimeo_video = $context['vimeo_videos'][array_rand($context['vimeo_videos'], 1)];
            }

            // Extract the video url
            $vimeo_enclosure = $vimeo_video->get_item_tags('', 'enclosure');
            $vimeo_video_url = $vimeo_enclosure[0]['attribs']['']['url'];

            // Extract the video thumbnail image
            $vimeo_media_content = $vimeo_video->get_item_tags('http://search.yahoo.com/mrss/', 'content');
            $vimeo_video_thumbnail_url = ($vimeo_media_content[0]['child']['http://search.yahoo.com/mrss/']['thumbnail'][0]['attribs']['']['url']);

            // Replace the thumbnail url with the hi-res version
            $vimeo_video_thumbnail_url = ikit_social_vimeo_thumbnail_image_resize($vimeo_video_thumbnail_url);

            ?>

            <a target="_blank" href="<?php echo $vimeo_video->get_link(); ?>">
                <div class="image-crop"><img class="vimeo-video-thumbnail-image" src="<?php echo $vimeo_video_thumbnail_url; ?>"/></div>
            </a>

            <ul>
                <li>
                <div class="ikit-widget-title2">
                    <a target="_blank" href="<?php echo $context['vimeo_url']; ?>">VIA VIMEO</a>
                </div>
                </li>
            </ul>


        <?php } ?>

       <!-- YouTube -->

        <?php if(count($context['youtube_videos']) > 0 && $context['show_youtube']) { ?>

            <?php if($is_first == false) { ?>
                <div class="box-section-divider"></div>
            <?php } $is_first = false; ?>

            <?php

            $youtube_video = $context['youtube_videos'][0];
            if($context['randomize_youtube']) {
                $youtube_video = $context['youtube_videos'][array_rand($context['youtube_videos'], 1)];
            }

            // Extract the video url
            $video_url = $youtube_video->get_link();
            $video_id = explode('=', $video_url);
            $video_id = explode("&", $video_id[1]);
            //$thumbnail_url = ikit_social_vimeo_thumbnail_image_resize("http://img.youtube.com/vi/".$video_id[0]."/0.jpg");
            $thumbnail_url = ikit_social_vimeo_thumbnail_image_resize("http://img.youtube.com/vi/".$video_id[0]."/0.jpg");

            ?>

            <a target="_blank" href="<?php echo $youtube_video->get_link(); ?>">
                <div class="image-crop youtube-video-thumbnail-image-container"><img class="youtube-video-thumbnail-image" src="<?php echo $thumbnail_url; ?>"/></div>
            </a>

            <ul>
                <li>
                <div class="ikit-widget-title2">
                    <a target="_blank" href="<?php echo $context['youtube_url']; ?>">VIA YOUTUBE</a>
                </div>
                </li>
            </ul>

        <?php } ?>

        <?php

    }

    if($class == Ikit_WidgetSponsors) {

        extract($args);

        $title_str = $instance['title'];
        if(empty($title_str)) {
            $title_str = 'Official Sponsor';
        }
        $title = get_ikit_one_render_banner_header(null, 'sponsor', $title_str, false);

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

        $count = 0; foreach($context['national_sponsors'] as $sponsor) {
        ?>

            <?php
            $attachment_image_src = wp_get_attachment_image_src(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE, true), 'full')
            ?>

            <div class="widget-sponsor"><a target="_blank" href="<?php echo get_post_meta($sponsor->ID, 'url', true);?>"><img width="<?php echo round($attachment_image_src[1]/2); ?>px" src="<?php echo $attachment_image_src[0]; ?>"/></a></div>
            <?php if($count < count($context['national_sponsors']) - 1) { ?>
                <div class="box-section-divider"></div>
            <?php } ?>
        <?php
        $count++; }

    }

    /**
     * Local Sponsors Widget
     */
    if($class == Ikit_WidgetLocalSponsors) {

        extract($args);

        $title_str = $instance['title'];
        if(empty($title_str)) {
            $title_str = 'Chapter Sponsors';
        }
        $title = get_ikit_one_render_banner_header(null, 'sponsor', $title_str, false);

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

        $count = 0; foreach($context['local_sponsors_short'] as $sponsor) {
        ?>
            <div class="widget-sponsor"><a target="_blank" href="<?php echo get_post_meta($sponsor->ID, 'url', true);?>"><img src="<?php echo wp_get_attachment_url(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE, true)); ?>" /></a></div>
            <?php if($count < count($context['local_sponsors_short']) - 1) { ?>
                <div class="box-section-divider"></div>
            <?php } ?>
        <?php
        $count++; }

    }

    /**
     * Sign Up Widget
     */
    if($class == Ikit_WidgetMailingList) {

        extract($args);

        $mailchimp_signup_form_url = $context['mailchimp_signup_form_url'];

        $custom_title = $instance['title'];
        if(empty($custom_title)) {
            $custom_title = 'Sign Up';
        }

        if(empty($mailchimp_signup_form_url) == false) {

            $title = get_ikit_one_render_banner_header(null, 'mailing-list', $custom_title, false);

            if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>

                <form action="<?php echo $mailchimp_signup_form_url; ?>" method="post">

                <div class="input-container">
                    <table>
                    <tr>
                    <td class="input-container-col0">
                    <input type="text" value="" placeholder="Email Address" name="EMAIL" class="required email" id="mce-EMAIL">
                    </td>
                    <td class="input-container-col1">
                    <input type="image" src="<?php echo ikit_get_plugin_url('images/transparent_pixel.png'); ?>"></input>
                    </td>
                    </tr>
                    </table>
                </div>

                </form>

            <?php


        }

    }

    /**
     * Featured Job Widget
     */
     if($class == Ikit_WidgetFeaturedJob) {

        extract($args);

        $title = get_ikit_one_render_banner_header(null, IKIT_SLUG_IKIT_SECTION_JOB, 'Featured Job', false);
        $text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>

            <?php

            $post_count = 0;

            foreach($context['featured_jobs'] as $featured_job) {
                $ikit_job_meta = ikit_job_get_meta($featured_job->ID);

                ?>
                    <ul>
                        <li>
                        <div class="ikit-widget-title"><a target="_blank" href="<?php echo ikit_sso_get_login_url(get_permalink($featured_job->ID)); ?>"><?php echo $featured_job->post_title; ?></a></div>
                        <div><?php echo mysql2date('F j, Y', get_gmt_from_date($ikit_job_meta->date_approved), false);?> / <?php echo $ikit_job_meta->company_name; ?></div>
                        </li>
                    </ul>

                    <?php if($post_count != count($context['featured_jobs'])-1) { ?>
                        <div class="box-section-divider"></div>
                    <?php } ?>

                <?php

                $post_count++;

            }
            ?>


        <?php

     }

    /**
     * Featured Portfolio Widget
     */
    if($class == Ikit_WidgetFeaturedPortfolio) {

        extract($args);


        $title = get_ikit_one_render_banner_header(null, IKIT_SLUG_IKIT_SECTION_PORTFOLIO, 'Featured Portfolio', false);

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>

            <?php

            $post_count = 0;

            foreach($context['featured_portfolios'] as $featured_portfolio) {

                $ikit_portfolio_meta = ikit_portfolio_get_meta($featured_portfolio->ID);
                $project = $ikit_portfolio_meta[array_rand($ikit_portfolio_meta, 1)]; // Get a random project for this portfolio
                $project_image = $project->cover_image_url;

                ?>

                <div class="image-crop"><a target="_blank" href="<?php echo $project->url; ?>"><img src="<?php if($project_image != null) { echo $project_image; } ?>"/></a></div>
                <ul>
                <li>
                <div class="ikit-widget-title"><a target="_blank" href="<?php echo $project->url; ?>"><?php echo $featured_portfolio->post_title; ?></a></div></li>
                </ul>

                <?php if($post_count != count($context['featured_portfolios'])-1) { ?>
                    <div class="box-section-divider"></div>
                <?php } ?>

                <?php

                $post_count++;

            }

            ?>


        <?php

    }

    /**
     * New Members
     */
    if($class == Ikit_WidgetNewMembers) {
        extract($args);

        $title = get_ikit_one_render_banner_header(null, 'new_members', 'New Members', false);

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>

            <?php foreach($context['members'] as $idx=>$member) { ?>

            <?php if($idx > 0) { ?>
            <div class="box-section-divider"></div>
            <?php } ?>

            <ul>
                <li>
                <div class="ikit-widget-title"><a target="_blank" href="<?php echo ikit_member_profile_url($member); ?>"><?php echo $member->full_name; ?></a></div>
                <div>Joined <?php echo date("F j, Y", strtotime($member->join_date)); ?></div>
                <div class="ikit-widget-title2"><?php echo ikit_member_type_display_name($member->member_type); ?></div>
                </li>
            </ul>

            <?php } ?>

        <?php

    }

    /**
     * I am AIGA Widget
     */
    if($class == Ikit_WidgetIAmAIGA) {
        extract($args);

        $title = get_ikit_one_render_banner_header(null, 'i_am_aiga', 'I am AIGA', false);

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>

            <?php

            $member_year = ikit_member_join_year($context['member']);

            ?>

            <div>
            <div class="image"><img src="<?php echo str_replace('~/', 'http://my.aiga.org/', $context['member']->avatar); ?>"/></div>

            <ul>
            <li>
            <div class="ikit-widget-title"><a target="_blank" href="<?php echo ikit_member_profile_url($context['member']); ?>"><?php echo $context['member']->full_name; ?></a></div>
            <div class="ikit-widget-title2"><?php echo 'MEMBER SINCE ' . $member_year; ?></div>
            </li>
            </ul>

            </div>

        <?php
    }

    /**
     * Join AIGA Widget
     */
    if($class == Ikit_WidgetJoinAIGA) {

        extract($args);

        $title = get_ikit_one_render_banner_header(null, 'join_aiga', 'Join AIGA', false);

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>

            <div class=""><a target="_blank" href="http://www.aiga.org/belong/"><img src="<?php echo $context['selected_promo_image_url']; ?>"/></a></div>

        <?php

    }

    /**
     * Page Widget
     */
    if($class == Ikit_WidgetPage) {

        extract( $args );

        if(array_key_exists('page', $context)) {

            $title_label = $instance['title'];

            $ikit_section_id = get_post_meta($context['page']->post_parent, IKIT_CUSTOM_FIELD_PAGE_IKIT_SECTION, true);
            $title = get_ikit_one_render_banner_header($ikit_section_id, null, $title_label, false);

            if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

            echo apply_filters ('the_content', $context['page']->post_content);
        }

    }


    /**
     * Eye on Design Widget
     */
    if($class == Ikit_WidgetEyeOnDesign) {

        extract($args);

        $title = get_ikit_one_render_banner_header(null, 'eyeondesign', 'Eye on Design', false);

        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>

            <?php foreach ($context['eyeondesign_items'] as $eyeondesign_item) {

                $image_url = null;

                // Assumes structure is <item><image><url>...</url></image></item>
                $image_tag = $eyeondesign_item->get_item_tags('', 'image');
                $image_tag = $image_tag[0]['child'];
                foreach($image_tag as $child) {
                    $image_url = $child['url'][0]['data'];
                }


                ?>

                <div class="image-crop"><a target="_blank" href="<?php echo $eyeondesign_item->get_link(); ?>"><img src="<?php echo $image_url; ?>"/></a></div>

                <ul>
                    <li>
                    <div class="ikit-widget-title"><a target="_blank" href="<?php echo $eyeondesign_item->get_link(); ?>"><?php echo $eyeondesign_item->get_title(); ?></a></div>
                    </li>
                </ul>

                <?php
                break;
            } ?>

        <?php

    }

}

add_action('ikit_action_widgets_render', 'ikit_action_widgets_render', 10, 4);

function ikit_action_widgets_render_before_widget($class, $args, $instance, $context, $before_widget) {

    echo $before_widget;

    /**
     * Event Calendar
     */
    if($class == Ikit_WidgetEventCalendar) {
        ikit_one_render_banner_header(null, IKIT_SLUG_IKIT_SECTION_EVENT, 'Calendar', false);
    }

}

add_action('ikit_action_widgets_render_before_widget', 'ikit_action_widgets_render_before_widget', 10, 5);


function ikit_action_widgets_render_after_widget($class, $args, $instance, $context, $after_widget) {
    echo $after_widget;
}

add_action('ikit_action_widgets_render_after_widget', 'ikit_action_widgets_render_after_widget', 10, 5);

add_filter('ikit_filter_widgets_should_render', 'ikit_filter_widgets_should_render', 10, 5);

function ikit_filter_widgets_should_render($val, $class, $args, $instance, $context) {
    return true;
}

?>