<?php

/**
 * Return the author, if attribution is set on the post, return that instead
 */
function ikit_post_get_author($id, $author) {

    $result = $author;
    $attribution = get_field(IKIT_CUSTOM_FIELD_POST_ATTRIBUTION, $id);
    if(empty($attribution) == false) {
        $result = $attribution;
    }
    return $result;

}

/**
 * Return the post image URL
 */
function ikit_post_get_image_url($id, $size, $default_image_url) {

    $news_image = null;
    $image_gallery = get_field(IKIT_CUSTOM_FIELD_POST_IMAGE_GALLERY, $id);

    foreach($image_gallery as $image_gallery_row) {
        $news_image = $image_gallery_row;
        $news_image = wp_get_attachment_image_src($news_image['image'], $size);
        $news_image = $news_image[0];
        break;
    }

    if(empty($news_image)) {
        $news_image = get_post_meta($id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_IMAGE, true); // Check if external image available
    }

    if(empty($news_image)) {
        $news_image = $default_image_url;
    }
    return $news_image;

}

?>