<?php

    global $id;

    $ikit_event_meta = ikit_event_get_meta($id);
    $event_location_city = null;
    $event_description = $ikit_event_meta->description;
    $event_image = ikit_event_get_image_url($id, $ikit_event_meta, ikit_one_get_event_image_default());
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

        <div class="box-section-image"><a target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><img onerror="this.src='<?php echo ikit_one_get_event_image_default(); ?>'" src="<?php if($event_image != null) { echo $event_image; } ?>"></img></a></div>

        <?php if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) { ?>
            <div class="box-section-title-source"><a target="_blank" class="text-color-ikit-section-event external-link-unstyled" href="<?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_LINK_URL, true); ?>">EVENTS FROM <?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE_DISPLAY_NAME, true); ?></a></div>
        <?php } ?>

        <div class="box-section-title-detail"><?php echo $event_start_date;?> <?php if($event_end_date != $event_start_date) { echo ' - ' . $event_end_date; } ?> <?php if(!empty($event_location_city)) { echo '/ ' . $event_location_city; } ?></div>
        <div class="box-section-title"><a class="external-link-unstyled" target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><?php the_title(); ?></a></div>
        <div class="box-section-body"><?php echo $event_description; ?></div>
        <div class="box-section-button"><a class="external-link-unstyled" target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><?php if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) { echo 'go to event'; } else { echo 'details'; } ?></a></div>

    </div>




    <?php





?>