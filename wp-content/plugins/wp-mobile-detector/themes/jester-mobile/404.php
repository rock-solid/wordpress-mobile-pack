<?php get_header(); ?>
<div class="websitez-container">
	<div class="post" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="post-wrapper">
			<h2>404 Error</h2>
			<p>This page does not exist.</p>
			<p>Please try one of the following:</p>
			<ul>
        <li>Hit the "back" button on your browser.</li>
        <li>Head on over to the <a href="<?php bloginfo('url'); ?>">front page</a>.</li>
        <li>Try searching using the form in the sidebar.</li>
        <li>Click on a link in the sidebar.</li>
        <li>Use the navigation menu at the top of the page.</li>
      </ul>
		</div>
	</div>
</div>
<?php get_footer(); ?>      