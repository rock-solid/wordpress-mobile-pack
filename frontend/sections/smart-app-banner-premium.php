<?php

if (class_exists('WMobilePack')):

	$smart_app_banner = false;
	$app_name = null;
	$icon_path = '';

	$is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

    // Load config json
    if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != '') {

        $premium_manager = new WMobilePack_Premium();
        $arr_config_premium = $premium_manager->get_premium_config();

        if ($arr_config_premium !== null)  {

            // Check if we have a valid subdomain linked to the Premium theme
            if (isset($arr_config_premium['domain_name']) && filter_var('http://' . $arr_config_premium['domain_name'], FILTER_VALIDATE_URL) &&
				isset($arr_config_premium['smart_app_banner']) && filter_var('http://' . $arr_config_premium['smart_app_banner'], FILTER_VALIDATE_URL) &&
				isset($arr_config_premium['cdn_apps_https']) && filter_var($arr_config_premium['cdn_apps_https'],FILTER_VALIDATE_URL) &&
				isset($arr_config_premium['cdn_apps']) && filter_var($arr_config_premium['cdn_apps'],FILTER_VALIDATE_URL) &&
				isset($arr_config_premium['shorten_url']) && ctype_alnum($arr_config_premium['shorten_url']) &&
				(!isset($arr_config_premium['icon_path']) || $arr_config_premium['icon_path'] == '' || $arr_config_premium['icon_path'] == strip_tags($arr_config_premium['icon_path']))) {

                $mobile_url = "http://" . $arr_config_premium['domain_name'];
				$smart_app_banner = $arr_config_premium['smart_app_banner'];

				$app_name = $arr_config_premium['title'];

				if (isset($arr_config_premium['kit_type']) && $arr_config_premium['kit_type'] == 'wpmp') {
					$app_name = htmlspecialchars_decode(urldecode(strip_tags($app_name)));
				}

				$cdn_apps = ($is_secure ? $arr_config_premium['cdn_apps_https'] : $arr_config_premium['cdn_apps']);

				if (isset($arr_config_premium['icon_path']) && $arr_config_premium['icon_path'] != '') {
					$icon_path = $cdn_apps."/".$arr_config_premium['shorten_url'].'/'.$arr_config_premium['icon_path'];
				}
            }
        }
    }

    // Smart app banner is loaded only for apps with subdomains & smart app banners
    if ($smart_app_banner !== false && $app_name !== null):

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

		$app_url = $mobile_url;
		if (strlen($app_url) > 30) {
			$app_url = substr($app_url, 0, 30).' ... ';
		}
?>
		<link href="<?php echo plugins_url()."/".WMP_DOMAIN;?>/frontend/sections/notification-banner/lib/noty.css" rel="stylesheet">
		<script src="<?php echo plugins_url()."/".WMP_DOMAIN;?>/frontend/sections/notification-banner/lib/noty.min.js" type="text/javascript" pagespeed_no_defer=""></script>
		<script src="<?php echo plugins_url()."/".WMP_DOMAIN;?>/frontend/sections/notification-banner/notification-banner.js" type="text/javascript" pagespeed_no_defer=""></script>

        <script type="text/javascript" pagespeed_no_defer="">
			jQuery(document).ready(function(){

				const wmpIconPath = "<?php echo $icon_path;?>";

				WMPAppBanner.message =
					(wmpIconPath !== '' ? '<img src="<?php echo $icon_path;?>" />' : '') +
					'<p><?php echo $app_name;?><br/> ' +
					'<span><?php echo $app_url;?></span></p>' +
					'<a href="<?php echo $mobile_url;?>"><span>OPEN</span></a>';

				WMPAppBanner.cookiePrefix = "<?php echo WMobilePack_Cookie::$prefix;?>";
				WMPAppBanner.isSecure = <?php echo $is_secure ? "true" : "false";?>;
			});
		</script>
<?php
    endif;
endif;
?>
