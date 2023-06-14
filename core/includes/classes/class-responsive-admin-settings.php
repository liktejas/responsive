<?php
/**
 * Admin settings helper
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     Responsive
 * @author      Responsive
 * @copyright   Copyright (c) 2020, Responsive
 * @link        https://www.cyberchimps.com
 * @since       Responsive 4.0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Responsive_Admin_Settings' ) ) {

	/**
	 * Responsive Admin Settings
	 */
	class Responsive_Admin_Settings {

		/**
		 * Menu page title
		 *
		 * @since 4.0.3
		 * @var array $menu_page_title
		 */
		public static $menu_page_title = 'Responsive Theme';

		/**
		 * Page title
		 *
		 * @since 4.0.3
		 * @var array $page_title
		 */
		public static $page_title = 'Responsive';

		/**
		 * Plugin slug
		 *
		 * @since 4.0.3
		 * @var array $plugin_slug
		 */
		public static $plugin_slug = 'responsive';

		/**
		 * Default Menu position
		 *
		 * @since 4.0.3
		 * @var array $default_menu_position
		 */
		public static $default_menu_position = 'themes.php';

		/**
		 * Parent Page Slug
		 *
		 * @since 4.0.3
		 * @var array $parent_page_slug
		 */
		public static $parent_page_slug = 'general';

		/**
		 * Current Slug
		 *
		 * @since 4.0.3
		 * @var array $current_slug
		 */
		public static $current_slug = 'general';

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( ! is_admin() ) {
				return;
			}

			add_action( 'after_setup_theme', __CLASS__ . '::init_admin_settings', 99 );
		}

		/**
		 * Admin settings init
		 *
		 * @since 4.0.3
		 */
		public static function init_admin_settings() {
			self::$menu_page_title = apply_filters( 'responsive_menu_page_title', __( 'Responsive Options', 'responsive' ) );
			self::$page_title      = apply_filters( 'responsive_page_title', __( 'Responsive', 'responsive' ) );
			self::$plugin_slug     = self::get_theme_page_slug();

			add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );

			add_action( 'admin_menu', __CLASS__ . '::add_admin_menu', 99 );

			// add_action( 'responsive_menu_general_action', __CLASS__ . '::general_page' );

			// add_action( 'responsive_menu_upgrade_to_pro_action', __CLASS__ . '::upgrade_to_pro_page' );

			// add_action( 'responsive_header_right_section', __CLASS__ . '::top_header_right_section' );

			// add_action( 'responsive_welcome_page_right_sidebar_content', __CLASS__ . '::responsive_welcome_page_support_section', 11 );

			// add_action( 'responsive_welcome_page_content', __CLASS__ . '::responsive_welcome_page_content' );
		}

		/**
		 * Theme options page Slug getter including White Label string.
		 *
		 * @since 4.0.3
		 * @return string Theme Options Page Slug.
		 */
		public static function get_theme_page_slug() {
			return apply_filters( 'responsive_theme_page_slug', self::$plugin_slug );
		}

		/**
		 * Enqueues the needed CSS/JS for the builder's admin settings page.
		 *
		 * @since 1.0
		 */
		public static function styles_scripts() {
			wp_enqueue_style( 'responsive-admin-settings', RESPONSIVE_THEME_URI . 'admin/css/responsive-admin-menu-page.css', array(), RESPONSIVE_THEME_VERSION );

			if ( isset( $_GET['page'] ) && 'responsive' === $_GET['page'] ) {

				wp_enqueue_script( 'responsive-getting-started-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), RESPONSIVE_THEME_VERSION, true );

				wp_enqueue_style( 'responsive-admin-getting-started', RESPONSIVE_THEME_URI . 'admin/css/responsive-getting-started-page.css', array(), RESPONSIVE_THEME_VERSION );

				wp_enqueue_script(
					'responsive-getting-started-jsfile',
					RESPONSIVE_THEME_URI . 'admin/js/responsive-getting-started.js',
					array( 'jquery' ),
					RESPONSIVE_THEME_VERSION,
					true
				);

				wp_localize_script(
					'responsive-getting-started-jsfile',
					'localize',
					array(
						'ajaxurl'        => admin_url( 'admin-ajax.php' ),
						'responsiveurl'  => RESPONSIVE_THEME_URI,
						'siteurl'        => site_url(),
						'isRSTActivated' => is_plugin_active( 'responsive-add-ons/responsive-add-ons.php' ),
					)
				);

				add_filter( 'admin_footer_text', '__return_false' );
				remove_filter( 'update_footer', 'core_update_footer' );
			}
		}

		/**
		 * Add main menu
		 *
		 * @since 1.0
		 */
		public static function add_admin_menu() {

			$parent_page    = self::$default_menu_position;
			$page_title     = self::$menu_page_title;
			$capability     = 'manage_options';
			$page_menu_slug = self::$plugin_slug;
			$page_menu_func = __CLASS__ . '::menu_callback';

			if ( apply_filters( 'responsive_dashboard_admin_menu', true ) ) {
				add_theme_page( $page_title, $page_title, $capability, $page_menu_slug, $page_menu_func );
			} else {
				do_action( 'responsivea_register_admin_menu', $parent_page, $page_title, $capability, $page_menu_slug, $page_menu_func );
			}
		}

		/**
		 * Menu callback
		 *
		 * @since 1.0
		 */
		public static function menu_callback() {
			require_once RESPONSIVE_THEME_DIR . 'admin/templates/get-started.php';
		}

		/**
		 * Include Welcome page content
		 *
		 * @since 1.2.4
		 */
		public static function responsive_welcome_page_content() {

			// Quick settings.
			$quick_settings = apply_filters(
				'responsive_quick_settings',
				array(
					'change-layout' => array(
						'title'     => __( 'Change site layout', 'responsive' ),
						'dashicon'  => 'dashicons-welcome-widgets-menus',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=responsive_layout' ),
					),
					'typography'    => array(
						'title'     => __( 'Customize fonts/typography', 'responsive' ),
						'dashicon'  => 'dashicons-editor-textcolor',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=responsive_typography' ),
					),
					'logo-favicon'  => array(
						'title'     => __( 'Upload logo & site icon', 'responsive' ),
						'dashicon'  => 'dashicons-format-image',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=title_tagline' ),
					),
					'navigation'    => array(
						'title'     => __( 'Add/edit navigation menu', 'responsive' ),
						'dashicon'  => 'dashicons-menu',
						'quick_url' => admin_url( 'customize.php?autofocus[panel]=nav_menus' ),
					),
					'header'        => array(
						'title'     => __( 'Customize header options', 'responsive' ),
						'dashicon'  => 'dashicons-editor-table',
						'quick_url' => admin_url( 'customize.php?autofocus[panel]=responsive_header' ),
					),
					'footer'        => array(
						'title'     => __( 'Customize footer options', 'responsive' ),
						'dashicon'  => 'dashicons-editor-table',
						'quick_url' => admin_url( 'customize.php?autofocus[panel]=responsive_footer' ),
					),
					'blog-layout'   => array(
						'title'     => __( 'Update blog layout', 'responsive' ),
						'dashicon'  => 'dashicons-welcome-write-blog',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=responsive_blog_layout' ),
					),
					'page'          => array(
						'title'     => __( 'Update page layout', 'responsive' ),
						'dashicon'  => 'dashicons-welcome-widgets-menus',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=responsive_page_content' ),
					),
				)
			);
			?>
			<div class="postbox responsive-quick-setting-section">
				<h2 class="handle"><span><?php esc_html_e( 'Quick Start:', 'responsive' ); ?></span></h2>
				<div class="responsive-quick-setting-section-inner">
					<?php
					if ( ! empty( $quick_settings ) ) :
						?>
						<div class="responsive-quick-links">
							<ul class="responsive-flex">
								<?php
								foreach ( (array) $quick_settings as $key => $link ) {
									echo '<li class=""><span class="dashicons ' . esc_attr( $link['dashicon'] ) . '"></span><a class="responsive-quick-setting-title" href="' . esc_url( $link['quick_url'] ) . '" target="_blank" rel="noopener">' . esc_html( $link['title'] ) . '</a></li>';
								}
								?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="postbox">
				<h2 class="handle"><?php esc_html_e( 'Community', 'responsive' ); ?></h2>
				<div class="responsive-documentation-section">
					<div class="resposive-documentation">
						<p>
							<?php esc_html_e( 'Meet the Responsive Power-users. Say hello, ask questions, give feedback, and help each other', 'responsive' ); ?>
						</p>
						<?php
						$responsive_facebook_group_link      = 'https://www.facebook.com/groups/responsive.theme';
						$responsive_facebook_group_link_text = __( 'Join Facebook Group &raquo;', 'responsive' );

						printf(
							'%1$s',
							! empty( $responsive_facebook_group_link ) ? '<a href=' . esc_url( $responsive_facebook_group_link ) . ' target="_blank" rel="noopener">' . esc_html( $responsive_facebook_group_link_text ) . '</a>' :
								esc_html( $responsive_facebook_group_link_text )
						);
						?>
					</div>
				</div>
			</div>


			<div class="postbox">
				<h2 class="handle"><?php esc_html_e( 'Feedback', 'responsive' ); ?></h2>
				<div class="responsive-review-section">
					<div class="responsive-review">
						<p>
							<?php esc_html_e( 'Hi! Thanks for using the Responsive theme. Can you please do us a favor and give us a 5-star rating? Your feedback keeps us motivated and helps us grow the Responsive community.', 'responsive' ); ?>
						</p>
						<?php
						$responsive_submit_review_link      = 'https://wordpress.org/support/theme/responsive/reviews/#new-post';
						$responsive_submit_review_link_text = __( 'Submit Review &raquo;', 'responsive' );

						printf(
							'%1$s',
							! empty( $responsive_submit_review_link ) ? '<a href=' . esc_url( $responsive_submit_review_link ) . ' target="_blank" rel="noopener">' . esc_html( $responsive_submit_review_link_text ) . '</a>' :
								esc_html( $responsive_submit_review_link_text )
						);
						?>
					</div>
				</div>
			</div>

			<div class="postbox responsive-bottom-banner" style="background-image: url(<?php echo esc_url( RESPONSIVE_THEME_URI . 'images/rst-bottom-banner.png' ); ?>);background-size:auto; background-position: center;">
				<div class="inside resposive-documentation inside-bottom-banner">

						<div class="responsive-bottom-banner-text">
						<p>
							<?php
							esc_html_e( 'Get free access to 100+ ready-to-use Elementor & Block templates.', 'responsive' );
							?>
						</p>
						</div>
						<div id="responsive-bottom-btn" class="responsive-bottom-banner-button">
							<?php echo Responsive_Plugin_Install_Helper::instance()->get_button_html( 'responsive-add-ons' ); //phpcs:ignore ?>
							<?php
							$responsive_facebook_group_link = 'https://wordpress.org/plugins/responsive-add-ons';
							?>
						</div>
					<div>
					</div>
				</div>
			</div>

			<?php
		}

		/**
		 * Responsive Header Right Section Links
		 *
		 * @since 4.0.3
		 */
		public static function top_header_right_section() {

			$top_links = apply_filters(
				'responsive_header_top_links',
				array(
					'responsive-theme-info' => array(
						'title' => __( 'Blazing Fast, mobile-friendly, fully-customizable WordPress theme.', 'responsive' ),
					),
				)
			);

			if ( ! empty( $top_links ) ) {
				?>
				<div class="responsive-top-links">
					<ul>
						<?php
						foreach ( (array) $top_links as $key => $info ) {
							if ( isset( $info['url'] ) ) {
								printf(
									/* translators: %1$s: Top Link URL wrapper, %2$s: Top Link URL, %3$s: Top Link URL target attribute */
									'<li><%1$s %2$s %3$s > %4$s </%1$s>',
									'a',
									'href="' . esc_url( $info['url'] ) . '"',
									'target="_blank" rel="noopener"',
									esc_html( $info['title'] )
								);
							} else {
								printf(
									/* translators: %1$s: Top Link URL wrapper, %2$s: Top Link URL, %3$s: Top Link URL target attribute */
									'<li><%1$s %2$s %3$s > %4$s </%1$s>',
									'span',
									'',
									'',
									esc_html( $info['title'] )
								);
							}
						}
						?>
					</ul>
				</div>
				<?php
			}
		}
	}

	new Responsive_Admin_Settings();
}
