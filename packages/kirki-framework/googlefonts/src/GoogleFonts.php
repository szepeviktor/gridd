<?php
/**
 * Processes typography-related fields
 * and generates the google-font link.
 *
 * @package   kirki-framework/googlefonts
 * @author    Ari Stathopoulos (@aristath)
 * @copyright Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license   https://opensource.org/licenses/MIT
 * @since     0.1
 */

namespace Kirki;

/**
 * Manages the way Google Fonts are enqueued.
 */
final class GoogleFonts {

	/**
	 * An array of our google fonts.
	 *
	 * @static
	 * @access public
	 * @since 0.1
	 * @var array
	 */
	public static $google_fonts;

	/**
	 * The class constructor.
	 *
	 * @access public
	 * @since 0.1
	 */
	public function __construct() {
		add_action( 'wp_ajax_kirki_fonts_google_all_get', [ $this, 'print_googlefonts_json' ] );
		add_action( 'wp_ajax_nopriv_kirki_fonts_google_all_get', [ $this, 'print_googlefonts_json' ] );
	}

	/**
	 * Prints the googlefonts JSON file.
	 *
	 * @since 0.1
	 * @param bool $die Whether the script should exit or not.
	 * @return void
	 */
	public function print_googlefonts_json( $die = true ) {
		include 'webfonts.json'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
		if ( function_exists( 'wp_die' ) && $die ) {
			wp_die();
		}
	}

	/**
	 * Returns the array of googlefonts from the JSON file.
	 *
	 * @since 0.1
	 * @return array
	 */
	public function get_array() {
		ob_start();
		include 'webfonts.json'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
		return json_decode( ob_get_clean(), true );
	}

	/**
	 * Return an array of all available Google Fonts.
	 *
	 * @access public
	 * @since 0.1
	 * @return array All Google Fonts.
	 */
	public function get_google_fonts() {

		// Get fonts from cache.
		self::$google_fonts = get_site_transient( 'kirki_googlefonts_cache' );

		// If cache is populated, return cached fonts array.
		if ( self::$google_fonts ) {
			return self::$google_fonts;
		}

		// If we got this far, cache was empty so we need to get from JSON.
		$fonts = $this->get_array();

		self::$google_fonts = [];
		if ( is_array( $fonts ) ) {
			foreach ( $fonts['items'] as $font ) {
				self::$google_fonts[ $font['family'] ] = [
					'label'    => $font['family'],
					'variants' => $font['variants'],
					'category' => $font['category'],
				];
			}
		}

		// Apply the 'kirki_fonts_google_fonts' filter.
		self::$google_fonts = apply_filters( 'kirki_fonts_google_fonts', self::$google_fonts );

		// Save the array in cache.
		$cache_time = apply_filters( 'kirki_googlefonts_transient_time', HOUR_IN_SECONDS );
		set_site_transient( 'kirki_googlefonts_cache', self::$google_fonts, $cache_time );

		return self::$google_fonts;
	}

	/**
	 * Returns an array of google-fonts matching our arguments.
	 *
	 * @access public
	 * @since 0.1
	 * @param array $args The arguments.
	 * @return array
	 */
	public function get_google_fonts_by_args( $args = [] ) {
		$cache_name = 'kirki_googlefonts_' . md5( wp_json_encode( $args ) );
		$cache      = get_site_transient( $cache_name );
		if ( $cache ) {
			return $cache;
		}

		$args['sort'] = isset( $args['sort'] ) ? $args['sort'] : 'alpha';

		$fonts         = $this->get_array();
		$ordered_fonts = $fonts['order'][ $args['sort'] ];
		if ( isset( $args['count'] ) ) {
			set_site_transient( $cache_name, $ordered_fonts, HOUR_IN_SECONDS );
			$ordered_fonts = array_slice( $ordered_fonts, 0, $args['count'] );
			return $ordered_fonts;
		}
		set_site_transient( $cache_name, $ordered_fonts, HOUR_IN_SECONDS );
		return $ordered_fonts;
	}
}
