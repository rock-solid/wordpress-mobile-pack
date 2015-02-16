<?php
// get current screen
$screen = get_current_screen();

// set current page
if($screen->id !== '')
	$current_page = substr($screen->id, strpos($screen->id, "_page_") + 6);
else
	$current_page = '';
	
?>
<!-- add nav main menu -->
<nav class="menu">
    <ul>
        <li <?php echo $current_page == 'wmp-options' ? 'class="selected"' : '';?>>
        	<a href="<?php echo add_query_arg(array('page'=>'wmp-options'), network_admin_url('admin.php'));?>">What's New</a>
        </li>	
        <li <?php echo $current_page == 'wmp-options-theme' ? 'class="selected"' : '';?>>
        	<a href="<?php echo add_query_arg(array('page'=>'wmp-options-theme'), network_admin_url('admin.php'));?>">Look & Feel</a>
        </li>
        <li <?php echo ($current_page == 'wmp-options-content' || $current_page == 'wmp-page-details') ? 'class="selected"' : '';?>>
        	<a href="<?php echo add_query_arg(array('page'=>'wmp-options-content'), network_admin_url('admin.php'));?>">Content</a>
        </li>
        <li <?php echo $current_page == 'wmp-options-settings' ? 'class="selected"' : '';?>>
        	<a href="<?php echo add_query_arg(array('page'=>'wmp-options-settings'), network_admin_url('admin.php'));?>">Settings</a>
        </li>
        <li <?php echo $current_page == 'wmp-options-upgrade' ? 'class="selected"' : '';?>>
        	<div class="ribbon relative">
                <div class="indicator"></div>
            </div> 
            <a href="<?php echo add_query_arg(array('page'=>'wmp-options-upgrade'), network_admin_url('admin.php'));?>">More ...</a>
        </li>
    </ul>
</nav>