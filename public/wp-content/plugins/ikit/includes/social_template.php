<?php

function ikit_social_get_designenvy_rss_items() {

    $items = array();
    global $g_options;

    // Pull design envy feed

    // SimplePie handles cacheing so we can call this on every request
    // the default cache time should be about 60 minutes
    $feed = new SimplePie();
    $feed->set_cache_duration(999999999);
    $feed->set_timeout(-1);
    $feed->set_feed_url(IKIT_SOCIAL_DESIGNENVY_RSS_URL);
    $feed->set_cache_location(IKIT_DIR_CACHE);
    $feed->init();
    $feed->handle_content_type();

    foreach ($feed->get_items() as $item) {
        array_push($items, $item);
    }

    return $items;

}

function ikit_social_get_eyeondesign_rss_items() {

    $items = array();
    global $g_options;

    // Pull eyeondesign feed

    // SimplePie handles cacheing so we can call this on every request
    // the default cache time should be about 60 minutes
    $feed = new SimplePie();
    $feed->set_cache_duration(999999999);
    $feed->set_timeout(-1);
    $feed->set_feed_url(IKIT_SOCIAL_EYEONDESIGN_RSS_URL);
    $feed->set_cache_location(IKIT_DIR_CACHE);
    $feed->init();
    $feed->handle_content_type();

    foreach ($feed->get_items() as $item) {
        array_push($items, $item);
    }

    return $items;

}

/**
 * Returns a list of the latest twitter status messages as SimplePie objects
 * safe to call multiple times, cacheing is taken care of
  */
function ikit_social_get_twitter_messages_rss_items() {

    $cache = FileCache::GetInstance(IKIT_SOCIAL_TWITTER_FEED_CACHE_SECS, IKIT_DIR_FILE_CACHE);
    $cache_contents = $cache->cache(IKIT_SOCIAL_TWITTER_FEED_CACHE_KEY);
    if($cache_contents != null) {

        $messages = json_decode($cache->cache(IKIT_SOCIAL_TWITTER_FEED_CACHE_KEY));
        if(isset($messages)) {
            return $messages;
        }

    }

    return array();

}

/**
 * Returns a list of the latest vimeo videos as SimplePie objects
 * safe to call multiple times, cacheing is taken care of
 */
function ikit_social_get_vimeo_videos_rss_items() {

    $videos = array();
    global $g_options;

    // Pull vimeo feed
    $vimeo_user_id = null;
    if(isset($g_options[IKIT_PLUGIN_OPTION_VIMEO_USER_ID])) {
        $vimeo_user_id = $g_options[IKIT_PLUGIN_OPTION_VIMEO_USER_ID];
    }

    if($vimeo_user_id != null) {

        // SimplePie handles cacheing so we can call this on every request
        // the default cache time should be about 60 minutes
        $feed = new SimplePie();
        $feed->set_cache_duration(999999999);
        $feed->set_timeout(-1);
        $feed->set_feed_url(sprintf(IKIT_SOCIAL_VIMEO_VIDEOS_RSS_URL, $vimeo_user_id));
        $feed->set_cache_location(IKIT_DIR_CACHE);
        $feed->init();
        $feed->handle_content_type();

        foreach ($feed->get_items() as $item) {
            array_push($videos, $item);
        }

    }

    return $videos;

}

function ikit_social_get_instagram_feed_items() {

    $cache = FileCache::GetInstance(IKIT_SOCIAL_INSTAGRAM_FEED_CACHE_SECS, IKIT_DIR_FILE_CACHE);
    $cache_contents = $cache->cache(IKIT_SOCIAL_INSTAGRAM_FEED_CACHE_KEY);
    if($cache_contents != null) {

        $photos = $cache->cache(IKIT_SOCIAL_INSTAGRAM_FEED_CACHE_KEY);
        if(isset($photos->data)) {
            return $photos->data;
        }

    }

    return array();

}

/**
 * Returns a list of the latest youtube videos as SimplePie objects
 * safe to call multiple times, cacheing is taken care of
 */
function ikit_social_get_youtube_videos_rss_items() {

    $videos = array();
    global $g_options;

    $youtube_username = null;
    if(isset($g_options[IKIT_PLUGIN_OPTION_YOUTUBE_USERNAME])) {
        $youtube_username = $g_options[IKIT_PLUGIN_OPTION_YOUTUBE_USERNAME];
    }

    if($youtube_username != null) {

        // SimplePie handles cacheing so we can call this on every request
        // the default cache time should be about 60 minutes
        $feed = new SimplePie();
        $feed->set_cache_duration(999999999);
        $feed->set_timeout(-1);
        $feed->set_feed_url(sprintf(IKIT_SOCIAL_YOUTUBE_VIDEOS_RSS_URL, $youtube_username));
        $feed->set_cache_location(IKIT_DIR_CACHE);
        $feed->init();
        $feed->handle_content_type();

        foreach ($feed->get_items() as $item) {
            array_push($videos, $item);
        }

    }

    return $videos;

}

/**
 * Returns a list of the latest facebook status messages as objects
 * safe to call multiple times, cacheing is taken care of,
  */
function ikit_social_get_facebook_feed_items() {

    $cache = FileCache::GetInstance(IKIT_SOCIAL_FACEBOOK_FEED_CACHE_SECS, IKIT_DIR_FILE_CACHE);
    $cache_contents = $cache->cache(IKIT_SOCIAL_FACEBOOK_FEED_CACHE_KEY);
    if($cache_contents != null) {

        $messages = json_decode($cache->cache(IKIT_SOCIAL_FACEBOOK_FEED_CACHE_KEY));
        if(isset($messages->data)) {
            return $messages->data;
        }

    }

    return array();

}

/**
 * Returns a list of the latest flickr images as SimplePie objects
 * safe to call multiple times, cacheing is taken care of, you should
 * use the functions ikit_social_flickr_image_from_description and
 * ikit_social_flickr_image_resize to get the image URL from each feed
 * item.
 */
function ikit_social_get_flickr_images_rss_items() {

    $images = array();
    global $g_options;

    // Pull twitter feed
    $flickr_user_id = null;
    if(isset($g_options[IKIT_PLUGIN_OPTION_FLICKR_USER_ID])) {
        $flickr_user_id = $g_options[IKIT_PLUGIN_OPTION_FLICKR_USER_ID];
    }

    if($flickr_user_id != null) {

        // SimplePie handles cacheing so we can call this on every request
        // the default cache time should be about 60 minutes
        $feed = new SimplePie();
        $feed->set_cache_duration(999999999);
        $feed->set_timeout(-1);
        $feed->set_feed_url(sprintf(IKIT_SOCIAL_FLICKR_IMAGES_RSS_URL, $flickr_user_id));
        $feed->set_cache_location(IKIT_DIR_CACHE);
        $feed->init();
        $feed->handle_content_type();

        foreach ($feed->get_items() as $item) {
            array_push($images, $item);
        }

    }

    return $images;

}

?>