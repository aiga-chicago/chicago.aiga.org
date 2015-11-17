<?php

/**
 * Render generic social links based on option settings
 */
function ikit_render_social_links($rss_url = null) {

    // Get the configuration for social feeds
    global $g_options;
    $vimeo_user_id = $g_options[IKIT_PLUGIN_OPTION_VIMEO_USER_ID];
    $twitter_username = $g_options[IKIT_PLUGIN_OPTION_TWITTER_USERNAME];
    $flickr_user_id = $g_options[IKIT_PLUGIN_OPTION_FLICKR_USER_ID];
    $facebook_id = $g_options[IKIT_PLUGIN_OPTION_FACEBOOK_ID];
    $pinterest_username = $g_options[IKIT_PLUGIN_OPTION_PINTEREST_USERNAME];
    $linkedin_group_id = $g_options[IKIT_PLUGIN_OPTION_LINKEDIN_GROUP_ID];
    $youtube_username = $g_options[IKIT_PLUGIN_OPTION_YOUTUBE_USERNAME];
    $instagram_username = $g_options[IKIT_PLUGIN_OPTION_INSTAGRAM_USERNAME];

    // Generate the URLs for social feeds
    $twitter_url = 'http://www.twitter.com/' . $twitter_username;
    $facebook_url = 'http://www.facebook.com/' . $facebook_id;
    $flickr_url = 'http://www.flickr.com/' . $flickr_user_id;
    $vimeo_url = 'http://www.vimeo.com/' . $vimeo_user_id;
    $linkedin_url = 'http://www.linkedin.com/groups/?gid=' . $linkedin_group_id;
    $pinterest_url = 'http://www.pinterest.com/' . $pinterest_username;
    $youtube_url = 'http://www.youtube.com/' . $youtube_username;
    $instagram_url = 'http://www.instagram.com/' . $instagram_username;

    // Determine if feeds are enabled
    $twitter_enabled = false;
    $vimeo_enabled = false;
    $facebook_enabled = false;
    $flickr_enabled = false;
    $pinterest_enabled = false;
    $linkedin_enabled = false;
    $youtube_enabled = false;
    $instagram_enabled = false;
    if(empty($twitter_username) == false) {
        $twitter_enabled = true;
    }
    if(empty($facebook_id) == false) {
        $facebook_enabled = true;
    }
    if(empty($flickr_user_id) == false) {
        $flickr_enabled = true;
    }
    if(empty($vimeo_user_id) == false) {
        $vimeo_enabled = true;
    }
    if(empty($linkedin_group_id) == false) {
        $linkedin_enabled = true;
    }
    if(empty($pinterest_username) == false) {
        $pinterest_enabled = true;
    }
    if(empty($youtube_username) == false) {
        $youtube_enabled = true;
    }
    if(empty($instagram_username) == false) {
        $instagram_enabled = true;
    }

    ?>

    <ul>

    <?php if($twitter_enabled == true) { ?>
        <li class="twitter"><a target="_blank" href="<?php echo $twitter_url; ?>"><img src="<?php echo ikit_get_plugin_url('images/transparent_pixel.png'); ?>"/></a></li>
    <?php } ?>

    <?php if($facebook_enabled == true) { ?>
        <li class="facebook"><a target="_blank" href="<?php echo $facebook_url; ?>"><img src="<?php echo ikit_get_plugin_url('images/transparent_pixel.png'); ?>"/></a></li>
    <?php } ?>

    <?php if($instagram_enabled == true) { ?>
        <li class="instagram"><a target="_blank" href="<?php echo $instagram_url; ?>"><img src="<?php echo ikit_get_plugin_url('images/transparent_pixel.png'); ?>"/></a></li>
    <?php } ?>

    <?php if($flickr_enabled == true) { ?>
        <li class="flickr"><a target="_blank" href="<?php echo $flickr_url; ?>"><img src="<?php echo ikit_get_plugin_url('images/transparent_pixel.png'); ?>"/></a></li>
    <?php } ?>

    <?php if($linkedin_enabled == true) { ?>
        <li class="linkedin"><a target="_blank" href="<?php echo $linkedin_url; ?>"><img src="<?php echo ikit_get_plugin_url('images/transparent_pixel.png'); ?>"/></a></li>
    <?php } ?>

    <?php if($vimeo_enabled == true) { ?>
        <li class="vimeo"><a target="_blank" href="<?php echo $vimeo_url; ?>"><img src="<?php echo ikit_get_plugin_url('images/transparent_pixel.png'); ?>"/></a></li>
    <?php } ?>

    <?php if($youtube_enabled == true) { ?>
        <li class="youtube"><a target="_blank" href="<?php echo $youtube_url; ?>"><img src="<?php echo ikit_get_plugin_url('images/transparent_pixel.png'); ?>"/></a></li>
    <?php } ?>

    <?php if($pinterest_enabled == true) { ?>
        <li class="pinterest"><a target="_blank" href="<?php echo $pinterest_url; ?>"><img src="<?php echo ikit_get_plugin_url('images/transparent_pixel.png'); ?>"/></a></li>
    <?php } ?>


    <li class="rss"><a target="_blank" href="<?php echo $rss_url; ?>"><img src="<?php echo ikit_get_plugin_url('images/transparent_pixel.png'); ?>"/></a></li>

    </ul>

    <?php

}

function ikit_render_settings_input($setting_group, $options, $setting_title, $settings_note, $setting_id, $input_type = null) {
    ?>
        <tr>
        <td>
        <div><?php echo $setting_title; ?></div>
        <div class="note"><?php echo $settings_note; ?></div>
        </td>
        <td>
        <?php if($input_type == 'textarea') { ?>
            <textarea rows="5" cols="50" name="<?php echo $setting_group; ?>[<?php echo $setting_id;?>]"><?php if(isset($options[$setting_id])) { echo $options[$setting_id]; } ?></textarea>
        <?php } else if($input_type == 'checkbox') { ?>
            <input type="checkbox" name="<?php echo $setting_group; ?>[<?php echo $setting_id;?>]" value="1" <?php checked('1', $options[$setting_id]); ?>" />
        <?php } else if($input_type == 'wp_editor') { ?>
            <?php wp_editor($options[$setting_id], $setting_group . '[' . $setting_id . ']', array( 'media_buttons' => false, 'teeny' => true, 'tinymce' => true )); ?>
        <?php } else { ?>
            <input type="text" name="<?php echo $setting_group; ?>[<?php echo $setting_id;?>]" value="<?php if(isset($options[$setting_id])) { echo $options[$setting_id]; } ?>" />
        <?php } ?>
        </td>
        </tr>
    <?php
}

function ikit_render_500_error() {
  header("HTTP/1.1 500 Internal Server Error");
}

?>