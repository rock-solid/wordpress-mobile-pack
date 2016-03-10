<?php

if (class_exists('WMobilePack')):

    if (is_single() || is_page()):

        // The mobile web app paths will be set relative to the home url
        $mobile_url = home_url().'/';
        $is_visible = false;

        // Load config json
        $premium_manager = new WMobilePack_Premium();
        $arr_config_premium = $premium_manager->get_premium_config();

        // Check if we have a valid subdomain linked to the Premium theme
        if ($arr_config_premium !== null) {

            if (isset($arr_config_premium['domain_name']) && filter_var('http://' . $arr_config_premium['domain_name'], FILTER_VALIDATE_URL)) {
                $mobile_url = "http://" . $arr_config_premium['domain_name'] . '/';
            }

            if (is_single() || (is_page() && !is_front_page())) {

                $permalink = get_permalink();

                if (is_numeric(get_the_ID()) && filter_var($permalink, FILTER_VALIDATE_URL)) {

                    $is_visible = true;

                    $permalink = rawurlencode($permalink);
                    $permalink = str_replace('.', '%2E', $permalink);

                    if (is_single())
                        $mobile_url .= '#articleUrl/' . $permalink;
                    else
                        $mobile_url .= '#pageUrl/' . $permalink;
                }
            }
        }

        if ($is_visible):
?>
            <link rel="alternate" media="only screen and (max-width: 640px)" href="<?php echo $mobile_url;?>" />
<?php
        endif;
    endif;
endif;
?>