<?php
/**
 * Init grid-parts.
 *
 * @since 1.0.3
 */

require_once get_theme_file_path( 'grid-parts/classes/Breadcrumbs.php' );
require_once get_theme_file_path( 'grid-parts/classes/Content.php' );
require_once get_theme_file_path( 'grid-parts/classes/Footer.php' );
require_once get_theme_file_path( 'grid-parts/classes/Header.php' );
require_once get_theme_file_path( 'grid-parts/classes/Nav_Handheld.php' );
require_once get_theme_file_path( 'grid-parts/classes/Navigation.php' );
require_once get_theme_file_path( 'grid-parts/classes/Reusable_Block.php' );
require_once get_theme_file_path( 'grid-parts/classes/Sidebar.php' );

require_once get_theme_file_path( 'grid-parts/customizer/breadcrumbs.php' );
require_once get_theme_file_path( 'grid-parts/customizer/content.php' );
require_once get_theme_file_path( 'grid-parts/customizer/footer-copyright.php' );
require_once get_theme_file_path( 'grid-parts/customizer/footer-sidebars.php' );
require_once get_theme_file_path( 'grid-parts/customizer/footer-social-media.php' );
require_once get_theme_file_path( 'grid-parts/customizer/footer.php' );
require_once get_theme_file_path( 'grid-parts/customizer/header-branding.php' );
require_once get_theme_file_path( 'grid-parts/customizer/header-contact-info.php' );
require_once get_theme_file_path( 'grid-parts/customizer/header-search.php' );
require_once get_theme_file_path( 'grid-parts/customizer/header-social-media.php' );
require_once get_theme_file_path( 'grid-parts/customizer/header.php' );
require_once get_theme_file_path( 'grid-parts/customizer/nav-handheld.php' );
require_once get_theme_file_path( 'grid-parts/customizer/navigation.php' );
require_once get_theme_file_path( 'grid-parts/customizer/reusable-block.php' );
require_once get_theme_file_path( 'grid-parts/customizer/sidebar.php' );

if ( class_exists( 'LS_Sliders' ) ) {
	require_once get_theme_file_path( 'grid-parts/classes/Layer_Slider.php' );
	require_once get_theme_file_path( 'grid-parts/customizer/layer-slider.php' );
}

if ( class_exists( 'RevSliderSlider' ) ) {
	require_once get_theme_file_path( 'grid-parts/classes/Revolution_Slider.php' );
	require_once get_theme_file_path( 'grid-parts/customizer/revolution-slider.php' );
}
