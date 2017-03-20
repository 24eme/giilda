<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationsContainer
 * @author mathurin
 */
class AlerteGenerationsContainer {

    protected $generations = array();
    protected $configs = array();

    public function __construct() {
        $this->configs = sfConfig::get('app_alertes_generations');
    }

    public function addAll() {
        foreach($this->configs as $key => $config) {
            $this->add($key);
        }
    }

    public function add($name) {
        if(!isset($this->configs[$name])) {

            throw new sfException(sprintf("Alerte '%s' does not configure in app.yml", $name));
        }
        $class = $this->configs[$name]['class'];
        $this->generations[$name] = new $class();
    }

    public function getGenerations() {

        return $this->generations;
    }

    public function setModeDev($mode) {
       foreach($this->getGenerations() as $generation) {
            $generation->setModeDev($mode);
        }
    }

    public function executeCreations($import = false) {
        foreach($this->getGenerations() as $alert_key => $generation) {
            $generation->executeCreations($import);
        }
    }
    public function executeUpdates() {
        foreach($this->getGenerations() as $alert_key => $generation) {
            $generation->executeUpdates();
        }
    }

}
