<?php

/**
 * Settings
 */

add_action('admin_menu', 'ikit_menu_dashboard_settings_menu_add', 10);

function ikit_menu_dashboard_settings_menu_add() {
    add_submenu_page( 'ikit-menu', 'Dashboard Settings', 'Dashboard Settings', 'manage_options', 'ikit-dashboard', 'ikit_menu_dashboard_settings_render');
    register_setting( 'ikit_settings', 'ikit_dashboard_settings', 'ikit_menu_dashboard_settings_validate');
}

function ikit_menu_dashboard_settings_render() {

    ?>
    <div class="wrap">
        <div id="icon-options-general" class="icon32"></div><h2>Dashboard</h2>

        <form method="post" action="options.php">
            <?php settings_fields('ikit_settings'); ?>
            <?php $options = get_option('ikit_dashboard_settings'); ?>

            <div class="wp-box">
                <div class="inner">

                    <h2>Widgets</h2>

                    <table class="widefat">

                    <?php ikit_render_settings_input('ikit_dashboard_settings', $options, 'Chapter Resources', 'Content to display in the chapter resources widget', IKIT_PLUGIN_OPTION_DASHBOARD_WIDGET_CHAPTER_RESOURCES_CONTENT, 'wp_editor'); ?>

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

function ikit_menu_dashboard_settings_validate($input) {
    return $input;
}

?>