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
class Widget_Area extends \Gridd\Upgrades\Block_Migrator {

	/**
	 * The widget-area slug.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $widget_area_slug;

	/**
	 * An array of our widgets.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var array
	 */
	protected $widgets;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param array $args The constructor arguments.
	 * @since 3.0.0
	 */
	public function __construct( $args ) {

		// Set the sidebar title & slug.
		$this->set_object_params( $args );
		parent::__construct();
	}

	/**
	 * Set the object params.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @param array $args The arguments passed-on from the constructor.
	 * @return void
	 */
	public function set_object_params( $args ) {
		$this->widget_area_slug = $args[0];
		$this->widgets          = $args[1];
		$this->slug             = 'gridd-' . str_replace( '_', '-', $this->widget_area_slug );
		$this->title            = 'Gridd: ' . $this->widget_area_slug;
	}

	/**
	 * Get the contents of the block we want to add.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return string
	 */
	protected function get_content() {
		global $wp_registered_widgets;

		$content = '';

		// Loop widget areas.
		foreach ( $this->widgets as $widget_id ) {

			$class       = $wp_registered_widgets[ $widget_id ]['callback'][0];
			$class_name  = get_class( $class );
			$method_name = 'get_contents_' . strtolower( $class_name );

			if ( is_callable( [ $this, $method_name ] ) ) {
				$content .= $this->$method_name( $widget_id, $class );
			}
		}

		return $content;
	}

	/**
	 * Gets the block contents for WP_Widget_Categories.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @param string               $widget_id The widget-ID.
	 * @param WP_Widget_Categories $class     The widget class.
	 * @return string
	 */
	protected function get_contents_wp_widget_archives( $widget_id, $class ) {
		$content = '';

		// Get the block settings.
		$settings = $class->get_settings()[ absint( str_replace( 'archives-', '', $widget_id ) ) ];

		// Build the arguments.
		$args = [
			'displayAsDropdown' => ( isset( $settings['dropdown'] ) && $settings['dropdown'] ),
			'showPostCounts'    => ( isset( $settings['count'] ) && $settings['count'] ),
		];

		// Add the title.
		if ( isset( $settings['title'] ) && ! empty( trim( $settings['title'] ) ) ) {
			$content = '<!-- wp:heading {"level":3} --><h3>' . $settings['title'] . '</h3><!-- /wp:heading -->';
		}

		// Add the archives block.
		$content .= '<!-- wp:archives ' . wp_json_encode( $args ) . ' /-->';

		return $content;
	}

	/**
	 * Gets the block contents for WP_Widget_Categories.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @param string               $widget_id The widget-ID.
	 * @param WP_Widget_Categories $class     The widget class.
	 * @return string
	 */
	protected function get_contents_wp_widget_categories( $widget_id, $class ) {
		$content = '';

		// Get the block settings.
		$settings = $class->get_settings()[ absint( str_replace( 'categories-', '', $widget_id ) ) ];

		// Build the arguments.
		$args = [
			'displayAsDropdown' => ( isset( $settings['dropdown'] ) && $settings['dropdown'] ),
			'showHierarchy'     => ( isset( $settings['hierarchical'] ) && $settings['hierarchical'] ),
			'showPostCounts'    => ( isset( $settings['count'] ) && $settings['count'] ),
		];

		// Add the title.
		if ( isset( $settings['title'] ) && ! empty( trim( $settings['title'] ) ) ) {
			$content = '<!-- wp:heading {"level":3} --><h3>' . $settings['title'] . '</h3><!-- /wp:heading -->';
		}

		// Add the categories block.
		$content .= '<!-- wp:categories ' . wp_json_encode( $args ) . ' /-->';

		return $content;
	}

	/**
	 * Gets the block contents for WP_Widget_Categories.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @param string               $widget_id The widget-ID.
	 * @param WP_Widget_Categories $class     The widget class.
	 * @return string
	 */
	protected function get_contents_wp_widget_media_audio( $widget_id, $class ) {
		$content = '';

		// Get the block settings.
		$settings = $class->get_settings()[ absint( str_replace( 'media_audio-', '', $widget_id ) ) ];

		// Add the title.
		if ( isset( $settings['title'] ) && ! empty( trim( $settings['title'] ) ) ) {
			$content = '<!-- wp:heading {"level":3} --><h3>' . $settings['title'] . '</h3><!-- /wp:heading -->';
		}

		$url = false;
		foreach ( [ 'mp3', 'ogg', 'flac', 'm4a', 'wav' ] as $filetype ) {
			if ( isset( $settings[ $filetype ] ) && ! empty( $settings[ $filetype ] ) ) {
				$url = $settings[ $filetype ];
			}
		}

		// Add the audio block.
		$content .= '<!-- wp:audio {"id":' . $settings['attachment_id'] . '} -->';
		$content .= '<figure class="wp-block-audio">';
		$content .= '<audio controls src="' . $url . '" autoplay';
		if ( isset( $settings['loop'] ) && $settings['loop'] ) {
			$content .= ' loop';
		}
		if ( isset( $settings['preload'] ) ) {
			$content .= ' preload="' . $settings['preload'] . '">';
		}
		$content .= '</audio></figure><!-- /wp:audio -->';

		return $content;
	}

	/**
	 * Gets the block contents for WP_Widget_Categories.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @param string               $widget_id The widget-ID.
	 * @param WP_Widget_Categories $class     The widget class.
	 * @return string
	 */
	protected function get_contents_wp_widget_calendar( $widget_id, $class ) {
		$content = '';

		// Get the block settings.
		$settings = $class->get_settings()[ absint( str_replace( 'calendar-', '', $widget_id ) ) ];

		// Add the title.
		if ( isset( $settings['title'] ) && ! empty( trim( $settings['title'] ) ) ) {
			$content = '<!-- wp:heading {"level":3} --><h3>' . $settings['title'] . '</h3><!-- /wp:heading -->';
		}

		$content .= '<!-- wp:calendar /-->';

		return $content;
	}
}
