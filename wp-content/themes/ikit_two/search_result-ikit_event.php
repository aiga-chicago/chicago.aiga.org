<?php
    $ikit_event_meta = ikit_event_get_meta($post->ID);
    $event = ikit_event_get_meta_normalized($post->ID, $ikit_event_meta, null);
    $event_image = $event['image'];
    $event_start_date = $event['start_date'];
    $event_end_date = $event['end_date'];
    $event_location_city = $event['location_city'];
    $internal = false;

    if($ikit_event_meta->service == IKIT_EVENT_SERVICE_INTERNAL) {
        $internal = true;
    }

?>

<div class="cat-plugin-fluid-grid-item grid-item">
    <div class="grid-item-inner">

        <div class="search-result">
            <?php if($event_image != null) { ?>
                <a href="<?php the_permalink(); ?>"><div class="search-result-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $event_image; ?>"></div></a>
            <?php } ?>
            <div class="search-result-type">EVENT</div>

            <a class="link-block" href="<?php the_permalink(); ?>">
                <span class="search-result-title"><?php echo $post->post_title; ?></span>
                <span class="search-result-date"><?php echo $event_start_date;?> <?php if($event_end_date != $event_start_date) { echo  ' - ' . $event_end_date; } ?> <?php if(!empty($event_location_city)) { echo '&middot; ' . $event_location_city; } ?></span>
            </a>
            <?php if($internal) { ?>
                <div class="search-result-event-internal">Community</div>
            <?php } ?>
        </div>

    </div>
</div>