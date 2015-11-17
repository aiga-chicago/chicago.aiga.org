<?php
/**
 * Shortcodes available for Internet Kit
 */


/**
 * Insert page content given the page slug
 *
 * [ikit_page_content slug="myslug"]
 */
function ikit_page_content_shortcode($atts){

    extract( shortcode_atts( array(
    'slug' => null,
    ), $atts ) );

    $page = ikit_get_post_by_slug($slug, 'page');
    return $page->post_content;

}
add_shortcode( 'ikit_page_content', 'ikit_page_content_shortcode' );


/**
 * Insert post content given post slug and optional post type
 */
function ikit_post_content_shortcode($atts){

    extract( shortcode_atts( array(
    'slug' => null,
    'post_type' => 'post',
    ), $atts ) );

    $post = ikit_get_post_by_slug($slug, $post_type);
    return $post->post_content;

}
add_shortcode( 'ikit_post_content', 'ikit_post_content_shortcode' );

?>