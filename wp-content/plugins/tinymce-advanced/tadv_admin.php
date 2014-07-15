<?php

if ( !defined('TADV_ADMIN_PAGE') || !current_user_can('manage_options') )
	wp_die('Access denied');

if ( isset( $_POST['tadv_uninstall'] ) ) {
	check_admin_referer( 'tadv-uninstall' );

	delete_option('tadv_options');
	delete_option('tadv_toolbars');
	delete_option('tadv_plugins');
	delete_option('tadv_btns1');
	delete_option('tadv_btns2');
	delete_option('tadv_btns3');
	delete_option('tadv_btns4');
	delete_option('tadv_allbtns');

	?>
	<div class="updated" style="margin-top:30px;">
	<p><?php _e('All options have been removed from the database. You can', 'tadv'); ?> <a href="plugins.php"><?php _e('deactivate TinyMCE Advanced', 'tadv'); ?></a> <?php _e('or', 'tadv'); ?> <a href=""> <?php _e('reload this page', 'tadv'); ?></a> <?php _e('to reset them to the default values.', 'tadv'); ?></p>
	</div>
	<?php

	return;
}

if ( ! isset( $GLOBALS['wp_version'] ) || version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
	?>
	<div class="error" style="margin-top:30px;">
	<p><?php _e('This plugin requires WordPress version 3.6 or newer. Please upgrade your WordPress installation or download an', 'tadv'); ?> <a href="http://wordpress.org/extend/plugins/tinymce-advanced/download/"><?php _e('older version of the plugin.', 'tadv'); ?></a></p>
	</div>
	<?php

	return;
}

$imgpath = TADV_URL . 'images/';
$tadv_toolbars = get_option('tadv_toolbars');

function _tadv_parse_buttons($row) {
	$arr = array();

	if ( !empty($_POST['toolbar_' . $row . 'order']) ) {
		parse_str($_POST['toolbar_' . $row . 'order'], $arr);

		if ( !empty($arr['pre']) && is_array($arr['pre']) )
			$arr = $arr['pre'];
	}

	return $arr;
}

if ( empty($tadv_toolbars) || ! is_array($tadv_toolbars) ) {
	@include_once( TADV_PATH . 'tadv_defaults.php');
} else {
	$tadv_options = get_option('tadv_options');
	$tadv_toolbars['toolbar_1'] = isset($tadv_toolbars['toolbar_1']) && is_array($tadv_toolbars['toolbar_1']) ? $tadv_toolbars['toolbar_1'] : array();
	$tadv_toolbars['toolbar_2'] = isset($tadv_toolbars['toolbar_2']) && is_array($tadv_toolbars['toolbar_2']) ? $tadv_toolbars['toolbar_2'] : array();
	$tadv_toolbars['toolbar_3'] = isset($tadv_toolbars['toolbar_3']) && is_array($tadv_toolbars['toolbar_3']) ? $tadv_toolbars['toolbar_3'] : array();
	$tadv_toolbars['toolbar_4'] = isset($tadv_toolbars['toolbar_4']) && is_array($tadv_toolbars['toolbar_4']) ? $tadv_toolbars['toolbar_4'] : array();
}

if ( isset( $_POST['tadv-save'] ) ) {
	check_admin_referer( 'tadv-save-buttons-order' );

	$tadv_toolbars['toolbar_1'] = _tadv_parse_buttons(1);
	$tadv_toolbars['toolbar_2'] = _tadv_parse_buttons(2);
	$tadv_toolbars['toolbar_3'] = _tadv_parse_buttons(3);
	$tadv_toolbars['toolbar_4'] = _tadv_parse_buttons(4);

	$tadv_options['advlink1'] = !empty($_POST['advlink1']) ? 1 : 0;
	$tadv_options['advimage'] = !empty($_POST['advimage']) ? 1 : 0;
	$tadv_options['advlist'] = !empty($_POST['advlist']) ? 1 : 0;
	$tadv_options['contextmenu'] = !empty($_POST['contextmenu']) ? 1 : 0;

	$tadv_options['editorstyle'] = !empty($_POST['editorstyle']) ? 1 : 0;
	$tadv_options['hideclasses'] = !empty($_POST['hideclasses']) ? 1 : 0;
	$tadv_options['no_autop'] = !empty($_POST['no_autop']) ? 1 : 0;
	
	update_option( 'tadv_toolbars', $tadv_toolbars );
	update_option( 'tadv_options', $tadv_options );
}

$btns = array();
$hidden_row = 0;
$i = 0;
foreach ( $tadv_toolbars as $toolbar ) {
	$l = $t = false;
	$i++;

	if ( empty($toolbar) ) {
		$btns["toolbar_$i"] = array();
		continue;
	}

	foreach( $toolbar as $k => $v ) {
		if ( strpos($v, 'separator') !== false )
			$toolbar[$k] = 'separator';

		if ( 'layer' == $v )
			$l = $k;

		if ( 'tablecontrols' == $v )
			$t = $k;
		
		if ( empty($v) )
			unset($toolbar[$k]);
	}

	if ( $l !== false )
		array_splice( $toolbar, $l, 1, array('insertlayer', 'moveforward', 'movebackward', 'absolute') );

	if ( $t !== false )
		array_splice( $toolbar, $t + 1, 0, 'delete_table,' );

	$btns["toolbar_$i"] = $toolbar;
}
extract($btns);

if ( empty($toolbar_1) && empty($toolbar_2) && empty($toolbar_3) && empty($toolbar_4) ) {
	?><div class="error" id="message"><p><?php _e('All toolbars are empty! Default buttons loaded.', 'tadv'); ?></p></div><?php

	@include_once( TADV_PATH . 'tadv_defaults.php' );
	$used_buttons = array_merge( $tadv_btns1, $tadv_btns2, $tadv_btns3, $tadv_btns4 );
} else {
	$used_buttons = array_merge( $toolbar_1, $toolbar_2, $toolbar_3, $toolbar_4 );
}

if ( in_array('advhr', $used_buttons, true) )
	$plugins[] = 'advhr';

if ( in_array('insertlayer', $used_buttons, true) )
	$plugins[] = 'layer';

if ( in_array('visualchars', $used_buttons, true) )
	$plugins[] = 'visualchars';

if ( in_array('nonbreaking', $used_buttons, true) )
	$plugins[] = 'nonbreaking';

if ( in_array('styleprops', $used_buttons, true) )
	$plugins[] = 'style';

if ( in_array('emotions', $used_buttons, true) )
	$plugins[] = 'emotions';

if ( in_array('insertdate', $used_buttons, true) || in_array('inserttime', $used_buttons, true) )
	$plugins[] = 'insertdatetime';

if ( in_array('tablecontrols', $used_buttons, true) )
	$plugins[] = 'table';

if ( in_array('print', $used_buttons, true) )
	$plugins[] = 'print';

if ( in_array('iespell', $used_buttons, true) )
	$plugins[] = 'iespell';

if ( in_array('search', $used_buttons, true) || in_array('replace', $used_buttons, true) )
	$plugins[] = 'searchreplace';

if ( in_array('cite', $used_buttons, true) || in_array('ins', $used_buttons, true) ||
	in_array('del', $used_buttons, true) || in_array('abbr', $used_buttons, true) ||
	in_array('acronym', $used_buttons, true) || in_array('attribs', $used_buttons, true) )
		$plugins[] = 'xhtmlxtras';

if ( !empty($tadv_options['advlink1']) )
	$plugins[] = 'advlink';

if ( !empty($tadv_options['advlist']) )
	$plugins[] = 'advlist';

if ( !empty($tadv_options['advimage']) )
	$plugins[] = 'advimage';

if ( !empty($tadv_options['contextmenu']) )
	$plugins[] = 'contextmenu';

$buttons = array( 'Horizontal rule' => 'hr', 'Hide next row' => 'wp_adv', 'Quote' => 'blockquote', 'Bold' => 'bold', 'Italic' => 'italic', 'Strikethrough' => 'strikethrough', 'Underline' => 'underline', 'Bullet List' => 'bullist', 'Numbered List' => 'numlist', 'Outdent' => 'outdent', 'Indent' => 'indent', 'Allign Left' => 'justifyleft', 'Center' => 'justifycenter', 'Alligh Right' => 'justifyright', 'Justify' => 'justifyfull', 'Cut' => 'cut', 'Copy' => 'copy', 'Paste' => 'paste', 'Link' => 'link', 'Remove Link' => 'unlink', 'Insert Image' => 'image', 'More Tag' => 'wp_more', 'Split Page' => 'wp_page', 'Search' => 'search', 'Replace' => 'replace', '<!--fontselect-->' => 'fontselect', '<!--fontsizeselect-->' => 'fontsizeselect', 'Help' => 'wp_help', 'Full Screen' => 'fullscreen', '<!--styleselect-->' => 'styleselect', '<!--formatselect-->' => 'formatselect', 'Text Color' => 'forecolor', 'Back Color' => 'backcolor', 'Paste as Text' => 'pastetext', 'Paste from Word' => 'pasteword', 'Remove Format' => 'removeformat', 'Clean Code' => 'cleanup', 'Check Spelling' => 'spellchecker', 'Character Map' => 'charmap', 'Print' => 'print', 'Undo' => 'undo', 'Redo' => 'redo', 'Table' => 'tablecontrols', 'Citation' => 'cite', 'Inserted Text' => 'ins', 'Deleted Text' => 'del', 'Abbreviation' => 'abbr', 'Acronym' => 'acronym', 'XHTML Attribs' => 'attribs', 'Layer' => 'layer', 'Advanced HR' => 'advhr', 'View HTML' => 'code', 'Hidden Chars' => 'visualchars', 'NB Space' => 'nonbreaking', 'Sub' => 'sub', 'Sup' => 'sup', 'Visual Aids' => 'visualaid', 'Insert Date' => 'insertdate', 'Insert Time' => 'inserttime', 'Anchor' => 'anchor', 'Style' => 'styleprops', 'Smilies' => 'emotions', 'Insert Movie' => 'media', 'IE Spell' => 'iespell' );

if ( function_exists('moxiecode_plugins_url') ) {
	if ( moxiecode_plugins_url('imagemanager') )
		$buttons['MCFileManager'] = 'insertimage';

	if ( moxiecode_plugins_url('filemanager') )
		$buttons['MCImageManager'] = 'insertfile';
}

$tadv_allbtns = array_values($buttons);
$tadv_allbtns[] = 'separator';
$tadv_allbtns[] = '|';

if ( isset($_POST['tadv-save']) ) {
	update_option( 'tadv_plugins', $plugins );
	update_option( 'tadv_btns1', $toolbar_1 );
	update_option( 'tadv_btns2', $toolbar_2 );
	update_option( 'tadv_btns3', $toolbar_3 );
	update_option( 'tadv_btns4', $toolbar_4 );
	update_option( 'tadv_allbtns', $tadv_allbtns );
?>
	<div class="updated" id="message"><p><?php _e('Options saved', 'tadv'); ?></p></div>
<?php } ?>

<div class="wrap" id="contain">
<h2><?php _e('TinyMCE Buttons Arrangement', 'tadv'); ?></h2>
<form id="tadvadmin" method="post" action="" onsubmit="">
<p><?php _e('Drag and drop buttons onto the toolbars below.', 'tadv'); ?></p>

<div id="tadvzones">
<input id="toolbar_1order" name="toolbar_1order" value="" type="hidden" />
<input id="toolbar_2order" name="toolbar_2order" value="" type="hidden" />
<input id="toolbar_3order" name="toolbar_3order" value="" type="hidden" />
<input id="toolbar_4order" name="toolbar_4order" value="" type="hidden" />
<input name="tadv-save" value="1" type="hidden" />

<div class="tadvdropzone">
<ul style="position: relative;" id="toolbar_1" class="container">
<?php

if ( is_array($tadv_toolbars['toolbar_1']) ) {
	$tb1 = array();
	
	foreach( $tadv_toolbars['toolbar_1'] as $k ) {
		$t = array_intersect( $buttons, (array) $k );
		$tb1 += $t;
	}

	foreach( $tb1 as $name => $btn ) {
		if ( strpos( $btn, 'separator' ) !== false )
			continue;
		
		?>
		<li class="tadvmodule" id="pre_<?php echo $btn; ?>">
		<div class="tadvitem"><div id="<?php echo $btn; ?>" title="<?php echo $name; ?>"></div>
		<span class="descr"> <?php echo $name; ?></span>
		</div></li>
		<?php
	}

	$buttons = array_diff( $buttons, $tb1 );
}

?>
</ul></div>
<br class="clear" />

<div class="tadvdropzone">
<ul style="position: relative;" id="toolbar_2" class="container">
<?php

if ( is_array($tadv_toolbars['toolbar_2']) ) {
	$tb2 = array();
	
	foreach( $tadv_toolbars['toolbar_2'] as $k ) {
		$t = array_intersect( $buttons, (array) $k );
		$tb2 = $tb2 + $t;
	}

	foreach( $tb2 as $name => $btn ) {
		if ( strpos( $btn, 'separator' ) !== false )
			continue;

		?>
		<li class="tadvmodule" id="pre_<?php echo $btn; ?>">
		<div class="tadvitem"><div id="<?php echo $btn; ?>" title="<?php echo $name; ?>"></div>
		<span class="descr"> <?php echo $name; ?></span></div></li>
		<?php
	}

	$buttons = array_diff( $buttons, $tb2 );
}

?>
</ul></div>
<br class="clear" />

<div class="tadvdropzone">
<ul style="position: relative;" id="toolbar_3" class="container">
<?php

if ( is_array($tadv_toolbars['toolbar_3']) ) {
	$tb3 = array();
	
	foreach( $tadv_toolbars['toolbar_3'] as $k ) {
		$t = array_intersect( $buttons, (array) $k );
		$tb3 += $t;
	}

	foreach( $tb3 as $name => $btn ) {
		if ( strpos( $btn, 'separator' ) !== false )
			continue;
		
		?>
		<li class="tadvmodule" id="pre_<?php echo $btn; ?>">
		<div class="tadvitem"><div id="<?php echo $btn; ?>" title="<?php echo $name; ?>"></div>
		<span class="descr"> <?php echo $name; ?></span></div></li>
		<?php
	}

	$buttons = array_diff( $buttons, $tb3 );
}

?>
</ul></div>
<br class="clear" />

<div class="tadvdropzone">
<ul style="position: relative;" id="toolbar_4" class="container">
<?php

if ( is_array($tadv_toolbars['toolbar_4']) ) {
	$tb4 = array();
	
	foreach( $tadv_toolbars['toolbar_4'] as $k ) {
		$t = array_intersect( $buttons, (array) $k );
		$tb4 += $t;
	}

	foreach( $tb4 as $name => $btn ) {
		if ( strpos( $btn, 'separator' ) !== false )
			continue;
		
		?>
		<li class="tadvmodule" id="pre_<?php echo $btn; ?>">
		<div class="tadvitem"><div id="<?php echo $btn; ?>" title="<?php echo $name; ?>"></div>
		<span class="descr"> <?php echo $name; ?></span></div></li>
		<?php
	}

	$buttons = array_diff( $buttons, $tb4 );
}

?>
</ul></div>
<br class="clear" />
</div>

<div id="tadvWarnmsg">&nbsp;
	<span id="too_long" style="display:none;"><?php _e('Adding too many buttons will make the toolbar too long and will not display correctly in TinyMCE!', 'tadv'); ?></span>
</div>

<div id="tadvpalettediv">
<ul style="position: relative;" id="tadvpalette">
<?php

if ( is_array($buttons) ) {
	foreach( $buttons as $name => $btn ) {
		if ( strpos( $btn, 'separator' ) !== false )
			continue;
		
		?>
		<li class="tadvmodule" id="pre_<?php echo $btn; ?>">
		<div class="tadvitem"><div id="<?php echo $btn; ?>" title="<?php echo $name; ?>"></div>
		<span class="descr"> <?php echo $name; ?></span></div></li>
		<?php
	}
}

?>
</ul>
</div>

<table class="clear" style="margin:10px 0">
	<tr><td style="padding:2px 12px 8px;">
	<?php _e('Also enable:', 'tadv'); ?>
	
	<label for="advimage" class="tadv-box">
	<input type="checkbox" class="tadv-chk"  name="advimage" id="advimage" <?php if ( !empty($tadv_options['advimage']) ) echo ' checked="checked"'; ?> />
	<?php _e('Advanced Image', 'tadv'); ?>
	</label>
	
	<label for="advlist" class="tadv-box">
	<input type="checkbox" class="tadv-chk"  name="advlist" id="advlist" <?php if ( !empty($tadv_options['advlist']) ) echo ' checked="checked"'; ?> />
	<?php _e('Advanced List Options', 'tadv'); ?>
	</label>

	<label for="contextmenu" class="tadv-box">
	<input type="checkbox" class="tadv-chk"  name="contextmenu" id="contextmenu" <?php if ( !empty($tadv_options['contextmenu']) ) echo ' checked="checked"'; ?> />
	<?php _e('Context Menu', 'tadv'); ?>
	</label>
	</td></tr>
<?php

$mce_locale = get_locale();
$mce_locale = empty( $mce_locale ) ? 'en' : strtolower( substr( $mce_locale, 0, 2 ) );

if ( $mce_locale != 'en' && ! @file_exists( TADV_PATH . 'mce/advlink/langs/' . $mce_locale . '_dlg.js' ) ) {
	if ( in_array( $mce_locale, array( 'ar', 'be', 'nb', 'bg', 'ca', 'cn', 'cs', 'da', 'nl', 'fa', 'fi', 'gl', 'el', 'he', 'hi', 'hu', 'id', 'km', 'ko', 'lv', 'lt', 'mk', 'no', 'pl', 'ro', 'sk', 'sl', 'sv', 'tr', 'uk', 'cy' ), true ) ) {
		?>
		<tr><td style="padding:2px 12px 8px;">
			<p><b><?php _e('Language Options'); ?></b></p>
			<p>
			<?php

			printf( __('Your WordPress language is set to <b>%1$s</b>, however there is no matching language installed for the TinyMCE components added from this plugin. You can install the <a href="%2$s">TnyMCE Advanced Language Pack</a> plugin to add it.', 'tadv'), esc_html( get_locale() ), esc_url('http://wordpress.org/plugins/tinymce-advanced-language-pack/') );

			?>
			</p>
		</td></tr>
		<?php
	}
} // end mce_locale

?>
	<tr><td style="border:1px solid #CD0000;padding:2px 12px 8px;">
	<p style="font-weight:bold;color:#CD0000;"><?php _e('Advanced Options', 'tadv'); ?></p>

	<p>
		<label for="advlink1" class="tadv-box">
		<input type="checkbox" class="tadv-chk"  name="advlink1" id="advlink1" <?php if ( !empty($tadv_options['advlink1']) ) echo ' checked="checked"'; ?> />
		<?php _e('Advanced Link', 'tadv'); ?>
		</label>
		<?php _e('Enabling this TinyMCE plugin will overwrite the internal links feature in WordPress 3.1 and newer. Cuttently there is no way to enable both of them at the same time.', 'tadv'); ?>
	</p>
	
<?php

if ( ! current_theme_supports( 'editor-style' ) ) {

	?>
	<p>
		<?php _e('It seems your theme (still) doesn\'t support customised styles for the editor. If you would like to use that, you can create a file named <i>editor-style.css</i> and add it to your theme\'s directory. You can use the editor-style.css from the Twenty Ten theme as a template.', 'tadv'); ?>
	</p>

	<p>
		<label for="editorstyle" class="tadv-box"><input type="checkbox" class="tadv-chk"  name="editorstyle" id="editorstyle" <?php if ( !empty($tadv_options['editorstyle']) ) echo ' checked="checked"'; ?> />
		<?php _e('Import editor-style.css.', 'tadv'); ?>
		</label>
		<?php _e('This is only needed if you created that file. Themes that style the editor will import the stylesheet automatically.', 'tadv'); ?>
	</p>
	<?php
}

?>
	<p>
		<label for="hideclasses" class="tadv-box">
		<input type="checkbox" class="tadv-chk"  name="hideclasses" id="hideclasses" <?php if ( !empty($tadv_options['hideclasses']) ) echo ' checked="checked"'; ?> />
		<?php _e('Hide all CSS classes in the editor menus.', 'tadv'); ?>
		</label>
		<?php _e('Note that selecting this will also disable the Styles drop-down menu.', 'tadv'); ?>
	</p>

	<p>
		<label for="no_autop" class="tadv-box">
		<input type="checkbox" class="tadv-chk"  name="no_autop" id="no_autop" <?php if ( !empty($tadv_options['no_autop']) ) echo ' checked="checked"'; ?> />
		<?php _e('Stop removing the &lt;p&gt; and &lt;br /&gt; tags when saving and show them in the HTML editor', 'tadv'); ?>
		</label>
		<?php _e('This will make it possible to use more advanced coding in the HTML editor without the back-end filtering affecting it much. However it may behave unexpectedly in rare cases, so test it thoroughly before enabling it permanently. Also line breaks in the HTML editor would still affect the output, in particular do not use empty lines, line breaks inside HTML tags or multiple &lt;br /&gt; tags.', 'tadv'); ?>
	</p>
	</td></tr>
</table>

<p>
	<?php wp_nonce_field( 'tadv-save-buttons-order' ); ?>
	<input class="button tadv_btn" type="button" class="tadv_btn" value="<?php _e('Remove Settings', 'tadv'); ?>" onclick="document.getElementById('tadv_uninst_div').style.display = 'block';" />
	<input class="button-primary tadv_btn" type="button" value="<?php _e('Save Changes', 'tadv'); ?>" onclick="tadvSortable.serialize();" />
</p>
</form>

<div id="tadvWarnmsg2">&nbsp;
	<span id="sink_err" style="display:none;"><?php _e('The Kitchen Sink button shows/hides the next toolbar row. It will not work at the current place.', 'tadv'); ?></span>
</div>

<div id="tadv_uninst_div" style="">
<form method="post" action="">
<?php wp_nonce_field('tadv-uninstall'); ?>
<div><?php _e('Remove all saved settings from the database?', 'tadv'); ?>
<input class="button tadv_btn" type="button" name="cancel" value="<?php _e('Cancel', 'tadv'); ?>" onclick="document.getElementById('tadv_uninst_div').style.display = 'none';" style="margin-left:20px" />
<input class="button tadv_btn" type="submit" name="tadv_uninstall" value="<?php _e('Continue', 'tadv'); ?>" /></div>
</form>
</div>
</div>
