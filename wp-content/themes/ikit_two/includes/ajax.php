<?php


function ikit_two_ajax($action, $data = array()) {

    $data['action'] = $action;
    $ajax_url = IKIT_AJAX_URL;

    // CURL does not have a built-in root cert, so it can't perform requests
    // over https, so instead we just convert to http, because this
    // is performed as a server side method call, it is never
    // exposed and so safe to do so.
    $ajax_url = str_replace('https://', 'http://', $ajax_url);

    // XXX If using theme test drive plugin to test out the theme,
    // we need to append the theme here, as curl runs on the server
    // so does not have the session parameters that allow browsers
    // to view the test driven theme.
    if(function_exists('themedrive_is_enabled')) {
        if(themedrive_is_enabled()) {
            $ajax_url = $ajax_url . '?theme=' . IKIT_TWO_THEME_NAME;
        }
    }

    $curl_request = curl_init();
    curl_setopt ($curl_request, CURLOPT_POST, true);
    curl_setopt($curl_request, CURLOPT_URL, $ajax_url);
    curl_setopt ($curl_request, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl_request);
    curl_close($curl_request);

    return $curl_response;

}

function ikit_two_ajax_render_portfolios() {

    // Get the posts
    $paged = ($_POST['page']) ? $_POST['page'] : 1;

    $args = array();
    $args['posts_per_page'] = IKIT_TWO_PORTFOLIOS_POSTS_PER_PAGE;
    $args['paged'] = $paged;
    $args['post_status'] = 'publish';
    $args['post_type'] = IKIT_POST_TYPE_IKIT_PORTFOLIO;

    query_posts($args);


    global $wp_query;
    $num_pages = ceil($wp_query->found_posts / IKIT_TWO_PORTFOLIOS_POSTS_PER_PAGE);

    while (have_posts()) : the_post();
        global $id;

        $ikit_portfolio_meta = ikit_portfolio_get_meta($id);

        $project = $ikit_portfolio_meta[array_rand($ikit_portfolio_meta, 1)]; // Get a random project for this portfolio

        $project_image = $project->cover_image_url;

        ?>

            <div class="cat-plugin-fluid-grid-item grid-item portfolio-item">
                <div class="grid-item-inner">

                    <?php if($project_image != null) { ?>
                    <a target="_blank" href="<?php echo $project->url; ?>">
                        <div class="portfolio-item-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $project_image; ?>"></div>
                    </a>
                    <?php } ?>

                    <div class="portfolio-item-author"><a target="_blank" href="<?php echo $project->owner_url; ?>"><?php the_title(); ?></a></div>
                    <div class="portfolio-item-project"><a target="_blank" href="<?php echo $project->url; ?>"><?php echo $project->title; ?></a></div>

                </div>
            </div>


        <?php
    endwhile;

    ?>
    <div class="data" page="<?php echo $paged; ?>" num_pages="<?php echo $num_pages; ?>"></div>
    <?php

    wp_reset_query();

    die();

}

function ikit_two_ajax_render_jobs() {

    // Get the posts
    $paged = ($_POST['page']) ? $_POST['page'] : 1;
    $post_count = 0;

    // Filter
    $expertise_area = $_POST['expertise_area'];
    $job_level = $_POST['job_level'];

    $args = array();
    $args['posts_per_page'] = IKIT_TWO_JOBS_POSTS_PER_PAGE;
    $args['paged'] = $paged;
    $args['post_status'] = 'publish';

    $args['post_type'] = IKIT_POST_TYPE_IKIT_JOB;

    $meta_query = array();
    if(empty($expertise_area) == false) {
        array_push($meta_query,
            array(
            'key' => IKIT_CUSTOM_FIELD_IKIT_JOB_EXPERTISE_AREA,
            'value' => $expertise_area, 'compare' => '=',
            'type' => 'CHAR')
        );
    }

    if(empty($job_level) == false) {
        array_push($meta_query,
            array(
            'key' => IKIT_CUSTOM_FIELD_IKIT_JOB_LEVEL,
            'value' => $job_level, 'compare' => '=',
            'type' => 'CHAR')
        );
    }

    $args['meta_query'] = $meta_query;

    query_posts($args);

    global $wp_query;
    $num_pages = ceil($wp_query->found_posts / IKIT_TWO_JOBS_POSTS_PER_PAGE);

    while (have_posts()) : the_post();
        global $id;

        $ikit_job_meta = ikit_job_get_meta($id);
        ?>

        <div class="cat-plugin-fluid-grid-item grid-item job-item">
            <div class="grid-item-inner">
                <div class="job-item-date"><?php echo mysql2date('F j, Y', get_gmt_from_date($ikit_job_meta->date_approved), false);?></div>
                <div class="job-item-location"><?php echo $ikit_job_meta->city; ?>, <?php echo $ikit_job_meta->state; ?></div>

                <a class="link-block" href="<?php echo ikit_sso_get_login_url(get_permalink()); ?>">
                    <span class="job-item-title"><?php the_title(); ?></span>
                    <span class="job-item-company-name"><?php echo $ikit_job_meta->company_name; ?></span>
                </a>
            </div>
        </div>

        <?php
        $post_count++;

    endwhile;

    ?>
    <div class="data" page="<?php echo $paged; ?>" num_pages="<?php echo $num_pages; ?>"></div>
    <?php

    wp_reset_query();

    die();

}

function ikit_two_ajax_render_events() {

    // Get the posts
    $paged = ($_POST['page']) ? $_POST['page'] : 1;

    // Filter
    $date = $_POST['date'];
    $type = $_POST['type'];
    $category_slug = $_POST['category'];

    $args = array();
    $args['posts_per_page'] = IKIT_TWO_EVENTS_POSTS_PER_PAGE;
    $args['paged'] = $paged;
    $args['post_status'] = 'publish';

    if($category_slug != null) {
        $category = get_category_by_slug($category_slug);
        $args['cat'] = $category->cat_ID;
    }

    if($type == IKIT_TWO_EVENTS_TYPE_AIGA) {
        $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL);
    }
    else if($type == IKIT_TWO_EVENTS_TYPE_COMMUNITY) {
        $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT_INTERNAL);
    }
    else {
        $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL, IKIT_POST_TYPE_IKIT_EVENT_INTERNAL);
    }

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

    global $wp_query;
    $num_pages = ceil($wp_query->found_posts / IKIT_TWO_EVENTS_POSTS_PER_PAGE);
    $post_count = 0;

    while (have_posts()) : the_post();

        global $id;
        global $post;

        $ikit_event_meta = ikit_event_get_meta($id);
        $event = ikit_event_get_meta_normalized($id, $ikit_event_meta, null);
        $event_location_city = $event['location_city'];
        $event_start_date = $event['start_date'];
        $event_end_date = $event['end_date'];
        $event_image = $event['image'];
        $event_url = $event['permalink'];
        $event_url_target = $event['permalink_target'];
        $external = false;
        $internal = false;
        $event_preview_description = get_post_meta($id, IKIT_CUSTOM_FIELD_IKIT_EVENT_PREVIEW_DESCRIPTION, true);

        if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) {
            $external = true;
        }
        else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_INTERNAL) {
            $internal = true;
        }

        ?>

        <div class="event-item">

            <table>
            <tr>
            <?php if($event_image) { ?>
            <td class="event-item-image-col">
                <a target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>">
                    <div class="event-item-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $event_image; ?>"></div>
                </a>
            </td>
            <?php } ?>
            <td class="event-item-info-col">

                <div class="event-item-title"><a target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><?php the_title(); ?></a></div>

                <?php if(empty($event_preview_description) == false) { ?>
                    <div class="event-item-description"><?php echo $event_preview_description;?></div>
                <?php } ?>

                <?php ikit_two_render_event_item_attributes($post, $external, $internal, $event_start_date, $event_end_date, $event_location_city); ?>

            </td>
            </tr>
            </table>

        </div>

        <?php

        $post_count++;

    endwhile;

    ?>
    <div class="data" page="<?php echo $paged; ?>" num_pages="<?php echo $num_pages; ?>"></div>
    <?php

    wp_reset_query();

    die();

}

function ikit_two_ajax_render_news() {

    // Get the posts
    $paged = ($_POST['page']) ? $_POST['page'] : 1;

    $args = array();
    $args['posts_per_page'] = IKIT_TWO_NEWS_POSTS_PER_PAGE;
    $args['paged'] = $paged;
    $args['order'] = 'DESC';
    $args['post_status'] = 'publish';
    $args['post_type'] = array('post', IKIT_POST_TYPE_IKIT_POST_EXTERNAL);
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;

    // Filter
    $category_slug = $_POST['category'];

    if($category_slug != null) {
        $category = get_category_by_slug($category_slug);
        $args['cat'] = $category->cat_ID;
    }

    // Determine if this is an initial rendering
    $initial = $paged < 2 ? true : false;

    $disable_featured = $_POST['disable_featured'];

    query_posts($args);
    global $wp_query;
    $num_pages = ceil($wp_query->found_posts / IKIT_TWO_NEWS_POSTS_PER_PAGE);
    $post_count = 0;

    while (have_posts()) : the_post();

        global $id;
        global $post;
        $news_image = ikit_post_get_image_url($id, 'full', null);
        $news_url = get_permalink($id);
        $news_url_target = '_self';

        $external = false;
        if($post->post_type == IKIT_POST_TYPE_IKIT_POST_EXTERNAL) {
            $external = true;
            $news_url = get_post_meta($id, IKIT_CUSTOM_FIELD_IKIT_POST_EXTERNAL_LINK_URL, true);
            $news_url_target = '_blank';
        }

        $news_preview_description = get_post_meta($id, IKIT_CUSTOM_FIELD_POST_PREVIEW_DESCRIPTION, true);

        $featured = false;
        if($initial) {
            $featured = true;
            $initial = false;
        }
        if($disable_featured) {
            $featured = false;
        }

        ?>

        <div class="cat-plugin-fluid-grid-item grid-item news-item <?php if($featured) { echo 'news-item-featured'; } ?>" <?php if($featured) { echo 'cat_plugin_fluid_grid_item_size="999"'; } ?>>
            <div class="grid-item-inner">

                <?php if($news_image) { ?>
                    <a target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>">
                        <div class="news-item-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $news_image; ?>"></div>
                    </a>
                <?php } ?>

                <div class="news-item-title"><a target="<?php echo $news_url_target; ?>" href="<?php echo $news_url; ?>"><?php the_title(); ?></a></div>

                <div class="news-item-author">
                    <span>by</span> <?php echo ikit_post_get_author($id, get_the_author()); ?>
                </div>

                <?php if(empty($news_preview_description) == false) { ?>
                    <div class="news-item-description"><?php echo $news_preview_description; ?></div>
                <?php } ?>

                <?php ikit_two_render_news_item_attributes($post, $external, true); ?>

            </div>

        </div>

        <?php

        $post_count++;

    endwhile;

    ?>
    <div class="data" page="<?php echo $paged; ?>" num_pages="<?php echo $num_pages; ?>"></div>
    <?php

    wp_reset_query();

    die();

}

function ikit_two_ajax_render_member_directory() {

//  Get the posts
    $paged = ($_POST['page']) ? $_POST['page'] : 1;

    // Filter
    $name_order = $_POST['name_order'];
    $company_order = $_POST['company_order'];
    $member_type = $_POST['member_type'];

    global $wpdb;
    $member_table_name = $wpdb->prefix . IKIT_MEMBER_TABLE_NAME;

    $where_clause = "where full_name != '' and is_member = 1 ";

    if(empty($member_type) == false) {
        $where_clause = $where_clause . ' and member_type = "' . $member_type . '" ';
    }

    $order_clause = 'order by last_name, first_name';
    if(empty($name_order) == false) {
        $order_clause = 'order by last_name ' . $name_order . ', first_name ' . $name_order;
    }
    if(empty($company_order) == false) {
        if($company_order == 'asc') {
            // Special case, place the empty companies at the bottom as they are not relevant
            $order_clause = "order by case company when '' then 'ZZZZZZZZ' else company end " . $company_order;
        }
        else {
          $order_clause = 'order by company ' . $company_order;
        }
    }

    // Offset is based on the page number
    $offset = 0;
    if($paged > 1) {
        $offset = ($paged-1) * IKIT_TWO_MEMBER_DIRECTORY_POSTS_PER_PAGE;
    }

    // We use a limit clause to handle paging
    $limit_clause = ' limit ' . $offset . ',' . IKIT_TWO_MEMBER_DIRECTORY_POSTS_PER_PAGE;

    $members = $wpdb->get_results("SELECT * from $member_table_name " . $where_clause . $order_clause . $limit_clause);
    $member_total = $wpdb->get_results("SELECT count(*) as count from $member_table_name " . $where_clause . $order_clause);

    $count = $member_total[0]->count;
    $num_pages = ceil($count / IKIT_TWO_MEMBER_DIRECTORY_POSTS_PER_PAGE);

    ?>

    <?php foreach($members as $member) { ?>

        <div class="cat-plugin-fluid-grid-item grid-item member-item">
            <div class="grid-item-inner">
                <?php
                if(empty($member->avatar) == false) {
                    ?>
                    <a target="_blank" href="<?php echo ikit_member_profile_url($member); ?>"><div class="member-item-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo str_replace('~/', 'http://my.aiga.org/', $member->avatar); ?>"></div></a>
                    <?php
                }
                 ?>
                <div class="member-item-name"><a target="_blank" href="<?php echo ikit_member_profile_url($member); ?>"><?php echo $member->full_name; ?></a></div>
                <?php
                if(isset($member->company)) {
                    ?>
                    <div class="member-item-company"><?php echo strip_tags($member->company); ?></div>
                    <?php
                }
                ?>
                <div class="member-item-attributes">

                    <?php
                    $member_type_display_name = null;
                    $member_type = $member->member_type;
                    $member_type_display_name = ikit_member_type_display_name($member_type);
                    if($member_type_display_name != null && $member_type != 'CONTR' && $member_type != 'SUP') {
                        ?>
                        <span class="member-item-type"><?php echo $member_type_display_name; ?><span> &middot; </span></span>
                        <?php
                    }

                    ?>

                    <span class="member-item-year"><?php echo ikit_member_join_year($member); ?></span>

                </div>
            </div>
        </div>


    <?php } ?>

    <div class="data" page="<?php echo $paged; ?>" num_pages="<?php echo $num_pages; ?>"></div>

    <?php

    die();

}

function ikit_two_ajax_render_events_calendar() {

    // Get the given month and render a calendar view for it with any
    // events on those dates rendered as calendar items
    // http://www.tiposaurus.co.uk/php/basics/generate-a-month-calendar/

    // Filter
    $type = $_POST['type'];
    $category_slug = $_POST['category'];

    // Display a specific month and year
    $month = $_POST['month'];
    $year = $_POST['year'];

    // Use defaults if not specified
    if($month == null) {
        $month = date("n");
    }
    if($year == null) {
        $year = date("Y");
    }

    // Query events to appear within the calendar
    $args = array();
    $args['posts_per_page'] = 999;
    $args['post_status'] = 'publish';

    if($category_slug != null) {
        $category = get_category_by_slug($category_slug);
        $args['cat'] = $category->cat_ID;
    }

    if($type == IKIT_TWO_EVENTS_TYPE_AIGA) {
        $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL);
    }
    else if($type == IKIT_TWO_EVENTS_TYPE_COMMUNITY) {
        $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT_INTERNAL);
    }
    else {
        $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL, IKIT_POST_TYPE_IKIT_EVENT_INTERNAL);
    }

    $start_of_month = new DateTime($year . '-' . $month . '-01');
    $end_of_month = new DateTime($year . '-' . $month . '-' . cal_days_in_month(CAL_GREGORIAN, $month, $year));

    // Filter by the month start and end
    $args['meta_query'] = array(

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE, 'value' => date_i18n("Y-m-d"),
                        'compare' => '>=',
                        'type' => 'DATE'),

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, 'value' => date_i18n("Y-m-d", $start_of_month->getTimestamp()),
                        'compare' => '>=',
                        'type' => 'DATE'),

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_START_DATE, 'value' => date_i18n("Y-m-d", $end_of_month->getTimestamp()),
                        'compare' => '<=',
                        'type' => 'DATE'),

        array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, 'value' => 'Closed',
                        'compare' => '!=',
                        'type' => 'CHAR')

    );

    // Place posts in buckets by day
    $events_by_date = array();
    $posts = get_posts($args);

    foreach($posts as $post) {

        $date_format = "Y-n-j";

        $ikit_event_meta = ikit_event_get_meta($post->ID);

        $start_date = gmdate($date_format, strtotime($ikit_event_meta->start_date));
        $end_date = gmdate($date_format, strtotime($ikit_event_meta->end_date));

        $iterating_date = $start_date;
        while(strtotime($iterating_date) <= strtotime($end_date)) {

            // Add a day to the iterating date
            if(array_key_exists($iterating_date, $events_by_date) == false) {
                $events_by_date[$iterating_date] = array();
            }
            array_push($events_by_date[$iterating_date], array($post, $ikit_event_meta));
            $iterating_date = gmdate($date_format, strtotime("+1 day", strtotime($iterating_date)));

        }

    }

    $date = mktime(12, 0, 0, $month, 1, $year);
    $days_in_month = date("t", $date);
    $offset = date("w", $date); // calculate the position of the first day in the calendar (sunday = 1st column, etc)
    $rows = 1;

    ?>

    <table>
    <tr>
        <th>S</th>
        <th>M</th>
        <th>T</th>
        <th>W</th>
        <th>T</th>
        <th>F</th>
        <th>S</th>
       </tr>
    <tr class='events-calendar-week'>

    <?php

    for($i = 1; $i <= $offset; $i++) {
        ikit_two_render_events_calendar_day();
    }
    for($day = 1; $day <= $days_in_month; $day++) {
        if( ($day + $offset - 1) % 7 == 0 && $day != 1) {
            echo "</tr><tr class='events-calendar-week'>";
            $rows++;
        }
        $check_date = $year . '-' . $month . '-' . $day;
        $current_date = date('Y-n-j');
        ikit_two_render_events_calendar_day($post, $ikit_event_meta, $day, $key, $events_by_date, $current_date, $check_date);

    }
    while( ($day + $offset) <= $rows * 7) {
        ikit_two_render_events_calendar_day();
        $day++;
    }

    ?>

    </tr>
    </table>

    <div class="data" name="<?php echo date("F Y", $date); ?>" year="<?php echo date("Y", $date); ?>" month="<?php echo date("n", $date); ?>"></div>

    <?php
    die();

}

function ikit_two_ajax_render_events_past() {

    $rpp = IKIT_TWO_EVENTS_PAST_POSTS_PER_PAGE;

    // Get the posts
    $paged = ($_POST['page']) ? $_POST['page'] : 1;
    $post_count = 0;

    // Filter
    $year = $_POST['y'];
    $category_slug = $_POST['category'];

    $args = array();
    $args['posts_per_page'] = $rpp;
    $args['paged'] = $paged;
    $args['post_status'] = 'publish';
    $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL);
    $args['orderby'] = 'meta_value';
    $args['meta_key'] = IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE;

    if($category_slug != null) {
        $category = get_category_by_slug($category_slug);
        $args['cat'] = $category->cat_ID;
    }

    $meta_query = array();

    array_push($meta_query,
        array(
        'key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE,
        'value' => date_i18n("Y-m-d"), 'compare' => '<',
        'type' => 'DATE')
    );

    if(empty($year) == false) {

        array_push($meta_query,
            array(
            'key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE,
            'value' => date_i18n("Y-m-d", mktime(0, 0, 0, 1, 1, $year)), 'compare' => '>',
            'type' => 'DATE')
        );

        array_push($meta_query,
            array(
            'key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE,
            'value' => date_i18n("Y-m-d", mktime(0, 0, 0, 12, 31, $year)), 'compare' => '<',
            'type' => 'DATE')
        );

    }

    $args['meta_query'] = $meta_query;

    query_posts($args);
    global $wp_query;
    $num_pages = ceil($wp_query->found_posts / $rpp);

    while (have_posts()) : the_post();
        global $id;
        global $post;

        $ikit_event_meta = ikit_event_get_meta($id);
        $event = ikit_event_get_meta_normalized($id, $ikit_event_meta, null);
        $event_location_city = $event['location_city'];
        $event_start_date = $event['start_date'];
        $event_end_date = $event['end_date'];
        $event_image = $event['image'];
        $event_url = $event['permalink'];
        $event_url_target = $event['permalink_target'];
        $external = false;
        $internal = false;
        $event_preview_description = get_post_meta($id, IKIT_CUSTOM_FIELD_IKIT_EVENT_PREVIEW_DESCRIPTION, true);

        if($ikit_event_meta->service == IKIT_EVENT_SERVICE_EXTERNAL) {
            $external = true;
        }
        else if($ikit_event_meta->service == IKIT_EVENT_SERVICE_INTERNAL) {
            $internal = true;
        }

        ?>

        <div class="cat-plugin-fluid-grid-item grid-item event-item">
            <div class="grid-item-inner">

                <?php if($event_image != null) { ?>
                    <a target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>">
                        <div class="event-item-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $event_image; ?>"></div>
                    </a>
                <?php } ?>

                <div class="event-item-title"><a target="<?php echo $event_url_target; ?>" href="<?php echo $event_url; ?>"><?php the_title(); ?></a></div>

                <?php if(empty($event_preview_description) == false) { ?>
                    <div class="event-item-description"><?php echo $event_preview_description; ?></div>
                <?php } ?>

                <?php ikit_two_render_event_item_attributes($post, $external, $internal, $event_start_date, $event_end_date, $event_location_city); ?>

            </div>

        </div>

        <?php

        $post_count++;

    endwhile;

    ?>
    <div class="data" page="<?php echo $paged; ?>" num_pages="<?php echo $num_pages; ?>"></div>
    <?php

    wp_reset_query();

    die();

}

function ikit_two_ajax_get_event_attendees() {

    $event_id = $_POST['event_id'];
    $event_service = $_POST['event_service'];

    $attendees = ikit_event_get_attendees($event_id, $event_service);
    if($attendees == null) {
        ikit_render_500_error();
    }
    else {
        print json_encode($attendees);
    }

    die();
}

// API Methods



add_action('wp_ajax_ikit_two_ajax_render_portfolios', 'ikit_two_ajax_render_portfolios' );
add_action('wp_ajax_nopriv_ikit_two_ajax_render_portfolios', 'ikit_two_ajax_render_portfolios' );

add_action('wp_ajax_ikit_two_ajax_render_events_past', 'ikit_two_ajax_render_events_past' );
add_action('wp_ajax_nopriv_ikit_two_ajax_render_events_past', 'ikit_two_ajax_render_events_past' );

add_action('wp_ajax_ikit_two_ajax_get_event_attendees', 'ikit_two_ajax_get_event_attendees' );
add_action('wp_ajax_nopriv_ikit_two_ajax_get_event_attendees', 'ikit_two_ajax_get_event_attendees' );

add_action( 'wp_ajax_ikit_two_ajax_render_news', 'ikit_two_ajax_render_news' );
add_action( 'wp_ajax_nopriv_ikit_two_ajax_render_news', 'ikit_two_ajax_render_news' );

add_action( 'wp_ajax_ikit_two_ajax_render_events', 'ikit_two_ajax_render_events' );
add_action( 'wp_ajax_nopriv_ikit_two_ajax_render_events', 'ikit_two_ajax_render_events' );

add_action( 'wp_ajax_ikit_two_ajax_render_events_calendar', 'ikit_two_ajax_render_events_calendar' );
add_action( 'wp_ajax_nopriv_ikit_two_ajax_render_events_calendar', 'ikit_two_ajax_render_events_calendar' );

add_action( 'wp_ajax_ikit_two_ajax_render_jobs', 'ikit_two_ajax_render_jobs' );
add_action( 'wp_ajax_nopriv_ikit_two_ajax_render_jobs', 'ikit_two_ajax_render_jobs' );

add_action( 'wp_ajax_ikit_two_ajax_render_member_directory', 'ikit_two_ajax_render_member_directory' );
add_action( 'wp_ajax_nopriv_ikit_two_ajax_render_member_directory', 'ikit_two_ajax_render_member_directory' );




?>