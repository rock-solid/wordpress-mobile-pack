<?php
    interface PtPwaManager {
        public function serialize();
        public function deserialize($json);
        public function write();
        public function read();
    }
?>