<script type="text/javascript">
    if (window.JSInterface && window.JSInterface != null){
        jQuery(document).ready(function(){
            
            JSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            JSInterface.init();
        });
    }
</script>
<?php 
    $inactive_categories = unserialize(WMobilePack::wmp_get_setting('inactive_categories'));
    $categories = get_categories();
?>
<div id="wmpack-admin">
	<div class="spacer-20"></div>
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
            	<p>You can choose what categories are displayed in your mobile web application from the section below. Click on the rows to deactivate/activate categories.</p>
            	<div class="spacer-20"></div>
                <div class="spacer-20"></div>
                
                <!-- start categories list -->
                <?php if (count($categories) > 0): ?>
                
                    <form name="editcategories_form" id="editcategories_form" action="" method="post">
                        
                        <div id="editcategories_warning" class="message-container warning" style="display: <?php echo count($inactive_categories) < count($categories) ? 'none' : 'block'?>;">
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
                 	<p class="left">Add more content from </p>
                 	<img src="<?php echo plugins_url()."/".WMP_DOMAIN."/admin/images/content-icons.png";?>" alt="" class="left" />
                </div>
                <div class="waitlist">
                    <!--<a class="btn blue smaller" href="#">Join Waitlist</a>
                    <div class="spacer-0"></div>
                    <p>and get notified when available</p>-->
                    <!--<div class="info">
                    	<input type="text" placeholder="your email" class="smaller" /> <a href="#" class="btn blue smallest">Ok</a>
                    	<div class="spacer-15"></div>
                	</div>-->
                    <div class="added">
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
    
<script type="text/javascript">
    if (window.JSInterface && window.JSInterface != null){
        jQuery(document).ready(function(){
            
            window.JSInterface.add("UI_editcategories","EDIT_CATEGORIES",{'DOMDoc':window.document}, window);
        });
    }
</script>