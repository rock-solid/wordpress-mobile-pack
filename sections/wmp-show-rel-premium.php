<?php

if (class_exists('WMobilePack')):

	if (is_single() || is_page()):
		
		// The mobile web app paths will be set relative to the home url
		$mobile_url = home_url().'/';
		$is_visible = false;
		
		// Load config json
		$json_config_premium = WMobilePack::wmp_set_premium_config(); 
		
		$arrConfig = null;
		if ($json_config_premium !== false) {
			$arrConfig = json_decode($json_config_premium, true);
		}
		
		// Check if we have a valid subdomain linked to the Premium theme
		if (isset($arrConfig['domain_name']) && filter_var('http://'.$arrConfig['domain_name'], FILTER_VALIDATE_URL)) {
			$mobile_url = "http://".$arrConfig['domain_name'].'/';
		}
		
		$permalink = get_permalink();
		
		if (is_single() || (is_page() && !is_front_page())){
			
			$permalink = get_permalink();
			
			if (is_numeric(get_the_ID()) && filter_var($permalink, FILTER_VALIDATE_URL)){
				
				$is_visible = true;
				
				$permalink = rawurlencode($permalink);
				$permalink = str_replace('.','%2E',$permalink);
				
				if (is_single())
					$mobile_url .= '#articleUrl/'.$permalink;
				else
					$mobile_url .= '#pageUrl/'.$permalink;
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