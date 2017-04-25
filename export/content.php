<?php

header( 'Content-Type: application/json; charset=UTF-8' );

require_once( '../../../../wp-config.php' );

if ( ! class_exists( 'WMobilePack_Export' ) ) {
	require_once( WMP_PLUGIN_PATH . 'export/class-export.php' );
}

// Disable error reporting because these methods are used as callbacks by the mobile web app
error_reporting( 0 );

if ( isset( $_GET['content'] ) ) {

	$export = new WMobilePack_Export();

	if ( isset( $_GET['callback'] ) ) {

		// filter callback param
		$callback = filter_var($_GET['callback'], FILTER_SANITIZE_STRING);

		header( 'Content-Type: application/javascript' );

		switch ( $_GET['content'] ) {

			case 'exportcategories':

				echo $callback . '(' . $export->export_categories() . ')';
				break;

			case 'exportcategory':

				echo $callback . '(' . $export->export_category() . ')';
				break;

			case 'exportarticles':

				echo $callback . '(' . $export->export_articles() . ')';
				break;

			case 'exportarticle':

				echo $callback . '(' . $export->export_article() . ')';
				break;

			case 'exportcomments':

				echo $callback . '(' . $export->export_comments() . ')';
				break;

			case 'savecomment':

				echo $callback . '(' . $export->save_comment() . ')';
				break;

			case 'exportpages':

				echo $callback . '(' . $export->export_pages() . ')';
				break;

			case 'exportpage':

				echo $callback . '(' . $export->export_page() . ')';
				break;

			default:
				echo $callback . '({"error":"No export requested"})';
		}
	} else {

		if ( ! class_exists( 'WMobilePack_Export_Settings' ) ) {
			require_once(WMP_PLUGIN_PATH.'/export/class-export-settings.php');
		}

		$export_settings = new WMobilePack_Export_Settings();

		switch ( $_GET['content'] ) {

			case 'androidmanifest':
			case 'mozillamanifest':

				if ( isset( $_GET['premium'] ) && $_GET['premium'] == 1 ) {
					echo $export_settings->export_manifest_premium();
				} else {
					echo $export_settings->export_manifest();
				}
				break;

			case 'apptexts':

				$format = 'javascript';
				if (isset($_GET['format']) && $_GET['format'] == 'json') {
					$format = 'json';
				}

				$app_texts = $export_settings->load_language($_GET['locale'], $format);

				if ( $app_texts !== false ) {
					header( 'Content-Type: application/' . $format);
					echo $app_texts;
				}

				break;

			case 'exportsettings':

				echo $export_settings->export_settings();
				break;

			default:
				echo '{"error":"No export requested","status":0}';
		}
	}
}
