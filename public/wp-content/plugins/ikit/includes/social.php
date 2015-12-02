<?php

define('IKIT_SOCIAL_TWITTER_MESSAGES_RSS_URL', 'http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=%s&count=10');
define('IKIT_SOCIAL_VIMEO_VIDEOS_RSS_URL', 'http://vimeo.com/%s/videos/rss');
define('IKIT_SOCIAL_YOUTUBE_VIDEOS_RSS_URL', 'http://gdata.youtube.com/feeds/base/users/%s/uploads');
define('IKIT_SOCIAL_FLICKR_IMAGES_RSS_URL', 'http://api.flickr.com/services/feeds/photos_public.gne?id=%s&lang=en-us&format=rss_200');
define('IKIT_SOCIAL_FACEBOOK_GENERIC_APP_ID', '1632631513627219');
define('IKIT_SOCIAL_FACEBOOK_GENERIC_APP_SECRET', '6218fa81f1edd75ac9c77ea86643bcbd');
define('IKIT_SOCIAL_EYEONDESIGN_RSS_URL', 'http://eyeondesign.aiga.org/feed');
define('IKIT_SOCIAL_DESIGNENVY_RSS_URL', 'http://feeds.feedburner.com/aigadesignenvy');
define('IKIT_SOCIAL_DESIGNENVY_RSS_NAMESPACE', 'http://designenvy.aiga.org/de/elements/1.0/');
define('IKIT_SOCIAL_FACEBOOK_FEED_CACHE_KEY', 'IKIT_SOCIAL_FACEBOOK_FEED_CACHE_KEY');
define('IKIT_SOCIAL_FACEBOOK_FEED_CACHE_SECS', 99999999); // Infinite cache
define('IKIT_SOCIAL_TWITTER_FEED_CACHE_KEY', 'IKIT_SOCIAL_TWITTER_FEED_CACHE_KEY');
define('IKIT_SOCIAL_TWITTER_FEED_CACHE_SECS', 99999999); // Infinite cache
define('IKIT_SOCIAL_INSTAGRAM_FEED_CACHE_KEY', 'IKIT_SOCIAL_INSTAGRAM_FEED_CACHE_KEY');
define('IKIT_SOCIAL_INSTAGRAM_FEED_CACHE_SECS', 99999999); // Infinite cache
define('IKIT_SOCIAL_INSTAGRAM_ACCESS_TOKEN', '303072081.d105731.6d98e1b8d2da4b86b4cad9ba32ee30c3'); // Access token generated via authentication URL for winfieldco_ user and saved, so that admins can skip the unneccessary authentication process
define('IKIT_SOCIAL_INSTAGRAM_API_URL', 'https://api.instagram.com/v1');

/**
 * Parses out the flickr image from the description, basically just finds
 * the img tag from the html.
 */
function ikit_social_flickr_image_from_description($data) {
    preg_match_all('/<img src="([^"]*)"([^>]*)>/i', $data, $matches);
    return $matches[1][0];
}

/**
 * Based on an video thumbnail url from vimeo, returns a URL for a different size of that image
 */
function ikit_social_vimeo_thumbnail_image_resize($image_url) {
    return $image_url;
}

/**
 * Based on an image url from flickr, returns a URL for a different size of that image
 */
function ikit_social_flickr_image_resize($img, $size) {

    $img = explode('/', $img);
    $filename = array_pop($img);

    // The sizes listed here are the ones Flickr provides by default.  Pass the array index in the
    // $size variable to selct one.
    // 0 for square, 1 for thumb, 2 for small, etc.
    $s = array(
        '_s.', // square
        '_t.', // thumb
        '_m.', // small
        '.',   // medium
        '_b.',  // large
        '_n.'
    );

    $img[] = preg_replace('/(_(s|t|m|b))?\./i', $s[$size], $filename);
    return implode('/', $img);
}

/**
 * Activates the URLs for a text update
 */
function ikit_social_url_converter($status,$targetBlank=true,$linkMaxLen=250) {

    // The target
    $target=$targetBlank ? " target=\"_blank\" " : "";

    // convert link to url
    $status = preg_replace("/((http:\/\/|https:\/\/)[^ )]+)/e", "'<a href=\"$1\" title=\"$1\"  $target >'. ((strlen('$1')>=$linkMaxLen ? substr('$1',0,$linkMaxLen).'...':'$1')).'</a>'", $status);

    // convert email addresses
    $email_pattern = "/[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}/i";
    if( preg_match( $email_pattern, $status, $email ) ) {
        $replacement = '<a href="mailto:' . $email[0]. '">' . $email[0] . '</a> ';
        $status = preg_replace($email_pattern, $replacement, $status);
    }

    // return the status
    return $status;
}

/**
 * Activates the URLs for a twitter status update
 */
/**
 * Activates the URLs for a twitter status update
 */
function ikit_social_twitter_url_converter($status,$targetBlank=true,$linkMaxLen=250) {

    //Convert urls to <a> links
    $status = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $status);

    //Convert hashtags to twitter searches in <a> links
    $status = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_blank\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $status);

    //Convert attags to twitter profiles in <a> links
    $status = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a target=\"_blank\" href=\"http://www.twitter.com/$1\">@$1</a>", $status);

    return $status;
}

/**
 * Refresh the facebook status cache
 */
function ikit_social_remote_fetch_facebook_feed() {

    global $g_options;

    // Pull twitter feed
    $facebook_id = null;
    if(isset($g_options[IKIT_PLUGIN_OPTION_FACEBOOK_ID])) {
        $facebook_id = $g_options[IKIT_PLUGIN_OPTION_FACEBOOK_ID];
    }

    if($facebook_id != null) {

        if (!class_exists('Facebook')) {
            include_once dirname( __FILE__ ) . '/library/facebook-php-sdk/src/facebook.php';
        }

        $facebook = new Facebook(array(
          'appId'  => IKIT_SOCIAL_FACEBOOK_GENERIC_APP_ID,
          'secret' => IKIT_SOCIAL_FACEBOOK_GENERIC_APP_SECRET,
        ));

        $contents = $facebook->api('/'. $facebook_id .'/feed?date_format=U&maxitems=5');

        $cache = FileCache::GetInstance(IKIT_SOCIAL_FACEBOOK_FEED_CACHE_SECS, IKIT_DIR_FILE_CACHE);
        $cache->delete(IKIT_SOCIAL_FACEBOOK_FEED_CACHE_KEY); // Deletes if older than the expiry time
        $cache->cache(IKIT_SOCIAL_FACEBOOK_FEED_CACHE_KEY, json_encode($contents));

    }

}

/**
 * Refresh the twitter messages cache
 */
function ikit_social_remote_fetch_twitter_messages_rss() {

    global $g_options;

    // Pull twitter feed
    $twitter_username = null;
    if(isset($g_options[IKIT_PLUGIN_OPTION_TWITTER_USERNAME])) {
        $twitter_username = $g_options[IKIT_PLUGIN_OPTION_TWITTER_USERNAME];
    }

    if($twitter_username != null) {

        include_once dirname( __FILE__ ) . '/library/twitteroauth/twitteroauth/twitteroauth.php';
        include_once dirname( __FILE__ ) . '/library/twitteroauth/config.php';

        $twitter_connection = new TwitterOAuth(
            'LagdiGmcpVTLbX9MjKKxw', // Consumer Key
            'wD05LmS98Wvgt39flT7JScWIxbI3Tb5pDypbCf9xP70', // Consumer secret
            '125141524-4UuStIXDMn9nhGyaaXa1AAqfccKMiVLrgJT3FIMe', // Access token
            '3jsvjkArPuoQg8OT8dAMDZDRJf6vKJJYrRDGuM7M3IY' // Access token secret
        );

        $twitter_data = $twitter_connection->get('statuses/user_timeline',
            array(
            'screen_name'     => $twitter_username,
            'count'           => 10,
            'exclude_replies' => true
            )
        );

        if($twitter_connection->http_code == 200) {

            $cache = FileCache::GetInstance(IKIT_SOCIAL_TWITTER_FEED_CACHE_SECS, IKIT_DIR_FILE_CACHE);
            $cache->delete(IKIT_SOCIAL_TWITTER_FEED_CACHE_KEY); // Deletes if older than the expiry time
            $cache->cache(IKIT_SOCIAL_TWITTER_FEED_CACHE_KEY, json_encode($twitter_data));

        }

    }

}

/**
 * Refresh the eye on design cache
 */
function ikit_social_remote_fetch_eyeondesign_rss() {

    $items = array();
    global $g_options;

    $feed = new SimplePie();
    $feed->set_cache_duration(0);
    $feed->set_timeout(5);
    $feed->set_feed_url(IKIT_SOCIAL_EYEONDESIGN_RSS_URL);
    $feed->set_cache_location(IKIT_DIR_CACHE);
    // XXX Fixes peculiar issue where feed was not pulling https://wcoaiga.atlassian.net/browse/AIGAIKIT-199
    $feed->force_feed (true);
    $feed->force_fsockopen (true);
    $feed->init();

}

/**
 * Refresh the designenvy cache
 */
function ikit_social_remote_fetch_designenvy_rss() {

    $items = array();
    global $g_options;

    $feed = new SimplePie();
    $feed->set_cache_duration(0);
    $feed->set_timeout(5);
    $feed->set_feed_url(IKIT_SOCIAL_DESIGNENVY_RSS_URL);
    $feed->set_cache_location(IKIT_DIR_CACHE);
    $feed->init();

}

/**
 * Refresh the vimeo videos cache
 */
function ikit_social_remote_fetch_vimeo_videos_rss() {

    global $g_options;

    $vimeo_user_id = null;
    if(isset($g_options[IKIT_PLUGIN_OPTION_VIMEO_USER_ID])) {
        $vimeo_user_id = $g_options[IKIT_PLUGIN_OPTION_VIMEO_USER_ID];
    }

    if($vimeo_user_id != null) {

        $feed = new SimplePie();
        $feed->set_cache_duration(0);
        $feed->set_timeout(5);
        $feed->set_feed_url(sprintf(IKIT_SOCIAL_VIMEO_VIDEOS_RSS_URL, $vimeo_user_id));
        $feed->set_cache_location(IKIT_DIR_CACHE);
        $feed->init();

    }

}

/**
 * Refresh the Youtube cache
 */
function ikit_social_remote_fetch_youtube_videos_rss() {

    global $g_options;

    $youtube_username = null;
    if(isset($g_options[IKIT_PLUGIN_OPTION_YOUTUBE_USERNAME])) {
        $youtube_username = $g_options[IKIT_PLUGIN_OPTION_YOUTUBE_USERNAME];
    }

    if($youtube_username != null) {

        $feed = new SimplePie();
        $feed->set_cache_duration(0);
        $feed->set_timeout(5);
        $feed->set_feed_url(sprintf(IKIT_SOCIAL_YOUTUBE_VIDEOS_RSS_URL, $youtube_username));
        $feed->set_cache_location(IKIT_DIR_CACHE);
        $feed->init();

    }
}

/**
 * Refresh the Instagram cache
 */
function ikit_social_remote_fetch_instagram_feed() {

    global $g_options;

    $instagram_user_id = null;
    if(isset($g_options[IKIT_PLUGIN_OPTION_INSTAGRAM_USER_ID])) {
        $instagram_user_id = $g_options[IKIT_PLUGIN_OPTION_INSTAGRAM_USER_ID];
    }

    if($instagram_user_id != null) {

        $curl_list_request = curl_init();

        // Fetch recent media for specified user
        curl_setopt($curl_list_request, CURLOPT_URL, IKIT_SOCIAL_INSTAGRAM_API_URL . "/users/" . $instagram_user_id . '/media/recent?access_token=' . IKIT_SOCIAL_INSTAGRAM_ACCESS_TOKEN);
        curl_setopt($curl_list_request, CURLOPT_RETURNTRANSFER, true);
        $curl_list_response = curl_exec($curl_list_request);
        curl_close($curl_list_request);

        $cache = FileCache::GetInstance(IKIT_SOCIAL_INSTAGRAM_FEED_CACHE_SECS, IKIT_DIR_FILE_CACHE);
        $cache->delete(IKIT_SOCIAL_INSTAGRAM_FEED_CACHE_KEY); // Deletes if older than the expiry time
        $cache->cache(IKIT_SOCIAL_INSTAGRAM_FEED_CACHE_KEY, json_decode($curl_list_response));



    }

}

/**
 * Refresh the flickr images cache
 */
function ikit_social_remote_fetch_flickr_images_rss() {

    global $g_options;

    $flickr_user_id = null;
    if(isset($g_options[IKIT_PLUGIN_OPTION_FLICKR_USER_ID])) {
        $flickr_user_id = $g_options[IKIT_PLUGIN_OPTION_FLICKR_USER_ID];
    }

    if($flickr_user_id != null) {

        $feed = new SimplePie();
        $feed->set_cache_duration(0);
        $feed->set_timeout(5);
        $feed->set_feed_url(sprintf(IKIT_SOCIAL_FLICKR_IMAGES_RSS_URL, $flickr_user_id));
        $feed->set_cache_location(IKIT_DIR_CACHE);
        $feed->init();

    }

}

// add_action('wp_loaded', 'ikit_social_remote_fetch_instagram_feed');
// add_action('wp_loaded', 'ikit_social_remote_fetch_eyeondesign_rss');

?>