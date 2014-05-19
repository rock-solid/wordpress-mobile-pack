<?php
/**
 * @package Tonal
 */
$format = get_post_format();
$formats = get_theme_support( 'post-formats' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( $format && in_array( $format, $formats[0] ) ): ?>
		<a href="<?php echo esc_url( get_post_format_link( $format ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'All %s posts', 'tonal' ), get_post_format_string( $format ) ) ); ?>">
			<span class="screen-reader-text"><?php echo get_post_format_string( $format ); ?></span>
			<span class="entry-format icon-block"></span>
		</a>
	<?php else : ?>
		<span class="entry-format icon-block"></span>
	<?php endif; ?>

	<?php if ( comments_open() ) : ?>
		<a href="<?php comments_link(); ?>">
			<span class="comment-icon icon-block"></span>
		</a>
	<?php endif; ?>

	<?php if ( '' != get_the_post_thumbnail() && '' == $format ) : ?>
		<div class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'tonal' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="<?php the_ID(); ?>">
				<?php the_post_thumbnail( 'featured-image' ); ?>
			</a>
		</div><!-- .center-block .entry-thumbnail -->
	<?php endif; ?>

	<header class="entry-header fullwidth-block">
		<h1 class="entry-title">
			<?php the_title(); ?>
		</h1>
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
		<?php tonal_posted_on(); ?>
		<span class="cat-list">
			<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( '<span class="cat-list">', __( ', ', 'tonal' ), '</span>' );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list('<span class="tag-list">', __( ', ', 'tonal' ), '</span>' );

			if ( ! tonal_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'tonal' );
				} else {
					$meta_text = __( 'Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'tonal' );
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'tonal' );
				} else {
					$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'tonal' );
				}

			} // end check for categories on this blog*/

			printf(
				$meta_text,
				$category_list,
				$tag_list,
				get_permalink()
			);
		?>
		</span><!-- .cat-list -->
		<?php edit_post_link( __( 'Edit', 'tonal' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .center-block .entry-meta -->
</article><!-- #post-## -->
