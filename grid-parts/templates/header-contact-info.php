<?php
/**
 * Template part for the Header Contact Info.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;
use Gridd\Theme;

$style = Style::get_instance( 'grid-part/header/contact-info' );
$style->add_file( get_theme_file_path( 'grid-parts/styles/header/styles-contact-info.min.css' ) );
$style->add_vars(
	[
		'--h-cnt-bg'         => get_theme_mod( 'gridd_grid_part_details_header_contact_info_background_color', '#ffffff' ),
		'--h-cnt-fs'  => get_theme_mod( 'gridd_grid_part_details_header_contact_info_font_size', .85 ) . 'em',
		'--h-cnt-ta' => get_theme_mod( 'gridd_grid_part_details_header_contact_text_align', 'flex-start' ),
		'--h-cnt-pd'    => get_theme_mod( 'gridd_grid_part_details_header_contact_info_padding', '10px' ),
		'--h-cnt-cl'      => get_theme_mod( 'gridd_grid_part_details_header_contact_info_text_color', '#000000' ),
	]
);
// Print styles.
$style->the_css( 'gridd-inline-css-header-contact-info' );
?>

<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-header_contact_info' ], 'wrapper-header_contact_info' ); ?>>
	<?php
	/**
	 * Print the text entered by the user.
	 */
	echo wp_kses_post( get_theme_mod( 'gridd_grid_part_details_header_contact_info', __( 'Email: <a href="mailto:contact@example.com">contact@example.com</a>. Phone: +1-541-754-3010', 'gridd' ) ) );
	?>
</div>
