<form method="post" action=""<?php if ( strpos( $_SERVER['REQUEST_URI'], 'wptouch-admin-license' ) !== false ) echo ' autocomplete="off"'; ?>>
	<div id="wptouch-settings-area" class="<?php wptouch_admin_panel_classes( array( 'wrap', 'clearfix' ) ); ?>">

		<?php if ( $_GET['page'] != 'wptouch-admin-license' ) { ?>
			<?php include_once( WPTOUCH_ADMIN_DIR . '/html/notification-center.php' ); ?>
		<?php } ?>

		<h2 class="logo-title">
			<?php echo WPTOUCH_PRODUCT_NAME; ?>
			<?php echo WPTOUCH_VERSION; ?>
			<span class="title-arrow">›</span>
			<?php if ( is_rtl() ) { ?>
			<span class="title-orange"><bdi><?php wptouch_admin_the_menu_friendly_name(); ?></bdi></span>
			<?php } else { ?>
			<span class="title-orange"><?php wptouch_admin_the_menu_friendly_name(); ?></span>
			<?php } ?>

			<div id="admin-spinner">
				<img src="<?php echo WPTOUCH_ADMIN_URL; ?>/images/loading.gif" alt="Loading image" />
			</div>
		</h2>
		<?php if ( wptouch_should_show_license_nag() && $_GET['page'] != 'wptouch-admin-license' ) { ?>
			<div class="alert-wrap">
				<div class="alert">
			  		<?php echo sprintf( __( 'This copy of %s is currently unlicensed!', 'wptouch-pro' ), 'WPtouch Pro' ); ?>
			  		<?php if ( wptouch_should_show_activation_nag() ) { ?>
			  			<a href="<?php echo wptouch_get_license_activation_url(); ?>" class="btn btn-small btn-warning"><?php echo sprintf( __( 'Add a license %s', 'wptouch-pro' ), '&raquo;'); ?></a>
			  		<?php } ?>
				</div>
			</div>
		<?php } ?>

		<?php if ( is_array( $panel_options ) ) { ?>
			<div id="wptouch-admin-menu" <?php if ( count ( $panel_options ) <= 1 ) echo 'style="display: none;"'; ?>>
				<?php foreach( $panel_options as $page_name => $page_info ) { ?>
					<?php if ( isset( $page_info->sections ) && is_array( $page_info->sections ) && count( $page_info->sections ) ) { ?>
					<a href="#" class="<?php echo $page_info->slug; ?><?php if ( isset( $_COOKIE['wptouch-admin-menu'] ) && ( $_COOKIE['wptouch-admin-menu'] == $page_info->slug ) ) { echo ' active'; } ?>" data-page-slug="<?php echo $page_info->slug; ?>"><?php echo $page_name; ?></a>
					<?php } ?>
				<?php } ?>
			</div>
		<?php } ?>

		<div id="wptouch-settings-content">
		<?php if ( wptouch_admin_is_custom_page() ) { ?>
			<?php wptouch_admin_render_custom_page(); ?>
		<?php } else { ?>
			<?php if ( is_array( $panel_options ) ) { ?>
				<?php foreach( $panel_options as $page_name => $page_info ) { ?>
					<div class="wptouch-settings-sub-page" class="clearfix" id="<?php echo $page_info->slug; ?>" style="<?php if ( isset( $_COOKIE['wptouch-admin-menu'] ) && ( $_COOKIE['wptouch-admin-menu'] == $page_info->slug ) ) { echo 'display: block;'; } else { echo 'display: none;'; } ?>">
					<?php foreach( $page_info->sections as $section ) { ?>
						<div class="wptouch-section"<?php if ( $section->name ) { ?> id="section-<?php echo $section->slug; ?>"<?php } ?>>
						<?php if ( $section->name ) { ?>
							<?php if ( wptouch_section_has_visible_settings( $section ) ) { ?>
								<h3><?php echo $section->name; ?> </h3>
								<ul class="padded">
								<?php foreach( $section->settings as $setting ) { ?>
									<?php if ( wptouch_admin_can_render_setting( $setting ) ) { ?>
									<li class="wptouch-setting" id="setting-<?php echo wptouch_convert_to_class_name( $setting->name ); ?>">
										<?php wptouch_admin_render_setting( $setting ); ?>
									</li>
									<?php } ?>
								<?php } ?>
								</ul>
							<?php } ?>
						<?php } else { ?>
							<?php // custom areas ?>
							<?php foreach( $section->settings as $setting ) { ?>
								<?php wptouch_admin_render_special_setting( $setting ); ?>
							<?php } ?>
						<?php } ?>
						</div><!-- wptouch-settings-sub-page -->
					<?php } ?>
					</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
		</div>
	</div>
	<?php if ( ( $_GET['page'] != 'wptouch-admin-touchboard' ) && ( $_GET['page'] != 'wptouch-admin-license' ) && ( $_GET['page'] != 'wptouch-admin-themes-and-addons' ) && ( $_GET['page'] != 'wptouch-admin-upgrade' ) ) { ?>
		<br /><br /><br /><!-- add some space above -->
		<input type="submit" name="wptouch-submit-3" id="submit" class="button-primary" value="<?php _e( 'Save Changes', 'wptouch-pro' ); ?>" />
		<input type="submit" name="wptouch-preview-theme" id="preview" class="preview-button button-secondary" value="<?php _e( "Preview Theme", "wptouch-pro" ); ?>" data-url="<?php wptouch_bloginfo( 'url' ); ?>/?wptouch_preview_theme=enabled"  />
		<input type="submit" name="wptouch-reset-3" id="reset" class="reset-button button-secondary" value="<?php _e( 'Reset Settings', 'wptouch-pro' ); ?>" />
		<input type="hidden" name="wptouch-admin-nonce" value="<?php echo wp_create_nonce( 'wptouch-post-nonce' ); ?>" />
	<?php } ?>
</form>