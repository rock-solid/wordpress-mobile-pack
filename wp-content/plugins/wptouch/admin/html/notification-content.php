<div class="nano notifications">
	<div class="content">
		<ul>
		<?php if ( wptouch_has_notifications() ) while ( wptouch_has_notifications() ) { ?>
			<?php wptouch_the_notification(); ?>
			<li class="<?php wptouch_notification_the_type(); ?>">
				<span class="dismiss" data-key="<?php wptouch_the_notification_key(); ?>">x</span>

				<?php if ( wptouch_notification_has_link() ) { ?>
					<a href="<?php wptouch_notification_the_link(); ?>">
						<?php wptouch_notification_the_name(); ?> &raquo;
					</a>
				<?php } else { ?>
					<?php wptouch_notification_the_name(); ?>
				<?php } ?>
				<span><?php wptouch_notification_the_desc(); ?></span>
			</li>
		<?php } else { ?>
			<li class="no-notifications">
				<?php _e( 'No notifications', 'wptouch-pro' ); ?>
			</li>
		<?php } ?>
		</ul>
	</div>
</div>