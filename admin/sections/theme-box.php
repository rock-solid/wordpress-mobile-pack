<?php

if (isset($theme)):
	$is_selected = isset($theme['selected']) && $theme['selected'] == 1;

	$is_premium = isset($theme['demo']) || isset($theme['details']);
?>
	<div class="theme <?php echo $is_premium ? 'premium' : '';?>">
		<div class="corner relative <?php echo $is_selected ? 'active' : '';?>">
			<div class="indicator"></div>
		</div>
		<div class="image" style="background:url(<?php echo isset($theme['icon']) ? esc_attr( $theme['icon'] ) : '' ?>);">
			<div class="relative">
				<div class="overlay">
					<div class="spacer-100"></div>

					<?php if (isset($theme['id']) && !$is_premium): ?>

						<div class="actions">
							<div class="select wmp_themes_select" onclick="jQuery('#submit-<?php echo esc_attr($theme['title']);?>').click();"data-theme="<?php echo esc_attr($theme['id']);?>" style="display: <?php echo $is_selected ? 'none' : 'block';?>"></div>
							<input class="select wmp_themes_select" type="submit" id="submit-<?php echo esc_attr($theme['title']);?>" name="<?php echo esc_attr($theme['title']);?>" style="display: none"/>
						</div>
						<div class="spacer-10"></div>
						<div class="text-select"><?php echo $is_selected ? 'Enabled' : 'Activate';?></div>

					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="name">
			<?php echo isset($theme['id']) && $theme['id'] == 2 ? '&#x1F680;' : '';?>
			<?php echo isset($theme['title']) ? esc_attr($theme['title']) : '';?>
		</div>
		<?php
			if ($is_premium && isset($theme['details']['link']) && isset($theme['details']['text'])):
		?>
			<div class="content">
				<a href="<?php echo esc_attr($theme['details']['link']) ?>" class="btn turquoise smaller" target="_blank">
					<?php echo isset($theme['details']['text']) ? $theme['details']['text'] : '';?>
				</a>
			</div>
		<?php endif; ?>
	</div>
<?php endif;?>
