<?php

/**
 * Contains a central place to easily add a scheduled background task
 * based on scheduled timing conventions, daily, twice daily, or hourly,
 *
 */

add_action('ikit_task_hourly_event', 'ikit_task_hourly_event_run');
function ikit_task_hourly_event_activation() {
    if(!wp_next_scheduled('ikit_task_hourly_event')) {
        wp_schedule_event(time(), 'hourly', 'ikit_task_hourly_event');
    }
}
add_action('wp', 'ikit_task_hourly_event_activation');

add_action('ikit_task_daily_event', 'ikit_task_daily_event_run');
function ikit_task_daily_event_activation() {
    if(!wp_next_scheduled('ikit_task_daily_event')) {
        wp_schedule_event(time(), 'daily', 'ikit_task_daily_event');
    }
}
add_action('wp', 'ikit_task_daily_event_activation');

add_action('ikit_task_twicedaily_event', 'ikit_task_twicedaily_event_run');
function ikit_task_twicedaily_event_activation() {
    if(!wp_next_scheduled('ikit_task_twicedaily_event')) {
        wp_schedule_event(time(), 'twicedaily', 'ikit_task_twicedaily_event');
    }
}
add_action('wp', 'ikit_task_twicedaily_event_activation');

// Hourly tasks
function ikit_task_hourly_event_run() {

    ikit_national_remote_fetch();
    ikit_event_etouches_remote_fetch();
    ikit_job_remote_fetch();

    ikit_social_remote_fetch_designenvy_rss();
    ikit_social_remote_fetch_eyeondesign_rss();
    ikit_social_remote_fetch_twitter_messages_rss();
    ikit_social_remote_fetch_vimeo_videos_rss();
    ikit_social_remote_fetch_flickr_images_rss();
    ikit_social_remote_fetch_facebook_feed();
    ikit_social_remote_fetch_youtube_videos_rss();
    ikit_social_remote_fetch_instagram_feed();

}

// Twice daily tasks
function ikit_task_twicedaily_event_run() {

    ikit_event_eventbrite_remote_fetch(); # Events can only be pulled twice daily from etouches due to usage limitations

}

// Daily tasks
function ikit_task_daily_event_run() {

    ikit_member_remote_fetch();
    ikit_portfolio_remote_fetch();
    ikit_external_ikit_remote_fetch();
    ikit_national_external_posts_remote_fetch();

}



?>