<?php

interface RouteMapper {
    public static function translatePermalinkStructure($permalink_structure);
    public static function includeTrailingSlashes($permalink_structure);
    public static function mapRoutes($category_prefix, $article_pattern, $includeTrailingSlashes);
}

?>