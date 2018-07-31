<?php
if( !defined( 'ABSPATH' ) )
    exit;

$video_settings   =   array(

    'video-settings'  =>  array(

        'video-general_setting_section_start' =>  array(
            'name'  =>  __('General Settings', 'yith-woocommerce-featured-video' ),
            'type'  =>  'title',
            'id'    =>  'ywcfav_video-general_setting_section_start'
        ),

    	'aspectratio' => array(
    		'name' => __('Aspect Ratio', 'yith-woocommerce-featured-video'),
    		'type' => 'select',
    		'options' => array( '16_9' => '16:9', '4_3' => '4:3' ),
			'desc'	=> __( 'Choose the aspect ratio for your video', 'yith-woocommerce-featured-video' ),
    		'default' => '16_9',
    		'id' => 'ywcfav_aspectratio'				 
    	),
    		'show_controls' =>  array(
    				'name'  =>  __( 'Player Controls', 'yith-woocommerce-featured-video' ),
    				'desc'  =>  __( 'This option sets whether the player has usable controls by users. ( Only on Youtube and Video uploaded ).', 'yith-woocommerce-featured-video' ),
    				'type'  =>  'checkbox',
    				'default'   =>  'yes',
    				'id'        =>  'ywcfav_show_controls'
    		),

        'autoplay'  =>  array(
            'name'  =>  __( 'AutoPlay', 'yith-woocommerce-featured-video' ),
            'desc'  =>  __( 'If checked, the video will start playing as soon as page is loaded', 'yith-woocommerce-featured-video' ),
            'type'  =>  'checkbox',
            'default'   =>  'no',
            'id'        =>  'ywcfav_autoplay'
        ),

        'loop'  =>  array(
            'name'  =>  __( 'Loop', 'yith-woocommerce-featured-video' ),
            'desc'  =>  __( 'The loop attribute causes the video to start over as soon as it ends', 'yith-woocommerce-featured-video' ),
            'type'  =>  'checkbox',
            'default'   =>  'no',
            'id'        =>  'ywcfav_loop'
        ),
    		'volume'    =>  array(
    				'name'  =>  __( 'Volume', 'yith-woocommerce-featured-video' ),
    				'desc'  =>  __( 'Set volume, 0 mute , 1 high volume', 'yith-woocommerce-featured-video' ),
    				'type'  =>  'number',
    				'custom_attributes' =>  array(
    						'min'   =>  0,
    						'max'   =>  1,
    						'step'  =>  0.1,
    				),
    				'default'   => 0.5,
    				'id'    =>  'ywcfav_volume'
    		),
    		
    		'video_stoppable'   =>  array(
    				'name'  =>  __( 'Stoppable videos', 'yith-woocommerce-featured-video' ),
    				'desc'  =>  __( 'Allow users to pause videos', 'yith-woocommerce-featured-video' ),
    				'type'  =>  'checkbox',
    				'id'    =>  'ywcfav_video_stoppable',
    				'default'   =>  'yes'
    		),

			'video_general_setting_section_end'   =>  array(
    				'type'  =>  'sectionend',
    				'id'    =>  'ywcfav_video_general_setting_section_end'
    		),
    		
    		'youtube_settings_section_start' => array(
    				'type' => 'title',
    				'name' => __('Youtube Settings', 'yith-woocommerce-featured-video'),
    				'id' => 'ywcvaf_youtube_section_start'
    		),
    		

    		
    		'youtube_show_info' => array(
    			'name' => __('Show Info', 'yith-woocommerce-featured-video'),
    			'type' => 'checkbox',
    			'default' => 'yes',
				'desc' => __('Show video title before playing', 'yith-woocommerce-featured-video') ,
    			'id' => 'ywcfav_youtube_show_info'				
    		),
    		
    		'youtube_auto_hide' => array(
    			'name' => __('Autohide', 'yith-woocommerce-featured-video'),
    			'type' => 'checkbox',
    			'default' => 'yes',
    			'desc' => __( 'If checked, the progress bar of the video and the player controls disappear automatically a few seconds after play.', 'yith-woocommerce-featured-video'),
    			'id' => 'ywcfav_youtube_autohide'				
    		),
		'youtube_show_rel' => array(
			'name' => __( 'Show Related', 'yith-woocommerce-featured-video'),
			'type' => 'checkbox',
			'default' => 'yes',
			'desc' => __( 'If selected, it shows related videos when the video ends', 'yith-woocommerce-featured-video'),
			'id'	=> 'ywcfav_youtube_rel'
		),
    		'youtube_theme' => array(
    			'name' => __( 'Theme', 'yith-woocommerce-featued-video'),
    			'type' => 'select',
    			'options' => array( 'dark' => __('Dark', 'yith-woocommerce-featured-video' ), 'light' => __('Light', 'yith-woocommerce-featured-video' ) ),
    			'default' => 'dark',
    			'id' => 'ywcfav_youtube_theme'				
    		),
    		'youtube_color' => array(
    		  'name' => __('Color', 'yith-woocommerce-featured-video'),
    		  'type' => 'select',
    		  'options' => array( 'red' => __('Red', 'yith-woocommerce-featured-video'), 'white' => __('White', 'yith-woocommerce-featured-video') ),
    		  'default' => 'red',
    			'desc' => __( 'Sets the color used in the player progress bar', 'yith-woocommerce-featured-video'),
    		  'id' => 'ywcfav_youtube_color'		
    										
    		 ),
    		'youtube_settings_section_end' => array(
    				'type' => 'sectionend',
    				'id' => 'ywcvaf_youtube_section_end'
    		),
    		
    		'vimeo_settings_section_start' => array(
    				'type' => 'title',
    				'name' => __('Vimeo Settings', 'yith-woocommerce-featured-video'),
    				'id' => 'ywcvaf_vimeo_section_start'
    		),
    		'vimeo_show_title' => array(
    			'name' => __('Show Video Title', 'yith-woocommerce-featured-video'),
    			'type' => 'checkbox',
    			'default' => 'yes',
    			'id' => 'ywcfav_vimeo_show_title'				
    		),
    		'vimeo_color' => array(
    			'name' => __( 'Color', 'yith-woocommerce-featured-video'),
    			'type' => 'color',
    			'default' => '#00adef',
    			'id'	=>'ywcfav_vimeo_color'
    						
    		),
    		'vimeo_settings_section_end' => array(
    				'type' => 'sectionend',
    				
    				'id' => 'ywcvaf_vimeo_section_end'
    		),

        'player_style_section_start'    =>  array(
            'name'  =>  __('VideoJS Player Style', 'yith-woocommerce-featured-video'),
            'type'  =>  'title',
            'id'    =>  'ywcfav_player_style_section_start'
        ),


        'player_type_style' =>  array(
            'name'  =>  __( 'Style', 'yith-woocommerce-featured-video'),
            'type'  =>  'select',
            'options'   =>  array(
                'default'   =>  __('Default', 'yith-woocommerce-featured-video'),
                'custom'    =>  __('Custom', 'yith-woocommerce-featured-video' )
            ),
            'default'   =>  'default',
            'id'        =>  'ywcfav_player_type_style'
        ),

        'main_font_colors'  =>  array(
            'name'  =>  __( 'Main Font Colors', 'yith-woocommerce-featured-video'),
            'desc'  =>  __( 'The colors of text the icons (icon font)', 'yith-woocommerce-featured-video'),
            'type'  =>  'color',
            'id'    =>  'ywcfav_main_font_colors',
            'default'   =>  '#cccccc'
        ),

        'control_bg_color' =>  array(
            'name'  =>  __( 'Control background color', 'yith-woocommerce-featured-video'),
            'desc'  =>  __( 'The default background color of the controls is black, with a little bit of blue so it can still be seen on all black video frames, which are common.', 'yith-woocommerce-featured-video' ),
            'type'  =>  'color',
            'id'    =>  'ywcfav_control_bg_color',
            'default'   => '#07141E'

        ),
        'control_bg_color_alpha'    =>  array(
            'name'  =>  __( 'Control background Alpha', 'yith-woocommerce-featured-video' ),
            'desc'  =>  __( '1.0 = 100% opacity, 0.0 = 0% opacity', 'yith-woocommerce-featured-video'),
            'type'  =>  'number',
            'custom_attributes' =>  array(
                'min'   =>  0,
                'max'   =>  1,
                'step'  =>  0.1
            ),
            'default'   =>  0.7,
            'id'    =>  'ywcfav_control_bg_color_alpha'
        ),

        'slider_color'      =>  array(
            'name'    =>  __( 'Slider Color', 'yith-woocommerce-featured-video' ),
            'desc'    =>  __( 'The slider bar color is used for the progress bar and the volume bar', 'yith-woocommerce-featured-video' ),
            'type'    =>  'color',
            'default' =>  '#66A8CC',
            'id'     =>  'ywcfav_slider_color'
        ),

        'slider_bg_color'   =>  array(
            'name'  =>  __( 'Slider background color','yith-woocommerce-featured-video' ),

            'type'  =>  'color',
            'id'    =>  'ywcfav_slider_bg_color',
            'default'   =>  '#333333'
        ),

        'slider_bg_color_alpha'    =>  array(
            'name'  =>  __( 'Slider background Alpha', 'yith-woocommerce-featured-video' ),
            'desc'  =>  __( '1.0 = 100% opacity, 0.0 = 0% opacity', 'yith-woocommerce-featured-video'),
            'type'  =>  'number',
            'custom_attributes' =>  array(
                'min'   =>  0,
                'max'   =>  1,
                'step'  =>  0.1
            ),
            'default'   =>  0.9,
            'id'    =>  'ywcfav_slider_bg_color_alpha'
        ),

        'big_play_border_color'    =>  array(
            'name'    =>  __( 'Big Play Button Border Color', 'yith-woocommerce-featured-video' ),
            'type'    =>  'color',
            'default' =>  '#3b4249',
            'id'      =>  'ywcfav_big_play_border_color'
        ),

        'player_style_section_end'  =>  array(
            'type'  =>  'sectionend',
            'id'    =>  'ywcfav_player_style_section_end'
        )
    )

);

return apply_filters( 'ywcfav_video_settings', $video_settings );