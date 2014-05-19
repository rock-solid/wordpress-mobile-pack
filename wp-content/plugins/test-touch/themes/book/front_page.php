<?php 
global $is_ajax;
$is_ajax = isset( $_SERVER['HTTP_X_REQUESTED_WITH'] );

if( ! $is_ajax ) { get_header(); }
?>

<div class="front-page-content">

	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>">
			<div class="entry-wrapper">
				<div class="entry-content"><?php the_content(); ?></div>
			</div>
		</article>

	<?php endwhile; ?>

</div><!-- .front-page-content -->

<?php if( ! $is_ajax ) { get_footer(); } ?>