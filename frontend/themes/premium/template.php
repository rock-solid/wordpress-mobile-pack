<?php

    $premium_manager = new WMobilePack_Premium();
	$json_config_premium = $premium_manager->set_premium_config();
    
    $arr_config_premium = null;
	if ($json_config_premium !== false) {
		$arr_config_premium = json_decode($json_config_premium, true);
	}
    
    // check if we have a valid domain
    if (isset($arr_config_premium['domain_name']) && filter_var('http://'.$arr_config_premium['domain_name'], FILTER_VALIDATE_URL)) {
        header("Location: http://".$arr_config_premium['domain_name']);
        exit();
    }
    
    // check if we have a secure https connection
    $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    
    // Check if the browser supports the loading of gzipped files
    $supported_gzip = false;
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	   $supported_gzip = true;
    
	// check if it is tablet
    if (!class_exists( 'WMobilePack_Detect' ) ) {
        require_once(WMP_PLUGIN_PATH.'frontend/class-detect.php');
    }

    $detect_manager = new WMobilePack_Detect();
    $is_tablet = $detect_manager->is_tablet();

    $cdn_kits = ($is_secure ? $arr_config_premium['cdn_kits_https'] : $arr_config_premium['cdn_kits']);
    $cdn_apps = ($is_secure ? $arr_config_premium['cdn_apps_https'] : $arr_config_premium['cdn_apps']);

    // Check if we have to load a custom theme
    if ( ($is_tablet == 0 && $arr_config_premium['phone']['theme'] != 0) || ($is_tablet == 1 && $arr_config_premium['tablet']['theme'] != 0)) {
        $kits_path = $cdn_kits."/app".($is_tablet == 0 ? $arr_config_premium['phone']['theme'] : $arr_config_premium['tablet']['theme']).'/'.$arr_config_premium['kit_version'].'/';
    } else {
        $kits_path = $cdn_apps."/".$arr_config_premium['shorten_url'].'/';
    }

    // ----------------------------------------- //

    // Process icons & startup screens timestamps
    $icon_timestamp = '';
    if (isset($arr_config_premium['icon_path']) && $arr_config_premium['icon_path'] != '') {
        $str = $arr_config_premium['icon_path'];
        $icon_timestamp = '_'.substr($str, strpos($str, '_') + 1 , strpos($str, '.') - strpos($str, '_') - 1);
    }

    $logo_timestamp = '';
    if (isset($arr_config_premium['logo_path']) && $arr_config_premium['logo_path'] != '') {
        $str = $arr_config_premium['logo_path'];
        $logo_timestamp = '_'.substr($str, strpos($str, '_') + 1 , strpos($str, '.') - strpos($str, '_') - 1);
    }

    // ----------------------------------------- //

    // Set cover settings
    $cover = '';

    if ($is_tablet == 0 && isset($arr_config_premium['phone']['cover']) && $arr_config_premium['phone']['cover'] != '')
        $cover = $arr_config_premium['phone']['cover'];

    if ($is_tablet == 1 && isset($arr_config_premium['tablet']['cover']) && $arr_config_premium['tablet']['cover'] != '')
        $cover = $arr_config_premium['tablet']['cover'];

    // ----------------------------------------- //

    // Set locale
    $locale = isset($arr_config_premium['locale']) && $arr_config_premium['locale'] != '' ? $arr_config_premium['locale'] : 'en_EN';

    // ----------------------------------------- //

    // Set device
    $device = $is_tablet == 0 ? 'phone' : 'tablet';
?>
<!DOCTYPE HTML>
<html manifest="" lang="en-US">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="apple-touch-icon-precomposed" href="" />
    <link rel="manifest" href="<?php echo plugins_url()."/".WMP_DOMAIN."/export/content.php?content=androidmanifest";?>" />

    <?php if (isset($arr_config_premium['icon_path']) && $arr_config_premium['icon_path'] != ''): // icon path for Firefox ?>
        <link rel="shortcut icon" href="<?php echo $cdn_apps."/".$arr_config_premium['shorten_url'].'/'.$arr_config_premium['icon_path'];?>"/>
    <?php endif;?>

    <title><?php echo $arr_config_premium['title'];?></title>
    <style type="text/css">
        /**
        * Example of an initial loading indicator.
        * It is recommended to keep this as minimal as possible to provide instant feedback
        * while other resources are still being loaded for the first time
        */
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #e5e8e3;
        }

        #appLoadingIndicator {
            position: absolute;
            top: 50%;
            margin-top: -8px;
            text-align: center;
            width: 100%;
            height: 16px;
            -webkit-animation-name: appLoadingIndicator;
            -webkit-animation-duration: 0.5s;
            -webkit-animation-iteration-count: infinite;
            -webkit-animation-direction: linear;
            animation-name: appLoadingIndicator;
            animation-duration: 0.5s;
            animation-iteration-count: infinite;
            animation-direction: linear;
        }

        #appLoadingIndicator > * {
            background-color: #c6cdbe;
            display: inline-block;
            height: 16px;
            width: 16px;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
            margin: 0 2px;
            opacity: 0.8;
        }

        @-webkit-keyframes appLoadingIndicator{
            0% {
                opacity: 0.8
            }
            50% {
                opacity: 0
            }
            100% {
                opacity: 0.8
            }
        }

        @keyframes appLoadingIndicator{
            0% {
                opacity: 0.8
            }
            50% {
                opacity: 0
            }
            100% {
                opacity: 0.8
            }
        }
    </style>

    <script type="text/javascript" pagespeed_no_defer="">
        var appticles = {
            webApp: "<?php echo $arr_config_premium['webapp'];?>",
            title: "<?php echo addslashes($arr_config_premium['title']);?>", // to update the title tag with the same constant

            exportPath: '<?php echo $is_secure ? $arr_config_premium['api_content_https'] : $arr_config_premium['api_content'];?>',
            socialApiPath: '<?php echo $is_secure ? $arr_config_premium['api_social_https'] : $arr_config_premium['api_social'];?>',

            <?php if (isset($arr_config_premium['api_content_external'])):?>
            exportPathExternal: '<?php echo $arr_config_premium['api_content_external'];?>',
            <?php endif;?>

            defaultPath: '<?php echo $kits_path;?>',
            appPath: '<?php echo $cdn_apps."/".$arr_config_premium['shorten_url'];?>',

            logo: '<?php echo isset($arr_config_premium['logo_path']) && $arr_config_premium['logo_path'] != '' ? $cdn_apps."/".$arr_config_premium['shorten_url'].'/'.$arr_config_premium['logo_path'] : $cdn_kits."/app1/".$arr_config_premium['kit_version']."/resources/images/logo.png";?>',
            hasIcons: <?php echo intval(isset($arr_config_premium['icon_path']) && $arr_config_premium['icon_path'] != "");?>,
            hasStartups: <?php echo intval(isset($arr_config_premium['logo_path']) && $arr_config_premium['logo_path'] != "");?>,
            iconTimestamp: '<?php echo $icon_timestamp;?>',
            startupImageTimestamp: '<?php echo $logo_timestamp;?>',

            userCover: <?php echo $cover == "" ? 'false' : 'true' ;?>,
            defaultCover: "<?php echo $cover == "" ? $cdn_kits.'/others/covers/'.($is_tablet ? 'tablet' : 'phone').'/pattern-'.rand(1,8).'.jpg' : $cdn_apps."/".$arr_config_premium['shorten_url'].'/'.$cover;?>",

            appUrl: '<?php echo home_url();?>',
            websiteUrl: '<?php echo home_url(); echo parse_url(home_url(), PHP_URL_QUERY) ? '&' : '?'; echo WMobilePack_Cookie::$prefix; ?>theme_mode=desktop',
            canonicalUrl: '<?php echo home_url();?>',

            preview: 0,
            language: '<?php echo $locale;?>',

            // enable the social modules
            <?php if (isset($arr_config_premium['api_content_external'])):?>
                hasFacebook: false,
                hasTwitter: false,
            <?php else:?>
                hasFacebook: <?php echo !isset($arr_config_premium['enable_facebook']) || $arr_config_premium['enable_facebook'] == 1 ? 'true' : 'false';?>,
                hasTwitter: <?php echo !isset($arr_config_premium['enable_twitter']) || $arr_config_premium['enable_twitter'] == 1 ? 'true' : 'false';?>,
            <?php endif;?>

            <?php if ($arr_config_premium['has_phone_ads'] == 1 || $arr_config_premium['has_tablet_ads'] == 1):?>
            googleAds:{
                adsInterval: <?php echo isset($arr_config_premium[$device.'_ad_interval']) && $arr_config_premium[$device.'_ad_interval'] != ''  ? $arr_config_premium[$device.'_ad_interval'] : 30;?>,      // seconds between ads

                <?php if ($arr_config_premium['has_phone_ads'] == 1):?>
                    phone: {
                        networkCode: <?php echo $arr_config_premium['phone_network_code'];?>,
                        adUnitCode: "<?php echo $arr_config_premium['phone_unit_name'];?>",
                        sizes: <?php echo json_encode($arr_config_premium['phone_ad_sizes']);?>
                    },
                <?php else: ?>
                    phone : null,
                <?php endif;?>

                <?php if ($arr_config_premium['has_tablet_ads'] == 1):?>
                    tablet: {
                        networkCode: <?php echo $arr_config_premium['tablet_network_code'];?>,
                        adUnitCode: "<?php echo $arr_config_premium['tablet_unit_name'];?>",
                        sizes: <?php echo json_encode($arr_config_premium['tablet_ad_sizes']);?>
                    },
                <?php else: ?>
                    tablet : null,
                <?php endif;?>
            },
            <?php endif;?>
        };
    </script>

    <?php if (($arr_config_premium['has_phone_ads'] == 1 && $is_tablet == 0) || ($arr_config_premium['has_tablet_ads'] == 1 && $is_tablet == 1)):?>

        <!-- start Google Doubleclick for publishers -->
        <script type='text/javascript' pagespeed_no_defer="">
            var googletag = googletag || {};
            googletag.cmd = googletag.cmd || [];
            (function() {
                var gads = document.createElement('script');
                gads.async = true;
                gads.type = 'text/javascript';
                var useSSL = 'https:' == document.location.protocol;
                gads.src = (useSSL ? 'https:' : 'http:') +
                '//www.googletagservices.com/tag/js/gpt.js';
                var node = document.getElementsByTagName('script')[0];
                node.parentNode.insertBefore(gads, node);
            })();

            googletag.cmd.push(function() {
                googletag.pubads().enableSingleRequest();
                googletag.pubads().disableInitialLoad();
                googletag.pubads().collapseEmptyDivs(); 		// hide ad units when empty.
                googletag.enableServices();
            });
        </script>
        <!-- end Google Doubleclick for publishers -->
    <?php endif;?>

    <?php

    $theme_details = array(
        'theme'             => $arr_config_premium[$device]['theme'],
        'color_scheme'      => isset($arr_config_premium[$device]['color_scheme']) ? $arr_config_premium[$device]['color_scheme'] : 1,
        'font_headlines'    => isset($arr_config_premium[$device]['font_headlines']) ? $arr_config_premium[$device]['font_headlines'] : '',
        'font_subtitles'    => isset($arr_config_premium[$device]['font_subtitles']) ? $arr_config_premium[$device]['font_subtitles'] : '',
        'font_paragraphs'   => isset($arr_config_premium[$device]['font_paragraphs']) ? $arr_config_premium[$device]['font_paragraphs'] : '',
        'theme_timestamp'   => isset($arr_config_premium[$device]['theme_timestamp']) ? $arr_config_premium[$device]['theme_timestamp'] : '',
    );

    $arrLoadedFonts = array();

    if (is_numeric($theme_details['font_headlines']))
        $arrLoadedFonts[] = $theme_details['font_headlines'];

    if (!in_array($theme_details['font_subtitles'], $arrLoadedFonts) && is_numeric($theme_details['font_subtitles']))
        $arrLoadedFonts[] = $theme_details['font_subtitles'];

    if (!in_array($theme_details['font_paragraphs'], $arrLoadedFonts) && is_numeric($theme_details['font_paragraphs']))
        $arrLoadedFonts[] = $theme_details['font_paragraphs'];

    // Check if we have custom fonts, otherwise load at least one default font
    if (!isset($arr_config_premium[$device]['custom_fonts']) && count($arrLoadedFonts) == 0) {
        $arrLoadedFonts[] = 1;
        $theme_details['font_headlines'] = 1;
    }
    ?>

    <?php foreach ($arrLoadedFonts as $font_no):?>
        <link rel="stylesheet" href="<?php echo $cdn_kits;?>/resources/fonts/<?php echo $supported_gzip ? 'font-'.$font_no.'-css.gz' : 'font-'.$font_no.'.css' ;?>" type="text/css" />
    <?php endforeach; ?>

    <?php

    // load custom fonts
    $arrCustomFonts = array();

    if (isset($arr_config_premium[$device]['custom_fonts'])) {

        $arrFontsNo = explode(',', $arr_config_premium[$device]['custom_fonts']);

        foreach ($arrFontsNo as $font_no){
            if (is_numeric($font_no)){
                $arrCustomFonts[] = $font_no;
            }
        }
    }
    ?>

    <?php foreach ($arrCustomFonts as $font_no):?>
        <link rel="stylesheet" href="<?php echo $cdn_apps."/".$arr_config_premium['shorten_url'];?>/resources/css/<?php echo $supported_gzip ? 'font-'.$font_no.'-css.gz' : 'font-'.$font_no.'.css' ;?>" type="text/css" />
    <?php endforeach; ?>

    <?php if ($theme_details['theme'] == 0): // load custom theme ?>
        <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/<?php echo $device.'-'.$theme_details['theme_timestamp'].($supported_gzip ? '-css.gz' : '.css');?>" type="text/css" />
    <?php elseif ($theme_details['theme_timestamp'] != ''): // check if we have a generated css ?>
        <link rel="stylesheet" href="<?php echo $cdn_apps."/".$arr_config_premium['shorten_url'];?>/resources/css/<?php echo $device.'-'.$theme_details['theme_timestamp'].($supported_gzip ? '-css.gz' : '.css');?>" type="text/css" />
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/<?php echo $device.'/colors-'.$theme_details['color_scheme'].'-font-'.$theme_details['font_headlines'].($supported_gzip ? '-css.gz' : '.css');?>" type="text/css" />
    <?php endif;?>

    <script type="text/javascript" src="<?php echo $cdn_kits;?>/others/locales/<?php echo $locale.($supported_gzip ? '-js.gz' : '.js');?>"></script>
    <script type="text/javascript" src="<?php echo $kits_path;?>js/<?php echo $device.($supported_gzip ? '-js.gz' : '.js');?>"></script>

    <?php
    // check if google analytics id was set
    $google_analytics_id = isset($arr_config_premium['google_analytics_id']) ? $arr_config_premium['google_analytics_id'] : '';
    if ($google_analytics_id != ''):
        ?>

        <script type="text/javascript" pagespeed_no_defer="">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '<?php echo $google_analytics_id;?>']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>

    <?php endif;?>

    <?php
    // check if google analytics id was set
    $google_internal_id = isset($arr_config_premium['google_internal_id']) ? $arr_config_premium['google_internal_id'] : '';
    if ($google_internal_id != ''):
        ?>

        <!-- add google universal analytics -->
        <script pagespeed_no_defer="">
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-45917766-1');

            var dimensionValue = '<?php echo $arr_config_premium['google_internal_id'];?>';
            ga('set', 'dimension1', dimensionValue);

            ga('send', 'pageview');

        </script>
    <?php endif;?>

    <?php if (isset($arr_config_premium['google_webmasters_code']) && $arr_config_premium['google_webmasters_code'] != ""):?>
        <meta name="google-site-verification" content="<?php echo $arr_config_premium['google_webmasters_code'];?>" />
    <?php endif;?>

</head>
<body>
<div id="appLoadingIndicator">
    <div></div>
    <div></div>
    <div></div>
</div>
</body>
</html>
