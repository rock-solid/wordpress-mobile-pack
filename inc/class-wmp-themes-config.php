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

        public static $color_schemes = array(

            1 => array (
                'labels' => array(
                    'Headlines and primary texts',
                    'Article background',
                    'Article border',
                    'Secondary texts - dates and other messages',
                    'Category label color',
                    'Category text color',
                    'Buttons',
                    'Side menu background',
                    'Form inputs text',
                    'Cover text color'
                ),
                'vars' => array(
                    'base-text-color',
                    'shape-bg-color',
                    'article-border-color',
                    'extra-text-color',
                    'category-color',
                    'category-text-color',
                    'buttons-color',
                    'menu-color',
                    'form-color',
                    'cover-text-color'
                ),
                'presets' => array(
                    1 => array(
                        '#000000',
                        '#ffffff',
                        '#c3c3c3',
                        '#2f2f2f',
                        '#63a9dd',
                        '#ffffff',
                        '#37454c',
                        '#f0f0f0',
                        '#5c5c5c',
                        '#ffffff'
                    ),
                    2 => array(
                        '#ffffff',
                        '#212121',
                        '#6e6e6e',
                        '#eeeeee',
                        '#ff4f64',
                        '#ffffff',
                        '#63a9dd',
                        '#40454a',
                        '#ededed',
                        '#ffffff'
                    ),
                    3 => array(
                        '#4d3c2c',
                        '#f5e4d2',
                        '#cba37d',
                        '#655547',
                        '#f18a2e',
                        '#ffffff',
                        '#75ae62',
                        '#dfccb8',
                        '#f9efe4',
                        '#ffffff'
                    )
                ),
                'cover' => 1,
                'posts_per_page' => 1
            )
        );


		/**
		* Get the application's background color for the app manifest.
		*
		* @param int or null $color_scheme
		* @return string or false
		*/
		public static function get_manifest_background($color_scheme = null)
		{

			if ($color_scheme == null){
				$color_scheme = WMobilePack_Options::get_setting('color_scheme');
			}

			switch ($color_scheme) {

				case 0 :
					$custom_colors = WMobilePack_Options::get_setting('custom_colors');

					if (is_array($custom_colors) && isset($custom_colors[1])) {
						return $custom_colors[1];
					}
					break;

				case 1 :
				case 2 :
				case 3 :
					return self::$color_schemes[1]['presets'][$color_scheme][1];
			}

			return false;
		}
    }
}

