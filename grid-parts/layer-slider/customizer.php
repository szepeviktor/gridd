<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;

gridd_add_customizer_section(
	'gridd_grid_part_details_layer-slider',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Layer Slider', 'gridd' ) ),
		'description' => Customizer::section_description( false, 'https://wplemon.com/documentation/gridd/grid-parts/layer-slider/' ),
		'panel'       => 'gridd_options',
	]
);

$sliders       = \LS_Sliders::find();
$sliders_array = [
	'' => esc_html__( 'Choose a slider', 'gridd' ),
];
foreach ( $sliders as $slider ) {
	$sliders_array[ $slider['id'] ] = $slider['name'];
}
gridd_add_customizer_field(
	[
		'type'            => 'select',
		'settings'        => 'gridd_grid_layerslider_slider',
		'label'           => esc_html__( 'Choose Slider', 'gridd' ),
		// 'description'     => esc_html__( 'Select the slider you want to assign to this area.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_layer-slider',
		'default'         => '',
		'priority'        => 10,
		'transport'       => 'refresh',
		'choices'         => $sliders_array,
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'gridd_grid_layerslider_slider_template' => [
				'selector'            => '.gridd-tp-layer-slider',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'layer-slider' );
				},
			],
		],
	]
);
