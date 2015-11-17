<?php
/**
 * Template Name: Events Past
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
<div class="box-top-empty-spacer"></div>
<?php

$ikit_section_events = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_PAGE_IKIT_SECTION, true);
ikit_one_render_banner_header($ikit_section_events, null, 'Past Events');?>

<?php

// Get the posts
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$post_count = 0;

$args = array();
$args['posts_per_page'] = $g_theme_options[IKIT_ONE_THEME_OPTION_EVENTS_POSTS_PER_PAGE];
$args['paged'] = $paged;
$args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL);
$args['orderby'] = 'meta_value';
$args['meta_key'] = IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE;

$args['meta_query'] = array(array(
    'key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE,
    'value' => date_i18n("Y-m-d"), 'compare' => '<',
    'type' => 'DATE'
));

query_posts($args);

while (have_posts()) : the_post();
    global $id;

    $ikit_event_meta = ikit_event_get_meta($id);
    $event_image = ikit_event_get_image_url($id, $ikit_event_meta, ikit_one_get_event_image_default());
    $event_description = $ikit_event_meta->description;
    $event_start_date = mysql2date('l, F j, Y', get_gmt_from_date($ikit_event_meta->start_date), false);
    $event_end_date = mysql2date('l, F j, Y', get_gmt_from_date($ikit_event_meta->end_date), false);
    $event_url = get_permalink($id);
    $event_url_target = '_self';

    if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EVENTBRITE) {
        $event_location_city = $ikit_event_meta->venue_city;
        $event_description = ikit_strip_style_attributes($event_description);
    }
    else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_ETOUCHES) {
        $event_location_city = $ikit_event_meta->location_city;
        $event_description = wpautop(ikit_strip_style_attributes($event_description));
    }
    else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) {
        $event_location_city = $ikit_event_meta->location;
        $event_url = $ikit_event_meta->url;
        $event_url_target = '_blank';
    }

    ?>
    <div class="box-section ikit_event-<? echo $post->post_name; ?>">

        <?php if($post_count != 0) { ?>
            <div class="box-section-divider"></div>
        <?php } ?>

        <table class="box-section-split">
        <tr>
        <td class="box-section-split-col0">

            <div class="box-section-image"><img onerror="this.src='<?php echo ikit_one_get_event_image_default(); ?>'" src="<?php if($event_image != null) { echo $event_image; } ?>"></img></div>

        </td>
        <td class="box-section-split-col1">

            <?php if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) { ?>
                <div class="box-section-title-source"><a target="_blank" class="text-color-ikit-section-event external-link-unstyled" href="<?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_LINK_URL, true); ?>">EVENTS FROM <?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, true); ?></a></div>
            <?php } ?>

            <div class="box-section-title-detail"><?php echo $event_start_date;?> <?php if($event_end_date != $event_start_date) { echo  ' - ' . $event_end_date; } ?> <?php if(!empty($event_location_city)) { echo '/ ' . $event_location_city; } ?></div>
            <div class="box-section-title"><a class="external-link-unstyled" target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><?php the_title(); ?></a></div>
            <div class="box-section-body"><?php echo $event_description; ?></div>
            <div class="box-section-button"><a class="external-link-unstyled" target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><?php if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) { echo 'go to event'; } else { echo 'details'; } ?></a></div>

        </td>
        </tr>
        </table>

    </div>




    <?php

    $post_count++;

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