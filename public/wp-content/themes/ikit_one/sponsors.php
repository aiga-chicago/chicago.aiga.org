<?php
/**
 * Template Name: Sponsors
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

<?php ikit_one_render_banner_header(get_post_meta($post->post_parent, IKIT_CUSTOM_FIELD_PAGE_IKIT_SECTION, true), null, 'Official Sponsor');?>

<?php
    $post_count = 0;
    global $g_national_sponsors;
?>

    <?php foreach($g_national_sponsors as $sponsor) { ?>

        <?php
        $sponsorUrl = get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL, true);
        ?>

        <?php if($post_count != 0) { ?>
            <div class="box-section-divider"></div>
        <?php } ?>

        <div class="box-section-body"><a target="_blank" href="<?php echo $sponsorUrl; ?>"><img src="<?php echo wp_get_attachment_url(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE, true)); ?>" /></a></div>
        <div class="box-section-spacer"></div>
        <div class="box-section-body"><?php echo $sponsor->post_content; ?></div>

        <?php $post_count++; ?>

    <?php } ?>

    <div class="box-section-spacer"></div>

</div>
</div>

<div class="box-close"></div>


<?php if(count($g_local_sponsors) > 0) { ?>

<div class="box-container">
<div class="box">
<div class="box-top-empty-spacer"></div>

<?php ikit_one_render_banner_header(get_post_meta($post->post_parent, IKIT_CUSTOM_FIELD_PAGE_IKIT_SECTION, true), null, 'Chapter Sponsors'); ?>

<?php
    $post_count = 0;
?>

    <?php foreach($g_local_sponsors as $sponsor) { ?>

        <?php
        $sponsorUrl = get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL, true);
        ?>

        <?php if($post_count != 0) { ?>
            <div class="box-section-divider"></div>
        <?php } ?>

        <div class="box-section-body"><a target="_blank" href="<?php echo $sponsorUrl; ?>"><img src="<?php echo wp_get_attachment_url(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_PRIMARY_IMAGE, true)); ?>" /></a></div>
        <div class="box-section-spacer"></div>
        <div class="box-section-body"><?php echo $sponsor->post_content; ?></div>

        <?php $post_count++; ?>

    <?php } ?>

    <div class="box-section-spacer"></div>

</div>
</div>

<div class="box-close"></div>

<?php } ?>

<?php get_sidebar();?>
<?php get_footer();?>