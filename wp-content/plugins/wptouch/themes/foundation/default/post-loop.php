<!-- simple post loop -->
<div class="post-meta">
	<?php wptouch_the_time(); ?> // <?php the_author(); ?>
</div>

<h2 class="post-title heading-font">
	<a href="<?php wptouch_the_permalink(); ?>"><?php the_title(); ?></a>
</h2>

<div class="post-content">
	<?php wptouch_the_excerpt(); ?>
</div>