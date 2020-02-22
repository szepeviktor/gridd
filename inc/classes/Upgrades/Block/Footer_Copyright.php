<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Migrate arbitrary content to blocks
 *
 * @since 3.0.0
 * @package gridd
 */

namespace Gridd\Upgrades\Block;

/**
 * The block-migrator object.
 *
 * @since 3.0.0
 */
class Footer_Copyright extends \Gridd\Upgrades\Block_Migrator {

	/**
	 * The block slug.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $slug = 'gridd-footer-copyright';

	/**
	 * The block title.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $title = 'Gridd: Footer Copyright';

	/**
	 * Whether we should run the upgrade or not.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return bool
	 */
	protected function should_migrate() {

		// Get the footer grid.
		$footer_grid = get_theme_mod( 'footer_grid', \Gridd\Grid_Part\Footer::get_grid_defaults() );

		// Check if we have a footer-copyright part in our footer grid.
		return ( $footer_grid && isset( $footer_grid['areas'] ) && isset( $footer_grid['areas']['footer_copyright'] ) );
	}

	/**
	 * Get the contents of the block we want to add.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return string
	 */
	protected function get_content() {
		$content = get_theme_mod(
			'gridd_copyright_text',
			sprintf(
				/* translators: 1: CMS name, i.e. WordPress. 2: Theme name, 3: Theme author. */
				__( 'Proudly powered by %1$s | Theme: %2$s by %3$s.', 'gridd' ),
				'<a href="https://wordpress.org/">WordPress</a>',
				'Gridd',
				'<a href="https://wplemon.com/">wplemon.com</a>'
			)
		);
		$background_color = get_theme_mod( 'footer_copyright_bg_color', '#ffffff' );
		$text_color       = get_theme_mod( 'footer_copyright_color', '#000000' );

		// Get the final content from our HTML file.
		ob_start();
		include 'header-contact-info.html';
		$final_content = ob_get_clean();

		// Replace placeholders with actual values.
		$final_content = str_replace( 'BACKGROUND_COLOR', esc_attr( $background_color ), $final_content );
		$final_content = str_replace( 'TEXT_COLOR', esc_attr( $text_color ), $final_content );
		$final_content = str_replace( 'CONTENT', wpautop( $content ), $final_content );

		return $final_content;
	}

	/**
	 * Additional things to run after the block migration.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param int $block_id The block ID.
	 * @return void
	 */
	public function after_block_migration( $block_id ) {
		$footer_grid = get_theme_mod( 'footer_grid', \Gridd\Grid_Part\Header::get_grid_defaults() );

		// Replace footer-copyright with the new, reusable block.
		$footer_grid['areas'][ sanitize_key( 'reusable_block_' . $block_id ) ] = $footer_grid['areas']['footer_copyright'];
		unset( $footer_grid['areas']['footer_copyright'] );

		// Update the footer_grid theme-mod.
		set_theme_mod( 'footer_grid', $footer_grid );
	}
}