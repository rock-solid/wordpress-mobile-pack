<?php
    interface Manager {
        public function serialize();
        public function deserialize($json);
        public function write();
        public function read();
    }
?>