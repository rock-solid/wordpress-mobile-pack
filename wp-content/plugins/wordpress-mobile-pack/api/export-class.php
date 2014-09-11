<?php

require_once("../../../../wp-config.php");


class Export {
 
   	
    /* ----------------------------------*/
    /* Methods							 */
    /* ----------------------------------*/

	public function __construct() {
		
				
	}

   
	/**
    * 
    *  - exportSettings method used for the export of the main settings
	*  - this method returns a JSON with the specific content
	*  - ex : 	
	*		{
	*			"color_scheme": "1",
	*			"font_headlines": "Roboto Light Condensed",
	*			"font_subtitles": "Roboto Light Condensed",
	*			"font_paragraphs": "Roboto Light Condensed",
	*			"logo": "",
	*			"icon": "",
	*			"cover": ""
	*		}
	*				
    *
	* - The "Latest" category will be formed from all the visible categories and articles	
    */
	public function exportSettings() {
			
		if (isset($_GET["content"]) && $_GET["content"] == 'exportsettings') {
			
			$arrSettings = array();
			
			$apiKey = '';
			var_dump($_COOKIE);exit();
			if(isset($_COOKIE["apiKey"]) && $_COOKIE['apiKey'] != '' && preg_match('/^[a-zA-Z0-9]+$/', $_COOKIE['apiKey'])) {
				
				$apiKey = $_COOKIE["apiKey"];
			
			
				if(isset($_GET["apiKey"]) && $_GET["apiKey"] == WMobilePack::wmp_get_setting('premium_api_key')) {
				
					if(WMobilePack::wmp_get_setting('premium_active') == 0) {
						
						// check if logo exists
						$logo_path = WMobilePack::wmp_get_setting('logo');					
						if ($logo_path == '' || !file_exists(WMP_FILES_UPLOADS_DIR.$logo_path))
							$logo_path = '';    
						else
							$logo_path = WMP_FILES_UPLOADS_URL.$logo_path;
							
						// check if icon exists
						$icon_path = WMobilePack::wmp_get_setting('icon');					
						if ($icon_path == '' || !file_exists(WMP_FILES_UPLOADS_DIR.$icon_path))
							$icon_path = ''; 
						else
							$icon_path = WMP_FILES_UPLOADS_URL.$icon_path;
							
						// check if cover exists
						$cover_path = WMobilePack::wmp_get_setting('cover');					
						if ($cover_path == '' || !file_exists(WMP_FILES_UPLOADS_DIR.$cover_path))
							$cover_path = ''; 
						else
							$icon_path = WMP_FILES_UPLOADS_URL.$cover_path;
						
						
						// set settings
						$arrSettings = array(
											'color_scheme' => WMobilePack::wmp_get_setting('color_scheme'),
											'font_headlines' => WMobilePack::wmp_get_setting('font_headlines'),
											'font_subtitles' => WMobilePack::wmp_get_setting('font_subtitles'),
											'font_paragraphs' => WMobilePack::wmp_get_setting('font_paragraphs'),
											'logo' => $logo_path,
											'icon' => $icon_path,
											'cover' => $cover_path
										 );
					
						// return json
						return json_encode($arrSettings);
					
					}
				} 
			} 
				
			// by default assume the api key is not valid	
			return '{"error":"The api key provided is not valid."}';
			
		} else
			return '{"error":""}';
	}
	
  } // Export
  


?>