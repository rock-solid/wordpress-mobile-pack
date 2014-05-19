<li class="<?php wptouch_the_theme_classes(); ?>">
	<?php if ( wptouch_get_theme_screenshot() ) { ?>
	<div class="image-wrapper">
		<?php if ( wptouch_is_theme_in_cloud() ) { ?>
			<img src="<?php wptouch_the_theme_screenshot(); ?>" alt="<?php wptouch_the_theme_title(); ?>" />
			<span class="view"><?php _e( 'Click to view screenshots', 'wptouch-pro' ); ?></span>
		<?php }  else { ?>
		<a href="#" data-toggle="modal" data-target="#modal-<?php echo wptouch_convert_to_class_name( wptouch_get_theme_title() ); ?>">
			<img src="<?php wptouch_the_theme_screenshot(); ?>" alt="<?php wptouch_the_theme_title(); ?>" />
			<span class="view"><?php _e( 'Click to view screenshots', 'wptouch-pro' ); ?></span>
		</a>
		<?php } ?>

		<?php if ( !wptouch_is_theme_in_cloud() ) { ?>
		<div class="modal hide" id="modal-<?php echo wptouch_convert_to_class_name( wptouch_get_theme_title() ); ?>" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-header">
				<h3 id="<?php echo wptouch_convert_to_class_name( wptouch_get_theme_title() ); ?>-title"><?php wptouch_the_theme_title(); ?></h3>
			</div>
			<div class="modal-body">
				<div id="carousel-<?php echo wptouch_convert_to_class_name( wptouch_get_theme_title() ); ?>" class="carousel slide" data-interval="0">
				<!-- Carousel items -->
					<div class="carousel-inner">
						<?php wptouch_reset_theme_preview(); ?>
						<?php while ( wptouch_has_theme_preview_images() ) { ?>
							<?php wptouch_the_theme_preview_image(); ?>
							<div class="<?php if ( wptouch_is_first_theme_preview_image() ) echo 'active '; ?>item">
								<img src="<?php wptouch_the_theme_preview_url(); ?>" alt="preview-image" />
							</div>
						<?php } ?>
					</div>
					<!-- Carousel nav -->
				  	<a class="carousel-control left" href="#carousel-<?php echo wptouch_convert_to_class_name( wptouch_get_theme_title() ); ?>" data-slide="prev">&laquo;</a>
	  				<a class="carousel-control right" href="#carousel-<?php echo wptouch_convert_to_class_name( wptouch_get_theme_title() ); ?>" data-slide="next">&raquo;</a>
		 		 </div>
			</div>
			<div class="modal-footer">
				<button class="button" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div><!--modal -->
		<?php } ?>

	</div><!-- image-wrapper -->
	<?php } ?>

	<div class="item-information">
		<?php if ( wptouch_cloud_theme_update_available() ) { ?>
			<?php if ( !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
				<?php if ( wptouch_can_cloud_install( true ) ) { ?>
				<a class="button-primary upgrade" href="#" data-name="<?php wptouch_the_theme_base(); ?>" data-url="<?php wptouch_the_theme_download_url(); ?>" data-loading-text="<i class='icon-cloud-download'></i> <?php _e( 'Updating...', 'wptouch-pro' ); ?>">
					<i class="icon-cloud-download"></i>
					<?php echo sprintf( __( 'Update to %s', 'wptouch-pro' ), wptouch_cloud_theme_get_update_version() ); ?>
				</a>
				<?php } else { ?>
				<a class="button-primary cant-upgrade" href="<?php wptouch_the_theme_download_url(); ?>"><i class="icon-download"></i> <?php echo sprintf( __( 'Download %s Update', 'wptouch-pro' ), wptouch_cloud_theme_get_update_version() ); ?></a>
				<?php } ?>
			<?php } ?>
		<?php } ?>
		<h4>
			<?php wptouch_the_theme_title(); ?> <span class="version"><?php wptouch_the_theme_version(); ?></span>
			<?php if ( wptouch_has_theme_tags() ) { ?>
				<?php $tags = wptouch_get_theme_tags(); ?>
				<?php foreach( $tags as $tag ) { ?>
				<i class="wptouch-tooltip theme-tag tag-<?php echo wptouch_convert_to_class_name( $tag ); ?>" title="<?php echo sprintf( __( 'This theme supports %s devices', 'wptouch-pro' ), wptouch_get_translated_device_type( $tag ) ); ?>"></i>
				<?php } ?>
			<?php } ?>
		</h4>
		<h5>
			<?php echo sprintf( __( 'by %s', 'wptouch-pro' ), wptouch_get_theme_author() ); ?>
		</h5>

		<p class="desc"><?php wptouch_the_theme_description(); ?></p>

		<p class="info">
		<?php if ( !wptouch_is_theme_in_cloud() ) { ?>
		<?php echo sprintf( __( 'Theme location: %s', 'wptouch-pro' ), wptouch_get_theme_location() ); ?>
			<i class="wptouch-tooltip icon-info-sign" title="<?php _e( 'Relative to your WordPress wp-content directory.', 'wptouch-pro' ); ?>"></i>
			<br />
		<?php } ?>
		</p>
	</div>
	<ul class="item-actions">
		<?php if ( wptouch_is_theme_in_cloud() ) { ?>
			<?php if ( wptouch_get_theme_buy_url() ) { ?>
				<li>
					<?php if ( wptouch_theme_type() == 'WITHLICENSE' ) { ?>
						<?php if ( defined( 'WPTOUCH_IS_FREE' ) ) { ?>
							<?php if ( wptouch_theme_info_url() ) { ?>
							<a class="button-secondary" href="<?php echo wptouch_theme_info_url(); ?>"><?php _e( 'Available in WPtouch Pro', 'wptouch-pro' ); ?></a>
							<?php } ?>
						<?php } else { ?>
							<?php if ( wptouch_theme_info_url() ) { ?>
							<a class="button-secondary" href="<?php echo wptouch_theme_info_url(); ?>"><?php _e( 'More Info', 'wptouch-pro' ); ?></a>
							<?php } ?>

							<?php if ( wptouch_has_license() ) { ?>
							<a class="button-secondary buynow" href="<?php wptouch_the_theme_buy_url(); ?>"><?php _e( 'Upgrade License', 'wptouch-pro' ); ?></a>
							<?php } else { ?>
							<a class="button-secondary buynow" href="<?php wptouch_the_theme_buy_url(); ?>"><?php _e( 'Get License', 'wptouch-pro' ); ?></a>
							<?php } ?>
						<?php } ?>

					<?php } else { ?>
						<a class="button-secondary buynow" href="<?php wptouch_the_theme_buy_url(); ?>"><?php _e( 'Buy Now', 'wptouch-pro' ); ?></a>
					<?php } ?>
				</li>
			<?php } else { ?>
				<?php if ( current_user_can( 'install_plugins' ) ) { ?>
					<?php if ( !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
						<li>
							<?php if ( wptouch_can_cloud_install( true ) ) { ?>
							<a class="button-primary download" href="#" data-name="<?php wptouch_the_theme_base(); ?>" data-url="<?php wptouch_the_theme_download_url(); ?>" data-loading-text="<i class='icon-cloud-download'></i><?php _e( 'Downloading...', 'wptouch-pro' ); ?>">
								<i class="icon-cloud-download"></i><?php _e( 'Install', 'wptouch-pro' ); ?>
							</a>
							<?php } else { ?>
							<a class="button-primary" href="<?php wptouch_the_theme_download_url(); ?>"><i class="icon-download"></i> <?php _e( 'Download', 'wptouch-pro' ); ?></a>
							<?php } ?>
						</li>
					<?php } ?>
				<?php } ?>
			<?php } ?>

		<?php } else { ?>

			<?php if ( wptouch_is_theme_active() && current_user_can( 'manage_options' ) ) { ?>
				<li><a class="button-primary" href="admin.php?page=wptouch-admin-theme-settings"><?php _e( 'Setup', 'wptouch-pro' ); ?></a></li>
			<?php } ?>

			<?php if ( !wptouch_is_theme_active() && current_user_can( 'switch_themes' ) && !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
				<li><a class="button-primary" href="<?php wtouch_the_theme_activate_link_url(); ?>"><?php _e( 'Activate', 'wptouch-pro' ); ?></a></li>
			<?php } ?>

			<?php if ( !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
			<li><a class="button-secondary" href="<?php wtouch_the_theme_copy_link_url(); ?>"><?php _e( 'Copy', 'wptouch-pro' ); ?></a></li>
			<?php } ?>

			<?php if ( wptouch_is_theme_custom() && !wptouch_is_theme_active() && !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
				<li><a class="button-secondary" class="delete-theme ajax-button" href="<?php wptouch_the_theme_delete_link_url(); ?>"><?php _e( 'Delete', 'wptouch-pro' ); ?></a></li>
			<?php } ?>

		<?php } ?>
	</ul><!-- item-actions -->
</li>