
<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            
            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<div id="wmpack-admin">
    <div class="spacer-20"></div>
    <!-- set title -->
    <h1><?php echo WMP_PLUGIN_NAME;?></h1>
    <div class="spacer-20"></div>
    
    <div class="more">
        <div class="left-side"> 
        
            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>
            
            <div class="details">
                <div class="spacer-10"></div>
                <h1>MOBILE<br>is more than smartphones </h1>
                <div class="spacer-20"></div>
                <div class="showcase">
                    <img src="../wp-content/plugins/wordpress-mobile-pack/admin/images/more.png" />
                </div>
                <div class="spacer-20"></div>
                <p class="subtitle">Your blog can be an amazing TABLET web application</p>
                <div class="spacer-20"></div>
                <div id="" class="try-it">
                    <a href="javascript:void(0);" id="wmp_waitlist_display_btn" class="btn blue smaller">Try it Now</a>
                    <div class="spacer-5"></div>
                    <p>It's free</p>
                </div>
            </div>
            <div class="spacer-10"></div>
            <div class="ribbon relative">
                <div class="indicator"></div>
            </div> 
            <div class="details go-premium">
            	<div class="spacer-10"></div>
                <h1>MONETI$E Your Mobile <br />&amp; Tablet Web Application</h1>
                <div class="spacer-60"></div>
                <div class="features">
                	<div class="feature">
                    	<img src="../wp-content/plugins/wordpress-mobile-pack/admin/images/go-premium-social.png" />
                        <div class="spacer-5"></div>
                        <p>Increase social interactions between your readers by giving them the possibility to like, share, tweet &amp; comment directly from within your app.</p>
                    </div>
                    <div class="feature">
                    	<img src="../wp-content/plugins/wordpress-mobile-pack/admin/images/go-premium-payments.png" />
                        <div class="spacer-5"></div>
                        <p>Integrate with your favorite Ad Network &amp; Social Micropayments.</p>
                    </div>
                    <div class="feature">
                    	<img src="../wp-content/plugins/wordpress-mobile-pack/admin/images/go-premium-revenue.png" />
                        <div class="spacer-5"></div>
                        <p>All the money goes directly in your pockets; there are no shared revenue constraints.</p>
                    </div>
                </div>
                <div class="spacer-60"></div>
                <div id="" class="try-it">
                    <a href="javascript:void(0);" id="wmp_waitlist_display_btn" class="btn orange smaller">Go Premium</a>
                </div>
            </div>
        </div>
        <div class="right-side"> 
            <!-- add news and updates -->
            <?php include_once('sections/wmp-news.php'); ?>

            <!-- add feedback form -->
            <?php include_once('sections/wmp-feedback.php'); ?>
        </div>
    </div>
</div>