<?php
/**
 * Template Name: Events
 */
?>
<?php get_header();?>

<script type="text/javascript">

jQuery.events = function() {

};

jQuery.events.onDomReady = function() {

    $('.type-filter').click(function() {
        var urlParams = {};
        jQuery.events.updateUrlParams(urlParams);
        urlParams['type'] = $(this).attr('value');
        window.location.href = '?' + jQuery.param(urlParams);
    });

    $('.view-filter').click(function() {
        var urlParams = {};
        jQuery.events.updateUrlParams(urlParams);
        urlParams['view'] = $(this).attr('value');
        window.location.href = '?' + jQuery.param(urlParams);
    });

    $('.category-filter').click(function() {
        var urlParams = {};
        jQuery.events.updateUrlParams(urlParams);
        urlParams['category'] = $(this).attr('value');
        window.location.href = '?' + jQuery.param(urlParams);
    });

};

jQuery.events.updateUrlParams = function(urlParams) {

    <?php if(empty($_GET['view']) == false) { ?>
        urlParams['view'] = '<?php echo $_GET['view']; ?>';
    <?php } ?>

    <?php if(empty($_GET['type']) == false) { ?>
        urlParams['type'] = '<?php echo $_GET['type']; ?>';
    <?php } ?>

    <?php if(empty($_GET['category']) == false) { ?>
        urlParams['category'] = '<?php echo $_GET['category']; ?>';
    <?php } ?>

};

jQuery.events.onStartDomReady = function() {
    jQuery.events.calendar.onStartDomReady();
    jQuery.events.list.onStartDomReady();
};

jQuery.events.onEndDomReady = function() {
    jQuery.events.list.onEndDomReady();
    jQuery.events.calendar.onEndDomReady();
};

jQuery.events.onWindowResize = function() {
    jQuery.events.calendar.onWindowResize();
    jQuery.events.list.onWindowResize();
};

jQuery.ikit_two.onStartDomReadyFunctions.push(jQuery.events.onStartDomReady);
jQuery.ikit_two.onEndDomReadyFunctions.push(jQuery.events.onEndDomReady);

$(window).resize(function() {
    jQuery.events.onWindowResize();
});

$(document).ready(function() {
    jQuery.events.onDomReady();
});

/* Calendar */

jQuery.events.calendar = function() {

};

jQuery.events.calendar.onStartDomReady = function() {

};

jQuery.events.calendar.onWindowResize = function() {

    jQuery.events.calendar.layout();

};

jQuery.events.calendar.onEndDomReady = function() {

    jQuery.events.calendar.updateTitle();

    $('.events-calendar-next').click(function() {

        var calendarMonth = parseInt($('.page-layout-4-content .data').attr('month'));
        var calendarYear = parseInt($('.page-layout-4-content .data').attr('year'));

        if(calendarMonth == 12) {
            calendarYear++;
            calendarMonth = 1;
        }
        else {
            calendarMonth++;
        }

        var data = {month: calendarMonth, year: calendarYear};
        jQuery.events.calendar.fetch(data);

    });

    $('.events-calendar-prev').click(function() {

        var calendarMonth = parseInt($('.page-layout-4-content .data').attr('month'));
        var calendarYear = parseInt($('.page-layout-4-content .data').attr('year'));

        if(calendarMonth == 1) {
            calendarMonth = 12;
            calendarYear--;
        }
        else {
            calendarMonth--;
        }


        var data = {month: calendarMonth, year: calendarYear};
        jQuery.events.calendar.fetch(data);

    });

    jQuery.events.calendar.layout();
    jQuery.cat.plugin.anystretchImage.layout();

};

jQuery.events.calendar.updateTitle = function() {

    $('.events-calendar-title').text($('.page-layout-4-content .data').attr('name'));

};

jQuery.events.calendar.fetch = function(data) {

    jQuery.events.updateUrlParams(data);
    data['action'] = 'ikit_two_ajax_render_events_calendar';

    $.ajax({
        type : "POST",
        url : "<?php echo IKIT_AJAX_URL; ?>",
        data: data,
        success : function(response) {

            $('.events-calendar-content').html(response);
            jQuery.events.calendar.updateTitle();
            jQuery.events.calendar.layout();
            jQuery.cat.plugin.anystretchImage.layout();

        }
    });

};

jQuery.events.calendar.layout = function() {

    // Widths may not be identical across days, so we need to get the maximum width and use that
    var columnWidth = 0;
    $('.events-calendar-day-event').each(function() {
        columnWidth = Math.max(columnWidth, $(this).width());
    });

    $('.events-calendar-week').each(function() {

        // Find the max height of a single row
        var rowHeight = columnWidth;
        $(this).find('.events-calendar-day-events').each(function() {
            var eventEls = $(this).find('.events-calendar-day-event');
            rowHeight = Math.max(rowHeight, eventEls.length * (columnWidth/2));
        });

        // Set the heights of the events within the calendar with only 1 item
        $(this).find('.events-calendar-day-events').each(function() {
            var eventEls = $(this).find('.events-calendar-day-event');
            eventEls.height(rowHeight/eventEls.length);
        });

    });

    // Assign tooltip
    $('.events-calendar-day-event-image-link').each(function() {
        var infoEl = $(this).parent().find('.events-calendar-day-event-info');
        $(this).tooltipster({
            content: infoEl.html(),
            contentAsHTML: true,
            autoClose: true
        });
    });

};

/* List */

jQuery.events.list = function() {

};

jQuery.events.list.layout = function() {
    // Update the image column to match the widget item size so lines up with the footer
    $('.event-item-image-col').width(jQuery.ikit_two.grid.getItemWidth());
};

jQuery.events.list.onStartDomReady = function() {

    jQuery.ikit_two.infinityFetcher.contentContainer = $('.infinity-fetcher-content');
    jQuery.ikit_two.infinityFetcher.fetchButton = $('.infinity-fetcher-fetch-button');
    jQuery.ikit_two.infinityFetcher.fetchingUrl = "<?php echo IKIT_AJAX_URL; ?>";
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['action'] = 'ikit_two_ajax_render_events';
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['type'] = "<?php echo $_GET['type']; ?>";
    jQuery.ikit_two.infinityFetcher.fetchingUrlData['category'] = "<?php echo $_GET['category']; ?>";

    // Hide fetch button if no more pages
    if(parseInt($('.infinity-fetcher-content .data').attr('num_pages')) <= parseInt($('.infinity-fetcher-content .data').attr('page'))) {
        $('.infinity-fetcher-fetch-button').hide();
    }

};

jQuery.events.list.onWindowResize = function() {
    jQuery.events.list.layout();
};

jQuery.events.list.onEndDomReady = function() {
    jQuery.events.list.layout();
};

jQuery.events.list.afterFetchFinished = function(response, page, numPages) {
    jQuery.cat.plugin.anystretchImage.layout();
    jQuery.events.list.layout();
};

jQuery.events.list.afterAppendFunction = function(response) {

    // Do a simple fade in when new elements are appended
    response.hide();
    response.fadeIn();

};

jQuery.ikit_two.infinityFetcher.afterAppendFunctions.push(jQuery.events.list.afterAppendFunction);
jQuery.ikit_two.infinityFetcher.afterFetchFinishedFunctions.push(jQuery.events.list.afterFetchFinished);

</script>

<div class="page-header-3">
    <table>
    <tr>
        <td class="page-header-3-section-title">

            <?php
            $section_title = 'Events';

            if($_GET['type'] == IKIT_TWO_EVENTS_TYPE_AIGA) {
                $section_title = 'AIGA Events';
            }
            if($_GET['type'] == IKIT_TWO_EVENTS_TYPE_COMMUNITY) {
                $section_title = 'Community Events';
            }
            ?>

            <a href="<?php echo ikit_get_request_url(false); ?>"><?php echo $section_title; ?></a>
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

            <!-- Type filter -->
            <?php

            $show_type_filter = false;

            $args = array();
            $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT_INTERNAL);
            $args['post_status'] = 'publish';
            $args['posts_per_page'] = 1;
            $args['page'] = 1;
            $args['meta_query'] = array(

                array(
                'key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE,
                'value' => date_i18n("Y-m-d"), 'compare' => '>=',
                'type' => 'DATE'),

            );

            if(count(get_posts($args)) > 0) {
                $show_type_filter = true;
            }

            ?>

            <?php if($show_type_filter) { ?>

            <div class="cat-plugin-fluid-grid-item grid-item">
                <div class="grid-item-inner">
                    <div class="page-layout-4-tool">
                        <div class="page-layout-4-tool-header">Event type</div>
                        <div class="page-layout-4-tool-filters">
                        <div class="page-layout-4-tool-filter type-filter <?php if($_GET['type'] == null) { ?>active<?php } ?>" value="">All</div>
                        <div class="page-layout-4-tool-filter type-filter <?php if($_GET['type'] == IKIT_TWO_EVENTS_TYPE_AIGA) { ?>active<?php } ?>" value="<?php echo IKIT_TWO_EVENTS_TYPE_AIGA; ?>">AIGA</div>
                        <div class="page-layout-4-tool-filter type-filter <?php if($_GET['type'] == IKIT_TWO_EVENTS_TYPE_COMMUNITY) { ?>active<?php } ?>" value="<?php echo IKIT_TWO_EVENTS_TYPE_COMMUNITY; ?>">Community</div>
                        </div>
                    </div>
                </div>
            </div>

            <?php } ?>

            <div class="cat-plugin-fluid-grid-item grid-item">
                <div class="grid-item-inner">
                    <div class="page-layout-4-tool-header">View</div>
                    <div class="page-layout-4-tool">
                        <div class="view-filter" value="list">
                            <img
                                <?php if($_GET['view'] == null || $_GET['view'] == 'list') { ?>
                                    src="<?php bloginfo('template_url'); ?>/images/button_event_list_hl@2x.png"
                                <?php } else { ?>
                                    class="rollover-image" src="<?php bloginfo('template_url'); ?>/images/button_event_list@2x.png"
                                <?php } ?>
                            />
                        </div>
                        <div class="view-filter" value="calendar">
                            <img
                                <?php if($_GET['view'] == 'calendar') { ?>
                                    src="<?php bloginfo('template_url'); ?>/images/button_event_calendar_hl@2x.png"
                                <?php } else { ?>
                                    class="rollover-image" src="<?php bloginfo('template_url'); ?>/images/button_event_calendar@2x.png"
                                <?php } ?>
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="cat-plugin-fluid-grid-item grid-item category-filters">
                <div class="grid-item-inner">
                    <div class="page-layout-4-tool">
                        <div class="page-layout-4-tool-header">Categories</div>
                        <div class="page-layout-4-tool-filters">
                        <div class="page-layout-4-tool-filter category-filter <?php if($_GET['category'] == null) { ?>active<?php } ?>" value="">All</div>
                        <?php
                        $args = array();
                        $args['type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL, IKIT_POST_TYPE_IKIT_EVENT_INTERNAL);
                        $categories = ikit_get_categories_for_post_type(array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL, IKIT_POST_TYPE_IKIT_EVENT_INTERNAL), $args);
                        foreach($categories as $category) {
                            ?>
                            <div class="page-layout-4-tool-filter category-filter <?php if($_GET['category'] == $category->slug) { ?>active<?php } ?>" value="<?php echo $category->slug; ?>"><?php echo $category->name; ?></div>
                            <?php
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="page-layout-4-tools-footer"></div>

    </td>

    <?php if($_GET['view'] != 'calendar') { ?>

        <td class="page-layout-4-content">

            <table>
            <tr>
            <td class="page-layout-4-body">
                <div class="page-layout-body-pad">
                <?php

                $data = array();
                $data['category'] = $_GET['category'];
                $data['type'] = $_GET['type'];
                $rendered = ikit_two_ajax('ikit_two_ajax_render_events', $data);

                $no_results = false;
                preg_match_all('/num_pages="([0-9]+)"/i', $rendered, $matches);
                if(intval($matches[1][0]) <= 0) {
                    $no_results = true;
                }
                ?>

                <?php if(empty($rendered) == false && $no_results == false) { ?>
                    <div class="infinity-fetcher-content">
                        <?php echo $rendered; ?>
                    </div>
                <?php } else { ?>
                    <div class="cat-plugin-fluid-grid-item grid-item grid-item-empty">
                        <div class="grid-item-inner">
                            No events found.
                        </div>
                    </div>
                <?php } ?>
                </div>
            </td>
            <td class="page-layout-4-sidebar">
                <?php dynamic_sidebar('events-sidebar');?>
            </td>
            </tr>
            </table>

        </td>

    <?php } else { ?>

        <td class="page-layout-4-content">

            <table>
            <tr>
            <td class="page-layout-4-body">

            <?php

            $data = array();
            $data['category'] = $_GET['category'];
            $data['type'] = $_GET['type'];
            $rendered = ikit_two_ajax('ikit_two_ajax_render_events_calendar', $data);
            ?>

            <?php if(empty($rendered) == false) { ?>

                <div class="events-calendar-header clearfix">
                    <div class="events-calendar-title"></div>
                    <div class="events-calendar-pager">
                        <div class="events-calendar-prev"><a href="javascript:void(0);"><img class="rollover-image" src="<?php bloginfo('template_url'); ?>/images/button_calendar_previous@2x.png"/></a></div>
                        <div class="events-calendar-next"><a href="javascript:void(0);"><img class="rollover-image" src="<?php bloginfo('template_url'); ?>/images/button_calendar_next@2x.png"/></a></div>
                    </div>
                </div>
                <div class="events-calendar-content">
                    <?php echo $rendered; ?>
                </div>

            <?php } ?>

            </td>
            </tr>
            </table>


    <?php } ?>

</tr>
</table>

<?php if($_GET['view'] != 'calendar') { ?>

    <div class="box-button-container box-button-1-container page-layout-4-loader">
        <a href="javascript:void(0);" class="box-button-1 infinity-fetcher-fetch-button">
            <span>Load More</span>
            <div class="css-loader">
                <span class="css-loader-fallback">LOADING...</span>
            </div>
        </a>
    </div>

<?php } ?>

<?php get_footer();?>