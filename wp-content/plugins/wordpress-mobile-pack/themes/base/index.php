<!DOCTYPE HTML>
<html manifest="" lang="en-US">
<head>
    <meta charset="UTF-8" />
    <title><?php echo get_bloginfo("name");?></title>
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
            margin: 0 3px;
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
        // check if logo exists
        $logo_path = WMobilePack::wmp_get_setting('logo');
        
        if (!file_exists(WMP_FILES_UPLOADS_DIR.$logo_path))
            $logo_path = '';    
        else
            $logo_path = WMP_FILES_UPLOADS_URL.$logo_path;
            
        // check if icon exists
        $icon_path = WMobilePack::wmp_get_setting('icon');
        
        if (!file_exists(WMP_FILES_UPLOADS_DIR.$icon_path))
            $icon_path = ''; 
        else
            $icon_path = WMP_FILES_UPLOADS_URL.$icon_path;   
    ?>
                        
    <script type="text/javascript">
		var appticles = {
			exportPath: "<?php echo plugins_url()."/".WMP_DOMAIN."/export/";?>",
			creditsPath: "<?php echo plugins_url()."/".WMP_DOMAIN."/themes/".WMobilePack::wmp_app_theme()."/includes/credits.json";?>",
			logo: "<?php echo $logo_path;?>",
			icon: "<?php echo $icon_path;?>",
			websiteUrl: '<?php echo get_site_url();?>?wmp_theme_mode=desktop',
			commentsToken: "<?php echo WMobilePack::wmp_set_token();?>"
		};
	</script>

    <!-- The line below must be kept intact for Sencha Command to build your application -->
    <script id="microloader" type="text/javascript" src=".sencha/app/microloader/development.js"></script>
    
    <?php
        // check if google analytics id was set
        $google_analytics_id = WMobilePack::wmp_get_setting('google_analytics_id');
        
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
    
</head>
<body>
    <div id="appLoadingIndicator">
        <div></div>
        <div></div>
        <div></div>
    </div>
</body>
</html>
