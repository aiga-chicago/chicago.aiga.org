<?php
/**
 * Internet Kit Portfolios Template Functions.
 *
 * @package Internet Kit
 * @subpackage Template
 */

/**
 * Retrieve meta for the portfolio
 */
function ikit_portfolio_get_meta($ikit_portfolio_id) {

    $owner_id = get_post_meta($ikit_portfolio_id, IKIT_CUSTOM_FIELD_IKIT_PORTFOLIO_ID, true);

    global $wpdb;

    $project_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_TABLE_NAME;
    $owner_table_name = $wpdb->prefix . IKIT_PORTFOLIO_OWNER_TABLE_NAME;
    $project_owner_table_name = $wpdb->prefix . IKIT_PORTFOLIO_PROJECT_OWNER_TABLE_NAME;

    $sql = "select project.*, owner.url as owner_url, owner.id as owner_id, owner.name as owner_name, owner.frame_url as owner_frame_url, owner.aiga_chapter as owner_aiga_chapter from $project_owner_table_name project_owner
        inner join $project_table_name project on project.id = project_owner.project_id
        inner join $owner_table_name owner on owner.id = project_owner.owner_id where owner.id = $owner_id ";

    return $wpdb->get_results($sql);

}

?>