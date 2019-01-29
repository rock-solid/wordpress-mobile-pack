<?php

class PtPwaManifest {

    private $name;

    private $short_name;

    private $description;

    private $icons = [];

    private $theme_color;

    private $background_color;

    private $start_url;

    private $display = "standalone";

    private $orientation = "portrait";
     
    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of short_name
     */ 
    public function getShortName()
    {
        return $this->short_name;
    }

    /**
     * Set the value of short_name
     *
     * @return  self
     */ 
    public function setShortName($short_name)
    {
        $this->short_name = $short_name;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of icons
     */ 
    public function getIcons()
    {
        return $this->icons;
    }

    /**
     * Set the value of icons
     *
     * @return  self
     */ 
    public function setIcons($icons)
    {
        $this->icons = $icons;

        return $this;
    }

    /**
     * Get the value of theme_color
     */ 
    public function getThemeColor()
    {
        return $this->theme_color;
    }

    /**
     * Set the value of theme_color
     *
     * @return  self
     */ 
    public function setThemeColor($theme_color)
    {
        $this->theme_color = $theme_color;

        return $this;
    }

    /**
     * Get the value of background_color
     */ 
    public function getBackgroundColor()
    {
        return $this->background_color;
    }

    /**
     * Set the value of background_color
     *
     * @return  self
     */ 
    public function setBackgroundColor($background_color)
    {
        $this->background_color = $background_color;

        return $this;
    }

    /**
     * Get the value of start_url
     */ 
    public function getStartUrl()
    {
        return $this->start_url;
    }

    /**
     * Set the value of start_url
     *
     * @return  self
     */ 
    public function setStartUrl($start_url)
    {
        $this->start_url = $start_url;

        return $this;
    }

    /**
     * Get the value of display
     */ 
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set the value of display
     *
     * @return  self
     */ 
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }
}

?>

