<?php

    class PtPwaManifestManager implements PtPwaManager {

        private $manifest;

        public function __construct($manifest) {
            $this->manifest = $manifest;
            $this->manifest->setStartUrl(get_site_url());
        }

        public function serialize() {
            $serializer = new Zumba\JsonSerializer\JsonSerializer();
            return $serializer->serialize($this->manifest);
        }

        public function deserialize($json) {
            $serializer = new Zumba\JsonSerializer\JsonSerializer();
            $this->manifest = $serializer->unserialize($json);
            return $this->manifest;
        }

        public function write() {
            $PtPwaFileHelper = new PtPwaFileHelper();
            return $PtPwaFileHelper->write_file($_SERVER['DOCUMENT_ROOT'] . '/manifest.json', $this->serialize());
        }

        public function read() {
            $PtPwaFileHelper = new PtPwaFileHelper();
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
         * @return  self
         */
        public function setManifest($manifest) {
            $this->manifest = $manifest;

            return $this;
        }
    }
