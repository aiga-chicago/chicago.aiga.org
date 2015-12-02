<?php
/**
 * Template Name: Single
 */

?>
<?php get_header();?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post();
    global $id;
    $post_image = ikit_post_get_image_url($id, 'full', null);
    ?>

    <?php if(empty($post_image)) { ?>

        <div class="page-header-5">
            <div class="page-header-5-title">
                <?php the_title(); ?>
            </div>
        </div>

    <?php } else { ?>

        <div class="page-header-4">
            <div class="page-header-4-overlay"><img src="<?php bloginfo('template_url'); ?>/images/detail_background.png"/></div>
            <?php if(ikit_two_browser_supports_fixed_background_image($g_theme_options) == false) { ?>
                <div class="page-header-4-image hero-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php echo $post_image; ?>"></div>
            <?php } else { ?>
                <div class="page-header-4-image hero-image fixed-background-image" style="background-image:url('<?php echo $post_image; ?>');"></div>
            <?php } ?>

            <div class="page-header-4-title-container">
                <div class="page-header-4-title">
                    <?php the_title(); ?>
                </div>
            </div>

        </div>

    <?php } ?>

    <table class="page-layout-4">
    <tr>

        <td class="page-layout-4-tools">

            <div class="cat-plugin-fluid-grid grid"
                cat_plugin_fluid_grid_layout_mode="fitRows"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_TOOLS_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
            >
                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool first">
                            <div class="page-layout-4-tool-header">Written by</div>
                            <div class="page-layout-4-tool-text"><?php echo ikit_post_get_author($id, get_the_author()); ?></div>
                        </div>
                    </div>
                </div>

                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-header">Published</div>
                            <div class="page-layout-4-tool-text"><?php echo mysql2date('F j, Y', get_gmt_from_date($post->post_date), false);?></div>
                        </div>
                    </div>
                </div>

                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-header">Categories</div>
                            <div class="page-layout-4-tool-filters">
                            <?php
                            $category_ids = wp_get_post_categories($post->ID);
                            foreach($category_ids as $category_id) {
                                $category = get_category($category_id);
                                ?>
                                <div class="page-layout-4-tool-filter"><a href="<?php echo get_category_link($category); ?>"><?php echo $category->name; ?></a></div>
                                <?php
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="page-layout-4-tools-footer"></div>

        </td>

        <td class="page-layout-4-content">

            <table>
            <tr>
            <td class="page-layout-4-body">
                <div class="page-layout-body-pad">
                    <div class="page-layout-body-paragraph-text wp-editor">
                        <?php the_content();?>
                    </div>
                </div>
            </td>
            <td class="page-layout-4-sidebar">
                <?php dynamic_sidebar('single-sidebar');?>
            </td>
            </tr>
            </table>

        </td>

    </tr>
    </table>

    <div class="page-layout-4-sub-content comments">
        <div class="page-layout-4-sub-content-inner">
            <div class="cat-plugin-fluid-grid grid"
                cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
            >

                <div class="cat-plugin-fluid-grid-item grid-item page-layout-4-sub-content-title">
                    <div class="grid-item-inner">
                        Comments
                    </div>
                </div>

                <div class="cat-plugin-fluid-grid-item grid-item" cat_plugin_fluid_grid_item_size="3">
                    <div class="grid-item-inner">
                        <?php include(TEMPLATEPATH . '/post_comments.php'); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php
endwhile;?>

<?php get_footer();?>