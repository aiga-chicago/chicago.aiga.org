<?php
/**
 * The Template for displaying all single jobs
 *
 * @package WordPress
 * @subpackage Internet_Kit
 * @since Internet Kit One 1.0.0
 *
 */
?>

<?php get_header();?>

<div class="box-container">
<div class="box">
<div class="box-top-empty-spacer"></div>

<?php

// The single page is a wordpress built-in, therefore we can't get
// the page custom field section like normal, so use the constants since
// we already know for sure it's part of news

$ikit_section_jobs = ikit_get_post_by_slug(IKIT_SLUG_IKIT_SECTION_JOB, IKIT_POST_TYPE_IKIT_SECTION);
ikit_one_render_banner_header($ikit_section_jobs);

?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post();
    global $id;

    $ikit_job_meta = ikit_job_get_meta($id);

    ?>

    <div class="box-section">



        <div class="box-section-title2"><?php the_title(); ?></div>

        <?php if(empty($ikit_job_meta->company_name) == false) { ?>
        <div class="box-section-custom job-single-attribute">
            <div class="job-single-attribute-label">COMPANY:</div><div class="job-single-attribute-value"><?php echo $ikit_job_meta->company_name; ?></div>
        </div>
        <?php } ?>

        <?php if(empty($ikit_job_meta->city) == false) { ?>
        <div class="box-section-custom job-single-attribute">
            <div class="job-single-attribute-label">LOCATION:</div><div class="job-single-attribute-value"><?php echo $ikit_job_meta->city . ', ' . $ikit_job_meta->state . ', ' .  $ikit_job_meta->country; ?></div>
        </div>
        <?php } ?>

        <?php if(empty($ikit_job_meta->job_level) == false) { ?>
        <div class="box-section-custom job-single-attribute">
            <div class="job-single-attribute-label">JOB LEVEL:</div><div class="job-single-attribute-value"><?php echo $ikit_job_meta->job_level; ?></div>
        </div>
        <?php } ?>

        <?php if(empty($ikit_job_meta->expertise_area) == false) { ?>
        <div class="box-section-custom job-single-attribute">
            <div class="job-single-attribute-label">FIELD:</div><div class="job-single-attribute-value"><?php echo $ikit_job_meta->expertise_area; ?></div>
        </div>
        <?php } ?>

        <?php if(empty($ikit_job_meta->job_functions) == false) { ?>
        <div class="box-section-custom job-single-attribute">
            <div class="job-single-attribute-label">JOB FUNCTIONS:</div><div class="job-single-attribute-value"><?php echo $ikit_job_meta->job_functions; ?></div>
        </div>
        <?php } ?>

        <?php if(empty($ikit_job_meta->date_approved) == false) { ?>
        <div class="box-section-custom job-single-attribute">
            <div class="job-single-attribute-label">POSTED:</div><div class="job-single-attribute-value"><?php echo mysql2date('F j, Y', get_gmt_from_date($ikit_job_meta->date_approved), false);?></div>
        </div>
        <?php } ?>

        <?php if(empty($ikit_job_meta->description) == false) { ?>
            <div class="box-section-divider box-section-p-divider"></div>
            <div class="box-section-heading">DESCRIPTION</div>
            <div class="box-section-body"><?php echo wpautop($ikit_job_meta->description); ?></div>
        <?php } ?>

        <?php if(empty($ikit_job_meta->other_skills) == false) { ?>
            <div class="box-section-divider"></div>
            <div class="box-section-heading">SPECIFIC SKILLS</div>
            <div class="box-section-body"><?php echo wpautop($ikit_job_meta->other_skills); ?></div>
        <?php } ?>

        <div class="box-section-divider"></div>
        <div class="box-section-heading">SUBMISSION DETAILS</div>
        <div class="box-section-body"><?php echo wpautop($ikit_job_meta->submission_details); ?></div>
        <div class="box-section-body"><a href="mailto:<?php echo $ikit_job_meta->apply_online_email; ?>"><?php echo $ikit_job_meta->apply_online_email; ?></a></div>

        <?php if(empty($ikit_job_meta->application_url) == false) { ?>
            <div class="box-section-body"><a href="<?php echo $ikit_job_meta->application_url; ?>"><?php if(empty($ikit_job_meta->application_url_text)) { echo 'Click to apply online';} else { echo $ikit_job_meta->application_url_text; } ?></a></div>
        <?php } ?>

    </div>


    <?php
endwhile;?>

</div>
</div>

<div class="box-close"></div>

<?php get_sidebar();?>
<?php get_footer();?>