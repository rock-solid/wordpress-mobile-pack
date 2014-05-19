<?php get_header(); ?>
		
		<div id="content" class="part">
			<div id="individual_post" style="min-height: 400px;">
				<h1><?php _e('Oops!'); ?></h1>
				<p><?php _e('We\'re sorry, but we couldn\'t find what you were looking for.'); ?></p>
				<p><?php _e('Please try visiting the <a href="<?php echo get_option(\'home\'); ?>">home page</a> or try a search below:'); ?></p>
				<form action="<?php echo get_option('home'); ?>" method="GET">
					<input type="text" name="s" placeholder="<?php _e('Enter a keyword...'); ?>"> &nbsp;<input type="submit" value="<?php _e('Go!'); ?>" class="btn">
				</form>
			</div>
		</div><!-- END CONTENT -->
		
		<?php get_sidebar('right_single'); ?>
		
<?php get_footer(); ?>