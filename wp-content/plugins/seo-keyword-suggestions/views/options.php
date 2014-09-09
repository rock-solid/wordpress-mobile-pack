<div class="wrap">
	<div id="icon-options-hittail" class="icon32"><br></div>
	<h2>HitTail Settings 
		<a href="http://www.hittail.com/tour.asp?utm_source=wp-plugin&utm_medium=link&utm_campaign=dashboard-header" class="ht-visit" target="_blank">Take a Tour</a>
		<a href="http://www.hittail.com/contact.asp?utm_source=wp-plugin&utm_medium=link&utm_campaign=dashboard-header" class="ht-visit" target="_blank">Support</a>
	</h2>
	<?php if ( ! $this->site_id() ) { ?>
		<div class="ht-settings-banner ht-clearfix">
			<h3>Create a HitTail Account 
				<span class="ht-popdown">
					<a href="#">Already have an account?</a>
					<div class="message">Enter your site ID in the form below to install your tracking code.</div>
				</span>
			</h3>
			<p><a href="http://www.hittail.com/?utm_source=wp-plugin&utm_medium=link&utm_campaign=dashboard1" target="_blank">HitTail</a> tells you, in real-time, the most promising search terms you should 
				target based on your existing traffic. We do this using a sophisticated algorithm 
				tuned by analyzing over 1.2 billion keywords.</p>
			<a href="http://www.hittail.com/?utm_source=wp-plugin&utm_medium=link&utm_campaign=dashboard2" class="ht-button" target="_blank">Sign Up Here</a>
			<p class="ht-closer">Join over 5,000 other websites in using the 
				service <i>PC World</i> called &ldquo;Analytics for the rest of us.&rdquo;</p>
		</div>
	<?php } ?>
	<form name="ht-settings-form" method="post" action="options.php">
		<?php settings_fields( 'ht_options' ); ?>
		<?php do_settings_sections( 'hittail' ); ?>
		<?php submit_button(); ?>
	</form>
</div>