<?php

/**
 * Load the Advanced Custom Fields configuration. The configuration was created via the admin panel
 * then exported via settings, export field groups to PHP. This is so that the field groups
 * can easily be edited without requiring database changes. Do not manually edit. To edit first load
 * the custom fields into the database using the WordPress importer and the file
 * in tools/installation/advanced-custom-field-export.xml, after importing do export PHP
 * and paste the code below.
 */
function ikit_load_acf() {


/**
 * Register field groups
 * The register_field_group function accepts 1 array which holds the relevant data to register a field group
 * You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
 * This code must run every time the functions.php file is read
 */


if(function_exists("register_field_group"))
{
    register_field_group(array (
        'id' => '5107f5b222da8',
        'title' => 'Ikit Event',
        'fields' =>
        array (
            0 =>
            array (
                'key' => 'field_13',
                'label' => 'Additional Information',
                'name' => 'additional_information',
                'type' => 'wysiwyg',
                'order_no' => 0,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => '',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'toolbar' => 'full',
                'media_upload' => 'yes',
                'the_content' => 'yes',
            ),
            1 =>
            array (
                'key' => 'field_14',
                'label' => 'Registration Link',
                'name' => 'registration_type',
                'type' => 'select',
                'order_no' => 1,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'choices' =>
                array (
                    0 => 'Enabled',
                    1 => 'Disabled',
                ),
                'default_value' => '',
                'allow_null' => 0,
                'multiple' => 0,
            ),
            2 =>
            array (
                'key' => 'field_185',
                'label' => 'Display Priority',
                'name' => 'display_priority',
                'type' => 'text',
                'order_no' => 2,
                'instructions' => 'Supercede ordering by date, higher values mean the item will appear ealier. Reset to 0 to disable.',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            3 =>
            array (
                'key' => 'field_222',
                'label' => 'Image Gallery',
                'name' => 'image_gallery',
                'type' => 'repeater',
                'order_no' => 3,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'sub_fields' =>
                array (
                    'field_223' =>
                    array (
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'instructions' => '',
                        'column_width' => '',
                        'save_format' => 'id',
                        'preview_size' => 'thumbnail',
                        'order_no' => 0,
                        'key' => 'field_223',
                    ),
                    'field_224' =>
                    array (
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'column_width' => '',
                        'default_value' => '',
                        'formatting' => 'html',
                        'order_no' => 1,
                        'key' => 'field_224',
                    ),
                ),
                'row_min' => 0,
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => 'Add Row',
            ),
            4 =>
            array (
                'key' => 'field_38',
                'label' => 'Preview Description',
                'name' => 'preview_description',
                'type' => 'textarea',
                'order_no' => 4,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'default_value' => '',
                'formatting' => 'br',
            ),
        ),
        'location' =>
        array (
            'rules' =>
            array (
                0 =>
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ikit_event',
                    'order_no' => 0,
                ),
            ),
            'allorany' => 'all',
        ),
        'options' =>
        array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' =>
            array (
            ),
        ),
        'menu_order' => 0,
    ));
    register_field_group(array (
        'id' => '5107f5b22368d',
        'title' => 'Ikit Image Gallery',
        'fields' =>
        array (
            0 =>
            array (
                'key' => 'field_12',
                'label' => 'Image Gallery',
                'name' => 'image_gallery',
                'type' => 'repeater',
                'order_no' => 0,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'sub_fields' =>
                array (
                    0 =>
                    array (
                        'key' => 'field_4f84a6722a9d6',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'save_format' => 'id',
                        'preview_size' => 'thumbnail',
                        'order_no' => 0,
                    ),
                    1 =>
                    array (
                        'key' => 'field_4f84a6722a9f4',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'default_value' => '',
                        'formatting' => 'html',
                        'order_no' => 1,
                    ),
                    2 =>
                    array (
                        'key' => 'field_4f84a6722aa0b',
                        'label' => 'Link URL',
                        'name' => 'link_url',
                        'type' => 'text',
                        'default_value' => '',
                        'formatting' => 'html',
                        'order_no' => 2,
                    ),
                    3 =>
                    array (
                        'key' => 'field_4f84a6722aa51',
                        'label' => 'Link Target',
                        'name' => 'link_target',
                        'type' => 'select',
                        'choices' =>
                        array (
                            '_self' => 'Standard',
                            '_blank' => 'New Window',
                            '' => '',
                        ),
                        'default_value' => '',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'order_no' => 3,
                    ),
                ),
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => '+ Add Row',
            ),
        ),
        'location' =>
        array (
            'rules' =>
            array (
                0 =>
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ikit_image_gallery',
                    'order_no' => '0',
                ),
            ),
            'allorany' => 'all',
        ),
        'options' =>
        array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' =>
            array (
            ),
        ),
        'menu_order' => 0,
    ));
    register_field_group(array (
        'id' => '5107f5b223eea',
        'title' => 'Ikit Link',
        'fields' =>
        array (
            0 =>
            array (
                'key' => 'field_3',
                'label' => 'Image',
                'name' => 'image',
                'type' => 'image',
                'order_no' => 0,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'save_format' => 'url',
                'preview_size' => 'thumbnail',
            ),
            1 =>
            array (
                'key' => 'field_2',
                'label' => 'Highlighted Image',
                'name' => 'highlighted_image',
                'type' => 'image',
                'order_no' => 1,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'save_format' => 'url',
                'preview_size' => 'thumbnail',
            ),
            2 =>
            array (
                'key' => 'field_1',
                'label' => 'URL',
                'name' => 'url',
                'type' => 'text',
                'order_no' => 2,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'default_value' => '',
                'formatting' => 'none',
            ),
            3 =>
            array (
                'key' => 'field_4',
                'label' => 'Display Order',
                'name' => 'display_order',
                'type' => 'text',
                'order_no' => 3,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'default_value' => '',
                'formatting' => 'none',
            ),
        ),
        'location' =>
        array (
            'rules' =>
            array (
                0 =>
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ikit_link',
                    'order_no' => '0',
                ),
            ),
            'allorany' => 'all',
        ),
        'options' =>
        array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' =>
            array (
            ),
        ),
        'menu_order' => 0,
    ));
    register_field_group(array (
        'id' => '5107f5b226a61',
        'title' => 'Ikit Sponsor',
        'fields' =>
        array (
            0 =>
            array (
                'key' => 'field_5',
                'label' => 'Primary Image',
                'name' => 'primary_image',
                'type' => 'image',
                'order_no' => 0,
                'instructions' => 'If you are using the iKit Two theme, please upload an image double the width and height of your intended image dimensions. For example if you like to display the image as 50px by 100px, you should upload an image that is 100px by 200px. This ensures the image will not appear blurred on retina displays.',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'save_format' => 'id',
                'preview_size' => 'thumbnail',
            ),
            1 =>
            array (
                'key' => 'field_8',
                'label' => 'Secondary Image',
                'name' => 'secondary_image',
                'type' => 'image',
                'order_no' => 1,
                'instructions' => 'If you are using the iKit Two theme, please upload an image double the width and height of your intended image dimensions. For example if you like to display the image as 50px by 100px, you should upload an image that is 100px by 200px. This ensures the image will not appear blurred on retina displays.',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'save_format' => 'id',
                'preview_size' => 'thumbnail',
            ),
            2 =>
            array (
                'key' => 'field_6',
                'label' => 'URL',
                'name' => 'url',
                'type' => 'text',
                'order_no' => 2,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'default_value' => '',
                'formatting' => 'none',
            ),
            3 =>
            array (
                'key' => 'field_7',
                'label' => 'Display Order',
                'name' => 'display_order',
                'type' => 'text',
                'order_no' => 3,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
        ),
        'location' =>
        array (
            'rules' =>
            array (
                0 =>
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ikit_sponsor',
                    'order_no' => '0',
                ),
            ),
            'allorany' => 'all',
        ),
        'options' =>
        array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' =>
            array (
            ),
        ),
        'menu_order' => 0,
    ));
    register_field_group(array (
        'id' => '5107f5b2278b9',
        'title' => 'Page',
        'fields' =>
        array (
            0 =>
            array (
                'key' => 'field_11',
                'label' => 'Ikit Section',
                'name' => 'ikit_section',
                'type' => 'post_object',
                'order_no' => 0,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => '',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'post_type' =>
                array (
                    0 => 'ikit_section',
                ),
                'taxonomy' =>
                array (
                    0 => 'all',
                ),
                'allow_null' => 0,
                'multiple' => 0,
            ),
        ),
        'location' =>
        array (
            'rules' =>
            array (
                0 =>
                array (
                    'param' => 'page_type',
                    'operator' => '!=',
                    'value' => 'child',
                    'order_no' => 0,
                ),
                1 =>
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                    'order_no' => 1,
                ),
            ),
            'allorany' => 'all',
        ),
        'options' =>
        array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' =>
            array (
            ),
        ),
        'menu_order' => 0,
    ));
    register_field_group(array (
        'id' => '5107f5b227f3a',
        'title' => 'Post',
        'fields' =>
        array (
            0 =>
            array (
                'key' => 'field_10',
                'label' => 'Preview Description',
                'name' => 'preview_description',
                'type' => 'textarea',
                'order_no' => 0,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'default_value' => '',
                'formatting' => 'br',
            ),
            1 =>
            array (
                'key' => 'field_9',
                'label' => 'Image Gallery',
                'name' => 'image_gallery',
                'type' => 'repeater',
                'order_no' => 1,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'allorany' => 'all',
                    'rules' => 0,
                ),
                'sub_fields' =>
                array (
                    0 =>
                    array (
                        'key' => 'field_4f7b11d3e7729',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'save_format' => 'id',
                        'preview_size' => 'thumbnail',
                        'order_no' => 0,
                    ),
                    1 =>
                    array (
                        'key' => 'field_4f7b11d3e7737',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'default_value' => '',
                        'formatting' => 'html',
                        'order_no' => 1,
                    ),
                ),
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => '+ Add Row',
            ),
            2 =>
            array (
                'key' => 'field_101',
                'label' => 'Attribution',
                'name' => 'attribution',
                'type' => 'text',
                'order_no' => 2,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            3 =>
            array (
                'key' => 'field_180',
                'label' => 'Display Priority',
                'name' => 'display_priority',
                'type' => 'text',
                'order_no' => 3,
                'instructions' => 'Supercede ordering by date, higher values mean the item will appear ealier. Reset to 0 to disable.',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
        ),
        'location' =>
        array (
            'rules' =>
            array (
                0 =>
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                    'order_no' => '0',
                ),
            ),
            'allorany' => 'all',
        ),
        'options' =>
        array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' =>
            array (
            ),
        ),
        'menu_order' => 0,
    ));
    register_field_group(array (
        'id' => '537e6ea07e482',
        'title' => 'iKit Internal Event',
        'fields' =>
        array (
            0 =>
            array (
                'key' => 'field_256',
                'label' => 'Image Gallery',
                'name' => 'image_gallery',
                'type' => 'repeater',
                'order_no' => 0,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'sub_fields' =>
                array (
                    'field_257' =>
                    array (
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'instructions' => '',
                        'column_width' => '',
                        'save_format' => 'id',
                        'preview_size' => 'thumbnail',
                        'order_no' => 0,
                        'key' => 'field_257',
                    ),
                    'field_258' =>
                    array (
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'column_width' => '',
                        'default_value' => '',
                        'formatting' => 'html',
                        'order_no' => 1,
                        'key' => 'field_258',
                    ),
                ),
                'row_min' => 0,
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => 'Add Row',
            ),
            1 =>
            array (
                'key' => 'field_244',
                'label' => 'Preview Description',
                'name' => 'preview_description',
                'type' => 'textarea',
                'order_no' => 1,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'br',
            ),
            2 =>
            array (
                'key' => 'field_246',
                'label' => 'Start Date',
                'name' => 'start_date',
                'type' => 'date_picker',
                'order_no' => 2,
                'instructions' => '',
                'required' => 1,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'date_format' => 'yy-mm-dd',
                'display_format' => 'mm/dd/yy',
            ),
            3 =>
            array (
                'key' => 'field_247',
                'label' => 'End Date',
                'name' => 'end_date',
                'type' => 'date_picker',
                'order_no' => 3,
                'instructions' => '',
                'required' => 1,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'date_format' => 'yy-mm-dd',
                'display_format' => 'mm/dd/yy',
            ),
            4 =>
            array (
                'key' => 'field_33',
                'label' => 'Start Time',
                'name' => 'start_time',
                'type' => 'repeater',
                'order_no' => 4,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'sub_fields' =>
                array (
                    'field_46' =>
                    array (
                        'choices' =>
                        array (
                            '00' => 0,
                            '01' => 1,
                            '02' => 2,
                            '03' => 3,
                            '04' => 4,
                            '05' => 5,
                            '06' => 6,
                            '07' => 7,
                            '08' => 8,
                            '09' => 9,
                            10 => 10,
                            11 => 11,
                            12 => 12,
                            13 => 13,
                            14 => 14,
                            15 => 15,
                            16 => 16,
                            17 => 17,
                            18 => 18,
                            19 => 19,
                            20 => 20,
                            21 => 21,
                            22 => 22,
                            23 => 23,
                        ),
                        'label' => 'Hour',
                        'name' => 'hour',
                        'type' => 'select',
                        'instructions' => '',
                        'column_width' => '',
                        'default_value' => '',
                        'allow_null' => 1,
                        'multiple' => 0,
                        'order_no' => 0,
                        'key' => 'field_46',
                    ),
                    'field_47' =>
                    array (
                        'choices' =>
                        array (
                            '00' => 0,
                            '01' => 1,
                            '02' => 2,
                            '03' => 3,
                            '04' => 4,
                            '05' => 5,
                            '06' => 6,
                            '07' => 7,
                            '08' => 8,
                            '09' => 9,
                            10 => 10,
                            11 => 11,
                            12 => 12,
                            13 => 13,
                            14 => 14,
                            15 => 15,
                            16 => 16,
                            17 => 17,
                            18 => 18,
                            19 => 19,
                            20 => 20,
                            21 => 21,
                            22 => 22,
                            23 => 23,
                            24 => 24,
                            25 => 25,
                            26 => 26,
                            27 => 27,
                            28 => 28,
                            29 => 29,
                            30 => 30,
                            31 => 31,
                            32 => 32,
                            33 => 33,
                            34 => 34,
                            35 => 35,
                            36 => 36,
                            37 => 37,
                            38 => 38,
                            39 => 39,
                            40 => 40,
                            41 => 41,
                            42 => 42,
                            43 => 43,
                            44 => 44,
                            45 => 45,
                            46 => 46,
                            47 => 47,
                            48 => 48,
                            49 => 49,
                            50 => 50,
                            51 => 51,
                            52 => 52,
                            53 => 53,
                            54 => 54,
                            55 => 55,
                            56 => 56,
                            57 => 57,
                            58 => 58,
                            59 => 59,
                        ),
                        'label' => 'Minute',
                        'name' => 'minute',
                        'type' => 'select',
                        'instructions' => '',
                        'column_width' => '',
                        'default_value' => '',
                        'allow_null' => 1,
                        'multiple' => 0,
                        'order_no' => 1,
                        'key' => 'field_47',
                    ),
                ),
                'row_min' => 1,
                'row_limit' => 1,
                'layout' => 'table',
                'button_label' => 'Add Row',
            ),
            5 =>
            array (
                'key' => 'field_34',
                'label' => 'End Time',
                'name' => 'end_time',
                'type' => 'repeater',
                'order_no' => 5,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'sub_fields' =>
                array (
                    'field_48' =>
                    array (
                        'choices' =>
                        array (
                            '00' => 0,
                            '01' => 1,
                            '02' => 2,
                            '03' => 3,
                            '04' => 4,
                            '05' => 5,
                            '06' => 6,
                            '07' => 7,
                            '08' => 8,
                            '09' => 9,
                            10 => 10,
                            11 => 11,
                            12 => 12,
                            13 => 13,
                            14 => 14,
                            15 => 15,
                            16 => 16,
                            17 => 17,
                            18 => 18,
                            19 => 19,
                            20 => 20,
                            21 => 21,
                            22 => 22,
                            23 => 23,
                        ),
                        'label' => 'Hour',
                        'name' => 'hour',
                        'type' => 'select',
                        'instructions' => '',
                        'column_width' => '',
                        'default_value' => '',
                        'allow_null' => 1,
                        'multiple' => 0,
                        'order_no' => 0,
                        'key' => 'field_48',
                    ),
                    'field_49' =>
                    array (
                        'choices' =>
                        array (
                            '00' => 0,
                            '01' => 1,
                            '02' => 2,
                            '03' => 3,
                            '04' => 4,
                            '05' => 5,
                            '06' => 6,
                            '07' => 7,
                            '08' => 8,
                            '09' => 9,
                            10 => 10,
                            11 => 11,
                            12 => 12,
                            13 => 13,
                            14 => 14,
                            15 => 15,
                            16 => 16,
                            17 => 17,
                            18 => 18,
                            19 => 19,
                            20 => 20,
                            21 => 21,
                            22 => 22,
                            23 => 23,
                            24 => 24,
                            25 => 25,
                            26 => 26,
                            27 => 27,
                            28 => 28,
                            29 => 29,
                            30 => 30,
                            31 => 31,
                            32 => 32,
                            33 => 33,
                            34 => 34,
                            35 => 35,
                            36 => 36,
                            37 => 37,
                            38 => 38,
                            39 => 39,
                            40 => 40,
                            41 => 41,
                            42 => 42,
                            43 => 43,
                            44 => 44,
                            45 => 45,
                            46 => 46,
                            47 => 47,
                            48 => 48,
                            49 => 49,
                            50 => 50,
                            51 => 51,
                            52 => 52,
                            53 => 53,
                            54 => 54,
                            55 => 55,
                            56 => 56,
                            57 => 57,
                            58 => 58,
                            59 => 59,
                        ),
                        'label' => 'Minute',
                        'name' => 'minute',
                        'type' => 'select',
                        'instructions' => '',
                        'column_width' => '',
                        'default_value' => '',
                        'allow_null' => 1,
                        'multiple' => 0,
                        'order_no' => 1,
                        'key' => 'field_49',
                    ),
                ),
                'row_min' => 1,
                'row_limit' => 1,
                'layout' => 'table',
                'button_label' => 'Add Row',
            ),
            6 =>
            array (
                'key' => 'field_266',
                'label' => 'URL',
                'name' => 'url',
                'type' => 'text',
                'order_no' => 6,
                'instructions' => 'Enter the website address for the event, make sure to enter a properly formatted URL starting with http://. For example, http://myevent.com/event1.',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            7 =>
            array (
                'key' => 'field_267',
                'label' => 'URL Name',
                'name' => 'url_name',
                'type' => 'text',
                'order_no' => 7,
                'instructions' => 'Enter the name you would like to display for the website address for the event, for example, \'Register Now\', or \'More Information\'.',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            8 =>
            array (
                'key' => 'field_254',
                'label' => 'Display Priority',
                'name' => 'display_priority',
                'type' => 'text',
                'order_no' => 8,
                'instructions' => 'Supercede ordering by date, higher values mean the item will appear ealier. Reset to 0 to disable.',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            9 =>
            array (
                'key' => 'field_255',
                'label' => 'Location Name',
                'name' => 'location_name',
                'type' => 'text',
                'order_no' => 9,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            10 =>
            array (
                'key' => 'field_259',
                'label' => 'Location Address 1',
                'name' => 'location_address_1',
                'type' => 'text',
                'order_no' => 10,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            11 =>
            array (
                'key' => 'field_260',
                'label' => 'Location Address 2',
                'name' => 'location_address_2',
                'type' => 'text',
                'order_no' => 11,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            12 =>
            array (
                'key' => 'field_261',
                'label' => 'Location City',
                'name' => 'location_city',
                'type' => 'text',
                'order_no' => 12,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            13 =>
            array (
                'key' => 'field_262',
                'label' => 'Location State/Province',
                'name' => 'location_state_province',
                'type' => 'text',
                'order_no' => 13,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            14 =>
            array (
                'key' => 'field_264',
                'label' => 'Location Postal Code',
                'name' => 'location_postal_code',
                'type' => 'text',
                'order_no' => 14,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            15 =>
            array (
                'key' => 'field_263',
                'label' => 'Location Country',
                'name' => 'location_country',
                'type' => 'text',
                'order_no' => 15,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' =>
                array (
                    'status' => 0,
                    'rules' =>
                    array (
                        0 =>
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
        ),
        'location' =>
        array (
            'rules' =>
            array (
                0 =>
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ikit_event_internal',
                    'order_no' => 0,
                ),
            ),
            'allorany' => 'all',
        ),
        'options' =>
        array (
            'position' => 'normal',
            'layout' => 'no_box',
            'hide_on_screen' =>
            array (
            ),
        ),
        'menu_order' => 0,
    ));


    register_field_group(array (
        'id' => '551c220d3d9f6',
        'title' => 'iKit Person',
        'fields' => 
        array (
            0 => 
            array (
                'key' => 'field_57',
                'label' => 'Positions',
                'name' => 'positions',
                'type' => 'repeater',
                'order_no' => 0,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 
                array (
                    'status' => 0,
                    'rules' => 
                    array (
                        0 => 
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'sub_fields' => 
                array (
                    'field_58' => 
                    array (
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'column_width' => '',
                        'default_value' => '',
                        'formatting' => 'html',
                        'order_no' => 0,
                        'key' => 'field_58',
                    ),
                ),
                'row_min' => 0,
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => 'Add Row',
            ),
            1 => 
            array (
                'key' => 'field_50',
                'label' => 'Display Priority',
                'name' => 'display_priority',
                'type' => 'text',
                'order_no' => 1,
                'instructions' => 'Higher values mean the item will appear earlier.',
                'required' => 0,
                'conditional_logic' => 
                array (
                    'status' => 0,
                    'rules' => 
                    array (
                        0 => 
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'default_value' => '',
                'formatting' => 'html',
            ),
            2 => 
            array (
                'key' => 'field_51',
                'label' => 'Image Gallery',
                'name' => 'image_gallery',
                'type' => 'repeater',
                'order_no' => 2,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 
                array (
                    'status' => 0,
                    'rules' => 
                    array (
                        0 => 
                        array (
                            'field' => 'null',
                            'operator' => '==',
                            'value' => '',
                        ),
                    ),
                    'allorany' => 'all',
                ),
                'sub_fields' => 
                array (
                    'field_52' => 
                    array (
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'instructions' => '',
                        'column_width' => '',
                        'save_format' => 'id',
                        'preview_size' => 'thumbnail',
                        'order_no' => 0,
                        'key' => 'field_52',
                    ),
                    'field_53' => 
                    array (
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'column_width' => '',
                        'default_value' => '',
                        'formatting' => 'html',
                        'order_no' => 1,
                        'key' => 'field_53',
                    ),
                ),
                'row_min' => 0,
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => 'Add Row',
            ),
        ),
        'location' => 
        array (
            'rules' => 
            array (
                0 => 
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'ikit_person',
                    'order_no' => 0,
                ),
            ),
            'allorany' => 'all',
        ),
        'options' => 
        array (
            'position' => 'normal',
            'layout' => 'no_box',
            'hide_on_screen' => 
            array (
            ),
        ),
        'menu_order' => 0,
    ));


}


}

add_action('wp_loaded', 'ikit_load_acf');


?>