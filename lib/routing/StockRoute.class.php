<?php

class FactureRoute extends sfRequestRoute {

	protected $stock = null;


	protected function getStockForParameters($parameters) {

        if (preg_match('/^[0-9]{6}-[0-9]{10}$/', $parameters['identifiant'])) {
            $identifiant = $parameters['identifiant'];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'identifiant', $parameters['identifiant']));
        }

        $stock = StockClient::getInstance()->findByIdentifiant($identifiant);

        if (!$stock) {
            throw new sfError404Exception(sprintf('No Stock found with the id "%s".',  $parameters['identifiant']));
        }
        return $stock;
    }


    public function getStock() {
        if (is_null($this->stock)) {
            $this->stock = $this->getStockForParameters($this->parameters);
        }

        return $this->stock;
    }
}