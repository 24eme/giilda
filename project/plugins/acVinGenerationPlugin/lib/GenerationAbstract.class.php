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

    public static function isRegenerable() {

        return false;
    }
}