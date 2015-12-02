<?php
/**
 * Template Name: Contact Us
 */
?>
<?php get_header();?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

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
            <div class="page-layout-body-pad">
              <?php the_content();?>
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

<?php endwhile; ?>


<?php get_footer();?>