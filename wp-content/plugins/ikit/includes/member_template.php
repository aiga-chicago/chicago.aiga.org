<?php
/**
 * Internet Kit Members Template Functions.
 *
 * Note there is no WordPress custom post for each member, as this seemed unneccessary
 * and an added complication. Therefore if you would like to query the members
 * you can do so with direct SQL, or call ikit_get_members and sort and modify
 * with those objects instead.
 *
 * @package Internet Kit
 * @subpackage Template
 */

/**
 * Retrieve the members for the chapter
 */
function ikit_member_get_members() {

    global $wpdb;
    $member_table_name = $wpdb->prefix . IKIT_MEMBER_TABLE_NAME;

    $members = $wpdb->get_results("SELECT * from $member_table_name where full_name != '' and is_member = 1 order by last_name, first_name");
    return $members;

}

/**
 * Returns the member join year
 */
function ikit_member_join_year($member) {
    if(isset($member->join_date)) {
        $member_joindate_exploded = explode('-', $member->join_date);
        if(count($member_joindate_exploded) > 0) {
            return $member_joindate_exploded[0];
        }
    }
    return null;
}

/**
 * Returns the member profile URL on aiga.org
 */
function ikit_member_profile_url($member) {
    return "http://www.aiga.org/profile.aspx?uid=" . $member->id;
}

/**
 * Returns a list of member types ordered by level
 */
function ikit_member_types() {

    $member_types = array("TRUST", "LEAD", "SUS", "SUP", "CONTR");
    return $member_types;

}

/**
 * Returns a member type for display
 */
function ikit_member_type_display_name($member_type) {

    $member_type_display_name = null;

    if($member_type == 'SUS') {
        $member_type_display_name = 'Sustaining';
    }
    else if($member_type == 'LEAD') {
        $member_type_display_name = 'Design leader';
    }
    else if($member_type == 'TRUST') {
        $member_type_display_name = 'Trustee';
    }
    else if($member_type == 'SUP') {
        $member_type_display_name = 'Supporter';
    }
    else if($member_type == 'CONTR') {
        $member_type_display_name = 'Contributor';
    }

    return $member_type_display_name;

}

?>
