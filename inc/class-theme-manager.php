<?php

class ThemeManager {

    private $theme;

    public function __construct($theme) {
        $this->theme = $theme;
    }

    public function serialize() {
        $serializer = new JsonSerializer();
        return $serializer->serialize($this->theme);
    }

    public function deserialize($json) {
        $serializer = new JsonSerializer();
        $serializer->unserialize($json);
    }

    public function write() {
        $fileHelper = new FileHelper();
        return $fileHelper.write_file($_SERVER['DOCUMENT_ROOT'].'/theme.json', $this->serialize());
    }

    public function read() {
        $fileHelper = new FileHelper();
        return $fileHelper.read_file($_SERVER['DOCUMENT_ROOT'].'/theme.json');
    }

    /**
     * Get the value of theme
     */ 
    public function getTheme()
    {
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