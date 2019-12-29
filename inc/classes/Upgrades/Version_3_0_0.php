<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Run upgrades for version 3.0.0
 *
 * @since 3.0.0
 * @package gridd
 */

namespace Gridd\Upgrades;

/**
 * The upgrade object.
 *
 * @since 3.0.0
 */
class Version_3_0_0 {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function __construct() {
		$this->run_update();
	}

	/**
	 * Runs the update.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function run_update() {
		$this->cleanup_theme_mods_names();
		$this->custom_options();
		$this->rename_mods();
	}

	/**
	 * Clean-up theme-mods names.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function cleanup_theme_mods_names() {
		$theme_mods = get_theme_mods();
		if ( $theme_mods && is_array( $theme_mods ) ) {
			foreach ( $theme_mods as $key => $val ) {
				if ( 0 === strpos( $key, 'grid_part_details_' ) || 0 === strpos( $key, 'gridd_grid_' ) ) {
					$new_key = str_replace(
						[ 'grid_part_details_', 'gridd_grid_' ],
						[ '', '' ],
						$key
					);

					if ( ! isset( $theme_mods[ $new_key ] ) ) {
						set_theme_mod( $new_key, $val );
					}
				}
			}
		}
	}

	/**
	 * Handle modified defaults.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function custom_options() {
		$custom_options = [
			'breadcrumbs_custom_options',
			'content_custom_options',
			'footer_copyright_custom_options',
			'footer_social_media_custom_options',
			'footer_custom_options',
			'branding_custom_options',
			'header_contact_info_custom_options',
			'header_search_custom_options',
			'header_social_custom_options',
			'header_custom_options',
		];

		foreach ( $custom_options as $name ) {
			set_theme_mod( $name, true );
		}

		$number = \Gridd\Grid_Part\Navigation::get_number_of_nav_menus();
		for ( $i = 1; $i <= $number; $i++ ) {
			set_theme_mod( "nav_{$i}_custom_options", true );
		}

		$number = \Gridd\Grid_Part\Footer::get_number_of_sidebars();
		for ( $i = 1; $i <= $number; $i++ ) {
			set_theme_mod( "footer_sidebar_{$i}_custom_options", true );
		}

		$reusable_blocks = \Gridd\Grid_Part\ReusableBlock::get_reusable_blocks();
		if ( $reusable_blocks ) {
			foreach ( $reusable_blocks as $block ) {
				set_theme_mod( "reusable_block_{$block->ID}_custom_options", true );
			}
		}
	}

	/**
	 * Rename specific theme-mods.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function rename_mods() {
		$mods = [
			'gridd_header_grid' => 'header_grid',
			'gridd_footer_grid' => 'footer_grid',
		];

		foreach ( $mods as $old => $new ) {
			$old_val = get_theme_mod( $old, '__UNSET' );
			if ( '__UNSET' !== $old_val ) {
				set_theme_mod( $new, $old_val );
			}
		}
	}
}
