<?php
/**
 * @package Tonal
 */
$format = get_post_format();
$formats = get_theme_support( 'post-formats' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( 'post' == get_post_type() && $format && in_array( $format, $formats[0] ) ): ?>
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
		</div><!-- .entry-thumbnail .fullwidth-block -->
	<?php endif; ?>

	<header class="entry-header fullwidth-block">
		<h1 class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h1>
	</header><!-- .entry-header .fullwidth-block -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="center-block entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .center-block .entry-summary -->
	<?php else : ?>
		<div class="center-block entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'tonal' ) ); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'tonal' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .center-block .entry-content -->
	<?php endif; ?>

	<footer class="center-block entry-meta">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>

			<?php tonal_posted_on(); ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'tonal' ) );
				if ( $categories_list && tonal_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'tonal' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'tonal' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'tonal' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'tonal' ), __( '1 Comment', 'tonal' ), __( '% Comments', 'tonal' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'tonal' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
