<?php

if (isset($theme)):
	$is_selected = isset($theme['selected']) && $theme['selected'] == 1;
?>
	<div class="theme">
		
		<div class="corner relative <?php echo $is_selected ? 'active' : '';?>">
			<div class="indicator"></div>
		</div>

		<div class="image" style="background:url(<?= isset($theme['icon']) ? esc_attr( $theme['icon'] ) : '' ?>);">

			<div class="relative">
				<div class="overlay">
					<div class="spacer-100"></div>

					<?php if (isset($theme['id'])): ?>

						<div class="actions">
							<div class="select wmp_themes_select" onclick="jQuery('#submit-<?php echo esc_attr($theme['title']);?>').click();" style="display: <?php echo $is_selected ? 'none' : 'block';?>"></div>
							<input class="select wmp_themes_select" type="submit" id="submit-<?php echo esc_attr($theme['title']);?>" name="<?php echo esc_attr($theme['title']);?>" style="display: none"/>
						</div>
						<div class="spacer-10"></div>
						<div class="text-select"><?php echo $is_selected ? 'Enabled' : 'Activate';?></div>

					<?php endif;?>
				</div>
			</div>

		</div>

		<div class="name">
			<?php echo isset($theme['title']) ? esc_attr($theme['title']) : '';?>
		</div>

	</div>
<?php endif;?>
