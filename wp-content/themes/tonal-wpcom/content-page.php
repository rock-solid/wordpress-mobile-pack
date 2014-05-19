<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Tonal
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header fullwidth-block">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header .fullwidth-block -->

	<div class="center-block entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'tonal' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .center-block .entry-content -->
	<footer class="center-block entry-meta">
		<?php edit_post_link( __( 'Edit', 'tonal' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
	</footer><!-- .center-block .entry-meta -->
</article><!-- #post-## -->
