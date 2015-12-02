<?php

/**
 * Main file for external events and posts, externals are feeds from outside,
 * external defines a standard way of consuming posts and events into the
 * iKit from external sources.
 */

define('IKIT_POST_EXTERNAL_ID_FORMAT', 'post_external_id_%s_%s'); // Unique ID comprised of a section, then ID
define('IKIT_POST_EXTERNAL_SOURCE_FORMAT', 'post_external_source_%s');

/**
 * Add custom post edit widget to sync with eventbrite
 */
add_action('add_meta_boxes', 'ikit_post_external_add_meta_boxes');

function ikit_post_external_add_meta_boxes() {
    $screens = array(IKIT_POST_TYPE_IKIT_POST_EXTERNAL);
    foreach ($screens as $screen) {
        add_meta_box(
            'ikit_post_external',
            __( 'Post External Information', 'ikit_post_external' ),
            'ikit_post_external_add_meta_box_integration',
            $screen
        );
    }
}

function ikit_post_external_add_meta_box_integration($post) {

    $external_source_display_name = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, true);
    $post_date = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_DATE, true);

    ?>

    <div class="wp-box">
    <div class="inner">

    <table class="widefat">
    <tr>
        <td>
        External Source
        </td>
        <td>
        <?php echo $external_source_display_name; ?>
        </td>
    </tr>
    <tr>
        <td>
        Post Date
        </td>
        <td>
        <?php echo $post_date; ?>
        </td>
    </tr>
    </table>

    <?php

}

/**
 * Saves the posts from a remote fetch, assumes posts objects are of a particular format
 */
function ikit_post_external_remote_fetch_save($external_source, $external_source_display_name, $external_source_link_url, $items) {

    // Find all existing external posts for this source, delete any that are no longer in the feed
    $existing_posts = ikit_get_posts_by_meta(IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, $external_source, IKIT_POST_TYPE_IKIT_POST_EXTERNAL, 999999);
    $existing_post_exists_by_id = array();
    foreach($existing_posts as $existing_post) {
        $existing_post_exists_by_id[get_post_meta($existing_post->ID, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_ID, true)] = 0;
    }

    foreach($items as $item) {

        $title = $item->title;
        $post_date = $item->post_date;
        $link_url = $item->link_url;
        $description = $item->description;
        $author = $item->author;
        $image_url = $item->image_url;
        $post_id = $item->post_id;

        $post = array(
            'post_title' => $title,
            'post_type' => IKIT_POST_TYPE_IKIT_POST_EXTERNAL,
            'post_content' => $description,
            'post_date' => $post_date,
            'edit_date' => true
        );

        $existing_post_exists_by_id[$post_id] = 1;

        $existing_post = ikit_get_post_by_meta(IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_ID, $post_id, IKIT_POST_TYPE_IKIT_POST_EXTERNAL);
        if($existing_post == null) {

            $post['post_status'] = 'draft';

            $new_post_id = wp_insert_post($post);

            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_ID, $post_id, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, $external_source, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, $external_source_display_name, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_POST_ATTRIBUTION, $author, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_IMAGE, $image_url, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_LINK_URL, $external_source_link_url, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_LINK_URL, $link_url, true);
            add_post_meta($new_post_id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_DATE, $post_date, true);

        }
        else {

            $post['ID'] = $existing_post->ID;

            $updated_post_id = wp_update_post($post);

            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE, $external_source, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, $external_source_display_name, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_POST_ATTRIBUTION, $author, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_IMAGE, $image_url, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_LINK_URL, $external_source_link_url, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_LINK_URL, $link_url, true);
            ikit_add_or_update_post_meta($updated_post_id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_DATE, $post_date, true);

        }

    }

    // Delete posts no longer in feed
    foreach($existing_posts as $existing_post) {
        $exists = $existing_post_exists_by_id[get_post_meta($existing_post->ID, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_ID, true)];
        if($exists == 0) {
            wp_delete_post($existing_post->ID, true);
        }
    }

}

add_action( 'save_post', 'ikit_post_external_save_post');
function ikit_post_external_save_post($post_id) {

    $existing_post = get_post($post_id);
    if($existing_post->post_type == IKIT_POST_TYPE_IKIT_POST_EXTERNAL) {

        remove_action('save_post', 'ikit_post_external_save_post' );

        // Keep the publish date the same in all cases, otherwise WP will set the publish date to now
        // which would be incorrect, as the posts are from external sources and publishing is really
        // a toggle for inclusion on the site.
        $post_date = get_post_meta($post_id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_DATE, true);
        $post = array(
            'ID' => $post_id,
            'post_date' => $post_date,
            'edit_date' => true,
        );
        wp_update_post($post);


        add_action('save_post', 'ikit_post_external_save_post' );

    }


}

?>