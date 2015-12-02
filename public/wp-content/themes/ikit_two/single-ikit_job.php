<?php
/**
 * Template Name: Single Job
 */
?>
<?php get_header();?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post();
    global $id;

    $ikit_job_meta = ikit_job_get_meta($id);
    ?>

    <div class="page-header-5">
        <div class="page-header-5-title">
            <?php the_title(); ?>
        </div>
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

                <?php if(empty($ikit_job_meta->company_name) == false) { ?>
                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-header">Company</div>
                            <div class="page-layout-4-tool-text"><?php echo $ikit_job_meta->company_name; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(empty($ikit_job_meta->city) == false) { ?>
                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-header">Location</div>
                            <div class="page-layout-4-tool-text"><?php echo $ikit_job_meta->city . ', ' . $ikit_job_meta->state . ', ' .  $ikit_job_meta->country; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(empty($ikit_job_meta->job_level) == false) { ?>
                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-header">Job level</div>
                            <div class="page-layout-4-tool-text"><?php echo $ikit_job_meta->job_level; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(empty($ikit_job_meta->expertise_area) == false) { ?>
                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-header">Field</div>
                            <div class="page-layout-4-tool-text"><?php echo $ikit_job_meta->expertise_area; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(empty($ikit_job_meta->job_functions) == false) { ?>
                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-header">Job functions</div>
                            <div class="page-layout-4-tool-text"><?php echo $ikit_job_meta->job_functions; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(empty($ikit_job_meta->date_approved) == false) { ?>
                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-header">Published</div>
                            <div class="page-layout-4-tool-text"><?php echo mysql2date('F j, Y', get_gmt_from_date($ikit_job_meta->date_approved), false);?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(empty($ikit_job_meta->application_url) == false) { ?>

                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-actions">
                                <div class="box-button-container box-button-1-container">
                                    <a target="_blank" class="apply-button box-button-1" href="<?php echo $ikit_job_meta->application_url; ?>">APPLY</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } else { ?>

                <div class="cat-plugin-fluid-grid-item grid-item">
                    <div class="grid-item-inner">
                        <div class="page-layout-4-tool">
                            <div class="page-layout-4-tool-actions">
                                <div class="box-button-container box-button-1-container">
                                    <a class="apply-button box-button-1" href="mailto:<?php echo $ikit_job_meta->apply_online_email; ?>">APPLY</a>
                                </div>
                            </div>
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
                    <div class="page-layout-body-paragraph-text wp-editor">

                        <?php if(empty($ikit_job_meta->description) == false) { ?>
                            <div><?php echo wpautop($ikit_job_meta->description); ?></div>
                        <?php } ?>

                        <?php if(empty($ikit_job_meta->other_skills) == false) { ?>
                            <div class="editor-header21">Other Skills</div>
                            <div><?php echo wpautop($ikit_job_meta->other_skills); ?></div>
                        <?php } ?>

                        <?php if(empty($ikit_job_meta->submission_details) == false) { ?>
                            <div class="editor-header21">Submission Details</div>
                            <div><?php echo wpautop($ikit_job_meta->submission_details); ?></div>
                        <?php } ?>

                    </div>
                </div>

            </td>
            <td class="page-layout-4-sidebar">

            </td>
            </tr>
            </table>

        </td>

    </tr>
    </table>

    <?php
endwhile;?>

<?php get_footer();?>