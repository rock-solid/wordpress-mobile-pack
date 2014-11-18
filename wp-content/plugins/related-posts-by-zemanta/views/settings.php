	<div class="wrap" id="zem_rp_wrap">
		<input type="hidden" id="zem_rp_ajax_nonce" value="<?php echo wp_create_nonce("zem_rp_ajax_nonce"); ?>" />

		<input type="hidden" id="zem_rp_json_url" value="<?php esc_attr_e(ZEM_RP_ZEMANTA_CONTENT_BASE_URL . ZEM_RP_STATIC_JSON_PATH); ?>" />
		<input type="hidden" id="zem_rp_version" value="<?php esc_attr_e(ZEM_RP_VERSION); ?>" />
		<input type="hidden" id="zem_rp_dashboard_url" value="<?php esc_attr_e(ZEM_RP_CTR_DASHBOARD_URL); ?>" />
		<input type="hidden" id="zem_rp_static_base_url" value="<?php esc_attr_e(ZEM_RP_ZEMANTA_CONTENT_BASE_URL); ?>" />

		<div class="header">
			<div class="support">
				<h4><?php _e("Awesome support", 'zemanta_related_posts'); ?></h4>
				<p>
					<?php _e("If you have any questions please contact us at",'zemanta_related_posts');?> <a target="_blank" href="mailto:support+relatedposts@zemanta.com"><?php _e("support", 'zemanta_related_posts');?></a>.
				</p>
			</div>
			<h2 class="title"><?php _e("Related Posts by ",'zemanta_related_posts');?><a href="http://www.zemanta.com">Zemanta</a></h2>
		</div>

		<h2><?php _e('Subscribe to news and activity reports', 'zemanta_related_posts'); ?></h2>
		<div class="container subscription-container">
			<table class="form-table subscription-block">
				<tr valign="top">
					<th scope="row">
						<?php _e('Email:', 'zemanta_related_posts'); ?>
					</th>
					<td>
						<input type="text" id="zem_rp_subscribe_email" value="<?php esc_attr_e($meta['email']); ?>" class="regular-text" /> 
						<a id="zem_rp_subscribe_button" href="#" class="button-primary"><?php _e('Subscribe', 'wp_related_posts'); ?></a>
						<a id="zem_rp_unsubscribe_button" href="#" class="button-primary"><?php _e('Unsubscribe', 'wp_related_posts'); ?></a>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
					    <?php _e("Subscribe and we'll start monitoring our network for your <a href=\"$blog_url\" target=\"_blank\">blog</a>. <br />We'll <strong>let you know</strong> when somebody links to you.", 'wp_related_posts'); ?>
					</td>
				</tr>
			</table>
		</div>

		 
		<form method="post" enctype="multipart/form-data" action="" id="zem_rp_settings_form">
			<?php wp_nonce_field('zem_rp_settings', '_zem_rp_nonce') ?>

			<div>
				<h2><?php _e("Settings",'zemanta_related_posts');?></h2>

				<?php do_action('zem_rp_admin_notices'); ?>

				<div class="container">
					<h3><?php _e("Basic Settings",'zemanta_related_posts');?></h3>

					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Related Posts Title:', 'zemanta_related_posts'); ?></th>
							<td>
							  <input name="zem_rp_related_posts_title" type="text" id="zem_rp_related_posts_title" value="<?php esc_attr_e($options['related_posts_title']); ?>" class="regular-text" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Number of Posts:', 'zemanta_related_posts');?></th>
							<td>
							  <input name="zem_rp_max_related_posts" type="number" step="1" id="zem_rp_max_related_posts" class="small-text" min="1" value="<?php esc_attr_e($options['max_related_posts']); ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"></th>
							<td><label>
								<?php _e('Only show posts from the last', 'zemanta_related_posts');?>&nbsp;
								<select name="zem_rp_max_related_post_age_in_days" id="zem_rp_max_related_post_age_in_days">
									<option value="0" <?php selected($options['max_related_post_age_in_days'], 0); ?>><?php _e('Unlimited','zemanta_related_posts'); ?></option>
									<option value="30" <?php selected($options['max_related_post_age_in_days'], 30); ?>>1</option>
									<option value="91" <?php selected($options['max_related_post_age_in_days'], 91); ?>>3</option>
									<option value="365" <?php selected($options['max_related_post_age_in_days'], 365); ?>>12</option>
								</select> &nbsp;<?php _e('months.','zemanta_related_posts'); ?>
							</label></td>
						</tr>
					</table>

					<h3><?php _e('Theme Settings','zemanta_related_posts'); ?></h3>
					<div id="zem_rp_theme_options_wrap">
						<input type="hidden" id="zem_rp_desktop_theme_selected" value="<?php esc_attr_e($options['desktop']['theme_name']); ?>" />
						<table class="form-table zem_rp_settings_table">
							<tr id="zem_rp_desktop_theme_options_wrap">
								<td>
									<div id="zem_rp_desktop_theme_area" style="display: none;">
										<div class="theme-list"></div>
										<div class="theme-screenshot"></div>
										<div class="theme-extra-options">
											<label class="zem_rp_settings_button">
												<input type="checkbox" id="zem_rp_desktop_custom_theme_enabled" name="zem_rp_desktop_custom_theme_enabled" value="yes"<?php checked($options['desktop']['custom_theme_enabled']); ?> />
												<?php _e('Customize','zemanta_related_posts') ;?>
											</label>
										</div>
									</div>
								</td>
							</tr>
							<tr id="zem_rp_desktop_theme_custom_css_wrap" style="display: none; ">
								<td>
									<label>
										<input name="zem_rp_desktop_display_thumbnail" type="checkbox" id="zem_rp_desktop_display_thumbnail" value="yes"<?php checked($options['desktop']["display_thumbnail"]); ?> onclick="zem_rp_display_thumbnail_onclick();" />
										<?php _e("Display Thumbnails For Related Posts",'zemanta_related_posts');?>
									</label><br />
									<label>
										<input name="zem_rp_desktop_display_comment_count" type="checkbox" id="zem_rp_desktop_display_comment_count" value="yes" <?php checked($options['desktop']["display_comment_count"]); ?>>
										<?php _e("Display Number of Comments",'zemanta_related_posts');?>
									</label><br />
									<label>
										<input name="zem_rp_desktop_display_publish_date" type="checkbox" id="zem_rp_desktop_display_publish_date" value="yes" <?php checked($options['desktop']["display_publish_date"]); ?>>
										<?php _e("Display Publish Date",'zemanta_related_posts');?>
									</label><br />
									<label>
										<input name="zem_rp_desktop_display_excerpt" type="checkbox" id="zem_rp_desktop_display_excerpt" value="yes"<?php checked($options['desktop']["display_excerpt"]); ?> onclick="zem_rp_display_excerpt_onclick();" >
										<?php _e("Display Post Excerpt",'zemanta_related_posts');?>
									</label>
									<label id="zem_rp_desktop_excerpt_max_length_label">
										<input name="zem_rp_desktop_excerpt_max_length" type="text" id="zem_rp_desktop_excerpt_max_length" class="small-text" value="<?php esc_attr_e($options['desktop']["excerpt_max_length"]); ?>" /> <span class="description"><?php _e('Maximum Number of Characters.', 'zemanta_related_posts'); ?></span>
									</label>
									<br/>
									<h4><?php _e('Custom CSS','zemanta_related_posts'); ?></h4>
									<textarea style="width: 300px; height: 215px; background: #EEE;" name="zem_rp_desktop_theme_custom_css" class="custom-css"><?php echo htmlspecialchars($options['desktop']['theme_custom_css'], ENT_QUOTES); ?></textarea>
								</td>
							</tr>
							<tr>
								<td>
									
								</td>
							</tr>
						</table>
					</div>

					<table class="form-table">
						<tbody>
							<tr valign="top">
								<td>
									<label>
										<?php _e('For posts without images, a default image will be shown.<br/>
										You can upload your own default image here','zemanta_related_posts','zemanta_related_posts');?>
										<input type="file" name="zem_rp_default_thumbnail" />
									</label>
									<?php if($options['default_thumbnail_path']) : ?>
										<span style="display: inline-block; vertical-align: top; *display: inline; zoom: 1;">
											<img style="padding: 3px; border: 1px solid #DFDFDF; border-radius: 3px;" valign="top" width="80" height="80" src="<?php esc_attr_e(zem_rp_get_default_thumbnail_url()); ?>" alt="selected thumbnail" />
											<br />
											<label>
												<input type="checkbox" name="zem_rp_default_thumbnail_remove" value="yes" />
												<?php _e("Remove selected",'zemanta_related_posts');?>
											</label>
										</span>
									<?php endif; ?>


									<?php
									global $wpdb;

									$custom_fields = $wpdb->get_col( "SELECT meta_key FROM $wpdb->postmeta GROUP BY meta_key HAVING meta_key NOT LIKE '\_%' ORDER BY LOWER(meta_key)" );
									if($custom_fields) :
									?>
									<br />
									<br />
									<label><input name="zem_rp_thumbnail_use_custom" type="checkbox" value="yes" <?php checked($options['thumbnail_use_custom']); ?>> <?php _e('Use custom field for thumbnails','zemanta_related_posts'); ?></label>
									<select name="zem_rp_thumbnail_custom_field" id="zem_rp_thumbnail_custom_field"  class="postform">
									<?php foreach ( $custom_fields as $custom_field ) : ?>
										<option value="<?php esc_attr_e($custom_field); ?>"<?php selected($options["thumbnail_custom_field"], $custom_field); ?>><?php esc_html_e($custom_field);?></option>
									<?php endforeach; ?>
									</select>
									<br />
									<?php endif; ?>
								</td>
							</tr>
						</tbody>
					</table>
					<h3><?php _e('Custom Size Thumbnails','zemanta_related_posts'); ?></h3>
					<table class="form-table">
						<tbody>
						<tr><td><?php _e('If you want to use custom sizes, override theme\'s CSS rules in the Custom CSS section under Theme Settings above.','zemanta_related_posts');?>
						</td></tr>
						<tr><td>
								<label style="margin-bottom: 10px;">
									<input name="zem_rp_custom_size_thumbnail_enabled" type="checkbox" id="zem_rp_custom_size_thumbnail_enabled" value="yes" <?php checked($options['custom_size_thumbnail_enabled']); ?> />
									<?php _e("Use Custom Size Thumbnails",'wp_related_posts');?>
								</label><br />
								<div id="zem_rp_custom_thumb_sizes_settings" style="display:none">
									<label style="margin-right: 20px;">
										<?php _e("Custom Width: ",'wp_related_posts');?>
										<input name="zem_rp_custom_thumbnail_width" type="text" id="zem_rp_custom_thumbnail_width" class="small-text" value="<?php esc_attr_e($options['custom_thumbnail_width']); ?>" /> px
									</label>
									<label>
										<?php _e("Custom Height: ",'wp_related_posts');?>
										<input name="zem_rp_custom_thumbnail_height" type="text" id="zem_rp_custom_thumbnail_height" class="small-text" value="<?php esc_attr_e($options['custom_thumbnail_height']); ?>" /> px
									</label>
								</div>
							</td></tr>
						</tbody>
					</table>

					<h3><?php _e("Other Settings:",'zemanta_related_posts'); ?></h3>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Exclude these Categories:', 'zemanta_related_posts'); ?></th>
							<td>
								<div class="excluded-categories">
									<?php
									$exclude = explode(',', $options['exclude_categories']);
									$args = array(
										'orderby' => 'name',
										'order' => 'ASC',
										'hide_empty' => false
										);

									foreach (get_categories($args) as $category) :
									?>
									<label>
										<input name="zem_rp_exclude_categories[]" type="checkbox" id="zem_rp_exclude_categories" value="<?php esc_attr_e($category->cat_ID); ?>"<?php checked(in_array($category->cat_ID, $exclude)); ?> />
										<?php esc_html_e($category->cat_name); ?>
										<br />
									</label>
									<?php endforeach; ?>
								</div>
							</td>
						</tr>
						<tr valign="top">
							<td colspan="2"><?php if(strpos(get_bloginfo('language'), 'en') === 0): ?>
								<br/>
								<label>
									<input name="zem_classic_state" type="checkbox" id="zem_classic_state" value="yes" <?php checked($meta['classic_user']); ?>>
									<?php _e("Display Related Posts Recommendations on Compose Screen", 'zemanta_related_posts');?>
								</label><?php endif; ?>
								<br />
								<label>
									<input name="zem_rp_on_single_post" type="checkbox" id="zem_rp_on_single_post" value="yes" <?php checked($options['on_single_post']); ?>>
									<?php _e("Auto Insert Related Posts",'zemanta_related_posts');?>
								</label>
								<?php _e('or add','zemanta_related_posts');?> <pre style="display: inline">&lt;?php zemanta_related_posts()?&gt;</pre> <?php _e('to your single post template)','zemanta_related_posts'); ?>
								<br />
								<label>
									<input name="zem_rp_display_zemanta_linky" type="checkbox" id="zem_rp_display_zemanta_linky" value="yes" <?php checked($options['display_zemanta_linky']); ?> />
									<?php _e("Support us (show our logo)",'zemanta_related_posts');?>
								</label>
								<br />
								<label>
										<input type="checkbox" name="zem_rp_only_admins_can_edit_related_posts" id="zem_rp_only_admins_can_edit_related_posts" value="yes" <?php checked($options['only_admins_can_edit_related_posts']); ?> />
										<?php _e("Only admins can edit Related Posts",'zemanta_related_posts');?>
								</label>
								<br />
								<label>
									<input name="zem_rp_on_rss" type="checkbox" id="zem_rp_on_rss" value="yes"<?php checked($options['on_rss']); ?>>
									<?php _e("Display Related Posts in Feed",'zemanta_related_posts');?>
								</label>
								<br />
							</td>
						</tr>
					</table>
					<p class="submit"><input type="submit" value="<?php _e('Save changes', 'zemanta_related_posts'); ?>" class="button-primary" /></p>

				</form>
			</div>
		</div>
		<iframe src="//player.vimeo.com/video/98544840" width="500" height="375" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> 
	</div>
