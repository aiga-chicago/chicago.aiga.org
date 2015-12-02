<?php
/**
 * Template Name: Membership Rates
 */
?>
<?php get_header();?>

<div class="page-header-3">
    <table>
    <tr>
        <td class="page-header-3-section-title">
            <?php ikit_two_render_toc_title($post); ?>
        </td>
        <td class="page-header-3-title"></td>
    </tr>
    </table>
</div>

<table class="page-layout-4">
<tr>

    <td class="page-layout-4-tools">
        <div class="cat-plugin-fluid-grid grid"
            cat_plugin_fluid_grid_layout_mode="fitRows"
            cat_plugin_fluid_grid_breakpoint_body_size_num_cols="<?php echo IKIT_TWO_PAGE_LAYOUT_4_TOOLS_FLUID_GRID_BREAKPOINT_BODY_SIZE_NUM_COLS; ?>"
            cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
            cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
        >
            <?php if(ikit_two_has_toc($post)) {?>
            <div class="cat-plugin-fluid-grid-item grid-item">
                <div class="grid-item-inner">
                    <div class="page-layout-4-tool">
                        <?php ikit_two_render_toc($post, 'page-layout-4-tool'); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>

        <div class="page-layout-4-tools-footer"></div>

    </td>

    <td class="page-layout-4-content">

        <table>
        <tr>
        <td class="page-layout-4-body">

            <div class="page-layout-body-pad">
                <div class="page-layout-body-paragraph-text wp-editor">

                    <p>
                    No matter who you are, you&rsquo;re one of us.<BR/>
                    <BR/>
                    As the largest professional association of designers in the world, AIGA is committed to advancing the value and impact of design, both locally and globally, and working together to inspire, support and learn from each other, at every stage of our careers.<BR/>
                    <BR/>
                    Together we can do amazing things.
                    </p>

                    <div class="editor-header21">Choose the level that's right for you</div>

                    <p>
                    About half of AIGA&rsquo;s funding comes directly from membership dues, so the participation of every member really counts. Whether it&rsquo;s a $50 contribution or a $500 Leader membership, the support of our members makes everything we do possible. And in turn, every participation level offers members not only a vast range of tangible benefits but some intangible ones as well &mdash; which can be some of the most meaningful aspects of being an AIGA member.
                    </p>

                    <div class="cat-plugin-fluid-grid grid"
                        cat_plugin_fluid_grid_layout_mode="fitRows"
                        cat_plugin_fluid_grid_breakpoint_body_size_num_cols="5,5,1,1"
                        cat_plugin_fluid_grid_breakpoint_body_size_classes="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_SIZE_CLASSES; ?>"
                        cat_plugin_fluid_grid_breakpoint_body_class="<?php echo IKIT_TWO_FLUID_GRID_BREAKPOINT_BODY_CLASS; ?>"
                    >

                        <div class="cat-plugin-fluid-grid-item grid-item rate-box rate-box-contributing">
                        <div class="grid-item-inner">
                            <div class="rate-box-inner">
                                <div class="rate-box-title">CONTRIBUTING</div>
                                <div class="rate-box-price">$50/YEAR</div>
                                <div class="rate-box-body">
                                    If you&rsquo;re a student, just starting out, or maybe you&rsquo;re a design fan who wants to support the design profession and what AIGA is doing, this is a great membership level to begin with. We&rsquo;d love to have you!
                                </div>
                                <div class="rate-box-link">
                                    <div class="box-button-container box-button-1-container"><a class="box-button-1" target="_blank" href="http://www.aiga.org/join/">Join Today</a></div>
                                </div>
                            </div>
                        </div>
                        </div>

                        <div class="cat-plugin-fluid-grid-item grid-item rate-box rate-box-supporting">
                        <div class="grid-item-inner">
                            <div class="rate-box-inner">
                                <div class="rate-box-title">SUPPORTING</div>
                                <div class="rate-box-price">$150/YEAR</div>
                                <div class="rate-box-body">
                                    If you&rsquo;re a junior designer with a few years of experience, a dedicated fan, or an educator with limited means, this membership level may be right for you. We want you on our team!
                                </div>
                                <div class="rate-box-link">
                                    <div class="box-button-container box-button-1-container"><a class="box-button-1" target="_blank" href="http://www.aiga.org/join/">Join Today</a></div>
                                </div>
                            </div>
                        </div>
                        </div>

                        <div class="cat-plugin-fluid-grid-item grid-item rate-box rate-box-sustaining">
                        <div class="grid-item-inner">
                            <div class="rate-box-inner">
                                <div class="rate-box-title">SUSTAINING</div>
                                <div class="rate-box-price">$250/YEAR</div>
                                <div class="rate-box-body">
                                    You&rsquo;ve been at it for a while, are committed to your role as a designer, and you&rsquo;d like to invest in the future of the design profession in a more substantial way. This is the level for you. Let&rsquo;s do this!

                                </div>
                                <div class="rate-box-link">
                                    <div class="box-button-container box-button-1-container"><a class="box-button-1" target="_blank" href="http://www.aiga.org/join/">Join Today</a></div>
                                </div>
                                </div>
                        </div>
                        </div>

                        <div class="cat-plugin-fluid-grid-item grid-item rate-box rate-box-leader">
                        <div class="grid-item-inner">
                            <div class="rate-box-inner">
                                <div class="rate-box-title">LEADER</div>
                                <div class="rate-box-price">$500/YEAR</div>
                                <div class="rate-box-body">
                                    You&rsquo;ve earned your place as a leader of the design profession and are serious about your commitment to the growth of design. Reflect your seriousness in your level of support, and help lead AIGA forward.
                                </div>
                                <div class="rate-box-link">
                                    <div class="box-button-container box-button-1-container"><a class="box-button-1" target="_blank" href="http://www.aiga.org/join/">Join Today</a></div>
                                </div>
                            </div>
                        </div>
                        </div>

                        <div class="cat-plugin-fluid-grid-item grid-item rate-box rate-box-trustee">
                        <div class="grid-item-inner">
                            <div class="rate-box-inner">
                                <div class="rate-box-title">TRUSTEE</div>
                                <div class="rate-box-price">$2,500/YEAR</div>
                                <div class="rate-box-body">
                                    You are a titan of the design industry and a true inspiration to all of us. Show your committment to AIGA and everything that we stand for in the most profound way.
                                </div>
                                <div class="rate-box-link">
                                    <div class="box-button-container box-button-1-container"><a class="box-button-1" target="_blank" href="http://www.aiga.org/join/">Join Today</a></div>
                                </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div class="editor-header21">Group memberships</div>

                    <p>Save by establishing a <a target="_blank" href="http://www.aiga.org/membership-group/">group membership</a> for your firm: A group rate of $675 applies to the first group of three memberships at the Sustaining level from the same company; additional employees may be added at $75 per member (all prices in U.S. dollars). There&rsquo;s no reason not to include your entire design team at these rates! <a target="_blank" href="http://www.aiga.org/membership-group/">Learn more</a> about how to enroll or renew.</p>

                    <div class="editor-header21">Billing options</div>

                    <p>At the Supporter level and above, you can pay for your annual membership in monthly installments. Regardless of the level you select, you can select the convenient &ldquo;auto-renewal&rsquo; option so that your membership continues each year without interruption. If one of these billing options is selected, you will be required to maintain your credit card information on file to remain eligible.</p>

                    <div class="editor-header21">Membership policies</div>

                    <p>
                    Individual memberships are annual and non-transferable.
                    </p>

                    <p>
                    The option of monthly payments is designed to make AIGA membership more accessible; it represents a commitment to a full year&rsquo;s membership, i.e., 12 payments over the course of a year. If all payments are not made (e.g., if a credit card expires and is not updated), your membership will be suspended and the option to select monthly payments will not be available to you in the future.
                    </p>

                    <p>
                    No refunds are available for membership dues.
                    </p>

                    <div class="editor-header21">QUESTIONS</div>

                    <p>See the <a target="_blank" href="http://www.aiga.org/membership-faqs/">Membership FAQs</a> for more information about setting up your account. Still have questions about membership? Call 212 807 1990 or <a target="_blank" href="http://www.aiga.org/contact.aspx?sel=membership/log-inissues">send us an email</a>.</p>

                </div>
            </div>

        </td>

        </tr>
        </table>

    </td>

</tr>
</table>




<?php get_footer();?>