<?php

/**
 * Settings
 */

add_action('admin_menu', 'ikit_menu_settings_menu_add', 8);

function ikit_menu_settings_menu_add() {
    add_submenu_page( 'ikit-menu', 'Settings', 'Settings', 'manage_options', 'ikit-menu', 'ikit_menu_general_settings_render');
    register_setting( 'ikit_settings', 'ikit_general_settings', 'ikit_menu_settings_validate');
}

add_action('admin_menu', 'ikit_menu_settings_show_errors', 8);

function ikit_menu_settings_show_errors() {

    global $g_admin_notices;
    global $g_options;

    // Show an error if the instagram username is set, but the ID was never set, meaning the API call failed,
    // which will result in no images being fetched
    if(empty($g_options[IKIT_PLUGIN_OPTION_INSTAGRAM_USERNAME]) == false && empty($g_options[IKIT_PLUGIN_OPTION_INSTAGRAM_USER_ID])) {
        array_push($g_admin_notices, '<div class="error">There seems to be an error with your Instagram Username due the a failed connection to the Instagram servers. You should try clearing the field, saving, then reentering your username again and saving, until this message no longer appears.</div>');
    }
}

// Draw the menu page itself
function ikit_menu_general_settings_render() {
    ?>
    <div class="wrap">
        <div id="icon-options-general" class="icon32"></div><h2>Settings</h2>

        <form method="post" action="options.php">
            <?php settings_fields('ikit_settings'); ?>
            <?php $options = get_option('ikit_general_settings'); ?>

            <div class="wp-box">
                <div class="inner">

                    <h2>Chapter Filters</h2>

                    <table class="widefat">

                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'etouches Folder ID', 'Four digit number, E.g. 7770', IKIT_PLUGIN_OPTION_FILTER_IKIT_EVENT_FOLDER_ID); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Jobs State', 'Comma separated for multiple states from the <a target="_blank" href="http://ikit.aiga.org/ikit_national_feed/ikit-national-feed-ikit-job-states/">states list</a>. Do not use spaces between commas. E.g. New York,California,Texas.', IKIT_PLUGIN_OPTION_FILTER_IKIT_JOB_STATE); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Jobs City', 'Comma separated for multiple cities from the <a target="_blank" href="http://ikit.aiga.org/ikit_national_feed/ikit-national-feed-ikit-job-cities/">cities list</a>. Do not use spaces between commas. E.g. Austin,Houston,San Antonio. If cities are added, ONLY jobs in those cities will be included.', IKIT_PLUGIN_OPTION_FILTER_IKIT_JOB_CITY); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Chapter Code (do not change)', 'E.g. TX', IKIT_PLUGIN_OPTION_FILTER_IKIT_IMUS_CHAPTER); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Eventbrite API User Key', 'Please enter your Eventbrite API User key which is accessible via <a target="_blank" href="https://www.eventbrite.com/userkeyapi">https://www.eventbrite.com/userkeyapi</a>. Please make sure to use the key associated with the Eventbrite user that will host the events for your chapter. E.g. 131627677722194779129.', IKIT_PLUGIN_OPTION_FILTER_IKIT_EVENTBRITE_API_USER_KEY); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'External Chapter URLs', 'Comma separated list of external chapter URLs to pull Events and News, E.g. http://austin.aiga.org, http://houston.aiga.org', IKIT_PLUGIN_OPTION_FILTER_IKIT_EXTERNAL_IKIT_URLS); ?>
                    </table>

                </div>
            </div>

            <div class="wp-box">
                <div class="inner">

                    <h2>Mailing List</h2>

                    <table class="widefat">

                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'MailChimp Signup URL', null, IKIT_PLUGIN_OPTION_MAILCHIMP_SIGNUP_FORM_URL); ?>

                    </table>

                </div>
            </div>

            <div class="wp-box">
                <div class="inner">

                    <h2>Social</h2>

                    <table class="widefat">

                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Twitter Username', null, IKIT_PLUGIN_OPTION_TWITTER_USERNAME); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Flickr User ID', 'Appears in the Flickr URL. For example http://www.flickr.com/photos/34631821@N03, the user ID is 34631821@N03', IKIT_PLUGIN_OPTION_FLICKR_USER_ID); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Vimeo User ID', null, IKIT_PLUGIN_OPTION_VIMEO_USER_ID); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Facebook ID', null, IKIT_PLUGIN_OPTION_FACEBOOK_ID); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'LinkedIn Group ID', 'Appears in your group URL. For example for the URL http://www.linkedin.com/groups/?gid=23100, the group ID is 23100', IKIT_PLUGIN_OPTION_LINKEDIN_GROUP_ID); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Pinterest Username', 'Appears in your Pinterest URL. For example for the URL http://pinterest.com/aigachapterx the username is aigachapterx', IKIT_PLUGIN_OPTION_PINTEREST_USERNAME); ?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'YouTube Username', null, IKIT_PLUGIN_OPTION_YOUTUBE_USERNAME);?>
                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Instagram Username', null, IKIT_PLUGIN_OPTION_INSTAGRAM_USERNAME);?>

                    </table>

                </div>
            </div>

            <div class="wp-box">
                <div class="inner">

                    <h2>Misc</h2>

                    <table class="widefat">

                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Embed Codes', 'Enter script embeds such as Google Analytics here.', IKIT_PLUGIN_OPTION_EMBED_CODES, 'textarea'); ?>

                    </table>

                </div>
            </div>

            <div class="wp-box">
                <div class="inner">

                    <h2>Locking</h2>

                    <table class="widefat">

                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Disable Locking', 'To allow editing of locked pages, not recommended.', IKIT_PLUGIN_OPTION_LOCKING_DISABLED, 'checkbox'); ?>

                    </table>

                </div>
            </div>

            <div class="wp-box">
                <div class="inner">

                    <h2>Eventbrite</h2>

                    <table class="widefat">

                    <?php ikit_render_settings_input('ikit_general_settings', $options, 'Disable Sync Validation', 'To allow automated tickets to be created even if the event has pre-existing tickets or is in the past, not recommended.', IKIT_PLUGIN_OPTION_EVENTBRITE_SYNC_VALIDATION_DISABLED, 'checkbox'); ?>

                    </table>

                </div>
            </div>


            <div class="wp-box">
                <div class="inner">

                    <h2>Dashboard Widgets</h2>

                    <table class="widefat">

                        <?php ikit_render_settings_input('ikit_general_settings', $options, 'Chapter Resources', 'Content to display in the chapter resources widget', IKIT_PLUGIN_OPTION_DASHBOARD_WIDGET_CHAPTER_RESOURCES_CONTENT, 'wp_editor'); ?>

                    </table>

                </div>
            </div>

            <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>

        </form>



    </div>
    <?php
}

function ikit_menu_settings_validate($input) {

    $options = get_option('ikit_general_settings');

    // Handle instagram user, we need the user id to user the API
    if(empty($input[IKIT_PLUGIN_OPTION_INSTAGRAM_USERNAME]) == false) {

        $curl_list_request = curl_init();
        curl_setopt($curl_list_request, CURLOPT_URL, IKIT_SOCIAL_INSTAGRAM_API_URL . "/users/search?q=" . $input[IKIT_PLUGIN_OPTION_INSTAGRAM_USERNAME] . '&access_token=' . IKIT_SOCIAL_INSTAGRAM_ACCESS_TOKEN);
        curl_setopt($curl_list_request, CURLOPT_RETURNTRANSFER, true);
        $curl_list_response = curl_exec($curl_list_request);
        curl_close($curl_list_request);

        $user = json_decode($curl_list_response);
        $user_id = $user->data[0]->id;

        $input[IKIT_PLUGIN_OPTION_INSTAGRAM_USER_ID] = $user_id;

    }

    // Handle checkboxes
    $input[IKIT_PLUGIN_OPTION_LOCKING_DISABLED] = ( $input[IKIT_PLUGIN_OPTION_LOCKING_DISABLED] == 1 ? 1 : 0 );
    $input[IKIT_PLUGIN_OPTION_DASHBOARD_WIDGET_CHAPTER_RESOURCES_CONTENT] = ($input[IKIT_PLUGIN_OPTION_DASHBOARD_WIDGET_CHAPTER_RESOURCES_CONTENT]);
    return $input;

}

?>