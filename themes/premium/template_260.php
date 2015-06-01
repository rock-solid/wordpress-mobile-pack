<?php

// Check if we have to load a custom theme
if ( ($is_tablet == 0 && $arrConfig['phone']['theme'] != 0) || ($is_tablet == 1 && $arrConfig['tablet']['theme'] != 0)) {
    $kits_path = $cdn_kits."/app".($is_tablet == 0 ? $arrConfig['phone']['theme'] : $arrConfig['tablet']['theme']).'/'.$arrConfig['kit_version'].'/';    
} else {    
    $kits_path = $cdn_apps."/".$arrConfig['shorten_url'].'/';
}

 // ----------------------------------------- //
 
// Process icons & startup screens timestamps
$icon_timestamp = '';
if (isset($arrConfig['icon_path'])) {
     $str = $arrConfig['icon_path'];
     $icon_timestamp = '_'.substr($str, strpos($str, '_') + 1 , strpos($str, '.') - strpos($str, '_') - 1);
}

$logo_timestamp = '';
if (isset($arrConfig['logo_path'])) {
     $str = $arrConfig['logo_path'];
     $logo_timestamp = '_'.substr($str, strpos($str, '_') + 1 , strpos($str, '.') - strpos($str, '_') - 1);
}

// ----------------------------------------- //

// Set cover settings
$cover = '';
    
if ($is_tablet == 0 && isset($arrConfig['phone']['cover']) && $arrConfig['phone']['cover'] != '')
    $cover = $arrConfig['phone']['cover'];
    
if ($is_tablet == 1 && isset($arrConfig['tablet']['cover']) && $arrConfig['tablet']['cover'] != '')
    $cover = $arrConfig['tablet']['cover'];
    
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
    
    <?php if (isset($arrConfig['icon_path'])): // icon path for Firefox ?>
        <link rel="shortcut icon" href="<?php echo $cdn_apps."/".$arrConfig['shorten_url'].'/'.$arrConfig['icon_path'];?>"/>
    <?php endif;?>
    
    <title><?php echo $arrConfig['title'];?></title>
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
        var webcrumbz = {
            webApp: "<?php echo $arrConfig['webapp'];?>",
            title: "<?php echo addslashes($arrConfig['title']);?>", // to update the title tag with the same constant

            exportPath: '<?php echo $is_secure ? $arrConfig['api_content_https'] : $arrConfig['api_content'];?>',
			socialApiPath: '<?php echo $is_secure ? $arrConfig['api_social_https'] : $arrConfig['api_social'];?>',
			
			<?php if (isset($arrConfig['api_content_external'])):?>
				exportPathExternal: '<?php echo $arrConfig['api_content_external'];?>',
			<?php endif;?>
			
			defaultPath: '<?php echo $kits_path;?>',
            appPath: '<?php echo $cdn_apps."/".$arrConfig['shorten_url'];?>',
            
            logo: '<?php echo isset($arrConfig['logo_path']) && $arrConfig['logo_path'] != '' ? $cdn_apps."/".$arrConfig['shorten_url'].'/'.$arrConfig['logo_path'] : $cdn_kits."/app1/".$arrConfig['kit_version']."/resources/images/logo.png";?>',
            hasIcons: <?php echo intval(isset($arrConfig['icon_path']) && $arrConfig['icon_path'] != "");?>,
            hasStartups: <?php echo intval(isset($arrConfig['logo_path']) && $arrConfig['logo_path'] != "");?>,
            iconTimestamp: '<?php echo $icon_timestamp;?>',
            startupImageTimestamp: '<?php echo $logo_timestamp;?>',
	    
            userCover: <?php echo $cover == "" ? 'false' : 'true' ;?>,
            defaultCover: "<?php echo $cover == "" ? $cdn_kits.'/others/covers/'.($is_tablet ? 'tablet' : 'phone').'/pattern-'.rand(1,8).'.jpg' : $cdn_apps."/".$arrConfig['shorten_url'].'/'.$cover;?>",
            
            appUrl: '<?php echo home_url();?>',
            websiteUrl: '<?php echo home_url();?>?wmp_theme_mode=desktop',
			canonicalUrl: '<?php echo home_url();?>',
			
            preview: 0,
            language: '<?php echo isset($arrConfig['language']) && $arrConfig['language'] != '' ? $arrConfig['language'] : 'en';?>',
			
			// enable the social modules
			<?php if (isset($arrConfig['api_content_external'])):?>
				hasFacebook: false,
				hasTwitter: false,
			<?php else:?>
				hasFacebook: true,
				hasTwitter: true,
			<?php endif;?>
            
            <?php if ($arrConfig['has_phone_ads'] == 1 || $arrConfig['has_tablet_ads'] == 1):?>
                googleAds:{
                    adsInterval: 30,      // seconds between ads
                    
                    <?php if ($arrConfig['has_phone_ads'] == 1):?>
                        phone: {
                            networkCode: <?php echo $arrConfig['phone_network_code'];?>,
                            adUnitCode: "<?php echo $arrConfig['phone_unit_name'];?>",
                            sizes: <?php echo json_encode($arrConfig['phone_ad_sizes']);?>
                        },
                    <?php else: ?>
                        phone : null,
                    <?php endif;?>
            
                    <?php if ($arrConfig['has_tablet_ads'] == 1):?>
                        tablet: {
                            networkCode: <?php echo $arrConfig['tablet_network_code'];?>,
                            adUnitCode: "<?php echo $arrConfig['tablet_unit_name'];?>",
                            sizes: <?php echo json_encode($arrConfig['tablet_ad_sizes']);?>
                        },
                    <?php else: ?>
                        tablet : null,
                    <?php endif;?>
                },
            <?php endif;?>
        };
    </script>
	
	<?php if (isset($arrConfig['load_canonical_script']) && $arrConfig['load_canonical_script'] == 1):?>
	
		<script src="<?php echo $is_secure ? 'https' : 'http' ?>://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="<?php echo $cdn_kits;?>/others/landing-page-v2/scripts/<?php echo $supported_gzip ? 'canonical-urls-js.gz' : 'canonical-urls.js' ;?>" type="text/javascript"></script>
		
	<?php endif;?>
	
    <?php if (($arrConfig['has_phone_ads'] == 1 && $is_tablet == 0) || ($arrConfig['has_tablet_ads'] == 1 && $is_tablet == 1)):?>
    
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
        $device = $is_tablet == 0 ? 'phone' : 'tablet';
        
        $theme_details = array(
            'theme'             => $arrConfig[$device]['theme'],					
			'color_scheme'      => isset($arrConfig[$device]['color_scheme']) ? $arrConfig[$device]['color_scheme'] : 1,
			'font_headlines'    => isset($arrConfig[$device]['font_headlines']) ? $arrConfig[$device]['font_headlines'] : '',
			'font_subtitles'    => isset($arrConfig[$device]['font_subtitles']) ? $arrConfig[$device]['font_subtitles'] : '',
			'font_paragraphs'   => isset($arrConfig[$device]['font_paragraphs']) ? $arrConfig[$device]['font_paragraphs'] : '',
            'theme_timestamp'   => isset($arrConfig[$device]['theme_timestamp']) ? $arrConfig[$device]['theme_timestamp'] : '',
		);	 
		  
        $arrLoadedFonts = array();
        
        if (is_numeric($theme_details['font_headlines']))
            $arrLoadedFonts[] = $theme_details['font_headlines'];  
		  
        if (!in_array($theme_details['font_subtitles'], $arrLoadedFonts) && is_numeric($theme_details['font_subtitles']))
            $arrLoadedFonts[] = $theme_details['font_subtitles'];
            
        if (!in_array($theme_details['font_paragraphs'], $arrLoadedFonts) && is_numeric($theme_details['font_paragraphs']))
            $arrLoadedFonts[] = $theme_details['font_paragraphs'];
    
        // Check if we have custom fonts, otherwise load at least one default font
        if (!isset($arrConfig[$device]['custom_fonts']) && count($arrLoadedFonts) == 0) {
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
        
        if (isset($arrConfig[$device]['custom_fonts'])) {
            
            $arrFontsNo = explode(',', $arrConfig[$device]['custom_fonts']);
            
            foreach ($arrFontsNo as $font_no){
                if (is_numeric($font_no)){
                    $arrCustomFonts[] = $font_no;
                }
            }
        }
    ?>
    
    <?php foreach ($arrCustomFonts as $font_no):?>
        <link rel="stylesheet" href="<?php echo $cdn_apps."/".$arrConfig['shorten_url'];?>/resources/css/<?php echo $supported_gzip ? 'font-'.$font_no.'-css.gz' : 'font-'.$font_no.'.css' ;?>" type="text/css" />
    <?php endforeach; ?>
    
    <?php if ($theme_details['theme'] == 0): // load custom theme ?>
        <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/<?php echo $device.'-'.$theme_details['theme_timestamp'].($supported_gzip ? '-css.gz' : '.css');?>" type="text/css" />
    <?php elseif ($theme_details['theme_timestamp'] != ''): // check if we have a generated css ?>
	    <link rel="stylesheet" href="<?php echo $cdn_apps."/".$arrConfig['shorten_url'];?>/resources/css/<?php echo $device.'-'.$theme_details['theme_timestamp'].($supported_gzip ? '-css.gz' : '.css');?>" type="text/css" />
    <?php else: ?>
	   <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/<?php echo $device.'/colors-'.$theme_details['color_scheme'].'-font-'.$theme_details['font_headlines'].($supported_gzip ? '-css.gz' : '.css');?>" type="text/css" />
    <?php endif;?>
    
    <script type="text/javascript" src="<?php echo $kits_path;?>js/<?php echo $device.($supported_gzip ? '-js.gz' : '.js');?>"></script>
    
    <?php
        // check if google analytics id was set
        $google_analytics_id = isset($arrConfig['google_analytics_id']) ? $arrConfig['google_analytics_id'] : '';        
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
        $google_internal_id = isset($arrConfig['google_internal_id']) ? $arrConfig['google_internal_id'] : '';        
        if ($google_internal_id != ''):
    ?>
    
       <!-- add google universal analytics -->
       <script pagespeed_no_defer="">
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      
        ga('create', 'UA-45917766-1');
      
        var dimensionValue = '<?php echo $arrConfig['google_internal_id'];?>';
        ga('set', 'dimension1', dimensionValue);
      
        ga('send', 'pageview');
      
      </script>
    <?php endif;?>
	
	<?php if (isset($arrConfig['google_webmasters_code']) && $arrConfig['google_webmasters_code'] != ""):?>
		<meta name="google-site-verification" content="<?php echo $arrConfig['google_webmasters_code'];?>" />
	<?php endif;?>
	
	<?php if (isset($arrConfig['load_chrome43_patch']) && $arrConfig['load_chrome43_patch'] == 1):?>
	
		<script type="text/javascript" pagespeed_no_defer="">
			// Override for Chrome 43 bug (swipe not working)
			Ext.define('Override.util.PaintMonitor', {
				override : 'Ext.util.PaintMonitor',
			
				constructor : function(config) {
					return new Ext.util.paintmonitor.CssAnimation(config);
				}
			});
			
			Ext.define('Override.util.SizeMonitor', {
				override : 'Ext.util.SizeMonitor',
			
				constructor : function(config) {
					var namespace = Ext.util.sizemonitor;
			
					if (Ext.browser.is.Firefox) {
						return new namespace.OverflowChange(config);
					} else if (Ext.browser.is.WebKit || Ext.browser.is.IE11) {
						return new namespace.Scroll(config);
					} else {
						return new namespace.Default(config);
					}
				}
			});
		</script>
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