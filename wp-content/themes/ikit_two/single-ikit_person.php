<?php
/**
 * Template Name: Single Person
 */

?>
<?php get_header();?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post();
    global $id;

    $images = ikit_person_get_images($person->ID);
    $positions = ikit_person_get_positions($person->ID);

    $post_image = null;
    if(sizeof($images) > 0) {
        if(sizeof($images) > 1) {
            $post_images = wp_get_attachment_image_src($images[1]['image'], 'full');
            $post_image = $post_images[0];
        }
        else {
            $post_images = wp_get_attachment_image_src($images[0]['image'], 'full');
            $post_image = $post_images[0];
        }
    }

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

                    <?php if(sizeof($positions) > 0) { ?>
                    <div class="person-positions">
                        <?php for($i=0;$i<sizeof($positions);$i++) { ?>
                            <span><?php echo $positions[$i]['title']; if($i != sizeof($positions) - 1) { echo ','; }; ?></span>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>

        </div>

    <?php } ?>

    <table class="page-layout-4">
    <tr>

        <td class="page-layout-4-tools">
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

            </td>
            </tr>
            </table>

        </td>

    </tr>
    </table>

    <?php
endwhile;?>

<?php get_footer();?>