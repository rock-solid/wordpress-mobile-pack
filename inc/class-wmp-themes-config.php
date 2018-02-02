<?php

if ( ! class_exists( 'WMobilePack_Themes_Config' ) ) {

    /**
     * Overall Themes Config class
     */
    class WMobilePack_Themes_Config
    {

        /* ----------------------------------*/
        /* Properties						 */
        /* ----------------------------------*/

        public static $allowed_fonts = array(
            'Roboto Light Condensed',
            'Crimson Roman',
            'Open Sans Condensed Light',
            'Roboto Condensed Bold',
            'Roboto Condensed Regular',
            'Roboto Slab Light',
            'Helvetica Neue Light Condensed',
            'Helvetica Neue Bold Condensed',
            'Gotham Book'
        );

        /**
         * Allowed font sizes are float numbers. Their unit measure is 'rem'.
         * @var array
         */
        public static $allowed_fonts_sizes = array(
            array(
                'label' => 'Small',
                'size' => 0.875
            ),
            array(
                'label' => 'Normal',
                'size' => 1
            ),
            array(
                'label' => 'Large',
                'size' => 1.125
            )
        );

		/**
        * Allowed themes.
        * @var array
        */
        protected static $allowed_themes = array(
			2 => 'Obliq V2.0',
        );

		/**
        * Get list with the allowed themes.
        * This method can be modified to dinamically read and allow access to different themes.
        *
        * @return array
        */
        public static function get_allowed_themes()
        {
            return self::$allowed_themes;
        }


		/**
        * Theme config json. Use this only for admin purposes.
        * If the theme param is missing, the method will return the settings of the current selected theme.
        *
        * @param int $theme
        *
        * @return array or false
        */
        public static function get_theme_config($theme = null){

            if ($theme == null){
                $theme = WMobilePack_Options::get_setting('theme');
            }

            $theme_config_path = WMP_PLUGIN_PATH.'frontend/themes/app'.$theme.'/presets.json';

            if (file_exists($theme_config_path)){

                $theme_config = file_get_contents($theme_config_path);
                $theme_config_json = json_decode($theme_config, true);

                if ($theme_config_json && !empty($theme_config_json) &&
                    array_key_exists('vars', $theme_config_json) && is_array($theme_config_json['vars']) &&
                    array_key_exists('labels', $theme_config_json) && is_array($theme_config_json['labels']) &&
                    array_key_exists('presets', $theme_config_json) && is_array($theme_config_json['presets']) &&
					array_key_exists('fonts', $theme_config_json) && is_array($theme_config_json['fonts']) &&
					array_key_exists('cover', $theme_config_json) && is_numeric($theme_config_json['cover']) &&
					array_key_exists('posts_per_page', $theme_config_json) && is_numeric($theme_config_json['posts_per_page'])) {

					return $theme_config_json;
                }
            }

            return false;
        }

		/**
		* Get the application's background color for the app manifest.
		*
		* @param int or null $theme
		* @param int or null $color_scheme
		* @return string or false
		*
		* @todo Update this method to use a separate color variable.
		*/
		public static function get_manifest_background($theme = null, $color_scheme = null)
		{
			if ($theme == null){
                $theme = WMobilePack_Options::get_setting('theme');
            }

			if ($color_scheme == null){
                $color_scheme = WMobilePack_Options::get_setting('color_scheme');
            }

            $background = $theme == 1 ? 1 : 9;

			switch ($color_scheme) {

				case 0 :
					$custom_colors = WMobilePack_Options::get_setting('custom_colors');

					if (is_array($custom_colors) && isset($custom_colors[$background])) {
						return $custom_colors[$background];
					}
					break;

				case 1 :
				case 2 :
				case 3 :
                    $theme_settings = self::get_theme_config($theme);
					return $theme_settings['presets'][$color_scheme][$background];
			}

			return false;
		}
    }
}

