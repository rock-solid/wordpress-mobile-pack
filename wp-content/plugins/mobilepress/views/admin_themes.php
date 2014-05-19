<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL . '/mobilepress/views/css/mobilepress.css'; ?>" type="text/css" media="all" />
<div class="wrap">
	<div id="icon-themes" class="icon32"><br /></div>
	<h2>MobilePress Themes</h2>

<h3>Current Mobile Theme</h3>
	<div class="mopr_current_themes">
		<div id="current-theme" class="mopr_current_theme">
			<h4><?php echo $themes[$mobile_theme]['Title']; ?> by <?php echo $themes[$mobile_theme]['Author']; ?></h4>
			<p class="theme-description"><?php echo $themes[$mobile_theme]['Description']; ?></p>
		</div>
	</div>

	<br class="clear" />

	<h3>Available MobilePress Themes</h3>

	<div id="availablethemes">
	<?php foreach ( $theme_names as $theme_name ) { ?>
		<div class="available-theme">
			<img src="<?php echo $themes[$theme_name]['Theme Root URI'] .'/'. $themes[$theme_name]['Stylesheet'] .'/'. $themes[$theme_name]['Screenshot']; ?>" alt="" />

			<h3><?php echo $themes[$theme_name]['Title']; ?> by <?php echo $themes[$theme_name]['Author']; ?></h3>
			<p class="description"><?php echo $themes[$theme_name]['Description']; ?></p>
			<span class="action-links">
				<a href="admin.php?page=mobilepress-themes&amp;action=activate&amp;template=<?php echo urlencode( $themes[$theme_name]['Template'] ); ?>&amp;theme=<?php echo urlencode( $themes[$theme_name]['Title'] ); ?>&amp;theme_root=<?php echo urlencode( $themes[$theme_name]['Theme Root'] ); ?>&amp;theme_type=mobile" class="activatelink" title="Activate &#8220;<?php echo $themes[$theme_name]['Title']; ?>&#8221;">Activate for Theme</a>
			</span>
			<p>All of this theme&#8217;s files are located in <code><?php echo str_replace( WP_CONTENT_DIR, '', $themes[$theme_name]['Stylesheet Dir'] ); ?></code>.</p>
		</div>
	<?php } ?>

	</div>
</div>