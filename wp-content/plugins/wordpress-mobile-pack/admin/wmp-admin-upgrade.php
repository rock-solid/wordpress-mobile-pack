
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
                
                <h1>MOBILE</h1>
                <div class="spacer-10"></div>
                <h1>is more than smartphones </h1>
                <div class="spacer-20"></div>
                <div class="showcase">
                    <img src="../wp-content/plugins/wordpress-mobile-pack/admin/images/more.png" />
                </div>
                <div class="spacer-20"></div>
                <p></p>
            </div>
            <div class="spacer-10"></div>
            <div class="details features">
            	<div class="spacer-20"></div>
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