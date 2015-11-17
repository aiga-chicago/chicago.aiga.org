<?php
/**
 * Internet Kit Persons Template Functions.
 *
 * @package Internet Kit
 * @subpackage Template
 */

/**
 * Returns the images for a person, note to actually retreive the image itself, you
 * need to use the wp_get_attachment_image_src function, e.g.
 * wp_get_attachment_image_src($image['image'], 'full');
 */
function ikit_person_get_images($ikit_person_id) {
     return get_field(IKIT_CUSTOM_FIELD_IKIT_PERSON_IMAGE_GALLERY, $ikit_person_id);
}

/**
 * Returns the positions for a person
 */
function ikit_person_get_positions($ikit_person_id) {
    return get_field(IKIT_CUSTOM_FIELD_IKIT_PERSON_POSITIONS, $ikit_person_id);
}

?>