<?php

class DSRoute extends sfRequestRoute {

	protected $ds = null;


	protected function getDSForParameters($parameters) {
        if (preg_match('/^[0-9]{4}-[0-9]{4}$/',$parameters['campagne'])) {            
            $campagne = $parameters['campagne'];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'campagne', $parameters['campagne']));
        }
        
        if (preg_match('/^[0-9]{6}$/',$parameters['identifiant'])) {            
            $identifiant = $parameters['identifiant'];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'identifiant', $parameters['identifiant']));
        }

        
        $ds = DSClient::getInstance()->findByCampagneAndIdentifiant($campagne,$identifiant);
        if (!$ds) {
            throw new sfError404Exception(sprintf('No DS found with the id "%s" and the campagne "%s".',  $parameters['identifiant'],$parameters['campagne']));
        }
        return $ds;
    }


    public function getDS() {
        if (is_null($this->ds)) {
            $this->ds = $this->getDSForParameters($this->parameters);
        }

        return $this->ds;
    }
}