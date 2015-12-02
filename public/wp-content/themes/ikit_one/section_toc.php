<?php
/**
 * Template Name: Section Table of Contents
 *
 * @package WordPress
 * @subpackage Internet_Kit
 * @since Internet Kit One 1.0.0
 *
 */
?>

<?php get_header();?>

<?php
$ikit_section_id = get_post_meta($post->ID, IKIT_CUSTOM_FIELD_PAGE_IKIT_SECTION, true);
$ikit_section = get_post($ikit_section_id);

// Get the nav item this page corresponds to
$selected_main_nav_menu_item = null;
foreach($g_main_nav_menu_items as $main_nav_menu_item) {
    if($main_nav_menu_item->object_id == $post->ID) {
        $selected_main_nav_menu_item = $main_nav_menu_item;
        break;
    }
}
?>

<div class="page-<?php echo $ikit_section->post_name; ?>">

<div class="box-container">
<div class="box">
<?php ikit_one_render_banner_header($ikit_section_id);?>

<div class="box-top-empty-spacer"></div>
<div class="box-section">

<?php if(empty($selected_main_nav_menu_item->description) == false) { ?>
<div class="wp-editor">
    <div class="editor-header3 clear-text-transform"><?php echo $selected_main_nav_menu_item->description; ?></div>
</div>
<?php } ?>

<?php

// Found the nav item
$count = 0;
$main_nav_menu_items_for_parent_id = $g_main_nav_menu_items_by_parent_id[$selected_main_nav_menu_item->ID];
foreach($main_nav_menu_items_for_parent_id as $main_nav_menu_item_for_parent_id) {
?>

    <div class="box-section-title2"><a target="<?php echo $main_nav_menu_item_for_parent_id->target; ?>" href="<?php echo $main_nav_menu_item_for_parent_id->url; ?>"><?php echo $main_nav_menu_item_for_parent_id->title; ?></a></div>
    <?php if($main_nav_menu_item_for_parent_id->description != '') {?>
        <div class="box-section-body"><?php echo $main_nav_menu_item_for_parent_id->description; ?></div>
    <?php } ?>

    <?php if($count != count($main_nav_menu_items_for_parent_id)-1) { ?>
        <div class="box-section-divider"></div>
    <?php }
    $count++;
    ?>

<?php

}
?>

</div>

</div>
</div>

<div class="box-close"></div>

</div>

<?php get_sidebar();?>
<?php get_footer();?>