<div class="fdn-colors">
	<div class="dropdown">
		<p class="dropdown-toggle" data-toggle="dropdown" href="#"><span style="background: <?php wptouch_admin_the_setting_value(); ?>;"></span><?php wptouch_admin_the_setting_desc(); ?></p>
		<ul class="dropdown-menu" role="menu">
			<p>
				<span class="pull-right"><a href="#" class="reset-color" data-original-color="<?php wptouch_admin_the_setting_value(); ?>"><?php _e( 'Undo', 'wptouch-pro' ); ?></a></span>
				<a href="#" rel="desktop-colors-ul" class="tabbed active"><?php _e( 'Desktop theme colors', 'wptouch-pro' ); ?></a> | <a href="#" rel="palette" class="tabbed"><?php _e( 'Palette', 'wptouch-pro' ); ?></a>
			</p>
			<?php $colors = wptouch_get_desktop_theme_colors(); ?>
			<?php if ( count( $colors ) ) { ?>
			<ul class="desktop-colors-ul">
				<?php foreach( $colors as $color ) { ?>
					<li class="desktop-colors-color" data-background="<?php echo $color; ?>"></li>
				<?php } ?>
			</ul>
			<?php } ?>
			<ul class="palette">
				<li>
					<div class="colorpicker" id="color-<?php wptouch_admin_the_encoded_setting_name(); ?>" data-target="<?php wptouch_admin_the_encoded_setting_name(); ?>"></div>
					<input type="text" id="<?php wptouch_admin_the_encoded_setting_name(); ?>" class="selected-color <?php wptouch_admin_the_encoded_setting_name(); ?>" name="<?php wptouch_admin_the_encoded_setting_name(); ?>" value="<?php wptouch_admin_the_setting_value(); ?>" />
				</li>
			</ul>
 		</ul><!-- dropdown-menu -->
	</div><!-- dropdown -->
</div><!-- fdn-colors -->