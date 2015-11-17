<?php
/**
 * Template Name: Jobs
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

<div class="box-section">

<?php ikit_one_render_banner_header($ikit_section_id);?>

<div class="wp-editor">
    <div class="editor-header3">

        To post a job, visit the <a target="_blank" href="http://designjobs.aiga.org">Design Jobs site</a>; one of the <a target="_blank" href="http://www.aiga.org/benefits/">benefits</a> of AIGA membership at the Sustaining level and above is discounted job posting rates.
        <BR/><BR/>
        Only AIGA members can see the full details of the positions listed below (you must be logged in to your AIGA account). And you can see listings from other geographical areas by visiting the main <a target="_blank" href="http://designjobs.aiga.org">Design Jobs site</a>. AIGA Friends and nonmembers, consider <a target="_blank" href="http://www.aiga.org/belong">joining</a> to reap the full benefits of this service.
    </div>
</div>

<?php

// Get the posts
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$post_count = 0;

$args = array();
$args['posts_per_page'] = IKIT_ONE_JOBS_POSTS_PER_PAGE;
$args['paged'] = $paged;

$args['post_type'] = IKIT_POST_TYPE_IKIT_JOB;
query_posts($args);
while (have_posts()) : the_post();
    global $id;

    $ikit_job_meta = ikit_job_get_meta($id);

    ?>

        <?php if($post_count != 0) { ?>
            <div class="box-section-divider"></div>
        <?php } ?>

        <div class="box-section-title-detail2">
            <div><?php echo mysql2date('F j, Y', get_gmt_from_date($ikit_job_meta->date_approved), false);?></div>
        </div>

        <div class="box-section-title2">
            <a href="<?php echo ikit_sso_get_login_url(get_permalink()); ?>"><?php the_title(); ?></a>
        </div>
        <div class="box-section-body">
            <div><?php echo $ikit_job_meta->company_name; ?></div>
            <div><?php echo $ikit_job_meta->city; ?>, <?php echo $ikit_job_meta->state; ?></div>
        </div>




    <?php
    $post_count++;

endwhile;

?>

</div>
</div>

<?php

ikit_one_render_pager($wp_query);
wp_reset_query();

?>

</div>

<div class="box-close"></div>

</div>

<?php get_sidebar();?>
<?php get_footer();?>