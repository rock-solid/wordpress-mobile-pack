<?php

    interface RouteMapper {
        /**
         * @param $permalink_structure
         * @return mixed
         */
        public static function translatePermalinkStructure($permalink_structure);

        /**
         * @param $permalink_structure
         * @return mixed
         */
        public static function includeTrailingSlashes($permalink_structure);

        /**
         * @param $category_prefix
         * @param $article_pattern
         * @param $includeTrailingSlashes
         * @return mixed
         */
        public static function mapRoutes($category_prefix, $article_pattern, $includeTrailingSlashes);
    }
