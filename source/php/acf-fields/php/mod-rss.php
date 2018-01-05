<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_5a4e3c8ce5cf2',
    'title' => __('Rss Feeds', 'modularity-testimonials'),
    'fields' => array(
        0 => array(
            'key' => 'field_5a4e3c9a10aa7',
            'label' => __('Add rss feeds', 'modularity-testimonials'),
            'name' => 'mod_rss',
            'type' => 'repeater',
            'instructions' => __('Add one or more rss-feeds to display them', 'modularity-testimonials'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 1,
            'max' => 10,
            'layout' => 'table',
            'button_label' => __('Add feed', 'modularity-testimonials'),
            'sub_fields' => array(
                0 => array(
                    'key' => 'field_5a4e3ca510aa8',
                    'label' => __('Rss Url', 'modularity-testimonials'),
                    'name' => 'mod_rss_url',
                    'type' => 'url',
                    'instructions' => __('Enter a valid rss-feed', 'modularity-testimonials'),
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                ),
            ),
        ),
        1 => array(
            'key' => 'field_5a4f3861781a9',
            'label' => __('Limit to max number of inlays', 'modularity-testimonials'),
            'name' => 'mod_rss_limit',
            'type' => 'number',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 7,
            'placeholder' => __('Enter a number for max limit', 'modularity-testimonials'),
            'prepend' => '',
            'append' => __('number of inlays', 'modularity-testimonials'),
            'min' => 1,
            'max' => 20,
            'step' => 1,
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-rssfeed',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));
}