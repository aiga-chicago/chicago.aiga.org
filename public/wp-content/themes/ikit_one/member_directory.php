<?php
/**
 * Template Name: Member Directory
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

$members =  ikit_member_get_members();

?>

<div class="page-<?php echo $ikit_section->post_name; ?>">

<div class="box-container">
<div class="box">

<div class="box-top-empty-spacer"></div>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<?php
ikit_one_render_banner_header($ikit_section_id, null, $post->post_title);
?>


<div class="wp-editor">
    <div class="editor-header3">
        The membership directory is available for individual and personal uses only. Accessing address information acknowledges the user's acceptance of the <a target="_blank" href="http://www.aiga.org/designer-directory/">conditions of use</a> prohibiting commercial use.
    </div>
</div>




<div class="box-section-wysiwyg-content wp-editor">

    <table class="box-section-data-table">
    <tr>
    <th>
    NAME
    </th>
    <th>
    COMPANY
    </th>
    <th>
    YEAR JOINED
    </th>
    </tr>

    <?php foreach($members as $member) { ?>

        <tr>
        <td>
            <div><a target="_blank" href="<?php echo ikit_member_profile_url($member); ?>"><?php echo $member->full_name; ?></a></div>

            <!-- Member type -->
            <?php
            $member_type_display_name = null;
            $member_type = $member->member_type;
            $member_type_display_name = ikit_member_type_display_name($member_type);
            if($member_type_display_name != null && $member_type != 'CONTR' && $member_type != 'SUP') {
                ?>
                <div class="box-section-title3 editor-ikit-section-color"><?php echo $member_type_display_name; ?></div>
                <?php
            }

            ?>

        </td>
        <td>
            <?php
            if(isset($member->company)) {
                echo strip_tags($member->company);
            }
            ?>
        </td>
        <td>
            <?php echo ikit_member_join_year($member); ?>
        </td>
        </tr>

    <?php } ?>

    </table>

</div>

<?php endwhile; ?>

</div>
</div>



<div class="box-close"></div>



</div>

<?php get_sidebar();?>
<?php get_footer();?>