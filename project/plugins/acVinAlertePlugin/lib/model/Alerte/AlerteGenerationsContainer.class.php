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
    
    public function __construct() {
        $configs = sfConfig::get('app_alertes_generations');
        
        foreach($configs as $key => $config) {
            $class = $config['class'];
            $this->generations[$key] = new $class();
        }
    }
    
    public function getGenerations() {
        
        return $this->generations;
    }
    
    public function setModeDev($mode) {
       foreach($this->getGenerations() as $generation) {
            $generation->setModeDev($mode);
        } 
    }
    
    public function creations() {
        foreach($this->getGenerations() as $generation) {
            $generation->creations();
        }
    }
    
    public function updates() {
        foreach($this->getGenerations() as $generation) {
            $generation->updates();
        }
    }
}
