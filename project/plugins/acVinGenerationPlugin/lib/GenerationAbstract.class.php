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
        $interpro = $this->getArgInterpro();
        $publishname = ($interpro && file_exists("web/generation/$interpro"))? "/generation/$interpro/$filename".$extension : "/generation/$filename".$extension;
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

    public function getArgInterpro() {
        $arguments = $this->generation->arguments->toArray();
        $interpro = null;
        if (isset($arguments['interpro'])) {
            $interpro = $arguments['interpro'];
        }
        return $interpro;
    }
}
