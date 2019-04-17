<?php

    interface PtPwaManager {
        /**
         * @return mixed
         */
        public function serialize();

        /**
         * @param $json
         * @return mixed
         */
        public function deserialize($json);

        /**
         * @return mixed
         */
        public function write();

        /**
         * @return mixed
         */
        public function read();
    }
