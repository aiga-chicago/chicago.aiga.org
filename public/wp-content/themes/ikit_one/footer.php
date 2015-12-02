<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Internet_Kit
 * @since Internet Kit One 1.0.0
 */
?>

<div class="layout-content-close"></div>

<!-- End layout content -->
</div>

<!-- End layout main -->
</div>

<!-- End layout main container -->
</div>

<div class="layout-footer-container">

    <?php

    global $g_main_nav_menu_items;
    global $g_national_sponsors;
    global $g_local_sponsors_short;
    global $g_options;

    ?>

    <div class="layout-footer">

        <div class="layout-footer-content">

            <div class="layout-footer-col0-container">

                <div class="layout-footer-col0">

                    <div>
                        &copy;2015, All Rights Reserved.<BR/>
                        <a target="_blank" href="http://aiga.org">AIGA</a> | the professional<BR/>
                        association for design
                    </div>

                    <div class="layout-footer-rule"></div>

                    <div>
                        <a target="_blank" href="http://www.aiga.org/insight--how-is-AIGA-investing-in-chapters/">iKit</a> Design &amp; Development<BR/>
                        <a target="_blank" href="http://www.winfieldco.com/">W&amp;Co.</a>
                    </div>

                    <div class="layout-footer-rule"></div>

                    <div>
                        Technology Partners<BR/>
                        <div class="layout-footer-disqus"><a target="_blank" href="http://www.disqus.com"><img src="<?php bloginfo('template_url'); ?>/images/disqus.png"/></a></div>
                        <div class="layout-footer-mailchimp"><a target="_blank" href="http://www.mailchimp.com"><img src="<?php bloginfo('template_url'); ?>/images/mailchimp.png"/></a></div>
                    </div>

                    <div class="layout-footer-rule"></div>

                    <div>
                        Powered by<BR/>
                        <a target="_blank" href="http://wordpress.org/">WordPress</a>
                    </div>

                </div>

            </div>

            <div class="layout-footer-col1-container">

                <div class="layout-footer-col1">

                    <?php $count = 0; ?>
                    <?php foreach($g_main_nav_menu_items as $main_nav_menu_item) { ?>

                        <a href="<?php echo $main_nav_menu_item->url; ?>"><?php echo $main_nav_menu_item->title; ?></a>

                        <!-- Do not append a footer to the last item -->
                        <?php if($count < count($g_main_nav_menu_items)-1) { ?>
                            <div class="layout-footer-rule"></div>
                        <?php } ?>

                        <?php $count++; ?>

                    <?php } ?>

                </div>

            </div>

            <div class="layout-footer-col2-container">

                <div class="layout-footer-col2">

                    <?php echo do_shortcode('[ikit_page_content slug="national-links"]'); ?>
                    <BR/>
                    &nbsp;

                </div>

            </div>

            <div class="layout-footer-col3-container">

                <div class="layout-footer-col3">

                    <div class="layout-footer-social">

                        <div class="layout-footer-header">STAY CONNECTED</div>
                        <div class="layout-footer-social-links">
                            <?php ikit_render_social_links(ikit_get_page_permalink_by_slug(IKIT_SLUG_PAGE_IKIT_FEED)); ?>
                        </div>

                    </div>

                    <?php
                    $mailchimp_signup_form_url = $g_options[IKIT_PLUGIN_OPTION_MAILCHIMP_SIGNUP_FORM_URL];

                    if(empty($mailchimp_signup_form_url) == false) {
                    ?>

                    <div class="layout-footer-rule"></div>

                    <div class="layout-footer-mailing-list">
                        <div class="layout-footer-header">SIGN UP</div>

                        <form action="<?php echo $mailchimp_signup_form_url; ?>" method="post">
                        <div class="input-container">
                            <table>
                            <tr>
                            <td class="input-container-col0">
                            <input type="text" value="" placeholder="Email Address" name="EMAIL" class="required email" id="mce-EMAIL">
                            </td>
                            <td class="input-container-col1">
                            <input type="image" class="highlightable" src="<?php bloginfo('template_url'); ?>/images/footer_mailinglist.png"></input>
                            </td>
                            </tr>
                            </table>
                        </div>
                        </form>

                    </div>
                    <?php } ?>


                    <!-- National sponsors -->
                    <div class="layout-footer-rule"></div>

                    <div class="layout-footer-sponsors">

                        <div class="layout-footer-header">OFFICIAL SPONSOR</div>
                        <?php
                        $sponsor_count = 0;
                        foreach($g_national_sponsors as $sponsor) { ?>

                            <?php
                            $attachment_image_src = wp_get_attachment_image_src(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_SECONDARY_IMAGE, true), 'full')
                            ?>

                            <div class="layout-footer-sponsor <?php if($sponsor_count == count($g_national_sponsors)-1) { echo 'layout-footer-sponsor-last'; } ?>"><a target="_blank" href="<?php echo get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL, true);?>"><img width="<?php echo round($attachment_image_src[1]/2); ?>px" src="<?php echo $attachment_image_src[0]; ?>"/></a></div>
                        <?php
                        $sponsor_count++;
                        } ?>

                    </div>

                    <!-- Local sponsors -->
                    <?php if(count($g_local_sponsors_short) > 0) { ?>

                    <div class="layout-footer-rule"></div>

                    <div class="layout-footer-sponsors">

                        <div class="layout-footer-header">CHAPTER SPONSORS</div>
                        <?php
                        $sponsor_count = 0;
                        foreach($g_local_sponsors_short as $sponsor) { ?>
                            <div class="layout-footer-sponsor <?php if($sponsor_count == count($g_local_sponsors_short)-1) { echo 'layout-footer-sponsor-last'; } ?>"><a target="_blank" href="<?php echo get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_URL, true);?>"><img src="<?php echo wp_get_attachment_url(get_post_meta($sponsor->ID, IKIT_CUSTOM_FIELD_IKIT_SPONSOR_SECONDARY_IMAGE, true)); ?>" /></a></div>
                        <?php
                        $sponsor_count++;
                        } ?>

                    </div>

                    <?php } ?>

                </div>

            </div>

            <div class="layout-footer-close"></div>

        </div>

    </div>

</div>

<!-- End ikit one -->
</div>

<?php
wp_footer();
?>

</body>

</html>
