<?php

/**
 * Main file for member feed
 */

define('IKIT_MEMBER_VERSION', '1.0.0');
define('IKIT_MEMBER_DB_VERSION', '1.43');
define('IKIT_MEMBER_TABLE_NAME', 'ikit_member');
define('IKIT_MEMBER_FEED_URL', 'http://my.aiga.org/vango/custom/chapterinformation.aspx?key=65AA0003-6329-495B-AFE3-2925E6ABCCF2');

global $ikit_member_db_version;
$ikit_member_db_version = IKIT_MEMBER_DB_VERSION;


/**
 * Install or update plugin by creating database tables
 */
function ikit_member_install() {
    global $wpdb;
    global $ikit_member_db_version;

    $member_table_name = $wpdb->prefix . IKIT_MEMBER_TABLE_NAME;
    $installed_ver = get_option( "ikit_member_db_version" );

    if($installed_ver != $ikit_member_db_version) {

        $sql = "CREATE TABLE " . $member_table_name . " (
            id varchar(128) NOT NULL,
            member_type tinytext,
            category tinytext,
            full_name tinytext,
            company tinytext,
            attribute1 tinytext,
            attribute2 tinytext,
            join_date datetime DEFAULT '0000-00-00 00:00:00',
            email tinytext,
            work_phone tinytext,
            address1 tinytext,
            address2 tinytext,
            address3 tinytext,
            city tinytext,
            state_province tinytext,
            zip tinytext,
            university tinytext,
            grad_month tinytext,
            grad_year mediumint(9),
            member_status_date datetime DEFAULT '0000-00-00 00:00:00',
            orig_member_type tinytext,
            member_status tinytext,
            avatar tinytext,
            first_name tinytext,
            last_name tinytext,
            is_member tinyint(1) DEFAULT 0,

            PRIMARY KEY (id),
            UNIQUE KEY id (id)

        ) CHARACTER SET utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option("ikit_member_db_version", $ikit_member_db_version);
    }

}

function ikit_member_update_db_check() {
    global $ikit_member_db_version;
    if (get_site_option('ikit_member_db_version') != $ikit_member_db_version) {
        ikit_member_install();
    }
}
add_action('wp_loaded', 'ikit_member_update_db_check');

/**
 * Fetch the members for this chapter
 */
function ikit_member_remote_fetch() {

    global $g_options;
    global $wpdb;
    $member_table_name = $wpdb->prefix . IKIT_MEMBER_TABLE_NAME;

    // Pull twitter feed
    $imus_chapter = null;
    if(isset($g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_IMUS_CHAPTER])) {
        $imus_chapter = $g_options[IKIT_PLUGIN_OPTION_FILTER_IKIT_IMUS_CHAPTER];
    }

    if($imus_chapter != null) {

        // Get members for chapter
        $curl_list_request = curl_init();
        curl_setopt($curl_list_request, CURLOPT_URL, IKIT_MEMBER_FEED_URL . "&chapter=" . $imus_chapter . '&pagesize=9999&page=1');
        curl_setopt($curl_list_request, CURLOPT_RETURNTRANSFER, true);
        $curl_list_response = curl_exec($curl_list_request);
        curl_close($curl_list_request);

        $members = json_decode($curl_list_response);
        $valid_members = array();
        if(isset($members->root->response->QueryResults)) {

            // Truncate existing members
            $wpdb->query("truncate table $member_table_name");

            $members = $members->root->response->QueryResults;
            foreach($members as $member) {

                $member_id = (string)$member->ID;

                // Do not include error conditioned events or those without live status
                if($member_id <= 0) {
                    continue;
                }

                $member_fullname = (string)$member->FullName;
                $member_fullname_exploded = explode(" ", $member_fullname);
                $member_first_name = null;
                $member_last_name = null;
                if(count($member_fullname_exploded) >= 2) { // Valid names are space separated
                    $member_last_name = $member_fullname_exploded[count($member_fullname_exploded)-1];
                    $member_first_name = $member_fullname_exploded[0];
                }

                $member_data = array(
                    'id' => $member_id,
                    'member_type' => $member->MemberType,
                    'category' => $member->Category,
                    'full_name' => empty($member->FullName) ? null : $member->FullName,
                    'company' => $member->Company,
                    'attribute1' => $member->ATTRIBUTE1,
                    'attribute2' => $member->ATTRIBUTE2,
                    'join_date' => date('Y-m-d H:i:s', strtotime($member->JoinDate)),
                    'email' => $member->Email,
                    'work_phone' => $member->WorkPhone,
                    'address1' => $member->Address1,
                    'address2' => $member->Address2,
                    'address3' => $member->Address3,
                    'city' => $member->City,
                    'state_province' => $member->StateProvince,
                    'zip' => $member->Zip,
                    'university' => $member->UNIVERSITY,
                    'grad_month' => $member->GRAD_MONTH,
                    'grad_year' => (empty($member->GRAD_YEAR) ? -1 : $member->GRAD_YEAR),
                    'member_status_date' => date('Y-m-d H:i:s', strtotime($member->MemberStatusDate)),
                    'orig_member_type' => $member->ORIG_MEMTYPE,
                    'member_status' => $member->MemberStatus,
                    'avatar' => empty($member->AVATAR) ? null : $member->AVATAR,
                    'first_name' => $member_first_name,
                    'last_name' => $member_last_name,
                    'is_member' => (($member->MemberType != 'FRND' && $member->MemberType != 'CORP' && $member->MemberType != 'CORPN' && $member->MemberType != 'STAFF' && $member->MemberType != 'CHAPT' && $member->MemberType != 'EDUC')) ? 1 : 0
                );

                $rows_affected = $wpdb->insert($member_table_name, $member_data);

            }

        }

    }

}

// add_action('wp_loaded', 'ikit_member_remote_fetch');

?>