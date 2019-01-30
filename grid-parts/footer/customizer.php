<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Grid_Part\Footer;

gridd_add_customizer_section(
	'gridd_grid_part_details_footer',
	[
		'title'       => esc_html__( 'Footer', 'gridd' ),
		'description' => Customizer::section_description(
			__( '<a href="https://wplemon.com/gridd-plus" rel="nofollow" target="_blank">Upgrade to <strong>plus</strong></a> for a separate grid for mobile devices.', 'gridd' ),
			'https://wplemon.com/documentation/gridd/grid-parts/footer/'
		),
		'priority'    => 25,
		'panel'       => 'gridd_options',
	]
);

$footer_grid_parts = [
	[
		'label'    => esc_html__( 'Copyright Area', 'gridd' ),
		'color'    => [ '#A5D6A7', '#000' ],
		'priority' => 100,
		'hidden'   => false,
		'id'       => 'footer_copyright',
	],
	[
		'label'    => esc_html__( 'Social Media', 'gridd' ),
		'color'    => [ '#8BC34A', '#000' ],
		'priority' => 200,
		'hidden'   => false,
		'id'       => 'footer_social_media',
	],
];

$sidebars_nr = Footer::get_number_of_sidebars();
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

gridd_add_customizer_field(
	[
		'settings'          => 'gridd_footer_grid',
		'section'           => 'gridd_grid_part_details_footer',
		'type'              => 'gridd_grid',
		'grid-part'         => 'footer',
		'label'             => esc_html__( 'Grid Settings', 'gridd' ),
		'description'       => __( 'Edit settings for your footer grid. For more information and documentation on how the grid works, please read <a href="https://wplemon.com/documentation/gridd/the-grid-control/" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'default'           => Footer::get_grid_defaults(),
		'choices'           => [
			'parts' => $footer_grid_parts,
		],
		'sanitize_callback' => [ gridd()->customizer, 'sanitize_gridd_grid' ],
		'transport'         => 'postMessage',
		'partial_refresh'   => [
			'gridd_footer_grid_template' => [
				'selector'            => '.gridd-tp-footer',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'footer' );
				},
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_footer_max_width',
		'label'       => esc_html__( 'Max-Width', 'gridd' ),
		'description' => esc_html__( 'The maximum width that the contents of this grid-part can use.', 'gridd' ),
		'tooltip'     => __( 'Use any valid CSS value like <code>50em</code>, <code>800px</code> or <code>100%</code>.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => '',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-footer-max-width',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_footer_grid_gap',
		'label'       => esc_html__( 'Grid Gap', 'gridd' ),
		'description' => esc_html__( 'Adds a gap between your grid-parts, both horizontally and vertically.', 'gridd' ),
		'tooltip'     => __( 'For more information please read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/gap" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => '0',
		'css_vars'    => '--gridd-footer-grid-gap',
		'transport'   => 'postMessage',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_footer_padding',
		'label'       => esc_html__( 'Padding', 'gridd' ),
		'description' => esc_html__( 'Inner padding for all parts in the footer.', 'gridd' ),
		'tooltip'     => __( 'For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => '1em',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-footer-padding',
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_footer_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'tooltip'   => esc_html__( 'Individual grid-parts can override this by setting their own background color for their area. If you are using a grid-gap the color defined here will be visible between grid-parts.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-footer-bg',
		'choices'   => [
			'alpha' => true,
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_grid_footer_border_top_width',
		'label'     => esc_html__( 'Border-Top Width', 'gridd' ),
		'tooltip'   => esc_html__( 'You can make the top border of the footer ticker or thinner by adjusting this value. Set to 0 to disable the top border.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer',
		'default'   => 1,
		'transport' => 'postMessage',
		'css_vars'  => [ '--gridd-footer-border-top-width', '$px' ],
		'priority'  => 50,
		'choices'   => [
			'min'    => 0,
			'max'    => 30,
			'step'   => 1,
			'suffix' => 'px',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'            => 'color',
		'settings'        => 'gridd_grid_footer_border_top_color',
		'label'           => esc_html__( 'Top Border Color', 'gridd' ),
		// 'description'     => esc_html__( 'Choose a color for the top border.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_footer',
		'default'         => 'rgba(0,0,0,.1)',
		'priority'        => 60,
		'transport'       => 'postMessage',
		'css_vars'        => '--gridd-footer-border-top-color',
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => 'gridd_grid_footer_border_top_width',
				'operator' => '!=',
				'value'    => 0,
			],
		],
	]
);
