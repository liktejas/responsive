<?php
/**
 * button Customizer Options
 *
 * @package Responsive WordPress theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Responsive_layout_Customizer' ) ) :

	class Responsive_layout_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0
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
		 * @return [type]               [description]
		 */
		public function customizer_options( $wp_customize ) {

			$wp_customize->add_panel( 'responsive-layout-options',
				array(
			 		'title' => __( 'Layout' ),
			 		'description' => 'Layout Options', // Include html tags such as <p>.
			 		'priority' => 200, // Mixed with top-level-section hierarchy.
				)
			);

			/**
			 * Section
			 */
			$wp_customize->add_section(
				'responsive_layout_section',
				array(
					'title'    => esc_html__( 'Layout', 'responsive' ),
					'panel'    => 'responsive-layout-options',
					'priority' => 200,
				)
			);
			$wp_customize->add_setting(
				'responsive_layout_styles',
				array(
					'transport' => 'refresh',
					'default'   => 'boxed',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'responsive_layout_styles',
					array(
						'label'    => __( 'Container', 'responsive' ),
						'settings' => 'responsive_layout_styles',
						'priority' => 10,
						'section'  => 'responsive_layout_section',
						'type'     => 'select',
						'choices'  => array(
							'boxed'     => 'Boxed',
							'fullwidth' => 'Fullwidth',
						),
					)
				)
			);
			/**
			 * Section
			 */
			$wp_customize->add_section(
				'responsive_single_post_section',
				array(
					'title'    => esc_html__( 'Single Post', 'responsive' ),
					'panel'    => 'responsive-layout-options',
					'priority' => 208,
				)
			);
			$wp_customize->add_setting(
				'responsive_single_post_layout',
				array(
					'transport' => 'postMessage',
					'default'   => 'minimal',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'responsive_single_post_layout',
					array(
						'label'    => __( 'Layout', 'responsive' ),
						'settings' => 'responsive_single_post_layout',
						'priority' => 10,
						'section'  => 'responsive_single_post_section',
						'type'     => 'select',
						'choices'  => array(
							'minimal'       => 'Minimal',
							'boxed'         => 'Boxed',
							'content-boxed' => 'Content Boxed',
						),
					)
				)
			);
			// $wp_customize->add_setting(
			// 	'responsive_single_post_sidebar_position',
			// 	array(
			// 		'transport' => 'postMessage',
			// 		'default'   => 'no-sidebar',
			// 	)
			// );
			// $wp_customize->add_control(
			// 	new WP_Customize_Control(
			// 		$wp_customize,
			// 		'responsive_single_post_sidebar_position',
			// 		array(
			// 			'label'    => __( 'Sidebar Position', 'responsive' ),
			// 			'settings' => 'responsive_single_post_sidebar_position',
			// 			'priority' => 10,
			// 			'section'  => 'responsive_single_post_section',
			// 			'type'     => 'select',
			// 			'choices'  => array(
			// 				'no-sidebar'         => 'No Sidebar',
			// 				'left-sidebar'       => 'Left Sidebar',
			// 				'right-sidebar'      => 'Right Sidebar',
			// 				'left-right-sidebar' => 'Left & Right Sidebar',
			// 			),
			// 		)
			// 	)
			// );
			$wp_customize->add_setting( 'responsive_theme_options[single_post_layout_default]',
				array(
					'sanitize_callback' => 'responsive_sanitize_default_layouts',
					'type' => 'option'
				)
			);
			$wp_customize->add_control( 'res_single_post_layout_default',
				array(
					'label'                 => __( 'Sidebar Position', 'responsive' ),
					'section'               => 'responsive_single_post_section',
					'settings'              => 'responsive_theme_options[single_post_layout_default]',
					'type'                  => 'select',
					'choices'               => Responsive_Options::valid_layouts()
				)
			);
			/**
			 * Blog Single Elements Positioning
			 */
			$wp_customize->add_setting(
				'responsive_blog_single_elements_positioning',
				array(
					'default'           => array( 'featured_image', 'title', 'meta', 'content', 'author_box' ),
					'sanitize_callback' => 'responsive_sanitize_multi_choices',
				)
			);

			$wp_customize->add_control(
				new Responsive_Customizer_Sortable_Control(
					$wp_customize,
					'responsive_blog_single_elements_positioning',
					array(
						'label'    => esc_html__( 'Post Elements', 'responsive' ),
						'section'  => 'responsive_single_post_section',
						'settings' => 'responsive_blog_single_elements_positioning',
						'priority' => 10,
						'choices'  => responsive_blog_single_elements(),
					)
				)
			);

			/**
			 * Blog Single Meta
			 */
			$wp_customize->add_setting(
				'responsive_blog_single_meta',
				array(
					'default'           => array( 'author', 'date', 'categories', 'comments' ),
					'sanitize_callback' => 'responsive_sanitize_multi_choices',
				)
			);

			$wp_customize->add_control(
				new Responsive_Customizer_Sortable_Control(
					$wp_customize,
					'responsive_blog_single_meta',
					array(
						'label'    => esc_html__( 'Meta Elements', 'responsive' ),
						'section'  => 'responsive_single_post_section',
						'settings' => 'responsive_blog_single_meta',
						'priority' => 10,
						'choices'  => apply_filters(
							'responsive_blog_meta_choices',
							array(
								'author'     => esc_html__( 'Author', 'responsive' ),
								'date'       => esc_html__( 'Date', 'responsive' ),
								'categories' => esc_html__( 'Categories', 'responsive' ),
								'comments'   => esc_html__( 'Comments', 'responsive' ),
							)
						),
					)
				)
			);

		}


	}

endif;

return new Responsive_layout_Customizer();
