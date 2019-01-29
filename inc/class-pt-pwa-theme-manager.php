<?php

class PtPwaThemeManager implements PtPwaManager {

    private $theme;

    public function __construct($theme) {
        
        // Add routes to theme.json
        $permalink_structure = get_option( 'permalink_structure' );

        $article_pattern = '/';

        if (!$permalink_structure) {
            $article_pattern =  '/?p=:id';
        } else {
            $param_keys = explode('/', $permalink_structure);
            
            foreach( $param_keys as $param ) {
                switch($param) {
                    case '%category%':
                        $article_pattern = $article_pattern . ':sectionSlug/';
                        break;
                    case '%postname%':
                        $article_pattern = $article_pattern . ':title/';
                        break;
                    case '%post_id%':
                        $article_pattern = $article_pattern . ':id/';
                        break;
                    case '%author%':
                        $article_pattern = $article_pattern . ':author/';
                        break;
                    case '%year%':
                        $article_pattern = $article_pattern . ':year/';
                        break;
                    case '%monthnum%':
                        $article_pattern = $article_pattern . ':month/';
                        break;
                    case '%day%':
                        $article_pattern = $article_pattern . ':day/';
                        break;
                    default: 
                        break;
                }
            }
        }

        $theme->setRoutes(
            array(
                array(
                    "name"      =>  "home",
                    "pattern"   =>  "/",
                    "page"      =>  "index" 
                ),
                array(
                    "name"      =>  "list",
                    "pattern"   =>  "/:sectionSlug",
                    "page"      =>  "index" 
                ),
                array(
                    "name"      =>  "list 2",
                    "pattern"   =>  "/:sectionSlug/:secondSectionSlug",
                    "page"      =>  "index" 
                ),
                array(
                    "name"      =>  "article",
                    "pattern"   =>  "/:articleSlug",
                    "page"      =>  "article" 
                ),
                array(
                    "name"      =>  null,
                    "pattern"   =>  $article_pattern,
                    "page"      =>  "article" 
                ),
            )
        );

        $site_url = get_site_url();

        // Add Host and Manifest URLs
        $theme->setHostUrl($site_url);
        $theme->setManifestUrl($site_url . '/manifest.json');
        $theme->setServiceWorkerUrl($site_url . '/service-worker.js');

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
        return $PtPwaFileHelper->write_file($_SERVER['DOCUMENT_ROOT'].'/theme.json', $this->serialize());
    }

    public function read() {
        $PtPwaFileHelper = new PtPwaFileHelper();
        return $PtPwaFileHelper->read_file($_SERVER['DOCUMENT_ROOT'].'/theme.json');
    }

    /**
     * Get the value of theme
     */ 
    public function getTheme()
    {
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
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }
}

?>