<?php
function websitez_themes_page(){
	global $wpdb, $websitez_plugin_description, $table_prefix, $websitez_free_version;
	
	if(function_exists('get_allowed_themes'))
		$themes_standard = get_allowed_themes();
	else
		$themes_standard = get_themes();
	$path = WEBSITEZ_PLUGIN_DIR.'/themes';
	$themes_preinstalled = websitez_get_themes($path,true);
	$themes = array_merge($themes_standard,$themes_preinstalled);
	
	if (isset($_GET['action']) ) {
		$preinstalled_themes_update = false;
		if ( 'activate' == $_GET['action'] ) {
			foreach($themes_preinstalled as $k=>$v):
				if($v['Template']==$_GET['template']){
					//If this is true, this is a theme located in the plugins folder
					//This value will tell the rest of the script to look in the plugin themes folder
					if(get_option(WEBSITEZ_USE_PREINSTALLED_THEMES_NAME)){
						update_option(WEBSITEZ_USE_PREINSTALLED_THEMES_NAME, "true");
						$preinstalled_themes_update = true;
					}
				}
			endforeach;

			//If this is false, it means we're using a theme from the regular themes folder
			//and must tell the rest of the script not to change the theme folder location
			if($preinstalled_themes_update == false){
				if(get_option(WEBSITEZ_USE_PREINSTALLED_THEMES_NAME)){
					update_option(WEBSITEZ_USE_PREINSTALLED_THEMES_NAME, "false");
				}
			}
			if(get_option(WEBSITEZ_ADVANCED_THEME))
				update_option(WEBSITEZ_ADVANCED_THEME, $_GET['template']);
			if(get_option(WEBSITEZ_BASIC_THEME))
				update_option(WEBSITEZ_BASIC_THEME, $_GET['template']);
			$activated=true;
		}
	}
	
	//Get the theme that is currently set for mobile devices
	$ct = current_mobile_theme_info($themes);
	unset($themes[$ct->name]);

	uksort( $themes, "strnatcasecmp" );
	$theme_total = count( $themes );
	$per_page = 15;

	if ( isset( $_GET['pagenum'] ) )
		$page = absint( $_GET['pagenum'] );

	if ( empty($page) )
		$page = 1;

	$start = $offset = ( $page - 1 ) * $per_page;

	$page_links = paginate_links( array(
		'base' => add_query_arg( 'pagenum', '%#%' ) . '#themenav',
		'format' => '',
		'prev_text' => __('&laquo;'),
		'next_text' => __('&raquo;'),
		'total' => ceil($theme_total / $per_page),
		'current' => $page
	));

	$themes = array_slice( $themes, $start, $per_page );
?>
<div class="wrap">
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%" valign="top">
			<div class="wz_pro">
				<div class="head">
					<?php echo esc_html( __(WEBSITEZ_PLUGIN_NAME." - Mobile Themes") ); ?>
					<ul class="nav">
						<li><?php echo sprintf(__( "%sUpgrade to WP Mobile Detector PRO%s", "wp-mobile-detector" ), '<a href="http://websitez.com/?utm_campaign=wp-admin-upgrade-link&utm_medium=web" target="_blank">','</a>'); ?></li>
						<li><?php echo sprintf(__( "%sRead User's Guide%s", "wp-mobile-detector" ), '<a href="http://websitez.com/wp-mobile-detector-guide/?utm_campaign=wp-admin-guide&utm_medium=web" target="_blank">','</a>'); ?></li>
						<li><?php echo sprintf(__( "%sWP Mobile Detector on Twitter%s", "wp-mobile-detector" ), '<a href="http://www.twitter.com/websitezcom" target="_blank">','</a>'); ?></li>
					</ul>
				</div>
				<div class="body">
					<a href="http://websitez.com/?utm_campaign=wp-admin-l-image&utm_medium=web" target="_blank"><img src="http://img.websitez.com/websitez-pro-pitch.png" border="0" class="desc"></a>
					<a href="http://websitez.com/?utm_campaign=wp-admin-r-image&utm_medium=web" target="_blank"><img src="http://img.websitez.com/websitez-pro-pitch-right.png" border="0" class="pic"></a>
				</div>
			</div>
		</td>
	</tr>
</table>
<div id="plugin-description" class="widefat alternate" style="margin:10px 0; padding:5px;background-color:#FFFEEB;">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="20" align="center" style="padding-top: 5px;"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/basic_phone_icon_16x16.gif"></td>
			<td><?php _e('<h3 style="margin: 0px 0px 10px;"><u>Basic Mobile Device</u></h3><p>The WP Mobile Detector will remove all images and advanced HTML  from being displayed on basic devices.</p>') ?></td>
		</tr>
		<tr>
			<td width="20" align="center" style="padding-top: 10px;"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/phone_icon_16x16.png"></td>
			<td><?php _e('<h3 style="margin: 5px 0px 10px;"><u>Advanced Mobile Device</u></h3><p>The WP Mobile Detector will resize images that are too large to display on advanced mobile devices.</p>') ?></td>
		</tr>
	</table>
</div>
<?php if ( !validate_current_mobile_theme($ct->template,$ct->template_dir) ) : ?>
<div id="message1" class="updated"><p><?php _e('The active mobile theme is broken.  Reverting to the default mobile theme.'); ?></p></div>
<?php elseif ( $activated == true ) :
?>
<div id="message2" class="updated"><p><?php printf( __( 'New mobile theme activated.' ), home_url( '/' ) ); ?></p></div><?php
 endif; ?>

<h3><?php _e('Current Mobile Theme'); ?></h3>
<div id="current-theme">
<?php if ( $ct->screenshot ) : ?>
<img src="<?php echo $ct->theme_root_uri . '/' . $ct->stylesheet . '/' . $ct->screenshot; ?>" alt="<?php _e('Current theme preview'); ?>" />
<?php endif; ?>
<h4><?php
	/* translators: 1: theme title, 2: theme version, 3: theme author */
	printf(__('%1$s %2$s by %3$s'), $ct->title, $ct->version, $ct->author) ; ?></h4>
<p class="theme-description"><?php echo $ct->description; ?></p>
<?php if ( current_user_can('edit_themes') && $ct->parent_theme ) { ?>
	<p><?php printf(__('The template files are located in <code>%2$s</code>. The stylesheet files are located in <code>%3$s</code>. <strong>%4$s</strong> uses templates from <strong>%5$s</strong>. Changes made to the templates will affect both themes.'), $ct->title, str_replace( WP_CONTENT_DIR, '', $ct->template_dir ), str_replace( WP_CONTENT_DIR, '', $ct->stylesheet_dir ), $ct->title, $ct->parent_theme); ?></p>
<?php } else { ?>
	<p><?php printf(__('All of this theme&#8217;s files are located in <code>%2$s</code>.'), $ct->title, str_replace( WP_CONTENT_DIR, '', $ct->template_dir ), str_replace( WP_CONTENT_DIR, '', $ct->stylesheet_dir ) ); ?></p>
<?php } ?>
<?php if ( $ct->tags ) : ?>
<p><?php _e('Tags:'); ?> <?php echo join(', ', $ct->tags); ?></p>
<?php endif; ?>
<?php theme_update_available($ct); ?>

</div>

<div class="clear"></div>
<?php
if ( ! current_user_can( 'switch_themes' ) ) {
	echo '</div>';
	require( './admin-footer.php' );
	exit;
}
?>
<h3><?php _e('Available Themes'); ?></h3>
<div class="clear"></div>

<?php if ( $theme_total ) { ?>

<?php if ( $page_links ) : ?>
<div class="tablenav">
<div class="tablenav-pages"><?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
	number_format_i18n( $start + 1 ),
	number_format_i18n( min( $page * $per_page, $theme_total ) ),
	number_format_i18n( $theme_total ),
	$page_links
); echo $page_links_text; ?></div>
</div>
<?php endif; ?>

<table id="availablethemes" cellspacing="0" cellpadding="0">
<?php
$style = '';

$theme_names = array_keys($themes);
natcasesort($theme_names);

$table = array();
$rows = ceil(count($theme_names) / 3);
for ( $row = 1; $row <= $rows; $row++ )
	for ( $col = 1; $col <= 3; $col++ )
		$table[$row][$col] = array_shift($theme_names);

foreach ( $table as $row => $cols ) {
?>
<tr>
<?php
foreach ( $cols as $col => $theme_name ) {
	$class = array('available-theme');
	if ( $row == 1 ) $class[] = 'top';
	if ( $col == 1 ) $class[] = 'left';
	if ( $row == $rows ) $class[] = 'bottom';
	if ( $col == 3 ) $class[] = 'right';
?>
	<td class="<?php echo join(' ', $class); ?>">
<?php if ( !empty($theme_name) ) :
	$template = $themes[$theme_name]['Template'];
	$stylesheet = $themes[$theme_name]['Stylesheet'];
	$title = $themes[$theme_name]['Title'];
	$version = $themes[$theme_name]['Version'];
	$description = $themes[$theme_name]['Description'];
	$author = $themes[$theme_name]['Author'];
	$screenshot = $themes[$theme_name]['Screenshot'];
	$stylesheet_dir = $themes[$theme_name]['Stylesheet Dir'];
	$template_dir = $themes[$theme_name]['Template Dir'];
	$parent_theme = $themes[$theme_name]['Parent Theme'];
	$theme_root = $themes[$theme_name]['Theme Root'];
	$theme_root_uri = $themes[$theme_name]['Theme Root URI'];
	$preview_link = esc_url(get_option('home') . '/');
	if ( is_ssl() )
		$preview_link = str_replace( 'http://', 'https://', $preview_link );
	$preview_link = htmlspecialchars( add_query_arg( array('preview' => 1, 'template' => $template, 'stylesheet' => $stylesheet, 'TB_iframe' => 'true' ), $preview_link ) );
	$preview_text = esc_attr( sprintf( __('Preview of &#8220;%s&#8221;'), $title ) );
	$tags = $themes[$theme_name]['Tags'];
	$thickbox_class = 'thickbox thickbox-preview';
	$activate_link = wp_nonce_url("admin.php?page=websitez_themes&action=activate&amp;template=".urlencode($template)."&amp;stylesheet=".urlencode($stylesheet), 'switch-theme_' . $template);
	$activate_text = esc_attr( sprintf( __('Activate &#8220;%s&#8221;'), $title ) );
	$actions = array();
	$actions[] = '<a href="' . $activate_link .  '" class="activatelink" title="' . $activate_text . '">' . __('Activate') . '</a>';
	$actions[] = '<a href="' . $preview_link . '" class="thickbox thickbox-preview" title="' . esc_attr(sprintf(__('Preview &#8220;%s&#8221;'), $theme_name)) . '" target="_blank">' . __('Preview') . '</a>';
	$actions = apply_filters('theme_action_links', $actions, $themes[$theme_name]);

	$actions = implode ( ' | ', $actions );
?>
		<a href="<?php echo $preview_link; ?>" class="<?php echo $thickbox_class; ?> screenshot">
<?php if ( $screenshot ) : ?>
			<img src="<?php echo $theme_root_uri . '/' . $stylesheet . '/' . $screenshot; ?>" alt="" />
<?php endif; ?>
		</a>
<h3><?php
	/* translators: 1: theme title, 2: theme version, 3: theme author */
	printf(__('%1$s %2$s by %3$s'), $title, $version, $author) ; ?></h3>
<p class="description"><?php echo $description; ?></p>
<span class='action-links'><?php echo $actions ?></span>
	<?php if ( current_user_can('edit_themes') && $parent_theme ) {
	/* translators: 1: theme title, 2:  template dir, 3: stylesheet_dir, 4: theme title, 5: parent_theme */ ?>
	<p><?php printf(__('The template files are located in <code>%2$s</code>. The stylesheet files are located in <code>%3$s</code>. <strong>%4$s</strong> uses templates from <strong>%5$s</strong>. Changes made to the templates will affect both themes.'), $title, str_replace( WP_CONTENT_DIR, '', $template_dir ), str_replace( WP_CONTENT_DIR, '', $stylesheet_dir ), $title, $parent_theme); ?></p>
<?php } else { ?>
	<p><?php printf(__('All of this theme&#8217;s files are located in <code>%2$s</code>.'), $title, str_replace( WP_CONTENT_DIR, '', $template_dir ), str_replace( WP_CONTENT_DIR, '', $stylesheet_dir ) ); ?></p>
<?php } ?>
<?php if ( $tags ) : ?>
<p><?php _e('Tags:'); ?> <?php echo join(', ', $tags); ?></p>
<?php endif; ?>
		<?php theme_update_available( $themes[$theme_name] ); ?>
<?php endif; // end if not empty theme_name ?>
	</td>
<?php } // end foreach $cols ?>
</tr>
<?php } // end foreach $table ?>
</table>
<?php } else { ?>
<p><?php
	if ( current_user_can('install_themes') )
		printf(__('You only have one theme installed right now. Live a little! You can choose from over 1,000 free themes in the WordPress.org Theme Directory at any time: just click on the <em><a href="%s">Install Themes</a></em> tab above.'), 'theme-install.php');
	else
		printf(__('Only the current theme is available to you. Contact the %s administrator for information about accessing additional themes.'), get_site_option('site_name'));
	?></p>
<?php } // end if $theme_total?>
<br class="clear" />

<?php if ( $page_links ) : ?>
<div class="tablenav">
<?php echo "<div class='tablenav-pages'>$page_links_text</div>"; ?>
<br class="clear" />
</div>
<?php endif; ?>

<br class="clear" />

<?php
// List broken themes, if any.
$broken_themes = get_broken_themes();
if ( current_user_can('edit_themes') && count( $broken_themes ) ) {
?>

<h2><?php _e('Broken Themes'); ?> <?php if ( is_multisite() ) _e( '(Site admin only)' ); ?></h2>
<p><?php _e('The following themes are installed but incomplete. Themes must have a stylesheet and a template.'); ?></p>

<table id="broken-themes">
	<tr>
		<th><?php _e('Name'); ?></th>
		<th><?php _e('Description'); ?></th>
	</tr>
<?php
	$theme = '';

	$theme_names = array_keys($broken_themes);
	natcasesort($theme_names);

	foreach ($theme_names as $theme_name) {
		$title = $broken_themes[$theme_name]['Title'];
		$description = $broken_themes[$theme_name]['Description'];

		$theme = ('class="alternate"' == $theme) ? '' : 'class="alternate"';
		echo "
		<tr $theme>
			 <td>$title</td>
			 <td>$description</td>
		</tr>";
	}
?>
</table>
<?php
}
?>
</div>

<?php
}

function current_mobile_theme_info($themes) {
	$current_theme_safe = get_current_mobile_theme();
	foreach($themes as $k=>$v):
		if($v['Template']==$current_theme_safe){
			$current_theme = $k;
			break;
		}else{
			$current_theme = ucwords(str_replace("-"," ",$current_theme_safe));
		}
	endforeach;

	$ct->name = $current_theme;
	$ct->title = $themes[$current_theme]['Title'];
	$ct->version = $themes[$current_theme]['Version'];
	$ct->parent_theme = $themes[$current_theme]['Parent Theme'];
	$ct->template_dir = $themes[$current_theme]['Template Dir'];
	$ct->stylesheet_dir = $themes[$current_theme]['Stylesheet Dir'];
	$ct->template = $themes[$current_theme]['Template'];
	$ct->stylesheet = $themes[$current_theme]['Stylesheet'];
	$ct->screenshot = $themes[$current_theme]['Screenshot'];
	$ct->description = $themes[$current_theme]['Description'];
	$ct->author = $themes[$current_theme]['Author'];
	$ct->tags = $themes[$current_theme]['Tags'];
	$ct->theme_root = $themes[$current_theme]['Theme Root'];
	$ct->theme_root_uri = $themes[$current_theme]['Theme Root URI'];
	return $ct;
}

function get_current_mobile_theme(){
	$theme = get_option(WEBSITEZ_ADVANCED_THEME);
	return $theme;
}
?>