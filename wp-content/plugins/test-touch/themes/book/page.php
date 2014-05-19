<?php 
global $is_ajax;
$is_ajax = isset( $_SERVER['HTTP_X_REQUESTED_WITH'] );

if( ! $is_ajax ) { get_header(); }
?>

<div class="single-content">

	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>">
			<div class="entry-wrapper">
				<div class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</div>
		
				<div class="entry-content"><?php the_content(); ?></div>
			</div>
		</article>

	<?php endwhile; ?>

</div><!-- .single-content -->

<?php if( ! $is_ajax ) { get_footer(); } ?>