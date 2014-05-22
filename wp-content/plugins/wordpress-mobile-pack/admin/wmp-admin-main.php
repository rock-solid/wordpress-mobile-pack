<div id="wmpack-admin">
    <div class="spacer-20"></div>
    <!-- set title -->
    <h1><?php echo WMP_PLUGIN_NAME;?></h1>
    <div class="spacer-20"></div>
    <div class="whats-new">
        <div class="left-side"> 
        
            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>
            
            <!-- add content form -->
            <div class="details">
                <div class="spacer-10"></div>
                <p>Lorem ipsum dolor sit amet, nec accusamus assentior in, per ea probo percipit ullamcorper. An mel animal menandri vituperata. Vis an solet ocurreret, sit laudem semper perfecto ex, vix an nibh tacimates. Ne usu duis ignota oblique.</p>
                <div class="spacer-20"></div>
                <div class="showcase">
                	<img src="<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/whats-new.png" />
                </div>
                <div class="spacer-20"></div>
                <div class="spacer-20"></div>
            </div>
            <div class="spacer-10"></div>
            <div class="details features">
                <h2 class="title">What you get</h2>
                <div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <div class="spacer-20"></div>
                <div class="feature left">
                	<img src="<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/html5.png" />
                    <div class="text">
                    	<span class="title">Cross-platform mobile web apps</span>
                        <span>All it takes for a mobile web application to run is a modern mobile browser (HTML5 compatible), thus allowing your readers to instantly have access to your content, without needing to go through an app store, download & install the app. </span>
                    </div>
                </div>
                <div class="spacer-10"></div>
                <div class="feature right">
                	<img src="<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/responsive.png" />
                    <div class="text">
                    	<span class="title">Responsive UI</span>
                        <span>All mobile web applications are sensitive to orientation changes: landscape, portrait. In other words, the look and feel of the mobile web app seamlessly morphs into the screen size of your reader's device.</span>
                    </div>
                </div>
                <div class="spacer-10"></div>
                <div class="feature left">
                	<img src="<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/customize.png" />
                    <div class="text">
                    	<span class="title">Customize appearance</span>
                        <span>Once you've selected your favorite theme you can start customizing the colors & fonts, adding your logo and graphic elements that can relate to your brand identity.</span>
                    </div>
                </div>
                <div class="spacer-10"></div>
                <div class="feature right">
                	<img src="<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/theming.png" />
                    <div class="text">
                    	<span class="title">Theming</span>
                        <span>From within the dashboard you have access to dozens of themes you can choose for your mobile web application. Give a holistic and integrated look & feel to your mobile web application that allows readers to easily swipe through your content.</span>
                    </div>
                </div>
                <div class="spacer-10"></div>
                <div class="feature left">
                	<img src="<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/custom-domain.png" />
                    <div class="text">
                    	<span class="title">Custom domain name</span>
                        <span>Have your mobile web application on top of your existing online presence: http://app.your-domain-name.com. All it takes are some simple steps to configure it and your readers will be able to benefit from your companion mobile web app in no time.</span>
                    </div>
                </div>
                <div class="spacer-10"></div>
                <div class="feature right">
                	<img src="<?php echo plugins_url()."/".WMP_DOMAIN;?>/admin/images/promoting-script.png" />
                    <div class="text">
                    	<span class="title">Promoting script</span>
                        <span>Take advantage of the openness of the web and grow your mobile audience from day one. Using the promoting script that goes in the &lt;header&gt; of your website, readers that reach your website from a mobile device are invited to check out the mobile web application you got in place for them. That's instant distribution!</span>
                    </div>
                </div>
                <div class="spacer-10"></div>
            </div>
        </div>
        <div class="right-side"> 
            <!-- add news and updates -->
            <?php include_once('sections/wmp-news.php'); ?>
            <div class="spacer-15"></div>

			<!-- add newsletter box -->
            <?php include_once('sections/wmp-newsletter.php'); ?>
            <div class="spacer-15"></div>
            
            <!-- add feedback form -->
            <?php include_once('sections/wmp-feedback.php'); ?>
        </div>
    </div>
</div>