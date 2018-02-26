<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){

            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<?php

	$categories = get_categories();

	$order_categories = WMobilePack_Options::get_setting('ordered_categories');

    // Depending on the language settings, not all categories might be visible at the same time
    $setting_inactive_categories = WMobilePack_Options::get_setting('inactive_categories');
	$inactive_categories = array();
	
	// Compose inactive pages array with only the visible pages
	foreach ($categories as $category){
		if (in_array($category->cat_ID, $setting_inactive_categories))
			$inactive_categories[] = $category->cat_ID;
	}

	// ------------------------------------ //

	// Depending on the language settings, not all pages might be visible at the same time
	$setting_inactive_pages = WMobilePack_Options::get_setting('inactive_pages');

	$inactive_pages = array();

    $no_root_pages = 0;
    $inactive_root_pages = 0;

	// Compose inactive pages array with only the visible pages
	foreach ($all_pages as $key => $page) {

        if (intval($page->post_parent) == 0) {
            $no_root_pages++;
        }

        if (in_array($page->ID, $setting_inactive_pages)){

            $inactive_pages[] = $page->ID;

            if (intval($page->post_parent) == 0) {
                $inactive_root_pages++;
            }
        }
	}
?>
<div id="wmpack-admin">
	<div class="spacer-60"></div>
    <!-- set title -->
    <h1><?php echo WMP_PLUGIN_NAME.' '.WMP_VERSION;?></h1>
	<div class="spacer-20"></div>
	<div class="content">
        <div class="left-side">
        
            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php');?>
            <div class="spacer-0"></div>
            
            <!-- add content form -->
            <div class="details">

                <?php if (WMobilePack::is_active_plugin('Polylang')):?>
                    <div class="message-container warning">
                        <div class="wrapper">
                            <span>When using Polylang, please make sure to select "<strong>Show all languages</strong>" when ordering categories. Inconsistent ordering will result otherwise.</span>
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                <?php endif;?>

            	<div class="spacer-10"></div>
                <h2 class="title">Categories</h2>
           		<div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <p>Choose what categories to be displayed in your mobile web application. Click on the rows to below to show/hide categories and order them by dragging the corresponding row on the desired position.</p>
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
                
                        <ul class="categories" id="categories">
							<?php 
                                // list with displayed categories
                                $arrOrderedCategories = array();
                                
                            	if (is_array($order_categories) && !empty($order_categories)){
                            	   
                                    // add categories in the array in their order 
                                    foreach ($order_categories as $category_id){
                                        
                                        foreach ($categories as $category){
                                            if ($category->cat_ID == $category_id){
                                                $arrOrderedCategories[] = clone $category;
                                                break;
                                            }
                                        }
                                    }    
                                    
                                    // copy categories that don't have an order yet (they were added after the categories were sorted)
                                    foreach($categories as $category){
                                        
                                        $added = false;
                                        
                                        foreach ($arrOrderedCategories as $key => $ordered_category){
                                            if ($category->cat_ID == $ordered_category->cat_ID){
                                                $added = true;
                                                break;
                                            }
                                        }
                                        
                                        if (!$added){
                                            $arrOrderedCategories[] = clone $category;
                                        }
                                    }
                                    
                            	} else
                            		$arrOrderedCategories = $categories;

                                $categories_details = WMobilePack_Options::get_setting('categories_details');

                                foreach ($arrOrderedCategories as $key => $category):
                            
                                    $status = 'active';
                                    if (in_array($category->cat_ID, $inactive_categories))
                                        $status = 'inactive';

                                    // check category icon path
                                    $icon_path = '';
                                    if (is_array($categories_details)) {

                                        if (array_key_exists($category->cat_ID, $categories_details)) {

                                            if (is_array($categories_details[$category->cat_ID])) {

                                                if (array_key_exists('icon', $categories_details[$category->cat_ID])) {

                                                    $icon_path = $categories_details[$category->cat_ID]['icon'];

                                                    if ($icon_path != ''){
                                                        if (!file_exists(WMP_FILES_UPLOADS_DIR . $icon_path))
                                                            $icon_path = '';
                                                        else
                                                            $icon_path = WMP_FILES_UPLOADS_URL . $icon_path;
                                                    }
                                                }
                                            }
                                        }
                                    }
                            ?>
                        	<li data-category-id="<?php echo $category->cat_ID;?>" data-order="<?php echo $key;?>">
                                <div class="row">
                                    <span class="status <?php echo $status;?>"><?php echo $status;?></span>
                                    <span class="pic <?php echo $icon_path == '' ? 'default' : ''?>" <?php if ($icon_path != ''):?>style="background-image: url(<?php echo $icon_path;?>);"<?php endif;?>></span>
                                    <span class="title"><?php echo $category->name;?></span>
                                    <span class="posts"><?php echo $category->category_count != 1 ? $category->category_count.' posts' : '1 post';?> published</span>
                                </div>
                                <div class="buttons">
                                    <a href="<?php echo admin_url('admin.php?page=wmp-category-details&id='.$category->cat_ID);?>" class="edit" title="Edit category for mobile"></a>
                                </div>
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
            <div class="details" id="editpages_container">
                <div class="spacer-10"></div>
                <h2 class="title">Pages</h2>
           		<div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <p>Choose what pages you want to display on your mobile web application. You can edit, show or hide different pages. The Page Order can be set from the <a href="<?php echo add_query_arg(array('post_type'=>'page'), network_admin_url('edit.php'));?>">'Pages' administrative panel</a>.</p>
                <p><strong>Please note that deactivating a parent page will also hide its child pages on the mobile version.</strong></p>

                <div class="spacer-20"></div>
                <!-- start pages list -->
                <?php if (count($all_pages) > 0): ?>
                
                    <form name="wmp_editpages_form" id="wmp_editpages_form" action="" method="post">
                        
                        <div id="wmp_editpages_warning" class="message-container warning" style="display: <?php echo $inactive_root_pages < $no_root_pages ? 'none' : 'block'?>;">
                            <div class="wrapper">
                                <div class="relative"><a class="close-x"></a></div>
                                <span>You deactivated all your main pages, no content will be displayed in your mobile web app!</span>
                            </div>
                            <div class="spacer-10"></div>
                        </div>

                        <?php

                            /**
                             * Recursive method for displaying the pages tree
                             *
                             * @param $list = Pages tree
                             * @param $level = The level of the page
                             * @param $inactive_pages = Array with inactive pages
                             */
                            function wmp_display_pages_tree($list, $level, $inactive_pages){

                                foreach ($list as $page):

                                    $status = in_array($page['obj']->ID, $inactive_pages) ? 'inactive' : 'active';
                        ?>

                                    <li data-page-id="<?php echo $page['obj']->ID;?>" style="width: <?php echo 100 - $level*5;?>%; margin-left:<?php echo $level * 5;?>%">
                                        <div class="row" >
                                            <span class="status <?php echo $status;?> <?php echo $level == 0 ? 'main-page' : '' ;?>"><?php echo $status;?></span>
                                            <span class="title">
                                                <?php
                                                    for ($i = 0; $i < $level; $i++)
                                                        echo ' — ';

                                                    echo $page['obj']->post_title;
                                                ?>
                                            </span>
                                        </div>
                                        <div class="buttons">
                                            <a href="<?php echo admin_url('admin.php?page=wmp-page-details&id='.$page['obj']->ID);?>" class="edit" title="Edit page for mobile"></a>
                                            <span class="delete" title="Delete page" style="display: none;"></span>
                                        </div>
                                    </li>
                        <?php
                                    if (!empty($page['child'])):?>
                                        <?php wmp_display_pages_tree($page['child'], $level + 1, $inactive_pages);?>
                        <?php
                                    endif;
                                endforeach;
                            }
                        ?>

                        <ul class="categories pages">
                            <?php wmp_display_pages_tree($pages, 0, $inactive_pages);?>
                        </ul>
                    </form>
                <?php else: ?>
                
                    <div class="message-container warning">
                        <div class="wrapper">
                            <div class="title">
                                <h2 class="underlined">No pages to display!</h2>
                            </div>
                            <span>You don't have any pages to be displayed in your mobile web app!</span> 
                        </div>
                    </div>
                        
                <?php endif;?>
            </div>
        </div>
    
        <div class="right-side">
            <!-- waitlist form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/waitlist.php');?>

            <!-- add feedback form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/feedback.php'); ?>
        </div>
	</div>
</div>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){

            window.WMPJSInterface.add("UI_editcategories","WMP_EDIT_CATEGORIES",{'DOMDoc':window.document}, window);
            window.WMPJSInterface.add("UI_editpages","WMP_EDIT_PAGES",{'DOMDoc':window.document}, window);
        });
    }
</script>