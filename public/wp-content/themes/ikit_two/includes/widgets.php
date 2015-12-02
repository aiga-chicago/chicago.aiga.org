<?php

function ikit_action_widgets_render($class, $args, $instance, $context) {

    /**
     * Eye on Design Widget
     */
    if($class == Ikit_WidgetEyeOnDesign) {

        extract($args);

        echo $before_title;
        ikit_two_render_banner_header('Eye on Design', null, 1, 'http://eyeondesign.aiga.org', '_blank');
        echo $after_title;

        ?>

        <?php foreach ($context['eyeondesign_items'] as $eyeondesign_item) {

            $image_url = null;

            // Assumes structure is <item><image><url>...</url></image></item>
            $image_tag = $eyeondesign_item->get_item_tags('', 'image');
            $image_tag = $image_tag[0]['child'];
            foreach($image_tag as $child) {
                $image_url = $child['url'][0]['data'];
            }

            ?>

            <?php if($image_url != null) { ?>
                <div class="eyeondesign-item-image"><a target="_blank" href="<?php echo $eyeondesign_item->get_link(); ?>"><img src="<?php echo $image_url; ?>"/></a></div>
            <?php } ?>

            <a class="link-block" target="_blank" href="<?php echo $eyeondesign_item->get_link(); ?>">
                <span class="eyeondesign-item-title"><?php echo $eyeondesign_item->get_title(); ?></span>
            </a>

            <?php
            break;
        }

    }

    if($class == Ikit_WidgetSponsors) {

        extract($args);

        echo $before_title;
        $title = $instance['title'];
        if(empty($title)) {
            $title = 'Official Sponsor';
        }
        ikit_two_render_banner_header($title);
        echo $after_title;

        foreach($context['national_sponsors'] as $sponsor) {
        ?>

            <?php
            $attachment_image_src = wp_get_attachment_image_src(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE, true), 'full')
            ?>

            <div class="sponsor-item"><a target="_blank" href="<?php echo get_post_meta($sponsor->ID, 'url', true);?>"><img width="<?php echo round($attachment_image_src[1]/2); ?>px" src="<?php echo $attachment_image_src[0]; ?>"/></a></div>

        <?php
        }

    }

    /**
     * Local Sponsors Widget
     */
    if($class == Ikit_WidgetLocalSponsors) {

        extract($args);

        echo $before_title;
        $title = $instance['title'];
        if(empty($title)) {
            $title = 'Local Sponsors';
        }
        ikit_two_render_banner_header($title);
        echo $after_title;

        foreach($context['local_sponsors_short'] as $sponsor) {
        ?>

            <?php
            $attachment_image_src = wp_get_attachment_image_src(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE, true), 'full')
            ?>

            <div class="sponsor-item"><a target="_blank" href="<?php echo get_post_meta($sponsor->ID, 'url', true);?>"><img width="<?php echo round($attachment_image_src[1]/2); ?>px" src="<?php echo $attachment_image_src[0]; ?>"/></a></div>
        <?php
        }

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

            $title = $custom_title;

            if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>

                <form action="<?php echo $mailchimp_signup_form_url; ?>" method="post">

                <div>
                    <table>
                    <tr>
                    <td>
                    <input type="text" value="" placeholder="Email Address" name="EMAIL" class="required email" id="mce-EMAIL">
                    </td>
                    <td>
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

        $num_results = count($context['featured_jobs']);

        $title = 'Latest Job Post';
        if($num_results > 1) {
            $title = $title . 's';
        }

        echo $before_title;
        ikit_two_render_banner_header($title);
        echo $after_title;

        $post_count = 0;

        ?>
        <div class="job-items">
        <?php

        foreach($context['featured_jobs'] as $featured_job) {
            $ikit_job_meta = ikit_job_get_meta($featured_job->ID);
            ?>
            <div class="job-item">
                <a class="link-block" href="<?php echo ikit_sso_get_login_url(get_permalink($featured_job->ID)); ?>">
                    <span class="job-item-title"><?php echo $featured_job->post_title; ?></span>
                    <span class="job-item-company"><?php echo $ikit_job_meta->company_name; ?></span>
                </a>
            </div>
            <?php
            $post_count++;
        }

        ?>
        </div>
        <?php

     }

    /**
     * Featured Portfolio Widget
     */
    if($class == Ikit_WidgetFeaturedPortfolio) {

        $num_results = count($context['featured_portfolios']);

        $title = 'Featured Portfolio';
        if($num_results > 1) {
            $title = $title . 's';
        }

        echo $before_title;
        ikit_two_render_banner_header($title);
        echo $after_title;

        ?>

        <div class="portfolio-items">

        <?php foreach ($context['featured_portfolios'] as $featured_portfolio) {

            $ikit_portfolio_meta = ikit_portfolio_get_meta($featured_portfolio->ID);
            $project = $ikit_portfolio_meta[array_rand($ikit_portfolio_meta, 1)]; // Get a random project for this portfolio
            $project_image = $project->cover_image_url;

            ?>

            <div class="portfolio-item">
            <div class="portfolio-item-image"><a target="_blank" href="<?php echo $project->url; ?>"><img src="<?php echo $project_image; ?>"/></a></div>
            <div class="portfolio-item-author"><a target="_blank" href="<?php echo $project->owner_url; ?>"><?php echo $project->owner_name; ?></a></div>
            <div class="portfolio-item-project"><a target="_blank" href="<?php echo $project->url; ?>"><?php echo $project->title; ?></a></div>
            </div>

            <?php

        }

        ?>
        </div>
        <?php

    }


    /**
     * New Members
     */
    if($class == Ikit_WidgetNewMembers) {
        extract($args);

        echo $before_title;
        ikit_two_render_banner_header('New Members');
        echo $after_title;

        ?>

        <?php foreach($context['members'] as $idx=>$member) { ?>

            <div class="member-item">
                <div class="member-item-name"><a target="_blank" href="<?php echo ikit_member_profile_url($member); ?>"><?php echo $member->full_name; ?></a></div>
                <div class="member-item-details">
                    <div class="member-item-type"><?php echo ikit_member_type_display_name($member->member_type); ?></div>
                    <div class="member-item-date"><?php echo date("F j, Y", strtotime($member->join_date)); ?></div>
                </div>

            </div>

        <?php } ?>

        <?php


    }

    /**
     * I am AIGA Widget
     */
    if($class == Ikit_WidgetIAmAIGA) {

        extract($args);

        echo $before_title;
        $title = ikit_two_render_banner_header('I am AIGA');
        echo $after_title;

        $member_year = ikit_member_join_year($context['member']);

        ?>

        <div class="member-item-image"><img src="<?php echo str_replace('~/', 'http://my.aiga.org/', $context['member']->avatar); ?>"/></div>
        <div class="member-item-details">
            <div class="member-item-name"><a target="_blank" href="<?php echo ikit_member_profile_url($context['member']); ?>"><?php echo $context['member']->full_name; ?></a></div>
        </div>

        <?php
    }

    /**
     * Join AIGA Widget
     */
    if($class == Ikit_WidgetJoinAIGA) {

        ?>
        <a target="_blank" href="http://www.aiga.org/belong/"><img src="<?php echo $context['selected_promo_image_url']; ?>"/></a>
        <?php

    }

    /**
     * Page Widget
     */
    if($class == Ikit_WidgetPage) {

        if(array_key_exists('page', $context)) {

            echo $before_title;
            $title = $instance['title'];
            if(empty($title) == false) {
                ikit_two_render_banner_header($instance['title']);
            }
            echo $after_title;
            echo apply_filters ('the_content', $context['page']->post_content);

        }

    }

    /**
     * Twitter Widget
     */
    if($class == Ikit_Twitter_Widget){

        if(count($context['twitter_messages']) > 0) {

            echo $before_title;
            $title = ikit_two_render_banner_header('Twitter', null, 1, $context['twitter_url'], '_blank');
            echo $after_title;

            ?>

            <div class="twitter-items">

            <?php

                for($i=0;$i<count($context['twitter_messages']);$i++) {
                    $twitter_message = $context['twitter_messages'][$i];
                    if($i < $context['num_twitter_messages']) {
                    ?>
                    <div class="twitter-item">
                        <?php echo ikit_social_twitter_url_converter(str_replace($context['twitter_username'] . ': ', '', html_entity_decode($twitter_message->text))); ?>
                    </div>
                    <?php
                    }
                }

                ?>

            </div>

            <?php

        }

    }

    /**
     * Facebook Widget
     */
    if($class == Ikit_Facebook_Widget) {

        extract($args);

        if(count($context['facebook_status_messages']) > 0) { ?>

            <?php
            echo $before_title;
            ikit_two_render_banner_header('Facebook', null, 1, $context['facebook_url'], '_blank');
            echo $after_title;
            ?>

            <div class="facebook-item">
            <?php foreach ($context['facebook_status_messages'] as $facebook_status_message) {?>

                <?php if(empty($facebook_status_message->message) == false) { ?>
                <div><?php echo ikit_social_url_converter(html_entity_decode(ikit_truncate($facebook_status_message->message, 256))); ?></div>
                <?php } elseif(empty($facebook_status_message->name) == false) { ?>
                <div><?php echo $facebook_status_message->name; ?></div>
                <?php } else { ?>
                  <?php continue; ?>
                <?php } ?>
                <?php if(empty($facebook_status_message->link) == false) { ?>
                <div class="facebook-item-read-more"><a target="_blank" href="<?php echo $facebook_status_message->link; ?>">Read more...</a></div>
                <?php } ?>


            <?php break; } ?>
            </div>

        <?php }

    }


    /**
     * Vimeo Widget
     */
    if($class == Ikit_Vimeo_Widget){
        extract($args);

        if(count($context['vimeo_videos']) > 0) {

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

            // Replace thethumbnail url with the hi-res version
            $vimeo_video_thumbnail_url = ikit_social_vimeo_thumbnail_image_resize($vimeo_video_thumbnail_url);

            echo $before_title;
            ikit_two_render_banner_header('Vimeo', null, 1, $context['vimeo_url'], '_blank');
            echo $after_title;

            ?>

            <a target="_blank" href="<?php echo $vimeo_video->get_link(); ?>">
                <img class="vimeo-item-thumbnail" src="<?php echo $vimeo_video_thumbnail_url; ?>"/>
            </a>


        <?php }
    }



    /**
     * YouTube Widget
     */
    if($class == Ikit_YouTube_Widget){

        extract($args);

        if(count($context['youtube_videos']) > 0) { ?>

            <?php

            $youtube_video = $context['youtube_videos'][0];
            if($context['randomize_youtube']) {
                $youtube_video = $context['youtube_videos'][array_rand($context['youtube_videos'], 1)];
            }

            // Extract the video url
            $video_url = $youtube_video->get_link();
            $video_id = explode('=', $video_url);
            $video_id = explode("&", $video_id[1]);
            $thumbnail_url = ikit_social_vimeo_thumbnail_image_resize("http://img.youtube.com/vi/".$video_id[0]."/0.jpg");

            echo $before_title;
            ikit_two_render_banner_header('YouTube', null, 1, $context['youtube_url'], '_blank');
            echo $after_title;

            ?>

            <a target="_blank" href="<?php echo $youtube_video->get_link(); ?>">
                <img class="youtube-item-thumbnail" src="<?php echo $thumbnail_url; ?>"/>
            </a>

        <?php }
    }


    /**
     * Flickr Widget
     */
    if($class == Ikit_Flickr_Widget){

        extract($args);

        if(count($context['flickr_images']) > 0) { ?>

            <?php

            $flickr_image = $context['flickr_images'][0];
            if($context['randomize_flickr']) {
                $flickr_image = $context['flickr_images'][array_rand($context['flickr_images'], 1)];
            }
            $flickr_image_url = ikit_social_flickr_image_from_description($flickr_image->get_description());

            echo $before_title;
            ikit_two_render_banner_header('Flickr', null, 1, $context['flickr_url'], '_blank');
            echo $after_title;

            ?>

            <a target="_blank" href="<?php echo $flickr_image->get_link(); ?>">
                <img class="flickr-item-image" src="<?php echo ikit_social_flickr_image_resize($flickr_image_url, 5);?>"/>
            </a>

        <?php }
    }

    /**
     * Instagram Widget
     */
    if($class == Ikit_Instagram_Widget){

        extract($args);

        if(count($context['instagram_photos']) > 0) {

            $instagram_photo = $context['instagram_photos'][0];
            if($context['randomize_instagram']) {
                $instagram_photo = $context['instagram_photos'][array_rand($context['instagram_photos'], 1)];
            }

            $instagram_image_url = $instagram_photo->images->standard_resolution->url;

            echo $before_title;
            ikit_two_render_banner_header('Instagram', null, 1, $context['instagram_url'], '_blank');
            echo $after_title;

            ?>

            <a target="_blank" href="<?php echo $instagram_photo->link; ?>">
                <img src="<?php echo $instagram_image_url;?>"/>
            </a>

            <?php
        }

    }

    /**
     * Video Billboard Widget
     */
    if($class == Ikit_Video_Billboard_Widget) {

        if($context['video_id'] != null) {

            $video_image_url = $context['video_image_url'];

            if($context['custom_video_image_url'] != null) {
                $video_image_url = $context['custom_video_image_url'];
            }

            $video_id = $context['video_id'];
            $video_player_url = null;
            if($context['video_type'] == 'youtube') {
                $video_player_url = 'http://www.youtube.com/embed/' . $video_id . '?autoplay=1&rel=0&wmode=opaque"';
            }
            else if($context['video_type'] == 'vimeo') {
                $video_player_url = 'http://player.vimeo.com/video/' . $video_id . '?autoplay=1&rel=0&wmode=opaque';
            }

            echo $before_title;
            ikit_two_render_banner_header('Featured Video', null, 2);
            echo $after_title;

            ?>

            <div class="video-item">
                <div class="video-item-image video-item-image-<?php echo $context['video_id'] ?> cat-plugin-anystretch-image cat-plugin-video-swap"
                    cat_plugin_anystretch_image_url="<?php echo $video_image_url; ?>"
                    cat_plugin_video_swap_button_selector=".video-item-image-<?php echo $context['video_id'] ?>"
                    cat_plugin_video_swap_video_player_url="<?php echo $video_player_url; ?>"
                >
                    <div class="video-item-overlay"></div>
                </div>
                <div class="video-item-title"><?php echo $context['video_title']; ?></div>

            </div>

            <?php

        }

    }

    /**
     * News Billboard Widget
     */
    if($class == Ikit_News_Billboard_Widget) {

        if(count($context['posts']) > 0) {

            echo $before_title;
            ikit_two_render_banner_header('Featured News', null, 2);
            echo $after_title;

            ?>
            <div class="cat-plugin-fluid-grid grid widget-grid page-layout-3"
                cat_plugin_fluid_grid_layout_mode="fitRows"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="3,3,3,1"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
            >
            <?php

            foreach($context['posts'] as $post) {

                $image = ikit_post_get_image_url($post->ID, IKIT_IMAGE_SIZE_MEDIUM_Z, null);
                $url_target = '_self';
                $url = get_permalink($post->ID);

                $external = false;
                if($post->post_type == IKIT_POST_TYPE_IKIT_POST_EXTERNAL) {
                    $external = true;
                    $url = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_LINK_URL, true);
                    $url_target = '_blank';
                }

                $author = ikit_post_get_author($post->ID, get_the_author_meta('display_name', $post->post_author));
                $preview_description = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_POST_PREVIEW_DESCRIPTION, true);



                ?>
                    <div class="cat-plugin-fluid-grid-item grid-item">
                        <div class="grid-item-inner">

                        <?php if($image) { ?>
                        <a target="<?php echo $url_target; ?>" href="<?php echo $url; ?>">
                            <div class="news-item-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $image; ?>"></div>
                        </a>
                        <?php } ?>

                        <div class="news-item-title">
                            <a target="<?php echo $url_target; ?>" href="<?php echo $url; ?>">
                                <?php echo $post->post_title; ?>
                            </a>
                        </div>

                        <?php if(empty($preview_description) == false) { ?>
                            <div class="news-item-description"><?php echo $preview_description; ?></div>
                        <?php } ?>

                        <?php ikit_two_render_news_item_attributes($post, $external, false); ?>

                        </div>
                    </div>

                <?php
            }

            ?>

            </div>

            <?php
        }
    }

    /**
     * News Widget
     */
    if($class == Ikit_News_Widget) {

        echo $before_title;
        ikit_two_render_banner_header('News', null, 1, ikit_get_page_permalink_by_slug(IKIT_SLUG_PAGE_NEWS));
        echo $after_title;

        ?>
        <div class="news-items">
        <?php

        $post_count = 0;
        foreach($context['posts'] as $post) {

            $news_image = null;
            if($post_count == 0) {
                $news_image = ikit_post_get_image_url($post->ID, 'full', null);
            }
            else {
                $news_image = ikit_post_get_image_url($post->ID, IKIT_IMAGE_SIZE_MEDIUM_Z, null);
            }

            $news_url_target = '_self';
            $news_url = get_permalink($post->ID);

            $external = false;
            if($post->post_type == IKIT_POST_TYPE_IKIT_POST_EXTERNAL) {
                $external = true;
                $news_url = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_LINK_URL, true);
                $news_url_target = '_blank';
            }

            $news_preview_description = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_POST_PREVIEW_DESCRIPTION, true);
            $news_author = ikit_post_get_author($post->ID, get_the_author_meta('display_name', $post->post_author));

            ?>

            <div class="news-item">

                <?php if($news_image) { ?>
                    <div class="news-item-image"><a target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>"><img src="<?php echo $news_image; ?>"></img></a></div>
                <?php } ?>

                <div class="news-item-title">
                    <a target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>">
                        <?php echo $post->post_title; ?>
                    </a>
                </div>
                <?php if(empty($news_preview_description) == false) { ?>
                    <div class="news-item-description"><?php echo $news_preview_description; ?></div>
                <?php } ?>

                <?php ikit_two_render_news_item_attributes($post, $external, false); ?>

            </div>

            <?php

            $post_count++;

        }

        ?>
        </div>
        <?php

    }

    /**
     * Events Internal Widget
     */
    if($class == Ikit_Events_Internal_Widget) {

        echo $before_title;
        ikit_two_render_banner_header('Community Events', null, 1, ikit_get_page_permalink_by_slug(IKIT_SLUG_PAGE_EVENTS) . '?type=' . IKIT_TWO_EVENTS_TYPE_COMMUNITY);
        echo $after_title;

        ?>
        <div class="event-items">
        <?php

        $post_count = 0;
        foreach($context['posts'] as $post) {

            $ikit_event_meta = ikit_event_get_meta($post->ID);
            $event = ikit_event_get_meta_normalized($post->ID, $ikit_event_meta, null);
            $event_image = $event['image'];
            $event_start_date = $event['start_date'];
            $event_end_date = $event['end_date'];
            $event_preview_description = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_PREVIEW_DESCRIPTION, true);
            $event_url = $event['permalink'];
            $event_url_target = $event['permalink_target'];
            $event_location_city = $event['location_city'];

            ?>

            <div class="event-item">

                <?php if($event_image) { ?>
                    <div class="event-item-image"><a target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><img src="<?php echo $event_image; ?>"></img></a></div>
                <?php } ?>

                <div class="event-item-title">
                    <a target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><?php echo $post->post_title; ?></a>
                </div>

                <?php if(empty($event_preview_description) == false) { ?>
                    <div class="event-item-description"><?php echo $event_preview_description; ?></div>
                <?php } ?>

                <?php ikit_two_render_event_item_attributes($post, false, true, $event_start_date, $event_end_date, $event_location_city); ?>

            </div>

            <?php

            $post_count++;

        }
        ?>
        </div>
        <?php

    }

    /**
     * Events Billboard Widget
     */

    if($class == Ikit_Events_Billboard_Widget) {

        if(count($context['posts']) > 0) {

            echo $before_title;
            ikit_two_render_banner_header('Featured Events', null, 2);
            echo $after_title;

            ?>

            <div class="event-item-slides-container">

            <div class="event-item-slides cycle-slideshow"
                data-cycle-pager-template="&lt;span&gt;&lt;/span&gt;"
                data-cycle-manual-speed="200"
                data-cycle-paused="true"
                data-cycle-log="false"
                data-cycle-slides=".event-item-slide"
                data-cycle-auto-height="container"
            >
                <?php

                $post_count = 0;
                foreach($context['posts'] as $post) {

                    $ikit_event_meta = ikit_event_get_meta($post->ID);
                    $event = ikit_event_get_meta_normalized($post->ID, $ikit_event_meta, null);
                    $event_image = $event['image'];

                    $event_start_date = $event['start_date'];
                    $event_end_date = $event['end_date'];
                    $event_location_city = $event['location_city'];
                    $event_url = $event['permalink'];
                    $event_url_target = $event['permalink_target'];
                    $event_preview_description = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_PREVIEW_DESCRIPTION, true);

                    $event_attributes =  $event_start_date;
                    if($event_end_date != $event_start_date) {
                        $event_attributes = $event_attributes . ' - ' . $event_end_date;
                    }

                    ?>
                    <div class="event-item-slide">
                        <table>
                        <tr>
                        <td class="event-item-image-col">
                            <?php if($event_image) { ?>
                                <a target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>">
                                    <div class="event-item-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $event_image; ?>"></div>
                                </a>
                            <?php } ?>
                        </td>

                        <td class="event-item-info-col">
                            <a class="link-block" target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>">
                                <span class="event-item-title">
                                    <?php echo $post->post_title; ?>
                                </span>
                                <span class="event-item-attributes">
                                    <?php echo $event_attributes; ?>
                                </span>
                            </a>
                            <?php if(empty($event_preview_description) == false) { ?>
                            <div class="event-item-description">
                                <?php echo $event_preview_description; ?>
                            </div>
                            <?php } ?>
                        </td>
                        </tr>
                        </table>
                    </div>
                    <?php

                    $post_count++;

                }

                ?>

                <?php if($post_count > 1) { ?>
                    <div class="event-item-slides-pager cycle-pager"></div>
                <?php } ?>

            </div>

            </div>


            <?php

        }

    }

    /**
     * Events Widget
     */
    if($class == Ikit_Events_Widget) {

        echo $before_title;
        ikit_two_render_banner_header('AIGA Events', null, 1, ikit_get_page_permalink_by_slug(IKIT_SLUG_PAGE_EVENTS) . '?type=' . IKIT_TWO_EVENTS_TYPE_AIGA);
        echo $after_title;

        ?>
        <div class="event-items">
        <?php

        $post_count = 0;
        foreach($context['posts'] as $post) {

            $ikit_event_meta = ikit_event_get_meta($post->ID);
            $event = ikit_event_get_meta_normalized($post->ID, $ikit_event_meta, null);
            $event_image = $event['image'];
            $event_location_city = $event['location_city'];
            $event_start_date = $event['start_date'];
            $event_end_date = $event['end_date'];
            $event_url = $event['permalink'];
            $event_url_target = $event['permalink_target'];
            $external = false;
            $event_preview_description = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_PREVIEW_DESCRIPTION, true);

            if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) {
                $external = true;
            }

            ?>

            <div class="event-item">

                <?php if($event_image) { ?>
                    <div class="event-item-image"><a target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><img src="<?php echo $event_image; ?>"></img></a></div>
                <?php } ?>

                <div class="event-item-title">
                    <a target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><?php echo $post->post_title; ?></a>
                </div>

                <?php if(empty($event_preview_description) == false) { ?>
                    <div class="event-item-description"><?php echo $event_preview_description; ?></div>
                <?php } ?>

                <?php ikit_two_render_event_item_attributes($post, $external, $internal, $event_start_date, $event_end_date, $event_location_city); ?>

            </div>

            <?php

            $post_count++;

        }

        ?>
        </div>
        <?php

    }

    /**
     * Quote Widget
     */
    if($class == Ikit_Quote_Widget) {

        // Get a random quote from the list
        if(count($context['quotes']) > 0) {

            $quote = $context['quotes'][array_rand($context['quotes'], 1)];

            ?>

            <div class="quote-item">

                <?php echo $quote['quote_text']; ?>

                <?php if(empty($quote['attribution']) == false) { ?>

                    <span class="quote-item-attribution">
                        <?php if(empty($quote['attribution_link_url']) == false) { ?>
                            <a target="_blank" href="<?php echo $quote['attribution_link_url']; ?>">&ndash;<?php echo $quote['attribution']; ?></a>
                        <?php } else { ?>
                            &ndash;<?php echo $quote['attribution']; ?>
                        <?php } ?>
                    </span>

                <?php } ?>

            </div>

            <?php

        }

    }



}

add_action('ikit_action_widgets_render', 'ikit_action_widgets_render', 10, 4);

function ikit_action_widgets_render_before_widget($class, $args, $instance, $context, $before_widget) {
    echo $before_widget;
}

add_action('ikit_action_widgets_render_before_widget', 'ikit_action_widgets_render_before_widget', 10, 5);

function ikit_action_widgets_render_after_widget($class, $args, $instance, $context, $after_widget) {
    echo $after_widget;
}

add_action('ikit_action_widgets_render_after_widget', 'ikit_action_widgets_render_after_widget', 10, 5);

add_filter('ikit_filter_widgets_should_render', 'ikit_filter_widgets_should_render', 10, 5);

function ikit_filter_widgets_should_render($val, $class, $args, $instance, $context) {

    if($class == Ikit_Events_Internal_Widget) {
        if(count($context['posts']) <= 0) {
            return false;
        }
    }

    if($class == Ikit_News_Widget) {
        if(count($context['posts']) <= 0) {
            return false;
        }
    }

    if($class == Ikit_Events_Widget) {
        if(count($context['posts']) <= 0) {
            return false;
        }
    }

    return true;
}


?>