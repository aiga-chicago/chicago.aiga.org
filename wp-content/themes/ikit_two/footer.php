<?php
/**
 * The template for displaying the footer.
 */
?>

<?php

global $g_main_nav_menu_items;
global $g_national_sponsors;
global $g_local_sponsors_short;
global $g_options;

?>

<!-- End content -->
</div>

<div class="footer">

    <div class="footer-inner">

        <div class="cat-plugin-fluid-grid grid"

            cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
            cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
            cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
        >

            <div class="cat-plugin-fluid-grid-item grid-item footer-copyright">
                <div class="grid-item-inner">

                <ul class="footer-list">
                <li>
                &copy;2015, All Rights Reserved.<BR/>
                <a target="_blank" href="http://aiga.org">AIGA | the professional association for design</a><BR/>
                </li>
                <li>
                <a target="_blank" href="http://www.aiga.org/insight--how-is-AIGA-investing-in-chapters/">iKit</a> Design & Development: <a target="_blank" href="http://winfieldco.com">W&Co.</a><BR/>
                Powered by <a target="_blank" href="http://wordpress.org">Wordpress</a>
                </li>
                </ul>

                </div>
            </div>

            <div class="cat-plugin-fluid-grid-item grid-item footer-partners">
                <div class="grid-item-inner">

                <div class="footer-header">iKit Technology Partners</div>
                <ul class="footer-list">
                <li class="footer-partner-disqus">
                <a target="_blank" href="http://www.disqus.com"><img src="<?php bloginfo('template_url'); ?>/images/disqus@2x.png"/></a>
                </li>
                <li class="footer-partner-mailchimp">
                <a target="_blank" href="http://www.mailchimp.com"><img src="<?php bloginfo('template_url'); ?>/images/mailchimp@2x.png"/></a>
                </li>
                </ul>

                </div>
            </div>

            <!-- National sponsors -->
            <div class="cat-plugin-fluid-grid-item grid-item footer-sponsors">
                <div class="grid-item-inner">

                <div class="footer-header">Official Sponsor</div>
                <ul class="footer-list">
                <?php
                $sponsor_count = 0;
                foreach($g_national_sponsors as $sponsor) { ?>

                    <?php
                    $attachment_image_src = wp_get_attachment_image_src(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_SECONDARY_IMAGE, true), 'full')
                    ?>

                    <li><a target="_blank" href="<?php echo get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL, true);?>"><img width="<?php echo round($attachment_image_src[1]/2); ?>px" src="<?php echo $attachment_image_src[0]; ?>"/></a></li>
                <?php
                $sponsor_count++;
                } ?>

                </ul>

                </div>
            </div>

            <!-- Local sponsors -->
            <?php if(count($g_local_sponsors_short) > 0) { ?>
            <div class="cat-plugin-fluid-grid-item grid-item footer-local-sponsors">
                <div class="grid-item-inner">

                <div class="footer-header">Local Sponsors</div>
                <ul class="footer-list">
                <?php
                $sponsor_count = 0;
                foreach($g_local_sponsors_short as $sponsor) { ?>

                    <?php
                    $attachment_image_src = wp_get_attachment_image_src(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_SECONDARY_IMAGE, true), 'full')
                    ?>

                    <li class="footer-sponsor"><a target="_blank" href="<?php echo get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL, true);?>"><img width="<?php echo round($attachment_image_src[1]/2); ?>px" src="<?php echo $attachment_image_src[0]; ?>"/></a></li>
                <?php
                $sponsor_count++;
                } ?>
                </ul>

                </div>
            </div>
            <?php } ?>

            <div class="cat-plugin-fluid-grid-item grid-item footer-social">
                <div class="grid-item-inner">

                <div class="footer-header">Find Us Online</div>
                <div class="footer-social-links">
                    <?php ikit_render_social_links(ikit_get_page_permalink_by_slug(IKIT_SLUG_PAGE_IKIT_FEED)); ?>
                </div>

                <?php
                $mailchimp_signup_form_url = $g_options[IKIT_PLUGIN_OPTION_MAILCHIMP_SIGNUP_FORM_URL];

                if(empty($mailchimp_signup_form_url) == false) {
                ?>
                <div class="footer-mailing-list">

                    <div class="footer-header">Join Our Mailing List</div>

                    <form action="<?php echo $mailchimp_signup_form_url; ?>" method="post">
                    <div>
                        <table>
                        <tr>
                        <td>
                            <input class="required email" type="text" value="" name="EMAIL" id="mce-EMAIL">
                        </td>
                        <td>
                        <div class="footer-mailing-list-submit custom-submit">Submit</div>
                        </td>
                        </tr>
                        </table>
                    </div>
                    </form>

                </div>

                <?php } ?>

                </div>
            </div>

        </div>

    </div>

</div>

<!-- End layout -->
</div>

<?php
wp_footer();
?>

</div>
</div>

<div class="page-loader-dialog">

<div id="page-loader-indicator" class="css-loader">
    <span class="css-loader-fallback">LOADING...</span>
</div>

</div>
<div class="unsupported-browser-dialog"><strong>Your browser is too old to view this site.</strong><BR/><BR/>Do yourself a favor and upgrade it to the latest version.</div>

</body>

</html>