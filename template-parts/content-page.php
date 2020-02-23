<?php
/**
 * Template part for displaying page content in page.php
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Blog;
use Gridd\Theme;

$parts = Blog::get_post_parts();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="contentinfo" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
	<?php foreach ( $parts as $part ) : ?>
		<?php Theme::get_template_part( 'template-parts/part-' . $part ); ?>
	<?php endforeach; ?>
</article>
