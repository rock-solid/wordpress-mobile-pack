<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            
            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<?php 
    $inactive_categories = unserialize(WMobilePack::wmp_get_setting('inactive_categories'));
    $categories = get_categories();
?>
<div id="wmpack-admin">
	<div class="spacer-60"></div>
    <!-- set title -->
    <h1><?php echo WMP_PLUGIN_NAME;?></h1>
	<div class="spacer-20"></div>
	<div class="content">
        <div class="left-side">
        
            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>
            
            <!-- add content form -->
            <div class="details">
            	<div class="spacer-10"></div>
                <p>Choose what categories to be displayed in your mobile web application. Click on the rows to below to <em>show/hide</em> categories.</p>
            	<div class="spacer-20"></div>
                <div class="spacer-20"></div>
                
                <!-- start categories list -->
                <?php if (count($categories) > 0): ?>
                
                    <form name="wmp_editcategories_form" id="wmp_editcategories_form" action="" method="post">
                        
                        <div id="wmp_editcategories_warning" class="message-container warning" style="display: <?php echo count($inactive_categories) < count($categories) ? 'none' : 'block'?>;">
                            <div class="wrapper">
                                <div class="relative"><a class="close-x"></a></div>
                                <span>Since you deactivated all your categories, no content will be displayed in your mobile web app!</span> 
                            </div>
                            <div class="spacer-10"></div>
                        </div>
                
                        <ul class="categories">
                            <?php 
                                foreach ($categories as $category):
                            
                                    $status = 'active';
                                    if (in_array($category->cat_ID, $inactive_categories))
                                        $status = 'inactive';
                            ?>
                        	<li>
                            	<span class="status <?php echo $status;?>" data-category-id="<?php echo $category->cat_ID;?>"><?php echo $status;?></span>
                                <span class="title"><?php echo $category->name;?></span>
                                <span class="posts"><?php echo $category->category_count != 1 ? $category->category_count.' posts' : '1 post';?> published</span>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </form>
                    
                <?php else: ?>
                
                    <div class="message-container warning">
                        <div class="wrapper">
                            <div class="title">
                                <h2 class="underlined">No categories to display!</h2>
                            </div>
                            <span>Since you don't have any categories, no content will be displayed in your mobile web app!</span> 
                        </div>
                    </div>
                        
                <?php endif;?>
            </div>
            <div class="spacer-10"></div>
            <div class="details">
            	<h2 class="title">Coming Soon</h2>
           		<div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <div class="spacer-20"></div>
                <div class="more-content">
                 	<p class="left">Want to add more content?</p>
                 	<img src="<?php echo plugins_url()."/".WMP_DOMAIN."/admin/images/content-icons.png";?>" alt="" class="left" />
                </div>
                <?php
                    $joined_content_waitlist = false;
                     
                    $joined_waitlists = unserialize(WMobilePack::wmp_get_setting('joined_waitlists'));
                    
                    if ($joined_waitlists != '' && in_array('content', $joined_waitlists))
                        $joined_content_waitlist = true;
                ?>
                
                <div class="waitlist" id="wmp_waitlist_container">
                
                    <?php if ($joined_content_waitlist == false):?>
                        <div id="wmp_waitlist_action">
                            <a href="javascript:void(0);" id="wmp_waitlist_display_btn" class="btn blue smaller">Join Waitlist</a>
                            <div class="spacer-0"></div>
                            <p>and get notified when available</p>
                        </div>
                    
                        <form name="wmp_waitlist_form" id="wmp_waitlist_form" action="" method="post" style="display: none;">    
                            <div class="info">
                        	   <input name="wmp_waitlist_emailaddress" id="wmp_waitlist_emailaddress" type="text" placeholder="your email" class="small" value="<?php echo get_option( 'admin_email' );?>" />
                               <a href="javascript: void(0);" id="wmp_waitlist_send_btn" class="btn blue smallest">Ok</a>
                               <div class="spacer-5"></div>
                               <div class="field-message error" id="error_emailaddress_container"></div>
                        	   <div class="spacer-15"></div>
                    	   </div>
                        </form>
                    <?php endif;?>
                    
                    <div id="wmp_waitlist_added" class="added" style="display: <?php echo $joined_content_waitlist ? 'block' : 'none'?>;">
                        <div class="switcher blue">
                        	<div class="msg">ADDED TO WAITLIST</div>
                            <div class="check"></div>
                        </div>
                        <div class="spacer-15"></div>
                	</div>
                </div> 
                <div class="spacer-5"></div>
                <div class="grey-line"></div>
                <div class="spacer-20"></div> 
            </div>
        </div>
    
        <div class="right-side">
        
            <!-- add feedback form -->
            <?php include_once('sections/wmp-feedback.php'); ?>
        </div>
	</div>


</div>
    
<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            
            window.WMPJSInterface.add("UI_editcategories","WMP_EDIT_CATEGORIES",{'DOMDoc':window.document}, window);
            
            <?php if ($joined_content_waitlist == false):?>
            
                window.WMPJSInterface.add("UI_joinwaitlist",
                    "WMP_WAITLIST",
                    {
                        'DOMDoc':       window.document,
                        'container' :   window.document.getElementById('wmp_waitlist_container'),
                        'submitURL' :   '<?php echo WMP_WAITLIST_PATH;?>',
                        'listType' :    'content'
                    }, 
                    window
                );
            <?php endif;?>
        });
    }
</script>