<?php
/**
 * Helper functions
 *
 * Helper functions for breadcrumb, social icons.
 *
 * @package        Responsive
 * @license        license.txt
 * @copyright      2014 CyberChimps
 * @since          1.9.5.0
 *
 * Please do not edit this file. This file is part of the Responsive and all modifications
 * should be made in a child theme.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Breadcrumb Lists
 * Load the plugin from the plugin that is installed.
 */
function responsive_get_breadcrumb_lists() {
	$responsive_options = get_option( 'responsive_theme_options' );
	$yoast_options      = get_option( 'wpseo_titles' );
	if ( 0 === $responsive_options['breadcrumb'] ) {
		return;
	} elseif ( function_exists( 'bcn_display' ) ) {
		echo '<span class="breadcrumb" typeof="v:Breadcrumb">';
		bcn_display();
		echo '</span>';
	} elseif ( function_exists( 'breadcrumb_trail' ) ) {
		breadcrumb_trail();
	} elseif ( function_exists( 'yoast_breadcrumb' ) && true === $yoast_options['breadcrumbs-enable'] ) {
		yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
	} else {
		responsive_breadcrumb_lists();
	}

}

/**
 * Checks if Yoast breadcrumbs are enabled.
 *
 * Retrieves Yoast SEO options and verifies if breadcrumbs are enabled
 * and the `yoast_breadcrumb` function exists.
 *
 * @return bool True if Yoast breadcrumbs are enabled, false otherwise.
 */
function responsive_check_yoast_enabled_breadcrumbs() {
	$yoast_options = get_option( 'wpseo_titles' );
	if ( function_exists( 'yoast_breadcrumb' ) && true === $yoast_options['breadcrumbs-enable'] ) {
		return true;
	}
	return false;
}

/**
 * Breadcrumb Lists
 * Allows visitors to quickly navigate back to a previous section or the root page.
 *
 * Adopted from Dimox
 */
if ( ! function_exists( 'responsive_breadcrumb_lists' ) ) {

	/**
	 * Breadcrumb Lists
	 * Allows visitors to quickly navigate back to a previous section or the root page.
	 */
	function responsive_breadcrumb_lists() {
		/* === OPTIONS === */
		$text['home'] = _x( 'Home', 'Text for Home link Breadcrumb', 'responsive' ); // text for the 'Home' link.
		/* translators: %s: Categories */
		$text['category'] = _x( 'Archive for %s', 'Text for a Category page Breadcrumb', 'responsive' ); // text for a category page.
		/* translators: %s: Search result page */
		$text['search'] = _x( 'Search results for: %s', 'Text for a Serch Results Breadcrumb', 'responsive' ); // text for a search results page.
		/* translators: %s: Post Pages */
		$text['tag'] = _x( 'Posts tagged %s', 'Text for a Tag page Breadcrumb', 'responsive' ); // text for a tag page.
		/* translators: %s: Author pages */
		$text['author'] = _x( 'View all posts by %s', 'Text for an Author page Breadcrumb', 'responsive' ); // text for an author page.
		$text['404']    = _x( 'Error 404', 'Text for a 404 page Breadcrumb', 'responsive' ); // text for the 404 page.

		$show['current'] = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show.
		$show['home']    = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show.

		$responsive_breadcrumb_separator = get_theme_mod( 'responsive_breadcrumb_separator', 'rsaquo' );

		$responsive_breadcrumb_unicode	= get_theme_mod('responsive_breadcrumb_unicode');
		$unicode_lower=strtolower($responsive_breadcrumb_unicode);
		$unicode_entity = ($unicode_lower=='\\' || $unicode_lower=='')?'\\':str_replace('\\','&#x',$unicode_lower).';';

		if($responsive_breadcrumb_separator!='unicode'){
			$delimiter=' <span class="chevron">&'.$responsive_breadcrumb_separator.';</span> ';
		}else{
			$delimiter=' <span class="chevron">'.$unicode_entity.'</span> ';
		}

		$before    = '<span class="breadcrumb-current">'; // tag before the current crumb.
		$after     = '</span>'; // t    ag after the current crumb.
		/* === END OF OPTIONS === */
		$position    = 1;
		$home_link   = home_url( '/' );
		$before_link = '<span class="breadcrumb" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		$after_link  = '</span>';
		$link_att    = '';
		$link        = $before_link . '<meta itemprop="position" content="' . $position . '" /><a itemprop="item"' . $link_att . ' href="%1$s"><span itemprop="name">%2$s</span></a>' . $after_link;

		$post      = get_queried_object();
		$parent_id = isset( $post->post_parent ) ? $post->post_parent : '';

		$html_output = '';

		if ( is_front_page() ) {
			if ( 1 === $show['home'] ) {
				$html_output .= '<div class="breadcrumb-list">' . sprintf( $link, $home_link, $text['home'] ) ;
			}
		} else {
			$html_output .= '<div class="breadcrumb-list">' . sprintf( $link, $home_link, $text['home'] ) . $delimiter;

			if ( is_home() ) {
				if ( 1 === $show['current'] ) {
					$html_output .= $before . get_the_title( get_option( 'page_for_posts', true ) ) . $after;
				}
			} elseif ( is_category() ) {
				$this_cat = get_category( get_query_var( 'cat' ), false );
				if ( 0 !== $this_cat->parent ) {
					++$position;
					$parent_all   = explode( '/', get_category_parents( $this_cat->parent ) );
					$parent_count = count( $parent_all ) - 1;
					$cats         = get_category_parents( $this_cat->parent, true, $delimiter );
					$cats         = str_replace( '<a', $before_link . '<meta itemprop="position" content="' . $position . '" /><a itemprop="item"' . $link_att, $cats );
					$cats         = str_replace( '</a>', '</a>' . $after_link, $cats );
					for ( $i = 0; $i < $parent_count; $i++ ) {
						$to_be_replaced = $parent_all[ $i ] . '</a>';
						$cats           = str_replace( $to_be_replaced, '<span itemprop="name">' . $parent_all[ $i ] . '</span></a>' . $after_link, $cats );
					}
					$html_output .= $cats;

				}
				$html_output .= $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;

			} elseif ( is_search() ) {
				$html_output .= $before . sprintf( $text['search'], get_search_query() ) . $after;

			} elseif ( is_day() ) {
				++$position;
				$html_output .= sprintf( $before_link . '<meta itemprop="position" content="' . $position . '" /><a itemprop="item"' . $link_att . ' href="%1$s"><span itemprop="name">%2$s</span></a>' . $after_link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $delimiter;

				++$position;
				$html_output .= sprintf( $before_link . '<meta itemprop="position" content="' . $position . '" /><a itemprop="item"' . $link_att . ' href="%1$s"><span itemprop="name">%2$s</span></a>' . $after_link, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ) ) . $delimiter;
				$html_output .= $before . get_the_time( 'd' ) . $after;

			} elseif ( is_month() ) {
				++$position;

				$html_output .= sprintf( $before_link . '<meta itemprop="position" content="' . $position . '" /><a itemprop="item"' . $link_att . ' href="%1$s"><span itemprop="name">%2$s</span></a>' . $after_link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $delimiter;
				$html_output .= $before . get_the_time( 'F' ) . $after;

			} elseif ( is_year() ) {
				$html_output .= $before . get_the_time( 'Y' ) . $after;

			} elseif ( is_single() && ! is_attachment() ) {
				if ( 'post' !== get_post_type() ) {
					$post_type    = get_post_type_object( get_post_type() );
					$archive_link = get_post_type_archive_link( $post_type->name );
					$html_output .= sprintf( $link, $archive_link, $post_type->labels->singular_name );
					if ( 1 === $show['current'] ) {
						$html_output .= $delimiter . $before . get_the_title() . $after;
					}
				} else {
					++$position;
					$cat     = get_the_category();
					$count   = $cat[0]->count;
					$cats    = get_category_parents( $cat[0], true, $delimiter );
					$term_id = $cat[0]->term_id;
					while ( $term_id ) {
						// Get the parent term.
						$term    = get_term( $term_id );
						$name    = $term->name;
						$cats    = str_replace( $name . '</a>', '<span itemprop="name">' . $name . '</span></a>', $cats );
						$term_id = $term->parent;
					}

					if ( 0 === $show['current'] ) {
						$cats = preg_replace( "#^(.+)$delimiter$#", '$1', $cats );
					}
					$cats = str_replace( '<a', $before_link . '<meta itemprop="position" content="' . $position . '" /><a itemprop="item"' . $link_att, $cats );
					$cats = str_replace( '</a>', '</a>' . $after_link, $cats );

					$html_output .= $cats;

					if ( 1 === $show['current'] ) {
						$html_output .= $before . get_the_title() . $after;
					}
				}
			} elseif ( ! is_single() && ! is_page() && ! is_404() && 'post' !== get_post_type() ) {
				$post_type    = get_post_type_object( get_post_type() );
				$html_output .= $before . $post_type->labels->singular_name . $after;

			} elseif ( is_attachment() ) {
				$parent = get_post( $parent_id );
				$cat    = get_the_category( $parent->ID );

				if ( isset( $cat[0] ) ) {
					$cat = $cat[0];
				}

				if ( $cat ) {
					++$position;
					$cats         = get_category_parents( $cat, true, $delimiter );
					$cats         = str_replace( '<a', $before_link . '<meta itemprop="position" content="' . $position . '" /><a itemprop="item"' . $link_att, $cats );
					$cats         = str_replace( '</a>', '</a>' . $after_link, $cats );
					$cats         = str_replace( $cat->name, '<span itemprop="name">' . $cat->name . '</span>' . $after_link, $cats );
					$html_output .= $cats;
				}

				$html_output .= sprintf( $link, get_permalink( $parent ), $parent->post_title );
				if ( 1 === $show['current'] ) {
					$html_output .= $delimiter . $before . get_the_title() . $after;
				}
			} elseif ( is_page() && ! $parent_id ) {
				if ( 1 === $show['current'] ) {
					$html_output .= $before . get_the_title() . $after;
				}
			} elseif ( is_page() && $parent_id ) {
				$breadcrumbs = array();
				while ( $parent_id ) {
					$page_child    = get_post( $parent_id );
					$breadcrumbs[] = sprintf( $link, get_permalink( $page_child->ID ), get_the_title( $page_child->ID ) );
					$parent_id     = $page_child->post_parent;
				}
				$breadcrumbs = array_reverse( $breadcrumbs );
				for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
					$html_output .= $breadcrumbs[ $i ];
					if ( $i !== count( $breadcrumbs ) - 1 ) {
						$html_output .= $delimiter;
					}
				}
				if ( 1 === $show['current'] ) {
					$html_output .= $delimiter . $before . get_the_title() . $after;
				}
			} elseif ( is_tag() ) {
				$html_output .= $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;

			} elseif ( is_author() ) {
				$user_id      = get_query_var( 'author' );
				$userdata     = get_the_author_meta( 'display_name', $user_id );
				$html_output .= $before . sprintf( $text['author'], $userdata ) . $after;

			} elseif ( is_404() ) {
				$html_output .= $before . $text['404'] . $after;

			}

			if ( get_query_var( 'paged' ) || get_query_var( 'page' ) ) {
				$page_num = get_query_var( 'page' ) ? get_query_var( 'page' ) : get_query_var( 'paged' );
				/* translators: %s: Page Number */
				$html_output .= $delimiter . sprintf( _x( 'Page %s', 'Text for a page Breadcrumb', 'responsive' ), $page_num );

			}
		}
		
		$html_output .= '</div>';

        echo $html_output; // phpcs:ignore
	} // end responsive_breadcrumb_lists.
}

/**
 * Use shortcode_atts_gallery filter to add new defaults to the WordPress gallery shortcode.
 * Allows user input in the post gallery shortcode.
 *
 * @param string $out Output.
 * @param string $pairs Pairs.
 * @param array  $atts Attributes.
 */
function responsive_gallery_atts( $out, $pairs, $atts ) {

	$full_width = is_page_template( 'full-width-page.php' ) || is_page_template( 'landing-page.php' );

	// Check if the size attribute has been set, if so use it and skip the responsive sizes.
	if ( array_key_exists( 'size', $atts ) ) {
		$size = $atts['size'];
	} else {

		if ( $full_width ) {
			switch ( $out['columns'] ) {
				case 1:
					$size = 'responsive-900'; // 900.
					break;
				case 2:
					$size = 'responsive-450'; // 450.
					break;
				case 3:
					$size = 'responsive-300'; // 300.
					break;
				case 4:
					$size = 'responsive-200'; // 225.
					break;
				case 5:
					$size = 'responsive-200'; // 180.
					break;
				case 6:
					$size = 'responsive-150'; // 150.
					break;
				case 7:
					$size = 'responsive-150'; // 125.
					break;
				case 8:
					$size = 'responsive-150'; // 112.
					break;
				case 9:
					$size = 'responsive-100'; // 100.
					break;
			}
		} else {
			switch ( $out['columns'] ) {
				case 1:
					$size = 'responsive-600'; // 600.
					break;
				case 2:
					$size = 'responsive-300'; // 300.
					break;
				case 3:
					$size = 'responsive-200'; // 200.
					break;
				case 4:
					$size = 'responsive-150'; // 150.
					break;
				case 5:
					$size = 'responsive-150'; // 120.
					break;
				case 6:
					$size = 'responsive-100'; // 100.
					break;
				case 7:
					$size = 'responsive-100'; // 85.
					break;
				case 8:
					$size = 'responsive-100'; // 75.
					break;
				case 9:
					$size = 'responsive-100'; // 66.
					break;
			}
		}
	}

	$atts = shortcode_atts(
		array(
			'size' => $size,
		),
		$atts
	);

	$out['size'] = $atts['size'];

	return $out;

}

add_filter( 'shortcode_atts_gallery', 'responsive_gallery_atts', 10, 3 );

/**
 * Create image sizes for the galley
 */
function responsive_add_image_size() {
	add_image_size( 'responsive-100', 100, 9999 );
	add_image_size( 'responsive-150', 150, 9999 );
	add_image_size( 'responsive-200', 200, 9999 );
	add_image_size( 'responsive-300', 300, 9999 );
	add_image_size( 'responsive-450', 450, 9999 );
	add_image_size( 'responsive-600', 600, 9999 );
	add_image_size( 'responsive-900', 900, 9999 );
}

add_action( 'after_setup_theme', 'responsive_add_image_size' );

/**
 * [responsive_get_social_icons description]
 *
 * @return void [description].
 */
function responsive_get_social_icons( $area ) {

	$responsive_options = Responsive\Core\responsive_get_options();

	$icons = array(
		'twitter'       => __( 'Twitter', 'responsive' ),
		'facebook'      => __( 'Facebook', 'responsive' ),
		'linkedin'      => __( 'LinkedIn', 'responsive' ),
		'youtube'       => __( 'YouTube', 'responsive' ),
		'stumbleupon'   => __( 'StumbleUpon', 'responsive' ),
		'rss'           => __( 'RSS Feed', 'responsive' ),
		'instagram'     => __( 'Instagram', 'responsive' ),
		'pinterest'     => __( 'Pinterest', 'responsive' ),
		'yelp'          => __( 'Yelp!', 'responsive' ),
		'vimeo'         => __( 'Vimeo', 'responsive' ),
		'foursquare'    => __( 'Foursquare', 'responsive' ),
		'email'         => __( 'Email', 'responsive' ),
		'bandcamp'      => __( 'Bandcamp', 'responsive' ),
		'behance'       => __( 'Behance', 'responsive' ),
		'discord'       => __( 'Discord', 'responsive' ),
		'github'        => __( 'Github', 'responsive' ),
		'googlereviews' => __( 'Googlereviews', 'responsive' ),
		'medium'        => __( 'Medium', 'responsive' ),
		'patreon'       => __( 'Patreon', 'responsive' ),
		'phone'         => __( 'Phone', 'responsive' ),
		'reddit'        => __( 'Reddit', 'responsive' ),
		'soundcloud'    => __( 'Soundcloud', 'responsive' ),
		'spotify'       => __( 'Spotify', 'responsive' ),
		'telegram'      => __( 'Telegram', 'responsive' ),
		'threads'       => __( 'Threads', 'responsive' ),
		'tiktok'        => __( 'Tiktok', 'responsive' ),
		'vk'            => __( 'VK', 'responsive' ),
		'whatsapp'      => __( 'Whatsapp', 'responsive' ),
		'wordpress'     => __( 'Wordpress', 'responsive' ),
		'custom1'       => __( 'Custom1', 'responsive' ),
		'custom2'       => __( 'Custom2', 'responsive' ),
		'custom3'       => __( 'Custom3', 'responsive' ),
	);

	$count = 0;

	foreach ( $icons as $key => $value ) {
		if ( ! empty( $responsive_options[ $key . '_uid' ] ) ) {
			$count++;
		}
	}
	
	$header_social = get_theme_mod( 'responsive_header_social_items' );
	$social_icons_sequence = array();

	if ( ! empty( $header_social['items'] ) ) {

		$header_social_items = $header_social['items'];
		foreach ( $header_social_items as $social_item ) {
			$social_icons_sequence[] = $social_item['id'];
		}
		if ( $count > 0 && $area === '_header' || $count > 0 && $area === '_footer' ) {
			require get_stylesheet_directory() . '/core/includes/responsive-icon-library.php'
			?>
			<div class="header-layouts social-icon">
				<ul class="social-icons">
					<?php
					$target_social_link = get_theme_mod( 'responsive_social_link_new_tab', '_self' );
					$is_show_label = get_theme_mod( 'responsive_header_social_show_label' );
					foreach ( $social_icons_sequence as $key ) {
						$icon_source = 'icon';
						$icon_url = '';
						$icon_svg = '';
						$icon_type = '';
						$icon_label = '';
						$icon_width = 24;
						foreach ( $header_social_items as $social_item ) {
							if ( $social_item['id'] === $key ) {
								$icon_source = $social_item['source'];
								$icon_url = $social_item['url'];
								$icon_svg = $social_item['svg'];
								$icon_type = $social_item['icon'];
								$icon_label = $social_item['label'];
								$icon_width = $social_item['width'];
							}
						}
						if ( ! empty( $responsive_options[ $key . '_uid' ] ) ) {
							$use_brand_colors = get_theme_mod( 'responsive_header_social_item_use_brand_colors', 'no' );
							$brand_color = '';
							$brand_svg = '';
							if ( 'yes' === $use_brand_colors ) {
								$brand_color = ' responsive-social-icon-anchor-' . esc_html( $key );
								$brand_svg = ' responsive-social-icon-wrapper-brand';
							}
							if ( 'on-hover' === $use_brand_colors ) {
								$brand_color = ' responsive-social-icon-anchor-hover-' . esc_html( $key );
								$brand_svg = ' responsive-social-icon-wrapper-brand-hover';
							}
							if ( 'until-hover' === $use_brand_colors ) {
								$brand_color = ' responsive-social-icon-anchor-until-hover-' . esc_html( $key );
							}
							?>
							<li class="responsive-social-icon responsive-social-icon-<?php echo esc_html( $key ); ?>">
								<a class="responsive-social-icon-anchor<?php echo $brand_color; ?>" aria-label=<?php echo esc_attr( $key ); ?> title=<?php echo esc_attr( $key ); ?> href="<?php echo esc_url( $responsive_options[ $key . '_uid' ] ); ?>" target=<?php echo esc_attr( $target_social_link ); ?> <?php responsive_schema_markup( 'url' ); ?>>
									<?php

										if ( 'icon' === $icon_source ) {
											?>
											<span class="responsive-social-icon-wrapper<?php echo esc_attr( $brand_svg ); ?>">
												<?php
												echo responsive_get_svg_icon( $icon_type );
												?>
											</span>
											<?php
										}
										if ( 'image' === $icon_source ) {
											?>
											<span class="responsive-social-icon-wrapper<?php echo esc_attr( $brand_svg ); ?>">
												<img fetchpriority="high" src="<?php echo esc_url( $icon_url ); ?>" class="responsive-social-icon-image" decoding="async" style="max-width: <?php echo esc_attr( $icon_width )?>px;">
											</span>
											<?php
										}
										if ( 'svg' === $icon_source ) {
											?>
											<span class="responsive-social-icon-wrapper<?php echo esc_attr( $brand_svg ); ?>" style="max-width: <?php echo esc_attr( $icon_width )?>px;">
												<?php
													echo $icon_svg;
												?>
											</span>
											<?php
										}
										if ( $is_show_label ) {
											?>
											<span class="responsive-social-icon-label">
												<?php
												echo esc_html( $icon_label );
												?>
											</span>
											<?php
										}
									?>
								</a>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
			<?php
		}
	}
}
