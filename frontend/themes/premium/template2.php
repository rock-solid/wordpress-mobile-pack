<?php

// Check if the browser supports the loading of gzipped files
$supported_gzip = false;
if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
    $supported_gzip = true;

$export_path = plugins_url()."/".WMP_DOMAIN."/export/";

?>
<!DOCTYPE HTML>
<html manifest="" lang="<?php echo str_replace('_', '-', $app_settings['locale']);?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo isset($app_settings['icon']) && $app_settings['icon'] != '' ? $app_settings['icon'] : '';?>" />
    <link rel="manifest" href="<?php echo plugins_url()."/".WMP_DOMAIN."/export/content.php?content=androidmanifest&premium=1";?>" />

    <?php if ($app_settings['icon'] != ''): // icon path for Firefox ?>
        <link rel="shortcut icon" href="<?php echo $app_settings['icon'];?>"/>
    <?php endif;?>

    <title><?php echo $app_settings['title'];?></title>

    <script type="text/javascript" pagespeed_no_defer="">
        var appticles = {

            // Define export paths
			export: {
				categories: {
					// Read categories list
					list: '<?php echo $export_path;?>content.php?content=exportcategories'
				},
				posts: {
					// Read posts from a category
					list: '<?php echo $export_path;?>content.php?content=exportarticles',

					// Read a post's details
					read: '<?php echo $export_path;?>content.php?content=exportarticle'
				},
				pages: {
					// Read pages list
					list: '<?php echo $export_path;?>content.php?content=exportpages',

					// Read a page's details
					read: '<?php echo $export_path;?>content.php?content=exportpage'
				},
				comments: {
					// Read comments for a post
					list: '<?php echo $export_path;?>content.php?content=exportcomments',

					// Submit comment for a post
					create: '<?php echo $export_path;?>content.php?content=savecomment'
				}
			},

			commentsToken: "<?php echo $app_settings['comments_token'];?>",
			articlesPerCard: <?php echo is_numeric($app_settings['posts_per_page']) ? $app_settings['posts_per_page'] : '"auto"' ;?>,

            <?php if (isset($app_settings['website_url']) && $app_settings['website_url'] != ''):?>
                websiteUrl: '<?php echo $app_settings['website_url']; echo parse_url($app_settings['website_url'], PHP_URL_QUERY) ? '&' : '?'; echo WMobilePack_Cookie::$prefix; ?>theme_mode=desktop',
            <?php endif;?>

            logo: '<?php echo $app_settings['logo'];?>',
            icon: '<?php echo $app_settings['icon'];?>',
            defaultCover: "<?php echo $app_settings['cover'];?>",
            userCover: <?php echo $app_settings['user_cover'] ? 'true' : 'false' ;?>,

            hasFacebook: <?php echo $app_settings['enable_facebook'] ? 'true' : 'false';?>,
            hasTwitter: <?php echo $app_settings['enable_twitter'] ? 'true' : 'false';?>,
			hasGoogle: <?php echo $app_settings['enable_google'] ? 'true' : 'false';?>,

            <?php if ($app_settings['has_phone_ads'] == 1 || $app_settings['has_tablet_ads'] == 1):?>
                googleAds:{
                    adsInterval: <?php echo isset($app_settings[$app_settings['device'].'_ad_interval']) && $app_settings[$app_settings['device'].'_ad_interval'] != ''  ? $app_settings[$app_settings['device'].'_ad_interval'] : 30;?>,      // seconds between ads

                    <?php if ($app_settings['has_phone_ads'] == 1):?>
                    phone: {
                        networkCode: <?php echo $app_settings['phone_network_code'];?>,
                        adUnitCode: "<?php echo $app_settings['phone_unit_name'];?>",
                        sizes: <?php echo json_encode($app_settings['phone_ad_sizes']);?>
                    },
                    <?php else: ?>
                    phone : null,
                    <?php endif;?>

                    <?php if ($app_settings['has_tablet_ads'] == 1):?>
                    tablet: {
                        networkCode: <?php echo $app_settings['tablet_network_code'];?>,
                        adUnitCode: "<?php echo $app_settings['tablet_unit_name'];?>",
                        sizes: <?php echo json_encode($app_settings['tablet_ad_sizes']);?>
                    },
                    <?php else: ?>
                    tablet : null,
                    <?php endif;?>
                },
            <?php endif;?>
        };
    </script>

    <?php

        $arrLoadedFonts = array();

        if (is_numeric($app_settings['font_headlines']))
            $arrLoadedFonts[] = $app_settings['font_headlines'];

        if (!in_array($app_settings['font_subtitles'], $arrLoadedFonts) && is_numeric($app_settings['font_subtitles']))
            $arrLoadedFonts[] = $app_settings['font_subtitles'];

        if (!in_array($app_settings['font_paragraphs'], $arrLoadedFonts) && is_numeric($app_settings['font_paragraphs']))
            $arrLoadedFonts[] = $app_settings['font_paragraphs'];

        // Check if we have custom fonts, otherwise load at least one default font
        if ($app_settings['custom_fonts'] != '' && count($arrLoadedFonts) == 0) {
            $arrLoadedFonts[] = 1;
            $app_settings['font_headlines'] = 1;
        }
    ?>

    <?php foreach ($arrLoadedFonts as $font_no):?>
        <link rel="stylesheet" href="<?php echo $app_settings['cdn_kits'];?>/resources/fonts/<?php echo $supported_gzip ? 'font-'.$font_no.'-css.gz' : 'font-'.$font_no.'.css' ;?>" type="text/css" />
    <?php endforeach; ?>

    <?php
        // load custom fonts
        $arrCustomFonts = array();

        if (isset($app_settings['custom_fonts'])) {

            $arrFontsNo = explode(',', $app_settings['custom_fonts']);

            foreach ($arrFontsNo as $font_no){
                if (is_numeric($font_no)){
                    $arrCustomFonts[] = $font_no;
                }
            }
        }
    ?>

    <?php foreach ($arrCustomFonts as $font_no):?>
        <link rel="stylesheet" href="<?php echo $app_settings['cdn_apps']."/".$app_settings['shorten_url'];?>/resources/css/<?php echo $supported_gzip ? 'font-'.$font_no.'-css.gz' : 'font-'.$font_no.'.css' ;?>" type="text/css" />
    <?php endforeach; ?>

    <?php if ($app_settings['theme'] == 0): // load custom theme ?>
        <link rel="stylesheet" href="<?php echo $app_settings['kits_path'];?>resources/css/<?php echo $app_settings['device'].'-'.$app_settings['theme_timestamp'].($supported_gzip ? '-css.gz' : '.css');?>" type="text/css" />
    <?php elseif ($app_settings['theme_timestamp'] != ''): // check if we have a generated css ?>
        <link rel="stylesheet" href="<?php echo $app_settings['cdn_apps']."/".$app_settings['shorten_url'];?>/resources/css/<?php echo $app_settings['device'].'-'.$app_settings['theme_timestamp'].($supported_gzip ? '-css.gz' : '.css');?>" type="text/css" />
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo $app_settings['kits_path'];?>resources/css/<?php echo $app_settings['device'].'/colors-'.$app_settings['color_scheme'].'-font-'.$app_settings['font_headlines'].($supported_gzip ? '-css.gz' : '.css');?>" type="text/css" />
    <?php endif;?>

    <script type="text/javascript" src="<?php echo $app_settings['cdn_kits'];?>/others/<?php echo $app_settings['kit_type'] == 'classic' ? 'locales' : 'locales2';?>/<?php echo $app_settings['locale'].($supported_gzip ? '-js.gz' : '.js');?>"></script>
    <script type="text/javascript" src="<?php echo $app_settings['kits_path'];?>js/<?php echo $app_settings['device'].($supported_gzip ? '-js.gz' : '.js');?>"></script>

    <?php
        // check if google analytics id was set
        $google_analytics_id = isset($app_settings['google_analytics_id']) ? $app_settings['google_analytics_id'] : '';
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
        $google_internal_id = isset($app_settings['google_internal_id']) ? $app_settings['google_internal_id'] : '';
        if ($google_internal_id != ''):
    ?>

        <!-- add google universal analytics -->
        <script pagespeed_no_defer="">
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-45917766-1');

            var dimensionValue = '<?php echo $google_internal_id;?>';
            ga('set', 'dimension1', dimensionValue);

            ga('send', 'pageview');

        </script>
    <?php endif;?>

    <?php if (isset($app_settings['google_webmasters_code']) && $app_settings['google_webmasters_code'] != ""):?>
        <meta name="google-site-verification" content="<?php echo $app_settings['google_webmasters_code'];?>" />
    <?php endif;?>

</head>
<body>

	<?php
		// check if google tag manager id was set
		$google_tag_manager_id = isset($app_settings['google_tag_manager_id']) ? $app_settings['google_tag_manager_id'] : '';
		if ($google_tag_manager_id != ''):
	?>
		<!-- Google Tag Manager -->
		<noscript><iframe src='//www.googletagmanager.com/ns.html?id=<?php echo $google_tag_manager_id;?>'
							height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','<?php echo $google_tag_manager_id;?>');</script>
		<!-- End Google Tag Manager -->
	<?php endif;?>

	<?php if ($app_settings['theme'] == 6):?>
		<div data-ng-app="invisionApp" id="appLoadingIndicator">
			<ion-nav-view></ion-nav-view>
		</div>
	<?php endif;?>

	<?php if ($app_settings['theme'] == 7):?>
		<div data-ng-app="Theme7" ng-controller="ApplicationController as appCtrl">
		  <ion-nav-view></ion-nav-view>
		</div>
	<?php endif;?>
</body>
</html>
