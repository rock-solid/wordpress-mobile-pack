<?php

class FileHelper
{

    public function write_file($path, $contents)
    {
        echo $path; die();
        if (file_exists($path)) {
            unlink($path);
        }
        
        $file = fopen($path, "w") or die("Unable to open file!");
        fwrite($file, $contents);
        fclose($myfile);
        return true;

        return false;
    }

    public function read_file($path)
    {
        if (file_exists($path)) {
            $file = fopen($path, "r") or die("Unable to open file!");
            $contents = fread($file, filesize($path));
            fclose($file);
            return $contents;
        }
        return "";
    }
}

?>