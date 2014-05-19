<li class="<?php wptouch_the_addon_classes(); ?>">
	<?php if ( wptouch_get_addon_screenshot() ) { ?>
	<div class="image-wrapper">
		<img src="<?php wptouch_the_addon_screenshot(); ?>" alt="<?php wptouch_the_addon_title(); ?>" />

		<div class="modal hide" id="modal-<?php echo wptouch_convert_to_class_name( wptouch_get_addon_title() ); ?>" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-header">
				<h3 id="<?php echo wptouch_convert_to_class_name( wptouch_get_addon_title() ); ?>-title"><?php wptouch_get_addon_title(); ?></h3>
			</div>
			<div class="modal-body">
				<div id="carousel-<?php echo wptouch_convert_to_class_name( wptouch_get_addon_title() ); ?>" class="carousel slide" data-interval="0">
				<!-- Carousel items -->
					<div class="carousel-inner">
						<?php wptouch_reset_addon_preview(); ?>
						<?php while ( wptouch_has_addon_preview_images() ) { ?>
							<?php wptouch_the_addon_preview_image(); ?>
							<div class="<?php if ( wptouch_is_first_addon_preview_image() ) echo 'active '; ?>item">
								<img src="<?php wptouch_the_addon_preview_url(); ?>" alt="preview-image" />
							</div>
						<?php } ?>
					</div>
					<!-- Carousel nav -->
				  	<a class="carousel-control left" href="#carousel-<?php echo wptouch_convert_to_class_name( wptouch_get_addon_title() ); ?>" data-slide="prev">&laquo;</a>
	  				<a class="carousel-control right" href="#carousel-<?php echo wptouch_convert_to_class_name( wptouch_get_addon_title() ); ?>" data-slide="next">&raquo;</a>
		 		 </div>
			</div>
			<div class="modal-footer">
				<button class="button" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="item-information">
		<?php if ( wptouch_cloud_addon_update_available() ) { ?>
			<?php if ( wptouch_can_cloud_install( false ) ) { ?>
			<a class="button-primary upgrade" href="#" data-name="<?php wptouch_the_addon_base(); ?>" data-url="<?php wptouch_the_addon_download_url(); ?>" data-loading-text="<i class='icon-cloud-download'></i><?php _e( 'Updating...', 'wptouch-pro' ); ?>"><i class="icon-cloud-download"></i><?php echo sprintf( __( 'Upgrade to %s', 'wptouch-pro' ), wptouch_cloud_addon_get_update_version() ); ?></a>
			<?php } else { ?>
			<a class="button-primary cant-upgrade" href="<?php wptouch_the_addon_download_url(); ?>"><i class="icon-download"></i> <?php echo sprintf( __( 'Download %s Update', 'wptouch-pro' ), wptouch_cloud_addon_get_update_version() ); ?></a>
			<?php } ?>
		<?php } ?>
		<h4>
			<?php wptouch_the_addon_title(); ?> <span class="version"><?php wptouch_the_addon_version(); ?></span>
		</h4>
		<h5>
			<?php echo sprintf( __( 'by %s', 'wptouch-pro' ), wptouch_get_addon_author() ); ?>
		</h5>
		<p class="desc"><?php wptouch_the_addon_description(); ?></p>

		<p class="info">
			<?php if ( !wptouch_is_addon_in_cloud() ) { ?>
			<?php echo sprintf( __( 'Extension location: %s', 'wptouch-pro' ), wptouch_get_addon_location() ); ?>
			<i class="wptouch-tooltip icon-info-sign" title="<?php _e( 'Relative to your WordPress wp-content directory.', 'wptouch-pro' ); ?>"></i>
			<?php } ?>
			<br />
		</p>
		<ul class="item-actions">
			<?php if ( wptouch_is_addon_in_cloud() ) { ?>
				<?php if ( wptouch_get_addon_buy_url() ) { ?>
					<?php if ( defined( 'WPTOUCH_IS_FREE' ) ) { ?>
						<?php if ( wptouch_addon_info_url() ) { ?>
						<li><a class="button-secondary buynow" href="<?php echo wptouch_addon_info_url(); ?>"><?php _e( 'Available in WPtouch Pro', 'wptouch-pro' ); ?></a></li>
						<?php } ?>
					<?php } else { ?>
						<?php if ( wptouch_addon_info_url() ) { ?>
						<li><a class="button-secondary buynow" href="<?php echo wptouch_addon_info_url(); ?>"><?php _e( 'More Info', 'wptouch-pro' ); ?></a></li>
						<?php } ?>
						<?php if ( wptouch_has_license() ) { ?>
						<li><a class="button-secondary buynow" href="<?php wptouch_the_addon_buy_url(); ?>"><?php _e( 'Upgrade License', 'wptouch-pro' ); ?></a></li>
						<?php } else { ?>
						<li><a class="button-secondary buynow" href="<?php wptouch_the_addon_buy_url(); ?>"><?php _e( 'Get License', 'wptouch-pro' ); ?></a></li>
						<?php } ?>
					<?php } ?>

				<?php } else { ?>
					<?php if ( current_user_can( 'install_plugins' ) ) { ?>
					<li>
						<?php if ( wptouch_can_cloud_install( false ) ) { ?>
						<a class="button-primary download" href="#" data-name="<?php wptouch_the_addon_base(); ?>" data-url="<?php wptouch_the_addon_download_url(); ?>" data-loading-text="<i class='icon-cloud-download'></i><?php _e( 'Downloading...', 'wptouch-pro' ); ?>"><i class="icon-cloud-download"></i><?php _e( 'Install', 'wptouch-pro' ); ?></a>
						<?php } else { ?>
						<a class="button-primary" href="<?php wptouch_the_addon_download_url(); ?>"><i class="icon-cloud-download"></i> <?php _e( 'Download', 'wptouch-pro' ); ?></a>
						<?php } ?>
					</li>
					<?php } ?>
				<?php } ?>
			<?php } else { ?>
				<?php if ( !wptouch_is_addon_active() && current_user_can( 'activate_plugins' ) ) { ?>
					<li><a class="button-primary" href="<?php wptouch_the_addon_activate_link_url(); ?>"><?php _e( 'Activate', 'wptouch-pro' ); ?></a></li>
				<?php } ?>
				<?php if ( wptouch_is_addon_active() && current_user_can( 'activate_plugins' ) ) { ?>
					<li><a class="button-primary" href="admin.php?page=wptouch-admin-addon-settings"><?php _e( 'Setup', 'wptouch-pro' ); ?></a></li>
					<li><a class="button-secondary deactivate" href="<?php wptouch_the_addon_deactivate_link_url(); ?>"><?php _e( 'Deactivate', 'wptouch-pro' ); ?></a></li>
				<?php } ?>
			<?php } ?>
		</ul><!-- item-actions -->
	</div>
</li>