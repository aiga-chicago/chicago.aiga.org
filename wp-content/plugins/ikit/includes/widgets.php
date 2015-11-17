<?php
/**
 * @package Internet_Kit
 */

/**
 * Widgets available for the Internet Kit are instantiated here.
 * If you want a new widget be made available simply create one
 * and add it as a plugin, there is nothing special neccessary, follow
 * the instructions here to create a new widget.
 *
 * http://codex.wordpress.org/WordPress_Widgets
 *
 * If you don't need special functionality, just adding a Text Widget
 * should typically work. Just go straight to the admin interface
 * and try adding one.
 *
 */

function ikit_widgets_init() {

}

add_action('init', 'ikit_widgets_init');


/**
 * Enable shortcodes in the default text widget
 */
add_filter('widget_text', 'do_shortcode');

/**
 * Base iKit Widget
 */
class Ikit_Widget extends WP_Widget {

    public function widget( $args, $instance ) {

        $context = array();
        $this->render_context($args, $instance, $context);

        // Each theme should add a filter to determine whether or not each
        // widget should render. If returns true, the widget will render, if false it will not.
        $should_render = apply_filters('ikit_filter_widgets_should_render', null, get_class($this), $args, $instance, $context);

        if($should_render) {

            extract($args);
            if(has_action('ikit_action_widgets_render_before_widget')) {
                do_action('ikit_action_widgets_render_before_widget', get_class($this), $args, $instance, $context, $before_widget);
            }
            else {
                echo $before_widget;
            }

            // Render the widget
            $this->render($args, $instance, $context);

            // Each theme should add an action which will handle rendering of the widget
            // this keeps the model separate from the view and allows more flexibility
            // for the widget look and feel
            if(has_action('ikit_action_widgets_render')) {
                do_action('ikit_action_widgets_render', get_class($this), $args, $instance, $context);
            }

            if(has_action('ikit_action_widgets_render_after_widget')) {
                do_action('ikit_action_widgets_render_after_widget', get_class($this), $args, $instance, $context, $after_widget);
            }
            else {
                echo $after_widget;
            }

        }

    }

    public function render($args, $instance, $context) {
        // Each widget can define a render call in case the theme is not handling rendering
        // for things such as calendar widgets that are common amongst all themes
    }

    public function render_context($args, $instance, &$context) {
        // Each widget can define a render context for commonly used data to be rendered in the
        // widget, these will appear in the $context variable as associative array fields, this allows
        // the presentation layer to be separate from the model which will not be differ amongst themes

    }



}

/**
 * Social Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetSocial extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
             'ikit_widget_social', // Base ID
            'Ikit Widget Social', // Name
            array('classname' => 'ikit-widget-social', 'description' => 'Displays various social feeds as configured in iKit settings.')
        );
    }

    function form($instance) {

        $instance = wp_parse_args((array)$instance, array('show_facebook' => true, 'show_twitter' => true, 'show_vimeo' => true, 'show_flickr' => true, 'randomize_flickr' => false, 'randomize_vimeo' => false, 'num_twitter_messages' => '1'));
        $num_twitter_messages = esc_attr( $instance['num_twitter_messages'] );

        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'show_facebook' ); ?>"><?php _e('Display Facebook Feed:'); ?></label>
           <input class="checkbox" type="checkbox" <?php checked('1', $instance['show_facebook']); ?> value="1" id="<?php echo $this->get_field_id( 'show_facebook' ); ?>" name="<?php echo $this->get_field_name( 'show_facebook' ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'show_twitter' ); ?>"><?php _e('Display Twitter Feed:'); ?></label>
           <input class="checkbox" type="checkbox" <?php checked('1', $instance['show_twitter']); ?> value="1" id="<?php echo $this->get_field_id( 'show_twitter' ); ?>" name="<?php echo $this->get_field_name( 'show_twitter' ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'show_vimeo' ); ?>"><?php _e('Display Vimeo Feed:'); ?></label>
           <input class="checkbox" type="checkbox" <?php checked('1', $instance['show_vimeo']); ?> value="1" id="<?php echo $this->get_field_id( 'show_vimeo' ); ?>" name="<?php echo $this->get_field_name( 'show_vimeo' ); ?>" />
        </p>
        <label for="<?php echo $this->get_field_id( 'show_youtube' ); ?>"><?php _e('Display YouTube Feed:'); ?></label>
           <input class="checkbox" type="checkbox" <?php checked('1', $instance['show_youtube']); ?> value="1" id="<?php echo $this->get_field_id( 'show_youtube' ); ?>" name="<?php echo $this->get_field_name( 'show_youtube' ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'show_flickr' ); ?>"><?php _e('Display Flickr Feed:'); ?></label>
           <input class="checkbox" type="checkbox" <?php checked('1', $instance['show_flickr']); ?> value="1" id="<?php echo $this->get_field_id( 'show_flickr' ); ?>" name="<?php echo $this->get_field_name( 'show_flickr' ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'show_instagram' ); ?>"><?php _e('Display Instagram Feed:'); ?></label>
           <input class="checkbox" type="checkbox" <?php checked('1', $instance['show_instagram']); ?> value="1" id="<?php echo $this->get_field_id( 'show_instagram' ); ?>" name="<?php echo $this->get_field_name( 'show_instagram' ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'randomize_flickr' ); ?>"><?php _e('Randomize Flickr Feed:'); ?></label>
           <input class="checkbox" type="checkbox" <?php checked('1', $instance['randomize_flickr']); ?> value="1" id="<?php echo $this->get_field_id( 'randomize_flickr' ); ?>" name="<?php echo $this->get_field_name( 'randomize_flickr' ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'randomize_vimeo' ); ?>"><?php _e('Randomize Vimeo Feed:'); ?></label>
           <input class="checkbox" type="checkbox" <?php checked('1', $instance['randomize_vimeo']); ?> value="1" id="<?php echo $this->get_field_id( 'randomize_vimeo' ); ?>" name="<?php echo $this->get_field_name( 'randomize_vimeo' ); ?>" />
        </p>
        <label for="<?php echo $this->get_field_id( 'randomize_youtube' ); ?>"><?php _e('Randomize YouTube Feed:'); ?></label>
           <input class="checkbox" type="checkbox" <?php checked('1', $instance['randomize_youtube']); ?> value="1" id="<?php echo $this->get_field_id( 'randomize_youtube' ); ?>" name="<?php echo $this->get_field_name( 'randomize_youtube' ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'randomize_instagram' ); ?>"><?php _e('Randomize Instagram Feed:'); ?></label>
           <input class="checkbox" type="checkbox" <?php checked('1', $instance['randomize_instagram']); ?> value="1" id="<?php echo $this->get_field_id( 'randomize_instagram' ); ?>" name="<?php echo $this->get_field_name( 'randomize_instagram' ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('num_twitter_messages'); ?>">Number of tweets to display:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('num_twitter_messages'); ?>" name="<?php echo $this->get_field_name('num_twitter_messages'); ?>" type="text" value="<?php echo esc_attr($num_twitter_messages); ?>"/>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        $instance['show_facebook'] = ( $new_instance['show_facebook'] == 1 ? 1 : 0 );
        $instance['show_twitter'] = ( $new_instance['show_twitter'] == 1 ? 1 : 0 );
        $instance['show_vimeo'] = ( $new_instance['show_vimeo'] == 1 ? 1 : 0 );
        $instance['show_youtube'] = ( $new_instance['show_youtube'] == 1 ? 1 : 0 );
        $instance['show_instagram'] = ( $new_instance['show_instagram'] == 1 ? 1 : 0 );
        $instance['show_flickr'] = ( $new_instance['show_flickr'] == 1 ? 1 : 0 );
        $instance['randomize_flickr'] = ( $new_instance['randomize_flickr'] == 1 ? 1 : 0 );
        $instance['randomize_vimeo'] = ( $new_instance['randomize_vimeo'] == 1 ? 1 : 0 );
        $instance['randomize_youtube'] = ( $new_instance['randomize_youtube'] == 1 ? 1 : 0 );
        $instance['randomize_instagram'] = ( $new_instance['randomize_instagram'] == 1 ? 1 : 0 );
        $instance['num_twitter_messages'] = $new_instance['num_twitter_messages'];

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

       // Retrieve the feed items
        $twitter_messages = ikit_social_get_twitter_messages_rss_items();
        $vimeo_videos = ikit_social_get_vimeo_videos_rss_items();
        $flickr_images = ikit_social_get_flickr_images_rss_items();
        $facebook_status_messages = ikit_social_get_facebook_feed_items();
        $youtube_videos = ikit_social_get_youtube_videos_rss_items();
        $instagram_photos = ikit_social_get_instagram_feed_items();

        $context['twitter_messages'] = $twitter_messages;
        $context['vimeo_videos'] = $vimeo_videos;
        $context['flickr_images'] = $flickr_images;
        $context['facebook_status_messages'] = $facebook_status_messages;
        $context['youtube_videos'] = $youtube_videos;
        $context['instagram_photos'] = $instagram_photos;

        // Get the configuration for social feeds
        global $g_options;
        $vimeo_user_id = $g_options[IKIT_PLUGIN_OPTION_VIMEO_USER_ID];
        $twitter_username = $g_options[IKIT_PLUGIN_OPTION_TWITTER_USERNAME];
        $flickr_user_id = $g_options[IKIT_PLUGIN_OPTION_FLICKR_USER_ID];
        $facebook_id = $g_options[IKIT_PLUGIN_OPTION_FACEBOOK_ID];
        $youtube_username = $g_options[IKIT_PLUGIN_OPTION_YOUTUBE_USERNAME];
        $instagram_username = $g_options[IKIT_PLUGIN_OPTION_INSTAGRAM_USERNAME];

        $context['vimeo_user_id'] = $vimeo_user_id;
        $context['twitter_username'] = $twitter_username;
        $context['flickr_user_id'] = $flickr_user_id;
        $context['facebook_id'] = $facebook_id;
        $context['instagram_username'] = $youtube_username;
        $context['instagram_username'] = $instagram_username;

        // Generate the URLs for social feeds
        $twitter_url = 'http://www.twitter.com/' . $twitter_username;
        $facebook_url = 'http://www.facebook.com/' . $facebook_id;
        $flickr_url = 'http://www.flickr.com/' . $flickr_user_id;
        $vimeo_url = 'http://www.vimeo.com/' . $vimeo_user_id;
        $youtube_url = 'http://www.youtube.com/' . $youtube_username;
        $instagram_url = 'http://www.instagram.com/' . $instagram_username;

        $context['twitter_url'] = $twitter_url;
        $context['facebook_url'] = $facebook_url;
        $context['flickr_url'] = $flickr_url;
        $context['vimeo_url'] = $vimeo_url;
        $context['youtube_url'] = $youtube_url;
        $context['instagram_url'] = $instagram_url;

        $show_facebook = in_array('show_facebook', $instance) ? $instance['show_facebook'] : true;
        $show_twitter = in_array('show_twitter', $instance) ? $instance['show_twitter'] : true;
        $show_flickr = in_array('show_flickr', $instance) ? $instance['show_flickr'] : true;
        $show_vimeo = in_array('show_vimeo', $instance) ? $instance['show_vimeo'] : true;
        $show_youtube = in_array('show_youtube', $instance) ? $instance['show_youtube'] : true;
        $show_instagram = in_array('show_instagram', $instance) ? $instance['show_instagram'] : true;
        $randomize_flickr = in_array('randomize_flickr', $instance) ? $instance['randomize_flickr'] : true;
        $randomize_vimeo = in_array('randomize_vimeo', $instance) ? $instance['randomize_vimeo'] : true;
        $randomize_youtube = in_array('randomize_youtube', $instance) ? $instance['randomize_youtube'] : true;
        $randomize_instagram = in_array('randomize_instagram', $instance) ? $instance['randomize_instagram'] : true;
        $num_twitter_messages = intval($instance['num_twitter_messages']);
        if($num_twitter_messages <= 0) {
            $num_twitter_messages = 1;
        }

        $context['show_facebook'] = $show_facebook;
        $context['show_twitter'] = $show_twitter;
        $context['show_flickr'] = $show_flickr;
        $context['show_vimeo'] = $show_vimeo;
        $context['show_youtube'] = $show_youtube;
        $context['show_instagram'] = $show_instagram;
        $context['randomize_flickr'] = $show_instagram;
        $context['randomize_vimeo'] = $randomize_vimeo;
        $context['randomize_youtube'] = $randomize_youtube;
        $context['randomize_instagram'] = $randomize_instagram;
        $context['num_twitter_messages'] = $num_twitter_messages;

    }

}

function ikit_widgets_social_init() {
    return register_widget("Ikit_WidgetSocial");
}

add_action( 'widgets_init', 'ikit_widgets_social_init');



/**
 * Sponsors Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetSponsors extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
             'ikit_widget_sponsors', // Base ID
            'Ikit Widget Sponsors', // Name
            array('classname' => 'ikit-widget-sponsors', 'description' => 'Displays the national sponsors, these are automatically populated.')
        );
    }

    public function render_context($args, $instance, &$context) {
        global $g_national_sponsors;
        $context['national_sponsors'] = $g_national_sponsors;
    }

    function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array('title' => '') );
        $title = esc_attr( $instance['title'] );

        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <?php
    }

}

function ikit_widgets_sponsors_init() {
    return register_widget("Ikit_WidgetSponsors");
}

add_action( 'widgets_init', 'ikit_widgets_sponsors_init');


/**
 * Local Sponsors Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetLocalSponsors extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
             'ikit_widget_local_sponsors', // Base ID
            'Ikit Widget Local Sponsors', // Name
            array('classname' => 'ikit-widget-local-sponsors', 'description' => 'Displays the chapter sponsors. If there are more than 3 chapter sponsors, will show random chapter sponsors on each page request.')
        );
    }

    public function render_context($args, $instance, &$context) {
        global $g_local_sponsors_short;
        $context['local_sponsors_short'] = $g_local_sponsors_short;
    }

    function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array('title' => '') );
        $title = esc_attr( $instance['title'] );

        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <?php
    }

}

function ikit_widgets_local_sponsors_init() {
    return register_widget("Ikit_WidgetLocalSponsors");
}

add_action( 'widgets_init', 'ikit_widgets_local_sponsors_init');




/**
 * Sign Up Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetMailingList extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
             'ikit_widget_mailing_list', // Base ID
            'Ikit Widget Mailing List', // Name
            array('classname' => 'ikit-widget-mailing-list', 'description' => 'Allows website visitors to sign up for the chapter MailChimp mailing list. If no "MailChimp Signup URL" is defined in the iKit settings, this widget will not display.')
        );
    }

    public function render_context($args, $instance, &$context) {

        global $g_options;
        $mailchimp_signup_form_url = $g_options[IKIT_PLUGIN_OPTION_MAILCHIMP_SIGNUP_FORM_URL];
        $context['mailchimp_signup_form_url'] = $mailchimp_signup_form_url;

    }

    function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array( 'title' => 'Sign Up' ));
        $title = esc_attr( $instance['title'] );
        ?>
        <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>

        <?php
    }

}

function ikit_widgets_mailing_list_init() {
    return register_widget("Ikit_WidgetMailingList");
}

add_action( 'widgets_init', 'ikit_widgets_mailing_list_init');



/**
 * Featured Job Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetFeaturedJob extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
             'ikit_widget_featured_job', // Base ID
            'Ikit Widget Featured Job', // Name
            array('classname' => 'ikit-widget-featured-job', 'description' => 'Displays one or more featured job listings. To choose which jobs appear in this widget, assign jobs to the "Featured" category, otherwise random jobs will be pulled in.')
        );
    }

    public function render_context($args, $instance, &$context) {

        $num_results = intval($instance['num_results']);
        if($num_results <= 0) {
            $num_results = 1;
        }

        $args=array(
          'post_type' => IKIT_POST_TYPE_IKIT_JOB,
          'numberposts' => $num_results,
          'post_status' => 'publish',
          'category_name' => IKIT_SLUG_CATEGORY_FEATURED
        );


        $posts = get_posts($args);
        if(empty($posts)) { // If nothing featured pull random
             $args['category_name'] = null;
             $args['orderby'] = 'rand';
             $posts = get_posts($args);
        }

        $context['featured_jobs'] = $posts;

    }

    function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array( 'num_results' => '1' ));
        $num_results = esc_attr( $instance['num_results'] );
        ?>
        <p>
        <label for="<?php echo $this->get_field_id('num_results'); ?>">Number of jobs to display:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('num_results'); ?>" name="<?php echo $this->get_field_name('num_results'); ?>" type="text" value="<?php echo esc_attr($num_results); ?>"/>
        </p>

        <?php
    }

}

function ikit_widgets_featured_job_init() {
    return register_widget("Ikit_WidgetFeaturedJob");
}

add_action( 'widgets_init', 'ikit_widgets_featured_job_init');


/**
 * Featured Portfolio Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetFeaturedPortfolio extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
             'ikit_widget_featured_portfolio', // Base ID
            'Ikit Widget Featured Portfolio', // Name
            array('classname' => 'ikit-widget-featured-portfolio', 'description' => 'Displays one or more featured portfolio listings. To choose which portfolios appear in this widget, assign portfolios to the "Featured" category, otherwise random portfolios will be pulled in.')
        );
    }

    function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array( 'num_results' => '1' ));
        $num_results = esc_attr( $instance['num_results'] );
        ?>
        <p>
        <label for="<?php echo $this->get_field_id('num_results'); ?>">Number of portfolios to display:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('num_results'); ?>" name="<?php echo $this->get_field_name('num_results'); ?>" type="text" value="<?php echo esc_attr($num_results); ?>"/>
        </p>

        <?php
    }

    public function render_context($args, $instance, &$context) {

        $num_results = intval($instance['num_results']);
        if($num_results <= 0) {
            $num_results = 1;
        }

        $args=array(
          'post_type' => IKIT_POST_TYPE_IKIT_PORTFOLIO,
          'numberposts' => $num_results,
          'post_status' => 'publish',
          'category_name' => IKIT_SLUG_CATEGORY_FEATURED
        );

        $posts = get_posts($args);

        if(empty($posts)) { // If nothing featured pull random
             $args['category_name'] = null;
             $args['orderby'] = 'rand';
             $posts = get_posts($args);
        }

        $context['featured_portfolios'] = $posts;


    }

}

function ikit_widgets_featured_portfolio_init() {
    return register_widget("Ikit_WidgetFeaturedPortfolio");
}

add_action( 'widgets_init', 'ikit_widgets_featured_portfolio_init');

/**
 * Eye on Design Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetEyeOnDesign extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_widget_designenvy', // This should remain as _designenvy for existing widgets, as it used to be for Design Envy, but that blog is no longer active and has been succeeded by Eye on Design.
            'Ikit Widget Eye on Design', // Name
            array('classname' => 'ikit-widget-eyeondesign', 'description' => 'Displays the latest post from the AIGA Eye on Design blog.')
        );
    }

    public function render_context($args, $instance, &$context) {

        // Has been replaced with Eye on Design
        $eyeondesign_items = ikit_social_get_eyeondesign_rss_items();
        $context['eyeondesign_items'] = $eyeondesign_items;

    }

}

function ikit_widgets_eyeondesign_init() {
    return register_widget("Ikit_WidgetEyeOnDesign");
}

add_action( 'widgets_init', 'ikit_widgets_eyeondesign_init');





/**
 * Page Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetPage extends Ikit_Widget {
    function Ikit_WidgetPage() {

        parent::__construct(
             'ikit_widget_page', // Base ID
            'Ikit Widget Page', // Name
            array('classname' => 'ikit-widget-page', 'description' => 'Displays the contents of a page. Will automatically color the title banner of the widget based on the parent page.')
        );

    }

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Pages' ) : $instance['title']);
        $pageid = $instance['pageid'] = intval ($new_instance['pageid']);

        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

        $pageid = empty ($instance['pageid']) ? 0 : $instance['pageid'];
        $pages = get_pages("include=$pageid");

        if ($pageid && $pages) {

            $page = $pages[0];
            $context['page'] = $page;
        }

    }

    function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array( 'pageid' => '', 'title' => '') );

        // This is filled by the title of the page, so it will appear after the widget name
        $title = esc_attr( $instance['title'] );
        $pageid = esc_attr( $instance['pageid'] );

        // Get the list of pages and turn it into a select list
        $pages = wp_list_pages( apply_filters('widget_pages_args', array('title_li' => '', 'echo' => 0))); // List of pages with <li> tags
        $pages = preg_replace ('/<li class="\D*(\d+)">/', '<option value="\\1">', $pages);
        $pages = preg_replace ('/value="' . $pageid . '"/', '\\0 selected="yes"', $pages);
        $pages = preg_replace ('/<\/li>/', '</option>', $pages);
        ?>
        <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="hidden"
            value="<?php echo $title; ?>" style="visibility:hidden;" />
        <p><select class="widefat" id="<?php echo $this->get_field_id('pageid'); ?>" name="<?php echo $this->get_field_name('pageid'); ?>"
            onchange="this.form['<?php echo $this->get_field_name('title'); ?>'].value = this.options[this.selectedIndex].text;">
        <option value="0">choose a page</option><?= $pages?>
        </select>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <?php
    }

}

function ikit_widgets_page() {
    return register_widget("Ikit_WidgetPage");
}

add_action( 'widgets_init', 'ikit_widgets_page');



/**
 * Join AIGA Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetJoinAIGA extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
             'ikit_widget_join_aiga', // Base ID
            'Ikit Widget Join AIGA', // Name
            array('classname' => 'ikit-widget-join-aiga', 'description' => 'Displays a join AIGA promo image.')
        );
    }

    function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array( 'promo_image_id' => '' ));

        $promo_images = array(
            "0" => "We come in all shapes and sizes 1",
            "2" => "We come in all shapes and sizes 2",
            "1" => "Together we can do amazing things 1",
            "3" => "Together we can do amazing things 2",
            "4" => "4"
        );

        $selected_promo_image_id = esc_attr( $instance['promo_image_id'] );
        ?>
        <p>
        <label for="<?php echo $this->get_field_id('promo_image_id'); ?>">Select the promo image to use:</label>
        <select id="<?php echo $this->get_field_id('promo_image_id'); ?>" name="<?php echo $this->get_field_name('promo_image_id'); ?>" class="widefat">
            <?php foreach($promo_images as $promo_image_id => &$promo_image_name) { ?>
                <option <?php if($promo_image_id == $selected_promo_image_id) {?>selected<?php } ?> value="<?php echo $promo_image_id; ?>"><?php echo $promo_image_name; ?></option>
            <?php } ?>
        </select>
        </p>

        <?php
    }

    public function render_context($args, $instance, &$context) {

        // Theme should provide the join AIGA images
        $selected_promo_image_id = esc_attr( $instance['promo_image_id'] );

        // If goes beyond the max, set to default
        if(intval($selected_promo_image_id) > 3) {
            $selected_promo_image_id = 0;
        }

        $selected_promo_image_url = get_bloginfo('template_url') . '/images/join_aiga_' . $selected_promo_image_id . '.png';

        $context['selected_promo_image_url'] = $selected_promo_image_url;

    }

}

function ikit_widgets_join_aiga_init() {
    return register_widget("Ikit_WidgetJoinAIGA");
}

add_action( 'widgets_init', 'ikit_widgets_join_aiga_init');


/**
 * I am AIGA Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetIAmAIGA extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
             'ikit_widget_i_am_aiga', // Base ID
            'Ikit Widget I am AIGA', // Name
            array('classname' => 'ikit-widget-i-am-aiga', 'description' => 'Displays randomized member.')
        );
    }

    public function render_context($args, $instance, &$context) {

        global $wpdb;
        $member_table_name = $wpdb->prefix . IKIT_MEMBER_TABLE_NAME;
        $member = $wpdb->get_results("SELECT * from $member_table_name where full_name != '' and avatar != '' and is_member = 1 order by RAND() limit 0,1");
        if(count($member) == 1) {
            $member = $member[0];
            $context['member'] = $member;
        }

    }

}

function ikit_widgets_i_am_aiga_init() {
    return register_widget("Ikit_WidgetIAmAIGA");
}

add_action( 'widgets_init', 'ikit_widgets_i_am_aiga_init');


/**
 * New Members
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetNewMembers extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
             'ikit_widget_new_members', // Base ID
            'Ikit Widget New Members', // Name
            array('classname' => 'ikit-widget-new-members', 'description' => 'Displays new members.')
        );
    }

    function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array( 'num_results' => '6' ));
        $num_results = esc_attr( $instance['num_results'] );
        ?>
        <p>
        <label for="<?php echo $this->get_field_id('num_results'); ?>">Number of members to display:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('num_results'); ?>" name="<?php echo $this->get_field_name('num_results'); ?>" type="text" value="<?php echo esc_attr($num_results); ?>"/>
        </p>

        <?php
    }

    public function render_context($args, $instance, &$context) {

        $num_results = intval($instance['num_results']);
        if($num_results <= 0) {
            $num_results = 6;
        }

        global $wpdb;
        $member_table_name = $wpdb->prefix . IKIT_MEMBER_TABLE_NAME;
        $members = $wpdb->get_results("SELECT * from $member_table_name where full_name != '' and is_member = 1 order by join_date DESC limit 0,$num_results");

        $context['members'] = $members;


    }

}

function ikit_widgets_new_members_init() {
    return register_widget("Ikit_WidgetNewMembers");
}

add_action( 'widgets_init', 'ikit_widgets_new_members_init');

/**
 * Event Calendar
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_WidgetEventCalendar extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_widget_event_calendar', // Base ID
            'Ikit Widget Event Calendar', // Name
            array('classname' => 'ikit-widget-event-calendar', 'description' => 'Displays a calendar and contains links to upcoming events')
        );
    }

    public function render($args, $instance, $context) {

        // The event calendar is the same for any theme, as it is fairly complicated
        // each theme would not want to create the rendering separately.

        // Define class functions here
        function get_days($s_start_date, $s_end_date) {

            // Firstly, format the provided dates.
            // This function works best with YYYY-MM-DD
            // but other date formats will work thanks
            // to strtotime().
            $s_start_date = gmdate("Y-m-d", strtotime($s_start_date));
            $s_end_date = gmdate("Y-m-d", strtotime($s_end_date));

            // Start the variable off with the start date
            $a_days[] = $s_start_date;

            // Set a 'temp' variable, sCurrentDate, with
            // the start date - before beginning the loop
            $s_current_date = $s_start_date;

            // While the current date is less than the end date
            while($s_current_date < $s_end_date) {
                // Add a day to the current date
                $s_current_date = gmdate("Y-m-d", strtotime("+1 day", strtotime($s_current_date)));

                // Add this new day to the aDays array
                $a_days[] = $s_current_date;
            }
            return $a_days;
        }

        // Outputs the content of the widget
        extract( $args );

        $title = $instance['title'];

        echo $before_title . $title . $after_title;

        global $wpdb;

        $etouches_event_table_name = $wpdb->prefix . IKIT_EVENT_ETOUCHES_TABLE_NAME;
        $etouches_events = $wpdb->get_results("SELECT * FROM $etouches_event_table_name where status = 'Sold Out' or status = 'Live'");

        $eventbrite_event_table_name = $wpdb->prefix . IKIT_EVENT_EVENTBRITE_TABLE_NAME;
        $eventbrite_events = $wpdb->get_results("SELECT * FROM $eventbrite_event_table_name where status = 'Live' or status = 'Started'");

        $days_by_event_id = array();

        foreach($etouches_events as $event) {
            if(!in_array($event->id, $days_by_event_id)) {
                $days_by_event_id['etouches' . $event->id]=get_days($event->start_date, $event->end_date);
            }
        }

        foreach($eventbrite_events as $event) {
            if(!in_array($event->id, $days_by_event_id)) {
                $days_by_event_id['eventbrite' . $event->id]=get_days($event->start_date, $event->end_date);
            }
        }

        // Figure out how to include using plugin URL
        wp_enqueue_script('js_zebra_datepicker', ikit_get_plugin_url('js/zebra_datepicker.js'));
        wp_enqueue_style('css_zebra_datepicker', ikit_get_plugin_url('css/zebra_datepicker.css'));

        ?>


        <div class="ikit-widget-event-calendar-hook"></div>

        <script type="text/javascript">

        $(document).ready (function() {

            $( ".ikit-widget-event-calendar-hook" ).Zebra_DatePicker({
                always_visible: $('.ikit-widget-event-calendar-hook'),
                show_clear_date: false,
                show_select_today: false,
                onChange: function() {
                    iKitWidgetsEventCalendarSync();
                }
            });
            $(".ikit-widget-event-calendar-hook" ).parent().css('margin','0');
            $(".dp_daypicker>tbody>tr").addClass("week");
            $(".week>td:first-child").css("border-left","none");


        });

        function iKitWidgetsEventCalendarLinkFunction(eventYear, eventMonth, eventDay) {

            window.location.replace("<?php echo get_home_url(); ?>"+"/upcoming-events?date="+eventYear+"-"+eventMonth+"-"+eventDay);
        };

        function iKitWidgetsEventCalendarSync() {

            $(".ikit-widget-event-calendar-hook .dp_daypicker tbody").children("tr").children("td").each(function() {

                var day = $(this).html();
                var dayCol = $(this);
                if(day.valueOf()<10) {
                    day="0"+day;
                }
                var year=$(this).attr('data-year');
                var month=$(this).attr('data-month');
                var date = dayCol.data('date');
                dayCol.addClass('date-' + date);

            });

            var event_info;
            event_info = JSON.parse('<?php echo json_encode($days_by_event_id );?>');
            for(var events in event_info) {
                for(var dates in event_info[events]) {
                    var eventDay=event_info[events][dates].toString().slice(8) ;
                    var eventMonth=event_info[events][dates].toString().slice(5,7)
                    var eventYear=event_info[events][dates].toString().slice(0,4) ;

                    var link = $(".date-" + eventYear+"-"+eventMonth+"-"+eventDay);
                    link.attr("onclick","iKitWidgetsEventCalendarLinkFunction("+eventYear+","+eventMonth+","+ eventDay+")" );
                    link.html("<a>"+ parseInt(eventDay, 10) +"</a>");
                    link.children().parent().addClass('has-events');
                    link.children().attr('href', '<?php echo get_home_url(); ?>' + '/upcoming-events?date='+eventYear+"-"+eventMonth+"-"+eventDay);
                }
            }

            $(".dp_daypicker>tbody>tr").addClass("week");
            $(".week>td:first-child").css("border-left","none");
        };

        $(document).ready(function() {
            iKitWidgetsEventCalendarSync();
        });

        </script>

        <?php

    }

}

function ikit_widgets_event_calendar_init() {
    return register_widget("Ikit_WidgetEventCalendar");

}
add_action( 'widgets_init', 'ikit_widgets_event_calendar_init');


/**
 * Facebook
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_Facebook_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
             'ikit_facebook_widget', // Base ID
            'Ikit Facebook Widget', // Name
            array('classname' => 'ikit-widget-facebook', 'description' => 'Displays Facebook feed as configured in iKit settings.')
        );
    }

    public function render_context($args, $instance, &$context) {

        global $g_options;

        $facebook_id = $g_options[IKIT_PLUGIN_OPTION_FACEBOOK_ID];
        $facebook_url = 'http://www.facebook.com/' . $facebook_id;
        $facebook_status_messages = ikit_social_get_facebook_feed_items();

        $context['facebook_status_messages'] = $facebook_status_messages;
        $context['facebook_url'] = $facebook_url;
        $context['facebook_id'] = $facebook_id;

    }

}

function ikit_widgets_facebook_init() {
    return register_widget("Ikit_Facebook_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_facebook_init');

/**
 * Twitter
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_Twitter_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_twitter_widget', // Base ID
            'Ikit Twitter Widget', // Name
            array('classname' => 'ikit-widget-twitter', 'description' => 'Displays Twitter feed as configured in iKit settings.')
        );
    }

    function form($instance) {

        $instance = wp_parse_args((array)$instance, array('num_twitter_messages' => '1'));
        $num_twitter_messages = esc_attr( $instance['num_twitter_messages'] );

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('num_twitter_messages'); ?>">Number of tweets to display:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('num_twitter_messages'); ?>" name="<?php echo $this->get_field_name('num_twitter_messages'); ?>" type="text" value="<?php echo esc_attr($num_twitter_messages); ?>"/>
        </p>

        <?php
    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['num_twitter_messages'] = $new_instance['num_twitter_messages'];

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

        global $g_options;

        $twitter_messages = ikit_social_get_twitter_messages_rss_items();
        $twitter_username = $g_options[IKIT_PLUGIN_OPTION_TWITTER_USERNAME];
        $twitter_url = 'http://www.twitter.com/' . $twitter_username;
        $num_twitter_messages = intval($instance['num_twitter_messages']);
        if($num_twitter_messages <= 0) {
            $num_twitter_messages = 1;
        }

        $context['twitter_messages'] = $twitter_messages;
        $context['twitter_url'] = $twitter_url;
        $context['twitter_username'] = $twitter_username;
        $context['num_twitter_messages'] = $num_twitter_messages;

    }

}

function ikit_widgets_twitter_init() {
    return register_widget("Ikit_Twitter_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_twitter_init');

/**
 * Vimeo
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_Vimeo_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_vimeo_widget', // Base ID
            'Ikit Vimeo Widget', // Name
            array('classname' => 'ikit-widget-vimeo', 'description' => 'Displays Vimeo feed as configured in iKit settings.')
        );
    }

    function form($instance) {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'randomize_vimeo' ); ?>"><?php _e('Randomize Vimeo Feed:'); ?></label>
            <input class="checkbox" type="checkbox" <?php checked('1', $instance['randomize_vimeo']); ?> value="1" id="<?php echo $this->get_field_id( 'randomize_vimeo' ); ?>" name="<?php echo $this->get_field_name( 'randomize_vimeo' ); ?>" />
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['randomize_vimeo'] = ( $new_instance['randomize_vimeo'] == 1 ? 1 : 0 );

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

        global $g_options;
        $vimeo_videos = ikit_social_get_vimeo_videos_rss_items();
        $vimeo_user_id = $g_options[IKIT_PLUGIN_OPTION_VIMEO_USER_ID];
        $vimeo_url = 'http://www.vimeo.com/' . $vimeo_user_id;
        $randomize_vimeo = in_array('randomize_vimeo', $instance) ? $instance['randomize_vimeo'] : true;

        $context['vimeo_videos'] = $vimeo_videos;
        $context['vimeo_user_id'] = $vimeo_user_id;
        $context['vimeo_url'] = $vimeo_url;
        $context['randomize_vimeo'] = $randomize_vimeo;


    }


}

function ikit_widgets_vimeo_init() {
    return register_widget("Ikit_Vimeo_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_vimeo_init');


/**
 * YouTube
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_YouTube_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_youtube_widget', // Base ID
            'Ikit YouTube Widget', // Name
            array('classname' => 'ikit-widget-youtube', 'description' => 'Displays YouTube feed as configured in iKit settings.')
        );
    }

    function form($instance) {
        ?>
        </p>
            <label for="<?php echo $this->get_field_id( 'randomize_youtube' ); ?>"><?php _e('Randomize YouTube Feed:'); ?></label>
            <input class="checkbox" type="checkbox" <?php checked('1', $instance['randomize_youtube']); ?> value="1" id="<?php echo $this->get_field_id( 'randomize_youtube' ); ?>" name="<?php echo $this->get_field_name( 'randomize_youtube' ); ?>" />
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['randomize_youtube'] = ( $new_instance['randomize_youtube'] == 1 ? 1 : 0 );

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

        $youtube_videos = ikit_social_get_youtube_videos_rss_items();

        global $g_options;
        $youtube_username = $g_options[IKIT_PLUGIN_OPTION_YOUTUBE_USERNAME];
        $youtube_url = 'http://www.youtube.com/' . $youtube_username;
        $randomize_youtube = in_array('randomize_youtube', $instance) ? $instance['randomize_youtube'] : true;

        $context['youtube_videos'] = $youtube_videos;
        $context['youtube_username'] = $youtube_username;
        $context['youtube_url'] = $youtube_url;
        $context['randomize_youtube'] = $randomize_youtube;

    }

}

function ikit_widgets_youtube_init() {
    return register_widget("Ikit_YouTube_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_youtube_init');


/**
 * Flickr
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_Flickr_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_flickr_widget', // Base ID
            'Ikit Flickr Widget', // Name
            array('classname' => 'ikit-widget-flickr', 'description' => 'Displays Flickr feed as configured in iKit settings.')
        );
    }

    function form($instance) {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'randomize_flickr' ); ?>"><?php _e('Randomize Flickr Feed:'); ?></label>
            <input class="checkbox" type="checkbox" <?php checked('1', $instance['randomize_flickr']); ?> value="1" id="<?php echo $this->get_field_id( 'randomize_flickr' ); ?>" name="<?php echo $this->get_field_name( 'randomize_flickr' ); ?>" />
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['randomize_flickr'] = ( $new_instance['randomize_flickr'] == 1 ? 1 : 0 );

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

        $flickr_images = ikit_social_get_flickr_images_rss_items();

        global $g_options;
        $flickr_user_id = $g_options[IKIT_PLUGIN_OPTION_FLICKR_USER_ID];
        $flickr_url = 'http://www.flickr.com/' . $flickr_user_id;
        $randomize_flickr = in_array('randomize_flickr', $instance) ? $instance['randomize_flickr'] : true;

        $context['flickr_images'] = $flickr_images;
        $context['flickr_user_id'] = $flickr_user_id;
        $context['flickr_url'] = $flickr_url;
        $context['randomize_flickr'] = $randomize_flickr;

    }

}

function ikit_widgets_flickr_init() {
    return register_widget("Ikit_Flickr_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_flickr_init');



/**
 * Instagram
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_Instagram_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_instagram_widget', // Base ID
            'Ikit Instagram Widget', // Name
            array('classname' => 'ikit-widget-instagram', 'description' => 'Displays Instagram feed as configured in iKit settings.')
        );
    }

    function form($instance) {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'randomize_instagram' ); ?>"><?php _e('Randomize Instagram Feed:'); ?></label>
            <input class="checkbox" type="checkbox" <?php checked('1', $instance['randomize_instagram']); ?> value="1" id="<?php echo $this->get_field_id( 'randomize_instagram' ); ?>" name="<?php echo $this->get_field_name( 'randomize_instagram' ); ?>" />
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['randomize_instagram'] = ( $new_instance['randomize_instagram'] == 1 ? 1 : 0 );

        return $instance;

    }


    public function render_context($args, $instance, &$context) {

        global $g_options;
        $instagram_photos = ikit_social_get_instagram_feed_items();

        $instagram_username = $g_options[IKIT_PLUGIN_OPTION_INSTAGRAM_USERNAME];
        $instagram_url = 'http://www.instagram.com/' . $instagram_username;
        $randomize_instagram = in_array('randomize_instagram', $instance) ? $instance['randomize_instagram'] : true;

        $context['instagram_photos'] = $instagram_photos;
        $context['instagram_username'] = $instagram_username;
        $context['instagram_url'] = $instagram_url;
        $context['randomize_instagram'] = $randomize_instagram;

    }

}

function ikit_widgets_instagram_init() {
    return register_widget("Ikit_Instagram_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_instagram_init');

/**
 Video Billboard Widget
 */
class Ikit_Video_Billboard_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_video_billboard_widget', // Base ID
            'Ikit Video Billboard Widget', // Name
            array('classname' => 'ikit-widget-video-billboard', 'description' => 'Displays a video in a billboard full-width format, only use within billboard widget areas.')
        );
    }
    function form($instance) {

        $instance = wp_parse_args((array)$instance, array('video_url' => '', 'video_image' => ''));

        $custom_video_image_url =  $instance['custom_video_image_url'];
        $video_url =  $instance['video_url'];
        $errors = $instance['errors'];

        if(sizeof($errors) > 0) {
            ?>
            <div class="errors">
                <?php foreach($errors as $error) { ?>
                    <?php echo $error; ?>
                <?php } ?>
            </div>
            <?php
        }

        ?>
        <p>
        Please use only YouTube or Vimeo URLs for the Video URL. You may set a custom image, otherwise the default video thumbnail will be used.
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('video_url'); ?>">Video URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('video_url'); ?>" name="<?php echo $this->get_field_name('video_url'); ?>" type="text" value="<?php echo esc_attr($video_url); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('custom_video_image_url'); ?>">Custom Video Image URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('custom_video_image_url'); ?>" name="<?php echo $this->get_field_name('custom_video_image_url'); ?>" type="text" value="<?php echo esc_attr($custom_video_image_url); ?>"/>
        </p>

        <?php
    }
    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        $video_url = $new_instance['video_url'];
        $custom_video_image_url = $new_instance['custom_video_image_url'];

        $instance['custom_video_image_url'] = $custom_video_image_url;
        $instance['errors'] = array();

        // Validate and parse video URL
        if (preg_match('/youtube/', $video_url)) {

            $youtube = "http://www.youtube.com/oembed?url=". $video_url . "&format=json";
            $curl = curl_init($youtube);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($curl);
            curl_close($curl);
            $parsed_response = json_decode($response, true);

            if($parsed_response == null) {
                array_push($instance['errors'], 'Sorry the YouTube video for that URL does not exist');
            }
            else {
                preg_match( '/embed\/(.*)\?/', $parsed_response['html'], $video_id_matches);
                $video_id = $video_id_matches[1];

                $instance['video_type'] = 'youtube';
                $instance['video_id'] = $video_id;
                $instance['video_url'] = $video_url;
                $instance['video_image_url'] = 'http://img.youtube.com/vi/' . $video_id . '/0.jpg';
                $instance['video_title'] = $parsed_response['title'];
            }

        }
        else if (preg_match('/vimeo/', $video_url)) {

            $vimeo = "http://vimeo.com/api/oembed.xml?url=". $video_url . "&format=json";
            $curl = curl_init($vimeo);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($curl);
            curl_close($curl);
            $parsed_response = json_decode($response, true);

            if($parsed_response == null) {
                array_push($instance['errors'], 'Sorry the Vimeo video for that URL does not exist');
            }
            else {
                $instance['video_type'] = 'vimeo';
                $instance['video_id'] = $parsed_response['video_id'];
                $instance['video_url'] = $video_url;
                $instance['video_image_url'] = $parsed_response['thumbnail_url'];
                $instance['video_title'] = $parsed_response['title'];
            }

        }
        else {
            array_push($instance['errors'], 'Sorry only Vimeo and YouTube URLs are supported');
        }

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

        // Get the posts
        $context['video_url'] = $instance['video_url'];
        $context['video_id'] = $instance['video_id'];
        $context['video_image_url'] = $instance['video_image_url'];
        $context['video_title'] = $instance['video_title'];
        $context['custom_video_image_url'] = $instance['custom_video_image_url'];
        $context['video_type'] = $instance['video_type'];

    }

}

function ikit_widgets_news_billboard_init() {
    return register_widget("Ikit_News_Billboard_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_news_billboard_init');



/**
 News Billboard Widget
 */
class Ikit_News_Billboard_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_news_billboard_widget', // Base ID
            'Ikit News Billboard Widget', // Name
            array('classname' => 'ikit-widget-news-billboard', 'description' => 'Displays news posts in a billboard full-width format, only use within billboard widget areas.')
        );
    }

    public function render_context($args, $instance, &$context) {

        // Get the posts
        $num_posts = 3;

        $args = array();
        $args['posts_per_page'] = $num_posts;
        $args['category_name'] = IKIT_SLUG_CATEGORY_FEATURED;
        $args['order'] = 'DESC';
        $args['post_status'] = 'publish';
        $args['post_type'] = array('post', IKIT_POST_TYPE_IKIT_POST_EXTERNAL);
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;

        $posts = get_posts($args);

        // If no featured, show latest
        if(count($posts) <= 0) {
            $args['category_name'] = null;
            $posts = get_posts($args);
        }

        $context['posts'] = $posts;

    }


}

function ikit_widgets_video_billboard_init() {
    return register_widget("Ikit_Video_Billboard_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_video_billboard_init');




/**
  News Widget
  */
class Ikit_News_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_news_widget', // Base ID
            'Ikit News Widget', // Name
            array('classname' => 'ikit-widget-news', 'description' => 'Displays news posts.')
        );
    }
    function form($instance) {

        $instance = wp_parse_args((array)$instance, array('num_posts' => '3'));
        $num_posts = esc_attr( $instance['num_posts'] );

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('num_posts'); ?>">Number of news items to display:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo esc_attr($num_posts); ?>"/>
        </p>

        <?php
    }
    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['num_posts'] = $new_instance['num_posts'];

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

        // Get the posts
        $num_posts = intval($instance['num_posts']);
        if($num_posts <= 0) {
            $num_posts = 1;
        }

        $args = array();
        $args['posts_per_page'] = $num_posts;
        $args['category_name'] = IKIT_SLUG_CATEGORY_FEATURED;
        $args['order'] = 'DESC';
        $args['post_status'] = 'publish';
        $args['post_type'] = array('post', IKIT_POST_TYPE_IKIT_POST_EXTERNAL);
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;

        $posts = get_posts($args);

        // If no featured, show latest
        if(count($posts) <= 0) {
            $args['category_name'] = null;
            $posts = get_posts($args);
        }

        $context['posts'] = $posts;

    }


}

function ikit_widgets_news_init() {
    return register_widget("Ikit_News_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_news_init');



/**
 Events Billboard Widget
 */
class Ikit_Events_Billboard_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_events_billboard_widget', // Base ID
            'Ikit Events Billboard Widget', // Name
            array('classname' => 'ikit-widget-events-billboard', 'description' => 'Displays events in a billboard full-width format, only use within billboard widget areas.')
        );
    }

    public function render_context($args, $instance, &$context) {

        // Get the posts
        $num_posts = 5;

        $args = array();
        $args['posts_per_page'] = $num_posts;
        $args['category_name'] = IKIT_SLUG_CATEGORY_FEATURED;
        $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL, IKIT_POST_TYPE_IKIT_EVENT_INTERNAL);
        $args['order'] = 'ASC';
        $args['post_status'] = 'publish';
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;

        $args['meta_query'] = array(

            array(
            'key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE,
            'value' => date_i18n("Y-m-d"), 'compare' => '>=',
            'type' => 'DATE'),

            array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, 'value' => 'Closed',
            'compare' => '!=',
            'type' => 'CHAR')

        );

        $posts = get_posts($args);

        // If no featured, show latest
        if(count($posts) <= 0) {
            $args['category_name'] = null;
            $posts = get_posts($args);
        }

        $context['posts'] = $posts;

    }

}

function ikit_widgets_events_billboard_init() {
    return register_widget("Ikit_Events_Billboard_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_events_billboard_init');


/**
  Events Widget
  */
class Ikit_Events_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_events_widget', // Base ID
            'Ikit Events Widget', // Name
            array('classname' => 'ikit-widget-events', 'description' => 'Displays events.')
        );
    }
    function form($instance) {

        $instance = wp_parse_args((array)$instance, array('num_posts' => '3'));
        $num_posts = esc_attr( $instance['num_posts'] );

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('num_posts'); ?>">Number of events to display:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo esc_attr($num_posts); ?>"/>
        </p>

        <?php
    }
    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['num_posts'] = $new_instance['num_posts'];

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

        // Get the posts
        $num_posts = intval($instance['num_posts']);
        if($num_posts <= 0) {
            $num_posts = 1;
        }

        $args = array();
        $args['posts_per_page'] = $num_posts;
        $args['category_name'] = IKIT_SLUG_CATEGORY_FEATURED;
        $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT, IKIT_POST_TYPE_IKIT_EVENT_EXTERNAL);
        $args['order'] = 'ASC';
        $args['post_status'] = 'publish';
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;

        $args['meta_query'] = array(

            array(
            'key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE,
            'value' => date_i18n("Y-m-d"), 'compare' => '>=',
            'type' => 'DATE'),

            array('key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_STATUS, 'value' => 'Closed',
            'compare' => '!=',
            'type' => 'CHAR')

        );

        $posts = get_posts($args);

        // If no featured, show latest
        if(count($posts) <= 0) {
            $args['category_name'] = null;
            $posts = get_posts($args);
        }

        $context['posts'] = $posts;

    }


}

function ikit_widgets_events_init() {
    return register_widget("Ikit_Events_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_events_init');



/**
  Internal Events Widget
  */
class Ikit_Events_Internal_Widget extends Ikit_Widget {

    public function __construct() {
        parent::__construct(
            'ikit_events_internal_widget', // Base ID
            'Ikit Community Events Widget', // Name
            array('classname' => 'ikit-widget-events-internal', 'description' => 'Displays community events.')
        );
    }
    function form($instance) {

        $instance = wp_parse_args((array)$instance, array('num_posts' => '3'));
        $num_posts = esc_attr( $instance['num_posts'] );

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('num_posts'); ?>">Number of events to display:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo esc_attr($num_posts); ?>"/>
        </p>

        <?php
    }
    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['num_posts'] = $new_instance['num_posts'];

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

        // Get the posts
        $num_posts = intval($instance['num_posts']);
        if($num_posts <= 0) {
            $num_posts = 1;
        }

        $args = array();
        $args['posts_per_page'] = $num_posts;
        $args['category_name'] = IKIT_SLUG_CATEGORY_FEATURED;
        $args['post_type'] = array(IKIT_POST_TYPE_IKIT_EVENT_INTERNAL);
        $args['order'] = 'ASC';
        $args['post_status'] = 'publish';
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = IKIT_CUSTOM_FIELD_GENERIC_ORDERING_DISPLAY_PRIORITY;


        $args['meta_query'] = array(

            array(
            'key' => IKIT_CUSTOM_FIELD_IKIT_EVENT_END_DATE,
            'value' => date_i18n("Y-m-d"), 'compare' => '>=',
            'type' => 'DATE'),

        );

        $posts = get_posts($args);

        // If no featured, show latest
        if(count($posts) <= 0) {
            $args['category_name'] = null;
            $posts = get_posts($args);
        }


        $context['posts'] = $posts;

    }


}

function ikit_widgets_events_internal_init() {
    return register_widget("Ikit_Events_Internal_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_events_internal_init');



/**
 * Page Widget
 *
 * @package Internet Kit
 * @subpackage Widgets
 */
class Ikit_Quote_Widget extends Ikit_Widget {
    function Ikit_Quote_Widget() {

        parent::__construct(
            'ikit_widget_quote', // Base ID
            'Ikit Widget Quote', // Name
            array('classname' => 'ikit-widget-quote', 'description' => 'Displays a quote with attribution.')
        );

    }

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $quotes = array();

        $quote_max = intval($new_instance['quote-max']);

        for($i=0;$i<$quote_max;$i++) {

            if(array_key_exists('quote-text-idx-' . $i, $new_instance)) {

                $quote = array(
                        "quote_text" => $new_instance['quote-text-idx-' . $i],
                        "attribution" => $new_instance['attribution-idx-' . $i],
                        "attribution_link_url" => $new_instance['attribution_link_url-idx-' . $i],
                );

                array_push($quotes, $quote);

            }

        }

        $instance['quotes'] = serialize($quotes);

        return $instance;

    }

    public function render_context($args, $instance, &$context) {

        $quotes = unserialize($instance['quotes']);
        $context['quotes'] = $quotes;

    }

    function form( $instance ) {

        $quotes = unserialize($instance['quotes']);

        ?>

        <div class="quotes">

        <input id="<?php echo $this->get_field_id('quote-max'); ?>" name="<?php echo $this->get_field_name('quote-max'); ?>" type="hidden" value="<?php echo count($quotes); ?>"></input>

        <?php

        for($i=0;$i<count($quotes);$i++) {

        ?>


        <div class="wp-box quote" quote_prefix="<?php echo $this->get_field_id(); ?>" quote_index="<?php echo $i; ?>">

            <p>
            <label for="<?php echo $this->get_field_id('quote-text-idx-' . $i); ?>"><?php _e('Quote:'); ?></label><BR/>
            <textarea id="<?php echo $this->get_field_id('quote-text-idx-' . $i); ?>" rows="5" name="<?php echo $this->get_field_name('quote-text-idx-' . $i); ?>"><?php echo $quotes[$i]['quote_text']; ?></textarea>
            </p>

            <p>
            <label for="<?php echo $this->get_field_id('attribution-idx-' . $i); ?>"><?php _e('Attribution:'); ?></label><BR/>
            <input id="<?php echo $this->get_field_id('attribution-idx-' . $i); ?>" name="<?php echo $this->get_field_name('attribution-idx-' . $i); ?>" type="text" value="<?php echo $quotes[$i]['attribution']; ?>"></input>
            </p>

            <p>
            <label for="<?php echo $this->get_field_id('attribution_link_url-idx-' . $i); ?>"><?php _e('Attribution Link URL:'); ?></label><BR/>
            <input id="<?php echo $this->get_field_id('attribution_link_url-idx-' . $i); ?>" name="<?php echo $this->get_field_name('attribution_link_url-idx-' . $i); ?>" type="text" value="<?php echo $quotes[$i]['attribution_link_url']; ?>"></input>
            </p>

            <p>
                <a href="javascript:void(0);" onclick="jQuery.ikit_admin.widgets.quoteWidget.removeQuote(this);">Remove</a>
            </p>


        </div>

        <?php

        }

        ?>

        </div>

        <div class="widget-control-actions">
            <p>
                <a href="javascript:void(0);" onclick="jQuery.ikit_admin.widgets.quoteWidget.addQuote(this);">+ Add another quote</a>
            </p>
        </div>

        <?php

    }

}

function ikit_widgets_quote() {
    return register_widget("Ikit_Quote_Widget");
}

add_action( 'widgets_init', 'ikit_widgets_quote');


?>