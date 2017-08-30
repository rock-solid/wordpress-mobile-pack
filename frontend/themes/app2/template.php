<?php
$app = new WMobilePack_Application();
$app_settings = $app->load_app_settings();

$frontend_path = plugins_url()."/".WMP_DOMAIN."/frontend/";
$export_path = plugins_url()."/".WMP_DOMAIN."/export/";

$theme_path = $frontend_path."themes/app".$app_settings['theme']."/";

// check fonts
$loaded_fonts = array(
	$app_settings['font_headlines'],
    $app_settings['font_paragraphs']
);

$loaded_fonts = array_unique($loaded_fonts);

// check if locale file exists
$texts_json_exists = WMobilePack_Application::check_language_file(get_locale());

if ($texts_json_exists === false) {
    echo "ERROR, unable to load language file. Please check the '".WMP_DOMAIN."/frontend/locales/' folder.";
}
?>
<!DOCTYPE HTML>
<html manifest="" <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="mobile-web-app-capable" content="yes" />

	<?php if ($app_settings['manifest_color'] !== false) :?>
		<meta name="theme-color" content="<?php echo $app_settings['manifest_color']; ?>">
	<?php endif;?>

	<link rel="manifest" href="<?php echo $export_path."content.php?content=androidmanifest";?>" />

	<?php if ($app_settings['icon'] != ''): ?>
		<link rel="apple-touch-icon" href="<?php echo $app_settings['icon'];?>" />
    <?php endif;?>

    <title><?php echo get_bloginfo("name");?></title>
	<noscript>Your browser does not support JavaScript!</noscript>

	<!-- load css -->
	<link rel="stylesheet" href="<?php echo $theme_path;?>css/bundle.css?date=20170503">

    <?php if ($app_settings['theme_timestamp'] != ''):?>
        <link rel="stylesheet" href="<?php echo WMP_FILES_UPLOADS_URL.'theme-'.$app_settings['theme_timestamp'].'.css';?>" type="text/css" />
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo $theme_path;?>css/phone.css?date=20170503" type="text/css" />
    <?php endif;?>

	<!-- custom fonts -->
    <?php foreach ($loaded_fonts as $font_no):?>
        <link rel="stylesheet" href="<?php echo $frontend_path."fonts/font-".$font_no.".css?date=20160106";?>" type="text/css">
    <?php endforeach;?>

	<!-- load js -->
	<script type="text/javascript" pagespeed_no_defer="">
		window.__APPTICLES_BOOTSTRAP_DATA__ = {
			CONFIG_PATH: '<?php echo $export_path;?>content.php?content=exportsettings'
		};
	</script>
	<script src="<?php echo $theme_path;?>js/app.js?date=20170503" type="text/javascript"></script>
	<?php if ($app_settings['service_worker_installed'] == 1): ?>
		<script>
			if ('serviceWorker' in navigator) {
				navigator.serviceWorker.register('/sw.js');
			}
		</script>
	<?php endif; ?>
    <?php
        // check if google analytics id was set
        if ($app_settings['google_analytics_id'] != ''):
    ?>

        <script type="text/javascript" pagespeed_no_defer="">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '<?php echo $app_settings['google_analytics_id'];?>']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>

    <?php endif;?>
</head>
<body>
  <ion-nav-view></ion-nav-view>
</body>
</html>
