<?php
/*
	Mobile Template: Home Page
*/
?>
<?php get_header(); ?>

<?php if ( have_posts() ) { ?>
<div id="content">
	<div class="post-content">
		<?php wptouch_the_content(); ?>
	</div>
</div>
<?php } ?>

<?php get_footer(); ?>

