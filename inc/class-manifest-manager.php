<?php

class ManifestManager implements Manager {

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
        $fileHelper = new FileHelper();
        return $fileHelper->write_file($_SERVER['DOCUMENT_ROOT'].'/manifest.json', $this->serialize());
    }

    public function read() {
        $fileHelper = new FileHelper();
        return $fileHelper->read_file($_SERVER['DOCUMENT_ROOT'].'/manifest.json');
    }

    /**
     * Get the value of manifest
     */ 
    public function getManifest()
    {
        return $this->manifest;
    }

    /**
     * Set the value of manifest
     *
     * @return  self
     */ 
    public function setManifest($manifest)
    {
        $this->manifest = $manifest;

        return $this;
    }
}

?>