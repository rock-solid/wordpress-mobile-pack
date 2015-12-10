<?php

require_once WMP_PLUGIN_PATH . "libs/scssphp-0.3.0/scss.inc.php";
use Leafo\ScssPhp\Compiler;

if ( ! class_exists( 'WMobilePack_Themes' ) ) {

    /**
     * Overall Themes Management class
     *
     * This class uses the SCSS compiler and it should not be included outside the admin area
     *
     * @todo Test methods from this class separately.
     *
     */
    class WMobilePack_Themes
    {

        /* ----------------------------------*/
        /* Properties						 */
        /* ----------------------------------*/

        public static $allowed_fonts = array(
            'Roboto Light Condensed',
            'Crimson Roman',
            'OpenSans Condensed Light',
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
                'cover' => 1
            )
        );

        /* ----------------------------------*/
        /* Methods							 */
        /* ----------------------------------*/


        /**
         * Compile new css theme file.
         *
         * The method will return false (error) if:
         *
         * - it can't compile the theme because the PHP version is too old
         * - the uploads folder is not writable
         * - the variables SCSS file can't be created
         * - the CSS file can't be compiled
         *
         * @param $theme_timestamp
         *
         * @return array with the following properties:
         * - compiled = (bool) If the theme was successfully compiled
         * - error = An error message
         *
         */
        public function compile_css_file($theme_timestamp)
        {

            $response = array(
                'compiled' => false,
                'error' => false
            );

            if (version_compare(PHP_VERSION, '5.3') < 0){

                $response['error'] = 'Can\'t compile the theme, PHP5.3 or newer is required!';

            } elseif (!is_writable(WMP_FILES_UPLOADS_DIR)){

                $response['error'] = "Error uploading theme files, the upload folder ".WMP_FILES_UPLOADS_DIR." is not writable.";

            } else {

                // write scss file with the colors and fonts variables
                $generated_vars_scss = $this->generate_variables_file($error);

                if ($generated_vars_scss) {

                    // compile css
                    $response['compiled'] = $this->generate_css_file($theme_timestamp, $error);

                    // cleanup variables file
                    $this->remove_variables_file();
                    return $response;
                }
            }

            return $response;
        }


        /**
         * Delete css file
         *
         * @param $theme_timestamp
         */
        public function remove_css_file($theme_timestamp)
        {
            $file_path = WMP_FILES_UPLOADS_DIR.'theme-'.$theme_timestamp.'.css';

            if (file_exists($file_path))
                unlink($file_path);
        }


        /**
         *
         * Write a scss file with the theme's settings (colors and fonts)
         *
         * @param bool $error
         * @return bool
         *
         */
        protected function generate_variables_file(&$error = false)
        {

            // attempt to open or create the scss file
            $file_path = WMP_FILES_UPLOADS_DIR.'_variables.scss';

            $fp = @fopen($file_path, "w");

            if ($fp !== false) {

                // read theme settings
                $theme = WMobilePack_Options::get_setting('theme');
                $color_scheme = WMobilePack_Options::get_setting('color_scheme');

                if ($color_scheme == 0){
                    $colors = WMobilePack_Options::get_setting('custom_colors');
                } else {
                    $colors = self::$color_schemes[$theme]['presets'][$color_scheme];
                }

                // write fonts
                foreach (array('headlines', 'subtitles', 'paragraphs') as $font_type){

                    $font_setting = WMobilePack_Options::get_setting('font_'.$font_type);
                    $font_family = self::$allowed_fonts[$font_setting-1];

                    fwrite($fp, '$'.$font_type."-font:'".str_replace(" ","",$font_family)."';\r\n");
                }

                // write colors
                foreach (self::$color_schemes[$theme]['vars'] as $key => $var_name){
                    fwrite($fp, '$'.$var_name.":".$colors[$key].";\r\n");
                }

                fclose($fp);
                return true;

            } else {

                $error = "Unable to compile theme, the file ".$file_path." is not writable.";
            }

            return false;
        }


        /**
         *
         * Delete variables scss file
         *
         */
        protected function remove_variables_file()
        {
            $file_path = WMP_FILES_UPLOADS_DIR.'_variables.scss';

            if (file_exists($file_path))
                unlink($file_path);
        }


        /**
         *
         * Generate a CSS file using the variables and theme SCSS files
         *
         * The CSS file is created as 'theme-{$theme_timestamp}.css' in the plugin's uploads folder.
         *
         * @param $theme_timestamp
         * @param bool|string $error
         * @return bool
         *
         */
        protected function generate_css_file($theme_timestamp, &$error = false)
        {

            // attempt to open or create the scss file
            $file_path = WMP_FILES_UPLOADS_DIR.'theme-'.$theme_timestamp.'.css';

            $fp = @fopen($file_path, "w");

            if ($fp !== false) {

                $scss_compiler = new Compiler();

                $scss_compiler->setImportPaths(array(
                    WMP_FILES_UPLOADS_DIR,
                    WMP_PLUGIN_PATH.'frontend/themes/app'.WMobilePack_Options::get_setting('theme').'/scss/'
                ));

                $scss_compiler->setFormatter('scss_formatter_compressed');

                try {

                    // write compiler output directly in the css file
                    $compiled_file = $scss_compiler->compile('@import "_variables.scss"; @import "phone.scss";');

                    fwrite($fp, $compiled_file);

                    fclose($fp);
                    return true;

                } catch (Exception $e){

                    $error = "Unable to compile theme, the theme's scss file contains errors.";
                    fclose($fp);
                }

            } else {
                $error = "Unable to compile theme, the file ".$file_path." is not writable.";
            }

            return false;
        }

    }
}

