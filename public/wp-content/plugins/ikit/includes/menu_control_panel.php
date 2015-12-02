<?php

add_action('admin_menu', 'ikit_menu_control_panel_menu_add', 9);

function ikit_menu_control_panel_menu_add() {
    add_submenu_page( 'ikit-menu', 'Control Panel', 'Control Panel', 'manage_options', 'ikit-control-panel', 'ikit_menu_control_panel_render');

}

function ikit_menu_control_panel_render() {

    ?>

    <div class="wrap">
        <div id="icon-options-general" class="icon32"></div><h2>Control Panel</h2>

       <div class="wp-box">
            <div class="inner">

            <h2>Force refresh</h2>
            <p>Click the button below to force a refresh. Please wait after hitting refresh, it may take a few moments. Otherwise, feeds are automatically refreshed once per hour.</p>

            <table class="widefat">
            <tr>
                <td>
                <form method="post">
                    <input type="hidden" name="action" value="force_pull_etouches_events"/>
                    <input type="submit" value="Force pull etouches events" class="button-primary"/>
                    <p class="note" style="margin-bottom:0;">Please use this action sparingly. It is recommended to never force pull events from etouches more than once or twice a day.</p>
                </form>
                </td>
            </tr>
            <tr>
                <td>
                <form method="post">
                    <input type="hidden" name="action" value="force_pull_eventbrite_events"/>
                    <input type="submit" value="Force pull Eventbrite events" class="button-primary"/>
                </form>
                </td>
            </tr>

            </table>



            </div>

        </div>

        <div class="wp-box">
            <div class="inner">

            <h2>Force purge</h2>
            <p>Click the buttons below to permanently remove chapter specific events, portfolios, and jobs. If you change one of your feed settings, you should purge old jobs and portfolios if they are from the wrong chapter.</p>

            <table class="widefat">
            <tr>
                <td>
                <form method="post" action="">
                    <input type="hidden" name="action" value="force_purge_etouches_events"/>
                    <input type="submit" value="Force purge etouches events" class="button-primary"/>
                </form>
                </td>
            </tr>

            <tr>
                <td>
                <form method="post" action="">
                    <input type="hidden" name="action" value="force_purge_eventbrite_events"/>
                    <input type="submit" value="Force purge Eventbrite events" class="button-primary"/>
                </form>
                </td>
            </tr>

            <tr>
                <td>
                <form method="post" action="">
                    <input type="hidden" name="action" value="force_purge_jobs"/>
                    <input type="submit" value="Force purge jobs" class="button-primary"/>
                </form>
                </td>
            </tr>

            <tr>
                <td>
                <form method="post" action="">
                    <input type="hidden" name="action" value="force_purge_portfolios"/>
                    <input type="submit" value="Force purge portfolios" class="button-primary"/>
                </form>
                </td>
            </tr>

            </table>

            </div>

        </div>



    </div>

    <?php


}

// Process the request, running as hook ensures that everything has been loaded properly
function ikit_menu_control_panel_process_request() {

    // Handle form submission
    $action = null;
    if(isset($_POST['action'])) {
        $action = $_POST['action'];
    }

    // Pull etouches events
    if($action == 'force_pull_etouches_events') {
        ikit_event_etouches_remote_fetch();
    }

    // Pull Eventbrite events
    if($action == 'force_pull_eventbrite_events') {
        try {
            ikit_event_eventbrite_remote_fetch();
        } catch(Exception $e) {
            // Display admin error if problem fetching
            global $g_admin_notices;
            array_push($g_admin_notices, '<div class="error">Error during force pull Eventbrite events: ' . $e->getMessage() . '</div>');
        }
    }

    // Delete etouches events
    if($action == 'force_purge_etouches_events') {
        ikit_delete_ikit_events(IKIT_EVENT_SERVICE_ETOUCHES);
    }

    // Delete Eventbrite events
    if($action == 'force_purge_eventbrite_events') {
        ikit_delete_ikit_events(IKIT_EVENT_SERVICE_EVENTBRITE);
    }

    // Delete all jobs
    if($action == 'force_purge_jobs') {

        ikit_delete_ikit_jobs();

    }

    // Delete all portfolios
    if($action == 'force_purge_portfolios') {

        ikit_delete_ikit_portfolios();

    }



}

add_action('wp_loaded', 'ikit_menu_control_panel_process_request');

?>