<?php

    class PtPwaThemeManager implements PtPwaManager {

        private $theme;

        public function __construct($theme) {

            $Pt_Pwa_Config = new Pt_Pwa_Config();

            // Add routes to theme.json
            $permalink_structure = get_option('permalink_structure');
            $category_prefix = get_option('category_base');
            $article_pattern = PtPwaRouteMapper::translatePermalinkStructure($permalink_structure);
            $includeTrailingSlashes = PtPwaRouteMapper::includeTrailingSlashes($permalink_structure);

            $theme->setRoutes(PtPwaRouteMapper::mapRoutes($category_prefix, $article_pattern, $includeTrailingSlashes));

            $site_url = get_site_url();

            // Add Host and Manifest URLs
            $theme->setHostUrl($site_url);
            $theme->setManifestUrl($site_url . '/manifest.json');
            $theme->setServiceWorkerUrl($site_url . '/service-worker.js');
            $theme->setSectionPrefix($category_prefix);
            $theme->setIncludeTrailingSlashes($includeTrailingSlashes);
            $theme->setLoadingSpinner(plugins_url() . '/' . $Pt_Pwa_Config->PWA_DOMAIN . "/admin/images/ajax-loader.gif");

            $this->theme = $theme;
        }

        public function serialize() {
            $serializer = new Zumba\JsonSerializer\JsonSerializer();
            return $serializer->serialize($this->theme);
        }

        public function deserialize($json) {
            $serializer = new Zumba\JsonSerializer\JsonSerializer();

            $this->theme = $serializer->unserialize($json);
            return $this->theme;
        }

        public function write() {
            $PtPwaFileHelper = new PtPwaFileHelper();
            return $PtPwaFileHelper->write_file($_SERVER['DOCUMENT_ROOT'] . '/theme.json', $this->serialize());
        }

        public function read() {
            $PtPwaFileHelper = new PtPwaFileHelper();
            return $PtPwaFileHelper->read_file($_SERVER['DOCUMENT_ROOT'] . '/theme.json');
        }

        /**
         * Get the value of theme
         */
        public function getTheme() {
            $themeContents = $this->read();
            if (!empty($themeContents)) {

                $this->setTheme($this->deserialize($themeContents));
            }

            return $this->theme;
        }

        /**
         * Set the value of theme
         *
         * @return  self
         */
        public function setTheme($theme) {
            $this->theme = $theme;

            return $this;
        }
    }
