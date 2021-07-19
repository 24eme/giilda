<?php
class DSRoute extends sfObjectRoute implements InterfaceEtablissementRoute {

    protected $doc = null;

    protected function getObjectForParameters($parameters = null) {

        $id = DSClient::TYPE_MODEL."-".$parameters['identifiant']."-".$parameters['date'];
        $this->doc = DSClient::getInstance()->find($id);

        if (!$this->doc) {
            throw new sfError404Exception(sprintf("document %s non trouvé", $id));
        }

        return $this->doc;
    }

    protected function doConvertObjectToArray($object = null) {

        return array("identifiant" => $object->identifiant, 'date' => str_replace('-', '', $object->getDateStock()));
    }

    public function getDS() {
      if (!$this->doc) {
           $this->doc = $this->getObject();
      }
      return $this->doc;
    }

    public function getEtablissement() {
        return $this->getDS()->getEtablissementObject();
    }
}
