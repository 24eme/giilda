<?php
class SocieteRoute extends sfObjectRoute implements InterfaceSocieteRoute {

    protected $societe = null;
    
    protected function getObjectForParameters($parameters = null) {
      $this->societe = SocieteClient::getInstance()->find($parameters['identifiant']);
      return $this->societe;
    }

    protected function doConvertObjectToArray($object = null) {

        return array("identifiant" => $object->getIdentifiant());
    }

    public function getSociete() {
      if (!$this->societe) {
           $this->societe = $this->getObject();
      }
      return $this->societe;
    }
}
