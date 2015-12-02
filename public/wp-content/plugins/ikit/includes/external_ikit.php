<?php

/**
 * Refresh the post and events from the external chapters
 */
function ikit_external_ikit_remote_fetch() {

    // Get filter
    global $g_options;
    $external_chapter_urls = $g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_EXTERNAL_IKIT_URLS];

    $external_chapter_urls_split = explode(',', $external_chapter_urls);
    foreach($external_chapter_urls_split as $external_chapter_url) {

        $external_chapter_url = trim($external_chapter_url);

        {

            $data = array (
                "action" => "ikit_api_v1_ikit_event_search",
            );

            $curl_list_request = curl_init();
            curl_setopt ($curl_list_request, CURLOPT_POST, true);
            curl_setopt($curl_list_request, CURLOPT_URL, $external_chapter_url . "/wp-admin/admin-ajax.php");
            curl_setopt ($curl_list_request, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl_list_request, CURLOPT_RETURNTRANSFER, true);
            $curl_list_response = curl_exec($curl_list_request);
            curl_close($curl_list_request);

            $result = simplexml_load_string($curl_list_response);

            $chapter_code = strtolower($result->site->code);
            $chapter_display_name = trim((string)$result->site->title);
            $chapter_link_url = trim((string)$result->site->linkUrl);
            $external_source = sprintf(IKIT_EVENT_EXTERNAL_SOURCE_FORMAT, $chapter_code);

            $sanitized_items = array();

            foreach($result->events->event as $item) {

                $post_id = sprintf(IKIT_EVENT_EXTERNAL_ID_FORMAT, $chapter_code, $item->id); // Generate an ID

                $sanitized_item = new stdClass();
                $sanitized_item->title = trim((string)$item->title);
                $sanitized_item->link_url = (string)$item->linkUrl;
                $sanitized_item->image_url = (string)$item->imageUrl;
                $sanitized_item->description = (string)$item->description;
                $sanitized_item->start_date = (string)$item->startDate;
                $sanitized_item->end_date = (string)$item->endDate;
                $sanitized_item->start_time = (string)$item->startTime;
                $sanitized_item->end_time = (string)$item->endTime;
                $sanitized_item->location = trim((string)$item->location);
                $sanitized_item->post_id = $post_id;

                array_push($sanitized_items, $sanitized_item);

            }

            ikit_event_external_remote_fetch_save($external_source, $chapter_display_name, $chapter_link_url, $sanitized_items);

        }

        // Posts
        {

            $data = array (
                "action" => "ikit_api_v1_post_search",
            );

            $curl_list_request = curl_init();
            curl_setopt ($curl_list_request, CURLOPT_POST, true);
            curl_setopt($curl_list_request, CURLOPT_URL, $external_chapter_url . "/wp-admin/admin-ajax.php");
            curl_setopt ($curl_list_request, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl_list_request, CURLOPT_RETURNTRANSFER, true);
            $curl_list_response = curl_exec($curl_list_request);
            curl_close($curl_list_request);

            $result = simplexml_load_string($curl_list_response);
            $chapter_code = strtolower($result->site->code);
            $chapter_display_name = trim((string)$result->site->title);
            $chapter_link_url = trim((string)$result->site->linkUrl);
            $external_source = sprintf(IKIT_POST_EXTERNAL_SOURCE_FORMAT, $chapter_code);

            $sanitized_items = array();

            foreach($result->posts->post as $item) {

                // Convert GMT to local
                $published_date = (string)$item->publishedDate;
                $published_date_local = get_date_from_gmt($published_date);

                $post_id = sprintf(IKIT_POST_EXTERNAL_ID_FORMAT, $chapter_code, $item->id); // Generate an ID

                $sanitized_item = new stdClass();
                $sanitized_item->title = trim((string)$item->title);
                $sanitized_item->post_date = $published_date_local;
                $sanitized_item->link_url = trim((string)$item->linkUrl);
                $sanitized_item->description = (string)$item->description;
                $sanitized_item->author = trim((string)$item->author);
                $sanitized_item->image_url = trim((string)$item->imageUrl);
                $sanitized_item->post_id = $post_id;

                array_push($sanitized_items, $sanitized_item);

            }

            ikit_post_external_remote_fetch_save($external_source, $chapter_display_name, $chapter_link_url, $sanitized_items);

        }

    }


}

// add_action('wp_loaded', 'ikit_external_ikit_remote_fetch');

?>