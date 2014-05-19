<?php $settings = wptouch_get_settings(); ?>

<!-- what's new right side -->
<div class="overview-right-wrap clearfix">
	<div id="touchboard-links" class="overview-box-appearance">
		<h3><?php _e( 'Quick Links', 'wptouch-pro' ); ?></h3>
		<ul>
			<li><a href="#" data-toggle="modal" data-target="#modal-updates"><?php _e( 'What\'s New Changelog', 'wptouch-pro' ); ?></a></li>
			<?php if ( defined( 'WPTOUCH_IS_FREE' ) ) { ?>
				<li><a href="http://www.wptouch/themes/?utm_campaign=touchboard&utm_source=<?php echo WPTOUCH_UTM_SOURCE; ?>&utm_medium=web"><?php _e( 'Look at Pro Themes', 'wptouch-pro' ); ?></a></li>
				<li><a href="http://www.wptouch/extensions/?utm_campaign=touchboard&utm_source=<?php echo WPTOUCH_UTM_SOURCE; ?>&utm_medium=web"><?php _e( 'Look at Pro Extensions', 'wptouch-pro' ); ?></a></li>
				<li><a href="http://www.wptouch/features/?utm_campaign=touchboard&utm_source=<?php echo WPTOUCH_UTM_SOURCE; ?>&utm_medium=web"><?php _e( 'Look at Pro Features', 'wptouch-pro' ); ?></a></li>
			<?php } ?>
			<li><a href="http://www.wptouch.com/support/" target="_blank"><?php _e( 'Product Support', 'wptouch-pro' ); ?></a></li>
			<?php if ( !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
			<li><a href="http://www.wptouch.com/support/knowledgebase/?utm_campaign=touchboard&utm_source=<?php echo WPTOUCH_UTM_SOURCE; ?>&utm_medium=web" target="_blank"><?php _e( 'Product Knowledgebase', 'wptouch-pro' ); ?></a></li>
			<li><a href="http://www.wptouch.com/support/profile/" target="_blank"><?php _e( 'Manage Account', 'wptouch-pro' ); ?></a></li>
			<li><a href="http://www.wptouch.com/support/" target="_blank"><?php _e( 'Manage License', 'wptouch-pro' ); ?></a></li>
			<?php } else { ?>
			<li><a href="http://wptouch.s3.amazonaws.com/docs/WPtouch%20User%20Guide.pdf"><?php _e( 'WPtouch User Guide', 'wptouch-pro' ); ?></a></li>
			<?php } ?>
		</ul>
	</div>

	<div id="touchboard-news" class="overview-box-appearance">
		<h3>
			<?php _e( 'WPtouch News', 'wptouch-pro' ); ?>
			<a href="//www.wptouch.com/blog/" target="_blank"><?php _e( 'Read More', 'wptouch-pro' ); ?> <i class="icon-external-link"></i></a>
		</h3>
		<span id="ajax-news">
			<!-- ajaxed news here -->
		</span>
		<h3>
			WPtouch Updates:
			<a href="https://www.google.com/+BraveNewCode" target="_blank"><i class="icon-google-plus-sign"></i></a>
			<a href="//www.facebook.com/bravenewcode" target="_blank"><i class="icon-facebook-sign"></i></a>
			<a href="//www.twitter.com/bravenewcode" target="_blank"><i class="icon-twitter-sign"></i></a>
		</h3>
	</div><!-- touchboard-news -->
</div><!-- over-right-side -->

<div id="touchboard-left" class="overview-box-appearance">
	<!--
	<h3>
		<?php echo sprintf( __( "What's New in %s", 'wptouch-pro' ), WPTOUCH_VERSION ); ?>
		<span>
			<?php if ( !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
				<?php if ( wptouch_should_show_license_nag() ) { ?>
					<?php _e( 'License', 'wptouch-pro' ); ?>: <strong class="orange"><?php _e( 'Unlicensed', 'wptouch-pro' ); ?></strong> &nbsp;|&nbsp; BraveNewCloud: <strong class="orange"><?php _e( 'Offline', 'wptouch-pro' ); ?></strong>
				<?php } else { ?>
					<?php _e( 'License', 'wptouch-pro' ); ?>: <strong class="green"><?php _e( 'Active', 'wptouch-pro' ); ?></strong> &nbsp;|&nbsp; BraveNewCloud: <strong class="green"><?php _e( 'Online', 'wptouch-pro' ); ?></strong>
				<?php } ?>
			<?php } ?>
		</span>
	</h3>
	-->
</div><!-- touchboard-left-side -->

	<div class="modal hide" id="modal-updates" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-header">
			<h3><?php echo sprintf( __( '%s Change Log', 'wptouch-pro' ), WPTOUCH_PRODUCT_NAME ); ?></h3>
		</div>
		<div class="modal-body" id="change-log">
		</div>
		<div class="modal-footer">
			<button class="button" data-dismiss="modal" aria-hidden="true">Close</button>
		</div>
	</div>