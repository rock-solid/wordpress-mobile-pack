<?php

class WPMobileDetect {


	/* ----------------------------------*/
    /* Methods							 */
    /* ----------------------------------*/

	function wmp_load_wurfl(&$requestingDevice) { 
		
		require_once (WMP_WURFL_DIR.'/Application.php');
		
		
		// check if persistence dir is writable and if not change its chmode
		$storageDir = WMP_WURFL_RESOURCES_DIR . "storage";
		$persistenceDir = WMP_WURFL_RESOURCES_DIR . "storage/persistence";
			
		$wurflConfig = new WURFL_Configuration_InMemoryConfig();
		
		// Set location of the WURFL File
		$wurflConfig->wurflFile(WMP_WURFL_RESOURCES_DIR.'/wurfl.zip');
		
		// Set the match mode for the API ('performance' or 'accuracy')
		$wurflConfig->matchMode('performance');
		
		// Automatically reload the WURFL data if it changes
		$wurflConfig->allowReload(false);
		
		// Set Capability Filter
		$wurflConfig->capabilityFilter(
											array(
												"is_tablet",
												"is_wireless_device",
												"is_mobile"
											)
										);
		// Create a WURFL Manager Factory from the WURFL Configuration
		$wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);
		
		// Create a WURFL Manager
		/* @var $wurflManager WURFL_WURFLManager */
		$wurflManager = $wurflManagerFactory->create();
		
		// set requesting device
		$requestingDevice = $wurflManager->getDeviceForHttpRequest($_SERVER);
		
	}
	
	
	function wmp_detect_device() { 
		
		// set load app variable
		$load_app = false;
		
		require_once (WMP_LIBS_DIR.'mobileesp/mdetect.php');
		$uagent_obj = new uagent_info();
		
		$is_tablet = $uagent_obj->DetectTierTablet();
		$is_mobile = $uagent_obj->DetectTierIphone();
		
		if($is_mobile && !$is_tablet) {
			
			// set load app cookie	
			setcookie("load_app", 1, time()+3600*7,'/');
			
			// set load app variable to true
			$load_app = true;	
		}
		
		return $load_app;
		
		/*// load wurfl library
		$this->wmp_load_wurfl($requestingDevice);
		// set variables
		$is_wireless_device = $requestingDevice->getCapability('is_wireless_device');
		$is_tablet = $requestingDevice->getCapability('is_tablet');
		$is_mobile = $requestingDevice->getVirtualCapability('is_mobile');
		// if is mobile device set cookie
		if($is_wireless_device && $is_mobile && !$is_tablet) {
			// set load app cookie	
			setcookie("load_app", 1, time()+3600*7,'/');
			
			// set load app variable to true
			$load_app = true;
		} else
			// set load app cookie	with value 0
			setcookie("load_app", 0, time()+3600*7,'/');
		
		return $load_app;
		exit();*/
	}
	

	function wmp_make_writable($dir) {
		
		if(!file_exists($dir)){
			if (!mkdir($dir, 0777, true)) {//0777
				die('Failed to create folders...');
			}
		
		} else
			if(!chmod($dir,0777))
				die('Failed to create folders...');
	}


	function wmp_is_writable($dir) {
		
		return is_writable($dir);
	}


	
	 
}


