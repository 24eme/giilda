<?php

class FactureRoute extends sfRequestRoute {

	protected $ds = null;


	protected function getDSForParameters($parameters) {

        if (preg_match('/^[0-9]{6}-[0-9]{10}$/', $parameters['identifiant'])) {
            $identifiant = $parameters['identifiant'];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'identifiant', $parameters['identifiant']));
        }

        $ds = DSClient::getInstance()->findByIdentifiant($identifiant);

        if (!$ds) {
            throw new sfError404Exception(sprintf('No DS found with the id "%s".',  $parameters['identifiant']));
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