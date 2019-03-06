    <?php

    if (!class_exists('PtPwa_Application')) {

      /**
     *
     * PtPwa_Application
     *
     * Main class for managing frontend apps
     *
     */
      class PtPwa_Application
      {
        /**
         * Class constructor
         */
        public function __construct($plugin_dir)
        {
          // Load application only if the PRO plugin is not active
            $this->check_load();
            $this->plugin_dir = $plugin_dir;

            add_action('rest_api_init', function () {
              remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
              add_filter('rest_pre_serve_request', function ($value) {
              header('Access-Control-Allow-Origin: *');
              header('Access-Control-Allow-Methods: GET');
              header('Access-Control-Allow-Credentials: true');
              header('Access-Control-Expose-Headers: Link', false);
              return $value;
            });
          }, 15);
        }

        /**
         *
         * Method that checks if we can load the mobile web application theme.
         *
         * The theme is loaded if ALL of the following conditions are met:
         *
         * - the user comes from a supported mobile device and browser
         * - the user has not deactivated the view of the mobile theme by switching to desktop mode
         * - the display mode of the app is set to 'normal' or is set to 'preview' and an admin is logged in
         *
         */
        public function check_load()
        {
          // Set app as visible by default
          $Pt_Pwa_Config = new Pt_Pwa_Config();
          $visible_app = $Pt_Pwa_Config->PWA_ENABLED;

          if ($_GET["noapp"] || $_REQUEST["noapp"]) {
            $visible_app = false;
          }

          if (
            strpos($_SERVER['REQUEST_URI'], 'about-us') ||
            strpos($_SERVER['REQUEST_URI'], 'contact-us') ||
            strpos($_SERVER['REQUEST_URI'], 'contact') ||
            strpos($_SERVER['REQUEST_URI'], 'win') ||
            strpos($_SERVER['REQUEST_URI'], 'advertise') ||
            strpos($_SERVER['REQUEST_URI'], 'terms') ||
            strpos($_SERVER['REQUEST_URI'], 'cookie-policy') ||
            strpos($_SERVER['REQUEST_URI'], 'privacy-policy')
          ) {
            $visible_app = false;
          }

          // Assume the app will not be loaded
          $load_app = false;

          if ($visible_app) {
            $load_app = $this->check_device();
          } else {
            $themeManager = new PtPwaThemeManager(new PtPwaTheme());
            $theme = $themeManager->getTheme();

            // The user is shown a button to redirect them back to the app
            if ($theme->getShowClassicSwitch()) {
              add_action('wp_enqueue_scripts', function () {
                wp_enqueue_script('show_classic_switch', $this->plugin_dir . '/frontend/themes/app2/js/classic_switch.js', null, null, false);
              });
            }
          }

          // We have a mobile device and the app is visible, so we can load the app
          if ($load_app) {
            $this->load_app();
          }
        }

        public function show_classic_switch()
        {
          echo "
    <script>
        window.onload = function() {
            function onMobileButtonClick() {
                document.cookie = 'classicCookie=false;';
                location.href = location.href.replace('?noapp=true', '');
            }

            var mobileButton = document.createElement('div');

            mobileButton.textContent = 'Switch to mobile';
            mobileButton.onclick = onMobileButtonClick;
            mobileButton.id = 'classicSwitch';
            mobileButton.style.position = 'fixed';
            mobileButton.style.backgroundColor = '#218CC6';
            mobileButton.style.color = '#FFF';
            mobileButton.style.bottom = '2%';
            mobileButton.style.right = '2%';
            mobileButton.style.padding = '7px';
            mobileButton.style.fontSize = '12px';
            document.body.insertAdjacentElement('beforeend', mobileButton);
        }
    </script>
      ";
        }


        /**
         *
         * Call the mobile detection method to verify if we have a supported device
         *
         * @return bool
         *
         */
        protected function check_device()
        {
          if (!class_exists('PtPwa_Detect')) {
            $Pt_Pwa_Config = new Pt_Pwa_Config();
            require_once $Pt_Pwa_Config->PWA_PLUGIN_PATH . 'frontend/class-detect.php';
          }

          $WMobileDetect = new PtPwa_Detect();
          return $WMobileDetect->detect_device();
        }


        /**
         *
         * Method that loads the mobile web application theme.
         *
         * The theme url and theme name from the WP installation are overwritten by the settings below.
         * Set higher than default priority for filters to ensure these are executed after the ones from the free version.
         */
        public function load_app()
        {
          add_filter("template", array(&$this, "pwa_app"), 11);
          add_filter('theme_root', array(&$this, 'pwa_app_root'), 11);
          add_filter('theme_root_uri', array(&$this, 'pwa_app_root'), 11);
        }


        /**
         * Return the theme name
         */
        public function pwa_app()
        {
          return 'app';
        }


        /**
         * Return path to the mobile themes folder
         */
        public function pwa_app_root()
        {
          $Pt_Pwa_Config = new Pt_Pwa_Config();
          return $Pt_Pwa_Config->PWA_PLUGIN_PATH . 'frontend/pwa';
        }
      }
    }
