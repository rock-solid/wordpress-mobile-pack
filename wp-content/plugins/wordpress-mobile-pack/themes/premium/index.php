<?php

	$json_config_premium = WMobilePack::wmp_set_premium_config(); 
    
    $arrConfig = null;
	if ($json_config_premium !== false) {
		$arrConfig = json_decode($json_config_premium, true);
	}
    
    // check if we have a valid domain
    if (isset($arrConfig['domain_name']) && filter_var('http://'.$arrConfig['domain_name'], FILTER_VALIDATE_URL)) {
        header("Location: http://".$arrConfig['domain_name']);
        exit();
    }
    
	// check if it is tablet 
	$is_tablet = WMobilePack::wmp_is_tablet();

	$kits_path =   $arrConfig['cdn_kits']."/app".$arrConfig['theme'].'/'.$arrConfig['kit_version'].'/';
	$app_files_path = $arrConfig['cdn_apps'].'/'.$arrConfig['shorten_url'].'/';
    
?>
<!DOCTYPE HTML>
<html manifest="" lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="apple-touch-icon-precomposed" href="" />

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
    
    <?php
        
        $cover = '';
        
        if ($is_tablet == 0 && isset($arrConfig['cover_smartphones_path'] ) && $arrConfig['cover_smartphones_path'] != '')
            $cover = $arrConfig['cover_smartphones_path'];
            
        if ($is_tablet == 1 && isset($arrConfig['cover_tablets_path'] ) && $arrConfig['cover_tablets_path'] != '')
            $cover = $arrConfig['cover_tablets_path'];
            
        // init icon & logo timestamps
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
    ?>

    <script type="text/javascript">
        var webcrumbz = {
            webApp: "<?php echo $arrConfig['webapp'];?>",
            title: "<?php echo addslashes($arrConfig['title']);?>", // to update the title tag with the same constant

            exportPath: '<?php echo $arrConfig['api_content'];?>',
			defaultPath: '<?php echo $kits_path;?>',
            appPath: '<?php echo $app_files_path;?>',
			socialApiPath: '<?php echo $arrConfig['api_social'];?>',
            
            logo: '<?php echo isset($arrConfig['logo_path']) && $arrConfig['logo_path'] != '' ? $app_files_path.$arrConfig['logo_path'] : $kits_path."resources/images/logo.png";?>',
            hasIcons: <?php echo intval(isset($arrConfig['icon_path']) && $arrConfig['icon_path'] != "");?>,
            hasStartups: <?php echo intval(isset($arrConfig['logo_path']) && $arrConfig['logo_path'] != "");?>,
            iconTimestamp: '<?php echo $icon_timestamp;?>',
            startupImageTimestamp: '<?php echo $logo_timestamp;?>',
   
            userCover: <?php echo $cover == "" ? 'false' : 'true' ;?>,
            defaultCover: "<?php echo $cover == "" ? $arrConfig['cdn_kits'].'/others/covers/'.($is_tablet ? 'tablet' : 'phone').'/pattern-'.rand(1,8).'.jpg' : $app_files_path.$cover ;?>",
            
            appUrl: '<?php echo home_url();?>',
            websiteUrl: '<?php echo home_url();?>?wmp_theme_mode=desktop',
            preview: 0,
            imageInterval : {
                minWidth: 120,
                minHeight: 120,
                maxWidth: 750,
                maxHeight: 600
            },
            
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
    
    <?php if (($arrConfig['has_phone_ads'] == 1 && $is_tablet == 0) || ($arrConfig['has_tablet_ads'] == 1 && $is_tablet == 1)):?>
    
        <!-- start Google Doubleclick for publishers -->
        <script type='text/javascript'>
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

    <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/<?php echo $is_tablet == 1 ? 'tablet' : 'phone';?>.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/fonts.css" type="text/css" />
    
    <?php
    	
		$theme_details = array(							
			'color_scheme'      => 1,
			'font_headlines'    => 1,
			'font_subtitles'    => 1,
			'font_paragraphs'   => 1
		);
	
		if (!isset($arrConfig['font_headlines'])) 
			$arrConfig['font_headlines'] = $theme_details['font_headlines'];
			
		if(!isset($arrConfig['font_subtitles'])) 
			$arrConfig['font_subtitles'] = $theme_details['font_subtitles'];
			
		if(!isset($arrConfig['font_paragraphs'])) 
			$arrConfig['font_paragraphs'] = $theme_details['font_paragraphs'];
     
        if(!isset($arrConfig['color_scheme'])) 
			$arrConfig['color_scheme'] = $theme_details['color_scheme'];  
		  
		$arrLoadedFonts[] = $theme_details['font_headlines'];  
		  
        if (!in_array($arrConfig['font_subtitles'], $arrLoadedFonts))
            $arrLoadedFonts[] = $arrConfig['font_subtitles'];
            
        if (!in_array($arrConfig['font_paragraphs'], $arrLoadedFonts))
            $arrLoadedFonts[] = $arrConfig['font_paragraphs'];
			
		
    ?>
    
    <?php foreach ($arrLoadedFonts as $font_no):?>
        <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/<?php echo $is_tablet == 1 ? 'tablet' : 'phone';?>/font-<?php echo $font_no;?>.css" type="text/css" />
    <?php endforeach; ?>

    <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/<?php echo $is_tablet == 1 ? 'tablet' : 'phone';?>/headlines-f<?php echo $arrConfig['font_headlines'];?>.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/<?php echo $is_tablet == 1 ? 'tablet' : 'phone';?>/paragraphs-f<?php echo $arrConfig['font_paragraphs'];?>.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/<?php echo $is_tablet == 1 ? 'tablet' : 'phone';?>/subtitles-f<?php echo $arrConfig['font_subtitles'];?>.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $kits_path;?>resources/css/<?php echo $is_tablet == 1 ? 'tablet' : 'phone';?>/theme-<?php echo $arrConfig['theme'] != 0 ? $arrConfig['color_scheme'] : 0;?>.css" type="text/css" />

    <script type="text/javascript" src="<?php echo $kits_path;?>js/<?php echo $is_tablet == 1 ? 'tablet' : 'phone';?>.js"></script>

   
   	<?php
        // check if google analytics id was set
        $google_analytics_id = isset($arrConfig['google_analytics_id']) ? $arrConfig['google_analytics_id'] : '';        
        if ($google_analytics_id != ''):
    ?>
    
        <script type="text/javascript">
    
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
       <script>
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

</head>
<body>
    <div id="appLoadingIndicator">
        <div></div>
        <div></div>
        <div></div>
    </div>
</body>
</html>
