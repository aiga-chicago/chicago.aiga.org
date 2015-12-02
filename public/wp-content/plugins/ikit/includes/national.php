<?php



define('IKIT_NATIONAL_FEED_IKIT_SPONSOR_URL', 'http://ikit.aiga.org/ikit_national_feed/ikit-national-feed-ikit-sponsor/');
define('IKIT_NATIONAL_FEED_PAGE_URL', 'http://ikit.aiga.org/ikit_national_feed/ikit-national-feed-page/');
define('IKIT_NATIONAL_EXTERNAL_POSTS_URL', 'http://www.aiga.org/aiga-main-feed/');
define('IKIT_NATIONAL_EXTERNAL_POSTS_CHAPTER_CODE', 'national');
define('IKIT_NATIONAL_EXTERNAL_POSTS_CHAPTER_LINK_URL', 'http://www.aiga.org');
define('IKIT_NATIONAL_EXTERNAL_POSTS_CHAPTER_DISPLAY_NAME', 'AIGA.ORG');


/**
 * Main file for feeds import and export
 */

/**
 * Reads in the sponsor RSS feed as determined by the settings
 * and adds/updates/deletes based on that feed, also sets
 * the external source for the item so that it doesn't get deleted
 * or edited by the user.
 */
function ikit_national_remote_fetch_ikit_sponsor() {

    $sponsors_feed_url = IKIT_NATIONAL_FEED_IKIT_SPONSOR_URL;

    // Use SimplePie to parse the feeds
    // note that we turn off cacheing, because this fetch
    // is called at the proper interval and should always
    // be freshest
    $feed = new SimplePie();
    $feed->set_feed_url($sponsors_feed_url);
    $feed->strip_attributes(false);
    $feed->enable_cache(false);
    $feed->init();
    $feed->handle_content_type();

    $feed_sponsor_slugs = array();

    foreach ($feed->get_items() as $item) {

        $sponsor_name = $item->get_title();

        // Extract the primary image url
        $sponsor_primary_image_url_el = $item->get_item_tags(IKIT_XML_NAMESPACE_URI, 'sponsorPrimaryImageUrl');
        $sponsor_primary_image_url = $sponsor_primary_image_url_el[0]['data'];

        // Extract the secondary image url
        $sponsor_secondary_image_url_el = $item->get_item_tags(IKIT_XML_NAMESPACE_URI, 'sponsorSecondaryImageUrl');
        $sponsor_secondary_image_url = $sponsor_secondary_image_url_el[0]['data'];

        // Extract the display order
        $sponsor_display_order_el = $item->get_item_tags(IKIT_XML_NAMESPACE_URI, 'sponsorDisplayOrder');
        $sponsor_display_order = $sponsor_display_order_el[0]['data'];

        // Extract the url
        $sponsor_url_el = $item->get_item_tags(IKIT_XML_NAMESPACE_URI, 'sponsorUrl');
        $sponsor_url = $sponsor_url_el[0]['data'];

        // Extract the slug
        $sponsor_slug_el = $item->get_item_tags(IKIT_XML_NAMESPACE_URI, 'sponsorSlug');
        $sponsor_slug = $sponsor_slug_el[0]['data'];
        $feed_sponsor_slugs[$sponsor_slug] = true;

        // Add or update existing sponsor
        $associated_post = array(
            'post_title' => $sponsor_name,
            'post_type' => IKIT_POST_TYPE_IKIT_SPONSOR,
            'post_status' => 'publish',
            'page_name' => $sponsor_slug,
            'post_content' => $item->get_description()
        );

        $existing_associated_post = ikit_get_post_by_slug($sponsor_slug, IKIT_POST_TYPE_IKIT_SPONSOR);

        if($existing_associated_post == null) {

            $new_associated_post_id = wp_insert_post($associated_post);
            if($new_associated_post_id > 0) { // Add custom fields

                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, IKIT_EXTERNAL_SOURCE_NATIONAL, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL, $sponsor_url, true);
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_DISPLAY_ORDER, $sponsor_display_order, true);

                ikit_media_sideload_image_meta($new_associated_post_id, $sponsor_primary_image_url, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE);
                ikit_media_sideload_image_meta($new_associated_post_id, $sponsor_secondary_image_url, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_SECONDARY_IMAGE);

            }
        }
        else {

            $associated_post['ID'] = $existing_associated_post->ID;
            $updated_associated_post_id = wp_update_post($associated_post);
            if($updated_associated_post_id > 0) {
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, IKIT_EXTERNAL_SOURCE_NATIONAL, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL, $sponsor_url, true);
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_DISPLAY_ORDER, $sponsor_display_order, true);

                ikit_media_sideload_image_meta($updated_associated_post_id, $sponsor_primary_image_url, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE);
                ikit_media_sideload_image_meta($updated_associated_post_id, $sponsor_secondary_image_url, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_SECONDARY_IMAGE);

            }
        }

    }

    if(count($feed_sponsor_slugs) > 0 && count($feed->get_items()) == count($feed_sponsor_slugs)) { // Safety check to not just delete randomly, should be at least one sponsor in the feed

        // Delete any non-existing sponsors marked with same external source
        $args=array(
          'post_type' => IKIT_POST_TYPE_IKIT_SPONSOR,
          'meta_key' => IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE,
          'meta_value' => IKIT_EXTERNAL_SOURCE_NATIONAL
        );
        $sponsors = get_posts($args);
        foreach($sponsors as $sponsor) {

            if(array_key_exists($sponsor->post_name, $feed_sponsor_slugs) == false) {
                // Delete
                wp_delete_post($sponsor->ID, true);
            }

        }

    }

}

/**
 * Reads in the pages RSS feed as determined by the settings
 * and adds/updates/deletes based on that feed, also sets
 * the external source for the item so that it doesn't get deleted
 * or edited by the user. The page content can then be placed
 * within other pages using the ikit page shortcode.
 */
function ikit_national_remote_fetch_page() {

    $page_feed_url = IKIT_NATIONAL_FEED_PAGE_URL;

    // Use SimplePie to parse the feeds
    // note that we turn off cacheing, because this fetch
    // is called at the proper interval and should always
    // be freshest
    $feed = new SimplePie();
    $feed->set_feed_url($page_feed_url);
    $feed->enable_cache(false);
    $feed->strip_attributes(false);
    $feed->init();
    $feed->handle_content_type();

    $feed_page_slugs = array();

    foreach ($feed->get_items() as $item) {

        $page_title = $item->get_title();
        $page_description = $item->get_description();

        // Extract the slug
        $page_slug_el = $item->get_item_tags(IKIT_XML_NAMESPACE_URI, 'pageSlug');
        $page_slug = $page_slug_el[0]['data'];
        $feed_page_slugs[$page_slug] = true;

        // Add or update existing sponsor
        $associated_post = array(
            'post_title' => $page_title,
            'post_name' => $page_slug,
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_content' => $page_description
        );

        $existing_associated_post = ikit_get_post_by_slug($page_slug, 'page');

        if($existing_associated_post == null) {
            $new_associated_post_id = wp_insert_post($associated_post);
            if($new_associated_post_id > 0) { // Add custom fields
                add_post_meta($new_associated_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, IKIT_EXTERNAL_SOURCE_NATIONAL, true);
            }
        }
        else {

            $associated_post['ID'] = $existing_associated_post->ID;

            $updated_associated_post_id = wp_update_post($associated_post);
            if($updated_associated_post_id > 0) {
                ikit_add_or_update_post_meta($updated_associated_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, IKIT_EXTERNAL_SOURCE_NATIONAL, true);
            }
        }

    }

    if(count($feed_page_slugs) > 0 && count($feed->get_items()) == count($feed_page_slugs)) { // Safety check to not just delete randomly, should be at least one page in the feed

        // Delete any non-existing sponsors marked with same external source
        $args=array(
          'post_type' => 'page',
          'meta_key' => IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE,
          'meta_value' => IKIT_EXTERNAL_SOURCE_NATIONAL
        );
        $pages = get_posts($args);
        foreach($pages as $page) {

            if(array_key_exists($page->post_name, $feed_page_slugs) == false) {
                // Delete
                wp_delete_post($page->ID, true);
            }

        }

    }

}

function ikit_national_external_posts_remote_fetch() {

    $curl_list_request = curl_init();
    curl_setopt($curl_list_request, CURLOPT_URL, IKIT_NATIONAL_EXTERNAL_POSTS_URL);
    curl_setopt($curl_list_request, CURLOPT_RETURNTRANSFER, true);
    $curl_list_response = curl_exec($curl_list_request);
    curl_close($curl_list_request);

    $result = simplexml_load_string($curl_list_response);
    $result->registerXPathNamespace("media", "http://search.yahoo.com/mrss/");

    $chapter_code = IKIT_NATIONAL_EXTERNAL_POSTS_CHAPTER_CODE;
    $external_source = sprintf(IKIT_POST_EXTERNAL_SOURCE_FORMAT, $chapter_code);
    $external_source_display_name = IKIT_NATIONAL_EXTERNAL_POSTS_CHAPTER_DISPLAY_NAME;
    $external_source_link_url = IKIT_NATIONAL_EXTERNAL_POSTS_CHAPTER_LINK_URL;

    $sanitized_items = array();

    foreach($result->channel->item as $item) {

        $link_url = trim((string)$item->link);
        $images = $item->xpath('media:content');
        $image_url = null;
        foreach($images as $image) {
            $image_url = (string)$image->attributes()->url;
            break;
        }
        $post_id = sprintf(IKIT_POST_EXTERNAL_ID_FORMAT, $chapter_code, sanitize_title($link_url)); // Generate an ID

        // Convert GMT to local
        $published_date = date("Y-m-d H:i:s", strtotime((string)$item->pubDate));
        $published_date_local = get_date_from_gmt($published_date);

        $sanitized_item = new stdClass();
        $sanitized_item->title = trim((string)$item->title);
        $sanitized_item->post_date = $published_date_local;
        $sanitized_item->link_url = $link_url;
        $sanitized_item->description = (string)$item->description;
        $sanitized_item->author = 'AIGA';
        $sanitized_item->image_url = $image_url;
        $sanitized_item->post_id = $post_id;

        array_push($sanitized_items, $sanitized_item);
    }

    ikit_post_external_remote_fetch_save($external_source, $external_source_display_name, $external_source_link_url, $sanitized_items);

}

/**
 * Pull feeds from national and create or update locked content types
 */
function ikit_national_remote_fetch() {

    // Below are fetches for which we do not want
    // to create a revision history for created posts etc.
    ikit_stop_revisions();

    ikit_national_remote_fetch_ikit_sponsor();
    ikit_national_remote_fetch_page();

    ikit_start_revisions();

}

// add_action('wp_loaded', 'ikit_national_remote_fetch_page');

?>