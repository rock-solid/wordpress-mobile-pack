<?php
// get current screen
$screen = get_current_screen();

// set current page
if ($screen->id !== '') {

    if (strpos($screen->id, "_page_") !== false)
        $current_page = substr($screen->id, strpos($screen->id, "_page_") + 6);
    else
        $current_page = substr($screen->id, strpos($screen->id, "_category_") + 10);

} else
    $current_page = '';

$wpmp_upgrade_pro_link = WMobilePack_Admin::upgrade_pro_link();

?>
<!-- add nav main menu -->
<nav class="menu">
    <ul>
        <li <?php echo $current_page == 'wmp-options' ? 'class="selected"' : '';?>>
            <a href="<?php echo add_query_arg(array('page'=>'wmp-options'), network_admin_url('admin.php'));?>">Quick Start</a>
        </li>
        <li <?php echo $current_page == 'wmp-options-themes' ? 'class="selected"' : '';?>>
            <a href="<?php echo add_query_arg(array('page'=>'wmp-options-themes'), network_admin_url('admin.php'));?>">App Themes</a>
        </li>
		<li <?php echo $current_page == 'wmp-options-theme-settings' ? 'class="selected"' : '';?>>
            <a href="<?php echo add_query_arg(array('page'=>'wmp-options-theme-settings'), network_admin_url('admin.php'));?>">Look & Feel</a>
        </li>
        <li <?php echo ($current_page == 'wmp-options-content' || $current_page == 'wmp-page-details' || $current_page == 'wmp-category-details') ? 'class="selected"' : '';?>>
            <a href="<?php echo add_query_arg(array('page'=>'wmp-options-content'), network_admin_url('admin.php'));?>">Content</a>
        </li>
        <li <?php echo $current_page == 'wmp-options-settings' ? 'class="selected"' : '';?>>
            <a href="<?php echo add_query_arg(array('page'=>'wmp-options-settings'), network_admin_url('admin.php'));?>">Settings</a>
        </li>
        <li <?php echo $current_page == 'wmp-options-pro' ? 'class="selected"' : '';?>>
			<?php if ($wpmp_upgrade_pro_link) :?>
				<a href="<?php echo esc_attr($wpmp_upgrade_pro_link);?>" target="_blank">
					PRO
					<div class="ribbon">
						<div class="indicator"></div>
					</div>
				</a>
			<?php else: ?>
				<a href="<?php echo add_query_arg(array('page'=>'wmp-options-pro'), network_admin_url('admin.php'));?>">
					PRO
					<div class="ribbon">
						<div class="indicator"></div>
					</div>
				</a>
			<?php endif; ?>
        </li>
    </ul>
</nav>
