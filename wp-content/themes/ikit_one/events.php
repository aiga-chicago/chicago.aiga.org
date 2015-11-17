<?php
/**
 * Template Name: Events
 *
 * @package WordPress
 * @subpackage Internet_Kit
 * @since Internet Kit One 1.0.0
 *
 */
?>

<?php get_header();?>

<div class="box-container">
<div class="box">
<?php

$date = $_GET['date']; // Filter events by date

$ikit_section_events = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_PAGE_IKIT_SECTION, true);
ikit_one_render_banner_header($ikit_section_events, null, $date == null ? 'Upcoming Events' : 'Events');

?>

<?php

// Get the posts
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array();
$args['posts_per_page'] = $g_theme_options[IKIT_ONE_THEME_OPTION_EVENTS_POSTS_PER_PAGE];
$args['paged'] = $paged;

$args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL);
$args['order'] = 'ASC';
$args['orderby'] = 'meta_value_num';
$args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;

// No filtering, show all events by date
if($date == null) {

    $args['meta_query'] = array(

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, 'value' => date_i18n("Y-m-d"),
        'compare' => '>=',
        'type' => 'DATE'),

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, 'value' => 'Closed',
        'compare' => '!=',
        'type' => 'CHAR')

    );

}
else {


    $args['meta_query'] = array(

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, 'value' => $date,
        'compare' => '>=',
        'type' => 'DATE'),

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, 'value' => $date,
        'compare' => '<=',
        'type' => 'DATE'),

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, 'value' => 'Closed',
        'compare' => '!=',
        'type' => 'CHAR')

    );


}


query_posts($args);

while (have_posts()) : the_post();

    include 'events-item.php';

endwhile;

?>

</div>

<?php

ikit_one_render_pager($wp_query, 'NEXT', null, false, 'PREVIOUS', null, false);
wp_reset_query();

?>

</div>

<div class="box-close"></div>

<?php get_sidebar();?>
<?php get_footer();?>