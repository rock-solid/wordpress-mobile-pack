<?php

class PtPwaIcon {

    private $src;

    private $sizes;

    private $type;


    /**
     * Get the value of src
     */ 
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set the value of src
     *
     * @return  self
     */ 
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get the value of sizes
     */ 
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * Set the value of sizes
     *
     * @return  self
     */ 
    public function setSizes($sizes)
    {
        $this->sizes = $sizes;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}