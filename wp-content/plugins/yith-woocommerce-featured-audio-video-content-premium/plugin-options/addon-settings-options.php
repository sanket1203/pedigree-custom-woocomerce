<?php
if( ! defined( 'ABSPATH' ) )
    exit;

$zoom_active = ywcfav_check_is_zoom_magnifier_is_active() ?  array() : array('disabled'  => 'disabled');
$addon =   array(

    'addon-settings'    =>  array(

        'addon_section_start'  =>  array(
            'name'  => __( 'YITH Zoom Magnifier Option', 'yith-woocommerce-featured-video'),
            'id'    =>  'ywcfav_addon_start',
            'type'  =>  'title'
        ),
        'addon_zoom_magnifier'   =>  array(
            'name'  =>  __('Video and Audio in the slider', 'yith-woocommerce-featured-video' ),
            'desc'  => __('It shows audios and videos in slider, replacing the featured image. This option is possible only if YITH WooCommerce Zoom Magnifier is enabled.', 'yith-woocommerce-featured-video' ),
            'id'    =>  'ywcfav_zoom_magnifer_option',
            'type'  => 'checkbox',
            'default'   =>  'no',
            'custom_attributes' => $zoom_active
        ),

        'addon_section_end'  =>  array(
            'type'  =>  'sectionend'
        ),
    )
);

return apply_filters( 'ywcfav_addons_option', $addon );

