<div id="pack-set-menu-area">
	<div id="right-area" class="section clearfix">
		<div class="header">
			<ul id="menu-set-options">
				<li><a href="#" class="check-all"><?php _e( 'Check All', 'wptouch-pro' ); ?></a></li> |
				<li><a href="#" class="check-none"><?php _e( 'None', 'wptouch-pro' ); ?></a></li> |
				<li><a href="#" class="reset-all"><?php _e( 'Reset', 'wptouch-pro' ); ?></a></li>
			</ul>
			<?php _e( 'Menu', 'wptouch-pro' ); ?>

			<select name="menu-list" id="menu-list">
				<?php global $wptouch_pro; ?>
				<?php foreach( $wptouch_pro->theme_menus as $menu_info ) { ?>
					<?php $real_name = wptouch_get_menu_name_from_slug( $menu_info->setting_name ); ?>
					<?php if ( $real_name == 'none' ) continue; ?>
					<option value="menu-<?php echo wptouch_get_menu_name_from_slug( $menu_info->setting_name ); ?>"><?php echo $menu_info->friendly_name; ?></option>
				<?php } ?>
			</select>
			<i class="wptouch-tooltip icon-info-sign" data-original-title="<?php _e( 'Active menus used in this theme.', 'wptouch-pro' ); ?>"></i>
		</div>
		<div id="menu-area">
			<div class="context-info clearfix">
				<span class="pull-right"><?php _e( 'Show/Hide', 'wptouch-pro' ); ?></span>
				<span class="pull-left"><?php _e( 'Menu Item', 'wptouch-pro' ); ?></span>
			</div>
		  	<div class="nano">
  				<div class="content">
  					<?php $complete_menus = array(); ?>
					<?php foreach( $wptouch_pro->theme_menus as $menu_info ) { ?>
						<?php $real_name = wptouch_get_menu_name_from_slug( $menu_info->setting_name ); ?>
						<?php if ( isset( $complete_menus[ $real_name ] ) ) continue; ?>
						<div data-menu-name="menu-<?php echo $real_name; ?>" class="menu-item-list" style="display: none;">
							<?php wptouch_show_menu( $menu_info->setting_name, new WPtouchProAdminNavMenuWalker(), new WPtouchProAdminPageMenuWalker() ); ?>
						</div>
						<?php $complete_menus[ $real_name ] = '1'; ?>
					<?php } ?>
				</div><!-- content -->
			</div><!-- nano -->
		</div>
	</div> <!-- right-area -->

	<div id="left-area" class="section">
		<div class="header">
			<?php _e( 'Icon Set', 'wptouch-pro' ); ?>
			<select name="pack-list" id="pack-list">
				<?php while ( wptouch_have_icon_packs() ) { ?>
					<?php wptouch_the_icon_pack(); ?>
					<option data-class="<?php echo wptouch_get_icon_pack_class_name(); ?>" value="<?php echo wptouch_get_icon_pack_class_name(); ?>"><?php wptouch_the_icon_pack_name(); ?></option>
				<?php } ?>
			</select>
		</div> <!-- header -->

		<div class="context-info clearfix">
			<center><?php _e( 'Drag icons to associate them with menu items', 'wptouch-pro' ); ?></center>
		</div>

		<?php while ( wptouch_have_icon_packs() ) { ?>
			<?php wptouch_the_icon_pack(); ?>
			<div class="pack" id="pack-<?php echo wptouch_get_icon_pack_class_name(); ?>">
			  	<div class="nano">
	  				<div class="content">
					<?php if ( wptouch_have_icons( wptouch_get_icon_pack_name() ) ) { ?>
						<ul>
						<?php while ( wptouch_have_icons( wptouch_get_icon_pack_name() ) ) { ?>
							<?php wptouch_the_icon(); ?>
							<li>
								<img src="<?php wptouch_the_icon_url(); ?>" />
								<div class="title"><?php wptouch_the_icon_short_name(); ?></div>
								<?php if ( wptouch_icon_has_image_size_info() ) { ?>
								<div class="size-info">
									<?php wptouch_icon_the_width(); ?> x <?php wptouch_icon_the_height(); ?>
								</div>
								<?php } ?>
							</li>
							<?php } ?>
						</ul>
					<?php } else { ?>
						<div id="no-icons">
							<p><?php _e( 'You don\'t have any custom icons yet.', 'wptouch-pro' ); ?></p>
						</div>
					<?php } ?>
					</div><!-- content -->
				</div><!-- nano -->
			</div>
		<?php } ?>
	</div> <!-- left-area -->
</div> <!-- pack set menu area -->

<div id="default-trash-area" class="clearfix">
	<div id="default-area">
		<div class="drop-target">
			<img src="<?php wptouch_the_site_default_icon(); ?>" alt="default-icon" />
				<span>
					<?php _e( 'Default Icon', 'wptouch-pro' ); ?>
					<span class="text">(<?php _e( 'applies to all unset icons', 'wptouch-pro' ); ?>)</span>
				</span>
		</div>
	</div>
	<div id="trash-area">
		<div class="drop-target">
			<img src="<?php echo WPTOUCH_ADMIN_URL . '/images/filler.png'; ?>" alt="trash-icon" />
			<span>
				<?php _e( 'Trash', 'wptouch-pro' ); ?>
				<span class="text">(<?php _e( 'drag icon here to reset', 'wptouch-pro' ); ?>)</span>
			</span>
		</div>
	</div>
</div>