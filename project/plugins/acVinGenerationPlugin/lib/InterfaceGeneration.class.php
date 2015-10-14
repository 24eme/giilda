<?php

interface InterfaceGeneration
{
    public function __construct(Generation $g, $config = null, $options = null);
    public function generate();

}