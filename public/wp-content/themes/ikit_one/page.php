<?php
/**
 * Template for displaying a page
 *
 * @package WordPress
 * @subpackage Internet_Kit
 * @since Internet Kit One 1.0.0
 *
 */
?>

<?php get_header();?>

<?php
$ikit_section_id = get_post_meta($post->post_parent, IKIT_CUSTOM_FIELD_PAGE_IKIT_SECTION, true);
$ikit_section = get_post($ikit_section_id);
?>

<div class="page-<?php echo $ikit_section->post_name; ?>">

<div class="box-container">
<div class="box">

<div class="box-top-empty-spacer"></div>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<?php
ikit_one_render_banner_header($ikit_section_id, null, $post->post_title);
?>


<div class="box-section-wysiwyg-content wp-editor">
<?php the_content(); ?>
</div>

<?php endwhile; ?>

<?php if($g_theme_options[IKIT_ONE_THEME_OPTION_COMMENTS_ENABLED_PAGE]) { ?>

    <div class="box-section-spacer"></div>
    <div class="box-section-divider"></div>

    <?php include(TEMPLATEPATH . '/post_comments.php'); ?>
<?php } ?>

</div>
</div>



<div class="box-close"></div>



</div>

<?php get_sidebar();?>
<?php get_footer();?>