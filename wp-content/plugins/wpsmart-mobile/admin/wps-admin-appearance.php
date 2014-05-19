<?php global $wps_fonts; ?>

<div class="wps-admin-option-group" id="wps-admin-appearance">	
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Site Font</span>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-section-input">
				<select name="site_font" id="site_font">
					<?php foreach( $wps_fonts as $wps_font ): ?>
					
					<option value="<?php echo $wps_font ?>" <?php echo wps_get_option( 'site_font' ) == $wps_font ? "selected=\"selected\"" : null ?>><?php echo $wps_font ?></option>
				
					<?php endforeach; ?>
				</select>
			</div>
			<div class="wps-admin-section-hint">Change the general font family</div>
		</div>
	</div>
	
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Site Background Color</span>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-section-input">
				<input type="text" name="site_background_color" id="site_background_color" class="wps-input-small wps-minicolors" value="<?php echo wps_get_option( 'site_background_color' ) ?>"/>
			</div>
			<div class="wps-admin-section-hint">Change the background color of the site</div>
		</div>
	</div>
	
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Site Header Background Color</span>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-section-input">
				<input type="text" name="header_background_color" id="header_background_color" class="wps-input-small wps-minicolors" value="<?php echo wps_get_option( 'header_background_color' ) ?>"/>
			</div>
			<div class="wps-admin-section-hint">Change the background color of the header</div>
		</div>
	</div>
	
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Site Header Text & Icon Color</span>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-section-input">
				<input type="text" name="header_text_color" id="header_text_color" class="wps-input-small wps-minicolors" value="<?php echo wps_get_option( 'header_text_color' ) ?>"/>
			</div>
			<div class="wps-admin-section-hint">Change the color of the text and icons (e.g. search icon) of the header</div>
		</div>
	</div>
	
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Site Header Trim Color</span>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-section-input">
				<input type="text" name="header_trim_color" id="header_trim_color" class="wps-input-small wps-minicolors" value="<?php echo wps_get_option( 'header_trim_color' ) ?>"/>
			</div>
			<div class="wps-admin-section-hint">Change the header's top border color</div>
		</div>
	</div>
	
</div>