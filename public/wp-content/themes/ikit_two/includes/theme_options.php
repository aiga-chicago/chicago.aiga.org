<?php

function ikit_two_theme_options_page_render() {

  // Include for success and error messages
  include_once 'options-head.php';

  ?>

  <div class='wrap'>

    <div id="icon-options-general" class="icon32"></div><h2>Theme Options</h2>

    <form method="post" enctype="multipart/form-data" action="options.php">

    <div class="wp-box">

      <?php settings_fields('ikit_two_theme_options'); ?>
      <?php $theme_options = get_option(IKIT_TWO_THEME_OPTION_GROUP_GENERAL); ?>
      <table class="form-table">

        <tr valign="top">
          <th scope="row">AIGA chapter logo for header</th>
          <td>
          <input name="<?php echo IKIT_TWO_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE; ?>" type="file"></input>
          </td>
        </tr>
        <tr>
          <th scope="row"></th>
          <td>

          <img src="<?php echo $theme_options[IKIT_TWO_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE]['url']; ?>"/>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">Favicon</th>
          <td>
          <input name="<?php echo IKIT_TWO_THEME_OPTION_FAV_ICON; ?>" type="file"></input>
          </td>
        </tr>
        <tr>
          <th scope="row"></th>
          <td>

          <img src="<?php echo $theme_options[IKIT_TWO_THEME_OPTION_FAV_ICON]['url']; ?>"/>
          </td>
        </tr>

        <tr valign="top">
            <th scope="row">Custom font embed code
            <div class="note">
            You can replace the default custom font embed with your own. For example, if you have your own TypeKit account, you would enter the font embed code here.
            <BR/>
            <BR/>
            If you are using a font loader such as TypeKit, you must call the function "jQuery.ikit_two.fonts.onLoaded" once your fonts have loaded, otherwise some of the grid layouts may not load properly:<BR/>
            <BR/>
            For TypeKit:

<pre>
Typekit.load({
    active: jQuery.ikit_two.fonts.onLoaded,
    inactive: jQuery.ikit_two.fonts.onLoaded
});
</pre>

            </div>
            </th>
            <td>

            <textarea rows="5" cols="50" name="<?php echo IKIT_TWO_THEME_OPTION_GROUP_GENERAL . '[' . IKIT_TWO_THEME_OPTION_CUSTOM_FONT_EMBED_CODE . ']'; ?>"><?php if(isset($theme_options[IKIT_TWO_THEME_OPTION_CUSTOM_FONT_EMBED_CODE])) { echo $theme_options[IKIT_TWO_THEME_OPTION_CUSTOM_FONT_EMBED_CODE]; } ?></textarea>
            </td>
        </tr>

        <tr valign="top">
          <th scope="row">Enable commenting on events</th>
          <td>
          <input <?php checked('1', $theme_options[IKIT_TWO_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT]); ?> name="<?php echo IKIT_TWO_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT; ?>" type="checkbox"/>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">Enable event attendee lists</th>
          <td>
          <input <?php checked('1', $theme_options[IKIT_TWO_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED]); ?> name="<?php echo IKIT_TWO_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED; ?>" type="checkbox"/>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">Enable parallax banner images</th>
          <td>
          <input <?php checked('1', $theme_options[IKIT_TWO_THEME_OPTION_FIXED_BACKGROUND_IMAGE_ENABLED]); ?> name="<?php echo IKIT_TWO_THEME_OPTION_FIXED_BACKGROUND_IMAGE_ENABLED; ?>" type="checkbox"/>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">Disable the detail page for iKit Persons
          <div class="note">Each person will appear on the list page but no longer redirect on click to their respective detail page. This may be useful if you do not have large enough images available for the detail page.</div>
          </th>
          <td>
          <input <?php checked('1', $theme_options[IKIT_TWO_THEME_OPTION_SINGLE_DISABLED_IKIT_PERSON]); ?> name="<?php echo IKIT_TWO_THEME_OPTION_SINGLE_DISABLED_IKIT_PERSON; ?>" type="checkbox"/>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">Enable homepage featured image gallery autoplay</th>
          <td>
          <input <?php checked('1', $theme_options[IKIT_TWO_THEME_OPTION_FEATURED_IMAGE_GALLERY_AUTOPLAY]); ?> name="<?php echo IKIT_TWO_THEME_OPTION_FEATURED_IMAGE_GALLERY_AUTOPLAY; ?>" type="checkbox"/>
          </td>
        </tr>

      </table>

    </div>

    <p class="submit">
    <input type="submit" class="button-primary" value='Save Changes' />
    </p>

    </form>

  </div>

  <?php

}

function ikit_two_theme_options_init(){
  register_setting( 'ikit_two_theme_options', IKIT_TWO_THEME_OPTION_GROUP_GENERAL, 'ikit_two_theme_options_validate');
}

add_action('admin_init', 'ikit_two_theme_options_init' );

function ikit_two_theme_options_validate($input) {

  $theme_options = get_option(IKIT_TWO_THEME_OPTION_GROUP_GENERAL);

  if(!$_FILES[IKIT_TWO_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE]['error']) {

    $file = wp_handle_upload($_FILES[IKIT_TWO_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE], array('test_form' => false));
    $input[IKIT_TWO_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE] = $file;

  }
  else {

    $input[IKIT_TWO_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE] = $theme_options[IKIT_TWO_THEME_OPTION_AIGA_HEADER_LOGO_IMAGE];

  }


  if(!$_FILES[IKIT_TWO_THEME_OPTION_FAV_ICON]['error']) {

    $file = wp_handle_upload($_FILES[IKIT_TWO_THEME_OPTION_FAV_ICON], array('test_form' => false));
    $input[IKIT_TWO_THEME_OPTION_FAV_ICON] = $file;

  }
  else {

    $input[IKIT_TWO_THEME_OPTION_FAV_ICON] = $theme_options[IKIT_TWO_THEME_OPTION_FAV_ICON];

  }

  $input[IKIT_TWO_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT] = ($_POST[IKIT_TWO_THEME_OPTION_COMMENTS_ENABLED_IKIT_EVENT] == 'on' ? 1 : 0);
  $input[IKIT_TWO_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED] = ($_POST[IKIT_TWO_THEME_OPTION_EVENT_ATTENDEE_LIST_ENABLED] == 'on' ? 1 : 0);
  $input[IKIT_TWO_THEME_OPTION_FIXED_BACKGROUND_IMAGE_ENABLED] = ($_POST[IKIT_TWO_THEME_OPTION_FIXED_BACKGROUND_IMAGE_ENABLED] == 'on' ? 1 : 0);
  $input[IKIT_TWO_THEME_OPTION_FEATURED_IMAGE_GALLERY_AUTOPLAY] = ($_POST[IKIT_TWO_THEME_OPTION_FEATURED_IMAGE_GALLERY_AUTOPLAY] == 'on' ? 1 : 0);
  $input[IKIT_TWO_THEME_OPTION_SINGLE_DISABLED_IKIT_PERSON] = ($_POST[IKIT_TWO_THEME_OPTION_SINGLE_DISABLED_IKIT_PERSON] == 'on' ? 1 : 0);

  return $input;

}

?>