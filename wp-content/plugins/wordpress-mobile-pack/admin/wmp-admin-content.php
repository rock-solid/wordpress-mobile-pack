<?php 
    $inactive_categories = unserialize(WMobilePack::wmp_get_setting('inactive_categories'));
    $categories = get_categories();
?>
<div id="wmpack-admin">
	<div class="spacer-20"></div>
    <!-- set title -->
    <h1>Content</h1>
	<div class="spacer-20"></div>
	<div class="content">
        <div class="left-side">
            <!-- add nav menu -->
            <nav class="menu">
                <ul>
                    <li><a href="#">Look & Feel</a></li>
                    <li class="selected"><a href="#">Content</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="#">Upgrade</a></li>
                </ul>
            
            </nav>
          <div class="spacer-0"></div>
            <!-- add content form -->
            <div class="details">
            	<div class="spacer-10"></div>
            	<p>Lorem ipsum dolor sit amet, nec accusamus assentior in, per ea probo percipit ullamcorper. An mel animal menandri vituperata. Vis an solet ocurreret, sit laudem semper perfecto ex, vix an nibh tacimates. Ne usu duis ignota oblique.</p>
            	<div class="spacer-20"></div>
                <div class="spacer-20"></div>
                
                <!-- start categories list -->
                <?php if (count($categories) > 0):?>
                
                    <form name="editcategories_form" id="editcategories_form" action="<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>publishers/ajax/recoveraccount" method="post">
                    
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
                
                    <p>Since you don't have any categories, no content will be displayed in your mobile web app!</p>
                    
                <?php endif;?>
            </div>
            <div class="spacer-10"></div>
            <div class="details">
            	<h2 class="title">Coming Soon</h2>
           		<div class="spacer_15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <div class="spacer-20"></div>
                <div class="more-content">
                 	<p class="left">Add more content from </p>
                 	<img src="<?php echo plugins_url()."/".WMP_DOMAIN."/admin/images/content-icons.png";?>" alt="" class="left" />
                </div>
                <div class="waitlist">
                	 <a class="btn blue smaller" href="#">Join Waitlist</a>
                     <div class="spacer-0"></div>
                     <p>and get notified when available</p>
                </div> 
                <div class="spacer-5"></div>
                <div class="grey-line"></div>
                <div class="spacer-20"></div>
                <div class="spacer-20"></div> 
            </div>
        </div>
    
        <div class="right-side">
            <!-- add news and updates -->
            <div class="updates">
                <h2>News & Updates</h2> 
                <div class="spacer-20"></div>
                <div class="details">
                    <!-- start news and updates -->
                    <p>Lorem ipsum dolor sit amet, nec accusamus assentior in, per ea probo percipit ullamcorper. An mel animal menandri vituperata. Ne usu duis ignota oblique. <a href="#" target="_blank" title="read more">read more</a></p> 
                    <div class="spacer-20"></div>
                    <div class="grey-dotted-line"></div>
                    <div class="spacer-20"></div>
                    <p>Lorem ipsum dolor sit amet, nec accusamus assentior in, per ea probo percipit ullamcorper. An mel animal menandri vituperata. Ne usu duis ignota oblique. <a href="#" target="_blank" title="read more">read more</a></p> 
                        
                </div>
            </div>
            <div class="spacer-5"></div>
            <!-- add appticles social -->
            <div class="appticles-updates">
                <!-- add content -->
            	<div><p>Appticles Updates</p></div>
                <div class="social">
                	<a href="#" class="facebook"></a>
                    <a href="#" class="twitter"></a>
                    <a href="#" class="google-plus"></a>
                </div>
            </div>
            <div class="spacer-15"></div>

			<!-- add newsletter box -->
            <div class="form-box">
                <h2>Join our newsletter</h2>
                <div class="spacer-10"></div>
                <p>Receive monthly freebies, Special Offers &amp; Access to Exclusive Subscriber Content.</p>
                <div class="spacer-0"></div>
<form id="newsletter" name="" action="" method="post">
                    <input type="hidden" name="" id="" placeholder="the-email address of the admin" class="small" />
                    <a class="btn green smaller" href="#">Subscribe</a>
                </form>
            </div>
		    <div class="spacer-15"></div>
            
            <!-- add feedback form -->
            <div class="form-box">
                <h2>Give us your feedback</h2>
                <div class="spacer-10"></div>
                <form id="" name="" action="" method="post">
                    <input type="hidden" name="" id="" placeholder="the-email address of the admin" class="small" />
                    <textarea name="feedback_message" id="feedback_message" placeholder="Your message" class="small"></textarea>
                    <div class="spacer-5"></div>
                    <a class="btn green smaller" href="#">Send</a>
                </form>
            </div>
        </div>
	</div>


</div>
    
<script type="text/javascript">
    if (window.JSInterface && window.JSInterface != null){
        jQuery(document).ready(function(){
            
            JSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            JSInterface.init();
    
            window.JSInterface.add("UI_editcategories","EDIT_CATEGORIES",{'DOMDoc':window.document}, window);
            // window.JSInterface.UI_editcategories.init();
        });
    }
</script>