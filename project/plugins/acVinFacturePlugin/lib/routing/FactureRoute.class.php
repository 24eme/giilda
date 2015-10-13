<?php
class FactureRoute extends sfObjectRoute {

    protected $facture = null;

    protected function getObjectForParameters($parameters) {
        var_dump($parameters['id']); exit;
        $this->facture = FactureClient::getInstance()->find($parameters['id']);
        if (!$this->facture) {

            throw new sfError404Exception(sprintf('No Facture found with the id "%s".', $parameters['id']));
        }
        return $this->facture;
    }

    protected function doConvertObjectToArray($object) {  
        $parameters = array("id" => $object->_id);
        return $parameters;
    }

    public function getFacture() {
        if (!$this->facture) {
            $this->getObject();
        }
        return $this->facture;
    }

}