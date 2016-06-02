<?php

// @todo (Future releases) Find a more efficient way to feed params to the banner script
if (class_exists('WMobilePack')):

    // The mobile web app paths will be set relative to the home url and will deactivate the desktop theme
    $mobile_url = home_url();
    $mobile_url .= parse_url(home_url(), PHP_URL_QUERY) ? '&' : '?';
    $mobile_url .= WMobilePack_Cookie::$prefix.'theme_mode=mobile';

    if (is_single() || is_page() || is_category()){

        if (is_single()){

            // Read inactive categories
            $inactive_categories = WMobilePack_Options::get_setting('inactive_categories');

            // Read post categories
            $post_categories = get_the_category();

            // Check if the post belongs to a visible category
            $visible_category = null;

            foreach ($post_categories as $post_category){

                if (!in_array($post_category->cat_ID, $inactive_categories)) {
                    $mobile_url .= "#article/".get_the_ID();
                    break;
                }
            }

        } elseif (is_page()) {

            $page_id = get_the_ID();
            $inactive_pages = WMobilePack_Options::get_setting('inactive_pages');

            if (!in_array($page_id, $inactive_pages)){
                $mobile_url .= "#page/".$page_id;
            }

        } elseif (is_category()) {

            $category_name = single_cat_title("", false);

            if ($category_name){

                $category_obj = get_term_by('name', $category_name, 'category');

                if ($category_obj && isset($category_obj->slug) && isset($category_obj->term_id) && is_numeric($category_obj->term_id)){

                    $category_id = $category_obj->term_id;

                    // check if the category is active / inactive before displaying it
                    $inactive_categories = WMobilePack_Options::get_setting('inactive_categories');

                    if (!in_array($category_id, $inactive_categories)){
                        $mobile_url .= "#category/".$category_obj->slug.'/'.$category_id;
                    }
                }
            }
        }
    }

    // Load icon from the local settings and folder
    $app_icon_path = '';

    if (class_exists('WMobilePack_Uploads')) {

        $app_icon_path = WMobilePack_Options::get_setting('icon');

        if ($app_icon_path != '') {

            $WMP_Uploads = new WMobilePack_Uploads();
            $app_icon_path = $WMP_Uploads->get_file_url($app_icon_path);
        }
    }

    // Load 'Open' app button translation
    if ( ! class_exists( 'WMobilePack_Export' ) ) {
        require_once(WMP_PLUGIN_PATH.'export/class-export.php');
    }

    $wmp_export = new WMobilePack_Export();
    $wmp_texts_json = $wmp_export->load_language(get_locale(), 'list');

    $open_btn_text = 'Open';
    if ($wmp_texts_json !== false && isset($wmp_texts_json['APP_TEXTS']['LINKS']) && isset($wmp_texts_json['APP_TEXTS']['LINKS']['OPEN_APP'])) {
        $open_btn_text = $wmp_texts_json['APP_TEXTS']['LINKS']['OPEN_APP'];
    }

?>
    <script type="text/javascript" pagespeed_no_defer="">

        var wmpAppBanner = wmpAppBanner || {};
        wmpAppBanner.WIDGET = wmpAppBanner.WIDGET || {};
        wmpAppBanner.WIDGET.appUrl = '<?php echo home_url();?>';
        wmpAppBanner.WIDGET.appIcon = '<?php echo $app_icon_path;?>';
        wmpAppBanner.WIDGET.appName = '<?php echo get_bloginfo("name");?>';
        wmpAppBanner.WIDGET.ref = '<?php echo $mobile_url;?>';
        wmpAppBanner.WIDGET.trustedDevice = 1;
        wmpAppBanner.WIDGET.iframeUrl = '<?php echo plugins_url()."/".WMP_DOMAIN;?>/frontend/sections/smart-app-banner/iframe/bar.html';
        wmpAppBanner.WIDGET.cssPath = '<?php echo plugins_url()."/".WMP_DOMAIN;?>/frontend/sections/smart-app-banner/css/style-light.min.css';
        wmpAppBanner.WIDGET.openAppButton = '<?php echo $open_btn_text;?>';
        wmpAppBanner.WIDGET.cookiePrefix = '<?php echo WMobilePack_Cookie::$prefix;?>';

        (function () {
             var wmp = document.createElement('script');
             wmp.async = true;
             wmp.type = 'text/javascript';
             wmp.src = '<?php echo plugins_url()."/".WMP_DOMAIN;?>/frontend/sections/smart-app-banner/js/smart-app-banner.min.js';
             var node = document.getElementsByTagName('script')[0];
             node.parentNode.insertBefore(wmp, node);
         })();

    </script>
<?php endif; ?>
