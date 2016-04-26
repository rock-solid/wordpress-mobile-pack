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

?>
<!-- add nav main menu -->
<nav class="menu">
    <ul>
        <li <?php echo $current_page == 'wmp-options-premium' ? 'class="selected"' : '';?>>
            <a href="<?php echo add_query_arg(array('page'=>'wmp-options-premium'), network_admin_url('admin.php'));?>">PRO Settings</a>
        </li>
        <li <?php echo ($current_page == 'wmp-options-content' || $current_page == 'wmp-page-details' || $current_page == 'wmp-category-details') ? 'class="selected"' : '';?>>
            <a href="<?php echo add_query_arg(array('page'=>'wmp-options-content'), network_admin_url('admin.php'));?>">Content</a>
        </li>
    </ul>
</nav>