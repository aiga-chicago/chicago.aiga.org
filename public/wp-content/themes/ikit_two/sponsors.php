<?php
/**
 * Template Name: Sponsors
 */
?>
<?php get_header();?>

<?php

// Get the nav item this page corresponds to
foreach($g_main_nav_menu_items_all as $main_nav_menu_item) {
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

    <td class="page-layout-4-tools">
        <div class="cat-plugin-fluid-grid grid"
            cat_plugin_fluid_grid_layout_mode="fitRows"
            cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_TOOLS_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
            cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
            cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
        >
            <?php if(ikit_two_has_toc($post)) {?>
            <div class="cat-plugin-fluid-grid-item grid-item">
                <div class="grid-item-inner">
                    <div class="page-layout-4-tool">
                        <?php ikit_two_render_toc($post, 'page-layout-4-tool'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>

        <div class="page-layout-4-tools-footer"></div>

    </td>

    <td class="page-layout-4-content">

        <table>
        <tr>
        <td class="page-layout-4-body">

            <div class="cat-plugin-fluid-grid grid"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_BODY_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
            >

            <?php
                $post_count = 0;
                global $g_national_sponsors;
            ?>

            <?php foreach($g_national_sponsors as $sponsor) { ?>

                <?php
                $sponsor_url = get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL, true);
                $attachment_image_src = wp_get_attachment_image_src(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE, true), 'full')

                ?>

                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="sponsor-category">Official</div>
                        <div class="sponsor-image"><a target="_blank" href="<?php echo $sponsor_url; ?>"><img width="<?php echo round($attachment_image_src[1]/2); ?>px" src="<?php echo $attachment_image_src[0]; ?>"/></a></div>
                        <div class="sponsor-description"><?php echo $sponsor->post_content; ?></div>
                    </div>
                </div>

                <?php $post_count++; ?>

            <?php } ?>


            <?php if(count($g_local_sponsors) > 0) { ?>

                <?php $post_count = 0; ?>

                <?php foreach($g_local_sponsors as $sponsor) { ?>

                    <?php
                    $sponsor_url = get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL, true);
                    $attachment_image_src = wp_get_attachment_image_src(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE, true), 'full')
                    ?>

                    <div class="cat-plugin-fluid-grid-item grid-item">
                        <div class="grid-item-inner">

                            <?php
                            $category = get_the_category($sponsor->ID);
                            if(count($category) > 0) {
                                ?>
                                <div class="sponsor-category">
                                    <?php echo $category[0]->name; ?>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="sponsor-image"><a target="_blank" href="<?php echo $sponsor_url; ?>"><img width="<?php echo round($attachment_image_src[1]/2); ?>px" src="<?php echo $attachment_image_src[0]; ?>"/></a></div>
                            <div class="sponsor-description"><?php echo $sponsor->post_content; ?></div>
                        </div>
                    </div>

                    <?php $post_count++; ?>

                <?php } ?>

            <?php } ?>

            </div>

        </td>
        <td class="page-layout-4-sidebar">
            <?php dynamic_sidebar('page-sidebar');?>
        </td>
        </tr>
        </table>

    </td>

</tr>
</table>

<?php get_footer();?>