<div class="wrap">
	<form method="post" action="admin.php?page=mobilepress">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>MobilePress Settings</h2>

	<!--
	<div id="message" class="updated fade">
		<p>
			<strong>Upgrade:</strong> MobilePress serves as a basic entry level mobile plugin for WordPress. If you would more
			flexibility, mobile theme control and beautiful support for touch screen devices then consider an <a href="http://obox-design.com/mobilepress.cfm" target="_blank">upgrade to Obox Mobile.</a>

		</p>
	</div>
	-->

	<table class="form-table">
		<tr>
			<th scope="row">Front Page Display</th>
			<td>
				<?php wp_dropdown_pages( 'show_option_none=Latest Posts&name=mopr_front_page&selected='.$mopr_front_page.'' ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">Posts Per Page</th>
			<td>
				<input type="text" name="mopr_page_posts" value="<?php echo $mopr_page_posts; ?>" class="small-text" />
			</td>
		</tr>
		<tr>
			<th scope="row">General Settings</th>
			<td>
				<fieldset>
					<label for="mopr_show_categories">
						<input name="mopr_show_categories" type="checkbox" value="1" <?php if ( $mopr_show_categories ) { echo 'checked="checked"'; } ?> />
						Show Categories
					</label>
				</fieldset>
				<fieldset>
					<label for="mopr_show_pages">
						<input name="mopr_show_pages" type="checkbox" value="1" <?php if ( $mopr_show_pages ) { echo 'checked="checked"'; } ?> />
						Show Pages
					</label>
				</fieldset>
				<fieldset>
					<label for="mopr_show_tags">
						<input name="mopr_show_tags" type="checkbox" value="1" <?php if ( $mopr_show_tags ) { echo 'checked="checked"'; } ?> />
						Show Post Tags
					</label>
				</fieldset>
				<fieldset>
					<label for="mopr_show_thumbnails">
						<input name="mopr_show_thumbnails" type="checkbox" value="1" <?php if ( $mopr_show_thumbnails ) { echo 'checked="checked"'; } ?> />
						Show Post Thumbnails
					</label>
				</fieldset>
			</td>
		</tr>
		<tr>
			<th scope="row">Comments Settings</th>
			<td>
				<fieldset>
					<label for="mopr_comments"><input type="radio" name="mopr_comments" value="all" <?php if ( $mopr_comments == 'all' ) { echo 'checked="checked"'; } ?> /> <span>Posts and Pages</span></label><br />
					<label for="mopr_comments"><input type="radio" name="mopr_comments" value="posts" <?php if ( $mopr_comments == 'posts' ) { echo 'checked="checked"'; } ?> /> <span>Posts Only</span></label><br />
					<label for="mopr_comments"><input type="radio" name="mopr_comments" value="pages" <?php if ( $mopr_comments == 'pages' ) { echo 'checked="checked"'; } ?> /> <span>Pages Only</span></label><br />
					<label for="mopr_comments"><input type="radio" name="mopr_comments" value="none" <?php if ( $mopr_comments == 'none' ) { echo 'checked="checked"'; } ?> /> <span>Disable Comments</span></label>
				</fieldset>
			</td>
		</tr>
		<tr>
			<th scope="row">Force Mobile Site</th>
			<td>
				<fieldset>
					<label for="mopr_force_mobile"><input type="radio" name="mopr_force_mobile" value="1" <?php if ( $mopr_force_mobile ) { echo 'checked="checked"'; } ?> /> <span>Yes</span></label><br />
					<label for="mopr_force_mobile"><input type="radio" name="mopr_force_mobile" value="0" <?php if ( ! $mopr_force_mobile ) { echo 'checked="checked"'; } ?>/> <span>No</span></label>
				</fieldset>
			</td>
		</tr>
		<tr>
			<th scope="row">Custom Mobile Themes Directory:</th>
			<td>
				/wp-content <input type="text" name="mopr_custom_themes" value="<?php echo $mopr_custom_themes; ?>" class="regular-text" /> <span class="description">Default: <code>/wp-content/mobilepress/themes</code></span>
			</td>
		</tr>
	</table>

	<p class="submit"><input type="submit" name="save" class="button-primary" value="Save Settings" /></p>
	</form>

	<h2>Upgrade to <a href="http://obox-design.com/mobilepress.cfm" target="_blank">Obox Mobile</a></h2>
	<p>
		MobilePress serves as a basic entry level mobile plugin for WordPress. It works just great on low end and feature phones.<br />
		If you are looking for a more feature packed plugin with better theme control, smartphone and touch screen support<br />
		and a beautiful default theme, then consider <a href="http://obox-design.com/mobilepress.cfm" target="_blank">upgrading to Obox Mobile.</a>
	</p>

	<h2>Mobile Theme Testing</h2>
	<p>
		You can easily view your mobile theme in a web browser by visiting <em><a href="<?php bloginfo('siteurl'); ?>/?mobile"><?php bloginfo('siteurl'); ?>/?mobile</a></em>.<br />
		Remember, to view your normal blog theme again, simply visit <em><a href="<?php bloginfo('siteurl'); ?>/?nomobile"><?php bloginfo('siteurl'); ?>/?nomobile</a></em>.
	</p>

	<h2>Custom Themes</h2>
	<p>
		Why not create a custom theme for your blog? Mobile themes are created in the same way as normal WordPress themes are created.<br />
		Simply upload your mobile themes to <code>/wp-content<?php echo $mopr_custom_themes; ?></code>
	</p>
</div>