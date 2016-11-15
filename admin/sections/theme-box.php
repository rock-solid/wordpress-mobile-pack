<?php

if (isset($theme)):

	$is_selected = isset($theme['selected']) && $theme['selected'] == 1;

	$is_premium = isset($theme['buy']);
?>
	<div class="theme <?php echo $is_premium ? 'premium' : '';?> <?php echo isset($theme['bundle']) && $theme['bundle'] == 1 ? 'bundle' : '';?>">
		<div class="corner relative <?php echo $is_selected ? 'active' : '';?>">
			<div class="indicator"></div>
		</div>
		<div class="image" style="background:url(<?php echo isset($theme['icon']) ? esc_attr( $theme['icon'] ) : '' ?>);">
			<div class="relative">
				<?php if ($is_premium && (isset($theme['preview']) || isset($theme['snapshots'])) ): ?>
					<div class="overlay">
						<div class="spacer-100"></div>
						<div class="actions">
							<div class="preview wmp_themes_preview"

								<?php if (isset($theme['preview'])):?>
									data-url="<?php echo filter_var($theme['preview'], FILTER_VALIDATE_URL) ?  esc_attr($theme['preview']) : '';?>"
								<?php endif;?>

								<?php if (isset($theme['snapshots']) && is_array($theme['snapshots']) && count($theme['snapshots']) > 0):?>
									data-snapshots="<?php echo esc_attr(implode(',', $theme['snapshots']));?>"
								<?php endif;?>

							></div>
						</div>
						<div class="spacer-10"></div>
						<div class="text-preview">Preview theme</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="name">
			<?php echo isset($theme['title']) ? esc_attr($theme['title']) : '';?>

			<?php if ($is_premium && isset($theme['buy']['price'])):?>
				<span class="price"><?php echo esc_attr($theme['buy']['price']); ?></span>
			<?php endif;?>
		</div>
		<?php
			$buy_link = isset($theme['buy']['link']) && filter_var($theme['buy']['link'], FILTER_VALIDATE_URL) ? $theme['buy']['link'] : '';

			if ($is_premium && $buy_link != ''):
		?>
			<div class="content">
				<a href="<?php echo esc_attr($buy_link) ?>" class="btn turquoise smaller" target="_blank">
					<span class="shopping"></span>&nbsp;
					<?php echo isset($theme['buy']['text']) ? $theme['buy']['text'] : '';?>
				</a>
			</div>
		<?php endif; ?>
	</div>
<?php endif;?>
