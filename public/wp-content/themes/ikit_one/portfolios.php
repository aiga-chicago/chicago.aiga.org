<?php
/**
 * Template Name: Portfolios
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

?>

<div class="page-<?php echo $ikit_section->post_name; ?>">

<div class="box-container">
<div class="box">
<div class="box-top-empty-spacer"></div>
<?php ikit_one_render_banner_header($ikit_section_id);?>

<div class="wp-editor">
    <div class="editor-header3">
        Check out the work of other chapter members! These projects are from the <a target="_blank" href="http://portfolios.aiga.org/">AIGA Member Portfolios Gallery</a>. Participation requires an <a target="_blank" href="http://www.aiga.org/belong">active AIGA membership</a>.
    </div>
</div>

<?php

// Get the posts
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array();
$args['posts_per_page'] = IKIT_ONE_PORTFOLIOS_POSTS_PER_PAGE;
$args['paged'] = $paged;
$args['post_type'] = IKIT_POST_TYPE_IKIT_PORTFOLIO;

query_posts($args);
while (have_posts()) : the_post();
    global $id;

    $ikit_portfolio_meta = ikit_portfolio_get_meta($id);

    $project = $ikit_portfolio_meta[array_rand($ikit_portfolio_meta, 1)]; // Get a random project for this portfolio

    $project_image = $project->cover_image_url;

    ?>


        <table class="box-section-split">
        <tr>
        <td class="box-section-split-col0">
            <div class="box-section-image"><a target="_blank" href="<?php echo $project->url; ?>"><img src="<?php if($project_image != null) { echo $project_image; } ?>"></img></a></div>
        </td>
        <td class="box-section-split-col1">

            <div class="box-section-title3 portfolios-member-header-label">MEMBER</div>
            <div class="box-section-title2"><a target="_blank" class="external-link-unstyled" href="<?php echo $project->owner_url; ?>"><?php the_title(); ?></a></div>

            <div class="box-section-title3 portfolios-project-header-label">FEATURED PROJECT</div>
            <div class="box-section-title2"><a class="external-link-unstyled" target="_blank" href="<?php echo $project->url; ?>"><?php echo $project->title; ?></a></div>

        </td>
        </tr>
        </table>

        <div class="box-section-divider"></div>


    <?php
endwhile;

?>

</div>

<?php

ikit_one_render_pager($wp_query, 'NEXT', null, false, 'PREVIOUS', null, false);
wp_reset_query();

?>

</div>

<div class="box-close"></div>

</div>


<?php get_sidebar();?>
<?php get_footer();?>