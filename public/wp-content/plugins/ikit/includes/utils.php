<?php



/**
 * Return the file extension given a file name
 */
function ikit_get_file_extension($file_name) {
   $parts=explode(".",$file_name);
   return $parts[count($parts)-1];
}

/**
 * Get directory
 */
function ikit_get_abs_path($relative_path) {
    $plugin_abs_path = dirname( __FILE__ ) . '/../' . $relative_path;
    return $plugin_abs_path;
}

/**
 * Delete posts
 */
function ikit_delete_posts($post_type, $post_statuses = array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')) {

    $args = array();
    $args['post_type'] = $post_type;
    $args['post_status'] = $post_statuses;
    $args['posts_per_page'] = 9999;
    $posts = get_posts($args);
    foreach($posts as $post) {
        wp_delete_post($post->ID, true);
    }

}

/**
 * Purge events
 */
function ikit_delete_ikit_events($service) {

    $posts = ikit_get_posts_by_meta(IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, $service, IKIT_POST_TYPE_IKIT_EVENT, 999999);
    foreach($posts as $post) {
        wp_delete_post($post->ID, true);
    }

    global $wpdb;
    if($service == IKIT_EVENT_SERVICE_ETOUCHES) {
        $table_name = $wpdb->prefix . IKIT_EVENT_ETOUCHES_TABLE_NAME;
        $wpdb->query("truncate table $table_name");
    }
    else if($service == IKIT_EVENT_SERVICE_EVENTBRITE) {
        $table_name = $wpdb->prefix . IKIT_EVENT_EVENTBRITE_TABLE_NAME;
        $wpdb->query("truncate table $table_name");
    }






}

/**
 * Get the title of the blog
 */
function ikit_get_blog_title() {

    $blog_title = get_bloginfo('name');
    if(strpos($blog_title, 'AIGA') === false) {
        $blog_title = 'AIGA ' . $blog_title; // If does not contain AIGA, append
    }
    return $blog_title;

}

/**
 * Purge jobs
 */
function ikit_delete_ikit_jobs() {

    ikit_delete_posts(IKIT_POST_TYPE_IKIT_JOB);

    global $wpdb;
    $table_name = $wpdb->prefix . IKIT_JOB_TABLE_NAME;
    $wpdb->query("truncate table $table_name");

}

/**
 * Purge portfolios
 */
function ikit_delete_ikit_portfolios() {

    ikit_delete_posts(IKIT_POST_TYPE_IKIT_PORTFOLIO);

    global $wpdb;
    $project_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_TABLE_NAME;
    $owner_table_name = $wpdb->prefix . IKIT_PORTFOLIO_OWNER_TABLE_NAME;
    $project_owner_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_OWNER_TABLE_NAME;

    $wpdb->query("truncate table $project_table_name");
    $wpdb->query("truncate table $owner_table_name");
    $wpdb->query("truncate table $project_owner_table_name");

}

/**
 * Get the URL to the plugin
 */
function ikit_get_plugin_url($relative_path) {
    return plugins_url('ikit') . '/' . $relative_path;
}

/**
 * Get the request URL, the current URL being displayed
 */
function ikit_get_request_url($include_query) {
    $url = 'http://';
    if(isset($_SERVER['HTTPS'])) {
        $url = 'https://';
    }

    if($include_query) {
        return $url . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
    }
    else {
        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        return $url . $_SERVER["HTTP_HOST"] . $uri_parts[0];
    }
}

/**
 * Forces an update of the post content without going through the sanitization process that
 * may strip css classes etc.
 */
function ikit_force_update_post_content($post_id, $post_content) {

    global $wpdb;

    $wpdb->query(
        "
        UPDATE $wpdb->posts
        SET post_content = '$post_content'
        WHERE ID = $post_id
        "
    );

}

function ikit_national_plugin_installed() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if(is_plugin_active('ikit_national/ikit_national.php') == false) {
        return true;
    }
    return false;
}

/**
 * Checks for an update given an update url, the update url
 * should simply be a text file of the form, version|package url|package info url
 *
 * 1.2.3|http://example.com/myplugin-1.2.3.zip|http://example.com/whatsnew.html
 *
 * If the version is greater than the existing version, a notice
 * will appear to the user with the appropriate download link.
 *
 */
function ikit_explode_update_info($update_info) {

    $update_info_exploded = explode('|', $update_info);
    if(empty($update_info_exploded) == false && count($update_info_exploded) == 3) {
        $latest_version = $update_info_exploded[0];
        $latest_version_url = $update_info_exploded[1];
        $latest_version_info_url = $update_info_exploded[2];
        return $update_info_exploded;
    }

    return null;
}

function ikit_get_plugin_version() {
    $plugin_info = get_site_transient('update_plugins');
    $plugin_version = $plugin_info->checked['ikit/ikit.php'];
    return $plugin_version;
}

/**
 * Convenience function that calls get posts with the normal arg
 * array, but returns only a single item, or null if no items
 * are returned.
 */
function ikit_get_posts_single($args) {

    $post = null;
    $posts = get_posts($args);
    if($posts && count($posts) > 0) {
        $post = $posts[0];
    }

    return $post;

}

/**
 * Stop revisions from being made, this will save on DB rows
 * for things like automated nation feed pages, images etc.
 */
function ikit_stop_revisions() {
    remove_action('pre_post_update', 'wp_save_post_revision');
}

/**
 * Restart revisions being made, ikit_stop_revisions should
 * be called before this, typically having the block of
 * code that should not use revisions between the two.
 */
function ikit_start_revisions() {
    add_action('pre_post_update', 'wp_save_post_revision');
}

/**
 * Returns posts by meta key value with ordering
 */
function ikit_get_posts_by_meta($meta_key, $meta_value, $post_type, $showposts = 1) {

    $args=array(
      'post_type' => $post_type,
      'post_status' => array('publish', 'pending', 'draft', 'future', 'trash'),
      'meta_key' => $meta_key,
      'meta_value' => $meta_value,
      'showposts' => $showposts
    );
    $my_posts = get_posts($args);
    if( $my_posts ) {
        return $my_posts;
    }

    return array();

}

/**
 * Return a post by a meta key value
 */
function ikit_get_post_by_meta($meta_key, $meta_value, $post_type) {

    $posts = ikit_get_posts_by_meta($meta_key, $meta_value, $post_type, 1);
    if(count($posts) > 0) {
        return $posts[0];
    }

    return null;

}

/**
 * Return post meta, but allows for settings a default value in case the post meta key does not exist
 */
function ikit_get_post_meta($post_id, $key, $single, $default) {

    $value = get_post_meta($post_id, $key, $single);
    if($single && empty($value)) {
        return $default;
    }

    if($single == false && count($value) <= 0) {
        return $default;
    }

    return $value;

}

/**
 * Assumes the meta value is an attachment ID, adds or updates attachment, will
 * do a test for the file name, if it's the same won't update as is unneccesary
  */
function ikit_media_sideload_image_meta($post_id, $image_url, $image_url_meta_key) {

    $existing_attachment_id = get_post_meta($post_id, $image_url_meta_key, true);
    if(isset($existing_attachment_id)) {

        $existing_attachment_url = wp_get_attachment_url($existing_attachment_id);
        if(basename($existing_attachment_url) != basename($image_url)) {

            wp_delete_attachment($existing_attachment_id);

            $attachment_id = ikit_media_sideload_image($image_url, $post_id);
            ikit_add_or_update_post_meta($post_id, $image_url_meta_key, $attachment_id, true);
        }

    }
    else {

        $attachment_id = ikit_media_sideload_image($image_url, $post_id);
        ikit_add_or_update_post_meta($post_id, $image_url_meta_key, $attachment_id, true);


    }

}

/**
 * Same as media_sideload_image but instead returns the attachment ID
 */
function ikit_media_sideload_image($file, $post_id, $desc = null) {

    // We need to import certain admin functions that are called below
    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    if ( ! empty($file) ) {
        // Download file to temp location
        $tmp = download_url( $file );

        // Set variables for storage
        // fix file filename for query strings
        preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $file, $matches);
        $file_array['name'] = basename($matches[0]);
        $file_array['tmp_name'] = $tmp;

        // If error storing temporarily, unlink
        if ( is_wp_error( $tmp ) ) {
            @unlink($file_array['tmp_name']);
            $file_array['tmp_name'] = '';
        }

        // do the validation and storage stuff
        $id = media_handle_sideload( $file_array, $post_id, $desc );
        // If error storing permanently, unlink
        if ( is_wp_error($id) ) {
            @unlink($file_array['tmp_name']);
            return $id;
        }

        $src = wp_get_attachment_url( $id );
    }

    // Finally check to make sure the file has been saved, then return the html
    if ( ! empty($src) ) {
        return $id;
    }
}

/**
 * Adds a meta field, if it already exists updates it, note that
 * you should use this function if the field may already exist,
 * simply calling add_post_meta will not update an existing field
 */
function ikit_add_or_update_post_meta($post_id, $meta_key, $meta_value, $unique) {

    add_post_meta($post_id, $meta_key, $meta_value, $unique) or update_post_meta($post_id, $meta_key, $meta_value);

}

/**
 * Strip the enclosing <![CDATA[ ... ]]> tags, useful if a webservice
 * returns content enclosed by these tags, but you do not want to store
 * the tags in the database.
 */
function ikit_strip_cdata_tags($string) {
    preg_match_all('/<!\[cdata\[(.*?)\]\]>/is', $string, $matches);
    return str_replace($matches[0], $matches[1], $string);
}

/**
 * Check if value is set and not empty, this handles
 * the case where the variable is totally not defined and null
 * but also in the case it is a blank string such as ""
 */
function ikit_isset_not_empty($value) {
    if(isset($value) && empty($value) == false) {
        return true;
    }
    else {
        return false;
    }
}

/**
 * Return a post by it's slug
 */
function ikit_get_post_by_slug($slug, $post_type) {
    $the_slug = $slug;
    $args=array(
      'name' => $the_slug,
      'post_type' => $post_type,
      'post_status' => array('publish', 'pending', 'draft', 'future', 'trash'),
      'showposts' => 1
    );
    $my_posts = get_posts($args);
    if( $my_posts ) {
        return $my_posts[0];
    }

    return null;
}

/**
 * Get categories for a given post type, if a category
 * does not have any posts assigned, it will not be returned.
 * Useful to display categories without providings links to
 * categories that have no results.
 */
function ikit_get_categories_for_post_type($post_type, $args = '') {
    $exclude = array();

    // Check ALL categories for posts of given post type
    foreach (get_categories() as $category) {
        $posts = get_posts(array('post_type' => $post_type, 'category' => $category->cat_ID));

        // If no posts found, ...
        if (empty($posts))
            // ...add category to exclude list
            $exclude[] = $category->cat_ID;
    }

    // Set up args
    if (!empty($exclude)) {
        $args .= ('' === $args) ? '' : '&';
        $args .= 'exclude='.implode(',', $exclude);
    }

    // List categories
    return get_categories($args);
}

/**
 * Get a page URL given the page slug
 */
function ikit_get_page_permalink_by_slug($slug) {
    return get_permalink(get_page_by_path($slug));
}

/**
 * Strip style attributes from a HTML string, this can be used to sanitize content from feeds
 */
function ikit_strip_style_attributes($html) {
    return preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html);
}

/**
 * Strip the date from the datetime, returns date in string format
 */
function ikit_date_without_time($datetime) {

    $date_regex = '/([0-9-]+)\s[0-9:]+/';
    preg_match($date_regex, (string)$datetime, $matches);
    return $matches[1];

}

/**
 * Strip the time from the datetime, returns time in string format
 */
function ikit_time_without_date($datetime) {

    $date_regex = '/[0-9-]+\s([0-9:]+)/';
    preg_match($date_regex, (string)$datetime, $matches);
    return $matches[1];

}

/**
 * Truncate text, also handles HTML.
 * This code is taken from the cakephp framework.
 */
function ikit_truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $open_tags = array();
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                // if tag is a closing tag
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                    unset($open_tags[$pos]);
                    }
                // if tag is an opening tag
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length+$content_length> $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1]+1-$entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if($total_length>= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }
    return $truncate;
}

?>