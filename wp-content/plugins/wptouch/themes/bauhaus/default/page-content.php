<div class="<?php wptouch_post_classes(); ?>">
	<div class="post-page-head-area bauhaus">
		<h2 class="post-title heading-font"><?php the_title(); ?></h2>
	</div>
	<div class="post-page-content">
		<?php if ( bauhaus_should_show_thumbnail() && wptouch_has_post_thumbnail() ) { ?>
			<div class="post-page-thumbnail">
				<?php the_post_thumbnail('large', array( 'class' => 'post-thumbnail wp-post-image' ) ); ?>
			</div>
		<?php } ?>
		<?php wptouch_the_content() ; ?>
	</div>
</div>