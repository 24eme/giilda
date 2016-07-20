<?php

abstract class GenerationAbstract implements InterfaceGeneration
{
    protected $generation = null;
    protected $config = null;
  
    function __construct(Generation $g, $config = null, $options = null) {
        $this->generation = $g;
        $this->config = $config;
        $this->options = $options;
    }

    protected function publishFile($originpdf, $filename, $extension = '.pdf') {
        $publishname = "/generation/$filename".$extension;
        $publishrealdirname =  "web".$publishname;
        if (!file_exists($originpdf)) 
            throw new sfException("Origin $originpdf doesn't exist");
        if (!rename($originpdf, $publishrealdirname))
            throw new sfException("cannot write $publishrealdirname [rename($originpdf, $publishrealdirname)]");
        return urlencode($publishname);
    }

    public static function isRegenerable() {

        return false;
    }
}