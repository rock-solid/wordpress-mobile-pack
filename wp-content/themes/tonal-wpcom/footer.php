<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Tonal
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="fullwidth-block site-footer" role="contentinfo">

		<div class="site-info">
			<?php do_action( 'tonal_credits' ); ?>
			<a href="http://wordpress.org/" rel="generator"><?php printf( __( 'Proudly powered by %s', 'tonal' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'tonal' ), 'tonal', '<a href="http://automattic.com/" rel="designer">Automattic</a>' ); ?>
		</div><!-- .site-info -->

	</footer><!-- #colophon .fullwidth-block .site-footer -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>