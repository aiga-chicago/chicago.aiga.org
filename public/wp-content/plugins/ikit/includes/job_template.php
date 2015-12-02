<?php
/**
 * Internet Kit Jobs Template Functions.
 *
 * @package Internet Kit
 * @subpackage Template
 */

/**
 * Get the job meta information, because it is stored in it's own table
 * instead of custom fields, we use this function instead, it is stored
 * in it's own table for performance reasons, so we don't have 15 or so
 * custom fields on a post
 */
function ikit_job_get_meta($ikit_job_id) {

    $job_id = get_post_meta($ikit_job_id, IKIT_CUSTOM_FIELD_IKIT_JOB_ID, true);    
    
    global $wpdb;
    $job_table_name = $wpdb->prefix . IKIT_JOB_TABLE_NAME; 
    
    $sql = "select * from $job_table_name where id = $job_id ";
    
    return $wpdb->get_row($sql);

}

?>