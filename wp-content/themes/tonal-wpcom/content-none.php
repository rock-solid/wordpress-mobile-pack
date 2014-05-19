<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tonal
 */
?>

<section class="hentry no-results not-found">
	<header class="fullwidth-block page-header">
		<h1 class="page-title"><?php _e( 'Nothing Found', 'tonal' ); ?></h1>
	</header><!-- .fullwidth-block .page-header -->

	<div class="center-block page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'tonal' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'tonal' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'tonal' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .center-block .page-content -->
</section><!-- .hentry .no-results .not-found -->
