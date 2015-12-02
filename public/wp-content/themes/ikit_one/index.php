<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage Internet_Kit
 * @since Internet Kit One 1.0.0
 *
 */
?>

<?php get_header();?>

<script type="text/javascript">

<?php if($g_theme_options[IKIT_ONE_THEME_OPTION_FEATURED_IMAGE_GALLERY_MAX_HEIGHT_DISABLED]) { ?>

// If the max height is disabled, add a class to the flexslider which
// will disable the max height CSS
$(document).ready(function () {
    $(".slides img").addClass("cutoff-disabled");
    $(".flexslider").addClass("cutoff-disabled");
});

<?php } ?>

</script>


<?php
// Toggle the order of the news and the events modules on the homepage
if($g_theme_options[IKIT_ONE_THEME_OPTION_HOME_EVENTS_NEWS_ORDER_TOGGLE]==0) {
    include 'index-events.php';
    include 'index-news.php';
}
else{
    include 'index-news.php';
    include 'index-events.php';
}
?>







<?php get_sidebar();?>

<?php get_footer();?>