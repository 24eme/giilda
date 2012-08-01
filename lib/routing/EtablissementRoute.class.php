<?php

class EtablissementRoute extends sfObjectRoute {

    protected $etablissement = null;
    
    protected function getObjectForParameters($parameters = null) {
      $this->etablissement = EtablissementClient::getInstance()->findByIdentifiant($parameters['identifiant']);
      return $this->etablissement;
    }

    protected function doConvertObjectToArray($object = null) {
      $this->etablissement = $object;
      return array("identifiant" => $object->getIdentifiant());
    }

    public function getEtablissement() {
      if (!$this->etablissement) {
	$this->etablissement = $this->getObject();
      }
      return $this->etablissement;
    }
}
