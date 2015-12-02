<?php

function ikit_one_theme_options_page_render() {

    // Include for success and error messages
    include_once 'options-head.php';

    ?>

    <div class='wrap'>

        <div id="icon-options-general" class="icon32"></div><h2>Theme Options</h2>

        <form method="post" enctype="multipart/form-data" action="options.php">

        <div class="wp-box">

            <?php settings_fields('ikit_one_theme_options'); ?>
            <?php $theme_options = get_option(IKIT_ONE_THEME_OPTION_GROUP_GENERAL); ?>
            <table class="form-table">

                <tr valign="top">
                    <th scope="row">AIGA chapter logo for header</th>
                    <td>
                    <input name="<?php echo IKIT_ONE_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE; ?>" type="file"></input>
                    </td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td>

                    <img src="<?php echo $theme_options[IKIT_ONE_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE]['url']; ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Favicon</th>
                    <td>
                    <input name="<?php echo IKIT_ONE_THEME_OPTION_FAV_ICON; ?>" type="file"></input>
                    </td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td>

                    <img src="<?php echo $theme_options[IKIT_ONE_THEME_OPTION_FAV_ICON]['url']; ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Events per page</th>
                    <td>
                    <input name="<?php echo IKIT_ONE_THEME_OPTION_EVENTS_POSTS_PER_PAGE; ?>" type="number" step="1" min="1" value="<?php echo $theme_options[IKIT_ONE_THEME_OPTION_EVENTS_POSTS_PER_PAGE]; ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Homepage news posts per page</th>
                    <td>
                    <input name="<?php echo IKIT_ONE_THEME_OPTION_HOME_NEWS_POSTS_PER_PAGE; ?>" type="number" step="1" min="1" value="<?php echo $theme_options[IKIT_ONE_THEME_OPTION_HOME_NEWS_POSTS_PER_PAGE]; ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Homepage events per page</th>
                    <td>
                    <input name="<?php echo IKIT_ONE_THEME_OPTION_HOME_EVENTS_POSTS_PER_PAGE; ?>" type="number" step="1" min="1" value="<?php echo $theme_options[IKIT_ONE_THEME_OPTION_HOME_EVENTS_POSTS_PER_PAGE]; ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Enable commenting on events</th>
                    <td>
                    <input <?php checked('1', $theme_options[IKIT_ONE_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT]); ?> name="<?php echo IKIT_ONE_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT; ?>" type="checkbox"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Enable event attendee lists</th>
                    <td>
                    <input <?php checked('1', $theme_options[IKIT_ONE_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED]); ?> name="<?php echo IKIT_ONE_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED; ?>" type="checkbox"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Custom font embed code
                    <div class="note">
                    You can replace the default custom font embed with your own. For example, if you have your own TypeKit account, you would enter the font embed code here.
                    </div>
                    </th>
                    <td>

                    <textarea rows="5" cols="50" name="<?php echo IKIT_ONE_THEME_OPTION_GROUP_GENERAL . '[' . IKIT_ONE_THEME_OPTION_CUSTOM_FONT_EMBED_CODE . ']'; ?>"><?php if(isset($theme_options[IKIT_ONE_THEME_OPTION_CUSTOM_FONT_EMBED_CODE])) { echo $theme_options[IKIT_ONE_THEME_OPTION_CUSTOM_FONT_EMBED_CODE]; } ?></textarea>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"> Homepage events and news ordering</th>
                    <td>
                        <select name="<?php echo IKIT_ONE_THEME_OPTION_HOME_EVENTS_NEWS_ORDER_TOGGLE; ?>">
                            <option value="0" <?php selected('0', $theme_options[IKIT_ONE_THEME_OPTION_HOME_EVENTS_NEWS_ORDER_TOGGLE])?>>Events list appears above news</option>
                            <option value="1" <?php selected('1', $theme_options[IKIT_ONE_THEME_OPTION_HOME_EVENTS_NEWS_ORDER_TOGGLE])?>>News list appears above events</option>
                        </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Disable featured image gallery maximum height cutoff</th>
                    <td>
                    <input <?php checked('1', $theme_options[IKIT_ONE_THEME_OPTION_FEATURED_IMAGE_GALLERY_MAX_HEIGHT_DISABLED]); ?> name="<?php echo IKIT_ONE_THEME_OPTION_FEATURED_IMAGE_GALLERY_MAX_HEIGHT_DISABLED; ?>" type="checkbox"/>
                    </td>
                </tr>

            </table>

        </div>

        <p class="submit">
        <input type="submit" class="button-primary" value='Save Changes' />
        </p>

        </form>

    </div>

    <?php

}

function ikit_one_theme_options_init(){
    register_setting( 'ikit_one_theme_options', IKIT_ONE_THEME_OPTION_GROUP_GENERAL, 'ikit_one_theme_options_validate');
}

add_action('admin_init', 'ikit_one_theme_options_init' );

function ikit_one_theme_options_validate($input) {

    $theme_options = get_option(IKIT_ONE_THEME_OPTION_GROUP_GENERAL);

    if(!$_FILES[IKIT_ONE_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE]['error']) {

        $file = wp_handle_upload($_FILES[IKIT_ONE_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE], array('test_form' => false));
        $input[IKIT_ONE_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE] = $file;

    }
    else {

        $input[IKIT_ONE_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE] = $theme_options[IKIT_ONE_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE];

    }


    if(!$_FILES[IKIT_ONE_THEME_OPTION_FAV_ICON]['error']) {

        $file = wp_handle_upload($_FILES[IKIT_ONE_THEME_OPTION_FAV_ICON], array('test_form' => false));
        $input[IKIT_ONE_THEME_OPTION_FAV_ICON] = $file;

    }
    else {

        $input[IKIT_ONE_THEME_OPTION_FAV_ICON] = $theme_options[IKIT_ONE_THEME_OPTION_FAV_ICON];

    }

    $input[IKIT_ONE_THEME_OPTION_EVENTS_POSTS_PER_PAGE] = $_POST[IKIT_ONE_THEME_OPTION_EVENTS_POSTS_PER_PAGE];
    $input[IKIT_ONE_THEME_OPTION_HOME_NEWS_POSTS_PER_PAGE] = $_POST[IKIT_ONE_THEME_OPTION_HOME_NEWS_POSTS_PER_PAGE];
    $input[IKIT_ONE_THEME_OPTION_HOME_EVENTS_POSTS_PER_PAGE] = $_POST[IKIT_ONE_THEME_OPTION_HOME_EVENTS_POSTS_PER_PAGE];
    $input[IKIT_ONE_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT] = ($_POST[IKIT_ONE_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT] == 'on' ? 1 : 0);
    $input[IKIT_ONE_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED] = ($_POST[IKIT_ONE_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED] == 'on' ? 1 : 0);
    $input[IKIT_ONE_THEME_OPTION_HOME_EVENTS_NEWS_ORDER_TOGGLE] = $_POST[IKIT_ONE_THEME_OPTION_HOME_EVENTS_NEWS_ORDER_TOGGLE];
    $input[IKIT_ONE_THEME_OPTION_FEATURED_IMAGE_GALLERY_MAX_HEIGHT_DISABLED] = ($_POST[IKIT_ONE_THEME_OPTION_FEATURED_IMAGE_GALLERY_MAX_HEIGHT_DISABLED]  == 'on' ? 1 : 0);

    return $input;

}

?>