<div id="rbMenu" style="display: none;">
	<div class="element">
		<h3><i class="icon-search icon-white"></i> <?php _e('Search'); ?></h3>
		<form action="<?php echo get_option('home'); ?>" method="GET">
			<input type="text" name="s" placeholder="<?php _e('Enter a keyword...'); ?>"> &nbsp;<input type="submit" value="<?php _e('Go!'); ?>" class="btn">
		</form>
	</div>
	<div class="element">
		<h3><i class="icon-folder-open icon-white"></i> <?php _e('Categories'); ?></h3>
		<?php wp_list_categories('show_count=1&title_li=&depth=1'); ?>
	</div>
</div><!-- END RBMENU -->