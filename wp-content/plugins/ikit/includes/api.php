<?php

/**
 * API for iKit, includes various functions using the WordPress Ajax framework.
 *
 * To make calls to the API, send a POST request to http://mydomain/wp-admin/admin-ajax.php,
 * replacing mydomain with your own domain. Add the required POST parameter
 * action, and whatever other parameters are required depending on the action.
 * Note the action name is just the method name minus the wp_ajax_ prefix.
 * Methods return data in JSON format.
 *
 * E.g.
 *
 * http:<mydomain>/wp-admin/admin-ajax.php
 *
 * w/post parameters:
 * action=ikit_api_v1_ikit_event_search
 *
 */

/**
 * Currently takes no parameters, just returns list of upcoming events
 */
function ikit_api_v1_ikit_event_search() {

    global $g_options;
    $imus_chapter = $g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_IMUS_CHAPTER];

    $results = array();

    $args = array();
    $args['posts_per_page'] = 20;

    $args['post_type'] = IKIT_POST_TYPE_IKIT_EVENT;
    $args['order'] = 'ASC';
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;

    $args['meta_query'] = array(

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, 'value' => date_i18n("Y-m-d"),
        'compare' => '>=',
        'type' => 'DATE'),

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, 'value' => 'Closed',
        'compare' => '!=',
        'type' => 'CHAR')

    );

    query_posts($args);

    ?>

    <result>
    <site>
        <code><?php echo $imus_chapter; ?></code>
        <title><![CDATA[<?php echo ikit_get_blog_title(); ?>]]></title>
        <linkUrl><![CDATA[<?php echo get_bloginfo('url'); ?>]]></linkUrl>
    </site>
    <events>

    <?php

    while (have_posts()) : the_post();

        global $id;

        $ikit_event_meta = ikit_event_get_meta($id);

        if($ikit_event_meta->folder_id != IKIT_EVENT_FOLDER_ID_NATIONAL && $ikit_event_meta->folder_id != IKIT_EVENT_FOLDER_ID_NATIONAL_REGISTRATION_TYPE_DISABLED) { // Note may not return the full count, because exludes national events

            $event_image_url = ikit_event_get_image_url($id, $ikit_event_meta, null);
            $event_title = null;
            $event_location = null;

            if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EVENTBRITE) {
                $event_title = $ikit_event_meta->title;
                $event_location = $ikit_event_meta->venue_city;
            }
            else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_ETOUCHES) {
                $event_title = $ikit_event_meta->name;
                $event_location = $ikit_event_meta->location_city;
            }

            ?>
            <event>
                <id><?php echo $id ?></id>
                <title><![CDATA[<?php echo $event_title; ?>]]></title>
                <startDate><?php echo ikit_date_without_time($ikit_event_meta->start_date); ?></startDate>
                <endDate><?php echo ikit_date_without_time($ikit_event_meta->end_date); ?></endDate>
                <startTime><?php echo $ikit_event_meta->start_time; ?></startTime>
                <endTime><?php echo $ikit_event_meta->end_time; ?></endTime>
                <imageUrl><![CDATA[<?php echo $event_image_url; ?>]]></imageUrl>
                <description><![CDATA[<?php echo $ikit_event_meta->description ?>]]></description>
                <linkUrl><![CDATA[<?php the_permalink() ?>]]></linkUrl>
                <location><![CDATA[<?php echo $event_location ?>]]></location>
            </event>

            <?php

        }

    endwhile;

    ?>

    </events>
    </result>

    <?php

    die();

}

/**
 * Currently takes no parameters, just returns list of posts
 */
function ikit_api_v1_post_search() {

    global $g_options;
    $imus_chapter = $g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_IMUS_CHAPTER];

    $args = array();
    $args['posts_per_page'] = 20;
    $args['order'] = 'DESC';
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;

    query_posts($args);

    ?>

    <result>
    <site>
        <code><?php echo $imus_chapter; ?></code>
        <title><![CDATA[<?php echo ikit_get_blog_title(); ?>]]></title>
        <linkUrl><![CDATA[<?php echo get_bloginfo('url'); ?>]]></linkUrl>
    </site>
    <posts>

    <?php

    while (have_posts()) : the_post();

        global $id;
        $post = get_post($id);

        $post_image_url = ikit_post_get_image_url($id, 'full', null);

        ?>
        <post>
            <id><?php echo $id ?></id>
            <title><![CDATA[<?php the_title(); ?>]]></title>
            <publishedDate><?php echo mysql2date('Y-m-d H:i:s +0000', get_gmt_from_date($post->post_date), false);?></publishedDate>
            <linkUrl><![CDATA[<?php the_permalink() ?>]]></linkUrl>
            <imageUrl><![CDATA[<?php echo $post_image_url; ?>]]></imageUrl>
            <description><![CDATA[<?php echo get_post_meta($id, IKIT_CUSTOM_FIELD_POST_PREVIEW_DESCRIPTION, true);?>]]></description>
            <author><![CDATA[<?php echo ikit_post_get_author($id, get_the_author()); ?>]]></author>
        </post>

        <?php

    endwhile;

    ?>

    </posts>
    </result>

    <?php

    die();

}

// API Methods
add_action( 'wp_ajax_ikit_api_v1_post_search', 'ikit_api_v1_post_search' );
add_action( 'wp_ajax_nopriv_ikit_api_v1_post_search', 'ikit_api_v1_post_search' );
add_action( 'wp_ajax_ikit_api_v1_ikit_event_search', 'ikit_api_v1_ikit_event_search' );
add_action( 'wp_ajax_nopriv_ikit_api_v1_ikit_event_search', 'ikit_api_v1_ikit_event_search' );

?>