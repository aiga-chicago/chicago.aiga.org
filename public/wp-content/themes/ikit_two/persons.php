<?php
/**
 * Template Name: Persons
 */
?>
<?php get_header();?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post();
    global $id;
    global $post;

    // Get the posts
    $args = array();
    $args['posts_per_page'] = 999;
    $args['order'] = 'ASC';
    $args['post_status'] = 'publish';
    $args['post_type'] = array(IKIT_POST_TYPE_IKIT_PERSON);
    $args['orderby'] = 'meta_value';
    $args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;

    // Filter
    $category_ids = wp_get_post_categories($post->ID);
    if(sizeof($category_ids) > 0) {
        $args['category__in'] = $category_ids;
    }

    $persons = get_posts($args);

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
                    cat_plugin_fluid_grid_layout_mode="fitRows"
                    cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_BODY_NO_SIDEBAR_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
                    cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                    cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
                >

                <?php

                // Allow for disabling of the single page for persons
                $theme_options = get_option(IKIT_TWO_THEME_OPTION_GROUP_GENERAL);
                $single_disabled = $theme_options[IKIT_TWO_THEME_OPTION_SINGLE_DISABLED_IKIT_PERSON];

                foreach($persons as $person) {

                    $positions = ikit_person_get_positions($person->ID);
                    $images = ikit_person_get_images($person->ID);
                    ?>

                    <div class="cat-plugin-fluid-grid-item grid-item person-item">
                        <div class="grid-item-inner">

                            <?php if(sizeof($images) > 0) { ?>
                                <?php if($single_disabled) { ?>
                                    <div class="person-item-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php $person_image = wp_get_attachment_image_src($images[0]['image'], 'full'); echo $person_image[0];?>"></div>
                                <?php } else { ?>
                                <a href="<?php echo get_permalink($person->ID); ?>">
                                    <div class="person-item-image cat-plugin-anystretch-image" cat_plugin_anystretch_image_url="<?php $person_image = wp_get_attachment_image_src($images[0]['image'], 'full'); echo $person_image[0];?>"></div>
                                </a>
                                <?php } ?>

                            <?php } ?>

                            <div class="person-item-title">
                                <?php if($single_disabled) { ?>
                                    <?php echo $person->post_title; ?>
                                <?php } else { ?>
                                    <a href="<?php echo get_permalink($person->ID); ?>"><?php echo $person->post_title; ?></a>
                                <?php } ?>
                            </div>

                            <?php if(sizeof($positions) > 0) { ?>
                            <div class="person-item-positions">
                                <?php for($i=0;$i<sizeof($positions);$i++) { ?>
                                    <span><?php echo $positions[$i]['title']; if($i != sizeof($positions) - 1) { echo ','; }; ?></span>
                                <?php } ?>
                            </div>
                            <?php } ?>

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

    <?php
endwhile;?>

<?php get_footer();?>