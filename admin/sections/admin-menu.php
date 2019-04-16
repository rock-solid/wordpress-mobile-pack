<?php
    // get current screen
    $screen = get_current_screen();

    // set current page
    if ($screen->id !== '') {
        if (strpos($screen->id, "_page_") !== false) {
            $current_page = substr($screen->id, strpos($screen->id, "_page_") + 6);
        } else {
            $current_page = substr($screen->id, strpos($screen->id, "_category_") + 10);
        }
    } else {
        $current_page = '';
    }
?>
<!-- add nav main menu -->
<nav class="menu">
    <ul>
        <li <?php echo $current_page == 'wmp-options' ? 'class="selected"' : ''; ?>>
            <a href="<?php echo add_query_arg(array('page' => 'wmp-options'), admin_url('admin.php')); ?>">Quick Start</a>
        </li>
        <li <?php echo $current_page == 'wmp-options-themes' ? 'class="selected"' : ''; ?>>
            <a href="<?php echo add_query_arg(array('page' => 'wmp-options-themes'), admin_url('admin.php')); ?>">App Themes</a>
        </li>
        <li <?php echo $current_page == 'wmp-options-theme-settings' ? 'class="selected"' : ''; ?>>
            <a href="<?php echo add_query_arg(array('page' => 'wmp-options-theme-settings'), admin_url('admin.php')); ?>">Look & Feel</a>
        </li>
        <li <?php echo ($current_page == 'wmp-options-content' || $current_page == 'wmp-page-details' || $current_page == 'wmp-category-details') ? 'class="selected"' : ''; ?>>
            <a href="<?php echo add_query_arg(array('page' => 'wmp-options-content'), admin_url('admin.php')); ?>">Content</a>
        </li>
        <li <?php echo $current_page == 'wmp-options-settings' ? 'class="selected"' : ''; ?>>
            <a href="<?php echo add_query_arg(array('page' => 'wmp-options-settings'), admin_url('admin.php')); ?>">Settings</a>
        </li>
    </ul>
</nav>
