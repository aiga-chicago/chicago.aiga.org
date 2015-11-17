<?php
/**
 * Locks particular pages and posts specific to a Ikit installation,
 * locking works by adding a bit of Javascript that just removes the
 * update button and adds a warning message that this post is locked
 */

function ikit_locking() {



}

function ikit_locking_admin_notices() {

    global $g_options;
    if($g_options[IKIT_PLUGIN_OPTION_LOCKING_DISABLED] == 0) {

        $posts = array();
        $page_type = null;

        if(isset($_GET['post'])) {
            $post = get_post(absint($_GET['post']));
            array_push($posts, $post);
            $page_type = 'single';
        }
        else if(isset($_GET['post_type'])) {
            $args=array(
              'post_type' => $_GET['post_type']
            );
            $posts = get_posts($args);
            $page_type = 'multiple';
        }

        foreach($posts as $post) {

            $locked = false;

            // If the post has an external source, we shouldn't allow editing,
            // as any editing will be overwritten anyway, the next time the external source updates
            if(get_post_meta($post->ID, IKIT_CUSTOM_FIELD_GENERIC_EXTERNAL_SOURCE) != null) {
                if($post->post_type != IKIT_POST_TYPE_IKIT_POST_EXTERNAL && $post->post_type != IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL) {
                    $locked = true;
                }
            }

            // If the page is a fundamental structural page, we shouldn't allow editing
            if($post->post_name == IKIT_SLUG_PAGE_NEWS ||
                $post->post_name == IKIT_SLUG_PAGE_EVENTS ||
                $post->post_name == IKIT_SLUG_PAGE_EVENTS_PAST ||
                $post->post_name == IKIT_SLUG_PAGE_ABOUT_US ||
                $post->post_name == IKIT_SLUG_PAGE_JOBS ||
                $post->post_name == IKIT_SLUG_PAGE_PORTFOLIOS ||
                $post->post_name == IKIT_SLUG_PAGE_GET_INVOLVED ||
                $post->post_name == IKIT_SLUG_PAGE_MEMBERSHIP ||
                $post->post_name == IKIT_SLUG_PAGE_RESOURCES ||
                $post->post_name == IKIT_SLUG_PAGE_IKIT_FEED ||
                $post->post_name == IKIT_SLUG_PAGE_MEMBER_DIRECTORY ||
                $post->post_type == IKIT_POST_TYPE_IKIT_SECTION ||
                $post->post_type == IKIT_POST_TYPE_IKIT_NATIONAL_FEED
                ) {

                $locked = true;

            }

            if($locked) {
            ?>

            <?php if($page_type == 'single') { ?>
                <div class="updated page-locked-notice"><p>This page is read-only</p></div>
                <script type="text/javascript">

                    $(document).ready(function() {
                        $('.postbox').hide();
                    });

                </script>
            <?php } elseif($page_type = 'multiple') { ?>

                <script type="text/javascript">

                    $(document).ready(function() {
                        $('#post-<?php echo $post->ID; ?>').find('.editinline, .trash').css('visibility', 'hidden').css('position', 'absolute');
                    });

                </script>

            <?php } ?>

            <?php
            }

        }

    }

}

add_action( 'admin_notices', 'ikit_locking_admin_notices' );
add_action( 'admin_init', 'ikit_locking');


?>