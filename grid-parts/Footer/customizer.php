<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;
use Gridd\Grid_Part\Footer;

$sanitization = new Sanitize();

new \Kirki\Section(
	'footer',
	[
		'title'    => esc_html__( 'Footer', 'gridd' ),
		'priority' => 22,
		'panel'    => 'theme_options',
		'type'     => 'kirki-expanded',
	]
);

new \Kirki\Section(
	'footer_grid',
	[
		'title'    => esc_html__( 'Footer Grid', 'gridd' ),
		'priority' => 2,
		'panel'    => 'theme_options',
		'type'     => 'kirki-expanded',
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'  => 'footer_custom_options',
		'section'   => 'footer',
		'default'   => false,
		'transport' => 'refresh',
		'priority'  => -999,
		'choices'   => [
			'off' => esc_html__( 'Inherit Options', 'gridd' ),
			'on'  => esc_html__( 'Override Options', 'gridd' ),
		],
	]
);

Customizer::add_field(
	[
		'settings'          => 'footer_grid',
		'section'           => 'footer_grid',
		'type'              => 'gridd_grid',
		'grid-part'         => 'footer',
		'label'             => esc_html__( 'Footer Grid', 'gridd' ),
		'description'       => __( 'You can add columns and rows, define their sizes, and also add or remove grid-parts on your site. For more information and documentation on how the grid works, please read <a href="https://wplemon.github.io/gridd/the-grid-control.html" target="_blank">this article</a>.', 'gridd' ),
		'default'           => Footer::get_grid_defaults(),
		'choices'           => [
			'parts' => Footer::get_footer_grid_parts(),
		],
		'sanitize_callback' => [ $sanitization, 'grid' ],
		'priority'          => 10,
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'        => 'footer_max_width',
		'label'           => esc_html__( 'Footer Maximum Width', 'gridd' ),
		'section'         => 'footer',
		'default'         => '100%',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer.custom-options',
				'property' => '--mw',
			],
		],
		'priority'        => 20,
		'active_callback' => function() {
			return get_theme_mod( 'footer_custom_options', false );
		},
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'footer_background_color',
		'label'           => esc_html__( 'Background Color', 'gridd' ),
		'section'         => 'footer',
		'default'         => '#ffffff',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer.custom-options',
				'property' => '--bg',
			],
		],
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'priority'        => 50,
		'active_callback' => function() {
			return get_theme_mod( 'footer_custom_options', false );
		},
	]
);

new \WPLemon\Field\WCAGTextColor(
	[
		'settings'          => 'footer_color',
		'section'           => 'footer',
		'choices'           => [
			'backgroundColor' => 'footer_background_color',
			'appearance'      => 'hidden',
		],
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'priority'          => 30,
		'default'           => '#000000',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-footer.custom-options',
				'property' => '--cl',
			],
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
		'active_callback'   => function() {
			return get_theme_mod( 'footer_custom_options', false );
		},
	]
);

new \WPLemon\Field\WCAGLinkColor(
	[
		'settings'          => 'footer_links_color',
		'label'             => esc_html__( 'Links Color', 'gridd' ),
		'section'           => 'footer',
		'default'           => '#0f5e97',
		'priority'          => 40,
		'choices'           => [
			'alpha' => false,
		],
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-footer.custom-options',
				'property' => '--lc',
			],
		],
		'choices'           => [
			'backgroundColor' => 'footer_background_color',
			'textColor'       => 'footer_color',
			'linksUnderlined' => true,
			'forceCompliance' => get_theme_mod( 'target_color_compliance', 'auto' ),
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
		'active_callback'   => '__return_false',
	]
);

new \Kirki\Field\Slider(
	[
		'settings'  => 'footer_border_top_width',
		'label'     => esc_html__( 'Border-Top Width', 'gridd' ),
		'section'   => 'footer',
		'default'   => 0,
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--bt-w',
			],
		],
		'priority'  => 50,
		'choices'   => [
			'min'    => 0,
			'max'    => 30,
			'step'   => 1,
			'suffix' => 'px',
		],
		'priority'  => 70,
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'footer_border_top_color',
		'label'           => esc_html__( 'Top Border Color', 'gridd' ),
		'section'         => 'footer',
		'default'         => '',
		'priority'        => 60,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--bt-cl',
			],
		],
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'active_callback' => [
			[
				'setting'  => 'footer_border_top_width',
				'operator' => '!=',
				'value'    => 0,
			],
		],
		'priority'        => 80,
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
