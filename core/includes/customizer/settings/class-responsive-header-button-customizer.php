<?php
/**
 * Header Social Icons
 *
 * @package Responsive
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Responsive_Header_Button_Customizer' ) ) :
	/**
	 * Header Social Icons Customizer Options
	 */
	class Responsive_Header_Button_Customizer {
		/**
		 * Constructor
		 *
		 * @since 1.0.5
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customizer_options' ) );
		}
        /**
		 * Customizer options
		 *
		 * @since 0.2
		 *
		 * @param  object $wp_customize WordPress customization option.
		 */
		public function customizer_options( $wp_customize ) {
			$wp_customize->add_section(
				'responsive_header_button',
				array(
					'title'    => __( 'Header Button', 'responsive' ),
					'panel'    => 'responsive_header',
					'priority' => 10, 
				)
			);
            $tabs_label      = esc_html__( 'Tabs', 'responsive' );
            $tab_ids_prefix  = 'customize-control-';
            $general_tab_ids  = array(
				$tab_ids_prefix . 'responsive_header_button_label',
				$tab_ids_prefix . 'responsive_header_button_label_text_separator',
				$tab_ids_prefix . 'responsive_header_button_url',
				$tab_ids_prefix . 'responsive_header_button_url_text_separator',
				$tab_ids_prefix . 'responsive_header_button_open_in_new_tab',
				$tab_ids_prefix . 'responsive_header_button_open_new_tab_separator',
				$tab_ids_prefix . 'responsive_header_button_set_nofollow',
				$tab_ids_prefix . 'responsive_header_button_set_nofollow_separator',
				$tab_ids_prefix . 'responsive_header_button_set_sponsored',
				$tab_ids_prefix . 'responsive_header_button_set_sponsored_separator',
				$tab_ids_prefix . 'responsive_header_button_set_download',
				$tab_ids_prefix . 'responsive_header_button_set_download_separator',
				$tab_ids_prefix . 'responsive_header_button_style',
				$tab_ids_prefix . 'responsive_header_button_visibility',
			);
			$design_tab_ids = array(
				$tab_ids_prefix . 'responsive_header_button_size',
				$tab_ids_prefix . 'responsive_header_button_button_style_separator',
				$tab_ids_prefix . 'responsive_header_button_padding',
				$tab_ids_prefix . 'responsive_header_button_size_separator',
				$tab_ids_prefix . 'responsive_header_button_color',
				$tab_ids_prefix . 'responsive_header_button_color_separator',
				$tab_ids_prefix . 'responsive_header_button_bg_color',
				$tab_ids_prefix . 'responsive_header_button_bg_color_separator',
				$tab_ids_prefix . 'responsive_header_button_size_border_style',
				$tab_ids_prefix . 'responsive_header_button_border_color',
				$tab_ids_prefix . 'responsive_border_header_button_radius',
				$tab_ids_prefix . 'responsive_header_button_border_separator',
				$tab_ids_prefix . 'responsive_header_button_typography_group',
				$tab_ids_prefix . 'responsive_header_button_typography_separator',
				$tab_ids_prefix . 'responsive_header_button_margin_padding',
			);

			responsive_tabs_button_control( $wp_customize, 'header_social_tabs', $tabs_label, 'responsive_header_button', 10, '', 'responsive_social_general_tab', 'responsive_social_design_tab', $general_tab_ids, $design_tab_ids, null );

			$wp_customize->add_setting(
				'responsive_header_button_label',
				array(
					'default'           => 'Button',
					'sanitize_callback' => 'wp_check_invalid_utf8',
					'type'              => 'theme_mod',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'responsive_header_button_label',
				array(
					'label'    => __( 'Label', 'responsive' ),
					'section'  => 'responsive_header_button',
					'settings' => 'responsive_header_button_label',
					'type'     => 'text',
					'priority' => 15,
				)
			);

			responsive_horizontal_separator_control( $wp_customize, 'header_button_label_text_separator', 1, 'responsive_header_button',16, 1 );

			$wp_customize->add_setting(
				'responsive_header_button_url',
				array(
					'default'           => '',
					'sanitize_callback' => 'wp_check_invalid_utf8',
					'type'              => 'theme_mod',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'responsive_header_button_url',
				array(
					'label'    => __( 'URL', 'responsive' ),
					'section'  => 'responsive_header_button',
					'settings' => 'responsive_header_button_url',
					'type'     => 'text',
					'priority' => 20,
				)
			);

			responsive_horizontal_separator_control( $wp_customize, 'header_button_url_text_separator', 1, 'responsive_header_button',21, 1 );

			responsive_toggle_control( $wp_customize, 'header_button_open_in_new_tab', __( 'Open in New Tab?', 'responsive' ), 'responsive_header_button', 25, 0, null, 'refresh' );
			
			responsive_horizontal_separator_control( $wp_customize, 'header_button_open_new_tab_separator', 1, 'responsive_header_button', 26, 1 );

			responsive_toggle_control( $wp_customize, 'header_button_set_nofollow', __( 'Set link to nofollow?', 'responsive' ), 'responsive_header_button', 30, 0, null, 'refresh' );

			responsive_horizontal_separator_control( $wp_customize, 'header_button_set_nofollow_separator', 1, 'responsive_header_button', 31, 1 );

			responsive_toggle_control( $wp_customize, 'header_button_set_sponsored', __( 'Set link attribute Sponsored?', 'responsive' ), 'responsive_header_button', 35, 0, null, 'refresh' );

			responsive_horizontal_separator_control( $wp_customize, 'header_button_set_sponsored_separator', 1, 'responsive_header_button', 36, 1 );

			responsive_toggle_control( $wp_customize, 'header_button_set_download', __( 'Set link to Download?', 'responsive' ), 'responsive_header_button', 40, 0, null, 'refresh' );

			responsive_horizontal_separator_control( $wp_customize, 'header_button_set_download_separator', 1, 'responsive_header_button', 41, 1 );

			$responsive_header_button_style = array(
				'filled'   => esc_html__( 'Filled', 'responsive' ),
				'outlined' => esc_html__( 'Outlined', 'responsive' ),
			);
			responsive_select_button_control( $wp_customize, 'header_button_style', __( 'Button Style', 'responsive' ), 'responsive_header_button', 45, $responsive_header_button_style, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_style' ), null );

			responsive_horizontal_separator_control( $wp_customize, 'header_button_button_style_separator', 1, 'responsive_header_button', 46, 1 );

			$responsive_header_button_visibility = array(
				'everyone'   => esc_html__( 'Everyone', 'responsive' ),
				'logged-in'  => esc_html__( 'Logged in only', 'responsive' ),
				'logged-out' => esc_html__( 'Logged out only', 'responsive' ),
			);
			responsive_select_button_control( $wp_customize, 'header_button_visibility', __( 'Button Visibility', 'responsive' ), 'responsive_header_button', 45, $responsive_header_button_visibility, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_visibility' ), null );

			$responsive_header_button_size = array(
				'sm'     => esc_html__( 'SM', 'responsive' ),
				'md'     => esc_html__( 'MD', 'responsive' ),
				'lg'     => esc_html__( 'LG', 'responsive' ),
				'custom' => esc_html__( 'Custom', 'responsive' ),
			);
			responsive_select_button_control( $wp_customize, 'header_button_size', __( 'Button Size', 'responsive' ), 'responsive_header_button', 45, $responsive_header_button_size, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_size' ), null );

			responsive_horizontal_separator_control( $wp_customize, 'header_button_size_separator', 1, 'responsive_header_button', 46, 1 );

			responsive_padding_control( $wp_customize, 'header_button', 'responsive_header_button', 50, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_padding_y' ), Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_padding_x' ), '', __( 'Padding', 'responsive' ) );

			responsive_horizontal_separator_control( $wp_customize, 'header_button_size_separator', 1, 'responsive_header_button', 51, 1 );

			responsive_color_control( $wp_customize, 'header_button', __( 'Color', 'responsive' ), 'responsive_header_button', 52, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_color' ), null, '', true, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_color_hover' ), '_header_button_hover' );

			responsive_horizontal_separator_control( $wp_customize, 'header_button_color_separator', 1, 'responsive_header_button', 52, 1 );

			responsive_color_control( $wp_customize, 'header_button_bg', __( 'Background Color', 'responsive' ), 'responsive_header_button', 53, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_bg_color' ), null, '', true, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_bg_color_hover' ), 'header_button_bg_hover' );

			responsive_horizontal_separator_control( $wp_customize, 'header_button_bg_color_separator', 1, 'responsive_header_button', 53, 1 );

			$responsive_header_button_border_style = array(
				'none'   => esc_html__( 'None', 'responsive' ),
				'solid'  => esc_html__( 'Solid', 'responsive' ),
				'dashed' => esc_html__( 'Dashed', 'responsive' ),
				'dotted' => esc_html__( 'Dotted', 'responsive' ),
				'double' => esc_html__( 'Double', 'responsive' ),
			);
			responsive_select_button_control( $wp_customize, 'header_button_size_border_style', __( 'Border Style', 'responsive' ), 'responsive_header_button', 54, $responsive_header_button_border_style, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_size_border_style' ), null );

			responsive_color_control( $wp_customize, 'header_button_border', __( 'Border Color', 'responsive' ), 'responsive_header_button', 54, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_border_color' ), null, '', true, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_border_color_hover' ), 'header_button_border_hover' );

			responsive_radius_control( $wp_customize, 'header_button_radius', 'responsive_header_button', 55, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_radius_y' ), Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_radius_x' ), null, __( 'Border Radius', 'responsive' ) );

			responsive_horizontal_separator_control( $wp_customize, 'header_button_border_separator', 1, 'responsive_header_button', 55, 1 );

			responsive_typography_group_control( $wp_customize, 'header_button_typography_group', __( 'Font', 'responsive' ), 'responsive_header_button', 60, 'header_button_typography' );

			responsive_horizontal_separator_control( $wp_customize, 'header_button_typography_separator', 1, 'responsive_header_button', 60, 1 );

			responsive_padding_control( $wp_customize, 'header_button_margin', 'responsive_header_button', 62, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_margin_y' ), Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_button_margin_x' ), '', __( 'Margin', 'responsive' ) );
		
		}

	}
endif;
return new Responsive_Header_Button_Customizer();