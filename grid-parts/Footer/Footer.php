<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Gridd Footer grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Theme;
use Gridd\Grid;
use Gridd\Grid_Parts;
use Gridd\Grid_Part;
use Gridd\Rest;

/**
 * The Gridd\Grid_Part\Breadcrumbs object.
 *
 * @since 1.0
 */
class Footer extends Grid_Part {

	/**
	 * The grid-part ID.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $id = 'footer';

	/**
	 * Returns the grid-part definition.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_part() {
		$this->part = [
			'label'    => esc_html__( 'Footer', 'gridd' ),
			'color'    => [ '#1565C0', '#fff' ],
			'priority' => 1000,
			'id'       => 'footer',
			'grid'     => 'footer_grid',
		];
	}

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function init() {
		$this->register_rest_api_partials();
		add_action( 'widgets_init', [ $this, 'register_footer_sidebars' ], 30 );
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
		add_action( 'gridd_the_partial', [ $this, 'the_partial' ] );
	}

	/**
	 * Render this grid-part.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	public function render( $part ) {
		if ( $this->id !== $part ) {
			return;
		}
		if ( Rest::is_partial_deferred( $this->id ) ) {
			echo '<div class="gridd-tp gridd-tp-' . esc_attr( $this->id ) . ' gridd-rest-api-placeholder"></div>';
			return;
		}
		$this->the_partial( $this->id );
	}

	/**
	 * Renders the grid-part partial.
	 *
	 * @access public
	 * @since 1.1
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	public function the_partial( $part ) {
		if ( $this->id === $part ) {
			Theme::get_template_part( 'grid-parts/Footer/' . str_replace( 'footer', 'template', $this->id ) );
		}
	}

	/**
	 * Get the default value for the footer grid.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public static function get_grid_defaults() {
		return [
			'rows'         => 2,
			'columns'      => 3,
			'areas'        => [
				'footer_sidebar_1' => [
					'cells' => [ [ 1, 1 ] ],
				],
				'footer_sidebar_2' => [
					'cells' => [ [ 1, 2 ] ],
				],
				'footer_sidebar_3' => [
					'cells' => [ [ 1, 3 ] ],
				],
			],
			'gridTemplate' => [
				'rows'    => [ 'auto', 'auto' ],
				'columns' => [ '1fr', '1fr', '1fr', '1fr' ],
			],
		];
	}

	/**
	 * Register the sidebars.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function register_footer_sidebars() {

		$sidebars_nr = self::get_number_of_sidebars();
		for ( $i = 1; $i <= $sidebars_nr; $i++ ) {
			register_sidebar(
				[
					/* translators: Sidebar number, */
					'name'          => sprintf( esc_attr__( 'Footer Widget Area %d', 'gridd' ), absint( $i ) ),
					'id'            => "footer_sidebar_$i",
					'description'   => esc_html__( 'Add widgets here.', 'gridd' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				]
			);
		}
	}

	/**
	 * Gets the number of widget-areas in the footer.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 */
	public static function get_number_of_sidebars() {
		return apply_filters( 'gridd_get_number_footer_sidebars', 6 );
	}

	/**
	 * Get an array of grid-parts for the footer.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public static function get_footer_grid_parts() {
		$footer_grid_parts = [];

		$sidebars_nr = self::get_number_of_sidebars();
		for ( $i = 1; $i <= $sidebars_nr; $i++ ) {
			$footer_grid_parts[] = [
				/* translators: The widget-area number. */
				'label'    => sprintf( esc_html__( 'Footer Widget Area %d', 'gridd' ), absint( $i ) ),
				'color'    => [ 'hsl(' . ( 55 * $i - 55 ) . ',57%,75%)', '#000' ],
				'priority' => 8 + $i * 2,
				'hidden'   => false,
				'class'    => "footer_sidebar_$i",
				'id'       => "footer_sidebar_$i",
			];
		}

		// Add reusable-block parts.
		$all_parts = Grid_Parts::get_instance()->get_parts();
		foreach ( $all_parts as $part ) {
			if ( isset( $part['id'] ) && 0 === strpos( $part['id'], 'reusable_block_' ) ) {
				$footer_grid_parts[] = $part;
			}
		}

		return apply_filters( 'gridd_footer_grid_parts', $footer_grid_parts );
	}

	/**
	 * Registers the partial(s) for the REST API.
	 *
	 * @access public
	 * @since 1.1
	 * @return void
	 */
	public function register_rest_api_partials() {
		Rest::register_partial(
			[
				'id'    => 'footer',
				'label' => esc_html__( 'Footer', 'gridd' ),
			]
		);
	}
}

new Footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
