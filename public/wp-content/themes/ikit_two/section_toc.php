<?php
/**
 * Template Name: Section Table of Contents
 */

$nav_menu_locations = get_nav_menu_locations();
$nav_menu = wp_get_nav_menu_object($nav_menu_locations['nav-menu-main']);
$nav_menu_items = wp_get_nav_menu_items($nav_menu->term_id);

// Find the current page in the nav menu items
$selected_nav_menu_item = null;
foreach((array)$nav_menu_items as $key => $nav_menu_item ) {
    if($nav_menu_item->object_id == $post->ID) {
        $selected_nav_menu_item = $nav_menu_item;
        break;
    }
}

// Find the first child of the current nav menu item, then redirect to that page
if($selected_nav_menu_item != null) {
    foreach((array)$nav_menu_items as $key => $nav_menu_item ) {
        if($nav_menu_item->menu_item_parent == $selected_nav_menu_item->ID) {

            // However, if is an external link, we show the TOC
            if($nav_menu_item->target != '_blank') {
                wp_redirect($nav_menu_item->url);
            }
            break;
        }
    }
}

?>
<?php get_header();?>

<?php

// Get the nav item this page corresponds to
$selected_main_nav_menu_item = null;
foreach($g_main_nav_menu_items as $main_nav_menu_item) {
    if($main_nav_menu_item->object_id == $post->ID) {
        $selected_main_nav_menu_item = $main_nav_menu_item;
        break;
    }
}

?>

<div class="page-header-3">
    <table>
    <tr>
        <td class="page-header-3-section-title">
            <?php ikit_two_render_toc_title($post); ?>
        </td>
        <td class="page-header-3-title"></td>
    </tr>
    </table>
</div>


<table class="page-layout-4">
<tr>

    <td class="page-layout-4-content">

        <table>
        <tr>
        <td class="page-layout-4-body">

            <div class="page-layout-4-body-description">
                <div class="page-layout-4-body-description-inner">
                    <?php echo $selected_main_nav_menu_item->description; ?>
                </div>
            </div>

            <div class="cat-plugin-fluid-grid grid"
                cat_plugin_fluid_grid_layout_mode="fitRows"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_BODY_FULL_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
            >

                <?php

                // Found the nav item
                $count = 0;
                $main_nav_menu_items_for_parent_id = $g_main_nav_menu_items_by_parent_id[$selected_main_nav_menu_item->ID];
                foreach($main_nav_menu_items_for_parent_id as $main_nav_menu_item_for_parent_id) {
                ?>

                    <div class="cat-plugin-fluid-grid-item grid-item item">
                        <div class="grid-item-inner">

                        <div class="item-title"><a target="<?php echo $main_nav_menu_item_for_parent_id->target; ?>" href="<?php echo $main_nav_menu_item_for_parent_id->url; ?>"><?php echo $main_nav_menu_item_for_parent_id->title; ?></a></div>
                        <?php if($main_nav_menu_item_for_parent_id->description != '') {?>
                            <div class="item-description"><?php echo $main_nav_menu_item_for_parent_id->description; ?></div>
                        <?php } ?>
                        <?php if($main_nav_menu_item_for_parent_id->target == '_blank') { ?>
                            <div class="item-external-link">
                                <a target="<?php echo $main_nav_menu_item_for_parent_id->target; ?>" href="<?php echo $main_nav_menu_item_for_parent_id->url; ?>">Visit site</a>
                            </div>
                        <?php } ?>

                        <?php $count++; ?>

                        </div>
                    </div>



                <?php

                }
                ?>

            </div>

        </td>
        </tr>
        </table>

    </td>

</tr>
</table>


<?php get_footer();?>