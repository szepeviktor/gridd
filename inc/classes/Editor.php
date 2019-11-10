<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Extra bits and pieces needed for the WordPress Editor.
 *
 * @package Gridd
 * @since 2.0.0
 */

namespace Gridd;

/**
 * Extra methods and actions for the editor.
 *
 * @since 2.0.0
 */
class Editor {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_styles' ], 1, 1 );
	}

	/**
	 * Add editor styles.
	 *
	 * @access public
	 * @since 2.0.0
	 * @return void
	 */
	public function block_editor_styles() {
		wp_enqueue_style( 'gridd-editor', get_template_directory_uri() . '/assets/css/admin/editor.min.css', [], GRIDD_VERSION );
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
