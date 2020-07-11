<?php
class SubventionRoute extends sfObjectRoute {

    protected $subvention = null;

    protected function getObjectForParameters($parameters = null) {

        $id = "SUBVENTION-".$parameters['identifiant']."-".$parameters['operation'];
        $this->subvention = SubventionClient::getInstance()->find($id);

        if (!$this->subvention) {
            throw new sfError404Exception(sprintf("document %s non trouvé", $id));
        }

        return $this->subvention;
    }

    protected function doConvertObjectToArray($object = null) {

        return array("identifiant" => $object->getIdentifiant(), 'operation' => $object->operation);
    }

    public function getSubvention() {
      if (!$this->subvention) {
           $this->subvention = $this->getObject();
      }
      return $this->subvention;
    }
}
