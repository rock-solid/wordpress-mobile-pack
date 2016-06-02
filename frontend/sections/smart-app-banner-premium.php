<?php

if (class_exists('WMobilePack')):

    $smart_app_banner = false;

    // Load config json
    if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != '') {

        $premium_manager = new WMobilePack_Premium();
        $arr_config_premium = $premium_manager->get_premium_config();

        if ($arr_config_premium !== null)  {

            // Check if we have a valid subdomain linked to the Premium theme
            if (isset($arr_config_premium['domain_name']) && filter_var('http://' . $arr_config_premium['domain_name'], FILTER_VALIDATE_URL) &&
                isset($arr_config_premium['smart_app_banner']) && filter_var('http://' . $arr_config_premium['smart_app_banner'], FILTER_VALIDATE_URL)) {

                $mobile_url = "http://" . $arr_config_premium['domain_name'];
                $smart_app_banner = $arr_config_premium['smart_app_banner'];
            }
        }
    }

    // Smart app banner is loaded only for apps with subdomains & smart app banners
    // @todo (Future releases) Load smart app banner for apps without subdomains
    if ($smart_app_banner !== false):

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
                        $mobile_url .= "/#article/".get_the_ID();
                        break;
                    }
                }

            } elseif (is_page()) {

                $page_id = get_the_ID();
                $inactive_pages = WMobilePack_Options::get_setting('inactive_pages');

                if (!in_array($page_id, $inactive_pages)){
                    $mobile_url .= "/#page/".$page_id;
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
                            $mobile_url .= "/#category/".$category_obj->slug.'/'.$category_id;
                        }
                    }
                }
            }
        }
?>
        <script type="text/javascript" pagespeed_no_defer="">
            var appticlesRedirectToMobile = "<?php echo $mobile_url;?>";

            var wmpAppBanner = wmpAppBanner || {};
            wmpAppBanner.WIDGET = wmpAppBanner.WIDGET || {};
            wmpAppBanner.WIDGET.ref = '<?php echo $mobile_url;?>';
        </script>

        <script type="text/javascript">
            (function() {
                var wbz = document.createElement('script');
                wbz.type = 'text/javascript';
                wbz.async = true;
                wbz.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + '<?php echo $smart_app_banner;?>';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(wbz, s); })();
        </script>
<?php
    endif;
endif;
?>
