<?php 
	$current_theme = wps_get_current_theme();
	$available_themes = wps_get_themes( $exclude_current = true );
?>

<div class="wps-admin-option-group" id="wps-admin-themes">
	<div class="wps-admin-section">		
		<div class="wps-admin-current-theme">
			<div class="wps-admin-theme-screenshot">
				<img src="<?php echo $current_theme['screenshot'] ?>"/>
			</div>
			<div class="wps-admin-current-theme-info">
				<h3>Current Theme</h3>
				<h2><?php echo $current_theme['name'] ?></h2>
			</div>
		</div>
	</div>
	
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Available Themes</span>
		</div>
		
		<div class="wps-admin-available-themes">
		
		<?php if( ! empty( $available_themes ) ): foreach( $available_themes as $theme ): ?>
			<div class="wps-admin-theme available-theme">
				<div class="wps-admin-theme-screenshot"><img src="<?php echo $theme['screenshot'] ?>"/></div>
				<div class="wps-admin-theme-info">
					<h4><?php echo $theme['name'] ?></h4>
					<div class="action-links">
						<ul>
							<li class="wps-activate-theme-link"><a href="#" data-theme="<?php echo $theme['slug'] ?>">Activate</a></li>
							<li class="wps-preview-theme-link"><a href="#" data-theme="<?php echo $theme['slug'] ?>">Live Preview</a></li>
						</ul>
					</div>
				</div>
			</div>
		<?php endforeach; endif; ?>
		
		</div>
	</div>
</div>