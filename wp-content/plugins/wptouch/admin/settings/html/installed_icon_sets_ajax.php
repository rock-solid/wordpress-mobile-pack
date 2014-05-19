<ul class="manage-sets">
	<?php while ( wptouch_have_icon_packs() ) { ?>
		<?php wptouch_the_icon_pack(); ?>
		<?php if ( wptouch_get_icon_pack_name() == __( 'Custom Icons', 'wptouch-pro' ) ) continue; ?>
		<li>
			<img src="<?php wptouch_the_icon_pack_thumbnail(); ?>" alt="placeholder">
			<p class="set-title"><?php wptouch_the_icon_pack_name(); ?></p>
			<p class="set-author"><?php echo sprintf( __( 'by %s', 'wptouch-pro' ), '<a href="' . wptouch_get_icon_pack_author_url() . '">' .  wptouch_get_icon_pack_author() . '</a>' ); ?></p>
			<span class="installed"><i class="icon-ok-sign"></i> <?php _e( 'Installed', 'wptouch-pro' ); ?></span>
		</li>
	<?php } ?>

	<?php $remote_icon_sets = wptouch_get_remote_icon_packs(); ?>
	<?php if ( $remote_icon_sets ) { ?>
		<?php foreach( $remote_icon_sets as $icon_set ) { ?>
			<?php if ( !wptouch_already_has_icon_pack( $icon_set->name ) ) { ?>
			<li>
				<img src="<?php echo $icon_set->thumbnail; ?>" alt="placeholder">
				<p class="set-title"><?php echo $icon_set->name; ?></span>
				<p class="set-author"><?php echo sprintf( __( 'by %s', 'wptouch-pro' ), '<a href="' . $icon_set->author_url .  '">' . $icon_set->author . '</a>' ); ?></p>
				<button class="button-secondary" data-loading-text="<?php _e( 'Installing...', 'wptouch-pro' ); ?>" data-base-path="<?php echo $icon_set->dir_base; ?>" data-install-url="<?php echo $icon_set->download_url; ?>"><i class="icon-cloud-download"></i><?php _e( 'Install', 'wptouch-pro' ); ?></button>
				<span class="installed" style="display: none;"><i class="icon-ok-sign"></i> <?php _e( 'Installed', 'wptouch-pro' ); ?></span>
				<span class="error" style="display: none;"><i class="icon-warning-sign"></i> <?php _e( 'Unable to Install', 'wptouch-pro' ); ?></span>
			</li>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<ul>
