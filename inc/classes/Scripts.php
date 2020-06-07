<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Enqueue scripts & styles.
 *
 * @package Gridd
 */

namespace Gridd;

/**
 * Template handler.
 *
 * @since 1.0
 */
class Scripts {

	/**
	 * An array of async scripts.
	 *
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private $async_scripts = [
		'comment-reply',
	];

	/**
	 * An array of widgets for which the CSS has already been added.
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private static $widgets = [];

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		$this->async_scripts = apply_filters( 'gridd_async_scripts', $this->async_scripts );

		add_filter( 'script_loader_tag', [ $this, 'add_async_attribute' ], 10, 2 );

		add_action( 'wp_print_footer_scripts', [ $this, 'inline_scripts' ] );

		add_action( 'wp_head', [ $this, 'inline_styles' ] );

		// Admin styles for the aditor.
		if ( ! get_theme_mod( 'disable_editor_styles' ) ) {
			add_action( 'admin_footer', [ $this, 'admin_footer_editor_styles' ] );
		}

		// Add inline scripts.
		add_action( 'gridd_footer_inline_scripts', [ $this, 'add_user_agent_inline_script' ] );

		// Add widget styles.
		add_filter( 'gridd_widget_output', [ $this, 'widget_output' ], 10, 4 );

		add_filter( 'kirki_global_dynamic_css', [ $this, 'add_vars_defaults' ] );
	}

	/**
	 * Add scripts inline.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function inline_scripts() {

		// An array of scripts to print.
		$scripts = [
			get_theme_file_path( 'assets/js/skip-link.min.js' ),
			get_theme_file_path( 'assets/js/nav.min.js' ),
			get_theme_file_path( 'assets/js/responsive-videos.min.js' ),
			get_theme_file_path( 'assets/js/inline-css-to-vars.min.js' ),
		];

		// Comments.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			$scripts[] = wp_scripts()->registered['comment-reply']->src;
		}

		$scripts = apply_filters( 'gridd_footer_inline_script_paths', $scripts );

		echo '<script>';
		foreach ( $scripts as $path ) {
			if ( file_exists( $path ) ) {
				include $path;
			}
		}
		echo '</script>';
	}

	/**
	 * Add async to scripts.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string
	 */
	public function add_async_attribute( $tag, $handle ) {
		foreach ( $this->async_scripts as $script ) {
			if ( $script === $handle ) {
				return str_replace( ' src', ' async="async" src', $tag );
			}
		}
		return $tag;
	}

	/**
	 * Inline stylesheets builder.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function inline_styles() {

		\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/styles.min.css' ) );
		\Gridd\CSS::add_file(
			get_theme_file_path( 'assets/css/styles-small.min.css' ),
			'only screen and (max-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . ')'
		);
		\Gridd\CSS::add_file(
			get_theme_file_path( 'assets/css/styles-large.min.css' ),
			'only screen and (min-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . ')'
		);

		if ( is_rtl() ) {
			\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/styles-rtl.min.css' ) );
		}

		// Styles specific to the customizer preview pane.
		if ( is_customize_preview() ) {
			\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/customizer/preview.min.css' ) );
		}

		// Adminbar.
		if ( is_admin_bar_showing() ) {
			\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/adminbar.min.css' ) );
		}

		// Comments.
		if ( is_singular() && comments_open() ) {
			\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/core/comments.min.css' ) );

			if ( class_exists( 'Akismet' ) ) {
				\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/plugins/akismet.min.css' ) );
			}
		}

		// Post-formats for singular posts.
		if ( is_singular() && has_post_format( [ 'aside', 'chat', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ] ) ) {
			\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/core/singular-post-formats.min.css' ) );
		}

		// Post-formats for post-archives.
		if ( is_post_type_archive( 'post' ) || is_home() ) {
			\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/core/archive-post-formats.min.css' ) );
		}

		// Infinite-scroll.
		if ( class_exists( 'Jetpack' ) && \Jetpack::is_module_active( 'infinite-scroll' ) ) {
			\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/core/infinite-scroll.min.css' ) );
		}

		// Elementor.
		if ( class_exists( 'Elementor\Plugin' ) ) {
			\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/plugins/elementor.min.css' ) );
			if ( current_user_can( 'edit_posts' ) ) {
				\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/plugins/elementor-editor.min.css' ) );
			}
		}

		// Additional styles if the current user can edit a post.
		if ( current_user_can( 'edit_posts' ) ) {
			\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/core/can-edit-post.min.css' ) );
		}

		\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/core/inline-icons.min.css' ) );
		\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/buttons.min.css' ) );
		\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/core/media.min.css' ) );
		\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/core/nav-links.min.css' ) );

		echo '<script>window.griddPalette={};';
		$palette = Theme::get_color_palette();
		foreach ( $palette as $color ) {
			echo 'window.griddPalette["' . esc_attr( $color['slug'] ) . '"]="' . esc_attr( $color['color'] ) . '";';
		}
		echo '</script>';
	}

	/**
	 * Add extra styles for the editor.
	 * This time we'll be directly outputing our styles in the admin footer.
	 *
	 * @access public
	 * @since 1.0.3
	 * @return void
	 */
	public function admin_footer_editor_styles() {
		global $content_width;
		echo '<style>:root{--mw-c:' . absint( $content_width ) . 'px;}</style>';
	}

	/**
	 * Add user-agent classes in the <body>.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function add_user_agent_inline_script() {
		echo 'if(window.navigator.userAgent.indexOf(\'Trident/\')>0){document.body.classList.add(\'ua-trident\');}';
		echo 'if(window.navigator.userAgent.indexOf(\'MSIE \')>0){document.body.classList.add(\'ua-msie\');}';
		echo 'if(window.navigator.userAgent.indexOf(\'Edge/\')>0){document.body.classList.add(\'ua-edge\');}';
	}

	/**
	 * Add CSS for widgets.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $widget_output  The widget's output.
	 * @param string $widget_id_base The widget's base ID.
	 * @param string $widget_id      The widget's full ID.
	 * @param string $sidebar_id     The current sidebar ID.
	 * @return string
	 */
	public function widget_output( $widget_output, $widget_id_base, $widget_id, $sidebar_id ) {

		// If CSS for this widget-type has already been added there's no need to add it again.
		if ( in_array( $widget_id_base, self::$widgets, true ) ) {
			return $widget_output;
		}

		$styles = '';

		switch ( $widget_id_base ) {
			case 'nav_menu':
				\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/nav.min.css' ) );
				\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/nav-vertical.min.css' ) );
				break;

			default:
				$style_path = get_theme_file_path( 'assets/css/widgets/widget-' . str_replace( '_', '-', $widget_id_base ) . '.min.css' );
				if ( file_exists( $style_path ) ) {
					\Gridd\CSS::add_file( $style_path );
				}
		}

		// If this is the 1st widget we're adding, include the global styles for widgets.
		if ( empty( self::$widgets ) ) {
			\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/widgets/widgets.min.css' ) );
		}

		// Add the widget to the array of available widgets to prevent adding multiple instances of this CSS.
		self::$widgets[] = $widget_id_base;

		// Return the widget output, with the CSS prepended.
		return $styles . $widget_output;
	}

	/**
	 * Add css-vars defaults.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param string $styles The kirki dynamic styles.
	 * @return string
	 */
	public function add_vars_defaults( $styles ) {
		ob_start();
		include get_theme_file_path( 'assets/css/vars.min.css' );
		$vars = ob_get_clean();
		return $vars . $styles;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
