<?php

/**
 * Contains update tasks that need to be programatically written and typically
 * use the WordPress codex. These are for tasks that typically can be done via
 * the UI, but would be very time intensive.
 */

add_action( 'wp_loaded', 'ikit_updates' );
function ikit_updates() {


    // 20150608, update the eyeondesign feed
    if(!get_option('ikit_update_eyeondesign_feed_20150608')) {
        update_option('ikit_update_eyeondesign_feed_20150608', 1);
        ikit_social_remote_fetch_eyeondesign_rss();
    }

    // 20150505, change the page template of "events" to use the table of contents
    // this is okay for ikit one and two themes
    if(!get_option('ikit_update_events_template_20150505')) {
        update_option('ikit_update_events_template_20150505', 1);
        $events_page = get_page_by_path('events');
        if($events_page != null) {
            print_r($events_page);
            update_post_meta($events_page->ID, '_wp_page_template', 'section_toc.php');
        }
    }

    // 20141030, update the membership rates page template
    if(!get_option('ikit_update_membershiprates_template_20141030')) {
        update_option('ikit_update_membershiprates_template_20141030', 1);

        $membership_rates_page = get_page_by_path(IKIT_SLUG_PAGE_MEMBERSHIP . '/' . IKIT_SLUG_PAGE_MEMBERSHIP_RATES);
        if($membership_rates_page != null) {
            update_post_meta($membership_rates_page->ID, '_wp_page_template', 'membership_rates.php');
        }

    }

    // 20120809, update the menu items
    if(!get_option('ikit_update_menu_items_20120809')) {
        update_option('ikit_update_menu_items_20120809', 1);

        $nav_menu_locations = get_nav_menu_locations();
        $nav_menu = wp_get_nav_menu_object( $nav_menu_locations[ 'nav-menu-main' ] );
        $nav_menu_items = wp_get_nav_menu_items($nav_menu->term_id);

        foreach((array)$nav_menu_items as $key => $nav_menu_item ) {

            // Correct the link for Living Principles (it has an extra http// in it)
            if($nav_menu_item->title == 'The Living Princples') {

                $post = array();
                $post['ID'] = $nav_menu_item->ID;
                $post['post_title'] = 'The Living Principles';
                wp_update_post($post);

                ikit_add_or_update_post_meta($nav_menu_item->ID, '_menu_item_url', 'http://www.livingprinciples.org/', true);
            }
        }
    }

    // 20120621, add a one time flush rewrite as permalink
    // structure changed for event single and job single.
    if(!get_option('ikit_update_flush_rewrite_rules_20120621')) {
        update_option('ikit_update_flush_rewrite_rules_20120621', 1);
        flush_rewrite_rules();
    }

    // 20120710, update the menu items
    if(!get_option('ikit_update_menu_items_20120710')) {
        update_option('ikit_update_menu_items_20120710', 1);

        $nav_menu_locations = get_nav_menu_locations();
        $nav_menu = wp_get_nav_menu_object( $nav_menu_locations[ 'nav-menu-main' ] );
        $nav_menu_items = wp_get_nav_menu_items($nav_menu->term_id);

        foreach((array)$nav_menu_items as $key => $nav_menu_item ) {

            // Update events archive to past events
            if($nav_menu_item->title == 'Events Archive') {
                $post = array();
                $post['ID'] = $nav_menu_item->ID;
                $post['post_title'] = 'Past Events';
                wp_update_post($post);
            }

            // Delete Design Forum; it no longer exists.
            if($nav_menu_item->title == 'Design Forum') {
                wp_delete_post($nav_menu_item->ID);
            }

            // Change the link for Design & Business to  http://www.aiga.org/Resources/
            if($nav_menu_item->title == 'Design & Business') {
                ikit_add_or_update_post_meta($nav_menu_item->ID, '_menu_item_url', 'http://www.aiga.org/resources/', true);
            }

            // Change Design Salary Survey to AIGA | Aquent Survey of Design Salaries
            if($nav_menu_item->title == 'Design Salary Survey') {
                $post = array();
                $post['ID'] = $nav_menu_item->ID;
                $post['post_title'] = 'AIGA | Aquent Survey of Design Salaries';
                wp_update_post($post);
            }

            // Correct the link for Living Principles (it has an extra http// in it)
            if($nav_menu_item->title == 'Living Principles') {
                ikit_add_or_update_post_meta($nav_menu_item->ID, '_menu_item_url', 'http://www.livingprinciples.org/', true);
            }


        }

    }

    // 20130128, delete all acf groups, we will use the code instead to register them so they are easily updateable
    if(!get_option('ikit_update_acf_20130128')) {
        update_option('ikit_update_acf_20130128', 1);

        ikit_delete_posts('acf');

    }

    // 20130203, update the slug of the featured image gallery if not set properly
    if(!get_option('ikit_update_image_gallery_featured_20130203')) {
        update_option('ikit_update_image_gallery_featured_20130203', 1);

        $args = array(
            'post_type' => IKIT_POST_TYPE_IKIT_IMAGE_GALLERY
        );
        $posts = get_posts($args);
        foreach($posts as $post) {
            if($post->post_name = 'featured') {
                $post->post_name = IKIT_SLUG_IKIT_IMAGE_GALLERY_FEATURED;
                wp_update_post($post);
                break;
            }
        }

    }

    // 20130206, add member directory page, and then assign to about us menu
    if(!get_option('ikit_update_member_directory_20130206')) {

        global $g_options;

        // Only perform is an imus chapter is set, otherwise
        // we would display to users a blank members page
        $imus_chapter = null;
        if(ikit_isset_not_empty($g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_IMUS_CHAPTER])) {

            update_option('ikit_update_member_directory_20130206', 1);

            $member_directory_page = ikit_get_post_by_slug(IKIT_SLUG_PAGE_MEMBER_DIRECTORY, 'page');
            if($member_directory_page == null) {

                // Create member directory page
                $member_directory_page['post_type'] = 'page';
                $member_directory_page['post_status'] = 'publish';
                $member_directory_page['post_title'] = 'Member Directory';
                $member_directory_page['post_name'] = IKIT_SLUG_PAGE_MEMBER_DIRECTORY;

                // Assign about us as parent
                $membership_page = ikit_get_post_by_slug(IKIT_SLUG_PAGE_MEMBERSHIP, 'page');
                $member_directory_page['post_parent'] = $membership_page->ID;

                $member_directory_page_id = wp_insert_post($member_directory_page);

                if($member_directory_page_id == 0) {
                    // If error, try again next time
                    update_option('ikit_update_member_directory_20130206', 0);
                }
                else {

                    // Assign member directory template
                    update_post_meta($member_directory_page_id, '_wp_page_template', 'member_directory.php');

                    // Add to about us menu item
                    $nav_menu_locations = get_nav_menu_locations();
                    $nav_menu = wp_get_nav_menu_object( $nav_menu_locations[ 'nav-menu-main' ] );
                    $nav_menu_items = wp_get_nav_menu_items($nav_menu->term_id);

                    foreach((array)$nav_menu_items as $key => $nav_menu_item ) {
                        if($nav_menu_item->title == 'Membership') {

                            wp_update_nav_menu_item($nav_menu->term_id, 0, array(
                                'menu-item-title' =>  __('Member Directory'),
                                'menu-item-parent-id' => $nav_menu_item->ID,
                                'menu-item-object' => 'page',
                                'menu-item-object-id' => $member_directory_page_id,
                                'menu-item-type' => 'post_type',
                                'menu-item-status' => 'publish'));

                            break;

                        }
                    }

                    // Force pull of member feed
                    // ikit_national_remote_fetch_member_feed();

                }

            }

        }

    }

    // 20130212, update member directory
    if(!get_option('ikit_update_member_directory_20130212')) {
        update_option('ikit_update_member_directory_20130212', 1);

        // Force pull of member feed
        // ikit_national_remote_fetch_member_feed();

    }

    // 20130213, update member directory post NimbleUser fix
    if(!get_option('ikit_update_member_directory_20130213')) {
        update_option('ikit_update_member_directory_20130213', 1);

        // Force pull of member feed
        // ikit_national_remote_fetch_member_feed();

    }

    // 20130219, update member directory as 999 limit was too small for certain chapters
    if(!get_option('ikit_update_member_directory_20130219')) {
        update_option('ikit_update_member_directory_20130219', 1);

        // Force pull of member feed
        // ikit_national_remote_fetch_member_feed();

    }

    // 20130227, update about description to be 67 chapters
    if(!get_option('ikit_update_about_description_20130227')) {
        update_option('ikit_update_about_description_20130227', 1);

        $nav_menu_locations = get_nav_menu_locations();
        $nav_menu = wp_get_nav_menu_object( $nav_menu_locations[ 'nav-menu-main' ] );
        $nav_menu_items = wp_get_nav_menu_items($nav_menu->term_id);

        foreach((array)$nav_menu_items as $key => $nav_menu_item ) {

            // Update events archive to past events
            if($nav_menu_item->title == 'About Us') {

                $updated_post_content = str_replace('66', '67', $nav_menu_item->post_content);
                $nav_menu_item->post_content = $updated_post_content;
                wp_update_post($nav_menu_item);

                break;
            }
        }
    }

    // 20130408, update member directory as there is now a new database for it
    if(!get_option('ikit_update_member_database_20130408')) {
        update_option('ikit_update_member_database_20130408', 1);

        ikit_member_remote_fetch();

    }

    // 20130521, recover from error with datagram where can't access chapters.aiga.org portfolio feed, so truncated portfolios, this update checks if no portfolios, and if so repopulates
    if(!get_option('ikit_update_portfolios_if_empty_20130521')) {
        update_option('ikit_update_portfolios_if_empty_20130521', 1);

        global $wpdb;
        $project_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_TABLE_NAME;
        $rows_affected = $wpdb->query("select * from $project_table_name");
        if($rows_affected == 0) {

            ikit_portfolio_remote_fetch();

        }

    }

    // 20130624, set the status of published events to 'Live', future pulls will auto-set from etouches
    // note that we know published events are either 'Live' or 'Sold Out', either of which are fine to display
    if(!get_option('ikit_update_event_status_published_to_live_20130624')) {
        update_option('ikit_update_event_status_published_to_live_20130624', 1);

        // Get the sponsors
        $args = array(
            'numberposts'     => 999,
            'offset'          => 0,
            'order'           => 'ASC',
            'post_type'       => IKIT_POST_TYPE_IKIT_EVENT,
            'post_status'     => 'publish');

        $events = get_posts($args);
        foreach($events as $event) {
            ikit_add_or_update_post_meta($event->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, 'Live', true);
        }

    }

    // 20131017, save the post orderings, this is a special meta field which holds
    // a special ordering field that can be used to order events and posts, the
    // new display priority field will appear for admins for events and posts
    if(!get_option('ikit_update_save_post_orderings_20131017')) {
        update_option('ikit_update_save_post_orderings_20131017', 1);

        ikit_save_post_orderings();

    }

    // 20131101, update wp_ikit_event entries with service eventbrite
    if(!get_option('ikit_update_eventdb_setservice_20131101')) {
        update_option('ikit_update_eventdb_setservice_20131101', 1);

        global $wpdb;
        $event_table_name = $wpdb->prefix . IKIT_EVENT_ETOUCHES_TABLE_NAME;
        $wpdb->query("update $event_table_name set service = 'etouches'");

    }

    // 20131101, update event service meta for existing events
    if(!get_option('ikit_update_eventservicemeta_20131101')) {
        update_option('ikit_update_eventservicemeta_20131101', 1);

        $events = get_posts(array('post_type'=>array(IKIT_POST_TYPE_IKIT_EVENT),'posts_per_page'=>9999));
        foreach($events as $event) {
            ikit_add_or_update_post_meta($event->ID, IKIT_CUSTOM_FIELD_IKIT_EVENT_SERVICE, IKIT_EVENT_SERVICE_ETOUCHES, true);
        }

    }

    // 20131105, update the member id to a varchar instead of int, as it has leading zeroes, so refresh the feed
    if(!get_option('ikit_update_updatedmemberid_20131105')) {
        update_option('ikit_update_updatedmemberid_20131105', 1);

        ikit_member_remote_fetch();

    }

    // 20130227, update about description to be 67 chapters
    if(!get_option('ikit_update_about_description_20140712')) {
        update_option('ikit_update_about_description_20140712', 1);

        $nav_menu_locations = get_nav_menu_locations();
        $nav_menu = wp_get_nav_menu_object( $nav_menu_locations[ 'nav-menu-main' ] );
        $nav_menu_items = wp_get_nav_menu_items($nav_menu->term_id);

        foreach((array)$nav_menu_items as $key => $nav_menu_item ) {

            // Update events archive to past events
            if($nav_menu_item->title == 'About Us') {

                $updated_post_content = str_replace('67', '68', $nav_menu_item->post_content);
                $nav_menu_item->post_content = $updated_post_content;
                wp_update_post($nav_menu_item);

                break;
            }
        }
    }

    // 20141119, update about description to be 69 chapters
    if(!get_option('ikit_update_about_description_20141119')) {
        update_option('ikit_update_about_description_20141119', 1);

        $nav_menu_locations = get_nav_menu_locations();
        $nav_menu = wp_get_nav_menu_object( $nav_menu_locations[ 'nav-menu-main' ] );
        $nav_menu_items = wp_get_nav_menu_items($nav_menu->term_id);

        foreach((array)$nav_menu_items as $key => $nav_menu_item ) {

            // Update events archive to past events
            if($nav_menu_item->title == 'About Us') {

                $updated_post_content = str_replace('67', '69', $nav_menu_item->post_content);
                $nav_menu_item->post_content = $updated_post_content;
                wp_update_post($nav_menu_item);

                $updated_post_content = str_replace('68', '69', $nav_menu_item->post_content);
                $nav_menu_item->post_content = $updated_post_content;
                wp_update_post($nav_menu_item);

                break;
            }
        }
    }

}

?>