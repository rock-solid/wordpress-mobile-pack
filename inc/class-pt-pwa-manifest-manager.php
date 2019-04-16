<?php

    class PtPwaManifestManager implements PtPwaManager {

        private $manifest;

        /**
         * PtPwaManifestManager constructor.
         * @param $manifest
         */
        public function __construct($manifest) {
            $this->manifest = $manifest;
            $this->manifest->setStartUrl(get_site_url());
        }

        /**
         * @return string
         */
        public function serialize() {
            $serializer = new Zumba\JsonSerializer\JsonSerializer();
            return $serializer->serialize($this->manifest);
        }

        /**
         * @param $json
         * @return mixed
         */
        public function deserialize($json) {
            $serializer = new Zumba\JsonSerializer\JsonSerializer();
            $this->manifest = $serializer->unserialize($json);
            return $this->manifest;
        }

        /**
         * @return bool
         */
        public function write() {
            $PtPwaFileHelper = new PtPwaFileHelper();
            if (is_multisite()) {
                return $PtPwaFileHelper->write_file(PWA_FILES_UPLOADS_DIR . '/manifest.json', $this->serialize());
            }
            return $PtPwaFileHelper->write_file($_SERVER['DOCUMENT_ROOT'] . '/manifest.json', $this->serialize());
        }

        /**
         * @return bool|string
         */
        public function read() {
            $PtPwaFileHelper = new PtPwaFileHelper();
            if (is_multisite()) {
                return $PtPwaFileHelper->read_file(PWA_FILES_UPLOADS_DIR . '/manifest.json');
            }
            return $PtPwaFileHelper->read_file($_SERVER['DOCUMENT_ROOT'] . '/manifest.json');
        }

        /**
         * Get the value of manifest
         */
        public function getManifest() {
            $manifestContents = $this->read();
            if (!empty($manifestContents)) {
                $this->setManifest($this->deserialize($manifestContents));
            }

            return $this->manifest;
        }

        /**
         * Set the value of manifest
         *
         * @param $manifest
         * @return  self
         */
        public function setManifest($manifest) {
            $this->manifest = $manifest;

            return $this;
        }
    }
